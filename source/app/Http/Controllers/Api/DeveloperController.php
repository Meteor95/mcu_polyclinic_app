<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\ErrorLogApp;

class DeveloperController extends Controller
{
    public function error_log_app(Request $request)
    {
        try {   
            $perHalaman = (int) $request->length > 0 ? (int) $request->length : 1;
            $nomorHalaman = (int) $request->start / $perHalaman;
            $offset = $nomorHalaman * $perHalaman; 
            $datatabel = ErrorLogApp::errorLogApp($request, $perHalaman, $offset);
            $jumlahdata = $datatabel['total'];
            $dynamicAttributes = [
                'data' => $datatabel['data'],
                'recordsFiltered' => $jumlahdata,
                'pages' => [
                    'limit' => $perHalaman,
                    'offset' => $offset,
                ],
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Pengguna Aplikasi MCU Artha Medica']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function hapus_error_log_app($id)
    {
        try {
            ErrorLogApp::where('id', $id)->delete();
            return ResponseHelper::success("Informasi Error Log berhasil dihapus dari sistem yang berarti harusnya sudah FIX ya BUG ini wahai programmer");
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
}
