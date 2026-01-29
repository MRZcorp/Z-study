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
        // Ambil semua prodi
        $programStudis = DB::table('program_studis')->get();

        foreach ($programStudis as $prodi) {

            $matkul = [
                [
                    'kode' => 'MK-' . $prodi->id . '-01',
                    'nama' => 'Pengantar ' . $prodi->nama_prodi,
                    'semester' => 'Ganjil',
                    'sks' => 2
                ],
                [
                    'kode' => 'MK-' . $prodi->id . '-02',
                    'nama' => 'Dasar ' . $prodi->nama_prodi,
                    'semester' => 'Genap',
                    'sks' => 3
                ],
                [
                    'kode' => 'MK-' . $prodi->id . '-03',
                    'nama' => 'Metodologi ' . $prodi->nama_prodi,
                    'semester' => 'Ganjil',
                    'sks' => 2
                ],
                [
                    'kode' => 'MK-' . $prodi->id . '-04',
                    'nama' => 'Praktikum ' . $prodi->nama_prodi,
                    'semester' => 'Genap',
                    'sks' => 1
                ],
                [
                    'kode' => 'MK-' . $prodi->id . '-05',
                    'nama' => 'Proyek Akhir ' . $prodi->nama_prodi,
                    'semester' => 'Genap',
                    'sks' => 4
                ],
            ];

            foreach ($matkul as $m) {
                DB::table('mata_kuliahs')->insert([
                    'kode_mata_kuliah' => $m['kode'],
                    'mata_kuliah' => $m['nama'],
                    'nama_prodi_id' => $prodi->id,
                    'semester' => $m['semester'],
                    'sks' => $m['sks'],
                    'status' => 'aktif',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
