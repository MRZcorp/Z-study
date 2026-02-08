<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    /** @use HasFactory<\Database\Factories\KelasFactory> */
    use HasFactory;
    protected $fillable = [
        'dosen_id',
        'mata_kuliah_id',
        'tahun_ajar',
        'semester',
        'nama_kelas',
        'slug',
        'jadwal_kelas',
        'hari_kelas',
        'jam_mulai',
        'jam_selesai',
        'kuota_maksimal',
        'kuota_terdaftar',
        'bg_image',
        'status',
        
    ];
    protected $table = 'kelas';




    //Relasi
    public function tugass()
    {
        return $this->hasMany(Tugas::class, 'nama_kelas');
    }
    public function dosens()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }
public function mahasiswas()
{
    return $this->belongsToMany(Mahasiswa::class, 'kelas_mahasiswa')
    ->withPivot('status')
    ->withTimestamps();
}


//fungsi baru
public function mataKuliah()
{
    return $this->belongsTo(MataKuliah::class);
}


}
