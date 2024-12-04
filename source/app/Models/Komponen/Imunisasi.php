<?php

namespace App\Models\Komponen;

use Illuminate\Database\Eloquent\Model;

class Imunisasi extends Model
{
    protected $table = 'atribut_imunisasi';
    protected $fillable = [
        'nama_atribut_imunisasi',
        'keterangan',
        'status'
    ];
}
