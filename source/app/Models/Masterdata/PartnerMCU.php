<?php

namespace App\Models\Masterdata;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PartnerMCU extends Model
{
    protected $table = 'users_perusahaan';
    protected $fillable = [
        'id',
        'json_perusahaan',
    ];
    public static function listPartnerMCU($req, $perHalaman, $offset)
    {
        $parameterpencarian = $req->parameter_pencarian;
        $id_peserta = $req->id_peserta;
        $tablePrefix = config('database.connections.mysql.prefix');
        $query = PartnerMCU::join('users', 'users.id', '=', 'users_perusahaan.id')
            ->select('users_perusahaan.*','users.*');
        if (!empty($parameterpencarian)) {
            $query->where('users.username', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('users.email', 'LIKE', '%' . $parameterpencarian . '%');
        }        
        $jumlahdata = $query->count();
        $result = $query->take($perHalaman)
            ->skip($offset)
            ->orderBy('users.username', 'ASC')
            ->get();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
}
