<?php

namespace App\Http\Controllers;

use App\Models\Kelas;

class KelasController extends Controller
{
    public function admin()
     {
        $pilih_kelas = Kelas::with('dosens')->latest()->get();

    return view('admin.kelola_kelas', compact('pilih_kelas'));
       
    }
}
    
