<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserSettingController extends Controller
{
    //
    

    public function index()
    {
       $user = User::with('role')->findOrFail(session('user_id'));
       $dosen = Dosen::where('user_id', $user->id)->first();
       return view('admin.pengaturan', compact('user', 'dosen'));
   }

   public function mahasiswa() {
    $user = User::with('role')->findOrFail(session('user_id'));
    $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
    return view('mahasiswa.pengaturan', compact('user', 'mahasiswa'));
}
   public function dosen() {
    $user = User::with('role')->findOrFail(session('user_id'));
    $dosen = Dosen::where('user_id', $user->id)->first();
    return view('dosen.pengaturan_akun', compact('user', 'dosen'));
}

    public function updateAdmin(Request $request)
    {
        $user = User::findOrFail(session('user_id'));
        $dosen = Dosen::where('user_id', $user->id)->first();

        $validated = $request->validate([
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('profil', 'public');

            if ($dosen) {
                if (!empty($dosen->poto_profil)) {
                    Storage::disk('public')->delete($dosen->poto_profil);
                }
                $dosen->poto_profil = $path;
                $dosen->save();
            }
        }

        return redirect()->back();
    }

    public function updateAdminInfo(Request $request)
    {
        $user = User::findOrFail(session('user_id'));

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->username = $validated['username'];
        $user->save();

        return redirect()->back();
    }

    public function updateAdminPassword(Request $request)
    {
        $user = User::findOrFail(session('user_id'));

        $validated = $request->validate([
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $user->password = $validated['password'];
        $user->save();

        return redirect()->back();
    }

    public function updateMahasiswa(Request $request)
    {
        $user = User::findOrFail(session('user_id'));
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        $validated = $request->validate([
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('foto') && $mahasiswa) {
            $path = $request->file('foto')->store('profil', 'public');

            if (!empty($mahasiswa->poto_profil)) {
                Storage::disk('public')->delete($mahasiswa->poto_profil);
            }

            $mahasiswa->poto_profil = $path;
            $mahasiswa->save();
        }

        return redirect()->back();
    }

    public function updateMahasiswaInfo(Request $request)
    {
        $user = User::findOrFail(session('user_id'));
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:50'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->username = $validated['username'];
        $user->save();

        if ($mahasiswa && array_key_exists('no_hp', $validated)) {
            $mahasiswa->no_hp = $validated['no_hp'];
            $mahasiswa->save();
        }

        return redirect()->back();
    }

    public function updateMahasiswaPassword(Request $request)
    {
        $user = User::findOrFail(session('user_id'));

        $validated = $request->validate([
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $user->password = $validated['password'];
        $user->save();

        return redirect()->back();
    }

    public function updateDosen(Request $request)
    {
        $user = User::findOrFail(session('user_id'));
        $dosen = Dosen::where('user_id', $user->id)->first();

        $validated = $request->validate([
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('foto') && $dosen) {
            $path = $request->file('foto')->store('profil', 'public');

            if (!empty($dosen->poto_profil)) {
                Storage::disk('public')->delete($dosen->poto_profil);
            }

            $dosen->poto_profil = $path;
            $dosen->save();
        }

        return redirect()->back();
    }

    public function updateDosenInfo(Request $request)
    {
        $user = User::findOrFail(session('user_id'));
        $dosen = Dosen::where('user_id', $user->id)->first();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:50'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->username = $validated['username'];
        $user->save();

        if ($dosen && array_key_exists('no_hp', $validated)) {
            $dosen->no_hp = $validated['no_hp'];
            $dosen->save();
        }

        return redirect()->back();
    }

    public function updateDosenPassword(Request $request)
    {
        $user = User::findOrFail(session('user_id'));

        $validated = $request->validate([
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $user->password = $validated['password'];
        $user->save();

        return redirect()->back();
    }
}
