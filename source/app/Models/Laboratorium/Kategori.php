<?php

namespace App\Models\Laboratorium;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Kategori extends Model
{
    protected $table = 'lab_kategori';
    protected $fillable = ['nama_kategori', 'parent_id', 'grup_kategori'];
    public function parent()
    {
        return $this->belongsTo(Kategori::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(Kategori::class, 'parent_id');
    }
    public static function listKategoriTabel($req, $perHalaman, $offset){
        $parameterpencarian = $req->parameter_pencarian;
        $tablePrefix = config('database.connections.mysql.prefix');
        $query = DB::table('lab_kategori')
        ->leftJoin('lab_kategori as parent', 'lab_kategori.parent_id', '=', 'parent.id')
        ->select('lab_kategori.id', 'lab_kategori.nama_kategori', 'lab_kategori.grup_kategori', 'parent.nama_kategori as parent_kategori');
        if (!empty($parameterpencarian)) {
            $query->where('lab_kategori.nama_kategori', 'LIKE', '%' . $parameterpencarian . '%');
        }
        $jumlahdata = $query->count();
        $result = $query->take($perHalaman)
            ->skip($offset)
            ->orderBy('lab_kategori.parent_id', 'ASC')
            ->get();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
}
