<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\Pendaftaran\Peserta;
use App\Models\Masterdata\MemberMCU;
use App\Models\Transaksi\{LingkunganKerjaPeserta,RiwayatKecelakaanKerja,RiwayatKebiasaanHidup,RiwayatPenyakitKeluarga,RiwayatImunisasi,RiwayatPenyakitTerdahulu};
use App\Models\EndUser\Formulir;
use App\Services\RegistrationMCUServices;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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
    public function validasi_peserta(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nomor_identitas' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $informasi_formulir = Formulir::where('nomor_identifikasi', $request->nomor_identitas)->first();
            if (!$informasi_formulir) {
                return ResponseHelper::data_not_found("Informasi member dengan nomor identitas <strong>".$request->nomor_identitas."</strong> belum terdaftar pada sistem MCU");
            }
            $data = [
                'nomor_identifikasi' => $request->nomor_identitas,
                'nama_peserta' => $informasi_formulir->nama_peserta,
                'json_data_diri' => $informasi_formulir->json_data_diri,
                'json_lingkungan_kerja' => $informasi_formulir->json_lingkungan_kerja,
                'json_kecelakaan_kerja' => $informasi_formulir->json_kecelakaan_kerja,
                'json_kebiasaan_hidup' => $informasi_formulir->json_kebiasaan_hidup,
                'json_penyakit_terdahulu' => $informasi_formulir->json_penyakit_terdahulu,
                'json_penyakit_keluarga' => $informasi_formulir->json_penyakit_keluarga,
                'json_imunisasi' => $informasi_formulir->json_imunisasi,
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Calon Peserta']), $data);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function validasi_pasien(RegistrationMCUServices $registrationMCUServices,Request $request){
        $validator = Validator::make($request->all(), [
            'jsonDataDataDiri' => 'required',
            'jsonDataLingkunganKerja' => 'required',
            'jsonDataKecelakaanKerja' => 'required',
            'jsonDataKebiasaanHidup' => 'required',
            'jsonDataPenyakitTerdahulu' => 'required',
            'jsonDataPenyakitKeluarga' => 'required',
            'jsonDataImunisasi' => 'required',
        ]);
        if ($validator->fails()) {
            $dynamicAttributes = ['errors' => $validator->errors()];
            return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
        }
        try {
            $data = $request->all();
            $hasilquery = $registrationMCUServices->handleValidasiPeserta($data);
            if (!$hasilquery) {
                return ResponseHelper::data_not_found(__('common.data_not_found', ['namadata' => 'Informasi Peserta']));
            }
            return ResponseHelper::success(__('common.data_ready', ['namadata' => 'Informasi Peserta']));
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function deletepeserta(RegistrationMCUServices $registrationMCUServices, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama_peserta' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $data = $request->all();
            $registrationMCUServices->handleTransactionDeletePeserta($data);
            return ResponseHelper::success_delete("Informasi daftar peserta dengan nama <strong>".$data['nama_peserta']."</strong> dengan nomor antrian <strong>".$data['no_pemesanan']."</strong> berhasil dihapus beserta paramter lainnya");
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
                $data = Peserta::where('nomor_identifikasi', $request->nomor_identitas)->first();
                Log::info($data->json_lingkungan_kerja);
                $dynamicAttributes = [  
                    'data' => $data,
                    'message_info' => '<h4>Informasi Peserta dengan Nama : <strong>'.$data->nama_peserta.'</strong></h4><span style="color:red">BELUM TERDAFTAR PADA SISTEM MCU</span>. Informasi member ini akan ditambahkan menjadi member di '.config('app.name').' secara otomatis jika selesai melakukan transaksi MCU',
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
    public function simpanriwayatlingkungankerja(RegistrationMCUServices $registrationMCUServices, Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'informasi_riwayat_lingkungan_kerja' => 'required|array',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $registrationMCUServices->handleTransactionInsertLingkunganKerjaPeserta($request);
            return ResponseHelper::success('Data riwayat lingkungan kerja berhasil disimpan. Silahkan lakukan perubahan dengan cara ubah atau hapus pada tabel dibawah jikalau terdapat kesalahan dalam pengisian data');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function riwayatlingkungankerja(Request $request){
        try {
            $data = LingkunganKerjaPeserta::join('users_member', 'users_member.id', '=', 'mcu_lingkungan_kerja_peserta.user_id')
                ->where('user_id', $request->user_id)
                ->where('transaksi_id', $request->transaksi_id)
                ->get();
            if ($data->isEmpty()){
                return ResponseHelper::data_not_found(__('common.data_not_found', ['namadata' => 'Riwayat Lingkungan Kerja']));
            }
            $dynamicAttributes = [
                'data' => $data,
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Data Riwayat Lingkungan Kerja'])." jikalau ada perubahan maka data yang lama akan dihapus semua dan digantikan dengan parameter baru", $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function getpasien_riwayatlingkungankerja(Request $request){
        try {
            $perHalaman = (int) $request->length > 0 ? (int) $request->length : 1;
            $nomorHalaman = (int) $request->start / $perHalaman;
            $offset = $nomorHalaman * $perHalaman;
            $data = LingkunganKerjaPeserta::listPesertaLingkuanKerjaTabel($request, $perHalaman, $offset);
            $dynamicAttributes = [
                'data' => $data['data'],
                'recordsFiltered' => $data['total'],
                'pages' => [
                    'limit' => $perHalaman,
                    'offset' => $offset,
                ],
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Peserta']), $dynamicAttributes);
        }catch(\Throwable $th){
            return ResponseHelper::error($th);
        }
    }
    public function hapusriwayatlingkungankerja(Request $request){
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
            LingkunganKerjaPeserta::where('transaksi_id', $request->transaksi_id)
                ->where('user_id', $request->user_id)
                ->delete();
            return ResponseHelper::success('Formulir Bahaya Riwayat Lingkungan Kerja dengan Nomor MCU <strong>'.$request->nomor_identitas.'</strong> atas nama <strong>'.$request->nama_peserta.'</strong> berhasil dihapus. Formulir ini bersifat wajib diisi oleh peserta MCU. Jadi silahkan isi kembali formulir tersebut jikalau dibutuhkan pada laporan MCU');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function getpasien_riwayatkecelakaankerja(Request $request){
        try {
            $perHalaman = (int) $request->length > 0 ? (int) $request->length : 1;
            $nomorHalaman = (int) $request->start / $perHalaman;
            $offset = $nomorHalaman * $perHalaman;
            $data = RiwayatKecelakaanKerja::listPesertaKecelakaanKerjaTabel($request, $perHalaman, $offset);
            $dynamicAttributes = [
                'data' => $data['data'],
                'recordsFiltered' => $data['total'],
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
    public function simpanriwayatkecelakaankerja(Request $request){
        try {
            $user_id = RiwayatKecelakaanKerja::where('user_id', $request->input('user_id'))
                ->where('transaksi_id', $request->input('id_transaksi'))
                ->first();
            if ($user_id) {
                RiwayatKecelakaanKerja::where('user_id', $user_id->user_id)
                    ->where('transaksi_id', $request->input('id_transaksi'))
                    ->update([
                        'riwayat_kecelakaan_kerja' => $request->input('informasi_riwayat_kecelakaan_kerja'),
                        'updated_at' => now()
                    ]);
            }else{
                RiwayatKecelakaanKerja::create([
                    'user_id' => $request->input('user_id'),
                    'transaksi_id' => $request->input('id_transaksi'),
                    'riwayat_kecelakaan_kerja' => $request->input('informasi_riwayat_kecelakaan_kerja'),
                ]);
            }
            return ResponseHelper::success('Data riwayat kecelakaan kerja berhasil disimpan. Silahkan lakukan perubahan dengan cara ubah atau hapus pada tabel dibawah jikalau terdapat kesalahan dalam pengisian data');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function riwayatkecelakaankerja(Request $request){
        try {
            $data = RiwayatKecelakaanKerja::where('user_id', $request->user_id)
                ->where('transaksi_id', $request->transaksi_id)
                ->get();
            $dynamicAttributes = [
                'data' => $data,
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Data Riwayat Kecelakaan Kerja'])." jikalau ada perubahan maka data yang lama akan dihapus semua dan digantikan dengan parameter baru", $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function hapusriwayatkecelakaankerja(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'transaksi_id' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            RiwayatKecelakaanKerja::where('user_id', $request->user_id)
                ->where('transaksi_id', $request->transaksi_id)
                ->delete();
            return ResponseHelper::success('Data riwayat kecelakaan kerja dengan nomor identitas <strong>'.$request->nomor_identitas.'</strong> atas nama <strong>'.$request->nama_peserta.'</strong> berhasil dihapus. Formulir ini bersifat wajib diisi oleh peserta MCU. Jadi silahkan isi kembali formulir tersebut jikalau dibutuhkan pada laporan MCU');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }   
    }
    public function riwayatkebiasaanhidup(Request $request){
        try {
            $data = RiwayatKebiasaanHidup::join('users_member', 'users_member.id', '=', 'mcu_riwayat_kebiasaan_hidup.user_id')
                ->where('user_id', $request->user_id)
                ->where('transaksi_id', $request->transaksi_id)
                ->get();
            if ($data->isEmpty()){
                return ResponseHelper::data_not_found(__('common.data_not_found', ['namadata' => 'Riwayat Lingkungan Kerja']));
            }
            $dynamicAttributes = [
                'data' => $data,
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Data Riwayat Lingkungan Kerja'])." jikalau ada perubahan maka data yang lama akan dihapus semua dan digantikan dengan parameter baru", $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function simpankebiasaanhidup(RegistrationMCUServices $registrationMCUServices, Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'data' => 'required|array',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $registrationMCUServices->handleTransactionInsertKebiasaanHidupPeserta($request);
            return ResponseHelper::success('Data riwayat kebiasaan hidup berhasil disimpan. Silahkan lakukan perubahan dengan cara ubah atau hapus pada tabel dibawah jikalau terdapat kesalahan dalam pengisian data');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function getpasien_riwayatkebiasaanhidup(Request $request){
        try {
            $perHalaman = (int) $request->length > 0 ? (int) $request->length : 1;
            $nomorHalaman = (int) $request->start / $perHalaman;
            $offset = $nomorHalaman * $perHalaman;
            $data = RiwayatKebiasaanHidup::listPesertaKebiasaanHidupTabel($request, $perHalaman, $offset);
            $dynamicAttributes = [
                'data' => $data['data'],
                'recordsFiltered' => $data['total'],
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
    public function hapusriwayatkebiasaanhidup(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'transaksi_id' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            RiwayatKebiasaanHidup::where('user_id', $request->user_id)
                ->where('transaksi_id', $request->transaksi_id)
                ->delete();
            return ResponseHelper::success('Data riwayat kebiasaan hidup dengan nomor identitas <strong>'.$request->nomor_identitas.'</strong> atas nama <strong>'.$request->nama_peserta.'</strong> berhasil dihapus. Formulir ini bersifat wajib diisi oleh peserta MCU. Jadi silahkan isi kembali formulir tersebut jikalau dibutuhkan pada laporan MCU');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function simpanriwayatpenyakitkeluarga(RegistrationMCUServices $registrationMCUServices, Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'informasi_riwayat_penyakit_keluarga' => 'required|array',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $registrationMCUServices->handleTransactionInsertRiwayatPenyakitKeluargaPeserta($request);
            return ResponseHelper::success('Data riwayat penyakit keluarga berhasil disimpan. Silahkan lakukan perubahan dengan cara ubah atau hapus pada tabel dibawah jikalau terdapat kesalahan dalam pengisian data');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function getpasien_riwayatpenyakitkeluarga(Request $request){
        try {
            $perHalaman = (int) $request->length > 0 ? (int) $request->length : 1;
            $nomorHalaman = (int) $request->start / $perHalaman;
            $offset = $nomorHalaman * $perHalaman;
            $data = RiwayatPenyakitKeluarga::listPesertaRiwayatPenyakitKeluargaTabel($request, $perHalaman, $offset);
            $dynamicAttributes = [
                'data' => $data['data'],
                'recordsFiltered' => $data['total'],
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
    public function riwayatpenyakitkeluarga(Request $request){
        try {
            $data = RiwayatPenyakitKeluarga::join('users_member', 'users_member.id', '=', 'mcu_riwayat_penyakit_keluarga.user_id')
                ->where('user_id', $request->user_id)
                ->where('transaksi_id', $request->transaksi_id)
                ->get();
            if ($data->isEmpty()){
                return ResponseHelper::data_not_found(__('common.data_not_found', ['namadata' => 'Riwayat Penyakit Keluarga']));
            }
            $dynamicAttributes = [
                'data' => $data,
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Data Penyakit Keluarga'])." jikalau ada perubahan maka data yang lama akan dihapus semua dan digantikan dengan parameter baru", $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function hapusriwayatpenyakitkeluarga(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'transaksi_id' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            RiwayatPenyakitKeluarga::where('user_id', $request->user_id)
                ->where('transaksi_id', $request->transaksi_id)
                ->delete();
            return ResponseHelper::success('Data riwayat penyakit keluarga dengan nomor identitas <strong>'.$request->nomor_identitas.'</strong> atas nama <strong>'.$request->nama_peserta.'</strong> berhasil dihapus. Formulir ini bersifat wajib diisi oleh peserta MCU. Jadi silahkan isi kembali formulir tersebut jikalau dibutuhkan pada laporan MCU');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function simpanimunisasi(RegistrationMCUServices $registrationMCUServices, Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'informasi_imunisasi' => 'required|array',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $registrationMCUServices->handleTransactionInsertImunisasiPeserta($request);
            return ResponseHelper::success('Data imunisasi berhasil disimpan. Silahkan lakukan perubahan dengan cara ubah atau hapus pada tabel dibawah jikalau terdapat kesalahan dalam pengisian data');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function getpasien_imunisasi(Request $request){
        try {
            $perHalaman = (int) $request->length > 0 ? (int) $request->length : 1;
            $nomorHalaman = (int) $request->start / $perHalaman;
            $offset = $nomorHalaman * $perHalaman;
            $data = RiwayatImunisasi::listPesertaRiwayatImunisasiTabel($request, $perHalaman, $offset);
            $dynamicAttributes = [
                'data' => $data['data'],
                'recordsFiltered' => $data['total'],
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
    public function imunisasi(Request $request){
        try {
            $data = RiwayatImunisasi::join('users_member', 'users_member.id', '=', 'mcu_riwayat_imunisasi.user_id')
                ->where('user_id', $request->user_id)
                ->where('transaksi_id', $request->transaksi_id)
                ->get();
            if ($data->isEmpty()){
                return ResponseHelper::data_not_found(__('common.data_not_found', ['namadata' => 'Riwayat Imunisasi']));
            }
            $dynamicAttributes = [
                'data' => $data,
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Data Penyakit Keluarga'])." jikalau ada perubahan maka data yang lama akan dihapus semua dan digantikan dengan parameter baru", $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function hapusimunisasi(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'transaksi_id' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            RiwayatImunisasi::where('user_id', $request->user_id)
                ->where('transaksi_id', $request->transaksi_id)
                ->delete();
            return ResponseHelper::success('Data imunisasi dengan nomor identitas <strong>'.$request->nomor_identitas.'</strong> atas nama <strong>'.$request->nama_peserta.'</strong> berhasil dihapus. Formulir ini bersifat wajib diisi oleh peserta MCU. Jadi silahkan isi kembali formulir tersebut jikalau dibutuhkan pada laporan MCU');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function simpanriwayatpenyakitterdahulu(RegistrationMCUServices $registrationMCUServices, Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'informasi_penyakit_terdahulu' => 'required|array',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $registrationMCUServices->handleTransactionInsertRiwayatPenyakitTerdahuluPeserta($request);
            return ResponseHelper::success('Data riwayat penyakit terdahulu berhasil disimpan. Silahkan lakukan perubahan dengan cara ubah atau hapus pada tabel dibawah jikalau terdapat kesalahan dalam pengisian data');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function getpasien_riwayatpenyakitterdahulu(Request $request){
        try {
            $perHalaman = (int) $request->length > 0 ? (int) $request->length : 1;
            $nomorHalaman = (int) $request->start / $perHalaman;
            $offset = $nomorHalaman * $perHalaman;
            $data = RiwayatPenyakitTerdahulu::listPesertaRiwayatPenyakitTerdahuluTabel($request, $perHalaman, $offset);
            $dynamicAttributes = [
                'data' => $data['data'],
                'recordsFiltered' => $data['total'],
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
    public function riwayatpenyakitterdahulu(Request $request){
        try {
            $data = RiwayatPenyakitTerdahulu::join('users_member', 'users_member.id', '=', 'mcu_riwayat_penyakit_terdahulu.user_id')
                ->where('user_id', $request->user_id)
                ->where('transaksi_id', $request->transaksi_id)
                ->get();
            if ($data->isEmpty()){
                return ResponseHelper::data_not_found(__('common.data_not_found', ['namadata' => 'Riwayat Imunisasi']));
            }
            $dynamicAttributes = [
                'data' => $data,
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Data Penyakit Keluarga'])." jikalau ada perubahan maka data yang lama akan dihapus semua dan digantikan dengan parameter baru", $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function hapusriwayatpenyakitterdahulu(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'transaksi_id' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            RiwayatPenyakitTerdahulu::where('user_id', $request->user_id)
                ->where('transaksi_id', $request->transaksi_id)
                ->delete();
            return ResponseHelper::success('Data riwayat penyakit terdahulu dengan nomor identitas <strong>'.$request->nomor_identitas.'</strong> atas nama <strong>'.$request->nama_peserta.'</strong> berhasil dihapus. Formulir ini bersifat wajib diisi oleh peserta MCU. Jadi silahkan isi kembali formulir tersebut jikalau dibutuhkan pada laporan MCU');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
}
