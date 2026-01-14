<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Perusahaan extends Model
{
    protected $table = 'company';

    protected $fillable = [
        'company_code',
        'company_name',
        'company_alias_name',
        'alamat',
        'keterangan'
    ];
    public static function listPerusahaan($req, $perHalaman, $offset)
    {
        $parameterpencarian = $req->parameter_pencarian;
        $id_perusahaan = $req->id_perusahaan;
        $query = DB::table((new self())->getTable());
        if (!empty($id_perusahaan)) {
            $query->whereIn('id', $id_perusahaan);
        }else if (!empty($parameterpencarian)) {
            $query->where('company_name', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('company_code', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('company_alias_name', 'LIKE', '%' . $parameterpencarian . '%');
        }
        $jumlahdata = $query->count();
        $result = $query->take($perHalaman)
            ->skip($offset)
            ->orderBy('company_name', 'ASC')
            ->get();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
}
