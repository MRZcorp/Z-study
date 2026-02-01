<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    //
    
    public function index() 
        {
            
            $pengumuman = Pengumuman::where('is_active', true)
            ->orderBy('tanggal_publish', 'desc')
            ->get();
    
    $poto = Kelas::with('dosens', 'mataKuliah')->latest()->get();

    
        return view('dosen.dashboard', compact('pengumuman', 'poto'));
       

    }
 
    



    public function dosenkelas()
    {
        
    return view('mahasiswa.kelas', [
        'nama_dosen' => Dosen::latest()->get()
    ]);
   
}



    public function admin()
    {
        $dosens = User::with('dosens')->whereHas('role', fn($q) => $q->where('nama_role','dosen'))
        ->get();
      
        return view('admin.data_dosen.index', compact('dosens'));
       
    }
    


}