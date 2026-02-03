<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MahasiswaProfilController extends Controller
{
    //
    public function index()
    {
        $mahasiswa = auth()->user()->mahasiswa;
        return view('mahasiswa.profil', compact('mahasiswa'));
    }

}
