<?php

namespace App\Models\Laporan;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $table = 'transaksi_tagihan';
    protected $fillable = [
        'nomor_tagihan',
        'nomor_surat',
        'id_perusahaan',
        'array_mcu_peserta_id',
    ];
}
