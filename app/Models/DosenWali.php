<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenWali extends Model
{
    use HasFactory;

    protected $fillable = [
        'dosen_id',
        'nama_prodi_id',
        'angkatan_id',
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'nama_prodi_id');
    }

    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class);
    }
}
