<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyusersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
   
// =====================
// ADMIN (2)
// =====================
User::create([
    'name' => 'Admin Satu',
    'username' => 'admin1',
    'nim' => null,
    'nidn' => null,
    'email' => 'admin1@gmail.com',
    'password' => Hash::make('123'),
    'role_id' => 1,
]);

User::create([
    'name' => 'Admin Dua',
    'username' => 'admin2',
    'nim' => null,
    'nidn' => null,
    'email' => 'admin2@gmail.com',
    'password' => Hash::make('123'),
    'role_id' => 1,
]);

// =====================
// DOSEN (5)
// =====================
User::create([
    'name' => 'Dosen Satu',
    'username' => 'dosen1',
    'nim' => null,
    'nidn' => '198001001',
    'email' => 'dosen1@gmail.com',
    'password' => Hash::make('123'),
    'role_id' => 2,
]);

User::create([
    'name' => 'Dosen Dua',
    'username' => 'dosen2',
    'nim' => null,
    'nidn' => '198001002',
    'email' => 'dosen2@gmail.com',
    'password' => Hash::make('123'),
    'role_id' => 2,
]);

User::create([
    'name' => 'Dosen Tiga',
    'username' => 'dosen3',
    'nim' => null,
    'nidn' => '198001003',
    'email' => 'dosen3@gmail.com',
    'password' => Hash::make('123'),
    'role_id' => 2,
]);

User::create([
    'name' => 'Dosen Empat',
    'username' => 'dosen4',
    'nim' => null,
    'nidn' => '198001004',
    'email' => 'dosen4@gmail.com',
    'password' => Hash::make('123'),
    'role_id' => 2,
]);

User::create([
    'name' => 'Dosen Lima',
    'username' => 'dosen5',
    'nim' => null,
    'nidn' => '198001005',
    'email' => 'dosen5@gmail.com',
    'password' => Hash::make('123'),
    'role_id' => 2,
]);

// =====================
// MAHASISWA (5)
// =====================
User::create([
    'name' => 'Mahasiswa Satu',
    'username' => 'mhs1',
    'nim' => '20210001',
    'nidn' => null,
    'email' => 'mhs1@gmail.com',
    'password' => Hash::make('123'),
    'role_id' => 3,
]);

User::create([
    'name' => 'Mahasiswa Dua',
    'username' => 'mhs2',
    'nim' => '20210002',
    'nidn' => null,
    'email' => 'mhs2@gmail.com',
    'password' => Hash::make('123'),
    'role_id' => 3,
]);

User::create([
    'name' => 'Mahasiswa Tiga',
    'username' => 'mhs3',
    'nim' => '20210003',
    'nidn' => null,
    'email' => 'mhs3@gmail.com',
    'password' => Hash::make('123'),
    'role_id' => 3,
]);

User::create([
    'name' => 'Mahasiswa Empat',
    'username' => 'mhs4',
    'nim' => '20210004',
    'nidn' => null,
    'email' => 'mhs4@gmail.com',
    'password' => Hash::make('123'),
    'role_id' => 3,
]);

User::create([
    'name' => 'Mahasiswa Lima',
    'username' => 'mhs5',
    'nim' => '20210005',
    'nidn' => null,
    'email' => 'mhs5@gmail.com',
    'password' => Hash::make('123'),
    'role_id' => 3,
]);
    }

}