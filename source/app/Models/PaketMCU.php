<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaketMCU extends Model
{
    protected $table = 'paket_mcu';

    protected $fillable = [
        'kode_paket',
        'nama_paket',
        'harga_paket',
        'akses_poli',
        'keterangan',
    ];

    public static function listPaketMcu($req, $perHalaman, $offset)
    {
        $parameterpencarian = $req->parameter_pencarian;
        $tablePrefix = config('database.connections.mysql.prefix');
        $query = DB::table('paket_mcu')
            ->select('paket_mcu.id', 'paket_mcu.kode_paket', 'paket_mcu.nama_paket', 'paket_mcu.harga_paket', 'paket_mcu.keterangan',
                DB::raw('GROUP_CONCAT('.$tablePrefix.'poli_mcu.nama_poli ORDER BY '.$tablePrefix.'poli_mcu.nama_poli) AS akses_poli')
            )
            ->join('poli_mcu', DB::raw('FIND_IN_SET('.$tablePrefix.'poli_mcu.kode_poli, '.$tablePrefix.'paket_mcu.akses_poli)'), '>', DB::raw('0'))
            ->groupBy('paket_mcu.id', 'paket_mcu.kode_paket', 'paket_mcu.nama_paket', 'paket_mcu.harga_paket', 'paket_mcu.keterangan');

        if (!empty($parameterpencarian)) {
            $query->where('paket_mcu.kode_paket', 'LIKE', '%' . $parameterpencarian . '%')
                ->orWhere('paket_mcu.nama_paket', 'LIKE', '%' . $parameterpencarian . '%')
                ->orWhere('paket_mcu.keterangan', 'LIKE', '%' . $parameterpencarian . '%');
        }
        $jumlahdata = $query->count();
        $result = $query->take($perHalaman)
            ->skip($offset)
            ->orderBy('paket_mcu.nama_paket', 'ASC')
            ->get();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];

    }
}
