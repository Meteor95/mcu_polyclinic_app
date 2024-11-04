<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\Pendaftaran\Peserta;
use App\Models\Masterdata\MemberMCU;
use App\Services\RegistrationMCUServices;
use Illuminate\Support\Facades\Validator;
class PendaftaranController extends Controller
{
    public function getpeserta(Request $request)
    {
        try {
            $perHalaman = (int) $request->length > 0 ? (int) $request->length : 1;
            $nomorHalaman = (int) $request->start / $perHalaman;
            $offset = $nomorHalaman * $perHalaman;
            $data = Peserta::listPesertaTabel($request, $perHalaman, $offset);
            $jumlahdata = $data['total'];
            $dynamicAttributes = [
                'data' => $data['data'],
                'recordsFiltered' => $jumlahdata,
                'pages' => [
                    'limit' => $perHalaman,
                    'offset' => $offset,
                ],
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Peserta']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function deletepeserta(RegistrationMCUServices $registrationMCUServices, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $data = $request->all();
            $registrationMCUServices->handleTransactionDeletePeserta($data);
            return ResponseHelper::success_delete("Informasi Peserta berhasil dihapus beserta paramter lainnya");
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function getdatapeserta(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nomor_identitas' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $data = MemberMCU::where('nomor_identitas', $request->nomor_identitas)->first();
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
}
