<?php

namespace App\Services;

use Illuminate\Support\Facades\{DB, Hash, Storage};
use Carbon\Carbon;
use App\Models\Masterdata\{MemberMCU,DepartemenPerusahaan};
use App\Models\Transaksi\Transaksi;
use App\Models\Pendaftaran\Peserta;
use App\Models\{PaketMCU,Perusahaan};
use App\Helpers\ResponseHelper;
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

    public function handleTransactionPeserta($data, $user_id_petugas, $file)
    {
        return DB::transaction(function () use ($data, $user_id_petugas, $file) {
            $filename = "";
            /* update data peserta jikalau dibutuhkan atau saat pendaftaran peserta baru MCU */
            if ($data['type_data_peserta'] == 1) {
                Peserta::where('nomor_identitas', $data['nomor_identitas'])->update([
                    'nama_peserta' => $data['nama_peserta'],
                    'tempat_lahir' => $data['tempat_lahir'],
                    'tanggal_lahir' => Carbon::parse($data['tanggal_lahir_peserta'])->format('Y-m-d'),
                    'tipe_identitas' => $data['tipe_identitas'],
                    'status_kawin' => $data['status_kawin'],
                    'jenis_kelamin' => $data['jenis_kelamin'],
                    'no_telepon' => $data['no_telepon'],
                    'email' => $data['email'],
                    'alamat' => $data['alamat'],
                ]);
            }
            $kodeperusahaan = Perusahaan::where('id', $data['perusahaan_id'])->first()->company_code;
            $kodepdepartemen = DepartemenPerusahaan::where('id', $data['departemen_id'])->first()->kode_departemen;
            $baseCount = Transaksi::count() + 1;
            $nomor_transaksi_mcu = str_pad($baseCount + 1, 4, '0', STR_PAD_LEFT);
            $member = MemberMCU::where('nomor_identitas', $data['nomor_identitas'])->first();
            if ($nomor_transaksi_mcu === $member->no_transaksi) {
                $nomor_transaksi_mcu = str_pad($baseCount + 2, 4, '0', STR_PAD_LEFT);
            }
            $nomor_transaksi_mcu = $nomor_transaksi_mcu . "/MCU/" . $kodeperusahaan . "-" . $kodepdepartemen . "/AMC/" . $this->convertToRoman(date('m')) . "/" . date('Y');
            if(!$member){
                /*jika tidak ada maka insert*/
                $ambildaripeserta = Peserta::where('nomor_identitas', $data['nomor_identitas'])->first();
                if($ambildaripeserta){
                    $member = MemberMCU::create([
                        'nomor_identitas' => $ambildaripeserta->nomor_identitas,
                        'nama_peserta' => $ambildaripeserta->nama_peserta,
                        'tempat_lahir' => $ambildaripeserta->tempat_lahir,
                        'tanggal_lahir' => $ambildaripeserta->tanggal_lahir,
                        'tipe_identitas' => $ambildaripeserta->tipe_identitas,
                        'jenis_kelamin' => $ambildaripeserta->jenis_kelamin,
                        'alamat' => $ambildaripeserta->alamat,
                        'status_kawin' => $ambildaripeserta->status_kawin,
                        'no_telepon' => $ambildaripeserta->no_telepon,
                        'email' => $ambildaripeserta->email,
                    ]);
                    if ($member) {
                        Peserta::where('nomor_identitas', $data['nomor_identitas'])->delete();
                    }
                }
            }
            if($file){
                $originalName = $file->getClientOriginalName();
                $sanitizedName = strtolower(preg_replace('/[\s\W_]+/', '_', $originalName));
                $timestamp = microtime(true);
                $filename = $nomor_transaksi_mcu . '_' . $sanitizedName . '_' . $timestamp . '.' . $file->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('file_surat_pengantar/', $file, $filename);
            }
            $paket_mcu = PaketMCU::find($data['id_paket_mcu']);
            $dataToInsert = [
                'no_transaksi' => $nomor_transaksi_mcu,
                'tanggal_transaksi' => Carbon::parse($data['tanggal_transaksi']." ".Carbon::now()->format('H:i:s'))->format('Y-m-d H:i:s'),
                'user_id' => $member->id,
                'perusahaan_id' => $data['perusahaan_id'],
                'departemen_id' => $data['departemen_id'],
                'proses_kerja' => json_encode($data['proses_kerja']),
                'id_paket_mcu' => $data['id_paket_mcu'],
                'harga_paket_saat_ini' => $data['nominal_bayar_konfirmasi'],
                'fitur_paket_mcu_saat_ini' => $paket_mcu->akses_poli,
                'nama_file_surat_pengantar' => $filename,
                'tipe_pembayaran' => $data['tipe_pembayaran'],
                'metode_pembayaran' => $data['metode_pembayaran'],
                'nominal_pembayaran' => $data['nominal_pembayaran'],
                'penerima_bank_id' => $data['penerima_bank'],
                'nomor_transakasi_transfer' => $data['nomor_transaksi_transfer'] ?? null,
                'petugas_id' => $user_id_petugas,
            ];
            if (filter_var($data['isedit'], FILTER_VALIDATE_BOOLEAN)) {
                unset($dataToInsert['no_transaksi']);           
                Transaksi::where('id', $data['id_detail_transaksi_mcu'])->update($dataToInsert);
            } else {
                $datatransaksi = Transaksi::whereDate('tanggal_transaksi', Carbon::parse($data['tanggal_transaksi'])->format('Y-m-d'))
                    ->where('user_id', $member->id)
                    ->first();
                if ($datatransaksi) {
                    throw new \Exception("Dalam hari ini peserta dengan Nama ".$member->nama_peserta." sudah melakukan pendaftaran. Silahkan cek kembali pada menu pasien");
                }
                Transaksi::create($dataToInsert);
            }
            
        });
    }
}

