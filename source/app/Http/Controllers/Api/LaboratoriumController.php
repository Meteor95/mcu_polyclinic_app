<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper;
use App\Models\Laboratorium\{Tarif, Kategori, Satuan, Kenormalan};
use Illuminate\Support\Facades\Log;


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
                'meta_data_kuantitaif' => json_encode($request->meta_data_kuantitaif),
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
            Log::error($th);
            return ResponseHelper::error($th);
        }
    }
    public function daftar_tarif(Request $req)
    {
        try {
            $perHalaman = (int) $req->length > 0 ? (int) $req->length : 1;
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
            Log::error($th);
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
}

