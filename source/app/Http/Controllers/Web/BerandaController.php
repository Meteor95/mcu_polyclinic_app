<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    private function getData($req, $title, $breadcrumb) {
        return [
            'title' => $title,
            'breadcrumb' => $breadcrumb,
            'user_details' => $req->attributes->get('user_details'),
        ];
    }
    public function index(Request $request)
    {
        $data = $this->getData($request, 'Beranda MCU Arta Medica', [
            'Dashboard' => route('admin.beranda'),
        ]);
        return view('paneladmin.beranda.main_konten', ['data' => $data]);
    }
}
