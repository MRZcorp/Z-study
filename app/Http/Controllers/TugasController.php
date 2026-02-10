<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\TugasFile;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\PengumpulanTugas;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TugasController extends Controller
{
    //
    // Tampilkan daftar materi

    public function dosen()
    {
        $userId = session('user_id');
        $dosenId = Dosen::where('user_id', $userId)->value('id');

        $kelas_dosen = Kelas::with('mataKuliah')
            ->when($dosenId, fn($q) => $q->where('dosen_id', $dosenId))
            ->orderBy('nama_kelas')
            ->get();

        $kelasIds = $kelas_dosen->pluck('id');

        $tugas_kelas = Tugas::with(['mataKuliah', 'kelas', 'files'])
            ->when($kelasIds->isNotEmpty(), fn($q) => $q->whereIn('nama_kelas_id', $kelasIds))
            ->whereHas('kelas', fn($q) => $q->where('status', 'aktif'))
            ->latest()
            ->get();

        $tugasFileRows = TugasFile::whereIn('tugas_id', $tugas_kelas->pluck('id'))
            ->get()
            ->groupBy('tugas_id');

        $tugasCountByKelas = Tugas::select('nama_kelas_id', DB::raw('count(*) as total'))
            ->when($kelasIds->isNotEmpty(), fn($q) => $q->whereIn('nama_kelas_id', $kelasIds))
            ->groupBy('nama_kelas_id')
            ->pluck('total', 'nama_kelas_id');

        return view('dosen.tugas.tugas', compact('tugas_kelas', 'kelas_dosen', 'tugasCountByKelas', 'tugasFileRows'));
        
    }


    public function mahasiswa()
    {
        $tugas_kelas = $this->getMahasiswaTugasAktif();
        return view('mahasiswa.tugas.tugas', compact('tugas_kelas'));
        
    }

    public function mahasiswaSelesai()
    {
        $tugas_kelas = $this->getMahasiswaTugasSelesai();
        return view('mahasiswa.tugas.tugas_selesai', compact('tugas_kelas'));
    }

    private function updatePendingPengumpulan(?int $mahasiswaId): void
    {
        if (!$mahasiswaId) {
            return;
        }

        $pendingPengumpulan = PengumpulanTugas::with('tugas')
            ->where('mahasiswa_id', $mahasiswaId)
            ->whereNull('submitted_at')
            ->whereHas('tugas', function ($q) {
                $q->whereNotNull('deadline')
                  ->where('deadline', '<', Carbon::now());
            })
            ->get();

        foreach ($pendingPengumpulan as $row) {
            $hasData = !empty($row->file_path) || !empty($row->deskripsi);
            if ($hasData) {
                $deadline = $row->tugas && $row->tugas->deadline
                    ? Carbon::parse($row->tugas->deadline)
                    : Carbon::now();
                $row->submitted_at = $deadline;
                $row->save();
            }
        }
    }

    private function getMahasiswaTugasAktif()
    {
        $userId = session('user_id');
        $mahasiswaId = Mahasiswa::where('user_id', $userId)->value('id');

        $this->updatePendingPengumpulan($mahasiswaId);

        $kelasIds = Kelas::query()
            ->when($mahasiswaId, fn($q) => $q->whereHas('mahasiswas', function ($mq) use ($mahasiswaId) {
                $mq->where('mahasiswa_id', $mahasiswaId)
                    ->where(function ($sq) {
                        $sq->whereNull('kelas_mahasiswa.status')
                            ->orWhere('kelas_mahasiswa.status', 'disetujui');
                    });
            }))
            ->pluck('id');

        if ($kelasIds->isEmpty()) {
            return collect();
        }

        $tugas = Tugas::with([
                'mataKuliah',
                'kelas',
                'files',
                'pengumpulan' => function ($q) use ($mahasiswaId) {
                    if ($mahasiswaId) {
                        $q->where('mahasiswa_id', $mahasiswaId);
                    }
                },
            ])
            ->whereIn('nama_kelas_id', $kelasIds)
            ->whereHas('kelas', fn($q) => $q->where('status', 'aktif'))
            ->where(function ($q) {
                $q->whereNull('deadline')
                  ->orWhere('deadline', '>=', Carbon::now());
            })
            ->whereDoesntHave('pengumpulan', function ($q) use ($mahasiswaId) {
                if ($mahasiswaId) {
                    $q->where('mahasiswa_id', $mahasiswaId)
                      ->whereNotNull('submitted_at');
                }
            })
            ->latest()
            ->get();

        $tugasFileRows = TugasFile::whereIn('tugas_id', $tugas->pluck('id'))
            ->get()
            ->groupBy('tugas_id');

        $tugas->each(function ($row) use ($tugasFileRows) {
            $row->setRelation('files', $tugasFileRows->get($row->id, collect()));
        });

        return $tugas;
    }

    private function getMahasiswaTugasSelesai()
    {
        $userId = session('user_id');
        $mahasiswaId = Mahasiswa::where('user_id', $userId)->value('id');

        $this->updatePendingPengumpulan($mahasiswaId);

        $kelasIds = Kelas::query()
            ->when($mahasiswaId, fn($q) => $q->whereHas('mahasiswas', function ($mq) use ($mahasiswaId) {
                $mq->where('mahasiswa_id', $mahasiswaId)
                    ->where(function ($sq) {
                        $sq->whereNull('kelas_mahasiswa.status')
                            ->orWhere('kelas_mahasiswa.status', 'disetujui');
                    });
            }))
            ->pluck('id');

        if ($kelasIds->isEmpty()) {
            return collect();
        }

        $tugas = Tugas::with([
                'mataKuliah',
                'kelas',
                'files',
                'pengumpulan' => function ($q) use ($mahasiswaId) {
                    if ($mahasiswaId) {
                        $q->where('mahasiswa_id', $mahasiswaId);
                    }
                },
            ])
            ->whereIn('nama_kelas_id', $kelasIds)
            ->whereHas('kelas', fn($q) => $q->where('status', 'aktif'))
            ->where(function ($q) use ($mahasiswaId) {
                $q->whereHas('pengumpulan', function ($pq) use ($mahasiswaId) {
                        if ($mahasiswaId) {
                            $pq->where('mahasiswa_id', $mahasiswaId)
                               ->whereNotNull('submitted_at');
                        }
                    })
                  ->orWhere(function ($dq) {
                      $dq->whereNotNull('deadline')
                         ->where('deadline', '<', Carbon::now());
                  });
            })
            ->latest()
            ->get();

        $tugasFileRows = TugasFile::whereIn('tugas_id', $tugas->pluck('id'))
            ->get()
            ->groupBy('tugas_id');

        $tugas->each(function ($row) use ($tugasFileRows) {
            $row->setRelation('files', $tugasFileRows->get($row->id, collect()));
        });

        return $tugas;
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas_id' => 'required|exists:kelas,id',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'nama_tugas' => 'required|string|max:255',
            'detail_tugas' => 'nullable|string',
            'mulai_tugas' => 'required|date',
            'deadline' => 'required|date|after:mulai_tugas',
            'file_tugas' => 'nullable',
            'file_tugas.*' => 'file|max:51200',
        ]);

        $lastNumber = Tugas::where('nama_kelas_id', $request->nama_kelas_id)->max('tugas_ke') ?? 0;
        $nextNumber = $lastNumber + 1;

        $tugas = Tugas::create([
            'nama_kelas_id' => $request->nama_kelas_id,
            'tugas_ke' => $nextNumber,
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'nama_tugas' => $request->nama_tugas,
            'detail_tugas' => $request->detail_tugas,
            'mulai_tugas' => $request->mulai_tugas,
            'deadline' => $request->deadline,
        ]);

        if ($request->hasFile('file_tugas')) {
            $files = $request->file('file_tugas');
            if (!is_array($files)) {
                $files = [$files];
            }
            foreach ($files as $file) {
                $path = $file->store('tugas', 'public');
                TugasFile::create([
                    'tugas_id' => $tugas->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->extension(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Tugas berhasil dibuat');
    }

    public function update(Request $request, Tugas $tugas)
    {
        $request->validate([
            'nama_kelas_id' => 'required|exists:kelas,id',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'nama_tugas' => 'required|string|max:255',
            'detail_tugas' => 'nullable|string',
            'mulai_tugas' => 'required|date',
            'deadline' => 'required|date|after:mulai_tugas',
            'file_tugas' => 'nullable',
            'file_tugas.*' => 'file|max:51200',
        ]);

        if ($tugas->nama_kelas_id != $request->nama_kelas_id) {
            $lastNumber = Tugas::where('nama_kelas_id', $request->nama_kelas_id)->max('tugas_ke') ?? 0;
            $tugas->tugas_ke = $lastNumber + 1;
        }

        $tugas->update([
            'nama_kelas_id' => $request->nama_kelas_id,
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'nama_tugas' => $request->nama_tugas,
            'detail_tugas' => $request->detail_tugas,
            'mulai_tugas' => $request->mulai_tugas,
            'deadline' => $request->deadline,
        ]);

        if ($request->hasFile('file_tugas')) {
            $files = $request->file('file_tugas');
            if (!is_array($files)) {
                $files = [$files];
            }
            foreach ($files as $file) {
                $path = $file->store('tugas', 'public');
                TugasFile::create([
                    'tugas_id' => $tugas->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->extension(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Tugas berhasil diperbarui');
    }

    public function destroy(Tugas $tugas)
    {
        $tugas->load('files');
        foreach ($tugas->files as $file) {
            if ($file->file_path && Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }
        }

        if ($tugas->file_tugas && Storage::disk('public')->exists($tugas->file_tugas)) {
            Storage::disk('public')->delete($tugas->file_tugas);
        }

        $tugas->delete();

        return redirect()->back()->with('success', 'Tugas berhasil dihapus');
    }

    
}
