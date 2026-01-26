<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\User;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        

        // DATA MANUAL (PASTI ADA)
        Kelas::create([
            'dosen_id' => '1',
            'mata_kuliah' => 'Grafika Komputer',
            'sks' => 3,
            'nama_kelas' => 'A',
            'jadwal_kelas'=> 'Reguler Pagi',
            'hari_kelas' => 'Senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '11:30',
            
            'kuota_maksimal' => 15,
            'kuota_terdaftar' => 10,
            'bg_image' => '/img/zaky.jpeg',
            'kelas_image' => '/img/zaky.jpeg',

        ]);
        Kelas::factory()->count(9)->create();
        // DATA RANDOM LAINNYA
        


    }
}
