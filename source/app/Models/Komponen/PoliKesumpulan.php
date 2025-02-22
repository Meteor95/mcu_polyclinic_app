<?php

namespace App\Models\Komponen;

use Illuminate\Database\Eloquent\Model;

class PoliKesumpulan extends Model
{
    protected $table = 'atribut_poli_kesimpulan';
    protected $fillable = [
        'id',
        'jenis_poli',
        'keterangan_kesimpulan',
    ];
}
