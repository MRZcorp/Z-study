<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KrsSetting extends Model
{
    use HasFactory;

    protected $table = 'krs_settings';

    protected $fillable = [
        'mulai_tahun_ajar',
        'akhir_tahun_ajar',
        'semester',
        'status',
    ];
}
