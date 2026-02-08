<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanMahasiswa extends Model
{
    /** @use HasFactory<\Database\Factories\JawabanMahasiswaFactory> */
    use HasFactory;

    protected $table = 'jawaban_mahasiswas';

    protected $fillable = [
        'mahasiswa_id',
        'ujian_id',
        'soal_id',
        'tipe',
        'jawaban_pg',
        'jawaban_text',
    ];
}
