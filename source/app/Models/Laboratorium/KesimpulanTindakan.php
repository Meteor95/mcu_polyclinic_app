<?php

namespace App\Models\Laboratorium;

use Illuminate\Database\Eloquent\Model;

class KesimpulanTindakan extends Model
{
    protected $table = 'lab_kesimpulan_tindakan';
    protected $fillable = [
        'jenis_kesimpulan', 
        'keterangan_kesimpulan', 
    ];
}
