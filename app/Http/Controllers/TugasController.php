<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    //
    // Tampilkan daftar materi

    public function index()
    {
        $tugaskelas = Tugas::with(['mataKuliah', 'kelas'])->get();
        return view('mahasiswa.tugas',compact('tugas_kelas'));
        
    }
}
