<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\MateriKelas;
use App\Models\Pengumuman;
use App\Models\Tugas;
use App\Models\Ujian;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $term = trim((string) $request->query('q', ''));
        if ($term === '') {
            return response()->json(['items' => []]);
        }

        $role = strtolower((string) session('nama_role'));
        $userId = session('user_id');

        $items = [];
        $push = function (string $type, string $label, string $url) use (&$items) {
            $items[] = [
                'type' => $type,
                'label' => $label,
                'url' => $url,
            ];
        };

        if ($role === 'admin') {
            $users = User::with('role')
                ->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                      ->orWhere('username', 'like', "%{$term}%")
                      ->orWhere('email', 'like', "%{$term}%")
                      ->orWhere('nim', 'like', "%{$term}%")
                      ->orWhere('nidn', 'like', "%{$term}%");
                })
                ->limit(5)
                ->get();
            foreach ($users as $user) {
                $roleName = $user->role->nama_role ?? 'User';
                $label = "{$user->name} ({$roleName})";
                $push('User', $label, url('/admin/user_setting'));
            }

            $mataKuliahs = MataKuliah::where('mata_kuliah', 'like', "%{$term}%")
                ->limit(5)
                ->get();
            foreach ($mataKuliahs as $mk) {
                $push('Mata Kuliah', $mk->mata_kuliah, url('/admin/kelola_mata_kuliah'));
            }

            $kelas = Kelas::where('nama_kelas', 'like', "%{$term}%")
                ->limit(5)
                ->get();
            foreach ($kelas as $k) {
                $push('Kelas', $k->nama_kelas, url('/admin/data_kelas'));
            }

            $jadwal = Kelas::where(function ($q) use ($term) {
                    $q->where('hari_kelas', 'like', "%{$term}%")
                      ->orWhere('jadwal_kelas', 'like', "%{$term}%");
                })
                ->limit(5)
                ->get();
            foreach ($jadwal as $j) {
                $label = $j->jadwal_kelas ?: $j->hari_kelas;
                $push('Jadwal', $label ?: 'Jadwal Kelas', url('/admin/kelola_jadwal'));
            }

            $pengumuman = Pengumuman::where('judul', 'like', "%{$term}%")
                ->limit(5)
                ->get();
            foreach ($pengumuman as $p) {
                $push('Pengumuman', $p->judul, url('/admin/pengumuman'));
            }
        }

        if ($role === 'dosen') {
            $dosenId = Dosen::where('user_id', $userId)->value('id');
            $kelasIds = Kelas::when($dosenId, fn($q) => $q->where('dosen_id', $dosenId))
                ->pluck('id');

            $kelas = Kelas::when($dosenId, fn($q) => $q->where('dosen_id', $dosenId))
                ->where('nama_kelas', 'like', "%{$term}%")
                ->limit(5)
                ->get();
            foreach ($kelas as $k) {
                $push('Kelas', $k->nama_kelas, url('/dosen/kelas'));
            }

            $materi = MateriKelas::when($kelasIds->isNotEmpty(), fn($q) => $q->whereIn('kelas_id', $kelasIds))
                ->where('judul_materi', 'like', "%{$term}%")
                ->limit(5)
                ->get();
            foreach ($materi as $m) {
                $push('Materi', $m->judul_materi, url('/dosen/materi'));
            }

            $tugas = Tugas::when($kelasIds->isNotEmpty(), fn($q) => $q->whereIn('nama_kelas_id', $kelasIds))
                ->where('nama_tugas', 'like', "%{$term}%")
                ->limit(5)
                ->get();
            foreach ($tugas as $t) {
                $push('Tugas', $t->nama_tugas, url('/dosen/tugas'));
            }

            $ujian = Ujian::when($kelasIds->isNotEmpty(), fn($q) => $q->whereIn('nama_kelas_id', $kelasIds))
                ->where('nama_ujian', 'like', "%{$term}%")
                ->limit(5)
                ->get();
            foreach ($ujian as $u) {
                $push('Ujian', $u->nama_ujian, url('/dosen/ujian'));
            }

            $pengumuman = Pengumuman::where('is_active', true)
                ->where('judul', 'like', "%{$term}%")
                ->limit(5)
                ->get();
            foreach ($pengumuman as $p) {
                $push('Pengumuman', $p->judul, url('/dosen'));
            }
        }

        if ($role === 'mahasiswa') {
            $mahasiswaId = Mahasiswa::where('user_id', $userId)->value('id');
            $kelasIds = Kelas::query()
                ->when($mahasiswaId, fn($q) => $q->whereHas('mahasiswas', fn($mq) => $mq->where('mahasiswa_id', $mahasiswaId)))
                ->pluck('id');

            $kelas = Kelas::when($kelasIds->isNotEmpty(), fn($q) => $q->whereIn('id', $kelasIds))
                ->where('nama_kelas', 'like', "%{$term}%")
                ->limit(5)
                ->get();
            foreach ($kelas as $k) {
                $push('Kelas', $k->nama_kelas, url('/mahasiswa/kelas'));
            }

            $materi = MateriKelas::when($kelasIds->isNotEmpty(), fn($q) => $q->whereIn('kelas_id', $kelasIds))
                ->where('judul_materi', 'like', "%{$term}%")
                ->limit(5)
                ->get();
            foreach ($materi as $m) {
                $push('Materi', $m->judul_materi, url('/mahasiswa/materi'));
            }

            $tugas = Tugas::when($kelasIds->isNotEmpty(), fn($q) => $q->whereIn('nama_kelas_id', $kelasIds))
                ->where('nama_tugas', 'like', "%{$term}%")
                ->limit(5)
                ->get();
            foreach ($tugas as $t) {
                $push('Tugas', $t->nama_tugas, url('/mahasiswa/tugas'));
            }

            $ujian = Ujian::when($kelasIds->isNotEmpty(), fn($q) => $q->whereIn('nama_kelas_id', $kelasIds))
                ->where('nama_ujian', 'like', "%{$term}%")
                ->limit(5)
                ->get();
            foreach ($ujian as $u) {
                $push('Ujian', $u->nama_ujian, url('/mahasiswa/ujian'));
            }

            $pengumuman = Pengumuman::where('is_active', true)
                ->where('judul', 'like', "%{$term}%")
                ->limit(5)
                ->get();
            foreach ($pengumuman as $p) {
                $push('Pengumuman', $p->judul, url('/mahasiswa'));
            }
        }

        return response()->json(['items' => $items]);
    }
}
