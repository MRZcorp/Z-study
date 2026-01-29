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
        'dosen',
        'email',
        'no_hp',
        'gelar',
        'status',
        'poto_profil',
    ];
    // RELASI
    public function mataKuliah()
    {
        return $this->hasMany(MataKuliah::class);
    }
    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }
    public function kelas()
    {
        return $this->hasMany(kelas::class, 'dosen_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
