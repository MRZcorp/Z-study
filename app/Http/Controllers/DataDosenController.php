<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Http\Request;

class DataDosenController extends Controller
{
    //
    
    public function index() 
       
    {
        $dosens = User::with('dosens')->whereHas('role', fn($q) => $q->where('nama_role','dosen'))
        ->get();
      
        return view('admin.data_dosen.index', compact('dosens'));
       
    }
    


}