<?php

namespace App\Models\Laporan;

use Illuminate\Database\Eloquent\Model;

class EdsStatusCekKesimpulan extends Model
{
    protected $table = 'status_cek_kesimpulan';
    protected $fillable = [
        'no_mcu',
        'foto_data_diri',
        'lingkungan_kerja',
        'kecelakaan_kerja',
        'kebiasaan_hidup',
        'penyakit_terdahulu',
        'penyakit_keluarga',
        'imunisasi',
        'tingkat_kesadaran',
        'tanda_vital',
        'penglihatan',
        'kepala',
        'telinga',
        'mata',
        'tenggorokan',
        'mulut',
        'gigi',
        'leher',
        'thorax_fisik',
        'abdomen_urogenital',
        'anorectal_genital',
        'ekstremitas',
        'neurologis',
        'spirometri',
        'ekg',
        'treadmill',
        'rontgen_thorax',
        'rontgen_lumbosacral',
        'usg_abdomen',
        'framingham_score',
        'audiometri',
        'laboratorium_dan_pengobatan',
    ];
}