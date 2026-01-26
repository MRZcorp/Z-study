<?php

namespace Database\Seeders;

use App\Models\MateriKelas;
use App\Models\Posts;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Tugas;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            DosenSeeder::class, KelasSeeder::class, MataKuliahSeeder::class, TugasSeeder::class, 
        ]);

        

        

    }
}
