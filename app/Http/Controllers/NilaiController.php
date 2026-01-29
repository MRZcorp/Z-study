<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NilaiController extends Controller
{
    //
    public function dosen() {
        return view('dosen.nilai');

    }

    public function mahasiswa() {
        return view('mahasiswa.nilai');

    }
}