<?php

namespace Database\Factories;

use App\Models\Dosen;
use Illuminate\Database\Eloquent\Factories\Factory;

class DosenFactory extends Factory
{
    protected $model = Dosen::class;

    public function definition(): array
    {
        return [
            'nidn' => $this->faker->unique()->numerify('##########'),
            'dosen' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'no_hp' => '08' . $this->faker->numberBetween(100000000, 999999999),
            'gelar' => $this->faker->randomElement(['M.Kom', 'M.T', 'Ph.D', 'M.Sc']),
            'status' => 'aktif',
        ];
    }
}
