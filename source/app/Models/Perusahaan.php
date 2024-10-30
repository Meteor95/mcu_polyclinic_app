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
        'alamat',
        'keterangan'
    ];
    public static function listPerusahaan($req, $perHalaman, $offset)
    {
        $parameterpencarian = $req->parameter_pencarian;
        $query = DB::table((new self())->getTable());
        if (!empty($parameterpencarian)) {
            $query->where('company_name', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('company_code', 'LIKE', '%' . $parameterpencarian . '%');
        }
        $result = $query->take($perHalaman)
            ->skip($offset)
            ->orderBy('company_name', 'ASC')
            ->get();
        $jumlahdata = $query->count();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
}
