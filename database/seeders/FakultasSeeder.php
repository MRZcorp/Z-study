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
            'fakultas' => 'Fakultas Teknik',
        ],
        [
            'kode' => 'FEB',
            'fakultas' => 'Fakultas Ekonomi dan Bisnis',
        ],
        [
            'kode' => 'FIS',
            'fakultas' => 'Fakultas Ilmu Sosial',
        ],
    ];

    foreach ($data as $item) {
        Fakultas::updateOrCreate(
            ['kode' => $item['kode']], // kunci unik
            [
                'fakultas' => $item['fakultas'],
                'status' => 'aktif',
            ]
        );
    }
}
}
