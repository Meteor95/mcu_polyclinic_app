<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper;
use App\Models\PemeriksaanFisik\{TingkatKesadaran, TandaVital, Penglihatan};
use App\Services\RegistrationMCUServices;

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
    public function simpantandavital(RegistrationMCUServices $registrationMCUServices, Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'informasi_tanda_vital' => 'required|array',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $registrationMCUServices->handleTransactionInsertTandaVitalPeserta($request);
            return ResponseHelper::success('Data tanda vital berhasil disimpan. Silahkan lakukan perubahan dengan cara ubah atau hapus pada tabel dibawah jikalau terdapat kesalahan dalam pengisian data');
        }catch(\Throwable $th){
            return ResponseHelper::error($th);
        }
    }
    public function daftar_tanda_vital(Request $request){
        try {
            $perHalaman = (int) $request->length > 0 ? (int) $request->length : 1;
            $nomorHalaman = (int) $request->start / $perHalaman;
            $offset = $nomorHalaman * $perHalaman; 
            $datatabel = TandaVital::listTandaVital($request, $perHalaman, $offset);
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
    public function hapustandavital(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'transaksi_id' => 'required',
                'nama_peserta' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            TandaVital::where('user_id', $request->user_id)
                ->where('transaksi_id', $request->transaksi_id)
                ->delete();
            return ResponseHelper::success('Data tanda vital atas nama '.$request->nama_peserta.' berhasil dihapus. Silahkan tambah kembali jika dibutuhkan');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function get_tandavital(Request $request){
        try {
            $data = TandaVital::where('user_id', $request->user_id)
                ->where('transaksi_id', $request->transaksi_id)
                ->get();
            $dynamicAttributes = [
                'data' => $data,
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Tingkat Kesadaran']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    /* Penglihatan */
    public function simpanpenglihatan(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'transaksi_id' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $data = [
                'user_id' => $request->user_id,
                'transaksi_id' => $request->transaksi_id,
                'visus_os_tanpa_kacamata_jauh' => $request->visus_os_tanpa_kacamata_jauh,
                'visus_od_tanpa_kacamata_jauh' => $request->visus_od_tanpa_kacamata_jauh,
                'visus_os_kacamata_jauh' => $request->visus_os_kacamata_jauh,
                'visus_od_kacamata_jauh' => $request->visus_od_kacamata_jauh,
                'visus_os_tanpa_kacamata_dekat' => $request->visus_os_tanpa_kacamata_dekat,
                'visus_od_tanpa_kacamata_dekat' => $request->visus_od_tanpa_kacamata_dekat,
                'visus_os_kacamata_dekat' => $request->visus_os_kacamata_dekat,
                'visus_od_kacamata_dekat' => $request->visus_od_kacamata_dekat,
                'buta_warna' => $request->buta_warna,
                'lapang_pandang_superior_os' => $request->lapang_pandang_superior_os,
                'lapang_pandang_inferior_os' => $request->lapang_pandang_inferior_os,
                'lapang_pandang_temporal_os' => $request->lapang_pandang_temporal_os,
                'lapang_pandang_nasal_os' => $request->lapang_pandang_nasal_os,
                'lapang_pandang_keterangan_os' => $request->lapang_pandang_keterangan_os,
                'lapang_pandang_superior_od' => $request->lapang_pandang_superior_od,
                'lapang_pandang_inferior_od' => $request->lapang_pandang_inferior_od,
                'lapang_pandang_temporal_od' => $request->lapang_pandang_temporal_od,
                'lapang_pandang_nasal_od' => $request->lapang_pandang_nasal_od,
                'lapang_pandang_keterangan_od' => $request->lapang_pandang_keterangan_od,
            ];
            if (filter_var($request->isedit, FILTER_VALIDATE_BOOLEAN)) {
                Penglihatan::where('user_id', $request->user_id)
                    ->where('transaksi_id', $request->transaksi_id)
                    ->update($data);
            } else {
                Penglihatan::create($data);
            }

            return ResponseHelper::success('Data peserta untuk test penglihatan berhasil disimpan. Silahkan lakukan perubahan dengan cara ubah atau hapus pada tabel dibawah jikalau terdapat kesalahan dalam pengisian data');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function daftar_penglihatan(Request $request){
        try {
            $perHalaman = (int) $request->length > 0 ? (int) $request->length : 1;
            $nomorHalaman = (int) $request->start / $perHalaman;
            $offset = $nomorHalaman * $perHalaman; 
            $datatabel = Penglihatan::listPenglihatan($request, $perHalaman, $offset);
            $jumlahdata = $datatabel['total'];
            $dynamicAttributes = [
                'data' => $datatabel['data'],
                'recordsFiltered' => $jumlahdata,
                'pages' => [
                    'limit' => $perHalaman,
                    'offset' => $offset,
                ],
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Penglihatan']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function hapus_penglihatan(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'transaksi_id' => 'required',
                'nama_peserta' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            Penglihatan::where('user_id', $request->user_id)
                ->where('transaksi_id', $request->transaksi_id)
                ->delete();
            return ResponseHelper::success('Data penglihatan atas nama '.$request->nama_peserta.' berhasil dihapus pada id transaksi '.$request->transaksi_id.'. Silahkan tambah kembali jika dibutuhkan');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function get_penglihatan(Request $request){
        try {
            $data = Penglihatan::where('user_id', $request->user_id)
                ->where('transaksi_id', $request->transaksi_id)
                ->first();
            $dynamicAttributes = [
                'data' => $data,
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Penglihatan']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
}
