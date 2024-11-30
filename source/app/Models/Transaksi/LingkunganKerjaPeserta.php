<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Model;

class LingkunganKerjaPeserta extends Model
{
    protected $table = 'mcu_lingkungan_kerja_peserta';
    protected $fillable = [
        'user_id',
        'transaksi_id',
        'id_atribut_lk',
        'nama_atribut_saat_ini',
        'status',
        'nilai_jam_per_hari',
        'nilai_selama_x_tahun',
        'keterangan',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
