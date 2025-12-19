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
        'keterangan',
        'akses_tindakan',
        'kategori'
    ];

    public static function listPaketMcu($req, $perHalaman, $offset)
    {
        $parameterpencarian = $req->parameter_pencarian;
        $kategori_paket = $req->kategori_paket;
        $tablePrefix = config('database.connections.mysql.prefix');
        $query = PaketMCU::query()
        ->where('id', '>', 1);
        if (!empty($parameterpencarian)) {
            $query->where('paket_mcu.kode_paket', 'LIKE', '%' . $parameterpencarian . '%')
                ->orWhere('paket_mcu.nama_paket', 'LIKE', '%' . $parameterpencarian . '%')
                ->orWhere('paket_mcu.keterangan', 'LIKE', '%' . $parameterpencarian . '%');
        }
        if (!empty($kategori_paket)) {
            $query->where('paket_mcu.kategori', '=', $kategori_paket);
        }
        $jumlahdata = $query->count();
        $result = $query->take($perHalaman)
            ->skip($offset)
            ->orderBy('paket_mcu.id', 'ASC')
            ->get();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];

    }
}
