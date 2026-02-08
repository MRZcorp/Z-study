<?php

namespace Database\Seeders;

use App\Models\Dosen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\User;
use Illuminate\Container\Attributes\DB;
use Illuminate\Support\Str;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        

        $dosens = Dosen::limit(5)->get();
$matkuls = MataKuliah::limit(5)->get();

if ($dosens->isEmpty() || $matkuls->isEmpty()) {
    $this->command->warn('Seeder dibatalkan: tabel dosens atau mata_kuliahs kosong');
    return;
}

$hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
$kelasList = ['A', 'B', 'C', 'D', 'E'];
$bgList = ['img/bg1.jpg', 'img/bg2.jpg', 'img/bg3.jpg', 'img/bg4.jpg', 'img/bg5.jpg', ''];

for ($i = 0; $i < 5; $i++) {
    $kelas = Kelas::create([
        'dosen_id' => $dosens[$i % $dosens->count()]->id,
        'mata_kuliah_id' => $matkuls[$i % $matkuls->count()]->id,

        'nama_kelas' => $kelasList[$i],
        'jadwal_kelas' => 'Ruang ' . chr(65 + $i),
        'hari_kelas' => $hariList[$i],

        'jam_mulai' => '08:00:00',
        'jam_selesai' => '10:00:00',

        'kuota_maksimal' => 30,
        'kuota_terdaftar' => rand(0, 10),

        'bg_image' => $bgList[$i],
        'status' => 'aktif',
        
    ]);

    $matkulName = $matkuls[$i % $matkuls->count()]->mata_kuliah ?? '';
    $baseSlug = Str::slug(trim($matkulName . ' ' . $kelasList[$i]), '_');
    $slug = $baseSlug ?: ('kelas_' . $kelas->id);
    if (Kelas::where('slug', $slug)->where('id', '!=', $kelas->id)->exists()) {
        $slug = $slug . '_' . $kelas->id;
    }
    $kelas->update(['slug' => $slug]);

    }
}
}
