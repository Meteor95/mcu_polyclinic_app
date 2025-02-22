<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ErrorLogApp extends Model
{
    protected $table = "log_error_app";

    protected $fillable = [
        'level',
        'messages',
        'context',
    ];

    public static function errorLogApp($request, $perHalaman, $offset){
        $parameterpencarian = $request->parameter_pencarian;
        $query = DB::table((new self())->getTable());
        $jumlahdata = $query->count();
        $result = $query->take($perHalaman)
            ->skip($offset)
            ->orderBy('created_at', 'DESC')
            ->get();
        return [
            'data' => $result,
            'total' => $jumlahdata
        ];
    }
}