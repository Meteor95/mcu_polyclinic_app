<?php

namespace App\Models\Laporan;

use Illuminate\Database\Eloquent\Model;

class Kesimpulan extends Model
{
    protected $table = 'transaksi_kesimpulan';
    protected $fillable = [
        'id_mcu',
        'kesimpulan_pemeriksaan_fisik',
        'status_pemeriksaan_laboratorium',
        'kesimpulan_pemeriksaan_laboratorum',
        'kesimpulan_pemeriksaan_threadmill',
        'kesimpulan_pemeriksaan_ronsen',
        'kesimpulan_pemeriksaan_ekg',
        'kesimpulan_pemeriksaan_audio_kiri',
        'kesimpulan_pemeriksaan_audio_kanan',
        'kesimpulan_pemeriksaan_spiro_restriksi',
        'kesimpulan_pemeriksaan_spiro_obstruksi',
        'kesimpulan_keseluruhan',
        'saran_keseluruhan',
    ];
}
