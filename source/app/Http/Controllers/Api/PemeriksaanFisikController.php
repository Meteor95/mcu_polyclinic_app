<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper;
use App\Models\PemeriksaanFisik\{TingkatKesadaran, TandaVital, Penglihatan};
use App\Models\PemeriksaanFisik\KondisiFisik\{KondisiFisik,Gigi};
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
    /* kondisi Fisik */
    private function determineTableName($lokasiFisik)
    {
        $tables = [
            'kepala' => 'mcu_pf_kepala',
            'telinga' => 'mcu_pf_telinga',
            'mata' => 'mcu_pf_mata',
            'tenggorokan' => 'mcu_pf_tenggorokan',
            'mulut' => 'mcu_pf_mulut',
            'gigi' => 'mcu_pf_gigi',
            'leher' => 'mcu_pf_leher',
            'thorax' => 'mcu_pf_thorax',
            'abdomen_urogenital' => 'mcu_pf_abdomen_urogenital',
            'anorectal_genital' => 'mcu_pf_anorectal_genital',
            'ekstremitas' => 'mcu_pf_ekstremitas',
            'neurologis' => 'mcu_pf_neurologis',
        ];
        return $tables[strtolower($lokasiFisik)] ?? null;
    }

    private function validateRequest($request, $rules)
    {
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $dynamicAttributes = ['errors' => $validator->errors()];
            return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
        }
        return null;
    }
    public function simpan_kondisi_fisik(Request $request)
    {
        try {
            $validationError = $this->validateRequest($request, [
                'kondisi_fisik' => 'required|array',
            ]);
            if ($validationError) return $validationError;

            $data = collect($request->kondisi_fisik)->map(function ($kondisi) {
                return [
                    'user_id' => $kondisi['user_id'],
                    'transaksi_id' => $kondisi['transaksi_id'],
                    'id_atribut' => $kondisi['id_atribut'],
                    'nama_atribut' => $kondisi['nama_atribut'],
                    'kategori_atribut' => $kondisi['kategori_atribut'],
                    'jenis_atribut' => $kondisi['jenis_atribut'],
                    'status_atribut' => $kondisi['status_atribut'] === 'abnormal' ? "abnormal" : "normal",
                    'keterangan_atribut' => $kondisi['keterangan_atribut'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();
            $model = new KondisiFisik();
            $tableName = $this->determineTableName($request->kondisi_fisik[0]['nama_atribut']);
            if (!$tableName) return ResponseHelper::error('Invalid attribute name.');

            $model->setTableName($tableName);

            $isEdit = filter_var($request->isedit, FILTER_VALIDATE_BOOLEAN);
            $query = $model->newQuery()->where('user_id', $request->kondisi_fisik[0]['user_id'])
                ->where('transaksi_id', $request->kondisi_fisik[0]['transaksi_id']);

            if ($isEdit) {
                $query->delete();
            } else {
                if ($query->exists()) {
                    return ResponseHelper::data_conflict('Data kondisi fisik sudah ada. Silahkan ubah atau hapus data sebelumnya.');
                }
            }

            $donesave = $model->newQuery()->insert($data);
            if(strtolower($request->kondisi_fisik[0]['nama_atribut']) === 'gigi' && $donesave){
                $this->simpan_gigi_lokasi($request);
            }
            return ResponseHelper::success('Data kondisi fisik berhasil disimpan.');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }

    public function daftar_kondisi_fisik(Request $request)
    {
        try {
            $model = new KondisiFisik();
            $tableName = $this->determineTableName($request->lokasi_fisik);
            if (!$tableName) return ResponseHelper::error('Invalid location.');

            $model->setTableName($tableName);

            $perHalaman = max((int)$request->length, 1);
            $offset = (int)($request->start / $perHalaman) * $perHalaman;

            $datatabel = $model->listKondisiFisik($request, $perHalaman, $offset);
            $dynamicAttributes = [
                'data' => $datatabel['data'],
                'recordsFiltered' => $datatabel['total'],
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

    public function hapus_kondisi_fisik(Request $request)
    {
        try {
            $validationError = $this->validateRequest($request, [
                'user_id' => 'required',
                'transaksi_id' => 'required',
                'nama_peserta' => 'required',
            ]);
            if ($validationError) return $validationError;

            $model = new KondisiFisik();
            $tableName = $this->determineTableName($request->lokasi_fisik);
            if (!$tableName) return ResponseHelper::error('Invalid location.');

            $model->setTableName($tableName);
            $model->newQuery()->where('user_id', $request->user_id)
                ->where('transaksi_id', $request->transaksi_id)
                ->delete();

            return ResponseHelper::success('Data kondisi fisik berhasil dihapus.');
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }

    public function get_kondisi_fisik(Request $request)
    {
        try {
            $model = new KondisiFisik();
            $tableName = $this->determineTableName($request->lokasi_fisik);
            if (!$tableName) return ResponseHelper::error('Invalid location.');

            $model->setTableName($tableName);
            $data = $model->newQuery()->where('user_id', $request->user_id)
                ->where('transaksi_id', $request->transaksi_id)
                ->get();

            $dynamicAttributes = ['data' => $data];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Kondisi Fisik']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    private function simpan_gigi_lokasi($request){
        try {
            $data = [
                'user_id' => $request->kondisi_fisik[0]['user_id'],
                'transaksi_id' => $request->kondisi_fisik[0]['transaksi_id'],
                'atas_kanan_8' => $request->kondisi_fisik_tambahan[0]['atas_kanan_8'],
                'atas_kanan_7' => $request->kondisi_fisik_tambahan[0]['atas_kanan_7'],
                'atas_kanan_6' => $request->kondisi_fisik_tambahan[0]['atas_kanan_6'],
                'atas_kanan_5' => $request->kondisi_fisik_tambahan[0]['atas_kanan_5'],
                'atas_kanan_4' => $request->kondisi_fisik_tambahan[0]['atas_kanan_4'],
                'atas_kanan_3' => $request->kondisi_fisik_tambahan[0]['atas_kanan_3'],
                'atas_kanan_2' => $request->kondisi_fisik_tambahan[0]['atas_kanan_2'],
                'atas_kanan_1' => $request->kondisi_fisik_tambahan[0]['atas_kanan_1'],
                'atas_kiri_1' => $request->kondisi_fisik_tambahan[0]['atas_kiri_1'],
                'atas_kiri_2' => $request->kondisi_fisik_tambahan[0]['atas_kiri_2'],
                'atas_kiri_3' => $request->kondisi_fisik_tambahan[0]['atas_kiri_3'],
                'atas_kiri_4' => $request->kondisi_fisik_tambahan[0]['atas_kiri_4'],
                'atas_kiri_5' => $request->kondisi_fisik_tambahan[0]['atas_kiri_5'],
                'atas_kiri_6' => $request->kondisi_fisik_tambahan[0]['atas_kiri_6'],
                'atas_kiri_7' => $request->kondisi_fisik_tambahan[0]['atas_kiri_7'],
                'atas_kiri_8' => $request->kondisi_fisik_tambahan[0]['atas_kiri_8'],
                'bawah_kanan_8' => $request->kondisi_fisik_tambahan[0]['bawah_kanan_8'],
                'bawah_kanan_7' => $request->kondisi_fisik_tambahan[0]['bawah_kanan_7'],
                'bawah_kanan_6' => $request->kondisi_fisik_tambahan[0]['bawah_kanan_6'],
                'bawah_kanan_5' => $request->kondisi_fisik_tambahan[0]['bawah_kanan_5'],
                'bawah_kanan_4' => $request->kondisi_fisik_tambahan[0]['bawah_kanan_4'],
                'bawah_kanan_3' => $request->kondisi_fisik_tambahan[0]['bawah_kanan_3'],
                'bawah_kanan_2' => $request->kondisi_fisik_tambahan[0]['bawah_kanan_2'],
                'bawah_kanan_1' => $request->kondisi_fisik_tambahan[0]['bawah_kanan_1'],
                'bawah_kiri_1' => $request->kondisi_fisik_tambahan[0]['bawah_kiri_1'],
                'bawah_kiri_2' => $request->kondisi_fisik_tambahan[0]['bawah_kiri_2'],
                'bawah_kiri_3' => $request->kondisi_fisik_tambahan[0]['bawah_kiri_3'],
                'bawah_kiri_4' => $request->kondisi_fisik_tambahan[0]['bawah_kiri_4'],
                'bawah_kiri_5' => $request->kondisi_fisik_tambahan[0]['bawah_kiri_5'],
                'bawah_kiri_6' => $request->kondisi_fisik_tambahan[0]['bawah_kiri_6'],
                'bawah_kiri_7' => $request->kondisi_fisik_tambahan[0]['bawah_kiri_7'],
                'bawah_kiri_8' => $request->kondisi_fisik_tambahan[0]['bawah_kiri_8'],
            ];
            $existingData = Gigi::where('user_id', $request->kondisi_fisik[0]['user_id'])
                ->where('transaksi_id', $request->kondisi_fisik[0]['transaksi_id'])
                ->first();
            if ($existingData) {
                Gigi::where('user_id', $request->kondisi_fisik[0]['user_id'])
                    ->where('transaksi_id', $request->kondisi_fisik[0]['transaksi_id'])
                    ->update($data);
            }else{
                Gigi::create($data);
            }
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
    public function get_kondisi_fisik_gigi(Request $request){
        try {
            $data = Gigi::where('user_id', $request->user_id)
                ->where('transaksi_id', $request->transaksi_id)
                ->first();
            $dynamicAttributes = [  
                'data' => $data,
            ];
            return ResponseHelper::data(__('common.data_ready', ['namadata' => 'Informasi Kondisi Fisik Lokasi Gigi']), $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
}
