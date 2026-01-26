<?php

namespace App\Http\Controllers;


use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    //
    // Tampilkan daftar materi
    public function index()
{
    
    $pilih_kelas = Kelas::with('dosen')->latest()->get();

    return view('mahasiswa.kelas', compact('pilih_kelas'));
}

   
    
}
