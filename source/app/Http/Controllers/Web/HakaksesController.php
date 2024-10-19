<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HakaksesController extends Controller
{
    public function index()
    {
        $data = [
            'tipe_halaman' => 'admin',
            'menu_utama_aktif' => 'dashboard',
            'menu_aktif' => 'petugas',
            'sub_menu_aktif' => 'daftar_hakakses',
        ];
        return view('paneladmin.pengaturan.roledanhakakses.daftar_hakakses', ['data' => $data]);
    }
}
