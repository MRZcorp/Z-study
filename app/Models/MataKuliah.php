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
        'program_studi_id',
        'semester',
        'sks',
        // 'dosen_pengampu',
        // 'deskripsi',
        'status',
    ];

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'mata_kuliah_id');
    }
    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }


    //fungsi baru
    public function kelas()
{
    return $this->hasMany(Kelas::class);
}
public function prodi()
{
    return $this->belongsTo(ProgramStudi::class);
}

public function programStudi()
{
    return $this->belongsTo(ProgramStudi::class, 'nama_prodi_id');
}

}
