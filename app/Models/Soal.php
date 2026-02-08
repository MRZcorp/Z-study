<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    /** @use HasFactory<\Database\Factories\SoalFactory> */
    use HasFactory;

    protected $fillable = [
        'ujian_id',
        'tipe',
        'pertanyaan',
        'media_path',
        'bobot',
        'options',
        'pg_correct',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }
}
