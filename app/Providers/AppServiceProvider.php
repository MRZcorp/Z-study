<?php

namespace App\Providers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Pengumuman;
use App\Models\PengumumanRead;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        View::composer('*', function ($view) {

            $foto = null;
            $nama = null;
            $email = null;
            $role = null;
            $id_user = null;
            $fakultas = null;
            $prodi = null;
            $profil = null;
            $angkatan = null;
            $setting = null;
            $gelar = null;
            $no_hp = null;
            $bg = null;
            $jenjang = null;
    
 // Kalau belum login / session kosong → kirim default dan STOP
 if (!session()->has('user_id') || !session()->has('nama_role')) {
    $view->with(compact('foto', 'nama', 'role', 'id_user'));
    return;
}





            $userId = session('user_id');
            $roleName = session('nama_role');
            $roleKey = strtolower($roleName ?? '');
    
            if ($roleKey === 'dosen') {
                $dosen = Dosen::with(['user.role', 'fakultas', 'programStudi'])
                    ->where('user_id', $userId)
                    ->first();
    
                if ($dosen) {
                    $foto = $dosen->poto_profil;
                    $bg = $dosen->bg;
                    $nama = $dosen->user->name ?? null;
                    $role = $dosen->user->role->nama_role ?? null;
                    $id_user = $dosen->nidn ?? null;
                    $email = $dosen->email ?? null;
                    $gelar = $dosen->gelar ?? null;
                    $no_hp = $dosen->no_hp ?? null;
                    $fakultas = $dosen->fakultas->fakultas ?? null;
                    $prodi = $dosen->programStudi->nama_prodi ?? null;
                    $profil = '/dosen/profil';
                    $setting = '/dosen/pengaturan';
                    
                }
            }
    
            if ($roleKey === 'mahasiswa') {
                $mahasiswa = Mahasiswa::with(['user.role', 'fakultas', 'programStudi', 'angkatan'])
                    ->where('user_id', $userId)
                    ->first();
    
                if ($mahasiswa) {
                    $foto = $mahasiswa->poto_profil;
                    $bg = $mahasiswa->bg;
                    $nama = $mahasiswa->user->name ?? null;
                    $id_user = $mahasiswa->nim ?? null;
                    $role = $mahasiswa->user->role->nama_role ?? null;
                    $fakultas = $mahasiswa->fakultas->fakultas ?? null;
                    $prodi = $mahasiswa->programStudi->nama_prodi ?? null;
                    $email = $mahasiswa->email ?? null;
                    $no_hp = $mahasiswa->no_hp ?? null;
                    $angkatan = $mahasiswa->angkatan?->tahun ?? null;
                    $jenjang = $mahasiswa->jenjang ?? null;
                    $profil = '/mahasiswa/profil';
                    $setting = '/mahasiswa/pengaturan';
                    
                }
            }

            if ($roleKey === 'admin') {
                $user = User::with('role')->where('id', $userId)->first();
                $dosen = Dosen::with('user.role')->where('user_id', $userId)->first();
                if ($user) {
                    $foto = $dosen?->poto_profil ?? null;
                    $nama = $user->name ?? null;
                    $role = $user->role->nama_role ?? null;
                    $id_user = $dosen?->nidn ?? ($user->username ?? $user->email ?? null);
                    $email = $dosen?->email ?? $user->email ?? null;
                    $profil = '/admin/user_profile';
                    $setting = '/admin/pengaturan';
                }
            }
    
            $shared = compact(
                'foto',
                'nama',
                'role',
                'id_user',
                'fakultas',
                'bg',
                'prodi',
                'email',
                'profil',
                'angkatan',
                'setting',
                'gelar',
                'no_hp',
                'jenjang'
            );

            $navbarPengumuman = Pengumuman::where('is_active', true)
                ->orderByRaw('COALESCE(tanggal_publish, created_at) DESC')
                ->limit(5)
                ->get();
            $navbarPengumumanCount = $navbarPengumuman->count();
            $navbarHasNew = false;
            if ($userId) {
                $navbarHasNew = Pengumuman::where('is_active', true)
                    ->whereNotExists(function ($q) use ($userId) {
                        $q->selectRaw(1)
                          ->from('pengumuman_reads')
                          ->whereColumn('pengumuman_reads.pengumuman_id', 'pengumumen.id')
                          ->where('pengumuman_reads.user_id', $userId);
                    })
                    ->exists();
            }

            $existing = $view->getData();
            foreach ($shared as $key => $value) {
                if (array_key_exists($key, $existing)) {
                    $current = $existing[$key];
                    if ($current !== null && $current !== '') {
                        continue;
                    }
                }

                $view->with($key, $value);
            }

            $view->with('navbarPengumuman', $navbarPengumuman);
            $view->with('navbarPengumumanCount', $navbarPengumumanCount);
            $view->with('navbarHasNew', $navbarHasNew);
        });

    }
}
