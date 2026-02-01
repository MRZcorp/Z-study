<?php

namespace Database\Seeders;

use App\Models\MateriKelas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MateriKelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        MateriKelas::factory()->count(10)->create();
    }


  
}
