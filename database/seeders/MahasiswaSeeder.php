<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use App\Models\Role;
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
         // Ambil role mahasiswa
         $roleMahasiswa = Role::where('nama_role', 'mahasiswa')->firstOrFail();
     
         // Ambil fakultas & prodi (contoh: FT - Teknik Informatika)
         $fakultas = Fakultas::where('kode', 'FT')->firstOrFail();
         $prodi    = ProgramStudi::where('kode', 'TI')->firstOrFail();
     
         $data = [
             [
                 'name'     => 'Mahasiswa Satu',
                 'username' => 'mhs1',
                 'nim'      => '20210001',
                 'email'    => 'mhs1@gmail.com',
                 'foto'     => 'mahasiswa/mhs1.jpg',
                 'bg'     => 'mahasiswa/mhs1.jpg',
             ],
             [
                 'name'     => 'Mahasiswa Dua',
                 'username' => 'mhs2',
                 'nim'      => '20210002',
                 'email'    => 'mhs2@gmail.com',
                 'foto'     => 'mahasiswa/mhs2.jpg',
                 'bg'     => 'mahasiswa/mhs1.jpg',
             ],
             [
                 'name'     => 'Mahasiswa Tiga',
                 'username' => 'mhs3',
                 'nim'      => '20210003',
                 'email'    => 'mhs3@gmail.com',
                 'foto'     => 'mahasiswa/mhs3.jpg',
                 'bg'     => 'mahasiswa/mhs1.jpg',
             ],
             [
                 'name'     => 'Mahasiswa Empat',
                 'username' => 'mhs4',
                 'nim'      => '20210004',
                 'email'    => 'mhs4@gmail.com',
                 'foto'     => null,
                 'bg'     => 'mahasiswa/mhs1.jpg',
             ],
             [
                 'name'     => 'Mahasiswa Lima',
                 'username' => 'mhs5',
                 'nim'      => '20210005',
                 'email'    => 'mhs5@gmail.com',
                 'foto'     => null,
                 'bg'     => 'mahasiswa/mhs1.jpg',
             ],
         ];
     
         foreach ($data as $item) {
     
             // =====================
             // USER
             // =====================
             $user = User::updateOrCreate(
                 ['username' => $item['username']],
                 [
                     'name'     => $item['name'],
                     'nim'      => $item['nim'],
                     'nidn'     => null,
                     'email'    => $item['email'],
                     'password' => Hash::make('123'),
                     'role_id'  => $roleMahasiswa->id,
                 ]
             );
     
             // =====================
             // MAHASISWA
             // =====================
             Mahasiswa::updateOrCreate(
                 ['user_id' => $user->id],
                 [
                     'nim'              => $item['nim'],
                     'fakultas_id'      => $fakultas->id,
                     'nama_prodi_id' => $prodi->id,
                     'angkatan'         => 2021,
                     'email'            => $item['email'],
                     'poto_profil'      => $item['foto'],
                     'bg'      => $item['bg'],
                     'status'           => 'aktif',
                 ]
             );
         }
    
    }
}
