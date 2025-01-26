<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
    function validasi_mcu_nota(Request $req, $no_nota){
        $data = $this->getData($req, 'Validasi Tindakan Pasien', [
            'Daftar Validasi Tindakan' => route('admin.laporan.validasi_mcu'),
            'Validasi Tindakan Pasien' => route('admin.laporan.validasi_mcu_nota', ['no_nota' => urlencode($no_nota)]),
        ]);
        $data['no_nota'] = $no_nota;
        return view('paneladmin.laporan.validasi_mcu_nota', ['data' => $data]);
    }
    public function validasi_rekap_kesimpulan(Request $req){
        $data = $this->getData($req, 'Validasi Laporan Tindakan MCU atau Pengobatan Pasien', [
            'Beranda' => route('admin.beranda'),
            'Validasi' => route('admin.laporan.validasi_rekap_kesimpulan'),
        ]);
        return view('paneladmin.laporan.validasi_rekap_kesimpulan', ['data' => $data]);
    }
    public function berkas_mcu(Request $req){
        $data = $this->getData($req, 'Berkas Tindakan MCU', [
            'Beranda' => route('admin.beranda'),
            'Berkas' => route('admin.laporan.berkas_mcu'),
        ]);
        return view('paneladmin.laporan.berkas.berkas_mcu', ['data' => $data]);
    }
}

