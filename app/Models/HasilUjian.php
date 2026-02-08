<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilUjian extends Model
{
    /** @use HasFactory<\Database\Factories\HasilUjianFactory> */
    use HasFactory;

    protected $table = 'hasil_ujians';

    protected $fillable = [
        'mahasiswa_id',
        'ujian_id',
        'submitted_at',
        'nilai',
        'nilai_kecepatan',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }
}
