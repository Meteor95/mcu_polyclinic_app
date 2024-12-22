<?php

namespace App\Models\PemeriksaanFisik\KondisiFisik;

use Illuminate\Database\Eloquent\Model;

class Gigi extends Model
{
    protected $table = 'mcu_pf_gigi_lokasi';
    protected $fillable = [
        'user_id',
        'transaksi_id',
        'atas_kanan_8',
        'atas_kanan_7',
        'atas_kanan_6',
        'atas_kanan_5',
        'atas_kanan_4',
        'atas_kanan_3',
        'atas_kanan_2',
        'atas_kanan_1',
        'atas_kiri_1',
        'atas_kiri_2',
        'atas_kiri_3',
        'atas_kiri_4',
        'atas_kiri_5',
        'atas_kiri_6',
        'atas_kiri_7',
        'atas_kiri_8',
        'bawah_kanan_8',
        'bawah_kanan_7',
        'bawah_kanan_6',
        'bawah_kanan_5',
        'bawah_kanan_4',
        'bawah_kanan_3',
        'bawah_kanan_2',
        'bawah_kanan_1',
        'bawah_kiri_1',
        'bawah_kiri_2',
        'bawah_kiri_3',
        'bawah_kiri_4',
        'bawah_kiri_5',
        'bawah_kiri_6',
        'bawah_kiri_7',
        'bawah_kiri_8',
    ];
}


