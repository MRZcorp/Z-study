<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapBobot extends Model
{
    protected $fillable = [
        'kelas_id',
        'harian',
        'keaktifan',
        'kecepatan',
        'absensi',
        'uts',
        'uas',
    ];
}
