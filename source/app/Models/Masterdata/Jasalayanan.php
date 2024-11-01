<?php

namespace App\Models\Masterdata;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Jasalayanan extends Model
{
    protected $table = 'jasa_layanan';
   
    protected $fillable = [
        'kode_jasa_pelayanan',
        'nama_jasa_pelayanan',
        'nominal_layanan'
    ];
    public static function listJasaPelayanan($req, $perHalaman, $offset)
    {
        $parameterpencarian = $req->parameter_pencarian;
        $query = DB::table((new self())->getTable());
        if (!empty($parameterpencarian)) {
            $query->where('kode_jasa_pelayanan', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('nama_jasa_pelayanan', 'LIKE', '%' . $parameterpencarian . '%');
        }
        $result = $query->take($perHalaman)
            ->skip($offset)
            ->orderBy('nama_jasa_pelayanan', 'ASC')
            ->get();
        $jumlahdata = $query->count();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }   
}
