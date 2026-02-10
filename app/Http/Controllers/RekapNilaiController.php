<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Tugas;
use App\Models\Ujian;
use App\Models\PengumpulanTugas;
use App\Models\HasilUjian;
use App\Models\RekapNilai;
use App\Models\RekapBobot;

class RekapNilaiController extends Controller
{
    //
    public function index()
     {
        $userId = session('user_id');
        $dosenId = Dosen::where('user_id', $userId)->value('id');

        $kelasList = Kelas::with(['mataKuliah', 'dosens', 'mahasiswas'])
            ->when($dosenId, fn($q) => $q->where('dosen_id', $dosenId))
            ->withCount('mahasiswas')
            ->orderBy('nama_kelas')
            ->get();

        return view('dosen.rekap.list_kelas', compact('kelasList'));
    }

    public function show(Kelas $kelas)
    {
        $kelas->load(['mataKuliah', 'dosens', 'mahasiswas.user', 'mahasiswas.programStudi']);

        $tugasList = Tugas::where('nama_kelas_id', $kelas->id)
            ->orderBy('created_at')
            ->get();

        $ujianList = Ujian::where('nama_kelas_id', $kelas->id)
            ->orderBy('created_at')
            ->get();

        $mahasiswaIds = $kelas->mahasiswas->pluck('id');
        $tugasIds = $tugasList->pluck('id');
        $ujianIds = $ujianList->pluck('id');

        $pengumpulanMap = PengumpulanTugas::whereIn('tugas_id', $tugasIds)
            ->whereIn('mahasiswa_id', $mahasiswaIds)
            ->get()
            ->groupBy(['mahasiswa_id', 'tugas_id']);

        $hasilUjianMap = HasilUjian::whereIn('ujian_id', $ujianIds)
            ->whereIn('mahasiswa_id', $mahasiswaIds)
            ->get()
            ->groupBy(['mahasiswa_id', 'ujian_id']);

        $rekapMap = RekapNilai::where('kelas_id', $kelas->id)
            ->whereIn('mahasiswa_id', $mahasiswaIds)
            ->get()
            ->keyBy('mahasiswa_id');

        $bobot = RekapBobot::where('kelas_id', $kelas->id)->first();

        return view('dosen.rekap.rekap', compact(
            'kelas',
            'tugasList',
            'ujianList',
            'pengumpulanMap',
            'hasilUjianMap',
            'rekapMap',
            'bobot'
        ));
    }

    public function sync(Request $request, Kelas $kelas)
    {
        $records = $request->input('records', []);
        if (!is_array($records)) {
            return response()->json(['message' => 'Format data tidak valid.'], 422);
        }

        $kelas->load('mahasiswas:id');
        $allowedIds = $kelas->mahasiswas->pluck('id')->flip();

        foreach ($records as $record) {
            $mahasiswaId = (int) ($record['mahasiswa_id'] ?? 0);
            if ($mahasiswaId === 0 || !isset($allowedIds[$mahasiswaId])) {
                continue;
            }

            RekapNilai::updateOrCreate(
                [
                    'kelas_id' => $kelas->id,
                    'mahasiswa_id' => $mahasiswaId,
                ],
                [
                    'rata_tugas' => (float) ($record['rata_tugas'] ?? 0),
                    'rata_kecepatan_tugas' => (float) ($record['rata_kecepatan_tugas'] ?? 0),
                    'rata_ujian' => (float) ($record['rata_ujian'] ?? 0),
                    'rata_kecepatan_ujian' => (float) ($record['rata_kecepatan_ujian'] ?? 0),
                    'keaktifan' => $record['keaktifan'] ?? null,
                    'absensi' => isset($record['absensi']) ? (int) $record['absensi'] : null,
                ]
            );
        }

        return response()->json(['message' => 'Rekap tersimpan.']);
    }

    public function saveBobot(Request $request, Kelas $kelas)
    {
        $data = $request->validate([
            'harian' => ['required', 'numeric', 'min:0'],
            'keaktifan' => ['required', 'numeric', 'min:0'],
            'kecepatan' => ['required', 'numeric', 'min:0'],
            'absensi' => ['required', 'numeric', 'min:0'],
            'uts' => ['required', 'numeric', 'min:0'],
            'uas' => ['required', 'numeric', 'min:0'],
        ]);

        $bobot = RekapBobot::updateOrCreate(
            ['kelas_id' => $kelas->id],
            [
                'harian' => $data['harian'],
                'keaktifan' => $data['keaktifan'],
                'kecepatan' => $data['kecepatan'],
                'absensi' => $data['absensi'],
                'uts' => $data['uts'],
                'uas' => $data['uas'],
            ]
        );

        return response()->json(['message' => 'Bobot tersimpan.', 'bobot' => $bobot]);
    }
}
