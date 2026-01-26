<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    /** @use HasFactory<\Database\Factories\KelasFactory> */
    use HasFactory;
    protected $fillable = [
        'mata_kuliah',
        'sks',
        'nama_kelas',
        'hari_kelas',
        'jam_mulai',
        'jam_selesai',
        'dosen_id',
        'kuota_maksimal',
        'kuota_terdaftar',
        'bg_image',
        'kelas_image',
    ];
    protected $table = 'kelas';
    public function tugass()
    {
        return $this->hasMany(Tugas::class, 'nama_kelas');
    }
    public function dosens()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }
}
