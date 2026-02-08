<?php

namespace App\Http\Controllers;

use App\Models\Angkatan;
use App\Models\Fakultas;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DataMahasiswaController extends Controller
{
    public function index()
    {
        $query = User::with(['mahasiswa.fakultas', 'mahasiswa.programStudi', 'mahasiswa.angkatan'])
            ->whereHas('role', fn($q) => $q->where('nama_role', 'mahasiswa'));

        $search = request()->input('q');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%")
                    ->orWhereHas('mahasiswa', function ($mq) use ($search) {
                        $mq->where('nim', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $fakultasId = request()->input('fakultas_id');
        if ($fakultasId) {
            $query->whereHas('mahasiswa', fn($q) => $q->where('fakultas_id', $fakultasId));
        }

        $prodiId = request()->input('nama_prodi_id');
        if ($prodiId) {
            $query->whereHas('mahasiswa', fn($q) => $q->where('nama_prodi_id', $prodiId));
        }

        $angkatanId = request()->input('angkatan_id');
        if ($angkatanId) {
            $query->whereHas('mahasiswa', fn($q) => $q->where('angkatan_id', $angkatanId));
        }

        $status = request()->input('status');
        if ($status) {
            $query->whereHas('mahasiswa', fn($q) => $q->where('status', $status));
        }

        $mhss = $query->get();

        $fakultas = Fakultas::orderBy('fakultas')->get();
        $prodis = ProgramStudi::orderBy('nama_prodi')->get();
        $angkatans = Angkatan::orderBy('tahun', 'desc')->get();

        return view('admin.data_mahasiswa.index', compact('mhss', 'fakultas', 'prodis', 'angkatans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email', 'unique:mahasiswas,email'],
            'nim' => ['required', 'string', 'max:50', 'unique:users,nim', 'unique:mahasiswas,nim'],
            'fakultas_id' => ['required', 'exists:fakultas,id'],
            'nama_prodi_id' => ['required', 'exists:program_studis,id'],
            'angkatan_id' => ['required', 'exists:angkatans,id'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $role = Role::where('nama_role', 'mahasiswa')->first();
        if (!$role) {
            return redirect()->back()->with('error', 'Role mahasiswa tidak ditemukan');
        }

        DB::transaction(function () use ($validated, $role) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'nim' => $validated['nim'],
                'password' => bcrypt('123'),
                'role_id' => $role->id,
                'status' => $validated['status'],
            ]);

            Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $validated['nim'],
                'fakultas_id' => $validated['fakultas_id'],
                'nama_prodi_id' => $validated['nama_prodi_id'],
                'angkatan_id' => $validated['angkatan_id'],
                'email' => $validated['email'],
                'status' => $validated['status'],
            ]);
        });

        return redirect()->back()->with('success', 'Mahasiswa berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($id);
        $user = $mahasiswa->user;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
                Rule::unique('mahasiswas', 'email')->ignore($mahasiswa->id),
            ],
            'nim' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'nim')->ignore($user?->id),
                Rule::unique('mahasiswas', 'nim')->ignore($mahasiswa->id),
            ],
            'fakultas_id' => ['required', 'exists:fakultas,id'],
            'nama_prodi_id' => ['required', 'exists:program_studis,id'],
            'angkatan_id' => ['required', 'exists:angkatans,id'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        DB::transaction(function () use ($validated, $mahasiswa, $user) {
            if ($user) {
                $user->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'nim' => $validated['nim'],
                    'status' => $validated['status'],
                ]);
            }

            $mahasiswa->update([
                'nim' => $validated['nim'],
                'fakultas_id' => $validated['fakultas_id'],
                'nama_prodi_id' => $validated['nama_prodi_id'],
                'angkatan_id' => $validated['angkatan_id'],
                'email' => $validated['email'],
                'status' => $validated['status'],
            ]);
        });

        return redirect()->back()->with('success', 'Mahasiswa berhasil diupdate');
    }

    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($id);

        DB::transaction(function () use ($mahasiswa) {
            if ($mahasiswa->user) {
                $mahasiswa->user->delete();
                return;
            }

            $mahasiswa->delete();
        });

        return redirect()->back()->with('success', 'Mahasiswa berhasil dihapus');
    }
}
