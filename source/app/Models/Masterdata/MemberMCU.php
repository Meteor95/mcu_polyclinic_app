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
        if ($req->tipe == 1) {
            $query = MemberMCU::query();
        } else {
            $query = MemberMCU::join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.user_id', '=', 'users_member.id')
                ->select('users_member.*')
                ->selectRaw('
                    TIMESTAMPDIFF(YEAR, ' . $tablePrefix . 'users_member.tanggal_lahir, CURDATE()) AS umur,
                    DATE_FORMAT(' . $tablePrefix . 'users_member.created_at, "%d-%m-%Y %H:%i:%s") AS created_at
                ');
        }
        
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
