<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       
            return [
                'name'     => $this->faker->name(),
                'username' => $this->faker->unique()->userName(),
                'nim'      => null,
                'nidn'     => null,
                'email'    => $this->faker->unique()->safeEmail(),
                'password' => Hash::make('123'),
                'role_id'  => null, // ❗ sengaja dikosongkan
            ];
        
        
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    // ✅ STATE DOSEN
    public function dosen()
    {
        return $this->state(fn () => [
            'role_id' => 2,
            'nidn'    => $this->faker->unique()->numerify('##########'),
            'nim'     => null,
        ]);
    }

    // ✅ STATE MAHASISWA
    public function mahasiswa()
    {
        return $this->state(fn () => [
            'role_id' => 3,
            'nim'     => $this->faker->unique()->numerify('23########'),
            'nidn'    => null,
        ]);
    }

}
