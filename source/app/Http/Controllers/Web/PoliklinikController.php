<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Komponen\{PoliKesumpulan, PoliDetailKesimpulan};
class PoliklinikController extends Controller
{
    private function getData($req, $title, $breadcrumb, $catatan_kaki = "", $jenis_poli, $jenis_poli_db = "") {
        return [
            'title' => $title,
            'breadcrumb' => $breadcrumb,
            'user_details' => $req->attributes->get('user_details'),
            'catatan_kaki' => $catatan_kaki,
            'detail_kesimpulan' => PoliDetailKesimpulan::where('jenis_poli', $jenis_poli_db)->get(),
            'kesimpulan' => PoliKesumpulan::where('jenis_poli', $jenis_poli_db)->get(),
            'jenis_poli' => $jenis_poli,
        ];
    }
    public function spirometri(Request $req)
    {
        $data = $this->getData($req, 'Poliklinik Spirometri', [
            'Spirometri' => route('admin.poliklinik.spirometri'),
        ], "Angka Pada kolom prediksi, % prediksi, dan LLN telah diproyeksi menggunakan acuan Penumobile Project Indonesia (nilai fungsi paru normal orang indonesia) sehingga nilai berbeda dengan yang tercantum pada spirogram.", "spirometri", "poli_spirometri");
        return view('paneladmin.pemeriksaan_fisik.poliklinik.spirometri', ['data' => $data]);
    }
    public function audiometri(Request $req)
    {
        $data = $this->getData($req, 'Poliklinik Audiometri', [
            'Audiometri' => route('admin.poliklinik.audiometri'),
        ], "", "audiometri", "poli_audiometri");
        return view('paneladmin.pemeriksaan_fisik.poliklinik.audiometri', ['data' => $data]);
    }
    public function ekg(Request $req)
    {
        $data = $this->getData($req, 'Poliklinik EKG', [
            'EKG' => route('admin.poliklinik.ekg'),
        ], "", "ekg", "poli_ekg");
        return view('paneladmin.pemeriksaan_fisik.poliklinik.ekg', ['data' => $data]);
    }
    public function threadmill(Request $req)
    {
        $data = $this->getData($req, 'Poliklinik Threadmill', [
            'Threadmill' => route('admin.poliklinik.threadmill'),
        ], "", "threadmill", "poli_threadmill");
        return view('paneladmin.pemeriksaan_fisik.poliklinik.threadmill', ['data' => $data]);
    }
    public function ronsen(Request $req)
    {
        $data = $this->getData($req, 'Poliklinik Ronsen', [
            'Ronsen' => route('admin.poliklinik.ronsen'),
        ], "", "ronsen", "poli_ronsen");
        return view('paneladmin.pemeriksaan_fisik.poliklinik.ronsen', ['data' => $data]);
    }
}
