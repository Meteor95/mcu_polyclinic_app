<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    private function getData($req, $title, $breadcrumb) {
        return [
            'title' => $title,
            'breadcrumb' => $breadcrumb,
            'user_details' => $req->attributes->get('user_details'),
        ];
    }
    /* Area Landing Formulir Pendaftaran */
    public function formulir_pendaftaran(Request $req){
        $data = [
            'tipe_halaman' => 'landing',
            'menu_utama_aktif' => 'formulir_pendaftaran',
        ];
        return view('preregister.landingpage_form', ['data' => $data]);
    }
    /* Area Admin Pendaftaran */
    public function list_peserta(Request $req){
        $data = $this->getData($req, 'Daftar Peserta', [
            'Daftar Peserta' => route('admin.pendaftaran.daftar_peserta'),
        ]);
        return view('paneladmin.pendaftaran.daftarpeserta', ['data' => $data]);
    }
}
