<?php

namespace App\Models\Komponen;

use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    protected $table = 'poli_mcu';

    protected $fillable = [
        'kode_poli',
        'nama_poli',
    ];
}
