<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Komponen\LingkunganKerja;
use App\Helpers\ResponseHelper;

class AtributController extends Controller
{
    public function getlingkungankerja(Request $req){
        try {
            $lingkungankerja = LingkunganKerja::where('status', 1)->get();
            $dynamicAttributes = [  
                'data' => $lingkungankerja,
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Daftar Atribut Lingkungan Kerja Peserta MCU']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
}
