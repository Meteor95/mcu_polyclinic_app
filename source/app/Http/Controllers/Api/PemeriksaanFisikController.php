<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper;
use App\Models\PemeriksaanFisik\TingkatKesadaran;

class PemeriksaanFisikController extends Controller
{
    public function simpantingkatkesadaran(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'transaksi_id' => 'required',
                'id_atribut_tingkat_kesadaran' => 'required',
                'nama_atribut_tingkat_kesadaran' => 'required',
                'id_atribut_status_tingkat_kesadaran' => 'required',
                'nama_atribut_status_tingkat_kesadaran' => 'required',
                'keluhan' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            if (filter_var($request->isedit, FILTER_VALIDATE_BOOLEAN)) {
                TingkatKesadaran::where('user_id', $request->user_id)
                    ->where('transaksi_id', $request->transaksi_id)
                    ->update([
                        'id_atribut_tingkat_kesadaran' => $request->id_atribut_tingkat_kesadaran,
                        'nama_atribut_tingkat_kesadaran' => $request->nama_atribut_tingkat_kesadaran,
                        'keterangan_tingkat_kesadaran' => $request->keterangan_tingkat_kesadaran,
                        'id_atribut_status_tingkat_kesadaran' => $request->id_atribut_status_tingkat_kesadaran,
                        'nama_atribut_status_tingkat_kesadaran' => $request->nama_atribut_status_tingkat_kesadaran,
                        'keterangan_status_tingkat_kesadaran' => $request->keterangan_status_tingkat_kesadaran,
                        'keluhan' => $request->keluhan,
                    ]);
                return ResponseHelper::success("Informasi Tingkat Kesadaran atas Nama Pasien ".$request->nama_peserta." telah diperbarui. Silahkan cek pada tabel dibawah ini halaman untuk melihat perubahan.");
            }
            $data_exist = TingkatKesadaran::where('user_id', $request->user_id)
                ->where('transaksi_id', $request->transaksi_id)
                ->first();
            if ($data_exist) {
                return ResponseHelper::data_conflict("Informasi Tingkat Kesadaran atas Nama Pasien ".$request->nama_pasien." sudah ada. Silahkan masukkan pasien lain atau ubah data sebelumnya.");
            }
            TingkatKesadaran::create([
                'user_id' => $request->user_id,
                'transaksi_id' => $request->transaksi_id,
                'id_atribut_tingkat_kesadaran' => $request->id_atribut_tingkat_kesadaran,
                'nama_atribut_tingkat_kesadaran' => $request->nama_atribut_tingkat_kesadaran,
                'keterangan_tingkat_kesadaran' => $request->keterangan_tingkat_kesadaran,
                'id_atribut_status_tingkat_kesadaran' => $request->id_atribut_status_tingkat_kesadaran,
                'nama_atribut_status_tingkat_kesadaran' => $request->nama_atribut_status_tingkat_kesadaran,
                'keterangan_status_tingkat_kesadaran' => $request->keterangan_status_tingkat_kesadaran,
                'keluhan' => $request->keluhan,
            ]);
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Tingkat Kesadaran']));
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function gettingkatkesadaran(Request $request)
    {
        try {
            $perHalaman = (int) $request->length > 0 ? (int) $request->length : 1;
            $nomorHalaman = (int) $request->start / $perHalaman;
            $offset = $nomorHalaman * $perHalaman; 
            $datatabel = TingkatKesadaran::listTingkatKesadaran($request, $perHalaman, $offset);
            $jumlahdata = $datatabel['total'];
            $dynamicAttributes = [
                'data' => $datatabel['data'],
                'recordsFiltered' => $jumlahdata,
                'pages' => [
                    'limit' => $perHalaman,
                    'offset' => $offset,
                ],
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Tingkat Kesadaran']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }   
    }
    public function hapus_tingkat_kesadaran(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'transaksi_id' => 'required',
                'nomor_identitas' => 'required',
                'nama_peserta' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }   
            TingkatKesadaran::where('user_id', $request->user_id)
                ->where('transaksi_id', $request->transaksi_id)
                ->delete();
            return ResponseHelper::success('Informasi Tingkat Kesadaran atas Nama Pasien '.$request->nama_peserta.' berhasil dihapus. Silahkan tambah kembali jika dibutuhkan');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function get_tingkat_kesadaran(Request $request){
        try {
            $data = TingkatKesadaran::where('user_id', $request->user_id)
                ->where('transaksi_id', $request->transaksi_id)
                ->first();
            $dynamicAttributes = [
                'data' => $data,
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Tingkat Kesadaran']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
}
