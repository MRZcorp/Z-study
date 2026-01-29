<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MateriKelas;

class MateriController extends Controller
{
    // Tampilkan daftar materi
    public function dosen()
    {
        return view('dosen.materi.materi', [
            'materi_kelas' => MateriKelas::latest()->get()
        ]);
    }




    public function mahasiswa()
    {
        return view('mahasiswa.materi.materi', [
            'materi_kelas' => MateriKelas::latest()->get()
        ]);
    }
    

    // SIMPAN DATA & FILE (INI STORE)
    public function store(Request $request)
    {

       // 1️⃣ Validasi
    $request->validate([
        'judul_materi' => 'required|string|max:255',
        'matkul' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'file_materi' => 'required|file|max:51200'
    ]);

    // 2️⃣ Ambil file
    $file = $request->file('file_materi');

    // 3️⃣ Simpan ke storage
    $path = $file->store('materi', 'public');

    // 4️⃣ Simpan ke database
    \App\Models\MateriKelas::create([
        'judul_materi' => $request->judul_materi,
        'matkul' => $request->matkul,
        'deskripsi' => $request->deskripsi,
        'file_path' => $path,
        'file_type' => $file->extension(),
        'file_size' => $file->getSize(),
    ]);

    // 5️⃣ Redirect
    return redirect('/dosen/materi')
        ->with('success', 'Materi berhasil diupload!');
}








}
