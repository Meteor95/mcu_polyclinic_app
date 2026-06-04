<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TransaksiServices;
use Illuminate\Support\Facades\{Validator, Storage};
use App\Helpers\ResponseHelper;
use App\Models\Transaksi\{Transaksi, UnggahCitra};
use App\Models\Laboratorium\{Transaksi as TransaksiLab,TransaksiDetail};
use App\Models\Masterdata\Jasalayanan;
use App\Models\Masterdata\MemberMCU;
use App\Models\EdsJasaPelayanan;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
                'tipe_mcu_peserta' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $data = $request->all();
            $file = $request->file('nama_file_surat_pengantar');
            // Jika bukan edit, pastikan tidak ada transaksi PROSES
            if (!filter_var($data['isedit'], FILTER_VALIDATE_BOOLEAN)) {
                $member = MemberMCU::where('nomor_identitas', $data['nomor_identitas'])->first();
                if ($member) {
                    $existing = Transaksi::where('user_id', $member->id)
                        ->where('status_peserta', 'proses')
                        ->first();

                    if ($existing) {
                        return ResponseHelper::data_conflict('Pasien dengan Nama ' . $data['nama_peserta'] .' sudah melakukan pendaftaran dengan status PROSES dan belum selesai. Silahkan cek kembali pada menu pasien atau pilih peserta lainnya');
                    }
                }

            }
            $transaksiService->handleTransactionPeserta($data, $request->attributes->get('user_id'), $file, $request);
            return ResponseHelper::success('Pengguna ' . $request->input('nama_pegawai') . ' berhasil didaftarkan kedalam sistem MCU '.config('app.name').'. Silahkan tambah informasi detail MCU berdasarkan Nomor Indetitas yang sudah didaftarkan [' . $request->input('nomor_identitas') . ']');
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
            $cek_sudah_transaksi_tapi_pending = TransaksiLab::join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'transaksi.no_mcu')
                ->join('users_member', 'users_member.id', '=', 'mcu_transaksi_peserta.user_id')
                ->where('users_member.nomor_identitas', $request->nomor_identitas)
                ->where('transaksi.status_pembayaran', '=', 'process')
                ->first();
            if ($cek_sudah_transaksi_tapi_pending && filter_var($request->is_edit_transaksi, FILTER_VALIDATE_BOOLEAN)) {
                return ResponseHelper::data_not_found('Informasi Pasien MCU dengan Nomor Identitas ' . $request->nomor_identitas . ' sudah terdaftar dengan status PEMBAYARAN PROCESS. Silahkan cek kembali pada menu pasien atau pilih peserta lainnya');
            }
            $data = MemberMCU::join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.user_id', '=', 'users_member.id')
                ->join('departemen_peserta', 'departemen_peserta.id', '=', 'mcu_transaksi_peserta.departemen_id')
                ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
                ->leftJoin('paket_mcu', 'paket_mcu.id', '=', 'mcu_transaksi_peserta.id_paket_mcu')
                ->leftJoin('lab_template_tindakan', 'lab_template_tindakan.id_paket_mcu', '=', 'mcu_transaksi_peserta.id_paket_mcu')
                ->select('users_member.*', 'users_member.id as user_id', 'mcu_transaksi_peserta.no_transaksi', 'mcu_transaksi_peserta.id as id_transaksi', 'departemen_peserta.nama_departemen', 'company.company_name','paket_mcu.id as id_paket_mcu', 'paket_mcu.nama_paket', 'paket_mcu.harga_paket', 'mcu_transaksi_peserta.jenis_transaksi_pendaftaran','lab_template_tindakan.id as id_template_tindakan','paket_mcu.akses_tindakan')
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
            $dataWithFotoSignature = collect($data['data'])->map(function ($item) {
                $item->data_signature = url(env('APP_VERSI_API')."/file/unduh_foto_signature?file_name=" . $item->signature);
                return $item;
            });
            $dynamicAttributes = [
                'data' => $dataWithFoto,
                'data_signature' => $dataWithFotoSignature,
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
                'signature' => 'required|image|mimes:png|max:2048',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $user_id = MemberMCU::where('nomor_identitas', $request->input('nomor_identitas'))->first();
            $citra_peserta = UnggahCitra::where('user_id', $user_id->id)->where('transaksi_id', $request->input('id_transaksi'))->first();
            if ($citra_peserta) {
                return ResponseHelper::data_conflict("Informasi unggahan foto peserta MCU : " . $request->input('informasimember') . " sudah terdaftar dalam sistem MCU " . config('app.name') . ". Silahkan hapus terlebih dahulu informasi peserta MCU sebelum melakukan unggahan foto");
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
            
            $signature = $request->file('signature');
            $filenameSig = $uuid . '_signature_' . $timestamp . '.png';
            $imageSig = imagecreatefrompng($signature->getPathname());
            imagealphablending($imageSig, false);
            imagesavealpha($imageSig, true);
            $filePathSig = storage_path('app/public/mcu/signature/' . $filenameSig);
            imagepng($imageSig, $filePathSig, $compressionQuality);
            imagedestroy($imageSig);
            UnggahCitra::create([
                'user_id' => $user_id->id,
                'lokasi_gambar' => $filename,
                'transaksi_id' => $request->input('id_transaksi'),
                'signature' => $filenameSig,
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
            return ResponseHelper::error($th);
        }
    }
    public function pembagian_jasa_pelayanan(Request $request){
        try{
            $id_transaksi = $request->id_transaksi;
            $dari_laboratorium = $request->dari_laboratorium;
            $data_penerima_jp = [];
            if ($dari_laboratorium == 1) {
                $no_mcu = $request->no_mcu;
                $informasi_petugas = TransaksiDetail::join('transaksi', 'transaksi.id', '=', 'transaksi_detail.id_transaksi')->where('transaksi.no_mcu', $no_mcu)->where('transaksi_detail.kode_item', '1000001000001')->get();
                $cek_apakah_ada_jp = EdsJasaPelayanan::where('id_mcu_peserta', $no_mcu)->where('jenis_poli', 'lab')->first();
                $layanan = Jasalayanan::whereIn('kode_jasa_pelayanan', ['JS_PAKET_LAB_DOKTER', 'JS_PAKET_LAB_PETUGAS'])->get();
                $layananMap = $layanan->keyBy('kode_jasa_pelayanan');
                if ($cek_apakah_ada_jp == null) {
                    EdsJasaPelayanan::create([
                        'id_mcu_peserta' => $no_mcu,
                        'jenis_poli' => 'lab',
                        'role' => 'dokter',
                        'pegawai_id' => $informasi_petugas[0]->id_dokter,
                        'nominal' => $layananMap['JS_PAKET_LAB_DOKTER']->nominal_layanan ?? 0,
                    ]);
                    EdsJasaPelayanan::create([
                        'id_mcu_peserta' => $no_mcu,
                        'jenis_poli' => 'lab',
                        'role' => 'laboratorium',
                        'pegawai_id' => $informasi_petugas[0]->id_pj,
                        'nominal' => $layananMap['JS_PAKET_LAB_PETUGAS']->nominal_layanan ?? 0,
                    ]);
                }else{
                    EdsJasaPelayanan::where('id_mcu_peserta', $no_mcu)
                        ->where('jenis_poli', 'lab')
                        ->where('role', 'dokter')
                        ->update([
                            'pegawai_id' => $informasi_petugas[0]->id_dokter,
                            'nominal'    => DB::raw("CASE WHEN nominal = 0 THEN 0 ELSE nominal END")
                        ]);
                    EdsJasaPelayanan::where('id_mcu_peserta', $no_mcu)
                        ->where('jenis_poli', 'lab')
                        ->where('role', 'laboratorium')
                        ->update([
                            'pegawai_id' => $informasi_petugas[0]->id_pj,
                            'nominal'    => DB::raw("CASE WHEN nominal = 0 THEN 0 ELSE nominal END")
                        ]);
                }
                $data_penerima_jp = EdsJasaPelayanan::join('users_pegawai', 'users_pegawai.id', '=', 'jasa_pelayanan.pegawai_id')->where('id_mcu_peserta', $no_mcu)->where('jenis_poli', 'lab')->select('jasa_pelayanan.*', 'users_pegawai.nama_pegawai as nama_petugas')->orderBy('jasa_pelayanan.jenis_poli','ASC')->get();
            }else{
                $informasi_transaksi = TransaksiLab::where('id', $id_transaksi)->first();
                $data_penerima_jp = EdsJasaPelayanan::join('users_pegawai', 'users_pegawai.id', '=', 'jasa_pelayanan.pegawai_id')->where('id_mcu_peserta', $informasi_transaksi->no_mcu)->select('jasa_pelayanan.*', 'users_pegawai.nama_pegawai as nama_petugas')->orderBy('jasa_pelayanan.jenis_poli','ASC')->get();
            }
            $dynamicAttributes = ['data' => $data_penerima_jp];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Daftar Penerima Jasa Pelayanan']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function update_jasa_pelayanan(Request $request){
        try{
           $request->validate([
                'data' => 'required|array',
                'data.*.id' => 'required|integer',
                'data.*.nominal' => 'required|numeric|min:0',
            ]);
            DB::transaction(function () use ($request) {
                foreach ($request->data as $item) {
                    EdsJasaPelayanan::where('id', $item['id'])
                        ->update([
                            'nominal' => $item['nominal']
                        ]);
                }
            });
            return ResponseHelper::success('Informasi jasa pelayanan berhasil disesuaikan ulang dengan data terbaru');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
}
