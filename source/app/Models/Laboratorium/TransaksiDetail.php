<?php

namespace App\Models\Laboratorium;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiDetail extends Model
{
    use SoftDeletes;
    protected $table = 'transaksi_detail';
    protected $fillable = [
        'id_transaksi',
        'id_item',
        'kode_item',
        'nana_item',
        'harga',
        'diskon',
        'harga_setelah_diskon',
        'jumlah',
        'keterangan',
        'meta_data_kuantitatif',
        'meta_data_kualitatif',
        'meta_data_jasa',
        'meta_data_jasa_fee',
        'nilai_tindakan'
    ];
}
