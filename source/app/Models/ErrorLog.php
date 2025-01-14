<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    protected $table = 'log_error';
    protected $fillable = [
        'jenis_error',
        'log_error',
    ];
}
