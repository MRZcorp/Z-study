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
        'fakultas_id',
        'nama_prodi_id',
        'jenjang',
        'angkatan_id',
        'semester_aktif',
        'ips_terakhir',
        'ipk',
        'maks_sks',
        'status_akademik',
        'ips_below_2_count',
        'ipk_below_2_semester_count',
        'last_ips_semester',
        'last_ipk_semester',
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
    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class);
    }
    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_mahasiswa')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function pengumpulanTugas()
    {
        return $this->hasMany(PengumpulanTugas::class);
    }


    
    public function pengumuman()
    {
        return $this->hasMany(Tugas::class, 'mata_kuliah_id');
    }



}
