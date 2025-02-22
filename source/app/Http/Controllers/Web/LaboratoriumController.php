<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laboratorium\{Kategori, Satuan, Kenormalan};
use App\Models\Masterdata\{Jasalayanan, DaftarBank};


class LaboratoriumController extends Controller
{
    private function getData($req, $title, $breadcrumb) {
        return [
            'title' => $title,
            'breadcrumb' => $breadcrumb,
            'user_details' => $req->attributes->get('user_details'),
        ];
    }
    public function tarif(Request $req){
        $data = $this->getData($req, 'Daftar Tarif Laboratorium dan Pengobatan', [
            'Tarif' => route('admin.laboratorium.tarif'),
        ]);
        $data['kenormalan'] = Kenormalan::all();
        return view('paneladmin.laboratorium.daftar_tarif', ['data' => $data]);
    }
    public function kategori(Request $req){
        $data = $this->getData($req, 'Daftar Kategori Laboratorium', [
            'Kategori' => route('admin.laboratorium.kategori'),
        ]);
        return view('paneladmin.laboratorium.daftar_kategori', ['data' => $data]);
    }
    public function satuan(Request $req){
        $data = $this->getData($req, 'Daftar Satuan Laboratorium', [
            'Satuan' => route('admin.laboratorium.satuan'),
        ]);
        return view('paneladmin.laboratorium.daftar_satuan', ['data' => $data]);
    }
    public function rentang_kenormalan(Request $req){
        $data = $this->getData($req, 'Daftar Nilai Rentang Kenormalan', [
            'Nilai Rentang Kenormalan' => route('admin.laboratorium.rentang_kenormalan'),
        ]);
        return view('paneladmin.laboratorium.nilai_rentang_kenormalan', ['data' => $data]);
    }
    public function templating(Request $req){
        $data = $this->getData($req, 'Daftar Templat Laboratorium', [
            'Templat' => route('admin.laboratorium.templating'),
        ]);
        return view('paneladmin.laboratorium.templating', ['data' => $data]);
    }
    public function daftar_tindakan(Request $req){
        $data = $this->getData($req, 'Transaksi Tindakan Lab & Pengobatan', [
            'Beranda' => route('admin.beranda'),
            'Tindakan' => route('admin.laboratorium.daftar_tindakan'),
        ]);
        return view('paneladmin.laboratorium.daftar_tindakan', ['data' => $data]);
    }
    public function tindakan(Request $req){
        $data = $this->getData($req, 'Daftar Tindakan Lab & Pengobatan', [
            'Beranda' => route('admin.beranda'),
            'Tindakan' => route('admin.laboratorium.tindakan'),
        ]);
        $data['bank'] = DaftarBank::all();
        return view('paneladmin.laboratorium.transaksi_tindakan', ['data' => $data]);
    }
}
