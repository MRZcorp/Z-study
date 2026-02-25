<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use App\Models\RekapBobot;
use App\Models\RekapNilai;
use App\Models\Tugas;
use App\Models\Ujian;
use App\Models\PengumpulanTugas;
use App\Models\HasilUjian;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ManualTiSemester1NilaiSeeder extends Seeder
{
    /**
     * Seeder manual:
     * - Buat kelas Teknik Informatika semester 1 total 22 SKS
     * - Status kelas selesai
     * - Tiap kelas: 5 tugas + 5 ujian
     * - Nilai random 60-95 untuk semua mahasiswa existing
     *
     * Jalankan manual:
     * php artisan db:seed --class=Database\\Seeders\\ManualTiSemester1NilaiSeeder
     */
    public function run(): void
    {
        $mahasiswas = Mahasiswa::query()->get();
        if ($mahasiswas->isEmpty()) {
            $this->command?->warn('Tidak ada mahasiswa existing. Seeder dibatalkan.');
            return;
        }

        $prodiTi = ProgramStudi::query()
            ->whereRaw('LOWER(nama_prodi) = ?', ['teknik informatika'])
            ->first();

        if (!$prodiTi) {
            $this->command?->warn('Prodi Teknik Informatika tidak ditemukan. Seeder dibatalkan.');
            return;
        }

        $dosenId = Dosen::query()
            ->where('fakultas_id', $prodiTi->fakultas_id)
            ->value('id') ?? Dosen::query()->value('id');

        if (!$dosenId) {
            $this->command?->warn('Tidak ada data dosen. Seeder dibatalkan.');
            return;
        }

        $semesterPackages = [
            [
                'label' => '1',
                'semester' => 'ganjil',
                'tahun_ajar' => '2025 / 2026',
                // Total SKS semester 1 = 22
                'mata_kuliah' => [
                    ['kode' => 'TI-S1-101', 'nama' => 'Algoritma dan Pemrograman', 'sks' => 4],
                    ['kode' => 'TI-S1-102', 'nama' => 'Matematika Diskrit', 'sks' => 3],
                    ['kode' => 'TI-S1-103', 'nama' => 'Arsitektur Komputer', 'sks' => 3],
                    ['kode' => 'TI-S1-104', 'nama' => 'Sistem Operasi Dasar', 'sks' => 3],
                    ['kode' => 'TI-S1-105', 'nama' => 'Basis Data Dasar', 'sks' => 3],
                    ['kode' => 'TI-S1-106', 'nama' => 'Pendidikan Pancasila', 'sks' => 2],
                    ['kode' => 'TI-S1-107', 'nama' => 'Bahasa Inggris Teknik', 'sks' => 2],
                    ['kode' => 'TI-S1-108', 'nama' => 'Logika Informatika', 'sks' => 2],
                ],
            ],
            [
                'label' => '2',
                'semester' => 'genap',
                'tahun_ajar' => '2025 / 2026',
                // Total SKS semester 2 = 22
                'mata_kuliah' => [
                    ['kode' => 'TI-S2-201', 'nama' => 'Struktur Data', 'sks' => 4],
                    ['kode' => 'TI-S2-202', 'nama' => 'Statistika Informatika', 'sks' => 3],
                    ['kode' => 'TI-S2-203', 'nama' => 'Pemrograman Berorientasi Objek', 'sks' => 3],
                    ['kode' => 'TI-S2-204', 'nama' => 'Jaringan Komputer Dasar', 'sks' => 3],
                    ['kode' => 'TI-S2-205', 'nama' => 'Basis Data Lanjut', 'sks' => 3],
                    ['kode' => 'TI-S2-206', 'nama' => 'Kewarganegaraan', 'sks' => 2],
                    ['kode' => 'TI-S2-207', 'nama' => 'Komunikasi Teknis', 'sks' => 2],
                    ['kode' => 'TI-S2-208', 'nama' => 'Etika Profesi TI', 'sks' => 2],
                ],
            ],
            [
                'label' => '3',
                'semester' => 'ganjil',
                'tahun_ajar' => '2026 / 2027',
                // Total SKS semester 3 = 22
                'mata_kuliah' => [
                    ['kode' => 'TI-S3-301', 'nama' => 'Pemrograman Web', 'sks' => 3],
                    ['kode' => 'TI-S3-302', 'nama' => 'Sistem Basis Data', 'sks' => 3],
                    ['kode' => 'TI-S3-303', 'nama' => 'Analisis dan Desain Sistem', 'sks' => 3],
                    ['kode' => 'TI-S3-304', 'nama' => 'Interaksi Manusia dan Komputer', 'sks' => 2],
                    ['kode' => 'TI-S3-305', 'nama' => 'Probabilitas dan Statistik Lanjut', 'sks' => 3],
                    ['kode' => 'TI-S3-306', 'nama' => 'Pemrograman Mobile Dasar', 'sks' => 3],
                    ['kode' => 'TI-S3-307', 'nama' => 'Keamanan Informasi Dasar', 'sks' => 3],
                    ['kode' => 'TI-S3-308', 'nama' => 'Metodologi Penelitian TI', 'sks' => 2],
                ],
            ],
            [
                'label' => '4',
                'semester' => 'genap',
                'tahun_ajar' => '2026 / 2027',
                // Total SKS semester 4 = 22
                'mata_kuliah' => [
                    ['kode' => 'TI-S4-401', 'nama' => 'Rekayasa Perangkat Lunak', 'sks' => 3],
                    ['kode' => 'TI-S4-402', 'nama' => 'Jaringan Komputer Lanjut', 'sks' => 3],
                    ['kode' => 'TI-S4-403', 'nama' => 'Sistem Operasi Lanjut', 'sks' => 3],
                    ['kode' => 'TI-S4-404', 'nama' => 'Pemrograman Web Lanjut', 'sks' => 3],
                    ['kode' => 'TI-S4-405', 'nama' => 'Data Mining Dasar', 'sks' => 3],
                    ['kode' => 'TI-S4-406', 'nama' => 'Desain dan Analisis Algoritma', 'sks' => 3],
                    ['kode' => 'TI-S4-407', 'nama' => 'Manajemen Proyek TI', 'sks' => 2],
                    ['kode' => 'TI-S4-408', 'nama' => 'Kecerdasan Buatan Dasar', 'sks' => 2],
                ],
            ],
        ];

        DB::transaction(function () use ($mahasiswas, $prodiTi, $dosenId, $semesterPackages) {
            foreach ($semesterPackages as $package) {
                foreach ($package['mata_kuliah'] as $index => $mk) {
                $mataKuliah = MataKuliah::updateOrCreate(
                    ['kode_mata_kuliah' => $mk['kode']],
                    [
                        'mata_kuliah' => $mk['nama'],
                        'semester' => $package['semester'],
                        'sks' => $mk['sks'],
                        'tipe' => 'prodi',
                        'status' => 'aktif',
                    ]
                );

                DB::table('mata_kuliah_prodis')->updateOrInsert(
                    [
                        'mata_kuliah_id' => $mataKuliah->id,
                        'nama_prodi_id' => $prodiTi->id,
                    ],
                    []
                );

                $kelasNama = 'TI-' . $package['label'] . chr(65 + ($index % 8)); // TI-1A / TI-2A ...
                $kelas = Kelas::updateOrCreate(
                    [
                        'mata_kuliah_id' => $mataKuliah->id,
                        'tahun_ajar' => $package['tahun_ajar'],
                        'semester' => $package['semester'],
                        'nama_kelas' => $kelasNama,
                    ],
                    [
                        'dosen_id' => $dosenId,
                        'jadwal_kelas' => 'Ruang TI-' . (101 + $index),
                        'hari_kelas' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'][$index % 5],
                        'jam_mulai' => '08:00:00',
                        'jam_selesai' => '10:00:00',
                        'kuota_maksimal' => max(40, $mahasiswas->count()),
                        'kuota_terdaftar' => $mahasiswas->count(),
                        'bg_image' => null,
                        'status' => 'selesai',
                    ]
                );

                if (empty($kelas->slug)) {
                    $kelas->slug = Str::slug('ti_sem1_' . $kelas->nama_kelas . '_' . $kelas->id, '_');
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

                $createdTugas = [];
                for ($tugasKe = 1; $tugasKe <= 5; $tugasKe++) {
                    $mulai = now()->subMonths(8)->addDays($tugasKe * 3);
                    $deadline = (clone $mulai)->addDays(7);

                    $tugas = Tugas::updateOrCreate(
                        [
                            'nama_kelas_id' => $kelas->id,
                            'tugas_ke' => $tugasKe,
                        ],
                        [
                            'mata_kuliah_id' => $mataKuliah->id,
                            'nama_tugas' => 'Tugas ' . $tugasKe . ' - ' . $mataKuliah->mata_kuliah,
                            'detail_tugas' => 'Tugas otomatis dari seeder manual semester 1.',
                            'mulai_tugas' => $mulai,
                            'deadline' => $deadline,
                            'file_tugas' => null,
                        ]
                    );

                    $createdTugas[] = $tugas;
                }

                $createdUjian = [];
                for ($ujianKe = 1; $ujianKe <= 5; $ujianKe++) {
                    $mulai = now()->subMonths(7)->addDays($ujianKe * 5);
                    $deadline = (clone $mulai)->addHours(2);

                    $ujian = Ujian::updateOrCreate(
                        [
                            'nama_kelas_id' => $kelas->id,
                            'ujian_ke' => $ujianKe,
                            'nama_ujian' => 'Ujian ' . $ujianKe . ' - ' . $mataKuliah->mata_kuliah,
                        ],
                        [
                            'mata_kuliah_id' => $mataKuliah->id,
                            'deskripsi' => $ujianKe <= 2 ? 'UTS' : 'UAS',
                            'mulai_ujian' => $mulai,
                            'deadline' => $deadline,
                            'file_path' => null,
                            'file_name' => null,
                        ]
                    );

                    $createdUjian[] = $ujian;
                }

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

                    $tugasNilai = [];
                    $tugasSpeed = [];
                    foreach ($createdTugas as $tugas) {
                        $nilai = random_int(60, 95);
                        $kecepatan = random_int(60, 95);
                        $tugasNilai[] = $nilai;
                        $tugasSpeed[] = $kecepatan;

                        PengumpulanTugas::updateOrCreate(
                            [
                                'tugas_id' => $tugas->id,
                                'mahasiswa_id' => $mhs->id,
                            ],
                            [
                                'file_path' => null,
                                'file_name' => null,
                                'deskripsi' => 'Pengumpulan otomatis seeder manual.',
                                'submitted_at' => now()->subMonths(6),
                                'nilai' => $nilai,
                                'nilai_kecepatan' => $kecepatan,
                            ]
                        );
                    }

                    $ujianNilai = [];
                    $ujianSpeed = [];
                    foreach ($createdUjian as $ujian) {
                        $nilai = random_int(60, 95);
                        $kecepatan = random_int(60, 95);
                        $ujianNilai[] = $nilai;
                        $ujianSpeed[] = $kecepatan;

                        HasilUjian::updateOrCreate(
                            [
                                'ujian_id' => $ujian->id,
                                'mahasiswa_id' => $mhs->id,
                            ],
                            [
                                'submitted_at' => now()->subMonths(6),
                                'nilai' => $nilai,
                                'nilai_kecepatan' => $kecepatan,
                            ]
                        );
                    }

                    $avgTugas = count($tugasNilai) ? array_sum($tugasNilai) / count($tugasNilai) : 0;
                    $avgSpeedTugas = count($tugasSpeed) ? array_sum($tugasSpeed) / count($tugasSpeed) : 0;
                    $avgUjian = count($ujianNilai) ? array_sum($ujianNilai) / count($ujianNilai) : 0;
                    $avgSpeedUjian = count($ujianSpeed) ? array_sum($ujianSpeed) / count($ujianSpeed) : 0;

                    RekapNilai::updateOrCreate(
                        [
                            'kelas_id' => $kelas->id,
                            'mahasiswa_id' => $mhs->id,
                        ],
                        [
                            'rata_tugas' => round($avgTugas, 2),
                            'rata_kecepatan_tugas' => round($avgSpeedTugas, 2),
                            'rata_ujian' => round($avgUjian, 2),
                            'rata_kecepatan_ujian' => round($avgSpeedUjian, 2),
                            'keaktifan' => (string) random_int(70, 95),
                            'absensi' => random_int(75, 100),
                        ]
                    );
                }
            }
            }
        });

        $this->command?->info(
            'ManualTiSemester1NilaiSeeder selesai: kelas TI semester 1, 2, 3, dan 4 (masing-masing 22 SKS), 5 tugas & 5 ujian per kelas, nilai random 60-95 untuk semua mahasiswa existing.'
        );
    }
}
