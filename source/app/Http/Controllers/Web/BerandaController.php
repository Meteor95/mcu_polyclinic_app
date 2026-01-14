<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\{User,Perusahaan};
use App\Models\Masterdata\MemberMCU;
use App\Models\Transaksi\Transaksi;
use Illuminate\Http\Request;
use App\Models\Masterdata\DaftarBank;

class BerandaController extends Controller
{
    private function getData($req, $title, $breadcrumb) {
        return [
            'title' => $title,
            'breadcrumb' => $breadcrumb,
            'user_details' => $req->attributes->get('user_details'),
        ];
    }
    public function index(Request $request){
        $user = $request->attributes->get('user_details');
        $perusahaan = json_decode($user->json_perusahaan, true);
        $judul = 'Beranda Aplikasi Arta Medica Centre';
        if (!empty($perusahaan)) {
            $judul = 'Beranda Partner Artha Medica Centre';
        }
        $data = $this->getData($request, $judul , [
            'Dashboard' => route('admin.beranda'),
        ]);
        $data['jumlah_member_terdaftar'] = MemberMCU::count();
        $data['jumlah_transaksi'] = Transaksi::count();
        $data['jumlah_rekanan'] = Perusahaan::count();
        $data['jumlah_tindakan_selesai'] = Transaksi::where('status_peserta', 'selesai')->count();
        $data['jumlah_tindakan_proses'] = Transaksi::where('status_peserta', 'proses')->count();
        if (!empty($perusahaan)) {
            return view('paneladmin.beranda.main_konten_perusahaan', ['data' => $data]);    
        }
        return view('paneladmin.beranda.main_konten', ['data' => $data]);
    }
    public function kasir(Request $request){
        $data = $this->getData($request, 'Kasir MCU Arta Medica Centre', [
            'Kasir' => route('admin.kasir'),
        ]);
        $data['bank'] = DaftarBank::all();
        return view('paneladmin.beranda.kasir', ['data' => $data]);
    }
    public function antrian(Request $request){
        $data = $this->getData($request, 'Antrian Pasien Di Ruangan', [
            'Antrian Pasien' => route('admin.antrian'),
        ]);
        return view('paneladmin.beranda.antrian', ['data' => $data]);
    }
}
