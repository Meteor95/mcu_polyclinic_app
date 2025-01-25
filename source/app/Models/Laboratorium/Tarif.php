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
        'harga_jual',
        'visible_item'
    ];
    public static function listTarifTabel($req, $perHalaman, $offset)
    {
        $parameterpencarian = $req->parameter_pencarian;
        $query = Tarif::join('lab_kategori as B','lab_tarif.id_kategori','=','B.id')
        ->join('lab_satuan_item as C','lab_tarif.satuan','=','C.id')
        ->whereNull('lab_tarif.deleted_at');
        if (!empty($parameterpencarian)) {
            $query->where('lab_tarif.kode_item', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('lab_tarif.nama_item', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('lab_tarif.group_item', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('B.nama_kategori', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('C.nama_satuan', 'LIKE', '%' . $parameterpencarian . '%')
                  ->orWhere('lab_tarif.jenis_item', 'LIKE', '%' . $parameterpencarian . '%');
        }
        if (!empty($req->jenis_item_tampilkan)) {
            $query->where('lab_tarif.group_item', '=', $req->jenis_item_tampilkan);
        }
        $jumlahdata = $query->count();
        if ($perHalaman > 0) { 
            $result = $query->take($perHalaman)
                ->skip($offset)
                ->orderBy('lab_tarif.nama_item', 'ASC')
                ->get();
        } else {
            $result = $query->orderBy('lab_tarif.nama_item', 'ASC')
                ->get();
        }
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
}
