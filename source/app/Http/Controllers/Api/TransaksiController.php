<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TransaksiServices;
use Illuminate\Support\Facades\{Validator, Storage};
use App\Helpers\ResponseHelper;
use App\Models\Transaksi\{Transaksi, UnggahCitra};
use App\Models\Masterdata\MemberMCU;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

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
    public function getdatapasien(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'nomor_identitas' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $data = MemberMCU::join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.user_id', '=', 'users_member.id')
                ->where('users_member.nomor_identitas', $request->nomor_identitas)
                ->select('users_member.*', 'mcu_transaksi_peserta.no_transaksi', 'mcu_transaksi_peserta.id as id_transaksi')
                ->first();
            if (!$data) {
                return ResponseHelper::data_not_found(__('common.data_not_found', ['namadata' => 'Informasi Pasien MCU tidak ditemukan. Silahkan lakukan pendaftaran terlebih dahulu dengan cara transaksi MCU dan tentukan paket yang diinginkan']));
            }
            $dynamicAttributes = [
                'data' => $data,
            ];
            return ResponseHelper::data('Data pasien MCU dengan Nomor Identitas ' . $request->nomor_identitas, $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function getpasien_unggah_citra(Request $request){
        try {
            $perHalaman = (int) $request->length > 0 ? (int) $request->length : 1;
            $nomorHalaman = (int) $request->start / $perHalaman;
            $offset = $nomorHalaman * $perHalaman;
            $data = UnggahCitra::listPasienUnggahCitra($request, $perHalaman, $offset);
            $jumlahdata = $data['total'];
            $dataWithFoto = collect($data['data'])->map(function ($item) {
                $item->data_foto = url("/api/v1/file/unduh_foto?file_name=" . $item->lokasi_gambar);
                return $item;
            });
            $dynamicAttributes = [
                'data' => $dataWithFoto,
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
    public function upload_images_mcu(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                'foto' => 'required|image|mimes:jpeg,png,jpg|max:20480',
                'nomor_identitas' => 'required',
                'informasimember' => 'required',
                'id_transaksi' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $user_id = MemberMCU::where('nomor_identitas', $request->input('nomor_identitas'))->first();
            if ($user_id) {
                return ResponseHelper::data_conflict("Informasi unggahan foto peserta MCU : " . $request->input('informasimember') . " sudah terdaftar dalam sistem MCU Artha Medica. Silahkan hapus terlebih dahulu informasi peserta MCU sebelum melakukan unggahan foto");
            }
            $uuid = (string) Str::uuid();
            $foto = $request->file('foto');
            $originalName = $foto->getClientOriginalName();
            $sanitizedName = strtolower(preg_replace('/[^\w.]+/', '_', $originalName));
            $timestamp = microtime(true);
            $filename = $uuid.'_'.$sanitizedName.'_'.$timestamp.'.png';
            $image = imagecreatefrompng($foto->getPathname());
            $compressionQuality = 8;
            $filePath = storage_path('app/public/mcu/foto_peserta/' . $filename);
            imagepng($image, $filePath, $compressionQuality);
            imagedestroy($image);
            UnggahCitra::create([
                'user_id' => $user_id->id,
                'lokasi_gambar' => $filename,
                'transaksi_id' => $request->input('id_transaksi'),
            ]);
            return ResponseHelper::success('Foto berhasil disimpan untuk peserta: ' . $request->input('informasimember'));
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function hapusunduhan_citra_peserta(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                'id' => 'required',
                'nomor_mcu' => 'required',
                'nama_peserta' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $data = UnggahCitra::where('id', $request->id)->first();
            Storage::disk('public')->delete('mcu/foto_peserta/' . $data->lokasi_gambar);
            $data->delete();
            return ResponseHelper::success('Informasi Foto ' . $request->nama_peserta . ' dengan Nomor MCU ' . $request->nomor_mcu . ' berhasil dihapus. Silahkan unggah citra kembali jika ingin menampilkan citra tersebut jika tidak foto akan ditampilkan dengan citra dasar dari sistem MCU');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
}
