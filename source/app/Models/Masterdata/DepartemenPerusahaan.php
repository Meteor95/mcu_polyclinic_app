<?php

namespace App\Models\Masterdata;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DepartemenPerusahaan extends Model
{
    protected $table = 'departemen_peserta';
    protected $fillable = [
        'kode_departemen',
        'nama_departemen',
        'keterangan'
    ];

    public static function listGetDepartemen($req, $perHalaman, $offset)
    {
        $parameterpencarian = $req->parameter_pencarian;
        $query = DB::table((new self())->getTable());
        if (!empty($parameterpencarian)) {
            $query->where('kode_departemen', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('nama_departemen', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('keterangan', 'LIKE', '%' . $parameterpencarian . '%');
        }
        $result = $query->take($perHalaman)
            ->skip($offset)
            ->orderBy('nama_departemen', 'ASC')
            ->get();
        $jumlahdata = $query->count();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
}
