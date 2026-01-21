<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});
Route::get('/admin', function () {
    return view('admin');
});
Route::get('/dosen', function () {
    return view('dosen');
});
Route::get('/mahasiswa', function () {
    return view('mahasiswa');
});