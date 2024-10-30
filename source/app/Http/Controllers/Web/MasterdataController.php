<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MasterdataController extends Controller
{
    private function getData($title, $breadcrumb) {
        return [
            'title' => $title,
            'breadcrumb' => $breadcrumb,
        ];
    }
    function daftar_perusahaan(){
        $data = $this->getData('Daftar Perusahaan', [
            'Perusahaan' => route('admin.masterdata.daftar_perusahaan'),
        ]);
        return view('paneladmin.masterdata.daftarperusahaan', ['data' => $data]);
    }
    function daftar_paket_mcu(){
        $data = $this->getData('Daftar Paket MCU', [
            'Paket MCU' => route('admin.masterdata.daftar_paket_mcu'),
        ]);
        return view('paneladmin.masterdata.daftarpaketmcu', ['data' => $data]);
    }
}
