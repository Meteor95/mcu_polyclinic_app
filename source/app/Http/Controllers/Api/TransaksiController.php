<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TransaksiServices;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper;
use App\Models\Transaksi\Transaksi;

class TransaksiController extends Controller
{
    public function savepeserta(TransaksiServices $transaksiService, Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'nomor_identitas' => 'required',
                'tanggal_transaksi' => 'required|date',
                'perusahaan_id' => 'required',
                'departemen_id' => 'required',
                'id_paket_mcu' => 'required|integer',
                'proses_kerja' => 'required',
                'nominal_bayar_konfirmasi' => 'required_if:tipe_pembayaran,1|numeric',
                'tipe_pembayaran' => 'required',
                'metode_pembayaran' => 'required',
                'nominal_pembayaran' => 'required|numeric',
                'penerima_bank' => 'required_if:metode_pembayaran,1',
                'nomor_transaksi_transfer' => 'required_if:metode_pembayaran,1'
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $data = $request->all();
            $file = $request->file('nama_file_surat_pengantar');
            $iserrir = $transaksiService->handleTransactionPeserta($data,$request->attributes->get('user_id'),$file);
            return ResponseHelper::success('Pengguna ' . $request->input('nama_pegawai') . ' berhasil didaftarkan kedalam sistem MCU Artha Medica. Silahkan tambah informasi detail MCU berdasarkan Nomor Indetitas yang sudah didaftarkan [' . $request->input('nomor_identitas') . ']');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function getpasien(Request $request)
    {
        try {
            $perHalaman = (int) $request->length > 0 ? (int) $request->length : 1;
            $nomorHalaman = (int) $request->start / $perHalaman;
            $offset = $nomorHalaman * $perHalaman;
            $data = Transaksi::listPasienTabel($request, $perHalaman, $offset);
            $jumlahdata = $data['total'];
            $dynamicAttributes = [
                'data' => $data['data'],
                'recordsFiltered' => $jumlahdata,
                'pages' => [
                    'limit' => $perHalaman,
                    'offset' => $offset,
                ],
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi pasien MCU yang tersedia']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function deletepeserta(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'id_transaksi' => 'required',
                'no_transaksi' => 'required',
                'nama_peserta' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            Transaksi::where('id', $request->id_transaksi)->delete();
            return ResponseHelper::success('Informasi pasien MCU dengan Nama ' . $request->nama_peserta . ' dengan Nomor Transaksi ' . $request->no_transaksi . ' berhasil dihapus');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
}
