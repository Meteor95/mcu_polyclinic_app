<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftaran\Peserta;
use App\Models\Transaksi\Transaksi;
use App\Models\Masterdata\DaftarBank;
use App\Models\{Perusahaan, PaketMCU};
use App\Models\Masterdata\DepartemenPerusahaan;
use App\Models\Komponen\LingkunganKerja;
use Illuminate\Support\Facades\Log;

class PendaftaranController extends Controller
{
    private function getData($req, $title, $breadcrumb) {
        return [
            'title' => $title,
            'breadcrumb' => $breadcrumb,
            'user_details' => $req->attributes->get('user_details'),
        ];
    }
    /* Area Landing Formulir Pendaftaran */
    public function formulir_pendaftaran(Request $req){
        $data = [
            'tipe_halaman' => 'landing',
            'menu_utama_aktif' => 'formulir_pendaftaran',
        ];
        return view('preregister.landingpage_form', ['data' => $data]);
    }
    /* Area Admin Pendaftaran */
    public function list_pasien(Request $req){
        $data = $this->getData($req, 'Daftar Pasien', [
            'Daftar Pasien' => route('admin.pendaftaran.daftar_pasien'),
        ]);
        return view('paneladmin.pendaftaran.daftarpasien', ['data' => $data]);
    }
    public function list_peserta(Request $req){
        $data = $this->getData($req, 'Daftar Peserta', [
            'Daftar Peserta' => route('admin.pendaftaran.daftar_peserta'),
        ]);
        return view('paneladmin.pendaftaran.daftarpeserta', ['data' => $data]);
    }
    public function add_form_patien_mcu(Request $req, $uuid = null){
        $data = $this->getData($req, 'Formulir Pendaftaran', [
            'Daftar Peserta' => route('admin.pendaftaran.daftar_peserta'),
        ]);
        if($uuid != null){
            $data['peserta'] = Peserta::where('uuid', $uuid)
                ->selectRaw('*, TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) AS umur')
                ->first();
        }else{
            $data['peserta'] = null;
        }
        $data['bank'] = DaftarBank::all();
        $data['ubah'] = "";
        $data['uuid'] = $uuid;
        return view('paneladmin.pendaftaran.formulirtambahpeserta', ['data' => $data]);
    }
    public function update_form_patien_mcu(Request $req, $uuid = null){
        $data = $this->getData($req, 'Formulir Pendaftaran', [
            'Daftar Peserta' => route('admin.pendaftaran.daftar_peserta'),
        ]);
        $datatransaksi = Transaksi::getPasienById($uuid);
        $data = array_merge($data, [
            'bank' => DaftarBank::all(),
            'ubah' => true,
            'perusahaan' => Perusahaan::find($datatransaksi->perusahaan_id),
            'departemen' => DepartemenPerusahaan::find($datatransaksi->departemen_id),
            'paket_mcu' => PaketMCU::find($datatransaksi->id_paket_mcu),
            'uuid' => $uuid,
            'peserta' => $uuid ? $datatransaksi : null,
            'proses_kerja' => json_decode($datatransaksi->proses_kerja, true),
        ]);
        return view('paneladmin.pendaftaran.formulirtambahpeserta', compact('data'));
    }
    /* Riwayat Informasi */
    public function foto_pasien(Request $req){
        $data = $this->getData($req, 'Foto Data Diri Peserta MCU', [
            'Beranda' => route('admin.beranda'),
            'Foto Data Diri Peserta MCU' => route('admin.pendaftaran.foto_pasien'),
        ]);
        return view('paneladmin.pendaftaran.riwayat_foto_pasien', ['data' => $data]);
    }
    public function lingkungan_kerja(Request $req){
        $data = $this->getData($req, 'Lingkungan Kerja Peserta MCU', [
            'Beranda' => route('admin.beranda'),
            'Lingkungan Kerja Peserta MCU' => route('admin.pendaftaran.lingkungan_kerja'),
        ]);
        $data['bahaya_paparan_kerja'] = LingkunganKerja::where('status', 1)->get();
        return view('paneladmin.pendaftaran.riwayat_lingkungan_kerja', ['data' => $data]);
    }
    public function kecelakaan_kerja(Request $req){
        $data = $this->getData($req, 'Kecelakaan Kerja Peserta MCU', [
            'Beranda' => route('admin.beranda'),
            'Kecelakaan Kerja Peserta MCU' => route('admin.pendaftaran.kecelakaan_kerja'),
        ]);
        return view('paneladmin.pendaftaran.riwayat_kecelakaan_kerja', ['data' => $data]);
    }
    public function penyakit_keluarga(Request $req){
        $data = $this->getData($req, 'Penyakit Keluarga Peserta MCU', [
            'Beranda' => route('admin.beranda'),
            'Penyakit Keluarga Peserta MCU' => route('admin.pendaftaran.penyakit_keluarga'),
        ]);
        return view('paneladmin.pendaftaran.riwayat_penyakit_keluarga', ['data' => $data]);
    }
    public function kebiasaan_hidup(Request $req){
        $data = $this->getData($req, 'Kebiasaan Hidup Peserta MCU', [
            'Beranda' => route('admin.beranda'),
            'Kebiasaan Hidup Peserta MCU' => route('admin.pendaftaran.kebiasaan_hidup'),
        ]);
        return view('paneladmin.pendaftaran.riwayat_kebiasaan_hidup', ['data' => $data]);
    }
    public function vaksinasi(Request $req){
        $data = $this->getData($req, 'Vaksinasi Peserta MCU', [
            'Beranda' => route('admin.beranda'),
            'Vaksinasi Peserta MCU' => route('admin.pendaftaran.vaksinasi'),
        ]);
        return view('paneladmin.pendaftaran.riwayat_vaksinasi', ['data' => $data]);
    }
}
