<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\BantuanController;
use App\Http\Controllers\DiskusiController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\JadwalKelasController;
use App\Http\Controllers\KelolaKelasController;
use App\Http\Controllers\KelolaUserController;
use App\Http\Controllers\KoreksiTugasController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\RekapNilaiController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\UserProfilController;
use App\Http\Controllers\UserSettingController;
use App\Http\Middleware\RoleMiddleware;






/*
|--------------------------------------------------------------------------
| AUTH (LOGIN)
|--------------------------------------------------------------------------
*/
Route::get('/', [SessionController::class, 'index'])
    ->name('login');

Route::post('/', [SessionController::class, 'authenticate'])
    ->name('login.process');

Route::post('/logout', [SessionController::class, 'logout'])
    ->name('logout');


// Route::resource('/admin', AdminController::class)->middleware('superadmin');


Route::middleware(['auth.session', 'role.session:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])
        ->name('admin.dashboard');
});

Route::middleware(['auth.session', 'role.session:dosen'])->group(function () {
    Route::get('/dosen', [DosenController::class, 'index'])
        ->name('dosen.dashboard');
        Route::get('/dosen/kelas', [KelasController::class, 'dosen'])->name('dosen.kelas');
        Route::get('/dosen/buat_kelas', [KelasController::class, 'dosen1'])->name('dosen.buat_kelas');
        Route::get('/dosen/materi', [MateriController::class, 'dosen']);
        Route::get('/dosen/tugas', [TugasController::class, 'dosen']);
        Route::get('/dosen/ujian', [UjianController::class, 'dosen']);
        Route::get('/dosen/koreksi_tugas', [KoreksiTugasController::class, 'index']);
        Route::get('/dosen/rekap', [RekapNilaiController::class, 'index']);
        Route::get('/dosen/dikusi', [DiskusiController::class, 'dosen']);
        Route::get('/dosen/pengaturan', [UserSettingController::class, 'dosen']);
        Route::get('/dosen/user_profil', [UserProfilController::class, 'dosen']);
        Route::get('/dosen/bantuan', [BantuanController::class, 'dosen']);
        
        
        
        
        
        Route::post('/dosen/kelas', [KelasController::class, 'store'])
            ->name('dosen.kelas');
        
        Route::post('/dosen/materi', [MateriController::class, 'store']);
        



});

Route::middleware(['auth.session', 'role.session:mahasiswa'])->group(function () {
    Route::get('/mahasiswa', [MahasiswaController::class, 'index'])
        ->name('mahasiswa.dashboard');
});







// USER MAHASISWA////////////////////////////////////////////////////
//route get

Route::get('/mahasiswa/kelas', [KelasController::class, 'mahasiswa'])->name('mahasiswa.kelas_saya');
Route::get('/mahasiswa/kelas_tersedia', [KelasController::class, 'mahasiswakelas'])->name('mahasiswa.kelas_tersedia');
Route::get('/mahasiswa/materi', [MateriController::class, 'mahasiswa']);
Route::get('/mahasiswa/tugas', [TugasController::class, 'mahasiswa']);
Route::get('/mahasiswa/ujian', [UjianController::class, 'mahasiswa']);
Route::get('/mahasiswa/nilai', [NilaiController::class, 'mahasiswa']);
Route::get('/mahasiswa/pengaturan', [UserSettingController::class, 'mahasiswa']);
Route::get('/mahasiswa/profil', [UserProfilController::class, 'mahasiswa']);
Route::get('/mahasiswa/diskusi', [DiskusiController::class, 'mahasiswa']);
Route::get('/mahasiswa/bantuan', [BantuanController::class, 'mahasiswa']);

// Route::prefix('mahasiswa')
//     ->name('mahasiswa.')
//     ->group(function () {

//         Route::get('/', [MahasiswaController::class, 'index'])
//             ->name('mahasiswa');

//         Route::get('/kelas', [KelasController::class, 'mahasiswa'])
//             ->name('kelas_saya');

//         Route::get('/kelas-tersedia', [KelasController::class, 'mahasiswakelas'])
//             ->name('kelas_tersedia');

//         Route::get('/materi', [MateriController::class, 'mahasiswa'])
//             ->name('materi');

//         Route::get('/tugas', [TugasController::class, 'mahasiswa'])
//             ->name('tugas');

//         Route::get('/ujian', [UjianController::class, 'mahasiswa'])
//             ->name('ujian');

//         Route::get('/nilai', [NilaiController::class, 'mahasiswa'])
//             ->name('nilai');

//         Route::get('/pengaturan', [UserSettingController::class, 'mahasiswa'])
//             ->name('pengaturan');

//         Route::get('/profil', [UserProfilController::class, 'mahasiswa'])
//             ->name('profil');

//         Route::get('/diskusi', [DiskusiController::class, 'mahasiswa'])
//             ->name('diskusi');

//         Route::get('/bantuan', [BantuanController::class, 'mahasiswa'])
//             ->name('bantuan');
//     });


//route post
Route::post('/mahasiswa/kelas/{kelas}/ikuti', [KelasController::class, 'ikuti'])
    ->name('mahasiswa.kelas.ikuti');








/////// USER DOSEN  /////////////////////
/// route get











/////////// USER ADMIN /////////////////
//Route get










Route::resource('/admin/user_setting', KelolaUserController::class);











Route::get('/admin/kelola_dosen', [DosenController::class, 'admin']);
Route::get('/admin/kelola_mahasiswa', [MahasiswaController::class, 'admin']);
Route::get('/admin/kelola_mata_kuliah', [MataKuliahController::class, 'index']);
Route::get('/admin/data_kelas', [KelolaKelasController::class, 'index']);
Route::get('/admin/kelola_jadwal', [JadwalKelasController::class, 'index']);
Route::get('/admin/pengumuman', [PengumumanController::class, 'index']);
Route::get('/admin/pengaturan', [UserSettingController::class, 'index']);
Route::get('/admin/bantuan', [BantuanController::class, 'admin']);
Route::get('/admin/user_profil', [UserProfilController::class, 'admin']);













Route::get('/tes', function () {
    return view('tes');
});