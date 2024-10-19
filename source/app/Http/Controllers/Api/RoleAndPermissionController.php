<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Helpers\ResponseHelper;

class RoleAndPermissionController extends Controller
{
    function addpermission(Request $req)
    {
        $nama_hakakses = $req->input('nama_hakakses');
        $keterangan = $req->input('keterangan');
        Permission::create([
            'name' => $nama_hakakses,
            'guard_name' => 'admin',
            'description' => $keterangan
        ]);
        return ResponseHelper::success('Hak akses '.$nama_hakakses.' dengan keterangan '.$keterangan.' berhasil ditambahkan.');
    }
    function getpermission(Request $req)
    {
        $data = Permission::all();
        return ResponseHelper::success($data);
    }
}
