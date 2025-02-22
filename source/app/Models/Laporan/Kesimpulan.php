<?php

namespace App\Models\Laporan;

use Illuminate\Database\Eloquent\Model;

class Kesimpulan extends Model
{
    protected $table = 'transaksi_kesimpulan';
    protected $fillable = [
        'id_mcu',
        'kesimpulan_riwayat_medis',
        'kesimpulan_pemeriksaan_fisik',
        'status_pemeriksaan_laboratorium',
        'kesimpulan_pemeriksaan_laboratorum',
        'kesimpulan_pemeriksaan_threadmill',
        'kesimpulan_pemeriksaan_rontgen_thorax',
        'kesimpulan_pemeriksaan_rontgen_lumbosacral',
        'kesimpulan_pemeriksaan_usg_ubdomain',
        'kesimpulan_pemeriksaan_farmingham_score',
        'kesimpulan_pemeriksaan_ekg',
        'kesimpulan_pemeriksaan_audio_kiri',
        'kesimpulan_pemeriksaan_audio_kanan',
        'kesimpulan_pemeriksaan_spiro_restriksi',
        'kesimpulan_pemeriksaan_spiro_obstruksi',
        'kesimpulan_keseluruhan',
        'kesimpulan_hasil_medical_checkup',
        'saran_keseluruhan',
    ];
}
