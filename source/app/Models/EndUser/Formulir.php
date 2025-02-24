<?php

namespace App\Models\EndUser;

use Illuminate\Database\Eloquent\Model;

class Formulir extends Model
{
    protected $table = 'mcu_transaksi_pemesanan';
    protected $fillable = [
        'no_pemesanan',
        'nomor_identifikasi',
        'nama_peserta',
        'json_data_diri',
        'json_lingkungan_kerja',
        'json_kecelakaan_kerja',
        'json_kebiasaan_hidup',
        'json_penyakit_terdahulu',
        'json_penyakit_keluarga',
        'json_imunisasi',
    ];
}
