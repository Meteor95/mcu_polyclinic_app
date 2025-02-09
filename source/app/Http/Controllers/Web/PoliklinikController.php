<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Komponen\{PoliKesumpulan, PoliDetailKesimpulan};
use App\Models\User;
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
    public function poliklinik(Request $req, $jenis_poli)
    {
        $catatan_kaki = "";
        $unggahan_citra_aktif = true;
        if ($jenis_poli == "spirometri") {
            $catatan_kaki = "Angka Pada kolom prediksi, % prediksi, dan LLN telah diproyeksi menggunakan acuan Penumobile Project Indonesia (nilai fungsi paru normal orang indonesia) sehingga nilai berbeda dengan yang tercantum pada spirogram.";
        }
        if ($jenis_poli == "farmingham_score") {
            $unggahan_citra_aktif = false;
        }
        $data = $this->getData($req, 'Poliklinik ' . ucwords(str_replace('_', ' ', $jenis_poli)), [
            ucwords(str_replace('_', ' ', $jenis_poli)) => route('admin.poliklinik', $jenis_poli),
        ], $catatan_kaki, $jenis_poli, "poli_" . $jenis_poli);
        $data['unggahan_citra_aktif'] = $unggahan_citra_aktif;
        $data['daftar_dokter'] = User::role('dokter')->join('users_pegawai', 'users.id', '=', 'users_pegawai.id')->get()->toArray();
        return view('paneladmin.pemeriksaan_fisik.poliklinik.'.$jenis_poli, ['data' => $data]);
    }
}
