<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Pengumuman;
use App\Models\Tugas;
use App\Models\Ujian;
use App\Models\User;
use App\Models\KrsSetting;
use App\Models\DosenWali;
use App\Models\RekapNilai;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    //
   

    public function index()
    {
        $pengumuman = Pengumuman::where('is_active', true)
            ->orderByRaw('COALESCE(tanggal_publish, created_at) DESC')
            ->get();

        $userId = session('user_id');
        $mahasiswa = Mahasiswa::with('programStudi')->where('user_id', $userId)->first();
        $mahasiswaId = $mahasiswa?->id;

        $krsAktif = KrsSetting::where('status', 'aktif')->latest()->first();
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

        $jumlahKelas = $kelasIds->count();

        $sksDitempuh = $kelasIds->isNotEmpty()
            ? Kelas::with('mataKuliah')
                ->whereIn('id', $kelasIds)
                ->get()
                ->sum(fn($kelas) => (int) ($kelas->mataKuliah->sks ?? 0))
            : 0;

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

        $tugasAktif = $kelasIds->isNotEmpty()
            ? Tugas::whereIn('nama_kelas_id', $kelasIds)
                ->where(function ($q) {
                    $q->whereNull('deadline')
                      ->orWhere('deadline', '>=', now());
                })
                ->whereDoesntHave('pengumpulan', function ($q) use ($mahasiswaId) {
                    if ($mahasiswaId) {
                        $q->where('mahasiswa_id', $mahasiswaId)
                          ->whereNotNull('submitted_at');
                    }
                })
                ->count()
            : 0;

        $ujianAktif = $kelasIds->isNotEmpty()
            ? Ujian::whereIn('nama_kelas_id', $kelasIds)
                ->where(function ($q) {
                    $q->whereNull('deadline')
                      ->orWhere('deadline', '>=', now());
                })
                ->whereDoesntHave('hasilUjian', function ($q) use ($mahasiswaId) {
                    if ($mahasiswaId) {
                        $q->where('mahasiswa_id', $mahasiswaId)
                          ->whereNotNull('submitted_at');
                    }
                })
                ->count()
            : 0;

        $tugasTerdekat = $kelasIds->isNotEmpty()
            ? Tugas::with(['mataKuliah', 'kelas', 'pengumpulan' => function ($q) use ($mahasiswaId) {
                    if ($mahasiswaId) {
                        $q->where('mahasiswa_id', $mahasiswaId);
                    }
                }])
                ->whereIn('nama_kelas_id', $kelasIds)
                ->where(function ($q) {
                    $q->whereNull('deadline')
                      ->orWhere('deadline', '>=', now());
                })
                ->whereDoesntHave('pengumpulan', function ($q) use ($mahasiswaId) {
                    if ($mahasiswaId) {
                        $q->where('mahasiswa_id', $mahasiswaId)
                          ->whereNotNull('submitted_at');
                    }
                })
                ->orderByRaw('deadline IS NULL, deadline')
                ->limit(5)
                ->get()
            : collect();

        $ujianTerdekat = $kelasIds->isNotEmpty()
            ? Ujian::with(['mataKuliah', 'kelas'])
                ->whereIn('nama_kelas_id', $kelasIds)
                ->where(function ($q) {
                    $q->whereNull('deadline')
                      ->orWhere('deadline', '>=', now());
                })
                ->whereDoesntHave('hasilUjian', function ($q) use ($mahasiswaId) {
                    if ($mahasiswaId) {
                        $q->where('mahasiswa_id', $mahasiswaId)
                          ->whereNotNull('submitted_at');
                    }
                })
                ->orderByRaw('deadline IS NULL, deadline')
                ->limit(5)
                ->get()
            : collect();

        $terdekat = collect();
        foreach ($tugasTerdekat as $t) {
            $terdekat->push([
                'tipe' => 'Tugas',
                'judul' => $t->nama_tugas ?? '-',
                'matkul' => $t->mataKuliah->mata_kuliah ?? '-',
                'deadline' => $t->deadline,
            ]);
        }
        foreach ($ujianTerdekat as $u) {
            $terdekat->push([
                'tipe' => 'Ujian',
                'judul' => $u->nama_ujian ?? '-',
                'matkul' => $u->mataKuliah->mata_kuliah ?? '-',
                'deadline' => $u->deadline,
            ]);
        }
        $terdekat = $terdekat->sortBy(function ($item) {
            return $item['deadline'] ? Carbon::parse($item['deadline'])->timestamp : PHP_INT_MAX;
        })->values();

        $rataNilai = 0;
        if ($mahasiswaId) {
            $rekap = RekapNilai::where('mahasiswa_id', $mahasiswaId)->get();
            $tugasDone = $rekap->where('rata_tugas', '>', 0)->pluck('rata_tugas');
            $ujianDone = $rekap->where('rata_ujian', '>', 0)->pluck('rata_ujian');
            $totalCount = $tugasDone->count() + $ujianDone->count();
            $totalSum = $tugasDone->sum() + $ujianDone->sum();
            $rataNilai = $totalCount > 0 ? round($totalSum / $totalCount, 2) : 0;
        }

        $ipsTerakhir = 0;
        $sksMaksIps = 24;
        $semesterAktifMhs = 1;
        if ($mahasiswaId) {
            $pernahAmbilKelas = DB::table('kelas_mahasiswa')
                ->where('mahasiswa_id', $mahasiswaId)
                ->exists();

            $ipsTerakhir = (float) (Mahasiswa::where('id', $mahasiswaId)->value('ips_terakhir') ?? 0);
            $semesterAktifMhs = (int) (Mahasiswa::where('id', $mahasiswaId)->value('semester_aktif') ?? 1);

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

        return view('mahasiswa.dashboard', compact(
            'pengumuman',
            'jumlahKelas',
            'tugasAktif',
            'ujianAktif',
            'terdekat',
            'sksDitempuh',
            'sksMaks',
            'ipsTerakhir',
            'sksMaksIps',
            'tahunAjarAktif',
            'semesterAktif',
            'semesterAktifMhs',
            'namaDosenWali',
            'rataNilai'
        ));

    }

    public function updateBg(Request $request)
    {
        $validated = $request->validate([
            'bg' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $userId = session('user_id');
        $mahasiswa = Mahasiswa::where('user_id', $userId)->first();

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan');
        }

        $path = $request->file('bg')->store('mahasiswa/bg', 'public');

        if ($mahasiswa->bg && $mahasiswa->bg !== $mahasiswa->poto_profil && Storage::disk('public')->exists($mahasiswa->bg)) {
            Storage::disk('public')->delete($mahasiswa->bg);
        }

        $mahasiswa->update(['bg' => $path]);

        return redirect()->back()->with('success', 'Background berhasil diperbarui');
    }
   


    
    


}
