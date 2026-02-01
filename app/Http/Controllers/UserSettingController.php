<?php

namespace App\Http\Controllers;

use App\Models\User;
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
    $user = User::with(['mahasiswa', 'dosens'])
    ->findOrFail(session('user_id'));

return view('dosen.pengaturan_akun', compact('user'));
    

}
}
