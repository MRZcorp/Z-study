<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    /** @use HasFactory<\Database\Factories\PengumumanFactory> */
    use HasFactory;
    protected $table = 'pengumumen';
    protected $fillable = [
        'judul',
        'isi',
        'tipe',
        'is_active',
        'tanggal_publish'
    ];
    public function mahasiswa()
    {
        return $this->belongsTo(mahasiswa::class);
    }
}
