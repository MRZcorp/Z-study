<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserSettingController extends Controller
{
    //
    

    public function index()
    {
      
       return view('admin.pengaturan');
   }

   public function mahasiswa() {
    return view('mahasiswa.pengaturan');

}
   public function dosen() {
    return view('dosen.pengaturan_akun');

}
}
