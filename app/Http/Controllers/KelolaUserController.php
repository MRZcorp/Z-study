<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class KelolaUserController extends Controller
{
    //
    
    public function index()
    {
        $query = User::with('role');

        if (request('role')) {
            $query->where('role_id', request('role'));
        }

        if (request('status')) {
            $query->where('status', request('status'));
        }

        if (request('q')) {
            $term = request('q');
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', '%' . $term . '%')
                  ->orWhere('email', 'like', '%' . $term . '%');
            });
        }

        $users = $query
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
            'password' => 'nullable|string|min:3',
        ]);

        User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password ?: '123'),
        'role_id' => $request->role_id,
        'status' => $request->status,
        ]);
        
        if ($request->ajax()) {
            return response()->json(['message' => 'User berhasil ditambahkan']);
        }


        return redirect()->back()->with('success', 'User berhasil ditambahkan');
      
    }

    public function update(Request $request, $id)
        {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:aktif,nonaktif',
            'password' => 'nullable|string|min:3',
        ]);

        $data = [
        'name' => $request->name,
        'email' => $request->email,
        'role_id' => $request->role_id,
        'status' => $request->status
        ];

        if (!empty($request->password)) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        if ($request->ajax()) {
            return response()->json(['message' => 'User berhasil diupdate']);
        }

        return redirect()->back()->with('success', 'User berhasil diupdate');
        }

        public function destroy($id)
        {
        User::findOrFail($id)->delete();

        if (request()->ajax()) {
            return response()->json(['message' => 'User berhasil dihapus']);
        }

        return redirect()->back()->with('success', 'User berhasil dihapus');
        }

    
}
