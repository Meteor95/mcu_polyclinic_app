<?php

namespace App\Models\Komponen;

use Illuminate\Database\Eloquent\Model;

class LingkunganKerja extends Model
{
    protected $table = 'atribut_lingkungan_kerja';
    protected $fillable = [
        'nama_atribut_lk',
        'status',
        'keterangan'
    ];
}
