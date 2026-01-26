<?php

namespace Database\Factories;

use App\Models\Dosen;
use App\Models\MataKuliah;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tugas>
 */
class MataKuliahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = MataKuliah::class;
    public function definition(): array
    {
        return 
            //
            
            [
                'kode_mata_kuliah' => $this->faker->unique()->numberBetween(100, 999),
                'mata_kuliah' => $this->faker->randomElement([
                    'Pemrograman Web',
                'Basis Data',
                'Sistem Operasi',
                'Rekayasa Perangkat Lunak',
                'Jaringan Komputer',
                'Kecerdasan Buatan',
                'Grafika Komputer',
                'Pemrograman Mobile',
                ]),

                'semester' => $this->faker->randomElement([
                    'Genap 2025/2026',
                    'Ganjil 2025/2026',
                    
                ]),
                'sks' => $this->faker->randomElement([
                    '2',
                    '3',
                    '4',
                    
                ]),
                'dosen_id' => Dosen::factory(),
                    
                
                'deskripsi' => $this->faker->randomElement([
                    'Mata kuliah dasar pengembangan web menggunakan Laravel dan Tailwind CSS',
                    'Laravel dan Tailwind CSS',
                    'kuliah dasar',
                    'pengembangan web',
                ]),
                'status' => 'aktif',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            
    }
}
