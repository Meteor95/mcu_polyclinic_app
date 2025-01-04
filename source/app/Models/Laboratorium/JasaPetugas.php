<?php

namespace App\Models\Laboratorium;

use Illuminate\Database\Eloquent\Model;

class JasaPetugas extends Model
{
    protected $table = 'lab_jasa_petugas';
    protected $fillable = [
        'nama_jasa',
        'nominal_jasa',
    ];
}
