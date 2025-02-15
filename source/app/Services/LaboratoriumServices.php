<?php

namespace App\Services;

use Illuminate\Support\Facades\{DB, Hash, Storage};
use Carbon\Carbon;
use App\Models\Laboratorium\{Transaksi , TransaksiDetail, TransaksiFee};
use App\Models\Transaksi\Transaksi as TransaksiMCU;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Str;
use App\Models\ErrorLog;
use Illuminate\Support\Facades\Log;

use Exception;

class LaboratoriumServices
{
    public function handleTransactionLaboratorium($data, $user_id, $file)
    {
        return DB::transaction(function () use ($data, $user_id, $file) {
            $filename = "";
            $jenis_layanan = TransaksiMCU::where('id',$data['no_mcu'])->first();
            $informasi_file = Transaksi::where('id', $data['id_transaksi'])->first();
            $filename = $informasi_file->nama_file_surat_pengantar ?? "";
            if($file){
                $nomor_transaksi_mcu = $jenis_layanan->no_transaksi;
                $originalName = $file->getClientOriginalName();
                $sanitizedName = strtolower(preg_replace('/[\s\W_]+/', '_', $originalName));
                $timestamp = microtime(true);
                $filename = str_replace('/','_',$nomor_transaksi_mcu) . '_' . $sanitizedName . '_' . $timestamp . '.' . $file->getClientOriginalExtension();
            }
            if (filter_var($data['is_edit_transaksi'], FILTER_VALIDATE_BOOLEAN)){
                if ($informasi_file){
                    if (($informasi_file->nama_file_surat_pengantar != $filename) && ($file)){
                        Storage::disk('public')->delete('file_surat_pengantar/' . $informasi_file->nama_file_surat_pengantar);
                    }
                    DB::table('transaksi')->where('id', $data['id_transaksi'])->delete();
                }
            }
            $keranjangTindakan = json_decode($data['keranjang_tindakan'], true);
            if($file){
                Storage::disk('public')->putFileAs('file_surat_pengantar/', $file, $filename);
            }
            $data = [
                'no_mcu' => $data['no_mcu'],
                'no_nota' => $data['no_nota'],
                'waktu_trx' => Carbon::parse($data['waktu_trx'])->format('Y-m-d H:i:s'),
                'waktu_trx_sample' => Carbon::parse($data['waktu_trx_sample'])->format('Y-m-d H:i:s'),
                'id_dokter' => $data['id_dokter'],
                'nama_dokter' => $data['nama_dokter'],
                'id_pj' => $data['id_pj'],
                'nama_pj' => $data['nama_pj'],
                'total_bayar' => $data['total_bayar'],
                'total_transaksi' => $data['total_transaksi'],
                'total_tindakan' => $data['total_tindakan'],
                'jenis_transaksi' => $data['jenis_transaksi'],
                'metode_pembayaran' => $data['metode_pembayaran'],
                'id_kasir' => $user_id,
                'status_pembayaran' => $data['status_pembayaran'],
                'jenis_layanan' => $jenis_layanan->jenis_transaksi_pendaftaran,
                'nama_file_surat_pengantar' => $filename,
                'is_paket_mcu' => $data['is_paket_mcu'],
                'nama_paket_mcu' => $data['nama_paket_mcu'],
            ];
            $hasil_query_tranaksi = Transaksi::create($data);
            foreach ($keranjangTindakan as $item) {
                $data_tindakan[] = [
                    'id_transaksi' => $hasil_query_tranaksi->id,
                    'id_item' => $item['id_item'],
                    'kode_item' => $item['kode_item'],
                    'nama_item' => $item['nama_item'],
                    'nilai_tindakan' => $item['nilai_tindakan'],
                    'harga' => $item['harga'],
                    'diskon' => $item['diskon'],
                    'harga_setelah_diskon' => $item['harga_setelah_diskon'],
                    'jumlah' => $item['jumlah'],
                    'keterangan' => $item['keterangan'],
                    'meta_data_kuantitatif' => base64_decode($item['meta_kuantitatif']) == "" ? "{}" : base64_decode($item['meta_kuantitatif']),
                    'meta_data_kualitatif' => base64_decode($item['meta_kualitatif']) == "" ? "{}" : base64_decode($item['meta_kualitatif']),
                    'meta_data_jasa' => $item['meta_jasa'] == "" ? "{}" : $item['meta_jasa'],
                    'meta_data_jasa_fee' => $item['meta_jasa_fee'] == "" ? "{}" : $item['meta_jasa_fee'],
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ];
                $meta_data_jasa = json_decode(base64_decode($item['meta_jasa']), true);
                $jsonError = json_last_error(); 
                if($jsonError !== JSON_ERROR_NONE){
                    
                }else{
                    $bulkData = [];
                    foreach ($meta_data_jasa as $key => $items) {
                        foreach ($items as $item_fee) {
                            $bulkData[] = [
                                'kode_jasa' => $item_fee['kode_jasa'],
                                'id_transaksi' => $hasil_query_tranaksi->id,
                                'id_petugas' => $item_fee['id'],
                                'nama_petugas' => $item_fee['value'],
                                'kode_item' => $item['kode_item'],
                                'nama_tindakan' => $item['nama_item'],
                                'nominal_fee' => $item_fee['jumlah_tag_terpilih'],
                                'besaran_fee' => $item_fee['harga_jasa'],
                                'deleted_at' => NULL,
                                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            ];
                        }
                    }
                    $groupedData = [];
                    foreach ($bulkData as $item) {
                        $groupedData[$item['kode_jasa']][] = $item;
                    }
                    foreach ($groupedData as $kode_jasa => $items) {
                        $totalItems = count($items);
                        foreach ($items as &$item) {
                            $item['nominal_fee'] = floor($item['besaran_fee'] / $totalItems);
                        }
                        $groupedData[$kode_jasa] = $items;
                    }
                    $updatedData = array_merge(...array_values($groupedData));
                    TransaksiFee::insert($updatedData);
                }
            }
            TransaksiDetail::insert($data_tindakan);
        });
    }
    public function handleDeleteTransactionLaboratorium($data,$user_id){
        return DB::transaction(function () use ($data,$user_id) {
            $informasi_file = Transaksi::where('id', $data['id_transaksi'])->first();
            if ($informasi_file){
                if (isset($data['hard_delete']) && filter_var($data['hard_delete'], FILTER_VALIDATE_BOOLEAN)){
                    Storage::disk('public')->delete('file_surat_pengantar/' . $informasi_file->nama_file_surat_pengantar);
                }
                Transaksi::where('id', $data['id_transaksi'])->delete(); 
            }
        });
    }
}
