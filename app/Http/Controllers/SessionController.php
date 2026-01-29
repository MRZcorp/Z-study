<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class SessionController extends Controller
{ 
    // ======================
    // FORM LOGIN
    // ======================
    public function index()
    {
        
            if (session()->has('user_id')) {
                return match (session('nama_role')) {
                    'admin' => redirect()->route('admin.dashboard'),
                    'dosen' => redirect()->route('dosen.dashboard'),
                    'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
                    default => redirect('/login'),
                };
            }
        
            return view('login');
        
    }

    // ======================
    // PROSES LOGIN
    // ======================
    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Username / NIM / NIDN wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        $identity = $request->username;

        // 🔍 Cari user dari username / nim / nidn
        $user = User::where('username', $identity)
            ->orWhere('nim', $identity)
            ->orWhere('nidn', $identity)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withInput($request->only('username'))
                ->with('error', 'Username / NIM / NIDN atau password salah');
        }

        // ======================
        // SIMPAN SESSION
        // ======================
        session([
            'user_id' => $user->id,
            'name'    => $user->name,
            'nama_role'    => $user->role->nama_role, // admin | dosen | mahasiswa
        ]);

        // ======================
        // REDIRECT BERDASARKAN ROLE
        // ======================
        return match ($user->role) {
            'admin'     => redirect()->route('admin.dashboard'),
            'dosen'     => redirect()->route('dosen.dashboard'),
            'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
            default     => redirect('/'),
        };
    }

    // ======================
    // LOGOUT
    // ======================
    public function logout(Request $request)
    {
        // hapus semua session
        $request->session()->flush();

        // regenerasi session id (security)
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Berhasil logout');
    }
}