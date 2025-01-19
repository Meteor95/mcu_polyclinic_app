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
        $datatransaksi = Transaksi::where('user_id', $user_id_petugas)
            ->where('status_peserta', 'proses')
            ->first();
        if ($datatransaksi) {
            throw new \Exception("Pasien dengan Nama ".$data['nama_peserta']." sudah melakukan pendaftaran dengan status PROSES dan belum selesai. Silahkan cek kembali pada menu pasien atau pilih peserta lainnya");
        }
        return DB::transaction(function () use ($data, $user_id_petugas, $file) {
            $kodeperusahaan = Perusahaan::where('id', $data['perusahaan_id'])->first()->company_code;
            $kodepdepartemen = DepartemenPerusahaan::where('id', $data['departemen_id'])->first()->kode_departemen;
            $baseCount = Transaksi::count() + 1;
            $paket_mcu = PaketMCU::find($data['id_paket_mcu']);
            $dataMember = [
                'nomor_identitas' => $data['nomor_identitas'],
                'nama_peserta' => $data['nama_peserta'],
                'tempat_lahir' => $data['tempat_lahir'],
                'tanggal_lahir' => Carbon::parse($data['tanggal_lahir_peserta'])->format('Y-m-d'),
                'tipe_identitas' => $data['tipe_identitas'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'alamat' => $data['alamat'],
                'status_kawin' => $data['status_kawin'],
                'no_telepon' => $data['no_telepon'],
                'email' => $data['email'],
            ];
            $member = MemberMCU::where('nomor_identitas', $data['nomor_identitas'])->first();
            if(!$member){
                $member = MemberMCU::create($dataMember);
            }else{
                $member->update($dataMember);
            }
            $nomor_transaksi_mcu = str_pad($baseCount + 1, 4, '0', STR_PAD_LEFT);
            $nomor_transaksi_mcu = $nomor_transaksi_mcu . "/MCU/" . $kodeperusahaan . "-" . $kodepdepartemen . "/AMC/" . $this->convertToRoman(date('m')) . "/" . date('Y');
            $parts = explode('|', $data['id_paket_mcu']);
            $dataToInsert = [
                'no_transaksi' => $nomor_transaksi_mcu,
                'tanggal_transaksi' => Carbon::parse($data['tanggal_transaksi']." ".Carbon::now()->format('H:i:s'))->format('Y-m-d H:i:s'),
                'user_id' => $member->id,
                'perusahaan_id' => $data['perusahaan_id'],
                'departemen_id' => $data['departemen_id'],
                'proses_kerja' => json_encode($data['proses_kerja']),
                'id_paket_mcu' => $parts[0],
                'petugas_id' => $user_id_petugas,
                'jenis_transaksi_pendaftaran' => $data['jenis_transaksi_pendaftaran'],
                'status_peserta' => 'proses',
            ];
            if (filter_var($data['isedit'], FILTER_VALIDATE_BOOLEAN)) {
                $transaksi = Transaksi::where('no_transaksi', $data['no_transaksi'])->first();
                $transaksi->update($dataToInsert);
            }else{
                $transaksi = Transaksi::create($dataToInsert);
            }
        });
    }
}

