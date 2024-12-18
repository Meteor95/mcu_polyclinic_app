<?php

namespace App\Models\PemeriksaanFisik;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Penglihatan extends Model
{
    protected $table = 'mcu_pf_penglihatan';
    protected $primaryKey = 'id_penglihatan';   
    protected $fillable = [
        'user_id', 
        'transaksi_id',
        'visus_os_tanpa_kacamata_jauh',
        'visus_od_tanpa_kacamata_jauh', 
        'visus_os_kacamata_jauh',
        'visus_od_kacamata_jauh', 
        'visus_os_tanpa_kacamata_dekat', 
        'visus_od_tanpa_kacamata_dekat', 
        'visus_os_kacamata_dekat', 
        'visus_od_kacamata_dekat', 
        'buta_warna', 
        'lapang_pandang_superior_os', 
        'lapang_pandang_inferior_os', 
        'lapang_pandang_temporal_os', 
        'lapang_pandang_nasal_os', 
        'lapang_pandang_keterangan_os', 
        'lapang_pandang_superior_od', 
        'lapang_pandang_inferior_od', 
        'lapang_pandang_temporal_od', 
        'lapang_pandang_nasal_od', 
        'lapang_pandang_keterangan_od'
    ];
    public static function listPenglihatan($request, $perHalaman, $offset){
        $parameterpencarian = $request->parameter_pencarian;
        $tablePrefix = config('database.connections.mysql.prefix');
        $query = DB::table((new self())->getTable())
            ->join('users_member', 'users_member.id', '=', 'mcu_pf_penglihatan.user_id')
            ->join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'mcu_pf_penglihatan.transaksi_id')
            ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
            ->join('departemen_peserta', 'departemen_peserta.id', '=', 'mcu_transaksi_peserta.departemen_id')
            ->select('company.*','departemen_peserta.*','mcu_pf_penglihatan.*','mcu_pf_penglihatan.id as id_penglihatan', 'users_member.*', 'users_member.nama_peserta', 'mcu_transaksi_peserta.*')
            ->selectRaw('DATE_FORMAT('.$tablePrefix.'mcu_pf_penglihatan.created_at, "%d-%m-%Y %H:%i:%s") as tanggal_transaksi_penglihatan, TIMESTAMPDIFF(YEAR, ' . $tablePrefix . 'users_member.tanggal_lahir, CURDATE()) AS umur');
        if (!empty($parameterpencarian)) {
            $query->where('users_member.nama_peserta', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('mcu_transaksi_peserta.no_transaksi', 'LIKE', '%' . $parameterpencarian . '%');
        }
        $jumlahdata = $query->groupBy('mcu_pf_penglihatan.user_id', 'mcu_pf_penglihatan.transaksi_id')
            ->get()
            ->count();
        $result = $query->groupBy('mcu_pf_penglihatan.user_id', 'mcu_pf_penglihatan.transaksi_id')
            ->orderBy('mcu_pf_penglihatan.created_at', 'DESC')
            ->take($perHalaman)
            ->skip($offset)
            ->get();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
}

