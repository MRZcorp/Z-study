<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengumuman>
 */
class PengumumanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'judul' => $this->faker->sentence(4),
            'isi' => $this->faker->paragraphs(4, true),
            'tipe' => $this->faker->randomElement(['info', 'peringatan', 'event']),
            'is_active' => true,
            'tanggal_publish' => now(),
        ];
    }
}
