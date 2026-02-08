<?php

namespace Database\Seeders;

use App\Models\Fakultas;
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
         // Ambil fakultas berdasarkan kode
         $fakultas = Fakultas::whereIn('kode', ['FT', 'FEB', 'FIS'])->get()->keyBy('kode');
     
         $data = [
     
             // =========================
             // FAKULTAS TEKNIK
             // =========================
             ['kode' => 'TI',  'nama_prodi' => 'Teknik Informatika',        'fakultas' => 'FT'],
             ['kode' => 'SI',  'nama_prodi' => 'Sistem Informasi',         'fakultas' => 'FT'],
             ['kode' => 'RPL', 'nama_prodi' => 'Rekayasa Perangkat Lunak',  'fakultas' => 'FT'],
             ['kode' => 'TE',  'nama_prodi' => 'Teknik Elektro',           'fakultas' => 'FT'],
             ['kode' => 'TM',  'nama_prodi' => 'Teknik Mesin',            'fakultas' => 'FT'],
     
             // =========================
             // FAKULTAS EKONOMI & BISNIS
             // =========================
             ['kode' => 'AK',  'nama_prodi' => 'Akuntansi',                'fakultas' => 'FEB'],
             ['kode' => 'MNJ', 'nama_prodi' => 'Manajemen',               'fakultas' => 'FEB'],
             ['kode' => 'EP',  'nama_prodi' => 'Ekonomi Pembangunan',     'fakultas' => 'FEB'],
             ['kode' => 'KWU', 'nama_prodi' => 'Kewirausahaan',           'fakultas' => 'FEB'],
             ['kode' => 'PSA', 'nama_prodi' => 'Perbankan Syariah',       'fakultas' => 'FEB'],
     
             // =========================
             // FAKULTAS ILMU SOSIAL
             // =========================
             ['kode' => 'IK',  'nama_prodi' => 'Ilmu Komunikasi',          'fakultas' => 'FIS'],
             ['kode' => 'HI',  'nama_prodi' => 'Hubungan Internasional',  'fakultas' => 'FIS'],
             ['kode' => 'AP',  'nama_prodi' => 'Administrasi Publik',     'fakultas' => 'FIS'],
             ['kode' => 'SOS', 'nama_prodi' => 'Sosiologi',              'fakultas' => 'FIS'],
             ['kode' => 'KR',  'nama_prodi' => 'Kriminologi',            'fakultas' => 'FIS'],
         ];
     
         foreach ($data as $item) {
             $prodi = ProgramStudi::firstOrNew(['kode' => $item['kode']]);
             $prodi->nama_prodi = $item['nama_prodi'];
             $prodi->fakultas_id = $fakultas[$item['fakultas']]->id;
             $prodi->status = 'aktif';

             if ($prodi->s1 === null) {
                 $prodi->s1 = random_int(144, 160);
             }
             if ($prodi->d3 === null) {
                 $prodi->d3 = random_int(108, 120);
             }

             $prodi->save();
         }
}}
