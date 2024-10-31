<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MasterdataController extends Controller
{
    private function getData($req, $title, $breadcrumb) {
        return [
            'title' => $title,
            'breadcrumb' => $breadcrumb,
            'user_details' => $req->attributes->get('user_details'),
        ];
    }
    function daftar_perusahaan(Request $req){
        $data = $this->getData($req, 'Daftar Perusahaan', [
            'Perusahaan' => route('admin.masterdata.daftar_perusahaan'),
        ]);
        return view('paneladmin.masterdata.daftarperusahaan', ['data' => $data]);
    }
    function daftar_paket_mcu(Request $req){
        $data = $this->getData($req, 'Daftar Paket MCU', [
            'Paket MCU' => route('admin.masterdata.daftar_paket_mcu'),
        ]);
        return view('paneladmin.masterdata.daftarpaketmcu', ['data' => $data]);
    }
    function daftar_jasa_pelayanan(Request $req){
        $data = $this->getData($req, 'Daftar Jasa Pelayanan', [
            'Jasa Pelayanan' => route('admin.masterdata.daftar_jasa_pelayanan'),
        ]);
        return view('paneladmin.masterdata.daftarjasapelayanan', ['data' => $data]);
    }
    function daftar_departemen_peserta(Request $req){
        $data = $this->getData($req, 'Daftar Departemen Peserta', [
            'Departemen Peserta' => route('admin.masterdata.daftar_departemen_peserta'),
        ]);
        return view('paneladmin.masterdata.daftardepartemenpeserta', ['data' => $data]);
    }
}
