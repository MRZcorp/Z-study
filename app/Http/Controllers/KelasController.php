<?php

namespace App\Http\Controllers;


use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    //
    // Tampilkan daftar materi
    
    
    public function dosen()
     {

        $pilih_kelas = Kelas::with('dosens', 'mataKuliah')->latest()->get();

    return view('dosen.kelas.kelas', compact('pilih_kelas'));
       
    }
    public function admin()
     {
        $pilih_kelas = Kelas::with('dosens')->latest()->get();

    return view('admin.kelola_kelas', compact('pilih_kelas'));
       
    }
    

   public function mahasiswa()
{
    
    $pilih_kelas = Kelas::with('dosens')->latest()->get();

    return view('mahasiswa.kelas.kelas_saya', compact('pilih_kelas'));
}




   /**
     * Tampilkan form buat kelas
     */
  

    /**
     * Simpan data kelas
     */
    public function store(Request $request)
    {
       
        $request->validate([
            'mata_kuliah' => 'required|string|max:100',
            'sks' => 'required|string|max:100',
            'nama_kelas' => 'required|string|max:10',
            'jadwal_kelas' => 'required|string|max:50',
            'hari_kelas' => 'required|string|max:20',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'kuota_maksimal' => 'required|integer|min:1',
            'bg_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        
        $bg_image = null;
        if ($request->hasFile('bg_image')) {
            $bg_image = $request->file('bg_image')->store('img', 'public');
        }
        
            Kelas::create([
            'dosen_id' => 8,
            'mata_kuliah' => $request->mata_kuliah,
            'sks' => $request->sks,
            'nama_kelas' => $request->nama_kelas,
            'jadwal_kelas' => $request->jadwal_kelas,
            'hari_kelas' => $request->hari_kelas,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'kuota_maksimal' => $request->kuota_maksimal,
            'bg_image' => $bg_image,
        ]);
        
        return redirect()
            ->route('dosen.kelas')
            ->with('success', 'Kelas berhasil dibuat');
    }

    
   

  
   
   
}
    

