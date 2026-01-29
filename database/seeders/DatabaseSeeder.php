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

       

        $this->call([
           RoleSeeder::class, DummyusersSeeder::class, 
           ProgramStudiSeeder::class, DosenSeeder::class, 
           MataKuliahSeeder::class, KelasSeeder::class,  
           MateriKelasSeeder::class, TugasSeeder::class, 
           PengumumanSeeder::class, MahasiswaSeeder::class,
        ]);

        

        

    }
}
