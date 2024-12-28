<?php

namespace App\Models\Komponen;

use Illuminate\Database\Eloquent\Model;

class PoliDetailKesimpulan extends Model
{
    protected $table = 'atribut_poli_detail_keterangan';
    protected $fillable = [
        'id',
        'jenis_poli',
        'keterangan',
    ];
}
