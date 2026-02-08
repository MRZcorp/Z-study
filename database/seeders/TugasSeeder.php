<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\MataKuliah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tugas;
use Illuminate\Support\Carbon;

class TugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelasList = Kelas::with('mataKuliah')->orderBy('id')->get();
        if ($kelasList->isEmpty()) {
            return;
        }

        foreach ($kelasList as $kelas) {
            $lastNumber = Tugas::where('nama_kelas_id', $kelas->id)->max('tugas_ke') ?? 0;
            $nextNumber = $lastNumber + 1;
            $mulai = Carbon::now()->subDays(2);
            $deadline = Carbon::now()->addDays(7);

            Tugas::create([
                'nama_kelas_id' => $kelas->id,
                'tugas_ke' => $nextNumber,
                'mata_kuliah_id' => $kelas->mata_kuliah_id,
                'nama_tugas' => 'Tugas ' . ($kelas->mataKuliah->mata_kuliah ?? 'Kelas'),
                'detail_tugas' => 'Kerjakan tugas sesuai instruksi dosen.',
                'mulai_tugas' => $mulai,
                'deadline' => $deadline,
            ]);
        }
    }
}
