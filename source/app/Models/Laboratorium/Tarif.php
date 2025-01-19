<?php

namespace App\Models\Laboratorium;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Tarif extends Model
{
    use SoftDeletes;
    protected $table = 'lab_tarif';
    protected $fillable = [
        'kode_item',
        'nama_item',
        'group_item',
        'id_kategori',
        'nama_kategori',
        'satuan',
        'jenis_item',
        'meta_data_kuantitatif',
        'meta_data_kualitatif',
        'harga_dasar',
        'meta_data_jasa',
        'harga_jual'
    ];
    public static function listTarifTabel($req, $perHalaman, $offset)
    {
        $tableini = (new self())->getTable();
        $parameterpencarian = $req->parameter_pencarian;
        $query = DB::table($tableini.' as A')
        ->join('lab_kategori as B','A.id_kategori','=','B.id')
        ->join('lab_satuan_item as C','A.satuan','=','C.id')
        ->whereNull('A.deleted_at');
        if (!empty($parameterpencarian)) {
            $query->where('A.kode_item', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('A.nama_item', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('A.group_item', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('B.nama_kategori', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('C.nama_satuan', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('A.jenis_item', 'LIKE', '%' . $parameterpencarian . '%');
        }
        if (!empty($req->jenis_item_tampilkan)) {
            $query->where('A.group_item', '=', $req->jenis_item_tampilkan);
        }
        $jumlahdata = $query->count();
        if ($perHalaman > 0) { 
            $result = $query->take($perHalaman)
                ->skip($offset)
                ->orderBy('A.nama_item', 'ASC')
                ->get();
        } else {
            $result = $query->orderBy('A.nama_item', 'ASC')
                ->get();
        }
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
}
