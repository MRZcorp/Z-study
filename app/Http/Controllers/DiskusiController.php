<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DiskusiController extends Controller
{
    //
 

    public function dosen()
     {
       
        return view('dosen.diskusi');
    }

    public function index() {
        return view('mahasiswa.diskusi');

    }
}
