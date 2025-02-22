<?php

namespace App\Models\Masterdata;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DaftarBank extends Model
{
    protected $table = 'daftar_bank';
    protected $fillable = ['kode_bank', 'nama_bank','keterangan'];

    public static function listBank($req, $perHalaman, $offset)
    {
        $parameterpencarian = $req->parameter_pencarian;
        $query = DB::table((new self())->getTable());
        if (!empty($parameterpencarian)) {
            $query->where('kode_bank', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('nama_bank', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('keterangan', 'LIKE', '%' . $parameterpencarian . '%');
        }
        $result = $query->take($perHalaman)
            ->skip($offset)
            ->orderBy('nama_bank', 'ASC')
            ->get();
        $jumlahdata = $query->count();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
}
