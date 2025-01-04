<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper;
use App\Models\Laboratorium\{Tarif, Kategori, Satuan};
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
}
