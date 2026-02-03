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
        'poto_profil',
        'bg',
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'nama_prodi_id');
    }
    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_mahasiswa');
    }








    
    public function pengumuman()
    {
        return $this->hasMany(Tugas::class, 'mata_kuliah_id');
    }



}
