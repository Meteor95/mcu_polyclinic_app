<?php

namespace App\Models\Masterdata;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MemberMCU extends Model
{
    protected $table = 'users_member';
    protected $fillable = [
        'nomor_identitas',
        'nama_peserta',
        'tempat_lahir',
        'tanggal_lahir',
        'tipe_identitas',
        'jenis_kelamin',
        'alamat',
        'status_kawin',
        'no_telepon',
    ];
    public static function listMemberMcu($req, $perHalaman, $offset)
    {
        $parameterpencarian = $req->parameter_pencarian;
        $tablePrefix = config('database.connections.mysql.prefix');
        $query = DB::table((new self())->getTable())
            ->select('users_member.*')
            ->selectRaw('TIMESTAMPDIFF(YEAR, ' . $tablePrefix . 'users_member.tanggal_lahir, CURDATE()) AS umur, DATE_FORMAT(created_at, "%d-%m-%Y %H:%i:%s") as created_at ');
        if (!empty($parameterpencarian)) {
            $query->where('nomor_identitas', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('nama_peserta', 'LIKE', '%' . $parameterpencarian . '%');
        }
        $jumlahdata = $query->count();
        $result = $query->take($perHalaman)
            ->skip($offset)
            ->orderBy('nama_peserta', 'ASC')
            ->get();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
}
