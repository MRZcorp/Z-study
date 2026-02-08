<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\MateriKelas;
use App\Models\MateriDownload;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    // Tampilkan daftar materi
    public function dosen()
    {
        $userId = session('user_id');
        $dosenId = Dosen::where('user_id', $userId)->value('id');

        $pilih_kelas = Kelas::with(['dosens.user', 'mataKuliah'])
            ->withCount('mahasiswas')
            ->when($dosenId, fn($q) => $q->where('dosen_id', $dosenId))
            ->latest()
            ->get();

        $kelasIds = $pilih_kelas->pluck('id');
        $materiStats = MateriKelas::whereIn('kelas_id', $kelasIds)
            ->get()
            ->groupBy('kelas_id')
            ->map(function ($items) {
                return [
                    'total' => $items->count(),
                    'pertemuan' => $items->pluck('pertemuan')->filter()->unique()->count(),
                ];
            });

        return view('dosen.materi.list_kelas', compact('pilih_kelas', 'materiStats'));
    }

    public function mahasiswa()
    {
        return view('mahasiswa.materi.materi', [
            'materi_kelas' => MateriKelas::latest()->get()
        ]);
    }

    public function mahasiswaKelas(Kelas $kelas)
    {
        $userId = session('user_id');
        $mahasiswaId = Mahasiswa::where('user_id', $userId)->value('id');

        if (!$mahasiswaId || !$kelas->mahasiswas()->where('mahasiswa_id', $mahasiswaId)->exists()) {
            return redirect()->route('mahasiswa.materi.kelas')
                ->with('error', 'Kelas tidak ditemukan');
        }

        $materiQuery = MateriKelas::query()
            ->where('kelas_id', $kelas->id)
            ->latest();

        if (request('pertemuan')) {
            $materiQuery->where('pertemuan', request('pertemuan'));
        }

        $materi_kelas = $materiQuery->get();

        $allMateri = MateriKelas::where('kelas_id', $kelas->id)->get(['id', 'pertemuan']);
        $downloadedIds = MateriDownload::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('materi_id', $allMateri->pluck('id'))
            ->pluck('materi_id')
            ->flip();

        $pertemuanBadge = collect(range(1, 14))->mapWithKeys(function ($i) use ($allMateri, $downloadedIds) {
            $items = $allMateri->where('pertemuan', $i);
            if ($items->isEmpty()) {
                return [$i => null];
            }
            $hasUndownloaded = $items->contains(function ($m) use ($downloadedIds) {
                return !$downloadedIds->has($m->id);
            });
            return [$i => $hasUndownloaded ? 'new' : 'done'];
        });

        $materi_total_count = MateriKelas::where('kelas_id', $kelas->id)->count();
        $materi_total_pertemuan = MateriKelas::where('kelas_id', $kelas->id)
            ->pluck('pertemuan')
            ->filter()
            ->unique()
            ->count();

        return view('mahasiswa.materi.materi', compact('kelas', 'materi_kelas', 'materi_total_count', 'materi_total_pertemuan', 'pertemuanBadge'));
    }

    public function dosenKelas(Kelas $kelas)
    {
        $userId = session('user_id');
        $dosenId = Dosen::where('user_id', $userId)->value('id');

        if (!$dosenId || $kelas->dosen_id !== $dosenId) {
            return redirect()->route('dosen.materi.kelas')
                ->with('error', 'Kelas tidak ditemukan');
        }

        $materiQuery = MateriKelas::query()
            ->where('kelas_id', $kelas->id)
            ->latest();

        if (request('pertemuan')) {
            $materiQuery->where('pertemuan', request('pertemuan'));
        }

        $materi_kelas = $materiQuery->get();

        $pertemuanHasMateri = MateriKelas::where('kelas_id', $kelas->id)
            ->pluck('pertemuan')
            ->filter()
            ->unique();

        $materi_total_count = MateriKelas::where('kelas_id', $kelas->id)->count();
        $materi_total_pertemuan = MateriKelas::where('kelas_id', $kelas->id)
            ->pluck('pertemuan')
            ->filter()
            ->unique()
            ->count();

        return view('dosen.materi.materi', compact('kelas', 'materi_kelas', 'materi_total_count', 'materi_total_pertemuan', 'pertemuanHasMateri'));
    }

    public function dosenRiwayat(Kelas $kelas)
    {
        $userId = session('user_id');
        $dosenId = Dosen::where('user_id', $userId)->value('id');

        if (!$dosenId || $kelas->dosen_id !== $dosenId) {
            return redirect()->route('dosen.kelas_riwayat')
                ->with('error', 'Kelas tidak ditemukan');
        }

        if (($kelas->status ?? '') !== 'selesai') {
            return redirect()->route('dosen.kelas')
                ->with('error', 'Kelas belum selesai');
        }

        $materiQuery = MateriKelas::query()
            ->where('kelas_id', $kelas->id)
            ->latest();

        if (request('pertemuan')) {
            $materiQuery->where('pertemuan', request('pertemuan'));
        }

        $materi_kelas = $materiQuery->get();

        $pertemuanHasMateri = MateriKelas::where('kelas_id', $kelas->id)
            ->pluck('pertemuan')
            ->filter()
            ->unique();

        $materi_total_count = MateriKelas::where('kelas_id', $kelas->id)->count();
        $materi_total_pertemuan = MateriKelas::where('kelas_id', $kelas->id)
            ->pluck('pertemuan')
            ->filter()
            ->unique()
            ->count();

        return view('dosen.materi.riwayat_materi', compact('kelas', 'materi_kelas', 'materi_total_count', 'materi_total_pertemuan', 'pertemuanHasMateri'));
    }

    public function listKelas()
    {
        $userId = session('user_id');
        $mahasiswaId = Mahasiswa::where('user_id', $userId)->value('id');

        $pilih_kelas = Kelas::with(['dosens.user', 'mataKuliah'])
            ->withCount('mahasiswas')
            ->when($mahasiswaId, fn($q) => $q->whereHas('mahasiswas', fn($mq) => $mq->where('mahasiswa_id', $mahasiswaId)))
            ->latest()
            ->get();

        $kelasIds = $pilih_kelas->pluck('id');
        $allMateri = MateriKelas::whereIn('kelas_id', $kelasIds)
            ->get(['id', 'kelas_id', 'pertemuan']);

        $materiStats = $allMateri
            ->groupBy('kelas_id')
            ->map(function ($items) {
                return [
                    'total' => $items->count(),
                    'pertemuan' => $items->pluck('pertemuan')->filter()->unique()->count(),
                ];
            });

        $downloadedIds = MateriDownload::where('mahasiswa_id', $mahasiswaId)
            ->whereIn('materi_id', $allMateri->pluck('id'))
            ->pluck('materi_id')
            ->flip();

        $materiBadgeByKelas = $allMateri
            ->groupBy('kelas_id')
            ->map(function ($items) use ($downloadedIds) {
                $hasUndownloaded = $items->contains(function ($m) use ($downloadedIds) {
                    return !$downloadedIds->has($m->id);
                });
                return $hasUndownloaded ? 'new' : null;
            });

        return view('mahasiswa.materi.list_kelas', compact('pilih_kelas', 'materiStats', 'materiBadgeByKelas'));
    }

    // SIMPAN DATA & FILE (INI STORE)
    public function store(Request $request)
    {
        $request->validate([
            'judul_materi' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'pertemuan' => 'required|integer|min:1|max:14',
            'deskripsi' => 'required|string',
            'file_materi' => 'required|file|max:51200'
        ]);

        $userId = session('user_id');
        $dosenId = Dosen::where('user_id', $userId)->value('id');
        $kelas = Kelas::with('mataKuliah')->findOrFail($request->kelas_id);
        if ($dosenId && $kelas->dosen_id !== $dosenId) {
            abort(403);
        }

        $matkul = $kelas->mataKuliah->mata_kuliah ?? $request->matkul;

        $file = $request->file('file_materi');
        $path = $file->store('materi', 'public');

        MateriKelas::create([
            'judul_materi' => $request->judul_materi,
            'matkul' => $matkul,
            'kelas_id' => $kelas->id,
            'pertemuan' => $request->pertemuan,
            'deskripsi' => $request->deskripsi,
            'file_path' => $path,
            'file_type' => $file->extension(),
            'file_size' => $file->getSize(),
        ]);

        return redirect()->back()
            ->with('success', 'Materi berhasil diupload!');
    }

    public function update(Request $request, MateriKelas $materi)
    {
        $request->validate([
            'judul_materi' => 'required|string|max:255',
            'pertemuan' => 'required|integer|min:1|max:14',
            'deskripsi' => 'required|string',
            'file_materi' => 'nullable|file|max:51200'
        ]);

        $materi->load('kelas.mataKuliah');
        $matkul = $request->matkul ?? $materi->matkul;
        if ($materi->kelas && $materi->kelas->mataKuliah) {
            $matkul = $materi->kelas->mataKuliah->mata_kuliah;
        }

        $data = [
            'judul_materi' => $request->judul_materi,
            'matkul' => $matkul,
            'pertemuan' => $request->pertemuan,
            'deskripsi' => $request->deskripsi,
        ];

        if ($request->hasFile('file_materi')) {
            $file = $request->file('file_materi');
            $path = $file->store('materi', 'public');
            $data['file_path'] = $path;
            $data['file_type'] = $file->extension();
            $data['file_size'] = $file->getSize();
        }

        $materi->update($data);

        return back()->with('success', 'Materi berhasil diupdate!');
    }

    public function destroy(MateriKelas $materi)
    {
        $materi->delete();
        return back()->with('success', 'Materi berhasil dihapus!');
    }

    public function downloadMahasiswa(MateriKelas $materi)
    {
        $userId = session('user_id');
        $mahasiswaId = Mahasiswa::where('user_id', $userId)->value('id');

        $materi->load('kelas');
        if (!$mahasiswaId || !$materi->kelas || !$materi->kelas->mahasiswas()->where('mahasiswa_id', $mahasiswaId)->exists()) {
            abort(403, 'Akses materi tidak diizinkan.');
        }

        MateriDownload::firstOrCreate(
            ['materi_id' => $materi->id, 'mahasiswa_id' => $mahasiswaId],
            ['downloaded_at' => now()]
        );

        if (!$materi->file_path || !Storage::disk('public')->exists($materi->file_path)) {
            abort(404, 'File materi tidak ditemukan.');
        }

        $fileName = basename($materi->file_path);
        return Storage::disk('public')->download($materi->file_path, $fileName);
    }
}
