<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliahs';

    protected $fillable = [
        'kode_mata_kuliah',
        'mata_kuliah',
        'tipe',
        // 'program_studi_id',
        'semester',
        'sks',
        // 'dosen_pengampu',
        // 'deskripsi',
        'status',
    ];



public function programStudis()
{
    return $this->belongsToMany(
        ProgramStudi::class,
        'mata_kuliah_prodis',
        'mata_kuliah_id',
        'nama_prodi_id'
    );
}


    // public function programStudi()
    // {
    // return $this->belongsTo(ProgramStudi::class, 'nama_prodi_id');
    // }
    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }
    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'mata_kuliah_id');
    }
    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }


    




}
