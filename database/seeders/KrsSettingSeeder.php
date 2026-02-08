<?php

namespace Database\Seeders;

use App\Models\KrsSetting;
use Illuminate\Database\Seeder;

class KrsSettingSeeder extends Seeder
{
    public function run(): void
    {
        KrsSetting::insert([
            [
                'mulai_tahun_ajar' => 2020,
                'akhir_tahun_ajar' => 2021,
                'semester' => 'ganjil',
                'status' => 'nonaktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mulai_tahun_ajar' => 2020,
                'akhir_tahun_ajar' => 2021,
                'semester' => 'genap',
                'status' => 'nonaktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
