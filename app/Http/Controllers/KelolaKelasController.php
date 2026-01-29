<?php

namespace App\Http\Controllers;


use App\Models\Kelas;
use Illuminate\Http\Request;

class KelolaKelasController extends Controller
{
    //
    // Tampilkan daftar materi
    
    public function index()
    {
      
       return view('admin.data_kelas.index');
   }
    
    
}
    

