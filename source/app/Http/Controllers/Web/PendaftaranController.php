<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftaran\Peserta;
use App\Models\Transaksi\Transaksi;
use App\Models\Masterdata\DaftarBank;
use App\Models\{Perusahaan, PaketMCU};
use App\Models\Masterdata\DepartemenPerusahaan;
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
    public function riwayat_informasi_mcu(Request $req, $uuid = null){
        $data = $this->getData($req, 'Riwayat Informasi', [
            'Daftar Peserta' => route('admin.pendaftaran.daftar_peserta'),
        ]);
        return view('paneladmin.pendaftaran.riwayat_foto_pasien', ['data' => $data]);
    }
}
