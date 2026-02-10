<?php

namespace Database\Seeders;

use App\Models\KrsSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class KrsSettingSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $mulai = $now->year;
        $akhir = $mulai + 1;
        $semesterAktif = 'ganjil';

        KrsSetting::insert([
            [
                'mulai_tahun_ajar' => $mulai,
                'akhir_tahun_ajar' => $akhir,
                'semester' => 'ganjil',
                'status' => $semesterAktif === 'ganjil' ? 'aktif' : 'nonaktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'mulai_tahun_ajar' => $mulai,
                'akhir_tahun_ajar' => $akhir,
                'semester' => 'genap',
                'status' => $semesterAktif === 'genap' ? 'aktif' : 'nonaktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
