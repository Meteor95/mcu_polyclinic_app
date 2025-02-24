<?php

namespace App\Http\Controllers\Api\EndUser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\EndUser\Formulir;
use App\Helpers\ResponseHelper;

class FormulirController extends Controller
{
    function kirim_data_permohonan(Request $request, $status_formulir)
    {
        if ($status_formulir == 1) {
            $informasi_formulir = $request->all();
            $formData = json_decode($informasi_formulir['formDataDataDiri'], true); 
            $nomorIdentitas = $formData['nomor_identitas_temp'];
            $namaPeserta = $formData['nama_peserta_temp'];
            $data = [
               'no_pemesanan' => 'AMC' . date('ymdhis'),
               'nomor_identifikasi' => $nomorIdentitas,
               'nama_peserta' => $namaPeserta,
               'json_data_diri' => $informasi_formulir['formDataDataDiri'],
               'json_lingkungan_kerja' => $informasi_formulir['formDataLingkunganKerja'],
               'json_kecelakaan_kerja' => $informasi_formulir['formDataKecelakaanKerja'],
               'json_kebiasaan_hidup' => $informasi_formulir['formDataKebiasaanHidup'],
               'json_penyakit_terdahulu' => $informasi_formulir['formDataPenyakitTerdahulu'],
               'json_penyakit_keluarga' => $informasi_formulir['formDataPenyakitKeluarga'],
               'json_imunisasi' => $informasi_formulir['formDataImunisasi'],
            ];
            try {
                $adaData = Formulir::where('nomor_identifikasi', $nomorIdentitas)->first();
                if ($adaData) {
                    return ResponseHelper::data_conflict('Nomor Identitas : ' . $nomorIdentitas . ' atas nama ' . $namaPeserta . ' sudah melakukan pendaftaran Waktu : ' . $adaData->created_at . ' dengan status belum tervalidasi oleh petugas Clinic');
                }   
                Formulir::create($data); 
                $dataTerakhir = Formulir::latest()->first();
                $dynamicAttributes = [
                    'data' => $dataTerakhir,
                ];
                return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Antrian Pengguna']), $dynamicAttributes);
            }catch (\Throwable $th) {
                return ResponseHelper::error($th);
            }
        }
    }
}
