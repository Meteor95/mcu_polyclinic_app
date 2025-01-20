<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    private function getData($req, $title, $breadcrumb) {
        return [
            'title' => $title,
            'breadcrumb' => $breadcrumb,
            'user_details' => $req->attributes->get('user_details'),
        ];
    }
    function validasi_mcu(Request $req){
        $data = $this->getData($req, 'Validasi Tindakan Pasien', [
            'Validasi Tindakan Pasien' => route('admin.laporan.validasi_mcu'),
        ]);
        return view('paneladmin.laporan.validasi_mcu', ['data' => $data]);
    }
}
