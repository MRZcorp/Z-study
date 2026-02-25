<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\RekapBobot;
use App\Models\RekapNilai;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ManualSemesterNilaiSeeder extends Seeder
{
    /**
     * Seeder manual:
     * - Mengisi nilai semester 1-4 untuk mahasiswa existing
     * - Tidak didaftarkan ke DatabaseSeeder
     */
    public function run(): void
    {
        $mahasiswas = Mahasiswa::query()->get();

        if ($mahasiswas->isEmpty()) {
            $this->command?->warn('Tidak ada mahasiswa existing. Seeder dibatalkan.');
            return;
        }

        $dosenId = Dosen::query()->value('id');
        if (!$dosenId) {
            $this->command?->warn('Tidak ada data dosen. Seeder dibatalkan.');
            return;
        }

        $matkulIds = MataKuliah::query()->orderBy('id')->limit(4)->pluck('id')->values();
        if ($matkulIds->isEmpty()) {
            $this->command?->warn('Tidak ada data mata kuliah. Seeder dibatalkan.');
            return;
        }

        $semesterTemplates = [
            ['semester_num' => 1, 'tahun_ajar' => '2022 / 2023', 'semester' => 'ganjil', 'nama_kelas' => 'S1'],
            ['semester_num' => 2, 'tahun_ajar' => '2022 / 2023', 'semester' => 'genap',  'nama_kelas' => 'S2'],
            ['semester_num' => 3, 'tahun_ajar' => '2023 / 2024', 'semester' => 'ganjil', 'nama_kelas' => 'S3'],
            ['semester_num' => 4, 'tahun_ajar' => '2023 / 2024', 'semester' => 'genap',  'nama_kelas' => 'S4'],
        ];

        DB::transaction(function () use ($mahasiswas, $dosenId, $matkulIds, $semesterTemplates) {
            foreach ($semesterTemplates as $idx => $tpl) {
                $mataKuliahId = $matkulIds[$idx % $matkulIds->count()];

                $kelas = Kelas::updateOrCreate(
                    [
                        'tahun_ajar' => $tpl['tahun_ajar'],
                        'semester' => $tpl['semester'],
                        'nama_kelas' => $tpl['nama_kelas'],
                        'mata_kuliah_id' => $mataKuliahId,
                    ],
                    [
                        'dosen_id' => $dosenId,
                        'jadwal_kelas' => 'Ruang Manual Nilai',
                        'hari_kelas' => 'Senin',
                        'jam_mulai' => '08:00:00',
                        'jam_selesai' => '10:00:00',
                        'kuota_maksimal' => 200,
                        'kuota_terdaftar' => $mahasiswas->count(),
                        'bg_image' => null,
                        'status' => 'selesai',
                    ]
                );

                if (empty($kelas->slug)) {
                    $kelas->slug = Str::slug(
                        'manual_' . $tpl['tahun_ajar'] . '_' . $tpl['semester'] . '_kelas_' . $kelas->id,
                        '_'
                    );
                    $kelas->save();
                }

                RekapBobot::updateOrCreate(
                    ['kelas_id' => $kelas->id],
                    [
                        'harian' => 15,
                        'keaktifan' => 6.25,
                        'kecepatan' => 3.75,
                        'absensi' => 5,
                        'uts' => 30,
                        'uas' => 40,
                    ]
                );

                foreach ($mahasiswas as $mhs) {
                    DB::table('kelas_mahasiswa')->updateOrInsert(
                        [
                            'kelas_id' => $kelas->id,
                            'mahasiswa_id' => $mhs->id,
                        ],
                        [
                            'status' => 'disetujui',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );

                    $seed = ($mhs->id * 100) + $tpl['semester_num'];
                    $rataTugas = 70 + ($seed % 26);
                    $rataUjian = 68 + (($seed + 7) % 28);
                    $kecepatanTugas = 72 + (($seed + 3) % 24);
                    $kecepatanUjian = 72 + (($seed + 5) % 24);
                    $keaktifan = 75 + (($seed + 9) % 21);
                    $absensi = 80 + (($seed + 11) % 21);

                    RekapNilai::updateOrCreate(
                        [
                            'kelas_id' => $kelas->id,
                            'mahasiswa_id' => $mhs->id,
                        ],
                        [
                            'rata_tugas' => $rataTugas,
                            'rata_kecepatan_tugas' => $kecepatanTugas,
                            'rata_ujian' => $rataUjian,
                            'rata_kecepatan_ujian' => $kecepatanUjian,
                            'keaktifan' => (string) $keaktifan,
                            'absensi' => $absensi,
                        ]
                    );

                    $mhs->semester_aktif = max((int) ($mhs->semester_aktif ?? 1), 4);
                    if (empty($mhs->status_akademik)) {
                        $mhs->status_akademik = 'AKTIF';
                    }
                    $mhs->save();
                }
            }
        });

        $this->command?->info(
            'ManualSemesterNilaiSeeder selesai: nilai semester 1-4 dibuat untuk '
            . $mahasiswas->count()
            . ' mahasiswa existing.'
        );
    }
}
