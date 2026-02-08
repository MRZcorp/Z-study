<?php

namespace Database\Seeders;

use App\Models\Angkatan;
use Illuminate\Database\Seeder;

class AngkatanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['tahun' => 2021, 'tahun_masuk' => 2021, 'status' => 'aktif'],
            ['tahun' => 2020, 'tahun_masuk' => 2020, 'status' => 'lulus'],
            ['tahun' => 2022, 'tahun_masuk' => 2022, 'status' => 'aktif'],
            ['tahun' => 2023, 'tahun_masuk' => 2023, 'status' => 'aktif'],
        ];

        foreach ($data as $item) {
            Angkatan::updateOrCreate(
                ['tahun' => $item['tahun']],
                $item
            );
        }
    }
}
