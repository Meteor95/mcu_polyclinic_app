<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            'tipe_halaman' => 'admin',
            'menu_utama_aktif' => 'dashboard',
        ];
        return view('paneladmin.beranda.main_konten', ['data' => $data]);
    }
}
