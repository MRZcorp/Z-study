<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JadwalKelasController extends Controller
{
    //
    public function index()
    {
      
       return view('admin.kelola_jadwal');
   }

   
}
