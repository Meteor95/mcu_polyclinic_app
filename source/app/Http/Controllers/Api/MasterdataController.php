<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perusahaan;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Validator;

class MasterdataController extends Controller
{
    /* Master Data Perusahaan */
    public function getperusahaan(Request $request)
    {
        try {
            $perHalaman = (int) $request->length > 0 ? (int) $request->length : 1;
            $nomorHalaman = (int) $request->start / $perHalaman;
            $offset = $nomorHalaman * $perHalaman; 
            $datatabel = Perusahaan::listPerusahaan($request, $perHalaman, $offset);
            $jumlahdata = $datatabel['total'];
            $dynamicAttributes = [
                'data' => $datatabel['data'],
                'recordsFiltered' => $jumlahdata,
                'pages' => [
                    'limit' => $perHalaman,
                    'offset' => $offset,
                ],
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Perusahaan']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function saveperusahaan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_code' => 'required|string',
            'company_name' => 'required|string',
            'alamat' => 'required|string',
            'keterangan' => 'required|string',
        ]);
        if ($validator->fails()) {
            $dynamicAttributes = ['errors' => $validator->errors()];
            return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
        }
        try {
            Perusahaan::create(
                [
                    'company_code' => $request->company_code,
                    'company_name' => $request->company_name,
                    'alamat' => $request->alamat,
                    'keterangan' => $request->keterangan,
                ]
            );
            $dynamicAttributes = [  
                'message' => 'Informasi Perusahaan berhasil disimpan',
            ];
            return ResponseHelper::data(
                __('common.data_saved', ['namadata' => 'Informasi perusahaan dengan nama ' . $request->company_name . ' berhasil disimpan. Silahkan']), 
                $dynamicAttributes
            );
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function deleteperusahaan(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            Perusahaan::where('id', $request->id)->delete();
            return ResponseHelper::success_delete("Informasi Perusahaan dengan nama " . $request->nama . " berhasil dihapus beserta seluruh data yang terkait dengan perusahaan ini.");
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function detailperusahaan(Request $request)
    {
        try {
            $perusahaan = Perusahaan::where('id', $request->id)->first();
            $dynamicAttributes = [
                'data' => $perusahaan,
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Perusahaan']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function editperusahaan(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'company_code' => 'required|string',
                'company_name' => 'required|string',
                'alamat' => 'required|string',
                'keterangan' => 'required|string',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            Perusahaan::where('id', $request->id)->update([
                'company_code' => $request->company_code,
                'company_name' => $request->company_name,
                'alamat' => $request->alamat,
                'keterangan' => $request->keterangan,
            ]);
            return ResponseHelper::success("Informasi Perusahaan dengan nama " . $request->company_name . " berhasil diubah.");
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    /* Master Data Paket MCU */
    public function getpaketmcu(Request $request)
    {
        
    }
}
