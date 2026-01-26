<?php  

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MateriKelas extends Model
{
    protected $fillable = [
        'judul_materi',
        'matkul',
        'deskripsi',
        'file_path',
        'file_type',
        'file_size',
    ];
    
}