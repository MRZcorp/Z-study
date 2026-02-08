<?php

namespace Database\Seeders;

use App\Models\Angkatan;
use App\Models\Dosen;
use App\Models\DosenWali;
use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;

class DosenWaliSeeder extends Seeder
{
    public function run(): void
    {
        $dosens = Dosen::with('user')->get();
        $prodis = ProgramStudi::all();
        $angkatans = Angkatan::all();

        if ($dosens->isEmpty() || $prodis->isEmpty() || $angkatans->isEmpty()) {
            return;
        }

        $index = 0;
        foreach ($dosens as $dosen) {
            $prodi = $prodis[$index % $prodis->count()];
            $angkatan = $angkatans[$index % $angkatans->count()];

            DosenWali::updateOrCreate(
                [
                    'dosen_id' => $dosen->id,
                    'nama_prodi_id' => $prodi->id,
                    'angkatan_id' => $angkatan->id,
                ],
                []
            );

            $index++;
        }
    }
}
