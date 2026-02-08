<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    /** @use HasFactory<\Database\Factories\DosenFactory> */
    use HasFactory;
    protected $table = 'dosens';

    protected $fillable = [
        'user_id',
        'nama_prodi_id',
        'fakultas_id',
        'dosen',
        'email',
        'no_hp',
        'gelar',
        'status',
        'poto_profil',
        'bg',
    ];
    // RELASI
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }
    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class,'nama_prodi_id');
    }
    public function mataKuliah()
    {
        return $this->hasMany(MataKuliah::class);
    }
    public function kelas()
    {
        return $this->hasMany(kelas::class, 'dosen_id');
    }
    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }
    public function dosenWalis()
    {
        return $this->hasMany(DosenWali::class);
    }
    

   
   
}
