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
        if ($jenisKesimpulan == "") {
            $queryPoli = DB::table('atribut_poli_kesimpulan')
                ->select(
                    'jenis_poli as jenis_kesimpulan',
                    'keterangan_kesimpulan',
                    'id'
                );

            if (!empty($parameterpencarian)) {
                $queryPoli->where(
                    'keterangan_kesimpulan',
                    'LIKE',
                    '%' . $parameterpencarian . '%'
                );
            }
            $queryDefault = DB::table((new self())->getTable())
                ->select(
                    'jenis_kesimpulan',
                    'keterangan_kesimpulan',
                    'id'
                );

            if (!empty($parameterpencarian)) {
                $queryDefault->where(
                    'keterangan_kesimpulan',
                    'LIKE',
                    '%' . $parameterpencarian . '%'
                );
            }
            $query = $queryPoli->unionAll($queryDefault);
            $finalQuery = DB::query()->fromSub($query, 'x');
            $jumlahdata = $finalQuery->count();
            $result = $finalQuery
                ->orderBy('jenis_kesimpulan', 'ASC')
                ->orderBy('keterangan_kesimpulan', 'ASC')
                ->offset($offset)
                ->limit($perHalaman)
                ->get();
        } else {
            if (str_contains($jenisKesimpulan, 'poli')) {
                $table = 'atribut_poli_kesimpulan';
                $kolom = 'jenis_poli';
            } else {
                $table = (new self())->getTable();
                $kolom = 'jenis_kesimpulan';
            }
            $query = DB::table($table);
            $query->where($kolom, '=', $jenisKesimpulan);
            $query->select(
                "$kolom as jenis_kesimpulan",
                "keterangan_kesimpulan",
                "id"
            );
            if (!empty($parameterpencarian)) {
                $query->where(
                    'keterangan_kesimpulan',
                    'LIKE',
                    '%' . $parameterpencarian . '%'
                );
            }
            $jumlahdata = $query->count();
            $result = $query->orderBy($kolom, 'ASC')
                ->orderBy('keterangan_kesimpulan', 'ASC')
                ->offset($offset)
                ->limit($perHalaman)
                ->get();
        }
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
}
