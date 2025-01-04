<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laboratorium\{Kategori, JasaPetugas, Satuan, Kenormalan};

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
        $data['tarif_laboratorium'] = JasaPetugas::all();
        $data['kenormalan'] = Kenormalan::all();
        return view('paneladmin.laboratorium.daftar_tarif', ['data' => $data]);
    }
}
