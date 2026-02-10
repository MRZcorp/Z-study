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
            ]);
        }

        $waliPairs = DosenWali::where('dosen_id', $dosen->id)
            ->get(['nama_prodi_id', 'angkatan_id']);

        if ($waliPairs->isEmpty()) {
            return view('dosen.perwalian.perwalian', [
                'perwalians' => collect(),
                'semesterAktif' => null,
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

        $krsAktif = KrsSetting::where('status', 'aktif')->latest()->first();
        $semesterAktif = $krsAktif?->semester ?? null;

        return view('dosen.perwalian.perwalian', compact('perwalians', 'semesterAktif'));
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
