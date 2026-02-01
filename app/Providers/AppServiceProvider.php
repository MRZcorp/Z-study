<?php

namespace App\Providers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
    
 // Kalau belum login / session kosong → kirim default dan STOP
 if (!session()->has('user_id') || !session()->has('nama_role')) {
    $view->with(compact('foto', 'nama', 'role', 'id_user'));
    return;
}





            $userId = session('user_id');
            $roleName = session('nama_role');
    
            if ($roleName === 'dosen') {
                $dosen = Dosen::with('user.role')
                    ->where('user_id', $userId)
                    ->first();
    
                if ($dosen) {
                    $foto = $dosen->poto_profil;
                    $nama = $dosen->user->name ?? null;
                    $role = $dosen->user->role->nama_role ?? null;
                    $id_user = $dosen->nidn ?? null;
                    $email = $dosen->email ?? null;
                    $gelar = $dosen->gelar ?? null;
                    $no_hp = $dosen->no_hp ?? null;
                    
                }
            }
    
            if ($roleName === 'mahasiswa') {
                $mahasiswa = Mahasiswa::with('user.role')
                    ->where('user_id', $userId)
                    ->first();
    
                if ($mahasiswa) {
                    $foto = $mahasiswa->poto_profil;
                    $nama = $mahasiswa->user->name ?? null;
                    $id_user = $mahasiswa->nim ?? null;
                    $fakultas = $mahasiswa->fakultas ?? null;
                    $prodi = $mahasiswa->prodi ?? null;
                    $email = $mahasiswa->email ?? null;
                    $angkatan = $mahasiswa->angkatan ?? null;
                    $profil = '/mahasiswa/profil';
                    $setting = '/mahasiswa/pengaturan';
                    
                }
            }
    
            $view->with(compact('foto', 'nama', 'role','id_user', 
            'fakultas', 'prodi', 'email', 'profil','angkatan','setting' ));
        });

    }
}
