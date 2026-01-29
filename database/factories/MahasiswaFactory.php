<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mahasiswa>
 */
class MahasiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = \App\Models\Mahasiswa::class;
    public function definition(): array
    {
            //

                return [
                    'user_id' => User::factory(), // 🔥 INI KUNCI
                    'nim' => $this->faker->unique()->numerify('23########'),
                    'fakultas' => $this->faker->randomElement([
                        'Teknik',
                        'Ekonomi',
                        'Ilmu Komputer',
                        'Hukum',
                        'Keguruan'
                    ]),
                    'prodi' => $this->faker->randomElement([
                        'Informatika',
                        'Sistem Informasi',
                        'Manajemen',
                        'Akuntansi',
                        'Teknik Elektro'
                    ]),
                    'angkatan' => $this->faker->numberBetween(2019, 2024),
                    'email' => $this->faker->unique()->safeEmail(),
                    'status' => $this->faker->randomElement(['aktif', 'nonaktif']),
                ];
            
        
    }
}
