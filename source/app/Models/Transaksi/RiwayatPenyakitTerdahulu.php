<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RiwayatPenyakitTerdahulu extends Model
{
    protected $table = 'mcu_riwayat_penyakit_terdahulu';
    protected $fillable = [
        'user_id',
        'transaksi_id',
        'id_atribut_pt',
        'nama_atribut_saat_ini',
        'status',
        'keterangan'
    ];
    public static function listPesertaRiwayatPenyakitTerdahuluTabel($request, $perHalaman, $offset)
    {
        $parameterpencarian = $request->parameter_pencarian;
        $tablePrefix = config('database.connections.mysql.prefix');
        $query = DB::table((new self())->getTable())
            ->join('users_member', 'users_member.id', '=', 'mcu_riwayat_penyakit_terdahulu.user_id')
            ->join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'mcu_riwayat_penyakit_terdahulu.transaksi_id')
            ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
            ->join('departemen_peserta', 'departemen_peserta.id', '=', 'mcu_transaksi_peserta.departemen_id')
            ->select('company.*','departemen_peserta.*','mcu_riwayat_penyakit_terdahulu.*','mcu_riwayat_penyakit_terdahulu.id as id_riwayat_penyakit_terdahulu', 'users_member.*', 'users_member.nama_peserta', 'mcu_transaksi_peserta.*')
            ->selectRaw('DATE_FORMAT('.$tablePrefix.'mcu_riwayat_penyakit_terdahulu.created_at, "%d-%m-%Y %H:%i:%s") as tanggal_transaksi_penyakit_terdahulu, TIMESTAMPDIFF(YEAR, ' . $tablePrefix . 'users_member.tanggal_lahir, CURDATE()) AS umur');
        if (!empty($parameterpencarian)) {
            $query->where('users_member.nama_peserta', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('mcu_transaksi_peserta.no_transaksi', 'LIKE', '%' . $parameterpencarian . '%');
        }
        $jumlahdata = $query->groupBy('mcu_riwayat_penyakit_terdahulu.user_id', 'mcu_riwayat_penyakit_terdahulu.transaksi_id')
            ->get()
            ->count();
        $result = $query->groupBy('mcu_riwayat_penyakit_terdahulu.user_id', 'mcu_riwayat_penyakit_terdahulu.transaksi_id')
            ->orderBy('mcu_riwayat_penyakit_terdahulu.created_at', 'DESC')
            ->take($perHalaman)
            ->skip($offset)
            ->get();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
}
