<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Role;
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
                'fakultas_id' => '1',
                'username' => 'dosen1',
                'nidn' => '198001001',
                'email' => 'dosen1@gmail.com',
                'foto' => 'dosen/dosen1.jpg',
                'bg' => 'dosen/dosen1.jpg',
            ],
            [
                'name'  => 'Dosen Dua',
                'fakultas_id' => '1',
                'username' => 'dosen2',
                'nidn' => '198001002',
                'email' => 'dosen2@gmail.com',
                'foto' => 'dosen/dosen2.jpg',
                'bg' => 'dosen/dosen2.jpg',
            ],
            [
                'name'  => 'Dosen Tiga',
                'fakultas_id' => '1',
                'username' => 'dosen3',
                'nidn' => '198001003',
                'email' => 'dosen3@gmail.com',
                'foto' => 'dosen/dosen3.jpg',
                'bg' => 'dosen/dosen3.jpg',
            ],
            [
                'name'  => 'Dosen Empat',
                'username' => 'dosen4',
                'fakultas_id' => '1',
                'nidn' => '198001004',
                'email' => 'dosen4@gmail.com',
                'foto' => 'dosen/dosen4.jpg',
                'bg' => 'dosen/dosen4.jpg',
            ],
            [
                'name'  => 'Dosen Lima',
                'fakultas_id' => '1',
                'username' => 'dosen5',
                'nidn' => '198001005',
                'email' => 'dosen5@gmail.com',
                'foto' => 'dosen/dosen5.jpg',
                'bg' => 'dosen/dosen5.jpg',
            ],
        ];

        $roleDosen = Role::where('nama_role', 'Dosen')->first();
        if (!$roleDosen) {
            return;
        }

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
                'role_id'  => $roleDosen->id,
            ]);

            // =====================
            // CREATE DOSEN
            // =====================
            Dosen::create([
                'user_id'     => $user->id, // 🔥 kunci relasi
                'fakultas_id' => $item['fakultas_id'],
                'nidn'        => $item['nidn'],
                'email'       => $item['email'],
                'no_hp'       => '08' . rand(100000000, 999999999),
                'gelar'       => collect(['M.Kom', 'M.T', 'Ph.D', 'M.Sc'])->random(),
                'poto_profil' => $item['foto'],
                'bg' => $item['bg'],
                'status'      => 'aktif',
            ]);
        }
    }
}





