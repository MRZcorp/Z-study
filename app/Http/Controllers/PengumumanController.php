<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    //
    public function index()
     {
       
        return view('admin.pengumuman');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required',
            'tipe' => 'required|in:info,peringatan,event',
        ]);

        Pengumuman::create($request->all());

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

   

}
