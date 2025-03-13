<?php

namespace App\Services;

use Illuminate\Support\Facades\{DB, Hash, Storage};
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Pendaftaran\Peserta;
use App\Models\Transaksi\{LingkunganKerjaPeserta, RiwayatKebiasaanHidup, RiwayatPenyakitKeluarga, RiwayatImunisasi, RiwayatPenyakitTerdahulu};
use App\Models\PemeriksaanFisik\TandaVital;
use Exception;
use Illuminate\Support\Facades\Log;
class RegistrationMCUServices
{
    function handleTransactionDeletePeserta($data)
    {
        return Peserta::where('no_pemesanan', '=', $data['no_pemesanan'])->delete();
    }
    public function handleTransactionInsertLingkunganKerjaPeserta($request)
    {
        return DB::transaction(function () use ($request) {
            $bulkData = [];
            $userIds = array_column($request->informasi_riwayat_lingkungan_kerja, 'user_id');
            $transaksiId = array_column($request->informasi_riwayat_lingkungan_kerja, 'transaksi_id');
            $existingRecords = LingkunganKerjaPeserta::whereIn('user_id', $userIds)->whereIn('transaksi_id', $transaksiId)->exists();
            if ($existingRecords) {
                LingkunganKerjaPeserta::whereIn('user_id', $userIds)->whereIn('transaksi_id', $transaksiId)->delete();
            }
            foreach ($request->informasi_riwayat_lingkungan_kerja as $item) {
                $bulkData[] = [
                    'user_id' => $item['user_id'],
                    'transaksi_id' => $item['transaksi_id'],
                    'id_atribut_lk' => $item['id_atribut_lk'],
                    'nama_atribut_saat_ini' => $item['nama_atribut_saat_ini'],
                    'status' => $item['status'],
                    'nilai_jam_per_hari' => $item['nilai_jam_per_hari'],
                    'nilai_selama_x_tahun' => $item['nilai_selama_x_tahun'],
                    'keterangan' => $item['keterangan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            LingkunganKerjaPeserta::insert($bulkData);
            return true;
        });
    }
    public function handleTransactionInsertKebiasaanHidupPeserta($request)
    {
        return DB::transaction(function () use ($request) {
            $bulkData = [];
            $userIds = array_column($request->data, 'user_id');
            $transaksiId = array_column($request->data, 'transaksi_id');
            $existingRecords = RiwayatKebiasaanHidup::whereIn('user_id', $userIds)->whereIn('transaksi_id', $transaksiId)->exists();
            if ($existingRecords) {
                RiwayatKebiasaanHidup::whereIn('user_id', $userIds)->whereIn('transaksi_id', $transaksiId)->delete();
            }
            foreach ($request->data as $item) {
                $bulkData[] = [
                    'user_id' => $item['user_id'],
                    'transaksi_id' => $item['transaksi_id'],
                    'id_atribut_kb' => $item['id_atribut_kb'],
                    'nama_kebiasaan' => $item['nama_kebiasaan'],
                    'status_kebiasaan' => $item['status_kebiasaan'],
                    'nilai_kebiasaan' => $item['nilai_kebiasaan'],
                    'waktu_kebiasaan' => $item['waktu_kebiasaan'],
                    'satuan_kebiasaan' => $item['satuan_kebiasaan'],
                    'jenis_kebiasaan' => $item['jenis_kebiasaan'],
                    'keterangan' => $item['keterangan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            RiwayatKebiasaanHidup::insert($bulkData);
            if ($request->data_perempuan > 0) {
                $bulkDataPerempuan = [];
                foreach ($request->data_perempuan as $item) {
                    $tanggal = Carbon::parse($item['waktu_kebiasaan'])->format('Y-m-d').' '.Carbon::parse(Carbon::now())->format('H:i:s');
                    $bulkDataPerempuan[] = [
                        'user_id' => $item['user_id'],
                        'transaksi_id' => $item['transaksi_id'],
                        'id_atribut_kb' => $item['id_atribut_kb'],
                        'nama_kebiasaan' => $item['nama_kebiasaan'],
                        'status_kebiasaan' => $item['status_kebiasaan'],
                        'nilai_kebiasaan' => $item['nilai_kebiasaan'],
                        'waktu_kebiasaan' => $item['waktu_kebiasaan'] == "" ? null : $tanggal,
                        'satuan_kebiasaan' => $item['satuan_kebiasaan'],
                        'jenis_kebiasaan' => $item['jenis_kebiasaan'],
                        'keterangan' => $item['keterangan'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                RiwayatKebiasaanHidup::insert($bulkDataPerempuan);
            }
        });
    }
    public function handleTransactionInsertRiwayatPenyakitKeluargaPeserta($request)
    {
        return DB::transaction(function () use ($request) {
            $bulkData = [];
            $userIds = array_column($request->informasi_riwayat_penyakit_keluarga, 'user_id');
            $transaksiId = array_column($request->informasi_riwayat_penyakit_keluarga, 'transaksi_id');
            $existingRecords = RiwayatPenyakitKeluarga::whereIn('user_id', $userIds)->whereIn('transaksi_id', $transaksiId)->exists();
            if ($existingRecords) {
                RiwayatPenyakitKeluarga::whereIn('user_id', $userIds)->whereIn('transaksi_id', $transaksiId)->delete();
            }
            foreach ($request->informasi_riwayat_penyakit_keluarga as $item) {
                $bulkData[] = [
                    'user_id' => $item['user_id'],
                    'transaksi_id' => $item['transaksi_id'],
                    'id_atribut_pk' => $item['id_atribut_pk'],
                    'nama_atribut_saat_ini' => $item['nama_atribut_saat_ini'],
                    'status' => $item['status'],
                    'keterangan' => $item['keterangan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            RiwayatPenyakitKeluarga::insert($bulkData);
            return true;
        });
    }
    public function handleTransactionInsertImunisasiPeserta($request)
    {
        return DB::transaction(function () use ($request) {
            $bulkData = [];
            $userIds = array_column($request->informasi_imunisasi, 'user_id');
            $transaksiId = array_column($request->informasi_imunisasi, 'transaksi_id');
            $existingRecords = RiwayatImunisasi::whereIn('user_id', $userIds)->whereIn('transaksi_id', $transaksiId)->exists();
            if ($existingRecords) {
                RiwayatImunisasi::whereIn('user_id', $userIds)->whereIn('transaksi_id', $transaksiId)->delete();
            }
            foreach ($request->informasi_imunisasi as $item) {
                $bulkData[] = [
                    'user_id' => $item['user_id'],
                    'transaksi_id' => $item['transaksi_id'],
                    'id_atribut_im' => $item['id_atribut_im'],
                    'nama_atribut_saat_ini' => $item['nama_atribut_saat_ini'],
                    'status' => $item['status'],
                    'keterangan' => $item['keterangan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }   
            RiwayatImunisasi::insert($bulkData);
            return true;
        });
    }
    public function handleTransactionInsertRiwayatPenyakitTerdahuluPeserta($request)
    {
        return DB::transaction(function () use ($request) {
            $bulkData = [];
            $userIds = array_column($request->informasi_penyakit_terdahulu, 'user_id');
            $transaksiId = array_column($request->informasi_penyakit_terdahulu, 'transaksi_id');
            $existingRecords = RiwayatPenyakitTerdahulu::whereIn('user_id', $userIds)->whereIn('transaksi_id', $transaksiId)->exists();
            if ($existingRecords) {
                RiwayatPenyakitTerdahulu::whereIn('user_id', $userIds)->whereIn('transaksi_id', $transaksiId)->delete();
            }
            foreach ($request->informasi_penyakit_terdahulu as $item) {
                $bulkData[] = [
                    'user_id' => $item['user_id'],
                    'transaksi_id' => $item['transaksi_id'],
                    'id_atribut_pt' => $item['id_atribut_pt'],
                    'nama_atribut_saat_ini' => $item['nama_atribut_saat_ini'],
                    'status' => $item['status'],
                    'keterangan' => $item['keterangan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }   
            RiwayatPenyakitTerdahulu::insert($bulkData);
            return true;
        });
    }
    public function handleTransactionInsertTandaVitalPeserta($request)
    {
        return DB::transaction(function () use ($request) {
            $bulkData = [];
            $userIds = array_column($request->informasi_tanda_vital, 'user_id');
            $transaksiId = array_column($request->informasi_tanda_vital, 'transaksi_id');
            $existingRecords = TandaVital::whereIn('user_id', $userIds)->whereIn('transaksi_id', $transaksiId)->exists();
            if ($existingRecords) {
                TandaVital::whereIn('user_id', $userIds)->whereIn('transaksi_id', $transaksiId)->delete();
            }
            foreach ($request->informasi_tanda_vital as $item) {
                $bulkData[] = [
                    'user_id' => $item['user_id'],
                    'transaksi_id' => $item['transaksi_id'],
                    'id_atribut_lv' => $item['id_atribut_lv'],
                    'nama_atribut_saat_ini' => $item['nama_atribut_saat_ini'],
                    'nilai_tanda_vital' => $item['nilai_tanda_vital'],
                    'satuan_tanda_vital' => $item['satuan_tanda_vital'],
                    'keterangan_tanda_vital' => $item['keterangan_tanda_vital'],
                    'jenis_tanda_vital' => $item['jenis_tanda_vital'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            TandaVital::insert($bulkData);
            return true;
        });
    }
}
