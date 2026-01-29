<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserProfilController extends Controller
{
    //
    

    public function admin()
    {
      
       return view('admin.profil');
   }

   public function dosen() {
    return view('dosen.profil');

}
   

   public function mahasiswa() {
    return view('mahasiswa.profil');

}

}
