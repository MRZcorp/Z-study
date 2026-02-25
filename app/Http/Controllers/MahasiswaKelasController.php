<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\DosenWali;
use App\Models\MateriKelas;
use App\Models\Tugas;
use App\Models\Ujian;
use App\Models\JawabanMahasiswa;
use App\Models\HasilUjian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MahasiswaKelasController extends Controller
{
    private function buildProfileData(?int $userId): array
    {
        $mahasiswa = $userId ? Mahasiswa::with('programStudi')->where('user_id', $userId)->first() : null;
        $mahasiswaId = $mahasiswa?->id;

        $krsAktif = \App\Models\KrsSetting::where('status', 'aktif')->latest()->first();
        $semesterAktifRaw = $krsAktif?->semester;
        $tahunAjarAktif = $krsAktif ? ($krsAktif->mulai_tahun_ajar . ' / ' . $krsAktif->akhir_tahun_ajar) : '-';
        $semesterAktif = $krsAktif ? ucfirst($krsAktif->semester) : '-';

        $kelasIds = Kelas::query()
            ->when($mahasiswaId, fn($q) => $q->whereHas('mahasiswas', function ($mq) use ($mahasiswaId) {
                $mq->where('mahasiswa_id', $mahasiswaId)
                    ->where('kelas_mahasiswa.status', 'disetujui');
            }))
            ->where('status', 'aktif')
            ->when($semesterAktifRaw, fn($q) => $q->where('semester', $semesterAktifRaw))
            ->pluck('id');

        $approvedKelasAll = Kelas::with('mataKuliah:id,sks')
            ->select(['id', 'tahun_ajar', 'semester', 'mata_kuliah_id', 'status'])
            ->when($mahasiswaId, fn($q) => $q->whereHas('mahasiswas', function ($mq) use ($mahasiswaId) {
                $mq->where('mahasiswa_id', $mahasiswaId)
                    ->where('kelas_mahasiswa.status', 'disetujui');
            }))
            ->whereIn('status', ['aktif', 'selesai'])
            ->get();

        $sksDitempuh = $approvedKelasAll->sum(fn($kelas) => (int) ($kelas->mataKuliah?->sks ?? 0));

        $normalizedTahunAjarAktif = preg_replace('/\s+/', '', (string) $tahunAjarAktif);
        $hasActiveKrsPeriod = !empty($semesterAktifRaw) && !empty($normalizedTahunAjarAktif) && $normalizedTahunAjarAktif !== '-';
        $approvedKelasAktif = $hasActiveKrsPeriod
            ? $approvedKelasAll->filter(function ($k) use ($semesterAktifRaw, $normalizedTahunAjarAktif) {
                $statusMatch = strtolower((string) ($k->status ?? '')) === 'aktif';
                if (!$statusMatch) {
                    return false;
                }
                $semesterMatch = strtolower((string) ($k->semester ?? '')) === strtolower((string) $semesterAktifRaw);
                if (!$semesterMatch) {
                    return false;
                }
                $tahunKelas = preg_replace('/\s+/', '', (string) ($k->tahun_ajar ?? ''));
                return $tahunKelas === $normalizedTahunAjarAktif;
            })
            : collect();

        $sksDiambilSemester = $approvedKelasAktif->sum(fn($kelas) => (int) ($kelas->mataKuliah?->sks ?? 0));

        $jenjang = strtolower((string) ($mahasiswa?->jenjang ?? 's1'));
        $sksMaks = $jenjang === 'd3'
            ? (int) ($mahasiswa?->programStudi?->d3 ?? 0)
            : (int) ($mahasiswa?->programStudi?->s1 ?? 0);

        $dosenWali = null;
        if ($mahasiswa?->nama_prodi_id) {
            $dosenWali = DosenWali::with('dosen.user')
                ->where('nama_prodi_id', $mahasiswa->nama_prodi_id)
                ->orderBy('id')
                ->first();
        }
        $namaDosenWali = $dosenWali?->dosen?->user?->name ?? '-';

        $ipsTerakhir = 0;
        $sksMaksIps = 24;
        $semesterAktifMhs = 1;
        if ($mahasiswaId) {
            $pernahAmbilKelas = DB::table('kelas_mahasiswa')
                ->where('mahasiswa_id', $mahasiswaId)
                ->exists();

            $ipsTerakhir = (float) (Mahasiswa::where('id', $mahasiswaId)->value('ips_terakhir') ?? 0);
            $semesterAktifMhs = (int) (Mahasiswa::where('id', $mahasiswaId)->value('semester_aktif') ?? 1);

            $semesterOrder = ['ganjil' => 1, 'genap' => 2];
            $semesterRiwayat = Kelas::query()
                ->whereHas('mahasiswas', function ($q) use ($mahasiswaId) {
                    $q->where('mahasiswa_id', $mahasiswaId)
                      ->where('kelas_mahasiswa.status', 'disetujui');
                })
                ->whereIn('status', ['aktif', 'selesai'])
                ->get(['tahun_ajar', 'semester'])
                ->sort(function ($a, $b) use ($semesterOrder) {
                    preg_match('/\d{4}/', (string) ($a->tahun_ajar ?? ''), $aMatch);
                    preg_match('/\d{4}/', (string) ($b->tahun_ajar ?? ''), $bMatch);
                    $yearA = (int) ($aMatch[0] ?? 0);
                    $yearB = (int) ($bMatch[0] ?? 0);
                    if ($yearA !== $yearB) {
                        return $yearA <=> $yearB;
                    }
                    $semA = $semesterOrder[strtolower((string) ($a->semester ?? ''))] ?? 99;
                    $semB = $semesterOrder[strtolower((string) ($b->semester ?? ''))] ?? 99;
                    return $semA <=> $semB;
                })
                ->map(fn($k) => (($k->tahun_ajar ?? '-') . '|' . strtolower((string) ($k->semester ?? '-'))))
                ->unique()
                ->values();

            $semesterRiwayatCount = $semesterRiwayat->count();
            if ($semesterRiwayatCount > 0) {
                $semesterAktifMhs = max($semesterAktifMhs, $semesterRiwayatCount);
            }

            if (!$pernahAmbilKelas || $semesterAktifMhs <= 1) {
                $sksMaksIps = 24;
            } elseif ($ipsTerakhir >= 3.0) {
                $sksMaksIps = 24;
            } elseif ($ipsTerakhir >= 2.5) {
                $sksMaksIps = 22;
            } elseif ($ipsTerakhir >= 2.0) {
                $sksMaksIps = 20;
            } elseif ($ipsTerakhir >= 1.5) {
                $sksMaksIps = 18;
            } else {
                $sksMaksIps = 15;
            }
        }

        return [
            'bg' => $mahasiswa?->bg,
            'foto' => $mahasiswa?->poto_profil,
            'nama' => $mahasiswa?->user?->name ?? '-',
            'id_user' => $mahasiswa?->nim ?? '-',
            'fakultas' => $mahasiswa?->fakultas?->nama_fakultas ?? $mahasiswa?->fakultas?->nama ?? $mahasiswa?->fakultas?->kode ?? '-',
            'prodi' => $mahasiswa?->programStudi?->nama_prodi ?? '-',
            'jenjang' => $mahasiswa?->jenjang ?? '-',
            'tahunAjarAktif' => $tahunAjarAktif,
            'semesterAktif' => $semesterAktif,
            'semesterAktifMhs' => $semesterAktifMhs,
            'namaDosenWali' => $namaDosenWali,
            'sksDitempuh' => $sksDitempuh,
            'sksDiambilSemester' => $sksDiambilSemester,
            'sksMaks' => $sksMaks,
            'ipsTerakhir' => $ipsTerakhir,
            'sksMaksIps' => $sksMaksIps,
        ];
    }

    public function index()
    {
        $userId = session('user_id');
        $mahasiswaId = Mahasiswa::where('user_id', $userId)->value('id');

        $pilih_kelas = Kelas::with(['dosens.user', 'mataKuliah', 'mahasiswas.user'])
            ->withCount([
                'mahasiswas as mahasiswas_count' => function ($q) {
                    $q->where('kelas_mahasiswa.status', 'disetujui');
                }
            ])
            ->when($mahasiswaId, fn($q) => $q->whereHas('mahasiswas', function ($mq) use ($mahasiswaId) {
                $mq->where('mahasiswa_id', $mahasiswaId)
                    ->where('kelas_mahasiswa.status', 'disetujui');
            }))
            ->where('status', 'aktif')
            ->latest()
            ->get();

        $profile = $this->buildProfileData($userId);

        return view('mahasiswa.kelas.kelas_saya', array_merge(
            compact('pilih_kelas'),
            $profile
        ));
    }

    public function tersedia()
    {
        $userId = session('user_id');
        $mahasiswa = Mahasiswa::where('user_id', $userId)->first();
        $mahasiswaId = $mahasiswa?->id;
        $isKrsActive = strtolower((string) ($mahasiswa?->status_krs ?? 'nonaktif')) === 'aktif';
        $prodiId = $mahasiswa?->nama_prodi_id;
        $angkatanId = $mahasiswa?->angkatan_id;

        $passedMatkulIds = collect();
        $deMatkulIds = collect();
        if ($mahasiswaId) {
            $nilaiByMatkul = [];

            $tugasRows = Tugas::with('mataKuliah')
                ->whereHas('pengumpulan', function ($q) use ($mahasiswaId) {
                    $q->where('mahasiswa_id', $mahasiswaId)->whereNotNull('nilai');
                })
                ->get();
            foreach ($tugasRows as $tugas) {
                $nilai = $tugas->pengumpulan->first()?->nilai;
                $matkulId = $tugas->mata_kuliah_id;
                if (is_numeric($nilai) && $matkulId) {
                    if (!isset($nilaiByMatkul[$matkulId])) {
                        $nilaiByMatkul[$matkulId] = [];
                    }
                    $nilaiByMatkul[$matkulId][] = (float) $nilai;
                }
            }

            $ujianRows = Ujian::with('mataKuliah')
                ->whereHas('hasilUjian', function ($q) use ($mahasiswaId) {
                    $q->where('mahasiswa_id', $mahasiswaId)->whereNotNull('nilai');
                })
                ->get();
            foreach ($ujianRows as $ujian) {
                $nilai = $ujian->hasilUjian->first()?->nilai;
                $matkulId = $ujian->mata_kuliah_id;
                if (is_numeric($nilai) && $matkulId) {
                    if (!isset($nilaiByMatkul[$matkulId])) {
                        $nilaiByMatkul[$matkulId] = [];
                    }
                    $nilaiByMatkul[$matkulId][] = (float) $nilai;
                }
            }

            $toLetter = function ($nilai) {
                if ($nilai >= 85) return 'A';
                if ($nilai >= 80) return 'A-';
                if ($nilai >= 75) return 'B+';
                if ($nilai >= 70) return 'B';
                if ($nilai >= 65) return 'B-';
                if ($nilai >= 60) return 'C+';
                if ($nilai >= 55) return 'C';
                if ($nilai >= 40) return 'D';
                return 'E';
            };

            $gradeMap = collect($nilaiByMatkul)->map(function ($list) use ($toLetter) {
                    $avg = count($list) ? array_sum($list) / count($list) : 0;
                    return $toLetter($avg);
                });
            $passedMatkulIds = $gradeMap
                ->filter(fn($letter) => !in_array($letter, ['D', 'E'], true))
                ->keys();
            $deMatkulIds = $gradeMap
                ->filter(fn($letter) => in_array($letter, ['D', 'E'], true))
                ->keys();
        }

        $riwayatKelas = Kelas::where('status', 'selesai')
            ->when($mahasiswaId, fn($q) => $q->whereHas('mahasiswas', fn($mq) => $mq->where('mahasiswa_id', $mahasiswaId)))
            ->get();
        $riwayatMatkulIds = $riwayatKelas->pluck('mata_kuliah_id')->filter()->unique();

        if ($mahasiswaId && $riwayatKelas->isNotEmpty()) {
            $kelasIds = $riwayatKelas->pluck('id');
            $rekapMap = \App\Models\RekapNilai::where('mahasiswa_id', $mahasiswaId)
                ->whereIn('kelas_id', $kelasIds)
                ->get()
                ->keyBy('kelas_id');
            $bobotMap = \App\Models\RekapBobot::whereIn('kelas_id', $kelasIds)->get()->keyBy('kelas_id');

            $calcLetter = function ($nilai) use ($toLetter) {
                return $toLetter($nilai);
            };

            $deFromRekap = collect();
            foreach ($riwayatKelas as $kelas) {
                $rekap = $rekapMap->get($kelas->id);
                $bobot = $bobotMap->get($kelas->id);
                $harian = (float) ($bobot?->harian ?? 15);
                $keaktifan = (float) ($bobot?->keaktifan ?? 6.25);
                $kecepatan = (float) ($bobot?->kecepatan ?? 3.75);
                $absensi = (float) ($bobot?->absensi ?? 5);
                $uts = (float) ($bobot?->uts ?? 30);
                $uas = (float) ($bobot?->uas ?? 40);

                $rataTugas = (float) ($rekap?->rata_tugas ?? 0);
                $rataUjian = (float) ($rekap?->rata_ujian ?? 0);
                $nilaiHarian = 0;
                if ($rataTugas > 0 && $rataUjian > 0) {
                    $nilaiHarian = ($rataTugas + $rataUjian) / 2;
                } elseif ($rataTugas > 0) {
                    $nilaiHarian = $rataTugas;
                } elseif ($rataUjian > 0) {
                    $nilaiHarian = $rataUjian;
                }

                $speedTugas = (float) ($rekap?->rata_kecepatan_tugas ?? 0);
                $speedUjian = (float) ($rekap?->rata_kecepatan_ujian ?? 0);
                $nilaiKecepatan = 0;
                if ($speedTugas > 0 && $speedUjian > 0) {
                    $nilaiKecepatan = ($speedTugas + $speedUjian) / 2;
                } elseif ($speedTugas > 0) {
                    $nilaiKecepatan = $speedTugas;
                } elseif ($speedUjian > 0) {
                    $nilaiKecepatan = $speedUjian;
                }

                $nilaiKeaktifan = (float) ($rekap?->keaktifan ?? 0);
                $nilaiAbsensi = (float) ($rekap?->absensi ?? 0);

                $ujianList = \App\Models\Ujian::where('nama_kelas_id', $kelas->id)->get();
                $ujianIds = $ujianList->pluck('id');
                $hasilMap = \App\Models\HasilUjian::where('mahasiswa_id', $mahasiswaId)
                    ->whereIn('ujian_id', $ujianIds)
                    ->pluck('nilai', 'ujian_id');
                $utsVals = [];
                $uasVals = [];
                foreach ($ujianList as $u) {
                    $desc = strtolower(trim((string) ($u->deskripsi ?? '')));
                    $nilai = (float) ($hasilMap[$u->id] ?? 0);
                    if (str_contains($desc, 'uts') || str_contains($desc, 'tengah')) $utsVals[] = $nilai;
                    if (str_contains($desc, 'uas') || str_contains($desc, 'akhir')) $uasVals[] = $nilai;
                }
                $nilaiUts = count($utsVals) ? array_sum($utsVals) / count($utsVals) : 0;
                $nilaiUas = count($uasVals) ? array_sum($uasVals) / count($uasVals) : 0;

                $nilaiTotal =
                    ($nilaiHarian * $harian) / 100 +
                    ($nilaiKeaktifan * $keaktifan) / 100 +
                    ($nilaiKecepatan * $kecepatan) / 100 +
                    ($nilaiAbsensi * $absensi) / 100 +
                    ($nilaiUts * $uts) / 100 +
                    ($nilaiUas * $uas) / 100;

                $letter = $calcLetter($nilaiTotal);
                if (in_array($letter, ['D', 'E'], true)) {
                    $deFromRekap->push($kelas->mata_kuliah_id);
                }
            }
            if ($deFromRekap->isNotEmpty()) {
                $deMatkulIds = $deMatkulIds->merge($deFromRekap)->unique();
            }
        }

        $dosenWaliKontak = null;
        if ($prodiId && $angkatanId) {
            $dosenWaliKontak = DosenWali::with('dosen.user')
                ->where('nama_prodi_id', $prodiId)
                ->where('angkatan_id', $angkatanId)
                ->first();
        }

        $pilih_kelas = Kelas::with([
                'dosens.user',
                'mataKuliah',
                'mahasiswas' => function ($q) use ($mahasiswaId) {
                    if ($mahasiswaId) {
                        $q->where('mahasiswa_id', $mahasiswaId);
                    }
                },
            ])
            ->withCount([
                'mahasiswas as mahasiswas_count' => function ($q) {
                    $q->where('kelas_mahasiswa.status', 'disetujui');
                }
            ])
            ->when($mahasiswaId, fn($q) => $q->whereDoesntHave('mahasiswas', function ($mq) use ($mahasiswaId) {
                $mq->where('mahasiswa_id', $mahasiswaId)
                    ->where('kelas_mahasiswa.status', 'disetujui');
            }))
            ->when($prodiId, fn($q) => $q->whereHas('mataKuliah.programStudis', fn($mq) => $mq->where('program_studis.id', $prodiId)))
            ->when($passedMatkulIds->isNotEmpty(), fn($q) => $q->whereHas('mataKuliah', function ($mq) use ($passedMatkulIds) {
                $mq->whereNotIn('mata_kuliahs.id', $passedMatkulIds);
            }))
            ->when($riwayatMatkulIds->isNotEmpty(), fn($q) => $q->whereHas('mataKuliah', function ($mq) use ($riwayatMatkulIds, $deMatkulIds) {
                $mq->where(function ($sq) use ($riwayatMatkulIds, $deMatkulIds) {
                    $sq->whereNotIn('mata_kuliahs.id', $riwayatMatkulIds);
                    if ($deMatkulIds->isNotEmpty()) {
                        $sq->orWhereIn('mata_kuliahs.id', $deMatkulIds);
                    }
                });
            }))
            ->where('status', 'aktif')
            ->latest()
            ->get();

        $profile = $this->buildProfileData($userId);

        return view('mahasiswa.kelas.kelas_tersedia', array_merge(
            compact('pilih_kelas', 'dosenWaliKontak', 'isKrsActive'),
            $profile
        ));
    }

    public function riwayat()
    {
        $userId = session('user_id');
        $mahasiswaId = Mahasiswa::where('user_id', $userId)->value('id');

        $riwayat_kelas = Kelas::with(['dosens.user', 'mataKuliah', 'mahasiswas.user'])
            ->withCount('mahasiswas')
            ->when($mahasiswaId, fn($q) => $q->whereHas('mahasiswas', fn($mq) => $mq->where('mahasiswa_id', $mahasiswaId)))
            ->where('status', 'selesai')
            ->latest()
            ->get();

        $profile = $this->buildProfileData($userId);

        return view('mahasiswa.kelas.riwayat_kelas', array_merge(
            compact('riwayat_kelas'),
            $profile
        ));
    }

    public function riwayatDetail(Kelas $kelas)
    {
        $userId = session('user_id');
        $mahasiswaId = Mahasiswa::where('user_id', $userId)->value('id');

        $allowed = Kelas::where('id', $kelas->id)
            ->whereHas('mahasiswas', function ($q) use ($mahasiswaId) {
                $q->where('mahasiswa_id', $mahasiswaId)
                    ->where(function ($sq) {
                        $sq->whereNull('kelas_mahasiswa.status')
                            ->orWhere('kelas_mahasiswa.status', 'disetujui');
                    });
            })
            ->exists();

        if (!$allowed) {
            abort(403, 'Akses kelas tidak diizinkan.');
        }

        $materiQuery = MateriKelas::query()
            ->where('kelas_id', $kelas->id)
            ->latest();

        if (request('pertemuan')) {
            $materiQuery->where('pertemuan', request('pertemuan'));
        }

        $materi_kelas = $materiQuery->get();
        $pertemuanHasMateri = MateriKelas::where('kelas_id', $kelas->id)
            ->pluck('pertemuan')
            ->filter()
            ->unique();

        $materi_total_count = MateriKelas::where('kelas_id', $kelas->id)->count();
        $materi_total_pertemuan = MateriKelas::where('kelas_id', $kelas->id)
            ->pluck('pertemuan')
            ->filter()
            ->unique()
            ->count();

        $tugas_selesai = Tugas::with([
                'mataKuliah',
                'kelas',
                'files',
                'pengumpulan' => fn($q) => $q->where('mahasiswa_id', $mahasiswaId),
            ])
            ->where('nama_kelas_id', $kelas->id)
            ->whereNotNull('deadline')
            ->where('deadline', '<', now())
            ->latest()
            ->get();

        $ujian_selesai = Ujian::with([
                'mataKuliah',
                'kelas',
                'soals',
                'hasilUjian' => fn($q) => $q->where('mahasiswa_id', $mahasiswaId),
            ])
            ->where('nama_kelas_id', $kelas->id)
            ->whereNotNull('deadline')
            ->where('deadline', '<', now())
            ->latest()
            ->get();

        $kelas->load(['mataKuliah', 'dosens', 'mahasiswas.user', 'mahasiswas.programStudi']);

        $jawabanMap = [];
        $nilaiMap = [];
        if ($mahasiswaId && $ujian_selesai->isNotEmpty()) {
            $ujianIds = $ujian_selesai->pluck('id');
            $jawabanMap = JawabanMahasiswa::where('mahasiswa_id', $mahasiswaId)
                ->whereIn('ujian_id', $ujianIds)
                ->get()
                ->groupBy('ujian_id')
                ->map(function ($rows) {
                    return $rows->keyBy('soal_id')->map(function ($row) {
                        return [
                            'tipe' => $row->tipe,
                            'jawaban_pg' => $row->jawaban_pg,
                            'jawaban_text' => $row->jawaban_text,
                        ];
                    });
                })
                ->toArray();

            $nilaiMap = HasilUjian::where('mahasiswa_id', $mahasiswaId)
                ->whereIn('ujian_id', $ujianIds)
                ->pluck('nilai', 'ujian_id')
                ->toArray();
        }

        return view('mahasiswa.kelas.riwayat_kelas_detail', compact(
            'kelas',
            'materi_kelas',
            'materi_total_count',
            'materi_total_pertemuan',
            'pertemuanHasMateri',
            'tugas_selesai',
            'ujian_selesai',
            'jawabanMap',
            'nilaiMap'
        ));
    }

    public function ikuti(Request $request, Kelas $kelas)
    {
        $userId = session('user_id');
        $mahasiswa = Mahasiswa::where('user_id', $userId)->first();

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan');
        }

        if (($mahasiswa->status_akademik ?? 'AKTIF') === 'DO') {
            return redirect()->back()->with('error', 'Status akademik DO. Tidak dapat mengikuti kelas.');
        }
        if (strtolower((string) ($mahasiswa->status_krs ?? 'nonaktif')) !== 'aktif') {
            return redirect()->back()->with('error', 'Status KRS nonaktif. Harap konsultasi ke bagian akademik.');
        }

        $kelas->load('mataKuliah');
        if (!empty($kelas->semester)) {
            $semesterAktifNum = (int) ($mahasiswa->semester_aktif ?? 0);
            $semesterAktifLabel = ($semesterAktifNum % 2 === 1) ? 'ganjil' : 'genap';
            if ($semesterAktifNum <= 0 || $semesterAktifLabel !== strtolower((string) $kelas->semester)) {
                return redirect()->back()->with('error', 'Semester aktif tidak sesuai dengan kelas.');
            }
        }

        $currentSks = Kelas::whereHas('mahasiswas', function ($q) use ($mahasiswa) {
                $q->where('mahasiswa_id', $mahasiswa->id)
                  ->where('kelas_mahasiswa.status', 'disetujui');
            })
            ->with('mataKuliah')
            ->get()
            ->sum(fn($k) => (int) ($k->mataKuliah->sks ?? 0));

        $kelasSks = (int) ($kelas->mataKuliah->sks ?? 0);
        $maksSks = (int) ($mahasiswa->maks_sks ?? 24);
        if (($currentSks + $kelasSks) > $maksSks) {
            return redirect()->back()->with('error', 'SKS melebihi batas maksimal.');
        }

        $sudahIkut = $kelas->mahasiswas()->where('mahasiswa_id', $mahasiswa->id)->exists();
        if ($sudahIkut) {
            return redirect()->back()->with('info', 'Kamu sudah mengajukan kelas ini');
        }

        if ($kelas->mahasiswas()->count() >= $kelas->kuota_maksimal) {
            return redirect()->back()->with('error', 'Kuota kelas sudah penuh');
        }

        DB::transaction(function () use ($kelas, $mahasiswa) {
            $kelas->mahasiswas()->attach($mahasiswa->id, ['status' => 'menunggu']);
            $kelas->increment('kuota_terdaftar');
        });

        return redirect()->back()->with('success', 'Permintaan mengikuti kelas telah dikirim');
    }
}
