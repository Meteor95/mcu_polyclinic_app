<?php

namespace App\Models\Laboratorium;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $table = 'lab_satuan_item';
    protected $fillable = ['nama_satuan', 'grup_satuan'];
}
