<?php

namespace App\Models\Komponen;

use Illuminate\Database\Eloquent\Model;

class KondisiFisik extends Model
{
    protected $table = 'atribut_kondisi_fisik';
    protected $fillable = [
        'nama_atribut_fisik',
        'kategori_lokasi_fisik',
        'jenis_pemeriksaan',
        'status',
        'keterangan',
    ];
}
