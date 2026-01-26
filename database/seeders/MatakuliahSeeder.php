<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MataKuliahSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('mata_kuliah')->insert([
            [
                'kode_mata_kuliah' => 'IF101',
                'mata_kuliah' => 'Pemrograman Web',
                'semester' => 'Genap 2025/2026',
                'sks' => 3,
                'dosen_id' => 1,
                'deskripsi' => 'Mata kuliah dasar pengembangan web menggunakan Laravel dan Tailwind CSS',
                'status' => 'aktif',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            
            
        ]);
        MataKuliah::factory()->count(9)->create();
    }
}
