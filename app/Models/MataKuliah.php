<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliah';

    protected $fillable = [
        'kode_mata_kuliah',
        'mata_kuliah',
        'semester',
        'sks',
        'dosen_pengampu',
        'deskripsi',
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
}
