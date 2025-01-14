<?php

namespace App\Models\Laboratorium;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use SoftDeletes;
    protected $table = 'transaksi';
    protected $fillable = [
        'no_mcu',
        'no_nota',
        'waktu_trx',
        'waktu_trx_sample',
        'id_dokter',
        'nama_dokter',
        'id_pj',
        'nama_pj',
        'total_bayar',
        'total_transaksi',
        'total_tindakan',
        'jenis_transaksi',
        'metode_pembayaran',
        'id_kasir',
        'status_pembayaran',
        'jenis_layanan',
        'nama_file_surat_pengantar',
        'is_paket_mcu',
        'nama_paket_mcu',
    ];
    public static function listTabelTindakan($req, $perHalaman, $offset){
        $parameterpencarian = $req->parameter_pencarian;
        $tablePrefix = config('database.connections.mysql.prefix');
        $query = DB::table('transaksi')
        ->join('mcu_transaksi_peserta', 'transaksi.no_mcu', '=', 'mcu_transaksi_peserta.id')
        ->join('users_member', 'mcu_transaksi_peserta.user_id', '=', 'users_member.id')
        ->join('users','transaksi.id_kasir','=','users.id');
        if (!empty($parameterpencarian)) {
            $query->where('transaksi.no_mcu', 'LIKE', '%' . $parameterpencarian . '%')
            ->orWhere('transaksi.no_nota', 'LIKE', '%' . $parameterpencarian . '%');
        }
        $jumlahdata = $query->count();
        $result = $query->take($perHalaman)
            ->skip($offset)
            ->orderBy('transaksi.waktu_trx', 'DESC')
            ->get();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
}

