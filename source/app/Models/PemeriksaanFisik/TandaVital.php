<?php

namespace App\Models\PemeriksaanFisik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TandaVital extends Model
{
    protected $table = 'mcu_pf_tanda_vital';
    protected $fillable = [
        'user_id',
        'transaksi_id',
        'id_atribut_lv',
        'nama_atribut_saat_ini',
        'nilai_tanda_vital',
        'satuan_tanda_vital',
        'keterangan_tanda_vital',
    ];
    public static function listTandaVital($request, $perHalaman, $offset){
        $parameterpencarian = $request->parameter_pencarian;
        $tablePrefix = config('database.connections.mysql.prefix');
        $query = DB::table((new self())->getTable())
            ->join('users_member', 'users_member.id', '=', 'mcu_pf_tanda_vital.user_id')
            ->join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'mcu_pf_tanda_vital.transaksi_id')
            ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
            ->join('departemen_peserta', 'departemen_peserta.id', '=', 'mcu_transaksi_peserta.departemen_id')
            ->select('company.*','departemen_peserta.*','mcu_pf_tanda_vital.*','mcu_pf_tanda_vital.id as id_tanda_vital', 'users_member.*', 'users_member.nama_peserta', 'mcu_transaksi_peserta.*')
            ->selectRaw('DATE_FORMAT('.$tablePrefix.'mcu_pf_tanda_vital.created_at, "%d-%m-%Y %H:%i:%s") as tanggal_transaksi_tanda_vital, TIMESTAMPDIFF(YEAR, ' . $tablePrefix . 'users_member.tanggal_lahir, CURDATE()) AS umur');
        if (!empty($parameterpencarian)) {
            $query->where('users_member.nama_peserta', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('mcu_transaksi_peserta.no_transaksi', 'LIKE', '%' . $parameterpencarian . '%');
        }
        $jumlahdata = $query->groupBy('mcu_pf_tanda_vital.user_id', 'mcu_pf_tanda_vital.transaksi_id')
            ->get()
            ->count();
        $result = $query->groupBy('mcu_pf_tanda_vital.user_id', 'mcu_pf_tanda_vital.transaksi_id')
            ->orderBy('mcu_pf_tanda_vital.created_at', 'DESC')
            ->take($perHalaman)
            ->skip($offset)
            ->get();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
}


