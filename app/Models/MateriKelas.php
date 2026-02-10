<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriKelas extends Model
{
    use HasFactory;

    protected $table = 'materi_kelas';

    protected $fillable = [
        'judul_materi',
        'matkul',
        'kelas_id',
        'pertemuan',
        'deskripsi',
        'file_path',
        'file_type',
        'file_size',
    ];

   

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}