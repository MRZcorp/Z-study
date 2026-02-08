<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengumpulanTugas;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class PengumpulanTugasController extends Controller
{
    public function save(Request $request)
    {
        $userId = session('user_id');
        $mahasiswaId = Mahasiswa::where('user_id', $userId)->value('id');

        if (!$mahasiswaId) {
            return redirect()->back()->with('error', 'Mahasiswa tidak ditemukan.');
        }

        $request->validate([
            'tugas_id' => 'required|exists:tugas,id',
            'deskripsi' => 'nullable|string',
            'file_tugas' => 'nullable|file|max:51200',
        ]);

        $pengumpulan = PengumpulanTugas::firstOrNew([
            'tugas_id' => $request->tugas_id,
            'mahasiswa_id' => $mahasiswaId,
        ]);

        $deskripsi = trim((string) $request->deskripsi);
        if ($deskripsi !== '') {
            $pengumpulan->deskripsi = $deskripsi;
        }

        if ($request->hasFile('file_tugas')) {
            if ($pengumpulan->file_path && Storage::disk('public')->exists($pengumpulan->file_path)) {
                Storage::disk('public')->delete($pengumpulan->file_path);
            }

            $file = $request->file('file_tugas');
            $path = $file->store('pengumpulan_tugas', 'public');
            $pengumpulan->file_path = $path;
            $pengumpulan->file_name = $file->getClientOriginalName();
        }

        if (!$pengumpulan->file_path && empty($pengumpulan->deskripsi)) {
            return redirect()->back()->with('error', 'File atau deskripsi harus diisi.');
        }

        $pengumpulan->save();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Tugas berhasil di upload',
                'file_name' => $pengumpulan->file_name,
                'file_path' => $pengumpulan->file_path,
            ]);
        }

        return redirect()->back()->with('success', 'Tugas berhasil di upload');
    }

    public function submit(Request $request)
    {
        $userId = session('user_id');
        $mahasiswaId = Mahasiswa::where('user_id', $userId)->value('id');

        if (!$mahasiswaId) {
            return redirect()->back()->with('error', 'Mahasiswa tidak ditemukan.');
        }

        $request->validate([
            'tugas_id' => 'required|exists:tugas,id',
            'deskripsi' => 'nullable|string',
            'file_tugas' => 'nullable|file|max:51200',
        ]);

        $pengumpulan = PengumpulanTugas::firstOrNew([
            'tugas_id' => $request->tugas_id,
            'mahasiswa_id' => $mahasiswaId,
        ]);

        $deskripsi = trim((string) $request->deskripsi);
        if ($deskripsi !== '') {
            $pengumpulan->deskripsi = $deskripsi;
        }

        if ($request->hasFile('file_tugas')) {
            if ($pengumpulan->file_path && Storage::disk('public')->exists($pengumpulan->file_path)) {
                Storage::disk('public')->delete($pengumpulan->file_path);
            }

            $file = $request->file('file_tugas');
            $path = $file->store('pengumpulan_tugas', 'public');
            $pengumpulan->file_path = $path;
            $pengumpulan->file_name = $file->getClientOriginalName();
        }

        if (!$pengumpulan->file_path && empty($pengumpulan->deskripsi)) {
            return redirect()->back()->with('error', 'File atau deskripsi harus diisi.');
        }

        $pengumpulan->submitted_at = Carbon::now();
        $pengumpulan->save();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Tugas dikumpulkan.',
            ]);
        }

        return redirect()->back()->with('success', 'Tugas dikumpulkan.');
    }
}
