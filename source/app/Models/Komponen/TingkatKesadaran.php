<?php

namespace App\Models\Komponen;

use Illuminate\Database\Eloquent\Model;

class TingkatKesadaran extends Model
{
    protected $table = 'atribut_tingkat_kesadaran';
    protected $fillable = [
        'jenis_tingkat_kesadaran',
        'nama_tingkat_kesadaran',
        'keterangan_tingkat_kesadaran',
        'status'
    ];
}
