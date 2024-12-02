<?php

namespace App\Services;

use Illuminate\Support\Facades\{DB, Hash, Storage};
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Pendaftaran\Peserta;
use App\Models\Transaksi\LingkunganKerjaPeserta;
use Exception;

class RegistrationMCUServices
{
    function handleTransactionDeletePeserta($data)
    {
        return Peserta::where('uuid', '=', $data['id'])->delete();
    }
    public function handleTransactionInsertLingkunganKerjaPeserta($request)
    {
        return DB::transaction(function () use ($request) {
            $bulkData = [];
            $userIds = array_column($request->informasi_riwayat_lingkungan_kerja, 'user_id');
            $existingRecords = LingkunganKerjaPeserta::whereIn('user_id', $userIds)->exists();
            if ($existingRecords) {
                LingkunganKerjaPeserta::whereIn('user_id', $userIds)->delete();
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
}
