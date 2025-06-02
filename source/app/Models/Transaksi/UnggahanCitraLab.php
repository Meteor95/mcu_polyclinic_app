<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Model;

class UnggahanCitraLab extends Model
{
    protected $table = 'transaksi_berkas_lab';
    protected $fillable = [
        'id_trx_lab',
        'nama_file_asli',
        'nama_file',
        'meta_citra',
        'width',
        'height',
    ];
}
