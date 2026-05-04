<?php

namespace App\Models\Masterdata;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Kesimpulan extends Model
{
    protected $table = 'lab_kesimpulan_tindakan';
    protected $fillable = ['jenis_kesimpulan', 'keterangan_kesimpulan'];

    public static function listKesimpulan($req, $perHalaman, $offset)
    {
        $parameterpencarian = $req->parameter_pencarian;
        $jenisKesimpulan = $req->jenis_kesimpulan;
        $kolom = "";
        if (str_contains($jenisKesimpulan, 'poli')) {
            $table = 'atribut_poli_kesimpulan';
            $kolom = 'jenis_poli';
            $kolomKeterangan = 'keterangan_kesimpulan';
        }else{
            $table = (new self())->getTable();
            $kolom = 'jenis_kesimpulan';
            $kolomKeterangan = 'keterangan_kesimpulan';
        }
        $query = DB::table($table);
        if (!empty($jenisKesimpulan)) {
            $query->where($kolom, '=', $jenisKesimpulan);
        }
        $query->select("$kolom as jenis_kesimpulan","keterangan_kesimpulan","id");
        if (!empty($parameterpencarian)) {
            $query->where($kolomKeterangan, 'LIKE', '%' . $parameterpencarian . '%');
        }
        $jumlahdata = $query->count();
        $result = $query->take($perHalaman)
            ->skip($offset)
            ->orderBy($kolom, 'ASC')
            ->orderBy($kolomKeterangan, 'ASC')
            ->get();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
}
