<?php

namespace Database\Seeders;

use App\Models\Beasiswa;
use Illuminate\Database\Seeder;

class BeasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            'Beasiswa prestasi akademik',
            'Beasiswa KIP Kuliah',
            'Beasiswa Unggulan Kemendikbud',
        ];

        foreach ($items as $nama) {
            Beasiswa::updateOrCreate(
                ['nama' => $nama],
                ['nama' => $nama]
            );
        }
    }
}

