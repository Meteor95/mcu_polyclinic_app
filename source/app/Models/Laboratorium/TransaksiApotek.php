<?php

namespace App\Models\Laboratorium;

use Illuminate\Database\Eloquent\Model;

class TransaksiApotek extends Model
{
    protected $table = 'transaksi_apotek';
    protected $fillable = [
        'id_transaksi',
        'tgl_transaksi_input',
        'nama_file',
        'total_harga_nota',
        'data_foto',
        'ekstensi',
        'keterangan',
    ];
}
