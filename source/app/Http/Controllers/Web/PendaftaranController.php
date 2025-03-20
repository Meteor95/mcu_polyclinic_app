<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\NavigasiTrait;
use App\Models\Pendaftaran\Peserta;
use App\Models\Transaksi\Transaksi;
use App\Models\Masterdata\DaftarBank;
use App\Models\{Perusahaan, PaketMCU};
use App\Models\Masterdata\DepartemenPerusahaan;
use App\Models\Komponen\{LingkunganKerja, KebiasaanHidup, PenyakitKeluarga, Imunisasi, PenyakitTerdahulu};
use Illuminate\Support\Facades\Log;
use App\Models\EndUser\Formulir;
use App\Models\User;

class PendaftaranController extends Controller
{
    use NavigasiTrait; 
    private function getData($req, $title, $breadcrumb) {
        return [
            'title' => $title,
            'breadcrumb' => $breadcrumb,
            'user_details' => $req->attributes->get('user_details'),
        ];
    }
    /* Area Landing Formulir Pendaftaran */
    public function formulir_pendaftaran(Request $req){
        $lingkungan_kerja = LingkunganKerja::all();
        $kebiasaan_hidup = KebiasaanHidup::all();
        $penyakit_keluarga = PenyakitKeluarga::all();
        $imunisasi = Imunisasi::all();
        $penyakit_terdahulu = PenyakitTerdahulu::all();
        $data = [
            'tipe_halaman' => 'landing',
            'menu_utama_aktif' => 'formulir_pendaftaran',
            'dataNavigasi' => "",
            'lingkungan_kerja' => $lingkungan_kerja,
            'kebiasaan_hidup' => $kebiasaan_hidup,
            'penyakit_keluarga' => $penyakit_keluarga,
            'imunisasi' => $imunisasi,
            'penyakit_terdahulu' => $penyakit_terdahulu,
        ];
        return view('preregister.landingpage_form', ['data' => $data]);
    }
    public function formulir_no_antrian(Request $req, $uuid){
        $peserta_sudah_ada = Formulir::where('no_pemesanan', $uuid)->count();
        $data = [
            'tipe_halaman' => 'landing',
            'menu_utama_aktif' => 'formulir_pendaftaran',
            'dataNavigasi' => "",
            'uuid' => $uuid,
            'peserta_sudah_ada' => $peserta_sudah_ada
        ];
        return view('preregister.landingpage_nomor_pesanan', ['data' => $data]);
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
    public function add_form_patien_mcu(Request $req, $nomor_identifikasi = null){
        $data = $this->getData($req, 'Formulir Pendaftaran', [
            'Daftar Peserta' => route('admin.pendaftaran.daftar_peserta'),
        ]);
        if($nomor_identifikasi != null){
            $data['peserta'] = Peserta::where('nomor_identifikasi', $nomor_identifikasi)
                ->first();
        }else{
            $data['peserta'] = null;
        }
        $data['bank'] = DaftarBank::all();
        $data['ubah'] = "";
        $data['nomor_identifikasi'] = $nomor_identifikasi;
        $data['json_data_diri'] = $data['peserta'] ? json_decode($data['peserta']->json_data_diri, true) : null;
        return view('paneladmin.pendaftaran.formulirtambahpeserta', ['data' => $data]);
    }
    public function update_form_patien_mcu(Request $req, $uuid = null){
        $data = $this->getData($req, 'Formulir Pendaftaran', [
            'Daftar Peserta' => route('admin.pendaftaran.daftar_peserta'),
        ]);
        $datatransaksi = Transaksi::getPasienById($uuid);
        $informasipaket = PaketMCU::find($datatransaksi->id_paket_mcu);
        $data['dataNavigasi'] = $this->getNavigasi('', '', 'Foto Data Diri', route('admin.pendaftaran.foto_pasien', ['nomor_identitas' => $datatransaksi->nomor_identitas, 'nama_peserta' => $datatransaksi->nama_peserta]), false, true);
        $data = array_merge($data, [
            'bank' => DaftarBank::all(),
            'ubah' => true,
            'perusahaan' => Perusahaan::find($datatransaksi->perusahaan_id),
            'departemen' => DepartemenPerusahaan::find($datatransaksi->departemen_id),
            'paket_mcu' => $informasipaket,
            'akses_tindakan' => json_decode($informasipaket->akses_tindakan, true),
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
        $data['dataNavigasi'] = $this->getNavigasi('', '', 'Lingkungan Kerja', route('admin.pendaftaran.lingkungan_kerja', ['nomor_identitas' => $req->nomor_identitas, 'nama_peserta' => $req->nama_peserta]), false, true);
        $data['nomor_identitas'] = $req->nomor_identitas;
        $data['nama_peserta'] = $req->nama_peserta;
        return view('paneladmin.pendaftaran.riwayat_foto_pasien', ['data' => $data]);
    }
    public function lingkungan_kerja(Request $req){
        $data = $this->getData($req, 'Lingkungan Kerja Peserta MCU', [
            'Beranda' => route('admin.beranda'),
            'Lingkungan Kerja Peserta MCU' => route('admin.pendaftaran.lingkungan_kerja'),
        ]);
        $data['dataNavigasi'] = $this->getNavigasi('Foto Pasien',route('admin.pendaftaran.foto_pasien', ['nomor_identitas' => $req->nomor_identitas, 'nama_peserta' => $req->nama_peserta]), 'Kecelakaan Kerja', route('admin.pendaftaran.kecelakaan_kerja', ['nomor_identitas' => $req->nomor_identitas, 'nama_peserta' => $req->nama_peserta]), true, true);
        $data['nomor_identitas'] = $req->nomor_identitas;
        $data['nama_peserta'] = $req->nama_peserta;
        $data['bahaya_paparan_kerja'] = LingkunganKerja::where('status', 1)->get();
        return view('paneladmin.pendaftaran.riwayat_lingkungan_kerja', ['data' => $data]);
    }
    public function kecelakaan_kerja(Request $req){
        $data = $this->getData($req, 'Kecelakaan Kerja Peserta MCU', [
            'Beranda' => route('admin.beranda'),
            'Kecelakaan Kerja Peserta MCU' => route('admin.pendaftaran.kecelakaan_kerja'),
        ]);
        $data['dataNavigasi'] = $this->getNavigasi('Lingkungan Kerja',route('admin.pendaftaran.lingkungan_kerja', ['nomor_identitas' => $req->nomor_identitas, 'nama_peserta' => $req->nama_peserta]), 'Kebiasaan Hidup', route('admin.pendaftaran.kebiasaan_hidup', ['nomor_identitas' => $req->nomor_identitas, 'nama_peserta' => $req->nama_peserta]), true, true);
        $data['nomor_identitas'] = $req->nomor_identitas;
        $data['nama_peserta'] = $req->nama_peserta;
        return view('paneladmin.pendaftaran.riwayat_kecelakaan_kerja', ['data' => $data]);
    }
    public function penyakit_terdahulu(Request $req){
        $data = $this->getData($req, 'Penyakit Terdahulu Peserta MCU', [
            'Beranda' => route('admin.beranda'),
            'Penyakit Terdahulu Peserta MCU' => route('admin.pendaftaran.penyakit_terdahulu'),
        ]);
        $data['dataNavigasi'] = $this->getNavigasi('Kebiasaan Hidup',route('admin.pendaftaran.kebiasaan_hidup', ['nomor_identitas' => $req->nomor_identitas, 'nama_peserta' => $req->nama_peserta]), 'Penyakit Keluarga', route('admin.pendaftaran.penyakit_keluarga', ['nomor_identitas' => $req->nomor_identitas, 'nama_peserta' => $req->nama_peserta]), true, true);
        $data['nomor_identitas'] = $req->nomor_identitas;
        $data['nama_peserta'] = $req->nama_peserta;
        $data['penyakit_terdahulu'] = PenyakitTerdahulu::where('status', 1)->get();
        return view('paneladmin.pendaftaran.riwayat_penyakit_terdahulu', ['data' => $data]);
    }
    public function penyakit_keluarga(Request $req){
        $data = $this->getData($req, 'Penyakit Keluarga Peserta MCU', [
            'Beranda' => route('admin.beranda'),
            'Penyakit Keluarga Peserta MCU' => route('admin.pendaftaran.penyakit_keluarga'),
        ]);
        $data['dataNavigasi'] = $this->getNavigasi('Penyakit Terdahulu',route('admin.pendaftaran.penyakit_terdahulu', ['nomor_identitas' => $req->nomor_identitas, 'nama_peserta' => $req->nama_peserta]), 'Imunisasi', route('admin.pendaftaran.imunisasi', ['nomor_identitas' => $req->nomor_identitas, 'nama_peserta' => $req->nama_peserta]), true, true);
        $data['nomor_identitas'] = $req->nomor_identitas;
        $data['nama_peserta'] = $req->nama_peserta;
        $data['penyakit_keluarga'] = PenyakitKeluarga::where('status', 1)->get();
        return view('paneladmin.pendaftaran.riwayat_penyakit_keluarga', ['data' => $data]);
    }
    public function kebiasaan_hidup(Request $req){
        $data = $this->getData($req, 'Kebiasaan Hidup Peserta MCU', [
            'Beranda' => route('admin.beranda'),
            'Kebiasaan Hidup Peserta MCU' => route('admin.pendaftaran.kebiasaan_hidup'),
        ]);
        $data['dataNavigasi'] = $this->getNavigasi('Kecelakaan Kerja',route('admin.pendaftaran.kecelakaan_kerja', ['nomor_identitas' => $req->nomor_identitas, 'nama_peserta' => $req->nama_peserta]), 'Penyakit Terdahulu', route('admin.pendaftaran.penyakit_terdahulu', ['nomor_identitas' => $req->nomor_identitas, 'nama_peserta' => $req->nama_peserta]), true, true);
        $data['nomor_identitas'] = $req->nomor_identitas;
        $data['nama_peserta'] = $req->nama_peserta;
        $data['kebiasaan_hidup'] = KebiasaanHidup::where('status', '>', 0)->get();
        return view('paneladmin.pendaftaran.riwayat_kebiasaan_hidup', ['data' => $data]);
    }
    public function imunisasi(Request $req){
        $data = $this->getData($req, 'Imunisasi Peserta MCU', [
            'Beranda' => route('admin.beranda'),
            'Imunisasi Peserta MCU' => route('admin.pendaftaran.imunisasi'),
        ]);
        $data['dataNavigasi'] = $this->getNavigasi('Penyakit Keluarga',route('admin.pendaftaran.penyakit_keluarga', ['nomor_identitas' => $req->nomor_identitas, 'nama_peserta' => $req->nama_peserta]), '', '', true, false);
        $data['nomor_identitas'] = $req->nomor_identitas;
        $data['nama_peserta'] = $req->nama_peserta;
        $data['imunisasi'] = Imunisasi::where('status', 1)->get();
        return view('paneladmin.pendaftaran.riwayat_imunisasi', ['data' => $data]);
    }
}
