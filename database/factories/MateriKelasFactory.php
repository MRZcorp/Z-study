<?php

namespace Database\Factories;

use App\Models\MateriKelas;
use App\Models\Kelas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MateriKelas>
 */
class MateriKelasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = MateriKelas::class;
public function definition(): array
{
$fileTypes = ['pdf', 'zip', 'mp4', 'pptx'];

$fileType = $this->faker->randomElement($fileTypes);

 $kelas = Kelas::with('mataKuliah')->inRandomOrder()->first();
 $matkul = $kelas && $kelas->mataKuliah
     ? $kelas->mataKuliah->mata_kuliah
     : $this->faker->randomElement([
         'Pemrograman Web',
         'Basis Data',
         'Rekayasa Perangkat Lunak',
         'Sistem Informasi'
     ]);

return [
'judul_materi' => $this->faker->sentence(3),
'matkul' => $matkul,
'kelas_id' => $kelas?->id,
'pertemuan' => $this->faker->numberBetween(1, 14),
'deskripsi' => $this->faker->paragraph(),
'file_path' => 'materi/' . $this->faker->uuid . '.' . $fileType,
'file_type' => $fileType,
'file_size' => $this->faker->numberBetween(100_000, 5_000_000), // byte
];
}
}
