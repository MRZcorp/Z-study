<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BantuanController extends Controller
{
    //
    
    public function admin()
     {
       
        return view('admin.bantuan');
    }

    
    


    public function mahasiswa() {
        return view('mahasiswa.bantuan');

    }

    public function dosen()
    {
      
       return view('dosen.bantuan');
   }
}
