<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HakaksesController extends Controller
{
    private function getData($title, $tipe_halaman, $menu_utama_aktif, $menu_aktif, $sub_menu_aktif, $breadcrumb) {
        return [
            'title' => $title,
            'tipe_halaman' => $tipe_halaman,
            'menu_utama_aktif' => $menu_utama_aktif,
            'menu_aktif' => $menu_aktif,
            'sub_menu_aktif' => $sub_menu_aktif,
            'breadcrumb' => $breadcrumb,
        ];
    }
    public function permission(Request $req){
        $data = $this->getData('Daftar Hak Akses', 'admin', 'dashboard', 'petugas', 'daftar_hakakses', [
            'Role' => route('admin.role'),
            'Permission' => route('admin.permission'),
        ]);
        return view('paneladmin.pengaturan.roledanhakakses.daftar_permission', ['data' => $data]);
    }
    public function role(Request $req){
        $data = $this->getData('Daftar Role', 'admin', 'dashboard', 'petugas', 'daftar_hakakses', [
            'Role' => route('admin.role'),
            'Permission' => route('admin.permission'),
        ]);
        return view('paneladmin.pengaturan.roledanhakakses.daftar_role', ['data' => $data]);
    }
}
