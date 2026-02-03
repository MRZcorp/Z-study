<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FakultasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $data = [
        [
            'kode' => 'FT',
            'nama_fakultas' => 'Fakultas Teknik',
        ],
        [
            'kode' => 'FEB',
            'nama_fakultas' => 'Fakultas Ekonomi dan Bisnis',
        ],
        [
            'kode' => 'FIS',
            'nama_fakultas' => 'Fakultas Ilmu Sosial',
        ],
    ];

    foreach ($data as $item) {
        Fakultas::updateOrCreate(
            ['kode' => $item['kode']], // kunci unik
            [
                'fakultas' => $item['nama_fakultas'],
                'status' => 'aktif',
            ]
        );
    }
}
}