<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProgramStudiController extends Controller
{
    public function index()
    {
        $query = ProgramStudi::with('fakultas')
            ->withCount(['mahasiswas'])
            ->addSelect([
                'dosens_count' => function ($sub) {
                    $sub->from('dosens')
                        ->whereExists(function ($q) {
                            $q->selectRaw(1)
                                ->from('kelas')
                                ->join('mata_kuliahs', 'mata_kuliahs.id', '=', 'kelas.mata_kuliah_id')
                                ->join('mata_kuliah_prodis', 'mata_kuliah_prodis.mata_kuliah_id', '=', 'mata_kuliahs.id')
                                ->whereColumn('kelas.dosen_id', 'dosens.id')
                                ->whereColumn('mata_kuliah_prodis.nama_prodi_id', 'program_studis.id')
                                ->where('kelas.status', 'aktif');
                        })
                        ->selectRaw('COUNT(DISTINCT dosens.id)');
                }
            ]);

        if (request('fakultas_id')) {
            $query->where('fakultas_id', request('fakultas_id'));
        }

        if (request('status')) {
            $query->where('status', request('status'));
        }

        if (request('q')) {
            $term = request('q');
            $query->where(function ($q) use ($term) {
                $q->where('kode', 'like', '%' . $term . '%')
                  ->orWhere('nama_prodi', 'like', '%' . $term . '%');
            });
        }

        $prodis = $query->orderBy('nama_prodi')->get();
        $fakultasList = Fakultas::orderBy('fakultas')->get();

        return view('admin.prodi.index', compact('prodis', 'fakultasList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:50', 'unique:program_studis,kode'],
            'nama_prodi' => ['required', 'string', 'max:255'],
            'fakultas_id' => ['required', 'exists:fakultas,id'],
            's1' => ['nullable', 'integer', 'min:144', 'max:160'],
            'd3' => ['nullable', 'integer', 'min:108', 'max:120'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        ProgramStudi::create([
            'kode' => $validated['kode'],
            'nama_prodi' => $validated['nama_prodi'],
            'fakultas_id' => $validated['fakultas_id'],
            's1' => $validated['s1'] ?? random_int(144, 160),
            'd3' => $validated['d3'] ?? random_int(108, 120),
            'status' => $validated['status'],
        ]);

        return redirect()->back()->with('success', 'Program studi berhasil ditambahkan');
    }

    public function update(Request $request, ProgramStudi $prodi)
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:50', Rule::unique('program_studis', 'kode')->ignore($prodi->id)],
            'nama_prodi' => ['required', 'string', 'max:255'],
            'fakultas_id' => ['required', 'exists:fakultas,id'],
            's1' => ['nullable', 'integer', 'min:144', 'max:160'],
            'd3' => ['nullable', 'integer', 'min:108', 'max:120'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $prodi->update($validated);

        return redirect()->back()->with('success', 'Program studi berhasil diperbarui');
    }

    public function destroy(ProgramStudi $prodi)
    {
        $prodi->delete();
        return redirect()->back()->with('success', 'Program studi berhasil dihapus');
    }
}
