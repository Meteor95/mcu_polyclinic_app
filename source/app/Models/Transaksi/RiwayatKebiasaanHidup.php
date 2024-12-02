<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Model;

class RiwayatKebiasaanHidup extends Model
{
    protected $table = 'mcu_riwayat_kebiasaan_hidup';
    protected $fillable = [
        'user_id',
        'transaksi_id',
        'nama_kebiasaan',
        'nilai_kebiasaan',
        'satuan_kebiasaan'
    ];
    public static function listPesertaKebiasaanHidupTabel($request, $perHalaman, $offset){
        $parameterpencarian = $request->parameter_pencarian;
        $tablePrefix = config('database.connections.mysql.prefix');
        $query = DB::table((new self())->getTable())
            ->join('users_member', 'users_member.id', '=', 'mcu_riwayat_kebiasaan_hidup.user_id')
            ->join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'mcu_riwayat_kebiasaan_hidup.transaksi_id')
            ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
            ->join('departemen_peserta', 'departemen_peserta.id', '=', 'mcu_transaksi_peserta.departemen_id')
            ->select('company.*','departemen_peserta.*','mcu_riwayat_kebiasaan_hidup.*','mcu_riwayat_kebiasaan_hidup.id as id_riwayat_kebiasaan_hidup', 'users_member.*', 'users_member.nama_peserta', 'mcu_transaksi_peserta.*')
            ->selectRaw('TIMESTAMPDIFF(YEAR, ' . $tablePrefix . 'users_member.tanggal_lahir, CURDATE()) AS umur, DATE_FORMAT(tanggal_transaksi, "%d-%m-%Y %H:%i:%s") as tanggal_transaksi');
        if (!empty($parameterpencarian)) {
            $query->where('users_member.nama_peserta', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('mcu_transaksi_peserta.no_transaksi', 'LIKE', '%' . $parameterpencarian . '%');
        }
        $jumlahdata = $query->count();
        $result = $query->orderBy('mcu_riwayat_kebiasaan_hidup.created_at', 'DESC')
            ->take($perHalaman)
            ->skip($offset)
            ->get();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
}
