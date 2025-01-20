<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TransaksiServices;
use Illuminate\Support\Facades\{Validator, Storage};
use App\Helpers\ResponseHelper;
use App\Models\Transaksi\{Transaksi, UnggahCitra};
use App\Models\Laboratorium\Transaksi as TransaksiLab;
use App\Models\Masterdata\MemberMCU;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;

class TransaksiController extends Controller
{
    public function savepeserta(TransaksiServices $transaksiService, Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'nomor_identitas' => 'required',
                'tanggal_transaksi' => 'required|date',
                'perusahaan_id' => 'required',
                'departemen_id' => 'required',
                'id_paket_mcu' => 'required',
                'proses_kerja' => 'required',
                'jenis_transaksi_pendaftaran' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $data = $request->all();
            $file = $request->file('nama_file_surat_pengantar');
            $return_transaksi = $transaksiService->handleTransactionPeserta($data,$request->attributes->get('user_id'),$file);
            Log::info($return_transaksi);
            if (!$return_transaksi) {
                return ResponseHelper::data_conflict('Pasien dengan Nama '.$data['nama_peserta'].' sudah melakukan pendaftaran dengan status PROSES dan belum selesai. Silahkan cek kembali pada menu pasien atau pilih peserta lainnya');
            }
            return ResponseHelper::success('Pengguna ' . $request->input('nama_pegawai') . ' berhasil didaftarkan kedalam sistem MCU Artha Medica. Silahkan tambah informasi detail MCU berdasarkan Nomor Indetitas yang sudah didaftarkan [' . $request->input('nomor_identitas') . ']');
        } catch (\Throwable $th) {
            Log::info($th);
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
            $dynamicAttributes = [
                'data' => $data['data'],
                'recordsFiltered' => $data['total'],
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
                ->join('departemen_peserta', 'departemen_peserta.id', '=', 'mcu_transaksi_peserta.departemen_id')
                ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
                ->leftJoin('paket_mcu', 'paket_mcu.id', '=', 'mcu_transaksi_peserta.id_paket_mcu')
                ->leftJoin('lab_template_tindakan', 'lab_template_tindakan.id_paket_mcu', '=', 'mcu_transaksi_peserta.id_paket_mcu')
                ->select('users_member.*', 'users_member.id as user_id', 'mcu_transaksi_peserta.no_transaksi', 'mcu_transaksi_peserta.id as id_transaksi', 'departemen_peserta.nama_departemen', 'company.company_name','paket_mcu.id as id_paket_mcu', 'paket_mcu.nama_paket', 'paket_mcu.harga_paket', 'mcu_transaksi_peserta.jenis_transaksi_pendaftaran','lab_template_tindakan.id as id_template_tindakan')
                ->where('users_member.nomor_identitas', $request->nomor_identitas)
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
                $item->data_foto = url(env('APP_VERSI_API')."/file/unduh_foto?file_name=" . $item->lokasi_gambar);
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
            $citra_peserta = UnggahCitra::where('user_id', $user_id->id)->where('transaksi_id', $request->input('id_transaksi'))->first();
            if ($citra_peserta) {
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
    public function konfirmasi_pembayaran(Request $request){
        try{
            $id_transaksi = $request->id_transaksi;
            $informasi_transaksi = TransaksiLab::where('id', $id_transaksi)->first();
            $parts = explode('/', $informasi_transaksi->no_nota, 4);
            $no_nota = implode('/', array_slice($parts, 0, 3));
            $no_mcu = $parts[3];
            if ($informasi_transaksi->status_pembayaran == 'done') {
                return ResponseHelper::data_not_found('Status pembayaran dengan No Nota: ' . $no_nota . ' dan No MCU: ' . $no_mcu . ' sudah tidak dapat diubah karena Status Pembayaran : SELESAI (DONE)');
            }
            $dataInformasi = [
                'jenis_transaksi' => $request->jenis_transaksi,
                'status_pembayaran' => $request->status_pembayaran,
                'total_bayar' => $request->total_bayar,
                'metode_pembayaran' => $request->metode_pembayaran,
            ];
            TransaksiLab::where('id', $id_transaksi)->update($dataInformasi);
            return ResponseHelper::success('Informasi transaksi dengan No Nota: ' . $no_nota . ' dan No MCU: ' . $no_mcu . ' berhasil dikonfirmasi menjadi Status Pembayaran: ');
        } catch (\Throwable $th) {
            Log::info($th);
            return ResponseHelper::error($th);
        }
    }
}
