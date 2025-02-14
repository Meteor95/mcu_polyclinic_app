<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Masterdata\DaftarBank;

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
    public function kasir(Request $request)
    {
        $data = $this->getData($request, 'Kasir MCU Arta Medica', [
            'Kasir' => route('admin.kasir'),
        ]);
        $data['bank'] = DaftarBank::all();
        return view('paneladmin.beranda.kasir', ['data' => $data]);
    }
    public function antrian(Request $request)
    {
        $data = $this->getData($request, 'Antrian Pasien Di Ruangan', [
            'Antrian Pasien' => route('admin.antrian'),
        ]);
        return view('paneladmin.beranda.antrian', ['data' => $data]);
    }
}
