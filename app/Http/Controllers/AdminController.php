<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Container\Attributes\Auth as AttributesAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Echo_;

class AdminController extends Controller
{
    //
    public function index()
    {
        $users = User::with(['role'])->get();
       // Echo "<h1>". Auth::user()->role  ."</h1>" ;
        
         return view('admin.dashboard'
        
        );
        
    }
}