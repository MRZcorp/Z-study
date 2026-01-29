<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    //
    // Tampilkan daftar materi

    public function dosen()
    {
        $tugas_kelas = Tugas::with(['mataKuliah', 'kelas'])->get();
        return view('dosen.tugas.tugas',compact('tugas_kelas'));
        
    }


    public function mahasiswa()
    {
        $tugas_kelas = Tugas::with(['mataKuliah', 'kelas'])->get();
        return view('mahasiswa.tugas.tugas',compact('tugas_kelas'));
        
    }

    
}
