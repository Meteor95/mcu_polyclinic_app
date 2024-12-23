<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaboratoriumController extends Controller
{
    private function getData($req, $title, $breadcrumb) {
        return [
            'title' => $title,
            'breadcrumb' => $breadcrumb,
            'user_details' => $req->attributes->get('user_details'),
        ];
    }
    public function kategori(Request $req){
        $data = $this->getData($req, 'Daftar Kategori', [
            'Kategori' => route('admin.laboratorium.kategori'),
        ]);
        return view('paneladmin.laboratorium.kategori', ['data' => $data]);
    }
    public function satuan(Request $req){
        $data = $this->getData($req, 'Daftar Satuan', [
            'Satuan' => route('admin.laboratorium.satuan'),
        ]);
        return view('paneladmin.laboratorium.satuan', ['data' => $data]);
    }
    public function tindakan(Request $req){
        $data = $this->getData($req, 'Daftar Pemeriksaan Lab', [
            'Pemeriksaan Lab' => route('admin.laboratorium.tindakan'),
        ]);
        return view('paneladmin.laboratorium.tindakan', ['data' => $data]);
    }
    public function template(Request $req){
        $data = $this->getData($req, 'Daftar Template', [
            'Template' => route('admin.laboratorium.template'),
        ]);
        return view('paneladmin.laboratorium.template', ['data' => $data]);
    }
    public function transaksi(Request $req){
        $data = $this->getData($req, 'Daftar Transaksi', [
            'Transaksi' => route('admin.laboratorium.transaksi'),
        ]);
        return view('paneladmin.laboratorium.transaksi', ['data' => $data]);
    }
}
