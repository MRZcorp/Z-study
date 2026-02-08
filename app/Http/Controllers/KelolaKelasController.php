<?php

namespace App\Http\Controllers;


use App\Models\Kelas;
use App\Models\Dosen;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class KelolaKelasController extends Controller
{
    //
    // Tampilkan daftar materi
    
    public function index()
    {
       $query = Kelas::with(['mataKuliah', 'dosens.user'])
           ->withCount('mahasiswas');

       if (request('mata_kuliah_id')) {
           $query->where('mata_kuliah_id', request('mata_kuliah_id'));
       }

       if (request('semester')) {
           $query->where('semester', request('semester'));
       }

       if (request('tahun_ajar')) {
           $query->where('tahun_ajar', request('tahun_ajar'));
       }

       if (request('status')) {
           $query->where('status', request('status'));
       }

       if (request('q')) {
           $term = request('q');
           $query->where(function ($q) use ($term) {
               $q->where('nama_kelas', 'like', '%' . $term . '%')
                 ->orWhereHas('mataKuliah', fn($mq) => $mq->where('mata_kuliah', 'like', '%' . $term . '%'))
                 ->orWhereHas('dosens.user', fn($dq) => $dq->where('name', 'like', '%' . $term . '%'));
           });
       }

       $kelasList = $query->latest()->get();

       $mataKuliahs = MataKuliah::orderBy('mata_kuliah')->get();
       $dosens = Dosen::with('user')->orderBy('id')->get();
       $tahunAjars = Kelas::query()
           ->whereNotNull('tahun_ajar')
           ->pluck('tahun_ajar')
           ->unique()
           ->values();

       return view('admin.data_kelas.index', compact('kelasList', 'mataKuliahs', 'dosens', 'tahunAjars'));
   }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mata_kuliah_id' => ['required', 'exists:mata_kuliahs,id'],
            'dosen_id' => ['required', 'exists:dosens,id'],
            'nama_kelas' => ['required', 'string', 'max:50'],
            'jadwal_kelas' => ['required', 'string', 'max:50'],
            'hari_kelas' => ['required', 'string', 'max:20'],
            'jam_mulai' => ['required'],
            'jam_selesai' => ['required', 'after:jam_mulai'],
            'kuota_maksimal' => ['required', 'integer', 'min:1'],
            'tahun_ajar' => ['nullable', 'string', 'max:20'],
            'semester' => ['nullable', 'in:ganjil,genap'],
            'status' => ['required', 'in:draft,aktif,selesai'],
        ]);

        Kelas::create($validated);

        return redirect()->back()->with('success', 'Kelas berhasil ditambahkan');
    }

    public function update(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'mata_kuliah_id' => ['required', 'exists:mata_kuliahs,id'],
            'dosen_id' => ['required', 'exists:dosens,id'],
            'nama_kelas' => ['required', 'string', 'max:50'],
            'jadwal_kelas' => ['required', 'string', 'max:50'],
            'hari_kelas' => ['required', 'string', 'max:20'],
            'jam_mulai' => ['required'],
            'jam_selesai' => ['required', 'after:jam_mulai'],
            'kuota_maksimal' => ['required', 'integer', 'min:1'],
            'tahun_ajar' => ['nullable', 'string', 'max:20'],
            'semester' => ['nullable', 'in:ganjil,genap'],
            'status' => ['required', 'in:draft,aktif,selesai'],
        ]);

        $kelas->update($validated);

        return redirect()->back()->with('success', 'Kelas berhasil diperbarui');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();
        return redirect()->back()->with('success', 'Kelas berhasil dihapus');
    }
    
    
}
    
