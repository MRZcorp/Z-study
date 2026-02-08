<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\DosenWali;
use App\Models\Fakultas;
use App\Models\Kelas;
use App\Models\Pengumuman;
use App\Models\ProgramStudi;
use App\Models\Angkatan;
use App\Models\User;
use Illuminate\Http\Request;

class DataDosenController extends Controller
{
    //
    
    public function index() 
    {
        $query = User::with(['dosens.fakultas', 'dosens.programStudi', 'role'])
            ->whereHas('role', fn($q) => $q->where('nama_role','dosen'));

        if (request('fakultas_id')) {
            $query->whereHas('dosens', fn($q) => $q->where('fakultas_id', request('fakultas_id')));
        }

        if (request('nama_prodi_id')) {
            $query->whereHas('dosens', fn($q) => $q->where('nama_prodi_id', request('nama_prodi_id')));
        }

        if (request('jabatan')) {
            $query->whereHas('dosens', fn($q) => $q->where('status', request('jabatan')));
        }

        if (request('status')) {
            $query->where('status', request('status'));
        }

        if (request('q')) {
            $term = request('q');
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', '%' . $term . '%')
                  ->orWhere('email', 'like', '%' . $term . '%')
                  ->orWhere('nidn', 'like', '%' . $term . '%');
            });
        }

        $dosens = $query->get();

        $fakultas = Fakultas::orderBy('fakultas')->get();
        $prodis = ProgramStudi::orderBy('nama_prodi')->get();
      
        return view('admin.data_dosen.index', compact('dosens', 'fakultas', 'prodis'));
       
    }

    public function wali()
    {
        $query = DosenWali::with(['dosen.user.role', 'dosen.fakultas', 'programStudi', 'angkatan']);

        if (request('fakultas_id')) {
            $query->whereHas('dosen', fn($q) => $q->where('fakultas_id', request('fakultas_id')));
        }

        if (request('nama_prodi_id')) {
            $query->where('nama_prodi_id', request('nama_prodi_id'));
        }

        if (request('jabatan')) {
            $query->whereHas('dosen', fn($q) => $q->where('status', request('jabatan')));
        }

        if (request('status')) {
            $query->whereHas('angkatan', fn($q) => $q->where('status', request('status')));
        }

        if (request('q')) {
            $term = request('q');
            $query->where(function ($q) use ($term) {
                $q->whereHas('dosen.user', function ($uq) use ($term) {
                    $uq->where('name', 'like', '%' . $term . '%')
                       ->orWhere('email', 'like', '%' . $term . '%')
                       ->orWhere('nidn', 'like', '%' . $term . '%');
                });
            });
        }

        $dosenWalis = $query->get();
        $fakultas = Fakultas::orderBy('fakultas')->get();
        $prodis = ProgramStudi::orderBy('nama_prodi')->get();
        $angkatans = Angkatan::orderBy('tahun', 'desc')->get();
        $dosenList = Dosen::with('user')->orderBy('id')->get();

        return view('admin.data_dosen.dosen_wali', compact('dosenWalis', 'fakultas', 'prodis', 'angkatans', 'dosenList'));
    }

    public function storeWali(Request $request)
    {
        $validated = $request->validate([
            'dosen_id' => ['required', 'exists:dosens,id'],
            'nama_prodi_id' => ['required', 'exists:program_studis,id'],
            'angkatan_id' => ['required', 'exists:angkatans,id'],
        ]);

        DosenWali::create($validated);

        return redirect()->back()->with('success', 'Dosen wali berhasil ditambahkan');
    }

    public function updateWali(Request $request, DosenWali $dosenWali)
    {
        $validated = $request->validate([
            'dosen_id' => ['required', 'exists:dosens,id'],
            'nama_prodi_id' => ['required', 'exists:program_studis,id'],
            'angkatan_id' => ['required', 'exists:angkatans,id'],
        ]);

        $dosenWali->update($validated);

        return redirect()->back()->with('success', 'Dosen wali berhasil diperbarui');
    }

    public function destroyWali(DosenWali $dosenWali)
    {
        $dosenWali->delete();

        return redirect()->back()->with('success', 'Dosen wali berhasil dihapus');
    }



}
