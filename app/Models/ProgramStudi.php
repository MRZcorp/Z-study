<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    //
  
    protected $fillable = [
        'kode',
        'nama_prodi',
        'fakultas_id',
        'status',
        
    ];
   
    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }
   
    public function mataKuliah()
{
    return $this->belongsToMany(
        MataKuliah::class,
        'mata_kuliah_prodis',
        'nama_prodi_id',
        'mata_kuliah_id'
    );
}
    
    public function dosens()
    {
        return $this->hasMany(Dosen::class);
    }

    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class);
    }

   
}
