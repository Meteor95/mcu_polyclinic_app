<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PoliklinikController extends Controller
{
    private function getData($req, $title, $breadcrumb) {
        return [
            'title' => $title,
            'breadcrumb' => $breadcrumb,
            'user_details' => $req->attributes->get('user_details'),
        ];
    }
    public function spirometri(Request $req)
    {
        $data = $this->getData($req, 'Poliklinik Spirometri', [
            'Spirometri' => route('admin.poliklinik.spirometri'),
        ]);
        return view('paneladmin.pemeriksaan_fisik.poliklinik.spirometri', ['data' => $data]);
    }
}
