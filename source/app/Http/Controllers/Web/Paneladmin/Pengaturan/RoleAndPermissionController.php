<?php

namespace App\Http\Controllers\Web\Paneladmin\Pengaturan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Helpers\ResponseHelper;

class RoleAndPermissionController extends Controller
{
    public function createRole(Request $request){
        $data = [
            'tipe_halaman' => 'admin',
            'menu_utama_aktif' => 'dashboard',
        ];
        return view('paneladmin.pengaturan.roledanhakakses.daftar_hakakses', ['data' => $data]);
    }
}
