<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/about', function () {
    return view('dashboard_mhs');
});
Route::get('/admin', function () {
    return view('users/admin');
});
Route::get('/dosen', function () {
    return view('users/dosen');
});
Route::get('/mahasiswa', function () {
    return view('/users/mahasiswa');
});
Route::get('/mahasiswa/kelas', function () {
    return view('/mahasiswa/kelas');
});
Route::get('/mahasiswa/materi', function () {
    return view('/mahasiswa/materi');
});
Route::get('/mahasiswa/tugas', function () {
    return view('/mahasiswa/tugas');
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
