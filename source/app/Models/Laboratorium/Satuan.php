<?php

namespace App\Models\Laboratorium;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Satuan extends Model
{
    protected $table = 'lab_satuan_item';
    protected $fillable = ['nama_satuan', 'grup_satuan'];
    
    public static function listSatuanTabel($req, $perHalaman, $offset){
        $parameterpencarian = $req->parameter_pencarian;
        $tablePrefix = config('database.connections.mysql.prefix');
        $query = DB::table('lab_satuan_item');
        if (!empty($parameterpencarian)) {
            $query->where('lab_satuan_item.nama_satuan', 'LIKE', '%' . $parameterpencarian . '%');
        }
        $jumlahdata = $query->count();
        $result = $query->take($perHalaman)
            ->skip($offset)
            ->orderBy('lab_satuan_item.nama_satuan', 'ASC')
            ->get();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
}
