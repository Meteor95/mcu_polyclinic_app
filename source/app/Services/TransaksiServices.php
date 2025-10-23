<?php

namespace App\Services;

use Illuminate\Support\Facades\{DB, Hash, Storage};
use Carbon\Carbon;
use App\Models\Masterdata\{MemberMCU,DepartemenPerusahaan};
use App\Models\Transaksi\Transaksi;
use App\Models\Pendaftaran\Peserta;
use App\Models\{PaketMCU,Perusahaan};
use App\Helpers\ResponseHelper;
use App\Models\Transaksi\{LingkunganKerjaPeserta, RiwayatKecelakaanKerja, RiwayatKebiasaanHidup, RiwayatPenyakitKeluarga, RiwayatImunisasi, RiwayatPenyakitTerdahulu};
use Illuminate\Support\Facades\Log;

use Exception;

class TransaksiServices
{
    public function convertToRoman($number)
    {
        $number = (int) $number;
        if ($number < 1 || $number > 12) {
            return ''; 
        }
        $map = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        return $map[$number - 1];
    }

    public function handleTransactionPeserta(array $data, $user_id_petugas, $file)
    {
        $member = MemberMCU::firstOrCreate(
            ['nomor_identitas' => $data['nomor_identitas']],
            [
                'nama_peserta' => $data['nama_peserta'],
                'tempat_lahir' => $data['tempat_lahir'],
                'tanggal_lahir' => Carbon::parse($data['tanggal_lahir_peserta'])->format('Y-m-d'),
                'tipe_identitas' => $data['tipe_identitas'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'alamat' => $data['alamat'],
                'status_kawin' => $data['status_kawin'],
                'no_telepon' => $data['no_telepon'],
                'email' => $data['email'],
            ]
        );
        // Jika bukan edit, pastikan tidak ada transaksi PROSES
        if (!filter_var($data['isedit'], FILTER_VALIDATE_BOOLEAN)) {
            $existing = Transaksi::where('user_id', $member->id)
                ->where('status_peserta', 'proses')
                ->first();
            Log::info($existing);
            if ($existing) {
                return false;
            }
        }
        DB::transaction(function () use ($data, $member, $user_id_petugas) {
            $kodeperusahaan = Perusahaan::find($data['perusahaan_id'])->company_code;
            $kodepdepartemen = DepartemenPerusahaan::find($data['departemen_id'])->kode_departemen;

            $baseCount = Transaksi::count() + 1;
            $nomor_transaksi_mcu = str_pad($baseCount, 4, '0', STR_PAD_LEFT);
            $nomor_transaksi_mcu .= "/MCU/{$kodeperusahaan}-{$kodepdepartemen}/AMC/" . $this->convertToRoman(date('m')) . "/" . date('Y');

            $parts = explode('|', $data['id_paket_mcu']);

            $dataToInsert = [
                'no_transaksi' => $nomor_transaksi_mcu,
                'tanggal_transaksi' => Carbon::parse($data['tanggal_transaksi'] . ' ' . Carbon::now()->format('H:i:s')),
                'user_id' => $member->id,
                'perusahaan_id' => $data['perusahaan_id'],
                'departemen_id' => $data['departemen_id'],
                'proses_kerja' => json_encode($data['proses_kerja']),
                'id_paket_mcu' => $parts[0],
                'petugas_id' => $user_id_petugas,
                'jenis_transaksi_pendaftaran' => $data['jenis_transaksi_pendaftaran'],
                'tipe_mcu_peserta' => $data['tipe_mcu_peserta'],
                'status_peserta' => 'proses',
            ];

            if (filter_var($data['isedit'], FILTER_VALIDATE_BOOLEAN)) {
                $transaksi = Transaksi::find($data['id_detail_transaksi_mcu']);
                $transaksi->update($dataToInsert);
            } else {
                $transaksi = Transaksi::create($dataToInsert);
            }

            // Jika data berasal dari pendaftaran mandiri, insert lingkungan kerja
            $dari_pendaftaran_mandiri = Peserta::where('nomor_identifikasi', $data['nomor_identitas'])->first();
            if ($dari_pendaftaran_mandiri) {
                LingkunganKerjaPeserta::create([
                    'user_id' => $member->id,
                    'transaksi_id' => $transaksi->id,
                    'id_atribut_lk' => $data['id_atribut_lk'],
                    'nama_atribut_saat_ini' => $data['nama_atribut_saat_ini'],
                    'status' => $data['status'],
                    'nilai_jam_per_hari' => $data['nilai_jam_per_hari'],
                    'nilai_selama_x_tahun' => $data['nilai_selama_x_tahun'],
                    'keterangan' => $data['keterangan'],
                ]);
            }
        });
        Log::info("TRUUEEEE");
        return true;
    }

}

