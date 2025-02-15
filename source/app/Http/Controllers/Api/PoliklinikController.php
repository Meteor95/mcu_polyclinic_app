<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PoliklinikServices;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\{Storage, Log};
use Illuminate\Support\Facades\Validator;

use App\Models\Poliklinik\{Poliklinik, UnggahanCitra};

class PoliklinikController extends Controller
{
    public function simpan_poliklinik(Request $req, $jenis_poli, PoliklinikServices $poliklinikServices)
    {
        try {
            $validator = Validator::make($req->all(), [
                'pegawai_id' => 'required',
                'user_id' => 'required',
                'transaksi_id' => 'required',
                'judul_laporan' => 'required',
                'kesimpulan' => 'required',
                'citra_unggahan_poliklinik.*' => 'file|mimes:png,jpg,jpeg|max:20480',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $data = $req->all();
            $userId = $req->attributes->get('user_id');
            $poliklinikServices->handleTransactionPoliklinik($data, $req->file('citra_unggahan_poliklinik'),$jenis_poli, $userId);
            $dynamicAttributes = [
                'data' => $data,
            ];
            return ResponseHelper::data('Informasi unggahan data Poliklinik '.ucwords(str_replace('_', ' ', $jenis_poli)).' pada transaksi '.$data['transaksi_id'].' berhasil disimpan', $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function daftar_citra_unggahan_poliklinik(Request $req, PoliklinikServices $poliklinikServices)
    {
        $jenis_poli = $req->jenis_poli;
        try {
            $model = new Poliklinik();
            $tableName = $this->determineTableName($jenis_poli);
            if (!$tableName) return ResponseHelper::error('Invalid location.');
            $model->setTableName($tableName);
            $perHalaman = max((int)$req->length, 1);
            $offset = (int)($req->start / $perHalaman) * $perHalaman;
            $datatabel = $model->listCitraUnggahanPoliklinik($req, $perHalaman, $offset);
            $dataWithFoto = collect($datatabel['data'])->map(function ($item) use ($jenis_poli) {
                $item->data_foto = url(env('APP_VERSI_API')."/file/unduh_citra_poliklinik?jenis_poli=".$jenis_poli."&file_name=" . $item->nama_file);
                return $item;
            });
            $dynamicAttributes = [
                'data' => $dataWithFoto,
                'recordsFiltered' => $datatabel['total'],
                'pages' => [
                    'limit' => $perHalaman,
                    'offset' => $offset,
                ],
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Citra Unggahan Poliklinik']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    private function determineTableName($jenis_poli)
    {
        $tables = [
            'spirometri' => 'mcu_poli_spirometri',
            'ekg' => 'mcu_poli_ekg',
            'threadmill' => 'mcu_poli_threadmill',
            'rontgen_thorax' => 'mcu_poli_rontgen_thorax',
            'rontgen_lumbosacral' => 'mcu_poli_rontgen_lumbosacral',
            'audiometri' => 'mcu_poli_audiometri',
            'usg_ubdomain' => 'mcu_poli_usg_ubdomain',
            'farmingham_score' => 'mcu_poli_farmingham_score',

        ];
        return $tables[strtolower($jenis_poli)] ?? null;
    }
    public function detail_citra_unggahan_poliklinik(Request $req)
    {
        try {
            if ($req->jenis_modal === "lihat_foto_detail") {
                $citra_unggahan_poliklinik = UnggahanCitra::where('id_trx_poli', $req->id_trx_poli)->where('jenis_poli', 'poli_'.$req->jenis_poli)->get();
                $dataWithFoto = collect($citra_unggahan_poliklinik)->map(function ($item) {
                    $item->data_foto = url(env('APP_VERSI_API')."/file/unduh_citra_poliklinik?jenis_poli=".$item->jenis_poli ."&file_name=" . $item->nama_file);
                    return $item;
                });
                $dynamicAttributes = [
                    'foto' => $dataWithFoto,
                ];
                return ResponseHelper::data('Informasi Citra Unggahan Poliklinik', $dynamicAttributes);
            }
            if ($req->jenis_modal === "lihat_informasi") {
                $model = new Poliklinik();
                $tableName = $this->determineTableName($req->jenis_poli);
                if (!$tableName) return ResponseHelper::error('Invalid location.');
                $model->setTableName($tableName);
                if ($req->jenis_poli !== "farmingham_score") {
                    $informasi_poliklik = $model->where('id', $req->id_trx_poli)->first();
                }else{
                    $informasi_poliklik = $model->where('transaksi_id', $req->id_trx_poli)->first();
                }
                $dynamicAttributes = [
                    'data' => $informasi_poliklik,
                ];
                return ResponseHelper::data('Informasi Poliklinik', $dynamicAttributes);
            }
            if ($req->jenis_modal === "ubah_informasi") {
                $model = new Poliklinik();
                $tableName = $this->determineTableName($req->jenis_poli);
                if (!$tableName) return ResponseHelper::error('Invalid location.');
                $model->setTableName($tableName);
                $query = $model->leftJoin('mcu_poli_citra', 'mcu_poli_citra.id_trx_poli', '=', "$tableName.id")
                    ->select("$tableName.*", 'mcu_poli_citra.*', 'mcu_poli_citra.id as id_each_citra');
                if ($req->jenis_poli !== "farmingham_score") {
                    $query->where("$tableName.id", $req->id_trx_poli)
                    ->where('mcu_poli_citra.jenis_poli', 'poli_' . $req->jenis_poli);
                }else{
                    $query->where("$tableName.transaksi_id", $req->id_trx_poli);
                }
                $informasi_poliklik = $query->get();
                $dataWithFoto = collect($informasi_poliklik);
                if ($req->jenis_poli !== "farmingham_score") {
                    $dataWithFoto = $dataWithFoto->map(function ($item) {
                        $item->data_foto = url(env('APP_VERSI_API') . "/file/unduh_citra_poliklinik?jenis_poli=" . $item->jenis_poli . "&file_name=" . $item->nama_file);
                        return $item;
                    });
                }
                $dynamicAttributes = [
                    'data' => $dataWithFoto,
                ];
                return ResponseHelper::data('Informasi Poliklinik', $dynamicAttributes);
            }
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function hapus_citra_unggahan_poliklinik(Request $req, PoliklinikServices $poliklinikServices)
    {
        try {
            $validator = Validator::make($req->all(),[
                'id_trx_poli' => 'required',
                'transaksi_id' => 'required',
                'nama_peserta' => 'required',
                'nomor_mcu' => 'required',
                'jenis_poli' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $data = $req->all();
            $poliklinikServices->hapusCitraUnggahanPoliklinik($data);
            return ResponseHelper::data('Informasi Citra Unggahan Poliklinik berhasil dihapus', []);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function hapus_foto_unggahan_satuan(Request $req)
    {
        try {
            $validator = Validator::make($req->all(),[
                'id_each_citra' => 'required',
                'nama_file_asli' => 'required',
                'jenis_poli' => 'required',
                'id_trx_poli' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $total_citra = UnggahanCitra::where('id_trx_poli', $req->id_trx_poli)->where('jenis_poli', 'poli_'.$req->jenis_poli)->count();
            if ($total_citra == 1) {
                return ResponseHelper::data_conflict('Tidak dapat menghapus citra terakhir. Jika ingin menghapus citra ini silahkan hapus transaksi poliklinik terlebih dahulu atau tambahkan citra 1 lagi kemudian hapus citra ini');
            }
            $hapus_foto = UnggahanCitra::where('id', $req->id_each_citra)->first();
            Storage::disk('public')->delete('mcu/poliklinik/' . str_replace('poli_', '', $hapus_foto->jenis_poli) . '/' . $hapus_foto->nama_file);
            $dynamicAttributes = [
                'data' => $hapus_foto,
            ];
            $hapus_foto->delete();
            $hapus_foto = UnggahanCitra::where('id_trx_poli', $req->id_trx_poli)->where('jenis_poli', 'poli_'.$req->jenis_poli)->first();
            if (!$hapus_foto) {
                $model = new Poliklinik();
                $tableName = $this->determineTableName($req->jenis_poli);
                if (!$tableName) return ResponseHelper::error('Invalid location.');
                $model->setTableName($tableName);
                $model->where('id', $req->id_trx_poli)->delete();
                $dynamicAttributes['refresh'] = true;
            }
            return ResponseHelper::data('Informasi Citra Unggahan Poliklinik berhasil dihapus', $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
}
