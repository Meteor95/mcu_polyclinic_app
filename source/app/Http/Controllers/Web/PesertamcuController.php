<?php

namespace App\Http\Controllers\Web;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Cookie,Hash};


class PesertamcuController extends Controller
{
    public function register_mcu(Request $req)
    {
        $data = [
            'tipe_halaman' => 'register_mcu',
            'menu_utama_aktif' => 'no_menu',
        ];
        return view('preregister.landingpage_form', ['data' => $data]);
    }
}
