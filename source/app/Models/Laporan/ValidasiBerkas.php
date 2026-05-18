<?php

namespace App\Models\Laporan;

use Illuminate\Database\Eloquent\Model;

class ValidasiBerkas extends Model
{
    protected $table = 'mcu_validasi_berkas';
    protected $fillable = [
        'id_mcu',
        'nomor_mcu',
        'nik_peserta',
        'hash',
    ];
    public $timestamps = true;
    public static function generateHash($id_mcu, $nomor_mcu, $nik_peserta, $secretKey)
    {
        $stringToHash = $id_mcu . '|' . $nomor_mcu . '|' . $nik_peserta;
        return hash_hmac('sha256', $stringToHash, $secretKey);
    }
    public static function insertIfNotExists($id_mcu, $nomor_mcu, $nik_peserta, $secretKey)
    {
        $hash = self::generateHash($id_mcu, $nomor_mcu, $nik_peserta, $secretKey);

        $record = self::firstOrCreate(
            ['id_mcu' => $id_mcu, 'hash' => $hash],
            ['nomor_mcu' => $nomor_mcu, 'nik_peserta' => $nik_peserta]
        );

        return $record;
    }
    public static function cekHash($id_mcu, $nomor_mcu, $nik_peserta, $secretKey)
    {
        $stringToHash = $id_mcu . '|' . $nomor_mcu . '|' . $nik_peserta;
        $hash = hash_hmac('sha256', $stringToHash, $secretKey);
        return self::where('id_mcu', $id_mcu)
                    ->where('hash', $hash)
                    ->exists();
    }
}
