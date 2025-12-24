<?php

namespace App\Services;

use Illuminate\Support\Facades\{DB, Hash, Storage};
use Carbon\Carbon;
use App\Models\Masterdata\{MemberMCU,DepartemenPerusahaan};
use App\Models\Laboratorium\{Transaksi as TransaksiLab, TransaksiDetail};
use App\Models\Transaksi\Transaksi;
use App\Models\Pendaftaran\Peserta;
use App\Models\{PaketMCU,Perusahaan};
use App\Helpers\ResponseHelper;
use App\Models\EndUser\Formulir;
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

    public function handleTransactionPeserta(array $data, $user_id_petugas, $file, $req)
    {
        $nomor_transaksi_mcu_db = "";$id_transaksi_mcu_db = "";
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
        DB::transaction(function () use ($data, $member, $user_id_petugas, $req) {
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
                $id_transaksi_mcu_db = $transaksi->id;
                $nomor_transaksi_mcu_db = $transaksi->no_transaksi;
                TransaksiLab::where('no_nota', $nomor_transaksi_mcu_db)->forceDelete();
            } else {
                $transaksi = Transaksi::create($dataToInsert);
                $id_transaksi_mcu_db = $transaksi->id;
                $nomor_transaksi_mcu_db = $transaksi->no_transaksi;
            }
            //by pass transaksi saat pendaftara dan langsung muncul konfirmasi bayar
            $paket_mcu = PaketMCU::find($parts[0]);
            $userDetails = $req->get('user_details');
            $data_transaksi = [
                'no_mcu' => $id_transaksi_mcu_db,
                'no_nota' => $nomor_transaksi_mcu_db,
                'waktu_trx' => Carbon::now()->format('Y-m-d H:i:s'),
                'waktu_trx_sample' => Carbon::now()->format('Y-m-d H:i:s'),
                'id_dokter' => $userDetails->id,
                'nama_dokter' => $userDetails->nama_pegawai,
                'id_pj' => $userDetails->id,
                'nama_pj' => $userDetails->nama_pegawai,
                'total_bayar' => $paket_mcu->harga_paket,
                'total_transaksi' => $paket_mcu->harga_paket,
                'total_tindakan' => 0,
                'jenis_transaksi' => 0,
                'metode_pembayaran' => '',
                'id_kasir' => $userDetails->id,
                'status_pembayaran' => 'process',
                'jenis_layanan' => 'MCU',
                'nama_file_surat_pengantar' => "",
                'is_paket_mcu' => $paket_mcu->id,
                'nama_paket_mcu' => $paket_mcu->nama_paket,
                'nominal_apotek' => 0,
                'lampirkan_berkas_pdf' => 0,
            ];
            $hasil_query_tranaksi = TransaksiLab::create($data_transaksi);
            $displayTextPaket = ($parts[2] ?? null) ?  $parts[2] : $data['jenis_transaksi_pendaftaran'];
            $data_tindakan[] = [
                'id_transaksi' => $hasil_query_tranaksi->id,
                'id_item' => 1,
                'kode_item' => '1000001000001',
                'nama_item' => "Paket MCU ".$displayTextPaket,
                'nilai_tindakan' => 1,
                'harga' => $paket_mcu->harga_paket,
                'diskon' => 0,
                'harga_setelah_diskon' => $paket_mcu->harga_paket,
                'jumlah' => 1,
                'keterangan' => "PAKET MCU",
                'meta_data_kuantitatif' => "{}",
                'meta_data_kualitatif' => "{}",
                'meta_data_jasa' => "{}",
                'meta_data_jasa_fee' => "{}",
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ];
            TransaksiDetail::insert($data_tindakan);
            // Jika data berasal dari pendaftaran mandiri, insert lingkungan kerja
            $dari_pendaftaran_mandiri_lingkungan_kerja = Peserta::where('nomor_identifikasi', $data['nomor_identitas'])->first();
            if ($dari_pendaftaran_mandiri_lingkungan_kerja) {
                $lingkunganKerja = json_decode($dari_pendaftaran_mandiri_lingkungan_kerja->json_lingkungan_kerja, true);
                $kecelakaanKerja = json_decode($dari_pendaftaran_mandiri_lingkungan_kerja->json_kecelakaan_kerja, true);
                $kebiasaanHidup  = json_decode($dari_pendaftaran_mandiri_lingkungan_kerja->json_kebiasaan_hidup, true);
                $penyakitTerdahulu = json_decode($dari_pendaftaran_mandiri_lingkungan_kerja->json_penyakit_terdahulu, true);
                $penyakitKeluarga  = json_decode($dari_pendaftaran_mandiri_lingkungan_kerja->json_penyakit_keluarga, true);
                $imunisasi = json_decode($dari_pendaftaran_mandiri_lingkungan_kerja->json_imunisasi, true);
                foreach ($lingkunganKerja['lingkungan_kerja'] as $lk) {
                    LingkunganKerjaPeserta::create([
                        'user_id' => $member->id,
                        'transaksi_id' => $id_transaksi_mcu_db,
                        'id_atribut_lk' => $lk['id_atribut_lk'],
                        'nama_atribut_saat_ini' => $lk['nama_atribut_lk'],
                        'status' => $lk['status'],
                        'nilai_jam_per_hari' => $lk['jam_per_hari'],
                        'nilai_selama_x_tahun' => $lk['selama_x_tahun'],
                        'keterangan' => $lk['keterangan'] ?? null,
                    ]);
                }
                RiwayatKecelakaanKerja::create([
                    'user_id' => $member->id,
                    'transaksi_id' => $id_transaksi_mcu_db,
                    'riwayat_kecelakaan_kerja' => $kecelakaanKerja['informasi_kecelakaan_kerja'] ?? null,
                ]);
                foreach ($kebiasaanHidup['kebiasaan_hidup'] as $kb) {
                    RiwayatKebiasaanHidup::create([
                        'user_id' => $member->id,
                        'transaksi_id' => $id_transaksi_mcu_db,
                        'id_atribut_kb' => $kb['id_atribu_kb'],
                        'nama_kebiasaan' => trim($kb['nama_atribut_kb']),
                        'status_kebiasaan' => $kb['status'],
                        'nilai_kebiasaan' => $kb['nilai'],
                        'satuan_kebiasaan' => trim($kb['info']),
                    ]);
                }
               foreach ($penyakitTerdahulu['penyakit_terdahulu'] as $pt) {
                    RiwayatPenyakitTerdahulu::create([
                        'user_id' => $member->id,
                        'transaksi_id' => $id_transaksi_mcu_db,
                        'id_atribut_pt' => $pt['id_atribut_penyakit_terdahulu'],
                        'nama_atribut_saat_ini' => trim($pt['nama_atribut_penyakit_terdahulu']),
                        'status' => $pt['status'],
                        'keterangan' => $pt['keterangan'],
                    ]);
                }
                foreach ($penyakitKeluarga['penyakit_keluarga'] as $pk) {
                    RiwayatPenyakitKeluarga::create([
                        'user_id' => $member->id,
                        'transaksi_id' => $id_transaksi_mcu_db,
                        'id_atribut_pk' => $pk['id_atribut_penyakit_keluarga'] ?? null,
                        'nama_atribut_saat_ini' => trim($pk['nama_atribut_penyakit_keluarga']),
                        'status' => $pk['status'],
                        'keterangan' => $pk['keterangan'] ?? null,
                    ]);
                }
                foreach ($imunisasi['imunisasi'] as $im) {
                    RiwayatImunisasi::create([
                        'user_id' => $member->id,
                        'transaksi_id' => $id_transaksi_mcu_db,
                        'id_atribut_im' => $im['id_atribut_imunisasi'] ?? null,
                        'nama_atribut_saat_ini' => trim($im['nama_atribut_imunisasi']),
                        'status' => $im['status'],
                        'keterangan' => $im['keterangan'] ?? null,
                    ]);
                }
            }
            $formulir_peserta = Formulir::where('nomor_identifikasi', $data['nomor_identitas'])->first();
            if ($formulir_peserta) { $formulir_peserta->delete();}
        });
        return true;
    }
}