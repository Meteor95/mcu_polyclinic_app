<?php

namespace App\Models\Pendaftaran;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Peserta extends Model
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

    public static function listPesertaTabel($req, $perHalaman, $offset)
    {
        $parameterpencarian = $req->parameter_pencarian;
        $tablePrefix = config('database.connections.mysql.prefix');
        $query = DB::table((new self())->getTable());
        if (!empty($parameterpencarian)) {
            $query->where('no_pemesanana', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('nomor_identifikasi', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('nama_peserta', 'LIKE', '%' . $parameterpencarian . '%');
        }
        $jumlahdata = $query->count();
        $result = $query->take($perHalaman)
            ->skip($offset)
            ->orderBy('created_at', 'DESC')
            ->get();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
}
