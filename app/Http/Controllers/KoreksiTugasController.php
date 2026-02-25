<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class KoreksiTugasController extends Controller
{
    //
    public function index()
     {
        $userId = session('user_id');
        $dosenId = Dosen::where('user_id', $userId)->value('id');

        $kelas_dosen = Kelas::with('mataKuliah')
            ->when($dosenId, fn($q) => $q->where('dosen_id', $dosenId))
            ->get();

        $kelasIds = $kelas_dosen->pluck('id');
        $matkulList = $kelas_dosen->map(fn($k) => $k->mataKuliah)->filter()->unique('id')->values();

        $tugasQuery = Tugas::with([
                'mataKuliah',
                'kelas' => fn($q) => $q
                    ->withCount('mahasiswas')
                    ->with([
                        'dosens.user',
                        'dosens.fakultas',
                        'dosens.programStudi',
                        'mahasiswas.user',
                        'mahasiswas.fakultas',
                        'mahasiswas.programStudi',
                    ]),
                'files',
                'pengumpulan.mahasiswa.user',
            ])
            ->when($kelasIds->isNotEmpty(), fn($q) => $q->whereIn('nama_kelas_id', $kelasIds))
            ->whereHas('kelas', fn($q) => $q->where('status', 'aktif'))
            ->whereNotNull('deadline')
            ->where('deadline', '<', Carbon::now());

        if (request('matkul_id')) {
            $tugasQuery->where('mata_kuliah_id', request('matkul_id'));
        }

        $tugas_selesai = $tugasQuery->latest()->get();

        $tugasCountByKelas = Tugas::select('nama_kelas_id', DB::raw('count(*) as total'))
            ->when($kelasIds->isNotEmpty(), fn($q) => $q->whereIn('nama_kelas_id', $kelasIds))
            ->groupBy('nama_kelas_id')
            ->pluck('total', 'nama_kelas_id');

        return view('dosen.tugas.tugas_selesai', compact('tugas_selesai', 'matkulList', 'tugasCountByKelas'));
    }

    public function koreksi()
    {
        $userId = session('user_id');
        $dosenId = Dosen::where('user_id', $userId)->value('id');

        $kelas_dosen = Kelas::with('mataKuliah')
            ->when($dosenId, fn($q) => $q->where('dosen_id', $dosenId))
            ->get();

        $kelasId = request('kelas_id');
        $matkulId = request('matkul_id');
        $tugasId = request('tugas_id');

        $kelasName = '';
        $matkulName = '';
        $kuotaKelas = 0;
        $kumpulCount = 0;
        $tugasNama = '';
        $tugasKe = '';
        $tugasMulai = null;
        $tugasDeadline = null;

        $kelas = $kelas_dosen->firstWhere('id', (int) $kelasId);
        if ($kelas) {
            $kelasName = 'Kelas ' . ($kelas->nama_kelas ?? '');
            $matkulName = $kelas->mataKuliah->mata_kuliah ?? '';
            $kuotaKelas = $kelas->mahasiswas()->count();
        } else {
            $matkul = $kelas_dosen->map(fn($k) => $k->mataKuliah)->firstWhere('id', (int) $matkulId);
            if ($matkul) {
                $matkulName = $matkul->mata_kuliah ?? '';
            }
        }

        $pengumpulan = collect();
        $tidakMengumpulkan = collect();
        $nilaiMap = collect();
        if ($tugasId) {
            $tugas = Tugas::find($tugasId);
            if ($tugas) {
                $tugasNama = $tugas->nama_tugas ?? '';
                $tugasKe = $tugas->tugas_ke ?? '';
                $tugasMulai = $tugas->mulai_tugas ?? null;
                $tugasDeadline = $tugas->deadline ?? null;
                if (!$kelasName && $tugas->kelas) {
                    $kelasName = 'Kelas ' . ($tugas->kelas->nama_kelas ?? '');
                    $kuotaKelas = $tugas->kelas->mahasiswas()->count();
                }
                if (!$matkulName && $tugas->mataKuliah) {
                    $matkulName = $tugas->mataKuliah->mata_kuliah ?? '';
                }
                $pengumpulan = PengumpulanTugas::with('mahasiswa')
                    ->where('tugas_id', $tugasId)
                    ->whereNotNull('submitted_at')
                    ->orderBy('submitted_at')
                    ->get();
                $kumpulCount = $pengumpulan->count();

                if ($tugas->kelas) {
                    $kelasMahasiswa = $tugas->kelas->mahasiswas()
                        ->with(['user', 'programStudi'])
                        ->get();
                    $sudahKumpulIds = $pengumpulan->pluck('mahasiswa_id')->filter()->values();
                    $tidakMengumpulkan = $kelasMahasiswa->whereNotIn('id', $sudahKumpulIds);
                    $nilaiMap = PengumpulanTugas::where('tugas_id', $tugasId)
                        ->whereIn('mahasiswa_id', $kelasMahasiswa->pluck('id'))
                        ->get()
                        ->keyBy('mahasiswa_id');
                }
            }
        }

        return view('dosen.tugas.koreksi_tugas', compact('kelasName', 'matkulName', 'kuotaKelas', 'kumpulCount', 'tugasNama', 'tugasKe', 'tugasMulai', 'tugasDeadline', 'pengumpulan', 'tidakMengumpulkan', 'tugasId', 'nilaiMap'));
    }

    public function saveNilai(Request $request)
    {
        $request->validate([
            'id' => 'nullable|exists:pengumpulan_tugas,id',
            'mahasiswa_id' => 'nullable|exists:mahasiswas,id',
            'tugas_id' => 'nullable|exists:tugas,id',
            'nilai' => 'nullable|integer|min:0|max:100',
            'nilai_kecepatan' => 'nullable|numeric|min:0',
        ]);

        if ($request->id) {
            $pengumpulan = PengumpulanTugas::find($request->id);
        } else {
            $pengumpulan = PengumpulanTugas::firstOrNew([
                'tugas_id' => $request->tugas_id,
                'mahasiswa_id' => $request->mahasiswa_id,
            ]);
        }
        $pengumpulan->nilai = $request->nilai;
        if ($request->filled('nilai_kecepatan')) {
            $pengumpulan->nilai_kecepatan = $request->nilai_kecepatan;
        }
        $pengumpulan->save();

        return response()->json(['message' => 'Nilai berhasil disimpan']);
    }
}
