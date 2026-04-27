<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Komponen\{TingkatKesadaran, TandaVital, KondisiFisik};
use App\Traits\NavigasiTrait;

class PemeriksaanFisikController extends Controller
{
    use NavigasiTrait;
    private function getData($req, $title, $breadcrumb) {
        return [
            'title' => $title,
            'breadcrumb' => $breadcrumb,
            'user_details' => $req->attributes->get('user_details'),
        ];
    }
    public function tingkat_kesadaran(Request $req){
        $data = $this->getData($req, 'Tingkat Kesadaran', [
            'Beranda' => route('admin.beranda'),
            'Tingkat Kesadaran' => route('admin.pemeriksaan_fisik.tingkat_kesadaran'),
        ]);
        $data['dataNavigasi'] = $this->getNavigasi('','', 'Penyakit Terdahulu', route('admin.pendaftaran.penyakit_terdahulu', ['nomor_identitas' => $req->nomor_identitas, 'nama_peserta' => $req->nama_peserta]), false, true);
        $data['nomor_identitas'] = $req->nomor_identitas;
        $data['nama_peserta'] = $req->nama_peserta;
        $data['tingkat_kesadaran'] = TingkatKesadaran::where('status', 1)->get();
        return view('paneladmin.pemeriksaan_fisik.tingkat_kesadaran', ['data' => $data]);
    }
    public function tanda_vital(Request $req){
        $data = $this->getData($req, 'Tanda Vital dan Gizi', [
            'Beranda' => route('admin.beranda'),
            'Tanda Vital' => route('admin.pemeriksaan_fisik.tanda_vital'),
        ]);
        $data['dataNavigasi'] = $this->getNavigasi('','', 'Penglihatan', route('admin.pemeriksaan_fisik.penglihatan', ['nomor_identitas' => $req->nomor_identitas, 'nama_peserta' => $req->nama_peserta]), false, true);
        $data['nomor_identitas'] = $req->nomor_identitas;
        $data['nama_peserta'] = $req->nama_peserta;
        $data['tanda_vital'] = TandaVital::where('status', 1)->get();
        return view('paneladmin.pemeriksaan_fisik.tanda_vital', ['data' => $data]);
    }
    public function penglihatan(Request $req){
        $data = $this->getData($req, 'Penglihatan', [
            'Beranda' => route('admin.beranda'),
            'Penglihatan' => route('admin.pemeriksaan_fisik.penglihatan'),
        ]);
        $data['dataNavigasi'] = $this->getNavigasi('Penyakit Terdahulu', route('admin.pendaftaran.penyakit_terdahulu', ['nomor_identitas' => $req->nomor_identitas, 'nama_peserta' => $req->nama_peserta]), 'Kulit', url('/pemeriksaan_fisik/kondisi_fisik/kulit?nomor_identitas='.$req->nomor_identitas.'&nama_peserta='.$req->nama_peserta), true, true);
        $data['nomor_identitas'] = $req->nomor_identitas;
        $data['nama_peserta'] = $req->nama_peserta;
        return view('paneladmin.pemeriksaan_fisik.penglihatan', ['data' => $data]);
    }
    public function kondisi_fisik(Request $req, $lokasi_fisik){
        $data = $this->getData($req, 'Kondisi Fisik '.ucwords($lokasi_fisik), [
            'Beranda' => route('admin.beranda'),
            'Kondisi Fisik '.ucwords($lokasi_fisik) => route('admin.pemeriksaan_fisik.kondisi_fisik', ['lokasi_fisik' => strtolower($lokasi_fisik)]),
        ]);
        $lokasi_fisik_navigasi_sebelumnya = route('admin.pemeriksaan_fisik.penglihatan', ['nomor_identitas' => $req->nomor_identitas, 'nama_peserta' => $req->nama_peserta]);
        $lokasi_fisik_navigasi = '';
        switch ($lokasi_fisik) {
            case 'kulit':
                $data['dataNavigasi'] = $this->getNavigasi('Penglihatan', route('admin.pemeriksaan_fisik.penglihatan', ['nomor_identitas' => $req->nomor_identitas, 'nama_peserta' => $req->nama_peserta]), 'Kepala', url('/pemeriksaan_fisik/kondisi_fisik/kepala?nomor_identitas='.$req->nomor_identitas.'&nama_peserta='.$req->nama_peserta), true, true);
                break;
            case 'kepala':
                $lokasi_fisik_navigasi_sebelumnya = 'kulit';
                $lokasi_fisik_navigasi = 'telinga';
                break;
            case 'telinga':
                $lokasi_fisik_navigasi_sebelumnya = 'kepala';
                $lokasi_fisik_navigasi = 'mata';
                break;
            case 'mata':
                $lokasi_fisik_navigasi_sebelumnya = 'telinga';
                $lokasi_fisik_navigasi = 'tenggorokan';
                break;
            case 'tenggorokan':
                $lokasi_fisik_navigasi_sebelumnya = 'mata';
                $lokasi_fisik_navigasi = 'mulut';
                break;
            case 'mulut':
                $lokasi_fisik_navigasi_sebelumnya = 'tenggorokan';
                $lokasi_fisik_navigasi = 'gigi';
                break;
            case 'gigi':
                $lokasi_fisik_navigasi_sebelumnya = 'mulut';
                $lokasi_fisik_navigasi = 'leher';
                break;
            case 'leher':
                $lokasi_fisik_navigasi_sebelumnya = 'gigi';
                $lokasi_fisik_navigasi = 'thorax';
                break;
            case 'thorax':
                $lokasi_fisik_navigasi_sebelumnya = 'leher';
                $lokasi_fisik_navigasi = 'abdomen_urogenital';
                break;
            case 'abdomen_urogenital':
                $lokasi_fisik_navigasi_sebelumnya = 'thorax';
                $lokasi_fisik_navigasi = 'anorectal_genital';
                break;
            case 'anorectal_genital':
                $lokasi_fisik_navigasi_sebelumnya = 'abdomen_urogenital';
                $lokasi_fisik_navigasi = 'ekstremitas';
                break;
            case 'ekstremitas':
                $lokasi_fisik_navigasi_sebelumnya = 'anorectal_genital';
                $lokasi_fisik_navigasi = 'neurologis';
                break;
            case 'neurologis':
                $lokasi_fisik_navigasi_sebelumnya = 'ekstremitas';
                $lokasi_fisik_navigasi = 'kepala';
                break;
            default:
                $lokasi_fisik_navigasi = $lokasi_fisik;
                break;
        }
        if ($lokasi_fisik != 'kulit'){
            $data['dataNavigasi'] = $this->getNavigasi(ucwords(str_replace("_", " ", $lokasi_fisik_navigasi_sebelumnya)), $lokasi_fisik_navigasi_sebelumnya, ucwords(str_replace("_", " ", $lokasi_fisik_navigasi)), url('/pemeriksaan_fisik/kondisi_fisik/'.$lokasi_fisik_navigasi.'?nomor_identitas='.$req->nomor_identitas.'&nama_peserta='.$req->nama_peserta), true, true);
        }
        $data['nomor_identitas'] = $req->nomor_identitas;
        $data['nama_peserta'] = $req->nama_peserta;
        $data['kondisi_fisik'] = KondisiFisik::where('status', 1)->where('nama_atribut_fisik', ucwords(str_replace("_", " & ", $lokasi_fisik)))->orderBy('kategori_lokasi_fisik', 'asc')->orderByRaw("CASE WHEN jenis_pemeriksaan = 'Lainnya' THEN 1 ELSE 0 END ASC")->orderBy('jenis_pemeriksaan', 'asc')->get();
        $data['lokasi_fisik'] = ucwords($lokasi_fisik);
        return view('paneladmin.pemeriksaan_fisik.kondisi_fisik.'.strtolower($lokasi_fisik), ['data' => $data]);
    }
}

