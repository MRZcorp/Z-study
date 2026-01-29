<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UjianController extends Controller
{
    //

    public function dosen()
     {
       
        return view('dosen.ujian.ujian');
    }


    public function mahasiswa() {
        return view('mahasiswa.ujian.soal');

    }
}
