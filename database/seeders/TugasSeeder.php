<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\MataKuliah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tugas;

class TugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Tugas::create([
            'nama_kelas_id' => '4',
            'mata_kuliah_id' => '2',
            'nama_tugas' => 'Tugas ERD',
            'detail_tugas' => 'Buat ERD Sistem Akademik',
            'file_tugas' => 'tugas/erd.pdf',
            'deadline' => '2026-02-10 23:59:00'
        ]);
        Tugas::factory()->count(9)->create();
    }
}
