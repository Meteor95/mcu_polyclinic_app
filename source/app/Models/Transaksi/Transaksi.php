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
        'harga_paket_saat_ini',
        'fitur_paket_mcu_saat_ini',
        'nama_file_surat_pengantar',
        'tipe_pembayaran', // 0 = HUTANG, 1 = TUNAI
        'metode_pembayaran', // 0 = TUNAI, 1 = TRANSFER
        'nominal_pembayaran',
        'penerima_bank_id',
        'nomor_transakasi_transfer',
        'petugas_id'
    ];
    public static function listPasienTabel($request, $perHalaman, $offset)
    {
        $parameterpencarian = $request->parameter_pencarian;
        $tablePrefix = config('database.connections.mysql.prefix');
        $query = DB::table((new self())->getTable())
            ->join('users_member', 'users_member.id', '=', 'mcu_transaksi_peserta.user_id')
            ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
            ->join('departemen_peserta', 'departemen_peserta.id', '=', 'mcu_transaksi_peserta.departemen_id')
            ->join('users_pegawai', 'users_pegawai.id', '=', 'mcu_transaksi_peserta.petugas_id')
            ->join('paket_mcu', 'paket_mcu.id', '=', 'mcu_transaksi_peserta.id_paket_mcu')
            ->join('poli_mcu', DB::raw('FIND_IN_SET(' . $tablePrefix . 'poli_mcu.kode_poli, ' . $tablePrefix . 'mcu_transaksi_peserta.fitur_paket_mcu_saat_ini)'), '>', DB::raw('0'))
            ->select(
                'mcu_transaksi_peserta.id',
                'mcu_transaksi_peserta.no_transaksi',
                'users_member.nama_peserta',
                'company.company_name',
                'departemen_peserta.nama_departemen',
                'users_pegawai.nama_pegawai',
                'paket_mcu.nama_paket',
                DB::raw("DATE_FORMAT(" . $tablePrefix . "mcu_transaksi_peserta.tanggal_transaksi, '%d-%m-%Y %H:%i:%s') as tanggal_transaksi"),
                DB::raw("TIMESTAMPDIFF(YEAR, " . $tablePrefix . "users_member.tanggal_lahir, CURDATE()) AS umur"),
                DB::raw("GROUP_CONCAT(" . $tablePrefix . "poli_mcu.nama_poli ORDER BY " . $tablePrefix . "poli_mcu.kode_poli) AS akses_poli")
            );
        if (!empty($parameterpencarian)) {
            $query->where('no_transaksi', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('nama_peserta', 'LIKE', '%' . $parameterpencarian . '%');
        }
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
        $jumlahdata = $query->count();
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
