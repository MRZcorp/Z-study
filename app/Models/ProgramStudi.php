<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    //
    public function mataKuliah()
{
    return $this->hasMany(MataKuliah::class, 'nama_prodi_id');
}

}
