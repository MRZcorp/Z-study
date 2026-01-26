<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Kelas;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    //
    public function index()
    {
    return view('mahasiswa.kelas', [
        'nama_dosen' => Dosen::latest()->get()
    ]);
   
}
}