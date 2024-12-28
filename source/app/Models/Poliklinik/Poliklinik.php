<?php

namespace App\Models\Poliklinik;

use Illuminate\Database\Eloquent\Model;

class Poliklinik extends Model
{
    protected $table;
    protected $fillable = [
        'user_id',
        'transaksi_id',
        'judul_laporan',
        'kesimpulan',
        'detail_kesimpulan',
        'catatan_kaki'
    ];
    public function setTableName($table)
    {
        $this->table = $table;
    }
}
