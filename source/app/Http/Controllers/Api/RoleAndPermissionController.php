<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Helpers\ResponseHelper;

class RoleAndPermissionController extends Controller
{
    public function addpermission(Request $request){
        $permission = Permission::create(['name' => $request->input('permission_name')]);
        $dynamicAttributes = [];
        return ResponseHelper::success(__('permission.eds_permission_name_added', ['permission_name' => $request->input('permission_name')]), $dynamicAttributes);
    }
    public function createRole(Request $request){
        
    }
}
