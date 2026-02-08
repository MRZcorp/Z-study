<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Fakultas;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class JadwalKelasController extends Controller
{
    //
    public function index()
    {
       $query = Kelas::with(['mataKuliah', 'dosens.user'])->withCount('mahasiswas');

       if (request('hari_kelas')) {
           $query->where('hari_kelas', request('hari_kelas'));
       }

       if (request('fakultas_id')) {
           $query->whereHas('mataKuliah.programStudis.fakultas', function ($fq) {
               $fq->where('id', request('fakultas_id'));
           });
       }

       if (request('prodi_id')) {
           $query->whereHas('mataKuliah.programStudis', function ($pq) {
               $pq->where('program_studis.id', request('prodi_id'));
           });
       }

       if (request('status')) {
           $query->where('status', request('status'));
       }

       if (request('q')) {
           $term = request('q');
           $query->where(function ($q) use ($term) {
               $q->where('nama_kelas', 'like', '%' . $term . '%')
                 ->orWhere('jadwal_kelas', 'like', '%' . $term . '%')
                 ->orWhereHas('mataKuliah', fn($mq) => $mq->where('mata_kuliah', 'like', '%' . $term . '%'))
                 ->orWhereHas('dosens.user', fn($dq) => $dq->where('name', 'like', '%' . $term . '%'));
           });
       }

       $jadwals = $query->latest()->get();
       $fakultasList = Fakultas::orderBy('fakultas')->get();
       $prodis = ProgramStudi::orderBy('nama_prodi')->get();

       return view('admin.kelola_jadwal', compact('jadwals', 'fakultasList', 'prodis'));
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
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        Kelas::create($validated);

        return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan');
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
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $kelas->update($validated);

        return redirect()->back()->with('success', 'Jadwal berhasil diperbarui');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();
        return redirect()->back()->with('success', 'Jadwal berhasil dihapus');
    }

    
}
