<?php

namespace App\Models\Komponen;

use Illuminate\Database\Eloquent\Model;

class PenyakitTerdahulu extends Model
{
    protected $table = 'atribut_penyakit_terdahulu';
    protected $fillable = [
        'nama_atribut_pt',
        'keterangan',
        'status',
    ];
}
