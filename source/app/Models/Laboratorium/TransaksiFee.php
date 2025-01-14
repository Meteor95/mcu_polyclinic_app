<?php

namespace App\Models\Laboratorium;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiFee extends Model
{
    use SoftDeletes;
    protected $table = 'transaksi_fee';
    protected $fillable = [
        'kode_jasa',
        'id_transaksi',
        'id_petugas',
        'nama_petugas',
        'kode_item',
        'nama_tindakan',
        'nominal_fee',
        'besaran_fee',
    ];  
}

