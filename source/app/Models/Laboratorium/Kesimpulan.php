<?php

namespace App\Models\Laboratorium;

use Illuminate\Database\Eloquent\Model;

class Kesimpulan extends Model
{
    protected $table = 'lab_kesimpulan';
    protected $fillable = [
        'status', 
        'kategori',
        'catatan',
        'hex_color' 
    ];
}
