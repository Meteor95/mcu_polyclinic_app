<?php

namespace App\Models\Laboratorium;

use Illuminate\Database\Eloquent\Model;

class Kenormalan extends Model
{
    protected $table = 'lab_nilai_rentang_kenormalan';
    protected $fillable = [
        'nama_rentang_kenormalan',
        'jenis_kelamin',
    ];
}
