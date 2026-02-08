<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasFile extends Model
{
    use HasFactory;

    protected $table = 'tugas_files';

    protected $fillable = [
        'tugas_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
    ];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }
}