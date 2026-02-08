<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use App\Models\PengumumanRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
    //
    public function index()
     {
        $query = Pengumuman::query();

        if (request('status')) {
            $status = request('status');
            if ($status === 'publish') {
                $query->where('is_active', true);
            } elseif ($status === 'draft') {
                $query->where('is_active', false);
            }
        }

        if (request('tipe')) {
            $query->where('tipe', request('tipe'));
        }

        if (request('bulan')) {
            $bulanVal = request('bulan');
            if (strpos($bulanVal, '-') !== false) {
                [$y, $m] = explode('-', $bulanVal, 2);
                if (!empty($y) && !empty($m)) {
                    $query->whereYear('tanggal_publish', (int) $y)
                          ->whereMonth('tanggal_publish', (int) $m);
                }
            } else {
                $query->whereMonth('tanggal_publish', (int) $bulanVal);
            }
        }

        if (request('tahun')) {
            $query->whereYear('tanggal_publish', (int) request('tahun'));
        }

        if (request('q')) {
            $term = request('q');
            $query->where('judul', 'like', '%' . $term . '%');
        }

        $pengumumans = $query->orderByDesc('tanggal_publish')->latest()->get();

        return view('admin.pengumuman', compact('pengumumans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required',
            'tipe' => 'required|in:info,peringatan,event',
            'status' => 'required|in:publish,draft',
            'tanggal_publish' => 'nullable|date',
            'berkas' => 'nullable|file|max:5120',
        ]);

        $fileName = null;
        $filePath = null;
        if ($request->hasFile('berkas')) {
            $file = $request->file('berkas');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->store('pengumuman', 'public');
        }

        Pengumuman::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'tipe' => $request->tipe,
            'is_active' => $request->status === 'publish',
            'tanggal_publish' => $request->tanggal_publish,
            'file_name' => $fileName,
            'file_path' => $filePath,
        ]);

        return redirect()->back()->with('success', 'Pengumuman berhasil ditambahkan');
    }

    public function show($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        return view('pengumuman.show', compact('pengumuman'));
    }








    public function mahasiswa()
    {
        $pengumuman = Pengumuman::where('is_active', true)
            ->orderBy('tanggal_publish', 'desc')
            ->get();

        return view('mahasiswa.dashboard', compact('pengumuman'));
    }

 


    public function dosen()
    {
        $pengumuman = Pengumuman::where('is_active', true)
            ->orderBy('tanggal_publish', 'desc')
            ->get();

        return view('dashboard.dosen', compact('pengumuman'));
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required',
            'tipe' => 'required|in:info,peringatan,event',
            'status' => 'required|in:publish,draft',
            'tanggal_publish' => 'nullable|date',
            'berkas' => 'nullable|file|max:5120',
        ]);

        $updateData = [
            'judul' => $request->judul,
            'isi' => $request->isi,
            'tipe' => $request->tipe,
            'is_active' => $request->status === 'publish',
            'tanggal_publish' => $request->tanggal_publish,
        ];

        if ($request->hasFile('berkas')) {
            if (!empty($pengumuman->file_path)) {
                Storage::disk('public')->delete($pengumuman->file_path);
            }
            $file = $request->file('berkas');
            $updateData['file_name'] = $file->getClientOriginalName();
            $updateData['file_path'] = $file->store('pengumuman', 'public');
        }

        $pengumuman->update($updateData);

        return redirect()->back()->with('success', 'Pengumuman berhasil diperbarui');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        if (!empty($pengumuman->file_path)) {
            Storage::disk('public')->delete($pengumuman->file_path);
        }
        $pengumuman->delete();
        return redirect()->back()->with('success', 'Pengumuman berhasil dihapus');
    }

    public function markReadAll(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return response()->json(['ok' => false], 401);
        }

        $pengumumanIds = Pengumuman::where('is_active', true)->pluck('id');
        $now = now();

        $rows = $pengumumanIds->map(function ($id) use ($userId, $now) {
            return [
                'user_id' => $userId,
                'pengumuman_id' => $id,
                'read_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->all();

        if (!empty($rows)) {
            PengumumanRead::upsert(
                $rows,
                ['user_id', 'pengumuman_id'],
                ['read_at', 'updated_at']
            );
        }

        return response()->json(['ok' => true]);
    }

   

}
