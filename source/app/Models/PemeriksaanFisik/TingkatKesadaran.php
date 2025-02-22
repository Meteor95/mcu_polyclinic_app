<?php

namespace App\Models\PemeriksaanFisik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class TingkatKesadaran extends Model
{
    protected $table = 'mcu_pf_tingkat_kesadaran';
    protected $fillable = [
        'user_id',
        'transaksi_id',
        'id_atribut_tingkat_kesadaran',
        'nama_atribut_tingkat_kesadaran',
        'keterangan_tingkat_kesadaran',
        'id_atribut_status_tingkat_kesadaran',
        'nama_atribut_status_tingkat_kesadaran',
        'keterangan_status_tingkat_kesadaran',
        'keluhan',
    ];
    public static function listTingkatKesadaran($request, $perHalaman, $offset){
        $parameterpencarian = $request->parameter_pencarian;
        $tablePrefix = config('database.connections.mysql.prefix');
        $query = DB::table((new self())->getTable())
            ->join('users_member', 'users_member.id', '=', 'mcu_pf_tingkat_kesadaran.user_id')
            ->join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'mcu_pf_tingkat_kesadaran.transaksi_id')
            ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
            ->join('departemen_peserta', 'departemen_peserta.id', '=', 'mcu_transaksi_peserta.departemen_id')
            ->select('company.*','departemen_peserta.*','mcu_pf_tingkat_kesadaran.*','mcu_pf_tingkat_kesadaran.id as id_tingkat_kesadaran', 'users_member.*', 'users_member.nama_peserta', 'mcu_transaksi_peserta.*')
            ->selectRaw('DATE_FORMAT('.$tablePrefix.'mcu_pf_tingkat_kesadaran.created_at, "%d-%m-%Y %H:%i:%s") as tanggal_transaksi_tingkat_kesadaran, TIMESTAMPDIFF(YEAR, ' . $tablePrefix . 'users_member.tanggal_lahir, CURDATE()) AS umur');
        if (!empty($parameterpencarian)) {
            $query->where('users_member.nama_peserta', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('mcu_transaksi_peserta.no_transaksi', 'LIKE', '%' . $parameterpencarian . '%');
        }
        $jumlahdata = $query->groupBy('mcu_pf_tingkat_kesadaran.user_id', 'mcu_pf_tingkat_kesadaran.transaksi_id')
            ->get()
            ->count();
        $result = $query->groupBy('mcu_pf_tingkat_kesadaran.user_id', 'mcu_pf_tingkat_kesadaran.transaksi_id')
            ->orderBy('mcu_pf_tingkat_kesadaran.created_at', 'DESC')
            ->take($perHalaman)
            ->skip($offset)
            ->get();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
}
