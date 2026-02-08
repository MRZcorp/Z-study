<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    /** @use HasFactory<\Database\Factories\TugasFactory> */
    use HasFactory;

    

    protected $fillable = [
        'nama_kelas_id',
        
        'tugas_ke',
        'mata_kuliah_id',
        'nama_tugas',
        'detail_tugas',
        'mulai_tugas',
        'file_tugas',
        'deadline'
    ];
    protected $table = 'tugas';
    public function kelas()
    {
        return $this->belongsTo(Kelas::class,'nama_kelas_id');
    }
    public function mataKuliah()
    {
        return $this->belongsTo(Matakuliah::class);
    }

    public function files()
    {
        return $this->hasMany(TugasFile::class);
    }

    public function pengumpulan()
    {
        return $this->hasMany(PengumpulanTugas::class);
    }
}
