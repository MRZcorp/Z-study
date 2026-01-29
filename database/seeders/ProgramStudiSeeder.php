<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramStudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   
        public function run(): void
    {
        $data = [

            // =========================
            // FAKULTAS TEKNIK
            // =========================
            ['kode' => 'TI',  'nama_prodi' => 'Teknik Informatika',       'fakultas' => 'Fakultas Teknik'],
            ['kode' => 'SI',  'nama_prodi' => 'Sistem Informasi',        'fakultas' => 'Fakultas Teknik'],
            ['kode' => 'RPL', 'nama_prodi' => 'Rekayasa Perangkat Lunak','fakultas' => 'Fakultas Teknik'],
            ['kode' => 'TE',  'nama_prodi' => 'Teknik Elektro',          'fakultas' => 'Fakultas Teknik'],
            ['kode' => 'TM',  'nama_prodi' => 'Teknik Mesin',           'fakultas' => 'Fakultas Teknik'],

            // =========================
            // FAKULTAS EKONOMI & BISNIS
            // =========================
            ['kode' => 'AK',  'nama_prodi' => 'Akuntansi',               'fakultas' => 'Fakultas Ekonomi Dan Bisnis'],
            ['kode' => 'MNJ', 'nama_prodi' => 'Manajemen',              'fakultas' => 'Fakultas Ekonomi Dan Bisnis'],
            ['kode' => 'EP',  'nama_prodi' => 'Ekonomi Pembangunan',    'fakultas' => 'Fakultas Ekonomi Dan Bisnis'],
            ['kode' => 'KWU', 'nama_prodi' => 'Kewirausahaan',          'fakultas' => 'Fakultas Ekonomi Dan Bisnis'],
            ['kode' => 'PSA', 'nama_prodi' => 'Perbankan Syariah',      'fakultas' => 'Fakultas Ekonomi Dan Bisnis'],

            // =========================
            // FAKULTAS ILMU SOSIAL
            // =========================
            ['kode' => 'IK',  'nama_prodi' => 'Ilmu Komunikasi',         'fakultas' => 'Fakultas Ilmu Sosial'],
            ['kode' => 'HI',  'nama_prodi' => 'Hubungan Internasional', 'fakultas' => 'Fakultas Ilmu Sosial'],
            ['kode' => 'AP',  'nama_prodi' => 'Administrasi Publik',   'fakultas' => 'Fakultas Ilmu Sosial'],
            ['kode' => 'SOS', 'nama_prodi' => 'Sosiologi',             'fakultas' => 'Fakultas Ilmu Sosial'],
            ['kode' => 'KR',  'nama_prodi' => 'Kriminologi',           'fakultas' => 'Fakultas Ilmu Sosial'],
        ];

        foreach ($data as $item) {
            ProgramStudi::create([
                'kode' => $item['kode'],
                'nama_prodi' => $item['nama_prodi'],
                'fakultas' => $item['fakultas'],
                'status' => 'aktif',
            ]);
        }
    }
}