<?php

use App\Models\MateriKelas;
use App\Models\Kelas;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\TugasController;
use App\Models\Tugas;
use Illuminate\Support\Facades\Route;










Route::get('/mahasiswa/materi', [MateriController::class, 'index']);
Route::get('/create', [MateriController::class, 'create']);
Route::post('/mahasiswa/materi', [MateriController::class, 'store']);






Route::get('/', function () {
    return view('home');
});
Route::get('/signup', function () {
    return view('/signup');
});
Route::get('/login', function () {
    return view('/login');
});
Route::get('/about', function () {
    return view('dashboard_mhs');
});




// USER MAHASISWA
Route::get('/mahasiswa', function () {
    return view('/users/mahasiswa');
});
Route::get('/users_setting', function () {
    return view('/users_setting');
});
Route::get('/users_profile', function () {
    return view('/users_profile');
});



Route::get('/mahasiswa', function () {
    return view('/users/mahasiswa', ['nama' => 'M. Zaky Nugraha A.R', 'role' => 'mahasiswa']);
});
Route::get('/mahasiswa/kelas', function () {
    return view('/mahasiswa/kelas', 
    ['pilih_kelas' => Kelas::all()],
     );








});
Route::get('/mahasiswa/materi', function () {
    return view('/mahasiswa/materi', 
    ['materi_kelas' => MateriKelas::all()
]


);
});
Route::get('/mahasiswa/tugas', function () {
    return view('/mahasiswa/tugas', 
 
    ['tugas_kelas' => Tugas::all()]
);

});
Route::get('/mahasiswa/ujian', function () {
    return view('/mahasiswa/ujian');
});
Route::get('/mahasiswa/nilai', function () {
    return view('/mahasiswa/nilai');
});
Route::get('/mahasiswa/diskupesi', function () {
    return view('/mahasiswa/diskupesi');
});
Route::get('/mahasiswa/ngumuman', function () {
    return view('/mahasiswa/pengumuman');
});
Route::get('/mahasiswa/pengaturan', function () {
    return view('/mahasiswa/pengaturan');
});
Route::get('/mahasiswa/bantuan', function () {
    return view('/mahasiswa/bantuan');
});



// USER ADMIN

Route::get('/admin', function () {
    return view('users/admin');
});
Route::get('/admin/kelola_admin', function () {
    return view('/admin/kelola_admin');
});
Route::get('/admin/kelola_dosen', function () {
    return view('/admin/kelola_dosen');
});
Route::get('/admin/kelola_mahasiswa', function () {
    return view('/admin/kelola_mahasiswa');
});
Route::get('/admin/kelola_matakuliah', function () {
    return view('/admin/kelola_matakuliah');
});
Route::get('/admin/kelola_jadwal', function () {
    return view('/admin/kelola_jadwal');
});
Route::get('/admin/kelola_kelas', function () {
    return view('/admin/Kelola_kelas');
});
Route::get('/admin/pengumuman', function () {
    return view('/admin/pengumuman');
});
Route::get('/admin/pengaturan', function () {
    return view('/admin/pengaturan');
});
Route::get('/admin/bantuan', function () {
    return view('/admin/bantuan');
});


//USER DOSEN

Route::get('/dosen', function () {
    return view('users/dosen');
});
Route::get('/dosen/kelas', function () {
    return view('/dosen/kelas');
});
Route::get('/dosen/materi', function () {
    return view('/dosen/materi');
});
Route::get('/dosen/tugas', function () {
    return view('/dosen/tugas');
});
Route::get('/dosen/ujian', function () {
    return view('/dosen/ujian');
});
Route::get('/dosen/koreksi_tugas', function () {
    return view('/dosen/koreksi_tugas');
});
Route::get('/dosen/rekap', function () {
    return view('/dosen/rekap');
});
Route::get('/dosen/pengaturan', function () {
    return view('/dosen/pengaturan');
});
Route::get('/dosen/bantuan', function () {
    return view('/dosen/bantuan');
});
