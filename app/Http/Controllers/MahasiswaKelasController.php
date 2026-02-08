<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\DosenWali;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MahasiswaKelasController extends Controller
{
    public function index()
    {
        $userId = session('user_id');
        $mahasiswaId = Mahasiswa::where('user_id', $userId)->value('id');

        $pilih_kelas = Kelas::with(['dosens.user', 'mataKuliah', 'mahasiswas.user'])
            ->withCount([
                'mahasiswas as mahasiswas_count' => function ($q) {
                    $q->where('kelas_mahasiswa.status', 'disetujui');
                }
            ])
            ->when($mahasiswaId, fn($q) => $q->whereHas('mahasiswas', function ($mq) use ($mahasiswaId) {
                $mq->where('mahasiswa_id', $mahasiswaId)
                    ->where('kelas_mahasiswa.status', 'disetujui');
            }))
            ->where('status', 'aktif')
            ->latest()
            ->get();

        return view('mahasiswa.kelas.kelas_saya', compact('pilih_kelas'));
    }

    public function tersedia()
    {
        $userId = session('user_id');
        $mahasiswa = Mahasiswa::where('user_id', $userId)->first();
        $mahasiswaId = $mahasiswa?->id;
        $prodiId = $mahasiswa?->nama_prodi_id;
        $angkatanId = $mahasiswa?->angkatan_id;

        $dosenWaliKontak = null;
        if ($prodiId && $angkatanId) {
            $dosenWaliKontak = DosenWali::with('dosen.user')
                ->where('nama_prodi_id', $prodiId)
                ->where('angkatan_id', $angkatanId)
                ->first();
        }

        $pilih_kelas = Kelas::with([
                'dosens.user',
                'mataKuliah',
                'mahasiswas' => function ($q) use ($mahasiswaId) {
                    if ($mahasiswaId) {
                        $q->where('mahasiswa_id', $mahasiswaId);
                    }
                },
            ])
            ->withCount([
                'mahasiswas as mahasiswas_count' => function ($q) {
                    $q->where('kelas_mahasiswa.status', 'disetujui');
                }
            ])
            ->when($mahasiswaId, fn($q) => $q->whereDoesntHave('mahasiswas', function ($mq) use ($mahasiswaId) {
                $mq->where('mahasiswa_id', $mahasiswaId)
                    ->where('kelas_mahasiswa.status', 'disetujui');
            }))
            ->when($prodiId, fn($q) => $q->whereHas('mataKuliah.programStudis', fn($mq) => $mq->where('program_studis.id', $prodiId)))
            ->where('status', 'aktif')
            ->latest()
            ->get();

        return view('mahasiswa.kelas.kelas_tersedia', compact('pilih_kelas', 'dosenWaliKontak'));
    }

    public function riwayat()
    {
        $userId = session('user_id');
        $mahasiswaId = Mahasiswa::where('user_id', $userId)->value('id');

        $riwayat_kelas = Kelas::with(['dosens.user', 'mataKuliah', 'mahasiswas.user'])
            ->withCount('mahasiswas')
            ->when($mahasiswaId, fn($q) => $q->whereHas('mahasiswas', fn($mq) => $mq->where('mahasiswa_id', $mahasiswaId)))
            ->where('status', 'selesai')
            ->latest()
            ->get();

        return view('mahasiswa.kelas.riwayat_kelas', compact('riwayat_kelas'));
    }

    public function ikuti(Request $request, Kelas $kelas)
    {
        $userId = session('user_id');
        $mahasiswa = Mahasiswa::where('user_id', $userId)->first();

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan');
        }

        $sudahIkut = $kelas->mahasiswas()->where('mahasiswa_id', $mahasiswa->id)->exists();
        if ($sudahIkut) {
            return redirect()->back()->with('info', 'Kamu sudah mengajukan kelas ini');
        }

        if ($kelas->mahasiswas()->count() >= $kelas->kuota_maksimal) {
            return redirect()->back()->with('error', 'Kuota kelas sudah penuh');
        }

        DB::transaction(function () use ($kelas, $mahasiswa) {
            $kelas->mahasiswas()->attach($mahasiswa->id, ['status' => 'menunggu']);
            $kelas->increment('kuota_terdaftar');
        });

        return redirect()->back()->with('success', 'Permintaan mengikuti kelas telah dikirim');
    }
}
