<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HasilUjian;

class Ujian extends Model
{
    /** @use HasFactory<\Database\Factories\UjianFactory> */
    use HasFactory;

    protected $fillable = [
        'nama_kelas_id',
        'mata_kuliah_id',
        'nama_ujian',
        'ujian_ke',
        'deskripsi',
        'mulai_ujian',
        'deadline',
        'file_path',
        'file_name',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'nama_kelas_id');
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    public function soals()
    {
        return $this->hasMany(Soal::class);
    }

    public function hasilUjian()
    {
        return $this->hasMany(HasilUjian::class);
    }
}
