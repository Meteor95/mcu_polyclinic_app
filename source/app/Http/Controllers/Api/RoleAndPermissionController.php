<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RouteAndPermission;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class RoleAndPermissionController extends Controller
{
    function addpermission(Request $req)
    {
        try {
            $nama_hakakses = $req->input('nama_hakakses');
            $keterangan = $req->input('keterangan');
            $group = $req->input('namagroup');
            RouteAndPermission::create([
                'name' => $nama_hakakses,
                'guard_name' => 'admin',
                'group' => $group,
                'description' => $keterangan,
                'urutan' => 0
            ]);
            return ResponseHelper::success('Hak akses '.$nama_hakakses.' dengan keterangan '.$keterangan.' berhasil ditambahkan.');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function getpermission(Request $req)
    {
        try {
            $perHalaman = (int) $req->length > 0 ? (int) $req->length : 1;
            $nomorHalaman = (int) $req->start / $perHalaman;
            $offset = $nomorHalaman * $perHalaman; 
            $datatabel = RouteAndPermission::listPermissionTabel($req, $perHalaman, $offset);
            $jumlahdata = $datatabel['total'];
            $dynamicAttributes = [
                'data' => $datatabel['data'],
                'recordsFiltered' => $jumlahdata,
                'pages' => [
                    'limit' => $perHalaman,
                    'offset' => $offset,
                ],
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Hak Akses']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function deletepermission(Request $req){
        try {
            $idHakAkses = $req->idhakakses;
            $namaHakAkses = $req->namahakakses;
            $permission = RouteAndPermission::find($idHakAkses);
            Log::info($permission);
            if (!$permission) {
                return ResponseHelper::data_not_found(__('common.data_not_found', ['namadata' => 'Hak Akses']));
            }
            $permission->delete();
            return ResponseHelper::success(__('common.data_deleted', ['namadata' => 'Hak Akses ' . $namaHakAkses]));
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function editpermission(Request $req){
        try {
            $idHakAkses = (int)$req->input('idhakakses');
            $namaHakAkses = $req->input('namahakakses');
            $keterangan = $req->input('keteranganhakakses');
            $permission = RouteAndPermission::find($idHakAkses);
            $permission->update([
                'name' => $namaHakAkses,
                'description' => $keterangan
            ]);
            return ResponseHelper::success('Hak akses '.$namaHakAkses.' dengan keterangan '.$keterangan.' berhasil diubah.');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function addrole(Request $req){
        try {
            $nama_role = $req->input('name');
            $keterangan_role = $req->input('description');
            $guard_name = $req->input('guard_name');
            $permissions = $req->input('permissions');
            $role = Role::create([
                'name' => $nama_role,
                'description' => $keterangan_role,
                'guard_name' => $guard_name 
            ]);
            if (!empty($permissions)) {
                $role->givePermissionTo($permissions);
            }
            return ResponseHelper::success('Role ' . $nama_role . ' berhasil dibuat.');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function getrole(Request $req){
        try {
            $perHalaman = (int) $req->length > 0 ? (int) $req->length : 1;
            $nomorHalaman = (int) $req->start / $perHalaman;
            $offset = $nomorHalaman * $perHalaman; 
            $datatabel = RouteAndPermission::listRoleTabel($req, $perHalaman, $offset);
            $jumlahdata = $datatabel['total'];
            $dynamicAttributes = [  
                'data' => $datatabel['data'],
                'recordsFiltered' => $jumlahdata,
                'pages' => [
                    'limit' => $perHalaman,
                    'offset' => $offset,
                ],
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Role']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function deleterole(Request $req){
        try {
            $idRole = $req->idrole;
            $namaRole = $req->namarole;
            $role = Role::where('id', $idRole)->delete();
            return ResponseHelper::success(__('common.data_deleted', ['namadata' => 'Role ' . $namaRole]));
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function detailrole(Request $req){
        try {
            $idRole = $req->idrole;
            $role = Role::with('permissions:id,name')->find($idRole);
            $dynamicAttributes = [  
                'data' => $role,
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Role']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    function editrole(Request $req){
        try {
            $idRole = $req->input('idrole');
            $nama_role = $req->input('name');
            $keterangan_role = $req->input('description');
            $guard_name = $req->input('guard_name');
            $permissions = $req->input('permissions');
            $role = Role::find($idRole);
            if (!$role) {
                throw new \Exception("Role not found");
            }
            $role->update([
                'name' => $nama_role,
                'description' => $keterangan_role,
                'guard_name' => $guard_name,
            ]);
            if (!empty($permissions)) {
                $role->syncPermissions($permissions);
            }
        
            return ResponseHelper::success('Role ' . $nama_role . ' berhasil diubah.');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }        
    }
}
