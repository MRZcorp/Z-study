<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Pengumuman;
use App\Models\MateriKelas;
use App\Models\Tugas;
use App\Models\Ujian;
use App\Models\User;
use App\Models\KrsSetting;
use App\Models\DosenWali;
use App\Models\Mahasiswa;
use App\Models\RekapBobot;
use App\Models\RekapNilai;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DosenController extends Controller
{
    //
    
    public function index() 
        {
            
            $pengumuman = Pengumuman::where('is_active', true)
            ->orderByRaw('COALESCE(tanggal_publish, created_at) DESC')
            ->get();
    
    $poto = Kelas::with('dosens', 'mataKuliah')->latest()->get();

    $userId = session('user_id');
    $dosen = Dosen::with(['user', 'fakultas'])->where('user_id', $userId)->first();
    $dosenId = $dosen?->id;
    $jumlahKelas = Kelas::when($dosenId, fn($q) => $q->where('dosen_id', $dosenId))
        ->where('status', 'aktif')
        ->count();
    $kelasIds = Kelas::when($dosenId, fn($q) => $q->where('dosen_id', $dosenId))
        ->where('status', 'aktif')
        ->pluck('id');
    $totalSks = Kelas::with('mataKuliah')
        ->when($dosenId, fn($q) => $q->where('dosen_id', $dosenId))
        ->where('status', 'aktif')
        ->get()
        ->sum(fn($kelas) => (int) ($kelas->mataKuliah->sks ?? 0));
    $totalMateri = $kelasIds->isNotEmpty()
        ? MateriKelas::whereIn('kelas_id', $kelasIds)->count()
        : 0;
    $totalTugas = $kelasIds->isNotEmpty()
        ? Tugas::whereIn('nama_kelas_id', $kelasIds)->count()
        : 0;
    $totalUjian = $kelasIds->isNotEmpty()
        ? Ujian::whereIn('nama_kelas_id', $kelasIds)->count()
        : 0;

    $hariIni = Carbon::now()->locale('id')->isoFormat('dddd');
    $jadwalKelas = Kelas::with('mataKuliah')
        ->when($dosenId, fn($q) => $q->where('dosen_id', $dosenId))
        ->where('status', 'aktif')
        ->where('hari_kelas', $hariIni)
        ->orderBy('jam_mulai')
        ->get();
    
        $bg = $dosen?->bg;
        $foto = $dosen?->poto_profil;
        $nama = $dosen?->user?->name;
        $id_user = $dosen?->nidn;
        $homebaseFakultas = $dosen?->fakultas?->fakultas ?? $dosen?->fakultas_id ?? '-';

        $krsAktif = KrsSetting::where('status', 'aktif')->latest()->first();
        $tahunAjarAktif = $krsAktif ? ($krsAktif->mulai_tahun_ajar . ' / ' . $krsAktif->akhir_tahun_ajar) : '-';
        $semesterAktif = $krsAktif ? ucfirst($krsAktif->semester) : '-';

        return view('dosen.dashboard', compact(
            'pengumuman',
            'poto',
            'jumlahKelas',
            'totalMateri',
            'totalTugas',
            'totalUjian',
            'jadwalKelas',
            'hariIni',
            'bg',
            'foto',
            'nama',
            'id_user',
            'homebaseFakultas',
            'tahunAjarAktif',
            'semesterAktif',
            'totalSks'
        ));
       

    }
 
    



    public function dosenkelas()
    {
        
    return view('mahasiswa.kelas', [
        'nama_dosen' => Dosen::latest()->get()
    ]);
   
}



    public function admin()
    {
        $dosens = User::with('dosens')->whereHas('role', fn($q) => $q->where('nama_role','dosen'))
        ->get();
      
        return view('admin.data_dosen.index', compact('dosens'));
       
    }

    public function updateBg(Request $request)
    {
        $validated = $request->validate([
            'bg' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $userId = session('user_id');
        $dosen = Dosen::where('user_id', $userId)->first();

        if (!$dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan');
        }

        $path = $request->file('bg')->store('dosen/bg', 'public');

        if ($dosen->bg && $dosen->bg !== $dosen->poto_profil && Storage::disk('public')->exists($dosen->bg)) {
            Storage::disk('public')->delete($dosen->bg);
        }

        $dosen->update(['bg' => $path]);

        return redirect()->back()->with('success', 'Background berhasil diperbarui');
    }

    public function perwalian()
    {
        $userId = session('user_id');
        $dosen = Dosen::where('user_id', $userId)->first();

        if (!$dosen) {
            return view('dosen.perwalian.perwalian', [
                'perwalians' => collect(),
                'semesterAktif' => null,
                'tahunAjarAktif' => null,
            ]);
        }

        $waliPairs = DosenWali::where('dosen_id', $dosen->id)
            ->get(['nama_prodi_id', 'angkatan_id']);

        if ($waliPairs->isEmpty()) {
            return view('dosen.perwalian.perwalian', [
                'perwalians' => collect(),
                'semesterAktif' => null,
                'tahunAjarAktif' => null,
            ]);
        }

        $perwalians = Mahasiswa::with(['user', 'programStudi', 'angkatan', 'kelas.mataKuliah', 'kelas.dosens.user'])
            ->where(function ($q) use ($waliPairs) {
                foreach ($waliPairs as $pair) {
                    $q->orWhere(function ($sub) use ($pair) {
                        $sub->where('nama_prodi_id', $pair->nama_prodi_id)
                            ->where('angkatan_id', $pair->angkatan_id);
                    });
                }
            })
            ->orderBy('id')
            ->get();

        $toBobot = function (float $nilai): float {
            if ($nilai >= 85) return 4.0;
            if ($nilai >= 80) return 3.75;
            if ($nilai >= 75) return 3.5;
            if ($nilai >= 70) return 3.0;
            if ($nilai >= 65) return 2.75;
            if ($nilai >= 60) return 2.5;
            if ($nilai >= 55) return 2.0;
            if ($nilai >= 40) return 1.0;
            return 0.0;
        };

        $perwalians = $perwalians->map(function ($mhs) use ($toBobot) {
            $kelasList = $mhs->kelas ?? collect();

            $semesterOrder = ['ganjil' => 1, 'genap' => 2];
            $semesterItems = $kelasList
                ->filter(fn($k) => in_array($k->status ?? '', ['aktif', 'selesai'], true))
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
                ->map(fn($k) => (($k->tahun_ajar ?? '-') . '|' . ($k->semester ?? '-')))
                ->unique()
                ->values();
            $semesterMap = $semesterItems->mapWithKeys(fn($key, $idx) => [$key => $idx + 1]);
            $semesterDisplay = (int) ($semesterMap->max() ?? 0);

            $kelasSelesai = $kelasList->filter(fn($k) => ($k->status ?? '') === 'selesai')->values();
            $kelasSelesaiIds = $kelasSelesai->pluck('id');

            $ipRows = collect();
            if ($kelasSelesaiIds->isNotEmpty()) {
                $rekapByKelas = RekapNilai::query()
                    ->where('mahasiswa_id', $mhs->id)
                    ->whereIn('kelas_id', $kelasSelesaiIds)
                    ->get()
                    ->keyBy('kelas_id');
                $bobotByKelas = RekapBobot::query()
                    ->whereIn('kelas_id', $kelasSelesaiIds)
                    ->get()
                    ->keyBy('kelas_id');

                $ipRows = $kelasSelesai->map(function ($kelas) use ($rekapByKelas, $bobotByKelas, $semesterMap, $toBobot) {
                    $rekap = $rekapByKelas->get($kelas->id);
                    if (!$rekap) {
                        return null;
                    }

                    $bobot = $bobotByKelas->get($kelas->id);
                    $harianBobot = (float) ($bobot?->harian ?? 15);
                    $keaktifanBobot = (float) ($bobot?->keaktifan ?? 6.25);
                    $kecepatanBobot = (float) ($bobot?->kecepatan ?? 3.75);
                    $absensiBobot = (float) ($bobot?->absensi ?? 5);
                    $utsUasBobot = (float) (($bobot?->uts ?? 30) + ($bobot?->uas ?? 40));

                    $rataTugas = (float) ($rekap->rata_tugas ?? 0);
                    $rataUjian = (float) ($rekap->rata_ujian ?? 0);
                    $nilaiAkhir = ($rataTugas > 0 && $rataUjian > 0)
                        ? (($rataTugas + $rataUjian) / 2)
                        : max($rataTugas, $rataUjian);

                    $speedTugas = (float) ($rekap->rata_kecepatan_tugas ?? 0);
                    $speedUjian = (float) ($rekap->rata_kecepatan_ujian ?? 0);
                    $kecepatan = ($speedTugas > 0 && $speedUjian > 0)
                        ? (($speedTugas + $speedUjian) / 2)
                        : max($speedTugas, $speedUjian);

                    $nilaiTotal =
                        ($nilaiAkhir * $harianBobot) / 100 +
                        ((float) ($rekap->keaktifan ?? 0) * $keaktifanBobot) / 100 +
                        ($kecepatan * $kecepatanBobot) / 100 +
                        ((float) ($rekap->absensi ?? 0) * $absensiBobot) / 100 +
                        ($rataUjian * $utsUasBobot) / 100;

                    $semesterKey = ($kelas->tahun_ajar ?? '-') . '|' . ($kelas->semester ?? '-');
                    return [
                        'semester' => (int) ($semesterMap[$semesterKey] ?? 0),
                        'sks' => (int) ($kelas->mataKuliah?->sks ?? 0),
                        'ip' => $toBobot($nilaiTotal),
                    ];
                })->filter()->values();
            }

            $ipk = (float) ($mhs->ipk ?? 0);
            $ips = (float) ($mhs->ips_terakhir ?? 0);
            if ($ipRows->isNotEmpty()) {
                $totalSks = $ipRows->sum('sks');
                if ($totalSks > 0) {
                    $ipk = round($ipRows->sum(fn($r) => $r['ip'] * $r['sks']) / $totalSks, 2);
                }

                $lastSemester = (int) ($ipRows->max('semester') ?? 0);
                $lastRows = $ipRows->filter(fn($r) => $r['semester'] === $lastSemester);
                $lastSks = $lastRows->sum('sks');
                if ($lastSks > 0) {
                    $ips = round($lastRows->sum(fn($r) => $r['ip'] * $r['sks']) / $lastSks, 2);
                }
            }

            $mhs->semester_display = $semesterDisplay > 0 ? $semesterDisplay : (int) ($mhs->semester_aktif ?? 1);
            $mhs->ips_display = $ips;
            $mhs->ipk_display = $ipk;
            return $mhs;
        });

        $krsAktif = KrsSetting::where('status', 'aktif')->latest()->first();
        $semesterAktif = $krsAktif?->semester ?? null;
        $tahunAjarAktif = $krsAktif ? ($krsAktif->mulai_tahun_ajar . ' / ' . $krsAktif->akhir_tahun_ajar) : null;

        return view('dosen.perwalian.perwalian', compact('perwalians', 'semesterAktif', 'tahunAjarAktif'));
    }

    public function approvePerwalianKelas(Mahasiswa $mahasiswa, Kelas $kelas)
    {
        $userId = session('user_id');
        $dosen = Dosen::where('user_id', $userId)->first();

        if (!$dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan');
        }

        $isWali = DosenWali::where('dosen_id', $dosen->id)
            ->where('nama_prodi_id', $mahasiswa->nama_prodi_id)
            ->where('angkatan_id', $mahasiswa->angkatan_id)
            ->exists();

        if (!$isWali) {
            return redirect()->back()->with('error', 'Anda tidak berhak menyetujui mahasiswa ini');
        }

        $exists = DB::table('kelas_mahasiswa')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('kelas_id', $kelas->id)
            ->exists();

        if (!$exists) {
            return redirect()->back()->with('error', 'Data kelas mahasiswa tidak ditemukan');
        }

        DB::table('kelas_mahasiswa')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('kelas_id', $kelas->id)
            ->where('status', 'menunggu')
            ->update(['status' => 'disetujui']);

        return redirect()->back()->with('success', 'KRS mahasiswa disetujui');
    }

    public function rejectPerwalianKelas(Mahasiswa $mahasiswa, Kelas $kelas)
    {
        $userId = session('user_id');
        $dosen = Dosen::where('user_id', $userId)->first();

        if (!$dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan');
        }

        $isWali = DosenWali::where('dosen_id', $dosen->id)
            ->where('nama_prodi_id', $mahasiswa->nama_prodi_id)
            ->where('angkatan_id', $mahasiswa->angkatan_id)
            ->exists();

        if (!$isWali) {
            return redirect()->back()->with('error', 'Anda tidak berhak menolak mahasiswa ini');
        }

        $exists = DB::table('kelas_mahasiswa')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('kelas_id', $kelas->id)
            ->exists();

        if (!$exists) {
            return redirect()->back()->with('error', 'Data kelas mahasiswa tidak ditemukan');
        }

        DB::table('kelas_mahasiswa')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('kelas_id', $kelas->id)
            ->where('status', 'menunggu')
            ->update(['status' => 'ditolak']);

        return redirect()->back()->with('success', 'KRS mahasiswa ditolak');
    }

    public function resetPerwalianKelas(Mahasiswa $mahasiswa, Kelas $kelas)
    {
        $userId = session('user_id');
        $dosen = Dosen::where('user_id', $userId)->first();

        if (!$dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan');
        }

        $isWali = DosenWali::where('dosen_id', $dosen->id)
            ->where('nama_prodi_id', $mahasiswa->nama_prodi_id)
            ->where('angkatan_id', $mahasiswa->angkatan_id)
            ->exists();

        if (!$isWali) {
            return redirect()->back()->with('error', 'Anda tidak berhak mereset mahasiswa ini');
        }

        $exists = DB::table('kelas_mahasiswa')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('kelas_id', $kelas->id)
            ->exists();

        if (!$exists) {
            return redirect()->back()->with('error', 'Data kelas mahasiswa tidak ditemukan');
        }

        DB::table('kelas_mahasiswa')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('kelas_id', $kelas->id)
            ->delete();

        return redirect()->back()->with('success', 'Kelas berhasil direset, mahasiswa bisa mengajukan lagi');
    }

    public function approveAllPerwalianKelas(Mahasiswa $mahasiswa)
    {
        $userId = session('user_id');
        $dosen = Dosen::where('user_id', $userId)->first();

        if (!$dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan');
        }

        $isWali = DosenWali::where('dosen_id', $dosen->id)
            ->where('nama_prodi_id', $mahasiswa->nama_prodi_id)
            ->where('angkatan_id', $mahasiswa->angkatan_id)
            ->exists();

        if (!$isWali) {
            return redirect()->back()->with('error', 'Anda tidak berhak menyetujui mahasiswa ini');
        }

        DB::table('kelas_mahasiswa')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'menunggu')
            ->update(['status' => 'disetujui']);

        return redirect()->back()->with('success', 'Semua KRS yang menunggu berhasil disetujui');
    }
    


}
