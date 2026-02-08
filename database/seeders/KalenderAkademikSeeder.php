<?php

namespace Database\Seeders;

use App\Models\KalenderAkademik;
use Illuminate\Database\Seeder;

class KalenderAkademikSeeder extends Seeder
{
    public function run(): void
    {
        KalenderAkademik::insert([
            [
                'judul' => 'Awal Perkuliahan',
                'tanggal_mulai' => '2020-08-01',
                'tanggal_selesai' => '2021-06-30',
                'keterangan' => 'Tahun Ajaran 2020/2021',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Awal Perkuliahan',
                'tanggal_mulai' => '2025-08-01',
                'tanggal_selesai' => '2026-06-30',
                'keterangan' => 'Tahun Ajaran 2025/2026',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
