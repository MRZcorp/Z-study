<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
    public function run(): void
    {
        $data = [
            [
                'name' => 'Mahasiswa Satu',
                'username' => 'mhs1',
                'nim' => '20210001',
                'email' => 'mhs1@gmail.com',
                'foto' => 'mahasiswa/mhs1.jpg',
            ],
            [
                'name' => 'Mahasiswa Dua',
                'username' => 'mhs2',
                'nim' => '20210002',
                'email' => 'mhs2@gmail.com',
                'foto' => 'mahasiswa/mhs2.jpg',
            ],
            [
                'name' => 'Mahasiswa Tiga',
                'username' => 'mhs3',
                'nim' => '20210003',
                'email' => 'mhs3@gmail.com',
                'foto' => 'mahasiswa/mhs3.jpg',
            ],
            [
                'name' => 'Mahasiswa Empat',
                'username' => 'mhs4',
                'nim' => '20210004',
                'email' => 'mhs4@gmail.com',
                'foto' => '',
            ],
            [
                'name' => 'Mahasiswa Lima',
                'username' => 'mhs5',
                'nim' => '20210005',
                'email' => 'mhs5@gmail.com',
                'foto' => '',
            ],
        ];

        foreach ($data as $item) {

            // =====================
            // CREATE USER
            // =====================
            $user = User::create([
                'name'     => $item['name'],
                'username' => $item['username'],
                'nim'      => $item['nim'],
                'nidn'     => null,
                'email'    => $item['email'],
                'password' => Hash::make('123'),
                'role_id'  => 3, // mahasiswa
            ]);

            // =====================
            // CREATE MAHASISWA
            // =====================
            Mahasiswa::create([
                'user_id'      => $user->id, // 🔥 INI KUNCI
                'nim'          => $item['nim'],
                'fakultas'     => collect([
                    'Teknik',
                    'Ekonomi',
                    'Ilmu Komputer',
                    'Hukum',
                    'Keguruan'
                ])->random(),
                'prodi'        => collect([
                    'Informatika',
                    'Sistem Informasi',
                    'Manajemen',
                    'Akuntansi',
                    'Teknik Elektro'
                ])->random(),
                'angkatan'     => rand(2019, 2024),
                'email'        => $item['email'],
                'poto_profil'  => $item['foto'],
                'status'       => collect(['aktif', 'nonaktif'])->random(),
            ]);
        }
    
    }
}
