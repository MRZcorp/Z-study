<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapNilai extends Model
{
    protected $fillable = [
        'kelas_id',
        'mahasiswa_id',
        'rata_tugas',
        'rata_kecepatan_tugas',
        'rata_ujian',
        'rata_kecepatan_ujian',
        'keaktifan',
        'absensi',
    ];
}
