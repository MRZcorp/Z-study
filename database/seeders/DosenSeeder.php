<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            [
                'name'  => 'Dosen Satu',
                'username' => 'dosen1',
                'nidn' => '198001001',
                'email' => 'dosen1@gmail.com',
                'foto' => 'dosen/dosen1.jpg',
            ],
            [
                'name'  => 'Dosen Dua',
                'username' => 'dosen2',
                'nidn' => '198001002',
                'email' => 'dosen2@gmail.com',
                'foto' => 'dosen/dosen2.jpg',
            ],
            [
                'name'  => 'Dosen Tiga',
                'username' => 'dosen3',
                'nidn' => '198001003',
                'email' => 'dosen3@gmail.com',
                'foto' => 'dosen/dosen3.jpg',
            ],
            [
                'name'  => 'Dosen Empat',
                'username' => 'dosen4',
                'nidn' => '198001004',
                'email' => 'dosen4@gmail.com',
                'foto' => '',
            ],
            [
                'name'  => 'Dosen Lima',
                'username' => 'dosen5',
                'nidn' => '198001005',
                'email' => 'dosen5@gmail.com',
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
                'nim'      => null,
                'nidn'     => $item['nidn'],
                'email'    => $item['email'],
                'password' => Hash::make('123'),
                'role_id'  => 2, // dosen
            ]);

            // =====================
            // CREATE DOSEN
            // =====================
            Dosen::create([
                'user_id'     => $user->id, // 🔥 kunci relasi
                'nidn'        => $item['nidn'],
                'email'       => $item['email'],
                'no_hp'       => '08' . rand(100000000, 999999999),
                'gelar'       => collect(['M.Kom', 'M.T', 'Ph.D', 'M.Sc'])->random(),
                'poto_profil' => $item['foto'],
                'status'      => 'aktif',
            ]);
        }
    }
}
