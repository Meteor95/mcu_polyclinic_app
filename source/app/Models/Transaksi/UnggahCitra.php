<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UnggahCitra extends Model
{
    protected $table = 'mcu_peserta_gambar';
    protected $fillable = [
        'id',
        'user_id',
        'transaksi_id',
        'lokasi_gambar',
    ];
    public static function listPasienUnggahCitra($request, $perHalaman, $offset){
        $parameterpencarian = $request->parameter_pencarian;
        $tablePrefix = config('database.connections.mysql.prefix');
        $query = DB::table((new self())->getTable())
            ->join('users_member', 'users_member.id', '=', 'mcu_peserta_gambar.user_id')
            ->join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'mcu_peserta_gambar.transaksi_id')
            ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
            ->join('departemen_peserta', 'departemen_peserta.id', '=', 'mcu_transaksi_peserta.departemen_id')
            ->select('company.*','departemen_peserta.*','mcu_peserta_gambar.*','mcu_peserta_gambar.id as id_peserta_gambar', 'users_member.*', 'users_member.nama_peserta', 'mcu_transaksi_peserta.*')
            ->selectRaw('TIMESTAMPDIFF(YEAR, ' . $tablePrefix . 'users_member.tanggal_lahir, CURDATE()) AS umur, DATE_FORMAT(tanggal_transaksi, "%d-%m-%Y %H:%i:%s") as tanggal_transaksi');
        if (!empty($parameterpencarian)) {
            $query->where('users_member.nama_peserta', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('mcu_transaksi_peserta.no_transaksi', 'LIKE', '%' . $parameterpencarian . '%');
        }
        $jumlahdata = $query->count();
        $result = $query->take($perHalaman)
            ->skip($offset)
            ->orderBy((new self())->getTable().'.created_at', 'DESC')
            ->get();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
}
