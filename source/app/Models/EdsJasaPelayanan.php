<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EdsJasaPelayanan extends Model
{
    protected $table = 'jasa_pelayanan';
    protected $primaryKey = 'id';
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_mcu_peserta',
        'jenis_poli',
        'role',
        'pegawai_id',
        'nominal',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
    ];
}
