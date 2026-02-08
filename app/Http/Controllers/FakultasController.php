<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FakultasController extends Controller
{
    public function index()
    {
        $query = Fakultas::withCount('programStudis');

        if (request('status')) {
            $query->where('status', request('status'));
        }

        if (request('q')) {
            $term = request('q');
            $query->where(function ($q) use ($term) {
                $q->where('kode', 'like', '%' . $term . '%')
                  ->orWhere('fakultas', 'like', '%' . $term . '%');
            });
        }

        $fakultasList = $query->orderBy('fakultas')->get();

        return view('admin.fakultas.index', compact('fakultasList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:50', 'unique:fakultas,kode'],
            'fakultas' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        Fakultas::create($validated);

        return redirect()->back()->with('success', 'Fakultas berhasil ditambahkan');
    }

    public function update(Request $request, Fakultas $fakultas)
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:50', Rule::unique('fakultas', 'kode')->ignore($fakultas->id)],
            'fakultas' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $fakultas->update($validated);

        return redirect()->back()->with('success', 'Fakultas berhasil diperbarui');
    }

    public function destroy(Fakultas $fakultas)
    {
        $fakultas->delete();
        return redirect()->back()->with('success', 'Fakultas berhasil dihapus');
    }
}
