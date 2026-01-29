<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    /** @use HasFactory<\Database\Factories\MahasiswaFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'nim',
        'fakultas',
        'prodi',
        'angkatan',
        'email',
        'status',
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }








    
    public function pengumuman()
    {
        return $this->hasMany(Tugas::class, 'mata_kuliah_id');
    }



    public function kelases()
{
    return $this->belongsToMany(Kelas::class, 'kelas_mahasiswa');
}
}
