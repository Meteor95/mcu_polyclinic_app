<?php

namespace App\Http\Controllers\Web;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Cookie,Hash};


class PendaftaranController extends Controller
{
    public function dashboard(Request $req)
    {
        $data = [
            'tipe_halaman' => 'admin',
            'menu_utama_aktif' => 'dashboard',
        ];
        return view('paneladmin.beranda.beranda_konten', ['data' => $data]);
    }
}
