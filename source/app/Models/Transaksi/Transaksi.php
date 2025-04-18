<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaksi extends Model
{
    protected $table = 'mcu_transaksi_peserta';
    protected $fillable = [
        'no_transaksi',
        'tanggal_transaksi',
        'user_id',
        'perusahaan_id', 
        'departemen_id',
        'proses_kerja',
        'id_paket_mcu',
        'petugas_id',
        'jenis_transaksi_pendaftaran',
        'status_peserta'
    ];
    public static function listPasienTabel($request, $perHalaman, $offset)
    {
        $parameterpencarian = $request->parameter_pencarian;
        $status_peserta = $request->status_peserta;
        $from_query = $request->from_query;
        $tablePrefix = config('database.connections.mysql.prefix');
        $query = DB::table((new self())->getTable())
            ->join('users_member', 'users_member.id', '=', 'mcu_transaksi_peserta.user_id')
            ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
            ->join('departemen_peserta', 'departemen_peserta.id', '=', 'mcu_transaksi_peserta.departemen_id')
            ->join('users_pegawai', 'users_pegawai.id', '=', 'mcu_transaksi_peserta.petugas_id')
            ->join('paket_mcu', 'paket_mcu.id', '=', 'mcu_transaksi_peserta.id_paket_mcu')
            ->select(
                'mcu_transaksi_peserta.id',
                'mcu_transaksi_peserta.no_transaksi',
                'users_member.nama_peserta',
                'company.company_name',
                'departemen_peserta.nama_departemen',
                'users_pegawai.nama_pegawai',
                'paket_mcu.nama_paket',
                'mcu_transaksi_peserta.jenis_transaksi_pendaftaran',
                'mcu_transaksi_peserta.status_peserta',
                DB::raw("DATE_FORMAT(" . $tablePrefix . "mcu_transaksi_peserta.tanggal_transaksi, '%d-%m-%Y %H:%i:%s') as tanggal_transaksi"),
                DB::raw("TIMESTAMPDIFF(YEAR, " . $tablePrefix . "users_member.tanggal_lahir, CURDATE()) AS umur")
            );
        if (!empty($parameterpencarian)) {
            $query->where('no_transaksi', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('nama_peserta', 'LIKE', '%' . $parameterpencarian . '%');
        }
        if (!empty($status_peserta)) {
            $query->where('mcu_transaksi_peserta.status_peserta', '=', $status_peserta);
        }
        $jumlahdata = $query->groupBy('mcu_transaksi_peserta.id')
            ->get()
            ->count();
        $result = $query->take($perHalaman)
            ->skip($offset)
            ->groupBy(
                'mcu_transaksi_peserta.id',
                'mcu_transaksi_peserta.no_transaksi',
                'users_member.nama_peserta',
                'company.company_name',
                'departemen_peserta.nama_departemen',
                'users_pegawai.nama_pegawai',
                'paket_mcu.nama_paket',
                'mcu_transaksi_peserta.tanggal_transaksi',
                'users_member.tanggal_lahir'
            )
            ->orderBy('mcu_transaksi_peserta.tanggal_transaksi', 'DESC')
            ->get();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
    public static function getPasienById($id)
    {
        $tablePrefix = config('database.connections.mysql.prefix');
        return self::join('users_member', 'users_member.id', '=', 'mcu_transaksi_peserta.user_id')
        ->where('mcu_transaksi_peserta.id', $id)
        ->select(
            'mcu_transaksi_peserta.*',
            'users_member.*',
            DB::raw( $tablePrefix . 'mcu_transaksi_peserta.*, ' . $tablePrefix . 'users_member.*, TIMESTAMPDIFF(YEAR, ' . $tablePrefix . 'users_member.tanggal_lahir, CURDATE()) AS umur')
        )
        ->first();
    }
}
