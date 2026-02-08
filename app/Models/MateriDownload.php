<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriDownload extends Model
{
    use HasFactory;

    protected $table = 'materi_downloads';

    protected $fillable = [
        'materi_id',
        'mahasiswa_id',
        'downloaded_at',
    ];

    protected $casts = [
        'downloaded_at' => 'datetime',
    ];
}
