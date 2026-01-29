<?php

namespace Database\Factories;

use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\Tugas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tugas>
 */
class TugasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Tugas::class;
    public function definition(): array
    {
        return [
            //
        'nama_kelas_id'=> 1,
            'mata_kuliah_id' => 1,
            'nama_tugas' => 'Tugas ' . $this->faker->word(),
            'detail_tugas' => $this->faker->sentence(6),
            'file_tugas' => 'tugas/' . $this->faker->uuid() . '.pdf',
            'deadline' => now()->addDays(rand(3, 14)),
        ];
}
}
