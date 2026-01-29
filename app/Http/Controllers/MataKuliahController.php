<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    //
    // Tampilkan daftar materi
    
  
   
public function index()
{
   $matkuls = MataKuliah::with('programStudi')->latest()->get();

return view('admin.mata_kuliah.index', compact('matkuls'));
  
}
    
}








