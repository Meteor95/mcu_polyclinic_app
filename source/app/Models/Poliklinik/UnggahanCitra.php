<?php

namespace App\Models\Poliklinik;

use Illuminate\Database\Eloquent\Model;

class UnggahanCitra extends Model
{
    protected $table = 'mcu_poli_citra';
    protected $fillable = [
        'id_trx_poli',
        'jenis_poli',
        'nama_file_asli',
        'nama_file',
        'meta_citra',
    ];
}
