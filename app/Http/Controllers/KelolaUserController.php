<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class KelolaUserController extends Controller
{
    //
    
    public function index()
    {   $users = User::with('role')
        ->orderByRaw("
        CASE 
            WHEN role_id = 1 THEN 1  -- Admin
            WHEN role_id = 2 THEN 2  -- Dosen
            WHEN role_id = 3 THEN 3  -- Mahasiswa
            ELSE 4
        END
    ") // Admin, Dosen, Mahasiswa
        ->orderBy('name') // optional: urutkan nama di dalam role
        ->get();
        
        $roles = Role::all();
      
       return view('admin.data_akun.index', compact('users', 'roles'));
   }

   
   public function store(Request $request)
    { 
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'role_id' => 'required|exists:roles,id',
        'status' => 'required|in:aktif,nonaktif',
        ]);

        User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt('123'),
        'role_id' => $request->role_id,
        'status' => $request->status,
        ]);
        


        return redirect()->back()->with('success', 'User berhasil ditambahkan');
      
    }

    public function update(Request $request, $id)
        {
        $user = User::findOrFail($id);


        $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'role_id' => $request->role_id,
        'status' => $request->status
        ]);


        return redirect()->back()->with('success', 'User berhasil diupdate');
        }

        public function destroy($id)
        {
        User::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'User berhasil dihapus');
        }

    
}
