<?php

namespace App\Models\Komponen;

use Illuminate\Database\Eloquent\Model;

class PenyakitKeluarga extends Model
{
    protected $table = 'atribut_penyakit_keluarga';
    protected $fillable = [
        'nama_atribut_pk',
        'status',
        'keterangan',
    ];
}
