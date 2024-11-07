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
            $dynamicAttributes = [  
                'data' => $data,
                'message_info' => "Peserta dengan Nama : <h4><strong>".(isset($data->nama_peserta) ? $data->nama_peserta : '-')."</strong></h4> telah terdaftar pada sistem MCU. Apakah anda ingin menggunakan data ini untuk melakukan transaksi dan pendaftaran peserta MCU ?",
            ];
            if ($data) {
                return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Peserta']), $dynamicAttributes);
            } else {
                $data = Peserta::where('nomor_identitas', $request->nomor_identitas)->first();
                $dynamicAttributes = [  
                    'data' => $data,
                    'message_info' => '<h4>Informasi Peserta dengan Nama : <strong>'.$data->nama_peserta.'</strong></h4><span style="color:red">BELUM TERDAFTAR PADA SISTEM MCU</span>. Informasi member ini akan ditambahkan menjadi member di Artha Medica Clinic secara otomatis jika selesai melakukan transaksi MCU',
                ];
                if ($data) {
                    return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Peserta Temporari']), $dynamicAttributes);
                } else {
                    return ResponseHelper::data_not_found(__('common.data_not_found', ['namadata' => 'Informasi Peserta']));
                }
            }
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
}
