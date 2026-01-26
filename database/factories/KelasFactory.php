<?php

namespace Database\Factories;

use App\Models\Dosen;
use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kelas>
 */
class KelasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

//model ini penting bisa tidak jalan seed
     protected $model = Kelas::class;

    public function definition(): array
    {
        // jam mulai random antara 07:00 - 18:00
    $jamMulai = Carbon::createFromTime(
        $this->faker->numberBetween(7, 18),
        $this->faker->randomElement([0, 30]),
        0
    );
        return [
            //
            
            'mata_kuliah' => $this->faker->randomElement([
                'Grafika Komputer',
                'Pemrograman Web',
                'Basis Data',
                'Sistem Operasi',
                'Jaringan Komputer',
            ]),
            'dosen_id' => Dosen::inRandomOrder()->first()->id ?? Dosen::factory(),

            'sks' => $this->faker->randomElement([2, 3, 4]),

            'nama_kelas' => $this->faker->randomElement(['A', 'B', 'C']),

            'jadwal_kelas' => $this->faker->randomElement([
                'Regular Pagi', 
                'Reguler Siang', 
                'Reguler Malam']),

            'hari_kelas' => $this->faker->randomElement([
                'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'
            ]),

            'jam_mulai' => $jamMulai->format('H:i'),
            'jam_selesai' => $jamMulai->copy()->addHours(
            $this->faker->randomElement([2, 3])),

            'kuota_maksimal' => 15,
            'kuota_terdaftar' => $this->faker->numberBetween(0, 15),

            'bg_image' => '/img/zaky.jpeg',
            'kelas_image' => '/img/zaky.jpeg',
       
        ];
        
    }
}
