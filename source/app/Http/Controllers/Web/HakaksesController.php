<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HakaksesController extends Controller
{
    private function getData($title, $tipe_halaman, $menu_aktif, $sub_menu_aktif, $breadcrumb) {
        return [
            'title' => $title,
            'tipe_halaman' => $tipe_halaman,
            'menu_aktif' => $menu_aktif,
            'sub_menu_aktif' => $sub_menu_aktif,
            'breadcrumb' => $breadcrumb,
        ];
    }
    public function pengguna_aplikasi(Request $req){
        $data = $this->getData('Daftar Pengguna Aplikasi', 'admin', 'petugas', 'pengguna_aplikasi', [
            'Pengguna Aplikasi' => route('admin.pengguna_aplikasi'),
        ]);
        return view('paneladmin.pengaturan.pengguna.daftar_penggunaaplikasi', ['data' => $data]);
    }
    public function permission(Request $req){
        $data = $this->getData('Daftar Hak Akses', 'admin', 'petugas', 'daftar_hakakses', [
            'Role' => route('admin.role'),
            'Permission' => route('admin.permission'),
        ]);
        return view('paneladmin.pengaturan.roledanhakakses.daftar_permission', ['data' => $data]);
    }
    public function role(Request $req){
        $data = $this->getData('Daftar Role', 'admin', 'petugas', 'daftar_hakakses', [
            'Role' => route('admin.role'),
            'Permission' => route('admin.permission'),
        ]);
        return view('paneladmin.pengaturan.roledanhakakses.daftar_role', ['data' => $data]);
    }
}
