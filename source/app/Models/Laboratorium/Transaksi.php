<?php

namespace App\Models\Laboratorium;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transaksi extends Model
{
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
        'nominal_apotek',
        'lampirkan_berkas_pdf',
    ];
    public static function listTabelTindakan($req, $perHalaman, $offset){
        $parameterpencarian = $req->parameter_pencarian;
        $status_pembayaran = $req->status_pembayaran;
        $jenis_layanan = $req->jenis_layanan;
        $jenis_transaksi = $req->jenis_transaksi;
        $perusahaan_id = $req->perusahaan_id;
        $tanggal_awal = $req->tanggal_awal." 00:00:00";
        $tanggal_akhir = $req->tanggal_akhir." 23:59:59";
        $tablePrefix = config('database.connections.mysql.prefix');
        $query = Transaksi::rightJoin('mcu_transaksi_peserta', 'transaksi.no_mcu', '=', 'mcu_transaksi_peserta.id')
        ->join('users_member', 'mcu_transaksi_peserta.user_id', '=', 'users_member.id')
        ->join('users','transaksi.id_kasir','=','users.id')
        ->select('transaksi.id as id_transaksi','transaksi.*','mcu_transaksi_peserta.*','users_member.*','users.*','mcu_transaksi_peserta.id as id_transaksi_mcu');
        $query->whereBetween('transaksi.waktu_trx', [$tanggal_awal, $tanggal_akhir]);
        if (!empty($parameterpencarian)) {
            $query->where('transaksi.no_nota', 'LIKE', '%' . $parameterpencarian . '%')
            ->orWhere('transaksi.no_mcu', 'LIKE', '%' . $parameterpencarian . '%')
            ->orWhere('transaksi.nama_dokter', 'LIKE', '%' . $parameterpencarian . '%')
            ->orWhere('transaksi.nama_pj', 'LIKE', '%' . $parameterpencarian . '%')
            ->orWhere('users_member.nomor_identitas', 'LIKE', '%' . $parameterpencarian . '%')
            ->orWhere('users_member.nama_peserta', 'LIKE', '%' . $parameterpencarian . '%');
        }
        if (!empty($status_pembayaran)) {
            $query->where('transaksi.status_pembayaran', $status_pembayaran);
        }
        if (!empty($jenis_layanan)) {
            $query->where('mcu_transaksi_peserta.jenis_transaksi_pendaftaran', $jenis_layanan);
        }
        if (!empty($jenis_transaksi)) {
            $query->where('transaksi.jenis_transaksi', $jenis_transaksi);
        }
        if (!empty($perusahaan_id)) {
            $query->where('mcu_transaksi_peserta.perusahaan_id', $perusahaan_id);
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

