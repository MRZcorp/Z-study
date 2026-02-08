<?php

namespace Database\Seeders;

use App\Models\MateriKelas;
use App\Models\Kelas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MateriKelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Kelas::query()->exists()) {
            return;
        }

        MateriKelas::factory()->count(10)->create();
    }


  
}
