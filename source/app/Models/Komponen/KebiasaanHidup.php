<?php

namespace App\Models\Komponen;

use Illuminate\Database\Eloquent\Model;

class KebiasaanHidup extends Model
{
    protected $table = 'atribut_kebiasaan_hidup';
    protected $fillable = [
        'nama_atribut_kb',
        'nama_satuan_kb',
        'status',
        'keterangan',
    ];
}
