<?php

namespace App\Models\Masterdata;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Kesimpulan extends Model
{
    protected $table = 'lab_kesimpulan_tindakan';
    protected $fillable = ['jenis_kesimpulan', 'keterangan_kesimpulan'];

    public static function listKesimpulan($req, $perHalaman, $offset)
    {
        $parameterpencarian = $req->parameter_pencarian;
        $query = DB::table((new self())->getTable());
        if (!empty($parameterpencarian)) {
            $query->where('jenis_kesimpulan', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('keterangan_kesimpulan', 'LIKE', '%' . $parameterpencarian . '%');
        }
        $result = $query->take($perHalaman)
            ->skip($offset)
            ->orderBy('jenis_kesimpulan', 'ASC')
            ->get();
        $jumlahdata = $query->count();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
}
