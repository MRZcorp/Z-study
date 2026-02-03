<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    //
    protected $fillable = [
        'kode',
        'fakultas',
        'status'
        
        
    ];
    public function programStudis()
    {
        return $this->hasMany(ProgramStudi::class);
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
