<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    //
   

    public function index()
    { $pengumuman = Pengumuman::where('is_active', true)
        ->orderBy('tanggal_publish', 'desc')
        ->get();

    return view('mahasiswa.dashboard', compact('pengumuman'));



    }
    public function admin()
    {
        $mhss = User::with('mahasiswa')->whereHas('role', fn($q) => $q->where('nama_role','mahasiswa'))
        ->get();
      
        return view('admin.data_mahasiswa.index', compact('mhss'));
       
    }
   


    
    


}