<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminProfilController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\DosenKelasController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MahasiswaKelasController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\BantuanController;
use App\Http\Controllers\DataDosenController;
use App\Http\Controllers\DiskusiController;
use App\Http\Controllers\DataMahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\DosenProfilController;
use App\Http\Controllers\JadwalKelasController;
use App\Http\Controllers\KelolaKelasController;
use App\Http\Controllers\KelolaUserController;
use App\Http\Controllers\KoreksiTugasController;
use App\Http\Controllers\PengumpulanTugasController;
use App\Http\Controllers\MahasiswaProfilController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\RekapNilaiController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\UserProfilController;
use App\Http\Controllers\UserSettingController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\ProgramStudiController;
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

Route::get('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'request'])
    ->name('password.request');
Route::post('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'email'])
    ->name('password.email');
Route::post('/forgot-password/verify', [\App\Http\Controllers\PasswordResetController::class, 'verify'])
    ->name('password.verify');
Route::get('/reset-password/{token}', [\App\Http\Controllers\PasswordResetController::class, 'reset'])
    ->name('password.reset');
Route::post('/reset-password', [\App\Http\Controllers\PasswordResetController::class, 'update'])
    ->name('password.update');

Route::post('/logout', [SessionController::class, 'logout'])
    ->name('logout');

Route::get('/search', [SearchController::class, 'index'])
    ->name('search');


/////////// USER ADMIN /////////////////

Route::middleware(['auth.session', 'role.session:Admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])
        ->name('admin.dashboard');
        Route::resource('/admin/user_setting', KelolaUserController::class);
        Route::get('/admin/kelola_dosen/wali', [DataDosenController::class, 'wali'])
            ->name('admin.dosen.wali');
        Route::post('/admin/kelola_dosen/wali', [DataDosenController::class, 'storeWali'])
            ->name('admin.dosen.wali.store');
        Route::put('/admin/kelola_dosen/wali/{dosenWali}', [DataDosenController::class, 'updateWali'])
            ->name('admin.dosen.wali.update');
        Route::delete('/admin/kelola_dosen/wali/{dosenWali}', [DataDosenController::class, 'destroyWali'])
            ->name('admin.dosen.wali.destroy');
        Route::resource('/admin/kelola_dosen', DataDosenController::class);
        Route::resource('/admin/kelola_mahasiswa', DataMahasiswaController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::post('/admin/kelola_mahasiswa/import', [DataMahasiswaController::class, 'importCsv'])->name('admin.mahasiswa.import');
        Route::post('/admin/kelola_mahasiswa/status-krs', [DataMahasiswaController::class, 'bulkUpdateStatusKrs'])->name('admin.mahasiswa.status_krs.bulk');
        Route::get('/admin/kelola_mata_kuliah', [MataKuliahController::class, 'index'])->name('admin.mata_kuliah.index');
        Route::post('/admin/kelola_mata_kuliah', [MataKuliahController::class, 'store'])->name('admin.mata_kuliah.store');
        Route::put('/admin/kelola_mata_kuliah/{mataKuliah}', [MataKuliahController::class, 'update'])->name('admin.mata_kuliah.update');
        Route::delete('/admin/kelola_mata_kuliah/{mataKuliah}', [MataKuliahController::class, 'destroy'])->name('admin.mata_kuliah.destroy');
        Route::get('/admin/prodi', [ProgramStudiController::class, 'index'])->name('admin.prodi.index');
        Route::post('/admin/prodi', [ProgramStudiController::class, 'store'])->name('admin.prodi.store');
        Route::put('/admin/prodi/{prodi}', [ProgramStudiController::class, 'update'])->name('admin.prodi.update');
        Route::delete('/admin/prodi/{prodi}', [ProgramStudiController::class, 'destroy'])->name('admin.prodi.destroy');
        Route::get('/admin/fakultas', [FakultasController::class, 'index'])->name('admin.fakultas.index');
        Route::post('/admin/fakultas', [FakultasController::class, 'store'])->name('admin.fakultas.store');
        Route::put('/admin/fakultas/{fakultas}', [FakultasController::class, 'update'])->name('admin.fakultas.update');
        Route::delete('/admin/fakultas/{fakultas}', [FakultasController::class, 'destroy'])->name('admin.fakultas.destroy');
        Route::get('/admin/data_kelas', [KelolaKelasController::class, 'index'])->name('admin.kelas.index');
        Route::post('/admin/data_kelas', [KelolaKelasController::class, 'store'])->name('admin.kelas.store');
        Route::post('/admin/data_kelas/finish_all', [KelolaKelasController::class, 'finishAll'])->name('admin.kelas.finish_all');
        Route::put('/admin/data_kelas/{kelas}', [KelolaKelasController::class, 'update'])->name('admin.kelas.update');
        Route::delete('/admin/data_kelas/{kelas}', [KelolaKelasController::class, 'destroy'])->name('admin.kelas.destroy');
        Route::get('/admin/kelola_jadwal', [JadwalKelasController::class, 'index'])->name('admin.jadwal.index');
        Route::post('/admin/kelola_jadwal', [JadwalKelasController::class, 'store'])->name('admin.jadwal.store');
        Route::put('/admin/kelola_jadwal/{kelas}', [JadwalKelasController::class, 'update'])->name('admin.jadwal.update');
        Route::delete('/admin/kelola_jadwal/{kelas}', [JadwalKelasController::class, 'destroy'])->name('admin.jadwal.destroy');
        Route::get('/admin/pengumuman', [PengumumanController::class, 'index'])->name('admin.pengumuman.index');
        Route::post('/admin/pengumuman', [PengumumanController::class, 'store'])->name('admin.pengumuman.store');
        Route::put('/admin/pengumuman/{pengumuman}', [PengumumanController::class, 'update'])->name('admin.pengumuman.update');
        Route::delete('/admin/pengumuman/{pengumuman}', [PengumumanController::class, 'destroy'])->name('admin.pengumuman.destroy');
        Route::post('/pengumuman/read-all', [PengumumanController::class, 'markReadAll'])->name('pengumuman.read_all');
        Route::get('/admin/pengaturan', [UserSettingController::class, 'index']);
        Route::put('/admin/pengaturan', [UserSettingController::class, 'updateAdmin'])->name('admin.pengaturan.update');
        Route::put('/admin/pengaturan/info', [UserSettingController::class, 'updateAdminInfo'])->name('admin.pengaturan.update.info');
        Route::put('/admin/pengaturan/password', [UserSettingController::class, 'updateAdminPassword'])->name('admin.pengaturan.update.password');
        Route::get('/admin/bantuan', [BantuanController::class, 'admin']);
        Route::get('/admin/user_profile', [AdminProfilController::class, 'index']);
        Route::post('/admin/kalender-akademik', [AdminController::class, 'storeKalender'])
            ->name('admin.kalender.store');
        Route::put('/admin/kalender-akademik/{kalender}', [AdminController::class, 'updateKalender'])
            ->name('admin.kalender.update');
        Route::delete('/admin/kalender-akademik/{kalender}', [AdminController::class, 'destroyKalender'])
            ->name('admin.kalender.destroy');
        Route::post('/admin/krs', [AdminController::class, 'upsertKrs'])
            ->name('admin.krs.upsert');





});
/////// USER DOSEN  /////////////////////
Route::middleware(['auth.session', 'role.session:Dosen'])->group(function () {
    Route::get('/dosen', [DosenController::class, 'index'])
        ->name('dosen.dashboard');
        Route::get('/dosen/perwalian', [DosenController::class, 'perwalian'])
            ->name('dosen.perwalian');
        Route::post('/dosen/perwalian/{mahasiswa}/kelas/{kelas}/approve', [DosenController::class, 'approvePerwalianKelas'])
            ->name('dosen.perwalian.kelas.approve');
        Route::post('/dosen/perwalian/{mahasiswa}/kelas/{kelas}/reject', [DosenController::class, 'rejectPerwalianKelas'])
            ->name('dosen.perwalian.kelas.reject');
        Route::post('/dosen/perwalian/{mahasiswa}/kelas/{kelas}/reset', [DosenController::class, 'resetPerwalianKelas'])
            ->name('dosen.perwalian.kelas.reset');
        Route::post('/dosen/perwalian/{mahasiswa}/kelas/approve-all', [DosenController::class, 'approveAllPerwalianKelas'])
            ->name('dosen.perwalian.kelas.approve_all');
        Route::get('/dosen/kelas', [DosenKelasController::class, 'index'])->name('dosen.kelas');
        Route::get('/dosen/kelas/{kelas}/diskusi', [DiskusiController::class, 'kelasMessages'])->name('dosen.kelas.diskusi.index');
        Route::post('/dosen/kelas/{kelas}/diskusi', [DiskusiController::class, 'storeKelasMessage'])->name('dosen.kelas.diskusi.store');
        Route::put('/dosen/kelas/{kelas}/diskusi/{diskusi}', [DiskusiController::class, 'updateKelasMessage'])->name('dosen.kelas.diskusi.update');
        Route::delete('/dosen/kelas/{kelas}/diskusi/{diskusi}', [DiskusiController::class, 'destroyKelasMessage'])->name('dosen.kelas.diskusi.destroy');
        Route::get('/dosen/diskusi/unread-status', [DiskusiController::class, 'unreadStatus'])->name('dosen.diskusi.unread_status');
        Route::post('/dosen/diskusi/mark-read', [DiskusiController::class, 'markRead'])->name('dosen.diskusi.mark_read');
        Route::get('/dosen/ujian/{ujian}/diskusi', [DiskusiController::class, 'ujianMessages'])->name('dosen.ujian.diskusi.index');
        Route::post('/dosen/ujian/{ujian}/diskusi', [DiskusiController::class, 'storeUjianMessage'])->name('dosen.ujian.diskusi.store');
        Route::put('/dosen/ujian/{ujian}/diskusi/{diskusi}', [DiskusiController::class, 'updateUjianMessage'])->name('dosen.ujian.diskusi.update');
        Route::delete('/dosen/ujian/{ujian}/diskusi/{diskusi}', [DiskusiController::class, 'destroyUjianMessage'])->name('dosen.ujian.diskusi.destroy');
        Route::get('/dosen/tugas/{tugas}/diskusi', [DiskusiController::class, 'tugasMessages'])->name('dosen.tugas.diskusi.index');
        Route::post('/dosen/tugas/{tugas}/diskusi', [DiskusiController::class, 'storeTugasMessage'])->name('dosen.tugas.diskusi.store');
        Route::put('/dosen/tugas/{tugas}/diskusi/{diskusi}', [DiskusiController::class, 'updateTugasMessage'])->name('dosen.tugas.diskusi.update');
        Route::delete('/dosen/tugas/{tugas}/diskusi/{diskusi}', [DiskusiController::class, 'destroyTugasMessage'])->name('dosen.tugas.diskusi.destroy');
        Route::get('/dosen/kelas/riwayat', [DosenKelasController::class, 'riwayat'])->name('dosen.kelas_riwayat');
        Route::get('/dosen/kelas/riwayat/{kelas}', [DosenKelasController::class, 'riwayatDetail'])->name('dosen.kelas_riwayat.detail');
        Route::post('/dosen/kelas/bg', [DosenController::class, 'updateBg'])->name('dosen.kelas.bg');
        Route::get('/dosen/buat_kelas', [DosenKelasController::class, 'create'])->name('dosen.buat_kelas');
        Route::get('/dosen/materi', [MateriController::class, 'dosen'])->name('dosen.materi.kelas');
        Route::get('/dosen/materi/{kelas:slug}', [MateriController::class, 'dosenKelas'])->name('dosen.materi.kelas.detail');
        Route::get('/dosen/riwayat/{kelas}/materi', [MateriController::class, 'dosenRiwayat'])->name('dosen.materi.riwayat');
        Route::put('/dosen/materi/item/{materi}', [MateriController::class, 'update'])->name('dosen.materi.update');
        Route::delete('/dosen/materi/item/{materi}', [MateriController::class, 'destroy'])->name('dosen.materi.destroy');
        Route::get('/dosen/tugas', [TugasController::class, 'dosen']);
        Route::post('/dosen/tugas', [TugasController::class, 'store'])->name('dosen.tugas.store');
        Route::put('/dosen/tugas/{tugas}', [TugasController::class, 'update'])->name('dosen.tugas.update');
        Route::delete('/dosen/tugas/{tugas}', [TugasController::class, 'destroy'])->name('dosen.tugas.destroy');
        Route::get('/dosen/ujian', [UjianController::class, 'dosen']);
        Route::post('/dosen/ujian', [UjianController::class, 'store'])->name('dosen.ujian.store');
        Route::put('/dosen/ujian/{ujian}', [UjianController::class, 'update'])->name('dosen.ujian.update');
        Route::delete('/dosen/ujian/{ujian}', [UjianController::class, 'destroy'])->name('dosen.ujian.destroy');
        Route::get('/dosen/ujian_selesai', [UjianController::class, 'dosenSelesai'])->name('dosen.ujian.selesai');
        Route::get('/dosen/koreksi_ujian/{ujian?}', [UjianController::class, 'koreksi'])->name('dosen.ujian.koreksi');
        Route::post('/dosen/ujian/nilai', [UjianController::class, 'saveNilaiUjian'])->name('dosen.ujian.nilai.save');
        Route::post('/dosen/ujian/essay-score', [UjianController::class, 'saveEssayScore'])->name('dosen.ujian.essay.save');
        Route::get('/dosen/ujian/soal/{ujian}', [UjianController::class, 'soalDosen'])->name('dosen.ujian.soal');
        Route::post('/dosen/ujian/soal/{ujian}', [UjianController::class, 'storeSoal'])->name('dosen.ujian.soal.store');
        Route::post('/dosen/ujian/soal/{ujian}/import', [UjianController::class, 'importSoal'])->name('dosen.ujian.soal.import');
        Route::post('/dosen/ujian/soal/{ujian}/generate', [UjianController::class, 'generateSoalAI'])->name('dosen.ujian.soal.generate');
        Route::delete('/dosen/ujian/soal/{ujian}/delete-all', [UjianController::class, 'destroyAllSoal'])->name('dosen.ujian.soal.destroyAll');
        Route::put('/dosen/ujian/soal/{soal}', [UjianController::class, 'updateSoal'])->name('dosen.ujian.soal.update');
        Route::delete('/dosen/ujian/soal/{soal}', [UjianController::class, 'destroySoal'])->name('dosen.ujian.soal.destroy');
        Route::get('/dosen/koreksi_tugas', [KoreksiTugasController::class, 'koreksi'])->name('dosen.tugas.koreksi');
        Route::post('/dosen/koreksi_tugas/nilai', [KoreksiTugasController::class, 'saveNilai'])->name('dosen.tugas.nilai.save');
        Route::get('/dosen/tugas_selesai', [KoreksiTugasController::class, 'index'])->name('dosen.tugas.selesai');
        Route::get('/dosen/rekap', [RekapNilaiController::class, 'index'])->name('dosen.rekap');
        Route::get('/dosen/rekap/{kelas}', [RekapNilaiController::class, 'show'])->name('dosen.rekap.show');
        Route::post('/dosen/rekap/{kelas}/sync', [RekapNilaiController::class, 'sync'])->name('dosen.rekap.sync');
        Route::post('/dosen/rekap/{kelas}/bobot', [RekapNilaiController::class, 'saveBobot'])->name('dosen.rekap.bobot.save');
        Route::get('/dosen/dikusi', [DiskusiController::class, 'dosen']);
        Route::get('/dosen/pengaturan', [UserSettingController::class, 'dosen']);
        Route::put('/dosen/pengaturan', [UserSettingController::class, 'updateDosen'])->name('dosen.pengaturan.update');
        Route::put('/dosen/pengaturan/info', [UserSettingController::class, 'updateDosenInfo'])->name('dosen.pengaturan.update.info');
        Route::put('/dosen/pengaturan/password', [UserSettingController::class, 'updateDosenPassword'])->name('dosen.pengaturan.update.password');
        Route::get('/dosen/profil', [DosenProfilController::class, 'index']);
        Route::get('/dosen/bantuan', [BantuanController::class, 'dosen']);

        Route::post('/dosen/kelas', [DosenKelasController::class, 'store'])
            ->name('dosen.kelas');
        Route::put('/dosen/kelas/{kelas}', [DosenKelasController::class, 'update'])
            ->name('dosen.kelas.update');
        Route::delete('/dosen/kelas/{kelas}', [DosenKelasController::class, 'destroy'])
            ->name('dosen.kelas.destroy');
        
        Route::post('/dosen/materi', [MateriController::class, 'store']);
        



});
// USER MAHASISWA////////////////////////////////////////////////////
Route::middleware(['auth.session', 'role.session:Mahasiswa'])->group(function () {
    Route::get('/mahasiswa', [MahasiswaController::class, 'index'])
        ->name('mahasiswa.dashboard');
        Route::get('/mahasiswa/kelas', [MahasiswaKelasController::class, 'index'])->name('mahasiswa.kelas_saya');
        Route::get('/mahasiswa/kelas/{kelas}/diskusi', [DiskusiController::class, 'kelasMessages'])->name('mahasiswa.kelas.diskusi.index');
        Route::post('/mahasiswa/kelas/{kelas}/diskusi', [DiskusiController::class, 'storeKelasMessage'])->name('mahasiswa.kelas.diskusi.store');
        Route::put('/mahasiswa/kelas/{kelas}/diskusi/{diskusi}', [DiskusiController::class, 'updateKelasMessage'])->name('mahasiswa.kelas.diskusi.update');
        Route::delete('/mahasiswa/kelas/{kelas}/diskusi/{diskusi}', [DiskusiController::class, 'destroyKelasMessage'])->name('mahasiswa.kelas.diskusi.destroy');
        Route::get('/mahasiswa/diskusi/unread-status', [DiskusiController::class, 'unreadStatus'])->name('mahasiswa.diskusi.unread_status');
        Route::post('/mahasiswa/diskusi/mark-read', [DiskusiController::class, 'markRead'])->name('mahasiswa.diskusi.mark_read');
        Route::get('/mahasiswa/ujian/{ujian}/diskusi', [DiskusiController::class, 'ujianMessages'])->name('mahasiswa.ujian.diskusi.index');
        Route::post('/mahasiswa/ujian/{ujian}/diskusi', [DiskusiController::class, 'storeUjianMessage'])->name('mahasiswa.ujian.diskusi.store');
        Route::put('/mahasiswa/ujian/{ujian}/diskusi/{diskusi}', [DiskusiController::class, 'updateUjianMessage'])->name('mahasiswa.ujian.diskusi.update');
        Route::delete('/mahasiswa/ujian/{ujian}/diskusi/{diskusi}', [DiskusiController::class, 'destroyUjianMessage'])->name('mahasiswa.ujian.diskusi.destroy');
        Route::get('/mahasiswa/tugas/{tugas}/diskusi', [DiskusiController::class, 'tugasMessages'])->name('mahasiswa.tugas.diskusi.index');
        Route::post('/mahasiswa/tugas/{tugas}/diskusi', [DiskusiController::class, 'storeTugasMessage'])->name('mahasiswa.tugas.diskusi.store');
        Route::put('/mahasiswa/tugas/{tugas}/diskusi/{diskusi}', [DiskusiController::class, 'updateTugasMessage'])->name('mahasiswa.tugas.diskusi.update');
        Route::delete('/mahasiswa/tugas/{tugas}/diskusi/{diskusi}', [DiskusiController::class, 'destroyTugasMessage'])->name('mahasiswa.tugas.diskusi.destroy');
        Route::get('/mahasiswa/kelas_tersedia', [MahasiswaKelasController::class, 'tersedia'])->name('mahasiswa.kelas_tersedia');
    Route::get('/mahasiswa/riwayat_kelas', [MahasiswaKelasController::class, 'riwayat'])->name('mahasiswa.kelas_riwayat');
    Route::get('/mahasiswa/riwayat_kelas/{kelas}', [MahasiswaKelasController::class, 'riwayatDetail'])->name('mahasiswa.kelas_riwayat.detail');
        Route::get('/mahasiswa/materi', [MateriController::class, 'listKelas'])->name('mahasiswa.materi.kelas');
        Route::get('/mahasiswa/materi/{kelas:slug}', [MateriController::class, 'mahasiswaKelas'])->name('mahasiswa.materi.kelas.detail');
        Route::get('/mahasiswa/tugas', [TugasController::class, 'mahasiswa']);
        Route::get('/mahasiswa/tugas_selesai', [TugasController::class, 'mahasiswaSelesai'])->name('mahasiswa.tugas.selesai');
        Route::post('/mahasiswa/tugas/pengumpulan/save', [PengumpulanTugasController::class, 'save'])->name('mahasiswa.tugas.pengumpulan.save');
        Route::post('/mahasiswa/tugas/pengumpulan/submit', [PengumpulanTugasController::class, 'submit'])->name('mahasiswa.tugas.pengumpulan.submit');
        Route::get('/mahasiswa/ujian', [UjianController::class, 'mahasiswa']);
        Route::get('/mahasiswa/ujian_selesai', [UjianController::class, 'mahasiswaSelesai'])->name('mahasiswa.ujian.selesai');
        Route::get('/mahasiswa/ujian/{ujian}/soal', [UjianController::class, 'mahasiswaSoal'])->name('mahasiswa.ujian.soal');
        Route::post('/mahasiswa/ujian/jawaban', [UjianController::class, 'saveJawaban'])->name('mahasiswa.ujian.jawaban.save');
        Route::post('/mahasiswa/ujian/submit', [UjianController::class, 'submitUjian'])->name('mahasiswa.ujian.submit');
        Route::get('/mahasiswa/materi/download/{materi}', [MateriController::class, 'downloadMahasiswa'])
            ->name('mahasiswa.materi.download');
        Route::get('/mahasiswa/nilai', [NilaiController::class, 'mahasiswa']);
        Route::get('/mahasiswa/pengaturan', [UserSettingController::class, 'mahasiswa']);
        Route::put('/mahasiswa/pengaturan', [UserSettingController::class, 'updateMahasiswa'])->name('mahasiswa.pengaturan.update');
        Route::put('/mahasiswa/pengaturan/info', [UserSettingController::class, 'updateMahasiswaInfo'])->name('mahasiswa.pengaturan.update.info');
        Route::put('/mahasiswa/pengaturan/password', [UserSettingController::class, 'updateMahasiswaPassword'])->name('mahasiswa.pengaturan.update.password');
        Route::get('/mahasiswa/profile', [MahasiswaProfilController::class, 'index']);
        Route::get('/mahasiswa/diskusi', [DiskusiController::class, 'mahasiswa']);
        Route::get('/mahasiswa/bantuan', [BantuanController::class, 'mahasiswa']);
        Route::post('/mahasiswa/bg', [MahasiswaController::class, 'updateBg'])->name('mahasiswa.bg');
        Route::post('/mahasiswa/kelas/{kelas}/ikuti', [MahasiswaKelasController::class, 'ikuti'])
    ->name('mahasiswa.kelas.ikuti');






});




Route::get('/tes', function () {
    return view('tes');
});
