<?php

namespace App\Models\Komponen;

use Illuminate\Database\Eloquent\Model;

class TandaVital extends Model
{
    public $table = 'atribut_tanda_vital';
    protected $fillable = [
        'nama_atribut_tv',
        'satuan',
        'keterangan',
        'status',
    ];
}
