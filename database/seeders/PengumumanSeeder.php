<?php

namespace Database\Seeders;

use App\Models\Pengumuman;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PengumumanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = Carbon::now();

        $items = [
            [
                'judul' => 'Pembukaan KRS Semester Genap',
                'isi' => 'Pengisian KRS untuk semester genap sudah dibuka. Silakan ajukan mata kuliah dan tunggu persetujuan dosen wali.',
                'tipe' => 'info',
                'is_active' => true,
                'tanggal_publish' => $today->copy()->subDays(2)->toDateString(),
            ],
            [
                'judul' => 'Batas Akhir Pengisian KRS',
                'isi' => 'Pengisian KRS ditutup pada akhir minggu ini. Pastikan semua mata kuliah sudah diajukan.',
                'tipe' => 'peringatan',
                'is_active' => true,
                'tanggal_publish' => $today->copy()->subDay()->toDateString(),
            ],
            [
                'judul' => 'Jadwal Ujian Tengah Semester',
                'isi' => 'Jadwal UTS telah dirilis. Cek menu Ujian untuk melihat jadwal dan mata kuliah yang diikuti.',
                'tipe' => 'info',
                'is_active' => true,
                'tanggal_publish' => $today->copy()->toDateString(),
            ],
            [
                'judul' => 'Pengumuman Pemeliharaan Sistem',
                'isi' => 'Akan dilakukan pemeliharaan sistem pada malam hari. Layanan mungkin tidak tersedia sementara.',
                'tipe' => 'peringatan',
                'is_active' => true,
                'tanggal_publish' => $today->copy()->addDay()->toDateString(),
            ],
            [
                'judul' => 'Workshop Akademik: Tips Skripsi',
                'isi' => 'Ikuti workshop akademik tentang penyusunan skripsi dan manajemen waktu.',
                'tipe' => 'event',
                'is_active' => true,
                'tanggal_publish' => $today->copy()->addDays(2)->toDateString(),
            ],
            [
                'judul' => 'Perubahan Jadwal Kelas Praktikum',
                'isi' => 'Beberapa kelas praktikum mengalami perubahan jadwal. Periksa detail pada halaman Kelas.',
                'tipe' => 'info',
                'is_active' => true,
                'tanggal_publish' => $today->copy()->subDays(3)->toDateString(),
            ],
            [
                'judul' => 'Pengumuman Beasiswa Prestasi',
                'isi' => 'Pendaftaran beasiswa prestasi dibuka. Silakan unggah berkas melalui menu Pengumuman.',
                'tipe' => 'event',
                'is_active' => true,
                'tanggal_publish' => $today->copy()->addDays(5)->toDateString(),
            ],
        ];

        foreach ($items as $item) {
            Pengumuman::create($item);
        }
    }
}
