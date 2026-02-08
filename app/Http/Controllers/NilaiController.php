<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Tugas;
use App\Models\Ujian;
use App\Models\PengumpulanTugas;
use App\Models\HasilUjian;
use App\Models\RekapNilai;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class NilaiController extends Controller
{
    //
    public function dosen() {
        return view('dosen.nilai');

    }

    public function mahasiswa() {
        $userId = session('user_id');
        $mahasiswaId = Mahasiswa::where('user_id', $userId)->value('id');

        $kelasIds = Kelas::query()
            ->when($mahasiswaId, fn($q) => $q->whereHas('mahasiswas', fn($mq) => $mq->where('mahasiswa_id', $mahasiswaId)))
            ->pluck('id');

        if ($kelasIds->isEmpty()) {
            return view('mahasiswa.nilai', [
                'nilaiRows' => collect(),
                'radarLabels' => ['Kehadiran', 'Keaktifan', 'Tugas', 'Ujian', 'Kecepatan'],
                'radarData' => [0, 0, 0, 0, 0],
            ]);
        }

        $nilaiRows = collect();
        $tugasScores = [];
        $ujianScores = [];

        $tugasSelesai = Tugas::with([
                'mataKuliah',
                'kelas',
                'pengumpulan' => function ($q) use ($mahasiswaId) {
                    if ($mahasiswaId) {
                        $q->where('mahasiswa_id', $mahasiswaId);
                    }
                },
            ])
            ->whereIn('nama_kelas_id', $kelasIds)
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
            ->get();

        foreach ($tugasSelesai as $tugas) {
            $pengumpulan = ($tugas->pengumpulan ?? collect())->first();
            $nilai = $pengumpulan->nilai ?? null;
            $submitted = $pengumpulan && $pengumpulan->submitted_at;
            $deadlinePast = $tugas->deadline ? Carbon::parse($tugas->deadline)->isPast() : false;
            $tidakMengumpulkan = !$submitted && $deadlinePast;

            $nilaiLabel = is_null($nilai) ? '-' : $nilai;
            $keterangan = 'Belum Dinilai';
            if ($tidakMengumpulkan) {
                $nilaiLabel = 0;
                $keterangan = 'Tidak Mengumpulkan';
            } elseif (!is_null($nilai)) {
                if ($nilai < 60) {
                    $keterangan = 'Kurang';
                } elseif ($nilai < 70) {
                    $keterangan = 'Cukup';
                } elseif ($nilai < 90) {
                    $keterangan = 'Baik';
                } else {
                    $keterangan = 'Sangat Baik';
                }
            }

            $nilaiRows->push([
                'mata_kuliah' => $tugas->mataKuliah->mata_kuliah ?? '-',
                'jenis' => 'Tugas',
                'judul' => $tugas->nama_tugas ?? '-',
                'nilai' => $nilaiLabel,
                'keterangan' => $keterangan,
            ]);
            if (is_numeric($nilaiLabel)) {
                $tugasScores[] = (float) $nilaiLabel;
            }
        }

        $ujianSelesai = Ujian::with([
                'mataKuliah',
                'kelas',
                'hasilUjian' => function ($q) use ($mahasiswaId) {
                    if ($mahasiswaId) {
                        $q->where('mahasiswa_id', $mahasiswaId);
                    }
                },
            ])
            ->whereIn('nama_kelas_id', $kelasIds)
            ->whereNotNull('deadline')
            ->where(function ($q) use ($mahasiswaId) {
                $q->where('deadline', '<', Carbon::now())
                  ->orWhereHas('hasilUjian', function ($hq) use ($mahasiswaId) {
                      if ($mahasiswaId) {
                          $hq->where('mahasiswa_id', $mahasiswaId)->whereNotNull('submitted_at');
                      }
                  });
            })
            ->get();

        foreach ($ujianSelesai as $ujian) {
            $hasil = ($ujian->hasilUjian ?? collect())->first();
            $nilai = $hasil->nilai ?? null;
            $submitted = $hasil && $hasil->submitted_at;
            $deadlinePast = $ujian->deadline ? Carbon::parse($ujian->deadline)->isPast() : false;
            $tidakMengumpulkan = !$submitted && $deadlinePast;

            $nilaiLabel = is_null($nilai) ? '-' : $nilai;
            $keterangan = 'Belum Dinilai';
            if ($tidakMengumpulkan) {
                $nilaiLabel = 0;
                $keterangan = 'Tidak Mengumpulkan';
            } elseif (!is_null($nilai)) {
                if ($nilai < 60) {
                    $keterangan = 'Kurang';
                } elseif ($nilai < 70) {
                    $keterangan = 'Cukup';
                } elseif ($nilai < 90) {
                    $keterangan = 'Baik';
                } else {
                    $keterangan = 'Sangat Baik';
                }
            }

            $nilaiRows->push([
                'mata_kuliah' => $ujian->mataKuliah->mata_kuliah ?? '-',
                'jenis' => 'Ujian',
                'judul' => $ujian->nama_ujian ?? '-',
                'nilai' => $nilaiLabel,
                'keterangan' => $keterangan,
            ]);
            if (is_numeric($nilaiLabel)) {
                $ujianScores[] = (float) $nilaiLabel;
            }
        }

        $rekapQuery = RekapNilai::where('mahasiswa_id', $mahasiswaId);
        $kehadiran = (float) ($rekapQuery->avg('absensi') ?? 0);
        $keaktifan = (float) ($rekapQuery->avg('keaktifan') ?? 0);

        $tugasAvg = count($tugasScores) ? (array_sum($tugasScores) / count($tugasScores)) : 0;
        $ujianAvg = count($ujianScores) ? (array_sum($ujianScores) / count($ujianScores)) : 0;

        $tugasIds = $tugasSelesai->pluck('id');
        $ujianIds = $ujianSelesai->pluck('id');

        $kecepatanTugas = 0;
        if ($tugasIds->isNotEmpty()) {
            $kecepatanTugas = (float) (PengumpulanTugas::where('mahasiswa_id', $mahasiswaId)
                ->whereIn('tugas_id', $tugasIds)
                ->avg('nilai_kecepatan') ?? 0);
        }

        $kecepatanUjian = 0;
        if ($ujianIds->isNotEmpty() && Schema::hasColumn('hasil_ujians', 'nilai_kecepatan')) {
            $kecepatanUjian = (float) (HasilUjian::where('mahasiswa_id', $mahasiswaId)
                ->whereIn('ujian_id', $ujianIds)
                ->avg('nilai_kecepatan') ?? 0);
        }

        $kecepatanValues = array_filter([$kecepatanTugas, $kecepatanUjian], fn ($v) => $v !== null && $v !== 0.0);
        $kecepatanAvg = count($kecepatanValues) ? (array_sum($kecepatanValues) / count($kecepatanValues)) : 0;

        $radarLabels = ['Kehadiran', 'Keaktifan', 'Tugas', 'Ujian', 'Kecepatan'];
        $radarData = [
            round($kehadiran, 2),
            round($keaktifan, 2),
            round($tugasAvg, 2),
            round($ujianAvg, 2),
            round($kecepatanAvg, 2),
        ];

        return view('mahasiswa.nilai', [
            'nilaiRows' => $nilaiRows,
            'radarLabels' => $radarLabels,
            'radarData' => $radarData,
        ]);

    }
}
