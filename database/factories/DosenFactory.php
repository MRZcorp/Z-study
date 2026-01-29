<?php

namespace Database\Factories;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DosenFactory extends Factory
{
    protected $model = Dosen::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nidn' => $this->faker->unique()->numerify('##########'),
            
            'email' => $this->faker->unique()->safeEmail(),
            'no_hp' => '08' . $this->faker->numberBetween(100000000, 999999999),
            'gelar' => $this->faker->randomElement(['M.Kom', 'M.T', 'Ph.D', 'M.Sc']),
            'poto_profil' =>  $this->faker->randomElement([
            'img/dosen1.jpg', 'img/dosen2.jpg', 'img/dosen3.jpg',
            'img/dosen4.jpg', '']),
            'status' => 'aktif',
        ];
    }
}
