<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diskusi extends Model
{
    /** @use HasFactory<\Database\Factories\DiskusiFactory> */
    use HasFactory;

    protected $fillable = [
        'kelas_id',
        'ujian_id',
        'tugas_id',
        'user_id',
        'pesan',
        'lampiran_path',
        'lampiran_name',
        'lampiran_mime',
        'lampiran_size',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ujian()
    {
        return $this->belongsTo(Ujian::class, 'ujian_id');
    }

    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'tugas_id');
    }
}
