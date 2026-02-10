<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Tugas;
use App\Models\Ujian;
use App\Models\PengumpulanTugas;
use App\Models\HasilUjian;
use App\Models\RekapNilai;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class NilaiController extends Controller
{
    //
    public function dosen() {
        return view('dosen.nilai');

    }

    public function mahasiswa() {
        $userId = session('user_id');
        $mahasiswaId = Mahasiswa::where('user_id', $userId)->value('id');

        $kelasAll = Kelas::with(['mataKuliah', 'dosens.user'])
            ->when($mahasiswaId, fn($q) => $q->whereHas('mahasiswas', fn($mq) => $mq->where('mahasiswa_id', $mahasiswaId)))
            ->get();

        $kelasSelesai = $kelasAll->where('status', 'selesai')->values();
        $kelasSelesaiIds = $kelasSelesai->pluck('id');

        if ($kelasSelesaiIds->isEmpty()) {
            return view('mahasiswa.nilai', [
                'nilaiRows' => collect(),
                'ipRows' => collect(),
                'ipkValue' => 0,
                'semesterOptions' => [],
                'radarLabels' => ['Kehadiran', 'Keaktifan', 'Tugas', 'Ujian', 'Kecepatan'],
                'radarData' => [0, 0, 0, 0, 0],
            ]);
        }

        $nilaiRows = collect();
        $tugasScores = [];
        $ujianScores = [];

        $kelasAllIds = $kelasAll->pluck('id');

        $tugasSelesai = Tugas::with([
                'mataKuliah',
                'kelas',
                'pengumpulan' => function ($q) use ($mahasiswaId) {
                    if ($mahasiswaId) {
                        $q->where('mahasiswa_id', $mahasiswaId);
                    }
                },
            ])
            ->whereIn('nama_kelas_id', $kelasAllIds)
            ->where(function ($q) use ($mahasiswaId) {
                $q->whereHas('pengumpulan', function ($pq) use ($mahasiswaId) {
                        if ($mahasiswaId) {
                            $pq->where('mahasiswa_id', $mahasiswaId)
                               ->whereNotNull('submitted_at');
                        }
                    })
                  ->orWhere(function ($dq) {
                      $dq->whereNotNull('deadline')
                         ->where('deadline', '<', Carbon::now());
                  });
            })
            ->get();

        $kelasSemesterMap = $kelasAll->mapWithKeys(function ($kelas) {
            $tahun = (string) ($kelas->tahun_ajar ?? '');
            preg_match('/\d{4}/', $tahun, $matches);
            $tahunMulai = (int) ($matches[0] ?? 0);
            return [$kelas->id => [
                'tahun' => $tahunMulai,
                'semester' => $kelas->semester ?? '',
            ]];
        });
        $tahunList = $kelasSemesterMap->pluck('tahun')->filter()->unique()->sort()->values();
        $tahunIndex = $tahunList->flip();

        $mapSemesterNumber = function ($kelasId) use ($kelasSemesterMap, $tahunIndex) {
            $info = $kelasSemesterMap->get($kelasId);
            if (!$info) return null;
            $idx = $tahunIndex[$info['tahun']] ?? null;
            if ($idx === null) return null;
            $base = ($idx * 2) + 1;
            if ($info['semester'] === 'genap') return $base + 1;
            if ($info['semester'] === 'ganjil') return $base;
            return null;
        };

        foreach ($tugasSelesai as $tugas) {
            $pengumpulan = ($tugas->pengumpulan ?? collect())->first();
            $nilai = $pengumpulan->nilai ?? null;
            $submitted = $pengumpulan && $pengumpulan->submitted_at;
            $deadlinePast = $tugas->deadline ? Carbon::parse($tugas->deadline)->isPast() : false;
            $tidakMengumpulkan = !$submitted && $deadlinePast;

            $nilaiLabel = is_null($nilai) ? '-' : $nilai;
            $keterangan = 'Belum Dinilai';
            if ($tidakMengumpulkan) {
                $nilaiLabel = 0;
                $keterangan = 'Tidak Mengumpulkan';
            } elseif (!is_null($nilai)) {
                if ($nilai < 60) {
                    $keterangan = 'Kurang';
                } elseif ($nilai < 70) {
                    $keterangan = 'Cukup';
                } elseif ($nilai < 90) {
                    $keterangan = 'Baik';
                } else {
                    $keterangan = 'Sangat Baik';
                }
            }

            $semesterNum = $mapSemesterNumber($tugas->nama_kelas_id);
            $nilaiRows->push([
                'mata_kuliah_id' => $tugas->mata_kuliah_id ?? null,
                'kode_mata_kuliah' => $tugas->mataKuliah->kode_mata_kuliah ?? null,
                'mata_kuliah' => $tugas->mataKuliah->mata_kuliah ?? '-',
                'sks' => $tugas->mataKuliah->sks ?? null,
                'dosen_pengampu' => $tugas->kelas->dosens->user->name ?? '-',
                'jenis' => 'Tugas',
                'judul' => $tugas->nama_tugas ?? '-',
                'semester' => $semesterNum ?? '-',
                'nilai' => $nilaiLabel,
                'keterangan' => $keterangan,
            ]);
            if (is_numeric($nilaiLabel)) {
                $tugasScores[] = (float) $nilaiLabel;
            }
        }

        $ujianSelesai = Ujian::with([
                'mataKuliah',
                'kelas',
                'hasilUjian' => function ($q) use ($mahasiswaId) {
                    if ($mahasiswaId) {
                        $q->where('mahasiswa_id', $mahasiswaId);
                    }
                },
            ])
            ->whereIn('nama_kelas_id', $kelasAllIds)
            ->whereNotNull('deadline')
            ->where(function ($q) use ($mahasiswaId) {
                $q->where('deadline', '<', Carbon::now())
                  ->orWhereHas('hasilUjian', function ($hq) use ($mahasiswaId) {
                      if ($mahasiswaId) {
                          $hq->where('mahasiswa_id', $mahasiswaId)->whereNotNull('submitted_at');
                      }
                  });
            })
            ->get();

        foreach ($ujianSelesai as $ujian) {
            $hasil = ($ujian->hasilUjian ?? collect())->first();
            $nilai = $hasil->nilai ?? null;
            $submitted = $hasil && $hasil->submitted_at;
            $deadlinePast = $ujian->deadline ? Carbon::parse($ujian->deadline)->isPast() : false;
            $tidakMengumpulkan = !$submitted && $deadlinePast;

            $nilaiLabel = is_null($nilai) ? '-' : $nilai;
            $keterangan = 'Belum Dinilai';
            if ($tidakMengumpulkan) {
                $nilaiLabel = 0;
                $keterangan = 'Tidak Mengumpulkan';
            } elseif (!is_null($nilai)) {
                if ($nilai < 60) {
                    $keterangan = 'Kurang';
                } elseif ($nilai < 70) {
                    $keterangan = 'Cukup';
                } elseif ($nilai < 90) {
                    $keterangan = 'Baik';
                } else {
                    $keterangan = 'Sangat Baik';
                }
            }

            $semesterNum = $mapSemesterNumber($ujian->nama_kelas_id);
            $nilaiRows->push([
                'mata_kuliah_id' => $ujian->mata_kuliah_id ?? null,
                'kode_mata_kuliah' => $ujian->mataKuliah->kode_mata_kuliah ?? null,
                'mata_kuliah' => $ujian->mataKuliah->mata_kuliah ?? '-',
                'sks' => $ujian->mataKuliah->sks ?? null,
                'dosen_pengampu' => $ujian->kelas->dosens->user->name ?? '-',
                'jenis' => 'Ujian',
                'judul' => $ujian->nama_ujian ?? '-',
                'semester' => $semesterNum ?? '-',
                'nilai' => $nilaiLabel,
                'keterangan' => $keterangan,
            ]);
            if (is_numeric($nilaiLabel)) {
                $ujianScores[] = (float) $nilaiLabel;
            }
        }

        $rekapQuery = RekapNilai::where('mahasiswa_id', $mahasiswaId);
        $kehadiran = (float) ($rekapQuery->avg('absensi') ?? 0);
        $keaktifan = (float) ($rekapQuery->avg('keaktifan') ?? 0);

        $tugasAvg = count($tugasScores) ? (array_sum($tugasScores) / count($tugasScores)) : 0;
        $ujianAvg = count($ujianScores) ? (array_sum($ujianScores) / count($ujianScores)) : 0;

        $tugasIds = $tugasSelesai->pluck('id');
        $ujianIds = $ujianSelesai->pluck('id');

        $kecepatanTugas = 0;
        if ($tugasIds->isNotEmpty()) {
            $kecepatanTugas = (float) (PengumpulanTugas::where('mahasiswa_id', $mahasiswaId)
                ->whereIn('tugas_id', $tugasIds)
                ->avg('nilai_kecepatan') ?? 0);
        }

        $kecepatanUjian = 0;
        if ($ujianIds->isNotEmpty() && Schema::hasColumn('hasil_ujians', 'nilai_kecepatan')) {
            $kecepatanUjian = (float) (HasilUjian::where('mahasiswa_id', $mahasiswaId)
                ->whereIn('ujian_id', $ujianIds)
                ->avg('nilai_kecepatan') ?? 0);
        }

        $kecepatanValues = array_filter([$kecepatanTugas, $kecepatanUjian], fn ($v) => $v !== null && $v !== 0.0);
        $kecepatanAvg = count($kecepatanValues) ? (array_sum($kecepatanValues) / count($kecepatanValues)) : 0;

        $radarLabels = ['Kehadiran', 'Keaktifan', 'Tugas', 'Ujian', 'Kecepatan'];
        $radarData = [
            round($kehadiran, 2),
            round($keaktifan, 2),
            round($tugasAvg, 2),
            round($ujianAvg, 2),
            round($kecepatanAvg, 2),
        ];

        $toGrade = function ($nilai) {
            if ($nilai >= 85) return ['huruf' => 'A', 'bobot' => 4.0, 'ket' => 'Sangat Baik / Istimewa'];
            if ($nilai >= 80) return ['huruf' => 'A-', 'bobot' => 3.75, 'ket' => 'Sangat Baik'];
            if ($nilai >= 75) return ['huruf' => 'B+', 'bobot' => 3.5, 'ket' => 'Baik Sekali'];
            if ($nilai >= 70) return ['huruf' => 'B', 'bobot' => 3.0, 'ket' => 'Baik'];
            if ($nilai >= 65) return ['huruf' => 'B-', 'bobot' => 2.75, 'ket' => 'Cukup Baik'];
            if ($nilai >= 60) return ['huruf' => 'C+', 'bobot' => 2.5, 'ket' => 'Cukup'];
            if ($nilai >= 55) return ['huruf' => 'C', 'bobot' => 2.0, 'ket' => 'Cukup / Lulus'];
            if ($nilai >= 40) return ['huruf' => 'D', 'bobot' => 1.0, 'ket' => 'Kurang / Tidak Lulus'];
            return ['huruf' => 'E', 'bobot' => 0.0, 'ket' => 'Gagal'];
        };

        $ipBase = $kelasSelesai
            ->map(function ($kelas) use ($mapSemesterNumber) {
                $semesterNum = $mapSemesterNumber($kelas->id);
                return [
                    'kelas_id' => $kelas->id,
                    'mata_kuliah_id' => $kelas->mata_kuliah_id ?? null,
                    'kode_mata_kuliah' => $kelas->mataKuliah->kode_mata_kuliah ?? null,
                    'mata_kuliah' => $kelas->mataKuliah->mata_kuliah ?? '-',
                    'sks' => $kelas->mataKuliah->sks ?? '-',
                    'dosen_pengampu' => $kelas->dosens->user->name ?? '-',
                    'semester' => $semesterNum ?? '-',
                ];
            })
            ->filter(fn($row) => $row['mata_kuliah_id'] || $row['mata_kuliah'] !== '-')
            ->unique(fn($row) => ($row['mata_kuliah_id'] ?? $row['mata_kuliah']) . '::' . ($row['semester'] ?? '-'))
            ->values();

        $rekapByKelas = RekapNilai::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('kelas_id', $kelasSelesaiIds)
            ->get()
            ->keyBy('kelas_id');

        $bobotByKelas = \App\Models\RekapBobot::whereIn('kelas_id', $kelasSelesaiIds)
            ->get()
            ->keyBy('kelas_id');

        $ipRows = $ipBase->map(function ($row) use ($rekapByKelas, $bobotByKelas, $toGrade, $mahasiswaId) {
            $kelasId = $row['kelas_id'] ?? null;
            $rekap = $kelasId ? $rekapByKelas->get($kelasId) : null;
            $bobot = $kelasId ? $bobotByKelas->get($kelasId) : null;
            $harianBobot = (float) ($bobot?->harian ?? 15);
            $keaktifanBobot = (float) ($bobot?->keaktifan ?? 6.25);
            $kecepatanBobot = (float) ($bobot?->kecepatan ?? 3.75);
            $absensiBobot = (float) ($bobot?->absensi ?? 5);
            $utsBobot = (float) ($bobot?->uts ?? 30);
            $uasBobot = (float) ($bobot?->uas ?? 40);

            $rataTugas = (float) ($rekap?->rata_tugas ?? 0);
            $rataUjian = (float) ($rekap?->rata_ujian ?? 0);
            $nilaiAkhir = 0;
            if ($rataTugas > 0 && $rataUjian > 0) {
                $nilaiAkhir = ($rataTugas + $rataUjian) / 2;
            } elseif ($rataTugas > 0) {
                $nilaiAkhir = $rataTugas;
            } elseif ($rataUjian > 0) {
                $nilaiAkhir = $rataUjian;
            }

            $kecepatan = 0;
            $speedTugas = (float) ($rekap?->rata_kecepatan_tugas ?? 0);
            $speedUjian = (float) ($rekap?->rata_kecepatan_ujian ?? 0);
            if ($speedTugas > 0 && $speedUjian > 0) {
                $kecepatan = ($speedTugas + $speedUjian) / 2;
            } elseif ($speedTugas > 0) {
                $kecepatan = $speedTugas;
            } elseif ($speedUjian > 0) {
                $kecepatan = $speedUjian;
            }

            $keaktifan = (float) ($rekap?->keaktifan ?? 0);
            $absensi = (float) ($rekap?->absensi ?? 0);

            $utsNilai = 0;
            $uasNilai = 0;
            if ($kelasId && $mahasiswaId) {
                $ujianList = \App\Models\Ujian::where('nama_kelas_id', $kelasId)->get();
                $ujianIds = $ujianList->pluck('id');
                $hasilMap = \App\Models\HasilUjian::where('mahasiswa_id', $mahasiswaId)
                    ->whereIn('ujian_id', $ujianIds)
                    ->pluck('nilai', 'ujian_id');

                $getType = function ($desc) {
                    $text = strtolower(trim((string) $desc));
                    if (str_contains($text, 'uts') || str_contains($text, 'tengah')) return 'uts';
                    if (str_contains($text, 'uas') || str_contains($text, 'akhir')) return 'uas';
                    return '';
                };

                $utsVals = [];
                $uasVals = [];
                foreach ($ujianList as $u) {
                    $type = $getType($u->deskripsi ?? '');
                    if (!$type) continue;
                    $nilai = (float) ($hasilMap[$u->id] ?? 0);
                    if ($type === 'uts') $utsVals[] = $nilai;
                    if ($type === 'uas') $uasVals[] = $nilai;
                }
                if (count($utsVals)) $utsNilai = array_sum($utsVals) / count($utsVals);
                if (count($uasVals)) $uasNilai = array_sum($uasVals) / count($uasVals);
            }

            $nilaiTotal =
                ($nilaiAkhir * $harianBobot) / 100 +
                ($keaktifan * $keaktifanBobot) / 100 +
                ($kecepatan * $kecepatanBobot) / 100 +
                ($absensi * $absensiBobot) / 100 +
                ($utsNilai * $utsBobot) / 100 +
                ($uasNilai * $uasBobot) / 100;

            $grade = $toGrade($nilaiTotal);
            return [
                'kode_mata_kuliah' => $row['kode_mata_kuliah'] ?? null,
                'mata_kuliah' => $row['mata_kuliah'] ?? '-',
                'sks' => $row['sks'] ?? '-',
                'dosen_pengampu' => $row['dosen_pengampu'] ?? '-',
                'semester' => $row['semester'] ?? '-',
                'nilai_ip' => $grade['bobot'],
                'nilai_huruf' => $grade['huruf'],
                'keterangan' => $grade['ket'],
            ];
        })->values();

        $totalSksIp = $ipRows->sum(fn($row) => (float) ($row['sks'] ?? 0));
        $totalBobotIp = $ipRows->sum(fn($row) => (float) ($row['nilai_ip'] ?? 0) * (float) ($row['sks'] ?? 0));
        $ipsTerakhir = $totalSksIp > 0 ? round($totalBobotIp / $totalSksIp, 2) : 0;
        $ipkValue = $ipsTerakhir;
        if ($mahasiswaId) {
            $mhs = Mahasiswa::find($mahasiswaId);
            if ($mhs) {
                $semesterAktif = (int) ($mhs->semester_aktif ?? 1);
                $ipsBelowCount = (int) ($mhs->ips_below_2_count ?? 0);
                $ipkBelowCount = (int) ($mhs->ipk_below_2_semester_count ?? 0);

                if ((int) $mhs->last_ips_semester !== $semesterAktif) {
                    if ($ipsTerakhir < 2) {
                        $ipsBelowCount += 1;
                    } else {
                        $mhs->semester_aktif = $semesterAktif + 1;
                    }
                    $mhs->last_ips_semester = $semesterAktif;
                }

                if ($ipkValue < 2 && (int) $mhs->last_ipk_semester !== $semesterAktif) {
                    $ipkBelowCount += 1;
                    $mhs->last_ipk_semester = $semesterAktif;
                }

                $statusAkademik = 'AKTIF';
                if ($ipkValue < 2 && $ipkBelowCount >= 4) {
                    $statusAkademik = 'DO';
                } elseif ($ipsTerakhir < 2 && $ipsBelowCount >= 2) {
                    $statusAkademik = 'PERCOBAAN';
                } elseif ($ipsTerakhir < 2 && $ipsBelowCount >= 1) {
                    $statusAkademik = 'PERINGATAN';
                }

                $mhs->ips_terakhir = $ipsTerakhir;
                $mhs->ipk = $ipkValue;
                $mhs->status_akademik = $statusAkademik;
                $mhs->ips_below_2_count = $ipsBelowCount;
                $mhs->ipk_below_2_semester_count = $ipkBelowCount;
                if ($semesterAktif <= 1) {
                    $mhs->maks_sks = 24;
                } elseif ($ipsTerakhir >= 3.0) {
                    $mhs->maks_sks = 24;
                } elseif ($ipsTerakhir >= 2.5) {
                    $mhs->maks_sks = 22;
                } elseif ($ipsTerakhir >= 2.0) {
                    $mhs->maks_sks = 20;
                } elseif ($ipsTerakhir >= 1.5) {
                    $mhs->maks_sks = 18;
                } else {
                    $mhs->maks_sks = 15;
                }
                $mhs->save();
            }
        }

        $semesterAktif = (int) (Mahasiswa::where('id', $mahasiswaId)->value('semester_aktif') ?? 0);
        $semesterOptions = $semesterAktif > 0
            ? collect(range(1, $semesterAktif))->map(fn($n) => [
                'value' => $n,
                'label' => 'Semester ' . $n . ' ' . ($n % 2 === 1 ? 'Ganjil' : 'Genap'),
              ])->all()
            : [];

        return view('mahasiswa.nilai', [
            'nilaiRows' => $nilaiRows,
            'ipRows' => $ipRows,
            'ipkValue' => $ipkValue,
            'semesterOptions' => $semesterOptions,
            'radarLabels' => $radarLabels,
            'radarData' => $radarData,
        ]);

    }
}
