<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengumumanRead extends Model
{
    use HasFactory;

    protected $table = 'pengumuman_reads';

    protected $fillable = [
        'user_id',
        'pengumuman_id',
        'read_at',
    ];
}
