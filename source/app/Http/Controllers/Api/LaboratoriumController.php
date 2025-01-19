<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper;
use App\Models\Laboratorium\{Tarif, Kategori, Satuan, Kenormalan, TemplateLab, Transaksi};
use Illuminate\Support\Facades\Log;
use App\Services\LaboratoriumServices;

class LaboratoriumController extends Controller
{
    public function getkategori_laboratorium(Request $request)
    {
        try {
            $kategori = Kategori::where('grup_kategori', $request->grup_kategori)->get();
            $dynamicAttributes = [
                'data' => $kategori,
            ];
            return ResponseHelper::data('Informasi Kategori Laboratorium', $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function getsatuan_laboratorium(Request $request)
    {
        try {
            $satuan = Satuan::where('grup_satuan', $request->grup_satuan)->get();
            $dynamicAttributes = [
                'data' => $satuan,
            ];
            return ResponseHelper::data('Informasi Satuan Laboratorium', $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function simpan_tarif_laboratorium(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'kode_item' => 'required',
                'nama_item' => 'required',
                'group_item' => 'required',
                'id_kategori' => 'required',
                'nama_kategori' => 'required',
                'satuan' => 'required',
                'jenis_item' => 'required',
                'harga_dasar' => 'required',
                'harga_jual' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $data = [
                'kode_item' => $request->kode_item,
                'nama_item' => $request->nama_item,
                'group_item' => $request->group_item,
                'id_kategori' => $request->id_kategori,
                'nama_kategori' => preg_replace('/-+/', '', $request->nama_kategori),
                'satuan' => $request->satuan,
                'jenis_item' => $request->jenis_item,
                'meta_data_kuantitatif' => json_encode($request->meta_data_kuantitatif),
                'meta_data_kualitatif' => json_encode($request->meta_data_kualitatif),
                'harga_dasar' => $request->harga_dasar,
                'meta_data_jasa' => json_encode($request->meta_data_jasa),
                'harga_jual' => $request->harga_jual,
            ];
            if ($request->isedit == 'true') {
                $tarif = Tarif::where('kode_item', $request->kode_item)->update($data);
            }else{
                $tarif = Tarif::create($data);
            }
            return ResponseHelper::success("Informasi Nama Tarif '".$request->nama_item."' Laboratorium berhasil disimpan. Silahkan lakukan transaksi pada menu Transaksi Laboratorium");
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function daftar_tarif(Request $req)
    {
        try {
            $perHalaman = (int) $req->length > 0 ? (int) $req->length : -1;
            $nomorHalaman = (int) $req->start / $perHalaman;
            $offset = $nomorHalaman * $perHalaman; 
            $datatabel = Tarif::listTarifTabel($req, $perHalaman, $offset);
            $jumlahdata = $datatabel['total'];
            $dynamicAttributes = [
                'data' => $datatabel['data'],
                'recordsFiltered' => $jumlahdata,
                'pages' => [
                    'limit' => $perHalaman,
                    'offset' => $offset,
                ],
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Hak Akses']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function pencarian_tarif_laboratorium(Request $req)
    {
        try {
            $tarif = Tarif::where('kode_item', 'like', '%'.$req->parameter_pencarian.'%')
            ->orWhere('nama_item', 'like', '%'.$req->parameter_pencarian.'%')
            ->orWhere('harga_jual', 'like', '%'.$req->parameter_pencarian.'%')
            ->limit($req->limit)
            ->get();
            $dynamicAttributes = [
                'data' => $tarif,
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Tarif Laboratorium']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function detail_tarif_laboratorium(Request $req)
    {
        try {
            $tarif = Tarif::where('kode_item', $req->kode_item)
            ->join('lab_kategori as B', 'id_kategori', '=', 'B.id')
            ->join('lab_satuan_item as C', 'satuan', '=', 'C.id')
            ->first();
            $dynamicAttributes = [
                'data' => $tarif,
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Tarif Laboratorium Nama : '.$tarif->nama_item]), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function hapus_tarif_laboratorium(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'kode_item' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $tarif = Tarif::where('kode_item', $req->kode_item)->delete();
            return ResponseHelper::success("Informasi Nama Tarif '".$req->nama_item."' Laboratorium berhasil dihapus. Jika membutuhkan restore data, silahkan hubungi administrator dengan konfirmasi kode item : ".$req->kode_item);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function daftar_kategori_laboratorium(Request $req){
        try {
            $perHalaman = (int) $req->length > 0 ? (int) $req->length : 1;
            $nomorHalaman = (int) $req->start / $perHalaman;
            $offset = $nomorHalaman * $perHalaman; 
            $datatabel = Kategori::listKategoriTabel($req, $perHalaman, $offset);
            $jumlahdata = $datatabel['total'];
            $dynamicAttributes = [
                'data' => $datatabel['data'],
                'recordsFiltered' => $jumlahdata,
                'pages' => [
                    'limit' => $perHalaman,
                    'offset' => $offset,
                ],
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Hak Akses']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function simpan_kategori_laboratorium(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'grup_kategori' => 'required',
                'nama_kategori' => 'required',
                'parent_kategori' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            if ($req->parent_kategori == 'root') {
                $req->parent_kategori = NULL;
            }
            $data = [
                'nama_kategori' => $req->nama_kategori,
                'parent_id' => $req->parent_kategori,
                'grup_kategori' => $req->grup_kategori,
            ];
            if (filter_var($req->isedit, FILTER_VALIDATE_BOOLEAN)) {
                $kategori = Kategori::where('nama_kategori', $req->nama_kategori)->update($data);
            }else{
                $kategori = Kategori::create($data);
            }
            return ResponseHelper::success("Informasi Kategori Laboratorium '".$req->nama_kategori."' berhasil disimpan. Silahkan lakukan transaksi pada menu Transaksi Laboratorium");
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function hapus_kategori_laboratorium(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'id' => 'required',
                'nama_kategori' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $kategori_is_use = Tarif::where('id_kategori', $req->id)->first();
            if ($kategori_is_use) {
                return ResponseHelper::data_conflict("Informasi Kategori Laboratorium '".$req->nama_kategori."' tidak dapat dihapus karena sedang digunakan oleh item pada laboratorium dan pengobatan, silahkan ubah terlebih dahulu kategori item tersebut.");
            }
            $kategori_is_parent_used = Kategori::where('parent_id', $req->id)->first();
            if ($kategori_is_parent_used) {
                return ResponseHelper::data_conflict("Informasi Kategori Laboratorium '".$req->nama_kategori."' tidak dapat dihapus karena sedang digunakan oleh kategori lain, silahkan ubah terlebih dahulu kategori item tersebut.");
            }
            $kategori = Kategori::where('id', $req->id)->delete();
            return ResponseHelper::success("Informasi Kategori Laboratorium '".$req->nama_kategori."' berhasil dihapus.");
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function detail_kategori_laboratorium(Request $req)
    {
        try {
            $kategori = Kategori::where('lab_kategori.id', $req->id)
            ->leftJoin('lab_kategori as parent', 'lab_kategori.parent_id', '=', 'parent.id')
            ->select('lab_kategori.id', 'lab_kategori.nama_kategori', 'lab_kategori.grup_kategori', 'parent.id as parent_id', 'parent.nama_kategori as parent_kategori')
            ->first();
            $dynamicAttributes = [
                'data' => $kategori,
            ];
            return ResponseHelper::data('Informasi Kategori Laboratorium', $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function daftar_satuan_laboratorium(Request $req){
        try {
            $perHalaman = (int) $req->length > 0 ? (int) $req->length : 1;
            $nomorHalaman = (int) $req->start / $perHalaman;
            $offset = $nomorHalaman * $perHalaman; 
            $datatabel = Satuan::listSatuanTabel($req, $perHalaman, $offset);
            $jumlahdata = $datatabel['total'];
            $dynamicAttributes = [
                'data' => $datatabel['data'],
                'recordsFiltered' => $jumlahdata,
                'pages' => [
                    'limit' => $perHalaman,
                    'offset' => $offset,
                ],
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Hak Akses']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function simpan_satuan_laboratorium(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'grup_satuan' => 'required',
                'nama_satuan' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $data = [
                'nama_satuan' => $req->nama_satuan,
                'grup_satuan' => $req->grup_satuan,
            ];
            if (filter_var($req->isedit, FILTER_VALIDATE_BOOLEAN)) {
                $satuan = Satuan::where('id', $req->id)->update($data);
            }else{
                $satuan = Satuan::create($data);
            }
            return ResponseHelper::success("Informasi Satuan Laboratorium '".$req->nama_satuan."' berhasil disimpan. Silahkan lakukan transaksi pada menu Transaksi Laboratorium");
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function hapus_satuan_laboratorium(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'id' => 'required',
                'nama_satuan' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $satuan_is_use = Tarif::where('satuan', $req->id)->first();
            if ($satuan_is_use) {
                return ResponseHelper::data_conflict("Informasi Satuan Laboratorium '".$req->nama_satuan."' tidak dapat dihapus karena sedang digunakan oleh item pada laboratorium dan pengobatan, silahkan ubah terlebih dahulu satuan item tersebut.");
            }
            $satuan = Satuan::where('id', $req->id)->delete();
            return ResponseHelper::success("Informasi Satuan Laboratorium '".$req->nama_satuan."' berhasil dihapus.");
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function detail_satuan_laboratorium(Request $req)
    {
        try {
            $satuan = Satuan::where('id', $req->id)->first();
            $dynamicAttributes = [
                'data' => $satuan,
            ];
            return ResponseHelper::data('Informasi Satuan Laboratorium', $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }   
    public function daftar_rentang_kenormalan(Request $req){
        try {
            $perHalaman = (int) $req->length > 0 ? (int) $req->length : 1;
            $nomorHalaman = (int) $req->start / $perHalaman;
            $offset = $nomorHalaman * $perHalaman; 
            $datatabel = Kenormalan::listRentangTabel($req, $perHalaman, $offset);
            $jumlahdata = $datatabel['total'];
            $dynamicAttributes = [
                'data' => $datatabel['data'],
                'recordsFiltered' => $jumlahdata,
                'pages' => [
                    'limit' => $perHalaman,
                    'offset' => $offset,
                ],
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Hak Akses']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function simpan_rentang_kenormalan(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'nama_rentang_kenormalan' => 'required',
                'umur_awal' => 'required',
                'umur_akhir' => 'required',
                'jenis_kelamin' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $umur = $req->umur_awal."-".$req->umur_akhir;
            if($req->umur_awal == "0" && $req->umur_akhir == "0"){
                $umur = -1;
            }
            $data = [
                'umur' => $umur,
                'nama_rentang_kenormalan' => $req->nama_rentang_kenormalan,
                'jenis_kelamin' => $req->jenis_kelamin,
            ];
            if (filter_var($req->isedit, FILTER_VALIDATE_BOOLEAN)) {
                $rentang = Kenormalan::where('id', $req->id)->update($data);
            }else{
                $rentang = Kenormalan::create($data);
            }
            return ResponseHelper::success("Informasi Nilai Rentang Kenormalan '".$req->nama_rentang_kenormalan."' berhasil disimpan. Silahkan lakukan transaksi pada menu Transaksi Laboratorium");
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function hapus_rentang_kenormalan(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'id' => 'required',
                'nama_rentang_kenormalan' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $rentang = Kenormalan::where('id', $req->id)->delete();
            return ResponseHelper::success("Informasi Nilai Rentang Kenormalan '".$req->nama_rentang_kenormalan."' berhasil dihapus.");
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    /* Template Laboratorium */
    public function daftar_template_laboratorium(Request $req){
        try {
            $perHalaman = (int) $req->length > 0 ? (int) $req->length : -1;
            $nomorHalaman = (int) $req->start / $perHalaman;
            $offset = $nomorHalaman * $perHalaman; 
            $datatabel = TemplateLab::listTemplateTabel($req, $perHalaman, $offset);
            $dynamicAttributes = [
                'data' => $datatabel['data'],
                'recordsFiltered' => $datatabel['total'],
                'pages' => [
                    'limit' => $perHalaman,
                    'offset' => $offset,
                ],
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Hak Akses']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function simpan_template_laboratorium(Request $req){
        try {
            $validator = Validator::make($req->all(), [
                'used_paket_mcu' => 'required',
                'id_paket_mcu' => 'required',
                'nama_template' => 'required',
                'meta_data_template' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $data = [
                'used_paket_mcu' => $req->used_paket_mcu,
                'id_paket_mcu' => $req->id_paket_mcu,
                'nama_template' => $req->nama_template,
                'meta_data_template' => json_encode($req->meta_data_template),
            ];
            if (filter_var($req->isedit, FILTER_VALIDATE_BOOLEAN)) {
                $template = TemplateLab::where('id', $req->id)->update($data);
            }else{
                $template = TemplateLab::create($data);
            }
            return ResponseHelper::success("Informasi Template Laboratorium '".$req->nama_template."' berhasil disimpan. Silahkan lakukan transaksi pada menu Transaksi Laboratorium");
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function hapus_template_laboratorium(Request $req){
        try {
            $validator = Validator::make($req->all(), [
                'id' => 'required',
                'nama_template' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            TemplateLab::where('id', $req->id)->delete();
            return ResponseHelper::success("Informasi Template Laboratorium '".$req->nama_template."' berhasil dihapus. Silahkan buat template baru untuk mempermudah proses transaksi lab pada fitur transaksi lab.");
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function detail_template_laboratorium(Request $req){
        try {
            $template = TemplateLab::where('id', $req->id)->first();
            $dynamicAttributes = [  
                'data' => $template,
            ];
            return ResponseHelper::data('Informasi Template Laboratorium', $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function pilih_template_tindakan_mcu(Request $req){
        try {
            $template = TemplateLab::where('id', $req->id_template_tindakan)->first();
            if (!$template) {
                $dynamicAttributes = [
                    'data' => [],
                ];
                return ResponseHelper::data('Informasi template tindakan tidak ditemukan. Silahkan tentukan template terlebih dahulu untuk menghindari kerancuan data', $dynamicAttributes);
            }
            $metaData = json_decode($template->meta_data_template, true);
            $kodeItems = array_column($metaData, 'kode_item');
            $tarifItems = Tarif::whereIn('kode_item', $kodeItems)->get();
            $dynamicAttributes = [
                'id_template_tindakan' => $template->id,
                'nama_template' => $template->nama_template,
                'data' => $tarifItems,
            ];
            return ResponseHelper::data('Informasi Template Laboratorium', $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    /* Transaksi Tindakan */
    public function simpan_tindakan(LaboratoriumServices $laboratoriumService, Request $req){
        try {
            $validator = Validator::make($req->all(), [
                'no_mcu' => 'required',
                'no_nota' => 'required',
                'waktu_trx' => 'required',
                'waktu_trx_sample' => 'required',
                'id_dokter' => 'required',
                'nama_dokter' => 'required',
                'id_pj' => 'required',
                'nama_pj' => 'required',
                'total_bayar' => 'required',
                'total_transaksi' => 'required',
                'total_tindakan' => 'required',
                'jenis_transaksi' => 'required',
                'status_pembayaran' => 'required',
                'is_paket_mcu' => 'required',
                'nama_paket_mcu' => 'required',
                'keranjang_tindakan' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $data = $req->all();
            $file = $req->file('nama_file_surat_pengantar');
            if (!filter_var($req->is_edit_transaksi, FILTER_VALIDATE_BOOLEAN)){
            $pasien_sudah_ada = Transaksi::where('no_mcu', $req->no_mcu)
            ->where('status_pembayaran', '!=', 'paid')->first();
                if ($pasien_sudah_ada) {
                    $parts_nota = explode("/", $pasien_sudah_ada->no_nota);
                    $no_nota = implode("/", array_slice($parts_nota, 0, 3));
                    $no_mcu = implode("/", array_slice($parts_nota, 3));
                    return ResponseHelper::data_conflict("Informasi pasien MCU dengan <b>Nomor Transaksi '".$no_nota."' [MCU : ".$no_mcu."]</b> sudah ada. Silahkan lakukan transaksi dengan nomor transaksi yang berbeda.");
                }
            }
            $iserrir = $laboratoriumService->handleTransactionLaboratorium($data,$req->attributes->get('user_id'),$file);
            return ResponseHelper::success('Informasi transaksi tindakan dengan No. Nota ' . $req->no_nota . ' [MCU : ' . $req->no_mcu . '] berhasil disimpan. Silahkan lakukan validasi transaksi dengan hak akses kasir atau yang sesuai.');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function daftar_tindakan(Request $req){
        try {
            $perHalaman = (int) $req->length > 0 ? (int) $req->length : -1;
            $nomorHalaman = (int) $req->start / $perHalaman;
            $offset = $nomorHalaman * $perHalaman; 
            $datatabel = Transaksi::listTabelTindakan($req, $perHalaman, $offset);
            $dynamicAttributes = [
                'data' => $datatabel['data'],
                'recordsFiltered' => $datatabel['total'],
                'pages' => [
                    'limit' => $perHalaman,
                    'offset' => $offset,
                ],
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Hak Akses']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function hapus_tindakan(LaboratoriumServices $laboratoriumService, Request $req){
        try {
            $validator = Validator::make($req->all(), [
                'id_transaksi' => 'required',
                'nama_peserta' => 'required',
                'no_nota' => 'required',
                'no_mcu' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $data = $req->all();
            $iserrir = $laboratoriumService->handleDeleteTransactionLaboratorium($data,$req->attributes->get('user_id'));
            return ResponseHelper::success("Informasi transaksi tindakan dengan No. Nota '".$req->no_nota."' [MCU : ".$req->no_mcu."] berhasil dihapus. Silahkan transkasikan kembali jikalau dibutuhkan untuk keperluan dokumen lain seperti MCU atau laporan lainnya.");
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function detail_tindakan(Request $req){
        try {
            $validator = Validator::make($req->all(), [
                'id_transaksi' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $transaksiData = Transaksi::join('transaksi_detail', 'transaksi.id', '=', 'transaksi_detail.id_transaksi')
            ->join('mcu_transaksi_peserta', 'transaksi.no_mcu', '=', 'mcu_transaksi_peserta.id')
            ->join('users_member', 'users_member.id', '=', 'mcu_transaksi_peserta.user_id')
            ->select('transaksi.*', 'transaksi.id as id_transaksi', 'transaksi_detail.*', 'mcu_transaksi_peserta.*', 'users_member.*')
            ->where('transaksi.id', $req->id_transaksi)
            ->get();
            $transaksiFee = Transaksi::where('id_transaksi', $req->id_transaksi)
            ->join('transaksi_fee', 'transaksi.id', '=', 'transaksi_fee.id_transaksi')
            ->get();
            $dynamicAttributes = [
                'transaksi' => $transaksiData,
                'transaksi_fee' => $transaksiFee,
            ];
            return ResponseHelper::data('Informasi Transaksi Tindakan', $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
}

