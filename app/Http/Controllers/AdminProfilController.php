<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminProfilController extends Controller
{
    //
    


    public function index()
    {
        return view('admin.profile.index');
    }

}
