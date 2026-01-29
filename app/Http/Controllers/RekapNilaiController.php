<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RekapNilaiController extends Controller
{
    //
    public function index()
     {
       
        return view('dosen.rekap.rekap');
    }
}
