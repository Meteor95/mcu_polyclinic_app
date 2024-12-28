<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PoliklinikServices;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Log;

class PoliklinikController extends Controller
{
    public function simpan_poliklinik(PoliklinikServices $poliklinikServices, Request $req, $jenis_poli)
    {
        try {
            /*$validator = $req->validate([
                'user_id' => 'required',
                'transaksi_id' => 'required',
                'judul_laporan' => 'required',
                'kesimpulan' => 'required',
                'citra_unggahan_poliklinik' => 'required|array',
                'citra_unggahan_poliklinik.*' => 'file|mimes:png,jpg,jpeg|max:2048',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }*/
            $data = $req->all();
            $poliklinikServices->handleTransactionPoliklinik($data, $req->file('citra_unggahan_poliklinik'),$jenis_poli);
            $dynamicAttributes = [
                'data' => $data,
            ];
            return ResponseHelper::data('Informasi unggahan Poliklinik '.ucfirst($jenis_poli).' pada transaksi '.$data['transaksi_id'].' berhasil disimpan', $dynamicAttributes);
        } catch (\Throwable $th) {
            Log::error($th);
            return ResponseHelper::error($th);
        }
    }
}
