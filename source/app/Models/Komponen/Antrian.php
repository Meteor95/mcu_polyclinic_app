<?php

namespace App\Models\Komponen;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Antrian extends Model
{
    protected $table = "mcu_transaksi_antrian";

    protected $fillable = [
        'id_pendaftaran',
        'jenis_kategori',
        'waktu_masuk',
        'waktu_selesai',
        'status',
        'keterangan',
    ];
    public static function daftarantrian_beranda($req, $perHalaman, $offset){
        $tablePrefix = config('database.connections.mysql.prefix');
        $parameterpencarian = $req->parameter_pencarian;
        $check_kosong_semua = $req->check_kosong_semua;
        $query = DB::table('mcu_transaksi_peserta')
            ->leftJoin('mcu_transaksi_antrian', 'mcu_transaksi_antrian.id_pendaftaran', '=', 'mcu_transaksi_peserta.id')
            ->join('users_member', 'users_member.id', '=', 'mcu_transaksi_peserta.user_id')
            ->select(
                'mcu_transaksi_peserta.id as id_antrian_peserta',
                'users_member.nama_peserta as nama_peserta_antrian',
                DB::raw("MAX(CASE WHEN jenis_kategori = 'tanda_vital' THEN status END) AS tanda_vital_status"),
                DB::raw("MAX(CASE WHEN jenis_kategori = 'spirometri' THEN status END) AS spirometri_status"),
                DB::raw("MAX(CASE WHEN jenis_kategori = 'audiometri' THEN status END) AS audiometri_status"),
                DB::raw("MAX(CASE WHEN jenis_kategori = 'ekg' THEN status END) AS ekg_status"),
                DB::raw("MAX(CASE WHEN jenis_kategori = 'threadmill' THEN status END) AS threadmill_status"),
                DB::raw("MAX(CASE WHEN jenis_kategori = 'rontgen_thorax' THEN status END) AS rontgen_thorax_status"),
                DB::raw("MAX(CASE WHEN jenis_kategori = 'rontgen_lumbosacral' THEN status END) AS rontgen_lumbosacral_status"),
                DB::raw("MAX(CASE WHEN jenis_kategori = 'usg_ubdomain' THEN status END) AS usg_ubdomain_status"),
                DB::raw("MAX(CASE WHEN jenis_kategori = 'farmingham_score' THEN status END) AS farmingham_score_status"),
                DB::raw("MAX(CASE WHEN jenis_kategori = 'poli_dokter' THEN status END) AS poli_dokter_status"),
                DB::raw("MAX(CASE WHEN jenis_kategori = 'kesimpulan' THEN status END) AS kesimpulan_status")
            )
            ->groupBy('mcu_transaksi_peserta.id');
        if (filter_var($check_kosong_semua, FILTER_VALIDATE_BOOLEAN)) {
            $query->havingRaw("
                tanda_vital_status IS NULL AND 
                spirometri_status IS NULL AND 
                audiometri_status IS NULL AND 
                ekg_status IS NULL AND 
                threadmill_status IS NULL AND 
                rontgen_thorax_status IS NULL AND 
                rontgen_lumbosacral_status IS NULL AND 
                usg_ubdomain_status IS NULL AND 
                farmingham_score_status IS NULL AND 
                poli_dokter_status IS NULL AND 
                kesimpulan_status IS NULL
            ");
        }
        $query->orderBy('mcu_transaksi_peserta.id', 'ASC');
        if ($parameterpencarian != "") {
            if (ctype_digit($parameterpencarian)){
                $query->where('id_pendaftaran', $parameterpencarian);
            }else{
                $query->where('nama_peserta', 'like', "%$parameterpencarian%")
                ->orWhere('keterangan', 'like', "%$parameterpencarian%");   
            }
        }
        $jumlahdata = $query->count();
        $result = $query->take($perHalaman)
            ->skip($offset)
            ->get();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
    public static function daftarantrian($req, $perHalaman, $offset){
        $parameterpencarian = $req->parameter_pencarian;
        $kategori_antrian = $req->kategori_antrian;
        $status_antrian_sekarang = $req->status_antrian_sekarang;
        $query = DB::table((new self())->getTable())
        ->join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'mcu_transaksi_antrian.id_pendaftaran')
        ->join('users_member', 'users_member.id', '=', 'mcu_transaksi_peserta.user_id')
        ->select('mcu_transaksi_antrian.id_pendaftaran as id_antrian_peserta','mcu_transaksi_antrian.*','users_member.*','mcu_transaksi_peserta.*');
        if ($kategori_antrian != "") {
            $query->where('jenis_kategori', $kategori_antrian);
        }
        if ($status_antrian_sekarang != "") {
            $query->where('status', $status_antrian_sekarang);
        }
        if ($parameterpencarian != "") {
            if (ctype_digit($parameterpencarian)){
                $query->where('id_pendaftaran', $parameterpencarian);
            }else{
                $query->where('nama_peserta', 'like', "%$parameterpencarian%")
                ->orWhere('keterangan', 'like', "%$parameterpencarian%");   
            }
        }
        $jumlahdata = $query->count();
        $result = $query->take($perHalaman)
            ->skip($offset)
            ->orderBy('mcu_transaksi_antrian.id', 'ASC')
            ->get();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
}
