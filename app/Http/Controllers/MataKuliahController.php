<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MataKuliahController extends Controller
{
    //
    // Tampilkan daftar materi
    
  
   
public function index()
{
   $query = MataKuliah::with(['programStudis'])->withCount('kelas');

   if (request('prodi_id')) {
       $query->whereHas('programStudis', fn($q) => $q->where('program_studis.id', request('prodi_id')));
   }

   if (request('status')) {
       $query->where('status', request('status'));
   }

   if (request('semester')) {
       $query->where('semester', request('semester'));
   }

   if (request('q')) {
       $term = request('q');
       $query->where(function ($q) use ($term) {
           $q->where('kode_mata_kuliah', 'like', '%' . $term . '%')
             ->orWhere('mata_kuliah', 'like', '%' . $term . '%');
       });
   }

   $matkuls = $query->orderByDesc('kelas_count')->latest()->get();
   $prodis = ProgramStudi::orderBy('nama_prodi')->get();

return view('admin.mata_kuliah.index', compact('matkuls', 'prodis'));
  
}

public function store(Request $request)
{
    $validated = $request->validate([
        'kode_mata_kuliah' => ['required', 'string', 'max:50', 'unique:mata_kuliahs,kode_mata_kuliah'],
        'mata_kuliah' => ['required', 'string', 'max:255'],
        'semester' => ['required', 'in:ganjil,genap'],
        'sks' => ['required', 'integer', 'min:1', 'max:24'],
        'status' => ['required', 'in:aktif,nonaktif'],
        'prodi_ids' => ['nullable', 'array'],
        'prodi_ids.*' => ['exists:program_studis,id'],
    ]);

    $matkul = MataKuliah::create([
        'kode_mata_kuliah' => $validated['kode_mata_kuliah'],
        'mata_kuliah' => $validated['mata_kuliah'],
        'semester' => $validated['semester'],
        'sks' => $validated['sks'],
        'status' => $validated['status'],
    ]);

    if (!empty($validated['prodi_ids'])) {
        $matkul->programStudis()->sync($validated['prodi_ids']);
    }

    return redirect()->back()->with('success', 'Mata kuliah berhasil ditambahkan');
}

public function update(Request $request, MataKuliah $mataKuliah)
{
    $validated = $request->validate([
        'kode_mata_kuliah' => ['required', 'string', 'max:50', Rule::unique('mata_kuliahs', 'kode_mata_kuliah')->ignore($mataKuliah->id)],
        'mata_kuliah' => ['required', 'string', 'max:255'],
        'semester' => ['required', 'in:ganjil,genap'],
        'sks' => ['required', 'integer', 'min:1', 'max:24'],
        'status' => ['required', 'in:aktif,nonaktif'],
        'prodi_ids' => ['nullable', 'array'],
        'prodi_ids.*' => ['exists:program_studis,id'],
    ]);

    $mataKuliah->update([
        'kode_mata_kuliah' => $validated['kode_mata_kuliah'],
        'mata_kuliah' => $validated['mata_kuliah'],
        'semester' => $validated['semester'],
        'sks' => $validated['sks'],
        'status' => $validated['status'],
    ]);

    $mataKuliah->programStudis()->sync($validated['prodi_ids'] ?? []);

    return redirect()->back()->with('success', 'Mata kuliah berhasil diperbarui');
}

public function destroy(MataKuliah $mataKuliah)
{
    $mataKuliah->programStudis()->detach();
    $mataKuliah->delete();
    return redirect()->back()->with('success', 'Mata kuliah berhasil dihapus');
}
    
}








