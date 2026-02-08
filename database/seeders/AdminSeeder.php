<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $roleAdmin = Role::where('nama_role', 'Admin')->first();
        if (!$roleAdmin) {
            return;
        }

        $fakultas = Fakultas::first();

        $user = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin',
                'nim' => null,
                'nidn' => '000000000',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123'),
                'role_id' => $roleAdmin->id,
            ]
        );

        if ($fakultas) {
            Dosen::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'fakultas_id' => $fakultas->id,
                    'nidn' => $user->nidn,
                    'email' => $user->email,
                    'no_hp' => '080000000000',
                    'gelar' => 'Admin',
                    'poto_profil' => null,
                    'bg' => null,
                    'status' => 'aktif',
                ]
            );
        }
    }
}
