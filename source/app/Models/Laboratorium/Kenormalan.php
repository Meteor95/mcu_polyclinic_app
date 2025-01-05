<?php

namespace App\Models\Laboratorium;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Kenormalan extends Model
{
    protected $table = 'lab_nilai_rentang_kenormalan';
    protected $fillable = [
        'umur',
        'nama_rentang_kenormalan',
        'jenis_kelamin',
    ];
    public static function listRentangTabel($req, $perHalaman, $offset){
        $parameterpencarian = $req->parameter_pencarian;
        $tablePrefix = config('database.connections.mysql.prefix');
        $query = DB::table('lab_nilai_rentang_kenormalan');
        if (!empty($parameterpencarian)) {
            $query->where('lab_nilai_rentang_kenormalan.nama_rentang_kenormalan', 'LIKE', '%' . $parameterpencarian . '%');
        }
        $jumlahdata = $query->count();
        $result = $query->take($perHalaman)
            ->skip($offset)
            ->orderBy('lab_nilai_rentang_kenormalan.nama_rentang_kenormalan', 'ASC')
            ->get();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
}
