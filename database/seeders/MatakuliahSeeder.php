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

        // ====================================================
        // ================= MATA KULIAH PRODI ================
        // ====================================================
        foreach ($programStudis as $prodi) {

            // Ambil kode prodi (TI, SI, AK, dll)
            $prefix = $prodi->kode;

            $matkuls = [
                ['kode' => $prefix.'-101', 'nama' => 'Pengantar '.$prodi->nama_prodi,    'semester' => 'Ganjil', 'sks' => 2],
                ['kode' => $prefix.'-102', 'nama' => 'Dasar '.$prodi->nama_prodi,        'semester' => 'Genap',  'sks' => 3],
                ['kode' => $prefix.'-201', 'nama' => 'Metodologi '.$prodi->nama_prodi,   'semester' => 'Ganjil', 'sks' => 2],
                ['kode' => $prefix.'-202', 'nama' => 'Praktikum '.$prodi->nama_prodi,    'semester' => 'Genap',  'sks' => 1],
                ['kode' => $prefix.'-301', 'nama' => 'Proyek Akhir '.$prodi->nama_prodi, 'semester' => 'Genap',  'sks' => 4],
            ];

            foreach ($matkuls as $m) {

                // INSERT MATA KULIAH PRODI
                $matkulId = DB::table('mata_kuliahs')->insertGetId([
                    'kode_mata_kuliah' => $m['kode'],
                    'mata_kuliah'      => $m['nama'],
                    'semester'         => $m['semester'],
                    'sks'              => $m['sks'],
                    'tipe'             => 'prodi',
                    'status'           => 'aktif',
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);

                // RELASI KE PRODI (PIVOT)
                DB::table('mata_kuliah_prodis')->insert([
                    'mata_kuliah_id' => $matkulId,
                    'nama_prodi_id'  => $prodi->id,
                ]);
            }
        }

        // ====================================================
        // ================= MATA KULIAH UMUM =================
        // ======== (DIPINDAHKAN KE LUAR LOOP PRODI) ===========
        // ====================================================

        $matkulsUmum = [
            ['kode' => 'UM-101', 'nama' => 'Pendidikan Pancasila',       'semester' => 'Ganjil', 'sks' => 2],
            ['kode' => 'UM-102', 'nama' => 'Pendidikan Kewarganegaraan', 'semester' => 'Genap',  'sks' => 2],
            ['kode' => 'UM-201', 'nama' => 'Bahasa Indonesia',           'semester' => 'Ganjil', 'sks' => 2],
            ['kode' => 'UM-202', 'nama' => 'Bahasa Inggris',             'semester' => 'Genap',  'sks' => 2],
            ['kode' => 'UM-301', 'nama' => 'Kewirausahaan',              'semester' => 'Genap',  'sks' => 2],
        ];

        foreach ($matkulsUmum as $m) {

            // INSERT MATA KULIAH UMUM (HANYA SEKALI)
            $matkulId = DB::table('mata_kuliahs')->insertGetId([
                'kode_mata_kuliah' => $m['kode'],
                'mata_kuliah'      => $m['nama'],
                'semester'         => $m['semester'],
                'sks'              => $m['sks'],
                'tipe'             => 'umum',
                'status'           => 'aktif',
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            // RELASI KE SEMUA PRODI
            foreach ($programStudis as $prodi) {
                DB::table('mata_kuliah_prodis')->insert([
                    'mata_kuliah_id' => $matkulId,
                    'nama_prodi_id'  => $prodi->id,
                ]);
            }
        }
    }
}