<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Log;
use App\Models\Transaksi\{Transaksi, UnggahCitra, LingkunganKerjaPeserta, RiwayatKecelakaanKerja, RiwayatKebiasaanHidup, RiwayatPenyakitTerdahulu, RiwayatPenyakitKeluarga, RiwayatImunisasi};
use App\Models\PemeriksaanFisik\{TingkatKesadaran, TandaVital, Penglihatan};
use App\Models\PemeriksaanFisik\KondisiFisik\{KondisiFisik, Gigi};
use App\Models\Laboratorium\{Kesimpulan as KesimpulanLabStatus, Transaksi as TransaksiLab, Kategori, TransaksiDetail};
use App\Models\Laporan\Kesimpulan;
use App\Helpers\QuillHelper;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Helpers\GlobalHelper;
use Illuminate\Support\Facades\DB;
use App\Models\Poliklinik\{Poliklinik, UnggahanCitra};
use Illuminate\Support\Facades\Storage;
use App\Models\Pegawai;
use Carbon\Carbon;


class LaporanController extends Controller
{
    private function getData($req, $title, $breadcrumb) {
        return [
            'title' => $title,
            'breadcrumb' => $breadcrumb,
            'user_details' => $req->attributes->get('user_details'),
        ];
    }
    function validasi_mcu(Request $req){
        $data = $this->getData($req, 'Validasi Tindakan Pasien', [
            'Validasi Tindakan Pasien' => route('admin.laporan.validasi_mcu'),
        ]);
        return view('paneladmin.laporan.validasi_mcu', ['data' => $data]);
    }
    function validasi_mcu_nota(Request $req, $no_nota){
        $data = $this->getData($req, 'Validasi Tindakan Pasien', [
            'Daftar Validasi Tindakan' => route('admin.laporan.validasi_mcu'),
            'Validasi Tindakan Pasien' => route('admin.laporan.validasi_mcu_nota', ['no_nota' => urlencode($no_nota)]),
        ]);
        $data['no_nota'] = $no_nota;
        return view('paneladmin.laporan.validasi_mcu_nota', ['data' => $data]);
    }
    public function validasi_rekap_kesimpulan(Request $req){
        $data = $this->getData($req, 'Validasi Laporan Tindakan MCU atau Pengobatan Pasien', [
            'Beranda' => route('admin.beranda'),
            'Validasi' => route('admin.laporan.validasi_rekap_kesimpulan'),
        ]);
        return view('paneladmin.laporan.validasi_rekap_kesimpulan', ['data' => $data]);
    }
    public function berkas_mcu(Request $req){
        $data = $this->getData($req, 'Berkas Tindakan MCU', [
            'Beranda' => route('admin.beranda'),
            'Berkas' => route('admin.laporan.berkas_mcu'),
        ]);
        return view('paneladmin.laporan.berkas.berkas_mcu', ['data' => $data]);
    }
    private function determineTableNamePemeriksaanFisik($lokasiFisik)
    {
        $tables = [
            'kepala' => 'mcu_pf_kepala',
            'telinga' => 'mcu_pf_telinga',
            'mata' => 'mcu_pf_mata',
            'tenggorokan' => 'mcu_pf_tenggorokan',
            'mulut' => 'mcu_pf_mulut',
            'gigi' => 'mcu_pf_gigi',
            'leher' => 'mcu_pf_leher',
            'thorax' => 'mcu_pf_thorax',
            'abdomen_urogenital' => 'mcu_pf_abdomen_urogenital',
            'anorectal_genital' => 'mcu_pf_anorectal_genital',
            'ekstremitas' => 'mcu_pf_ekstremitas',
            'neurologis' => 'mcu_pf_neurologis',
        ];
        return $tables[strtolower($lokasiFisik)] ?? null;
    }
    public function getHasilLaboratorium($id_transaksi)
    {
        $categories = Kategori::whereNull('parent_id')->with('children')->get();
        return $categories->map(function ($kategori) use ($id_transaksi) {
            return $this->formatKategori($kategori, $id_transaksi);
        });
    }
    private function formatItem($detail, $id_transaksi)
    {
        return [
            'id' => $detail->id,
            'nama_item' => $detail->nama_item,
            'meta_data_kuantitatif' => $detail->meta_data_kuantitatif,
            'meta_data_kualitatif' => $detail->meta_data_kualitatif,
            'satuan' => $detail->nama_satuan,
            'nilai_tindakan' => $detail->nilai_tindakan,
            'metode_tindakan' => $detail->metode_tindakan,
            'sub' => TransaksiDetail::where('id_transaksi', $id_transaksi)
                ->where('id_item', $detail->id)
                ->get()
                ->map(function ($subDetail) use ($id_transaksi) {
                    return $this->formatItem($subDetail, $id_transaksi);
                })
        ];
    }
    private function formatKategori($kategori, $id_transaksi)
    {
        $items = TransaksiDetail::join('transaksi','transaksi.id','=','transaksi_detail.id_transaksi')
            ->join('lab_tarif','lab_tarif.id','=','transaksi_detail.id_item')
            ->join('lab_satuan_item', 'lab_satuan_item.id','=','lab_tarif.satuan')
            ->where('transaksi.no_mcu', $id_transaksi)
            ->where('lab_tarif.id_kategori', $kategori->id)
            ->where('lab_tarif.visible_item','tampilkan')
            ->where('lab_tarif.group_item','laboratorium')
            ->get()
            ->map(function ($detail) use ($id_transaksi) {
                return $this->formatItem($detail, $id_transaksi);
            });
        $subkategori = Kategori::where('parent_id', $kategori->id)
            ->get()
            ->map(function ($subkategori) use ($id_transaksi) {
                return $this->formatKategori($subkategori, $id_transaksi);
            });
        return [
            'id' => $kategori->id,
            'nama_kategori' => $kategori->nama_kategori,
            'parent_id' => $kategori->parent_id,
            'grup_kategori' => $kategori->grup_kategori,
            'items' => $items,
            'subkategori' => $subkategori
        ];
    }
    private function determineTableName($jenis_poli)
    {
        $tables = [
            'spirometri' => 'mcu_poli_spirometri',
            'ekg' => 'mcu_poli_ekg',
            'threadmill' => 'mcu_poli_threadmill',
            'rontgen_thorax' => 'mcu_poli_rontgen_thorax',
            'rontgen_lumbosacral' => 'mcu_poli_rontgen_lumbosacral',
            'audiometri' => 'mcu_poli_audiometri',
            'usg_ubdomain' => 'mcu_poli_usg_ubdomain',
            'farmingham_score' => 'mcu_poli_farmingham_score',
        ];
        return $tables[strtolower($jenis_poli)] ?? null;
    }

    private function fetchInformasiPoliklinik($jenis_poli, $id_mcu, $model)
    {
        $tableName = $this->determineTableName($jenis_poli);
        if (!$tableName) {
            return collect();
        }
        $model->setTableName($tableName);
        $informasi_poliklinik = $model
            ->join('mcu_poli_citra', 'mcu_poli_citra.id_trx_poli', '=', $tableName.'.id')
            ->join('users_pegawai', 'users_pegawai.id', '=', $tableName.'.pegawai_id')
            ->join('users_pegawai as petugas', 'petugas.id', '=', $tableName.'.petugas_id')
            ->select(
                $tableName.'.*',
                'mcu_poli_citra.*',
                'mcu_poli_citra.id as id_each_citra',
                'users_pegawai.nama_pegawai',
                'users_pegawai.departemen',
                'petugas.nama_pegawai as nama_petugas',
                'petugas.departemen as departemen_petugas'
            )
            ->where($tableName.'.transaksi_id', $id_mcu)
            ->where('mcu_poli_citra.jenis_poli', 'poli_'.$jenis_poli)
            ->get();
        return collect($informasi_poliklinik)->map(function ($item) {
            $item->kesimpulan_citra_spirometri = QuillHelper::quillToHtml($item->detail_kesimpulan);
            $item->data_foto = url(env('APP_VERSI_API')."/file/unduh_citra_poliklinik?jenis_poli=".$item->jenis_poli ."&file_name=" . $item->nama_file);
            return $item;
        });
    }
    public function cetak_berkas_mcu(Request $req){
        $dataparameter = json_decode(base64_decode($req->query('data')), true);
        $tanggal_cetak = date('d').' '.GlobalHelper::getNamaBulanIndonesia(date('n')).' '.date('Y');
        $id_mcu = $dataparameter['id_mcu'];
        $nomor_mcu = $dataparameter['nomor_mcu'];
        $nik_peserta = $dataparameter['nik_peserta'];
        $tablePrefix = config('database.connections.mysql.prefix');
        $riwayat_informasi_foto = UnggahCitra::where('transaksi_id', $id_mcu)->first();
        $status_kesimpulan_lab = KesimpulanLabStatus::all();
        $groupedData = $status_kesimpulan_lab->groupBy('status');
        $informasi_data_diri = Transaksi::join('users_member', 'users_member.id', '=', 'mcu_transaksi_peserta.user_id')
            ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
            ->join('departemen_peserta', 'departemen_peserta.id', '=', 'mcu_transaksi_peserta.departemen_id')
            ->select('users_member.nama_peserta', 'users_member.nomor_identitas', 'users_member.tempat_lahir', 'users_member.tanggal_lahir', 'users_member.jenis_kelamin', 'users_member.alamat', 'company.company_name', 'departemen_peserta.nama_departemen', 'mcu_transaksi_peserta.tanggal_transaksi as tanggal_mcu', 'mcu_transaksi_peserta.jenis_transaksi_pendaftaran')
            ->selectRaw('TIMESTAMPDIFF(YEAR, ' . $tablePrefix . 'users_member.tanggal_lahir, CURDATE()) AS umur')
            ->where('mcu_transaksi_peserta.id', $id_mcu)->first();
        $riwayat_informasi_foto->data_foto = url(env('APP_VERSI_API')."/file/unduh_foto?file_name=" . $riwayat_informasi_foto->lokasi_gambar);
        $kesimpulan_tindakan = Kesimpulan::join('lab_kesimpulan', 'lab_kesimpulan.id', '=', 'transaksi_kesimpulan.kesimpulan_keseluruhan')
            ->where('transaksi_kesimpulan.id_mcu', $id_mcu)->first();
        $logoPath = public_path('mofi/assets/images/logo/Logo_AMC_Full.png');
        $qrcode = base64_encode(QrCode::format('svg')
                    ->size(75)
                    ->margin(1)
                    ->merge($logoPath, 0.3, true)
                    ->generate('techsolutionstuff.com'));
        $riwayat_penyakit_terdahulu = RiwayatPenyakitTerdahulu::where('transaksi_id', $id_mcu)->get();
        $riwayat_penyakit_keluarga = RiwayatPenyakitKeluarga::where('transaksi_id', $id_mcu)->get();
        $riwayat_kecelakaan_kerja = RiwayatKecelakaanKerja::where('transaksi_id', $id_mcu)->first();
        $riwayat_kebiasaan_hidup = RiwayatKebiasaanHidup::where('transaksi_id', $id_mcu)->get();
        $riwayat_imunisasi = RiwayatImunisasi::where('transaksi_id', $id_mcu)->get();
        $riwayat_lingkungan_kerja = LingkunganKerjaPeserta::where('transaksi_id', $id_mcu)->get();
        $tingkat_kesadaran = TingkatKesadaran::where('transaksi_id', $id_mcu)->first();
        $tanda_vital = TandaVital::where('transaksi_id', $id_mcu)->get();
        $penglihatan = Penglihatan::where('transaksi_id', $id_mcu)->get();
        $kategori_pemeriksaan = ['kepala','telinga','mata','tenggorokan','mulut','gigi','leher','thorax','abdomen_urogenital','anorectal_genital','ekstremitas','neurologis'];
        $query_kondisi_fisik = "";
        foreach ($kategori_pemeriksaan as $kategori) {
            $subquery = DB::table($this->determineTableNamePemeriksaanFisik($kategori))
                ->select([
                    DB::raw("'$kategori' AS kategori"),
                    'jenis_atribut',
                    'status_atribut',
                    'keterangan_atribut',
                    'transaksi_id'
                ])
                ->where('transaksi_id', $id_mcu);
            if ($query_kondisi_fisik) {
                $query_kondisi_fisik->union($subquery);
            } else {
                $query_kondisi_fisik = $subquery;
            }
        }
        $data_kondisi_fisik = $query_kondisi_fisik ? $query_kondisi_fisik->get() : collect([]);
        $data_kondisi_fisik = $data_kondisi_fisik->toArray();
        $laboratorium = $this->getHasilLaboratorium($id_mcu);
        $model = new Poliklinik();
        $jenis_polis = ['spirometri', 'ekg', 'threadmill', 'rontgen_thorax', 'rontgen_lumbosacral', 'audiometri', 'usg_ubdomain', 'farmingham_score'];
        $all_citra_data = collect();
        foreach ($jenis_polis as $jenis_poli) {
            $citra_data = $this->fetchInformasiPoliklinik($jenis_poli, $id_mcu, $model);
            $all_citra_data = $all_citra_data->merge($citra_data);
        }
        $data = [
            'title' => 'Berkas Tindakan MCU',
            'id_mcu' => $id_mcu,
            'nomor_mcu' => $nomor_mcu,
            'nik_peserta' => $nik_peserta,
            'tanggal_cetak' => $tanggal_cetak,
            'qrcode' => $qrcode,
            'riwayat_informasi_foto' => $riwayat_informasi_foto,
            'informasi_data_diri' => $informasi_data_diri,
            'kesimpulan_tindakan' => $kesimpulan_tindakan,
            'kesimpulan_hasil_medical_checkup' => $kesimpulan_tindakan->kesimpulan_hasil_medical_checkup,
            'quill_pemeriksaan_riwayat_medis' => QuillHelper::quillToHtml($kesimpulan_tindakan->kesimpulan_riwayat_medis),
            'quill_pemeriksaan_fisik' => QuillHelper::quillToHtml($kesimpulan_tindakan->kesimpulan_pemeriksaan_fisik),
            'quill_pemeriksaan_laboratorium' => QuillHelper::quillToHtml($kesimpulan_tindakan->kesimpulan_pemeriksaan_laboratorum),
            'quill_pemeriksaan_rontgen_thorax' => QuillHelper::quillToHtml($kesimpulan_tindakan->kesimpulan_pemeriksaan_rontgen_thorax),
            'quill_pemeriksaan_rontgen_lumbosacral' => QuillHelper::quillToHtml($kesimpulan_tindakan->kesimpulan_pemeriksaan_rontgen_lumbosacral),
            'quill_pemeriksaan_usg_ubdomain' => QuillHelper::quillToHtml($kesimpulan_tindakan->kesimpulan_pemeriksaan_usg_ubdomain),
            'quill_pemeriksaan_ekg' => QuillHelper::quillToHtml($kesimpulan_tindakan->kesimpulan_pemeriksaan_ekg),
            'quill_pemeriksaan_audio_kiri' => QuillHelper::quillToHtml($kesimpulan_tindakan->kesimpulan_pemeriksaan_audio_kiri),
            'quill_pemeriksaan_audio_kanan' => QuillHelper::quillToHtml($kesimpulan_tindakan->kesimpulan_pemeriksaan_audio_kanan),
            'quill_pemeriksaan_spiro_restriksi' => QuillHelper::quillToHtml($kesimpulan_tindakan->kesimpulan_pemeriksaan_spiro_restriksi),
            'quill_pemeriksaan_spiro_obstruksi' => QuillHelper::quillToHtml($kesimpulan_tindakan->kesimpulan_pemeriksaan_spiro_obstruksi),
            'quill_pemeriksaan_farmingham_score' => QuillHelper::quillToHtml($kesimpulan_tindakan->kesimpulan_pemeriksaan_farmingham_score),
            'quill_pemeriksaan_threadmill' => QuillHelper::quillToHtml($kesimpulan_tindakan->kesimpulan_pemeriksaan_threadmill),
            'quill_kesimpulan_tindakan' => $kesimpulan_tindakan->status." ".$kesimpulan_tindakan->kategori." [".$kesimpulan_tindakan->catatan."]",
            'quill_tindakan_saran' => QuillHelper::quillToHtml($kesimpulan_tindakan->saran_keseluruhan),
            'status_kesimpulan_lab' => $groupedData,
            'riwayat_penyakit_terdahulu' => $riwayat_penyakit_terdahulu,
            'riwayat_penyakit_keluarga' => $riwayat_penyakit_keluarga,
            'riwayat_kecelakaan_kerja' => QuillHelper::quillToHtml($riwayat_kecelakaan_kerja->riwayat_kecelakaan_kerja),
            'riwayat_kebiasaan_hidup' => $riwayat_kebiasaan_hidup,
            'riwayat_imunisasi' => $riwayat_imunisasi,
            'riwayat_lingkungan_kerja' => $riwayat_lingkungan_kerja,
            'tingkat_kesadaran' => $tingkat_kesadaran,
            'tanda_vital' => $tanda_vital,
            'penglihatan' => $penglihatan,
            'kondisi_fisik' => $data_kondisi_fisik,
            'laboratorium' => $laboratorium,
            'all_citra_data' => $all_citra_data,
        ];
        $folderPath = 'public/mcu/berkas/mcu/';
        $filename = "MCU_".str_replace('/', '_', $nomor_mcu).'_'.$id_mcu.'_'.$nik_peserta.'.pdf';
        $fullPath = storage_path("app/$folderPath$filename");
        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath, 0755, true);
        }
        if (!file_exists($fullPath)) {
            $pdf = PDF::loadView('paneladmin.laporan.berkas.pdf_berkas_mcu', ['data' => $data])
                ->setPaper('legal', 'portrait')
                ->setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
            $pdf->render();
            $pdf->get_canvas()->page_script(function ($pageNumber, $pageCount, $canvas) {
                if ($pageNumber > 1 && $pageNumber < $pageCount) {
                    $width = $canvas->get_width();
                    $text = "Halaman " . ($pageNumber - 1) . " Dari " . ($pageCount - 2);
                    $x = ($width / 2) + 175;              
                    $y = $canvas->get_height() - 40;
                    $canvas->text($x, $y, $text, null, 12);
                }
                if ($pageCount == $pageNumber) {
                    $width = $canvas->get_width();
                    $height = $canvas->get_height();
                    $canvas->image(public_path('mofi/assets/images/logo/compress_cover_back.jpg'), 0, 0, $width, $height);
                }
            });
            $pdf->save($fullPath);
        } 
        return response()->file($fullPath);
    }
    public function berkas_laboratorium(Request $req){
        $data = $this->getData($req, 'Berkas Tindakan Laboratorium', [
            'Beranda' => route('admin.beranda'),
            'Berkas' => route('admin.laporan.berkas_laboratorium'),
        ]);
        return view('paneladmin.laporan.berkas.berkas_laboratorium', ['data' => $data]);
    }
    public function cetak_berkas_laboratorium(Request $req){
        $dataparameter = json_decode(base64_decode($req->query('data')), true);
        $tanggal_cetak = date('d').' '.GlobalHelper::getNamaBulanIndonesia(date('n')).' '.date('Y');
        $id_mcu = $dataparameter['id_mcu'];
        $nomor_mcu = $dataparameter['nomor_mcu'];
        $nik_peserta = $dataparameter['nik_peserta'];
        $tablePrefix = config('database.connections.mysql.prefix');
        $qrcode = base64_encode(QrCode::format('svg')
                    ->size(75)
                    ->margin(1)
                    ->generate('techsolutionstuff.com'));
        $riwayat_informasi_foto = UnggahCitra::where('transaksi_id', $id_mcu)->first();
        $informasi_data_diri = Transaksi::join('users_member', 'users_member.id', '=', 'mcu_transaksi_peserta.user_id')
            ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
            ->join('departemen_peserta', 'departemen_peserta.id', '=', 'mcu_transaksi_peserta.departemen_id')
            ->select('users_member.nama_peserta', 'users_member.nomor_identitas', 'users_member.tempat_lahir', 'users_member.tanggal_lahir', 'users_member.jenis_kelamin', 'users_member.alamat', 'company.company_name', 'departemen_peserta.nama_departemen', 'mcu_transaksi_peserta.tanggal_transaksi as tanggal_mcu', 'mcu_transaksi_peserta.jenis_transaksi_pendaftaran')
            ->selectRaw('TIMESTAMPDIFF(YEAR, ' . $tablePrefix . 'users_member.tanggal_lahir, CURDATE()) AS umur')
            ->where('mcu_transaksi_peserta.id', $id_mcu)->first();
        $riwayat_informasi_foto->data_foto = url(env('APP_VERSI_API')."/file/unduh_foto?file_name=" . $riwayat_informasi_foto->lokasi_gambar);
        $laboratorium = $this->getHasilLaboratorium($id_mcu);
        $data = [
            'title' => 'Berkas Tindakan MCU',
            'id_mcu' => $id_mcu,
            'nomor_mcu' => $nomor_mcu,
            'nik_peserta' => $nik_peserta,
            'tanggal_cetak' => $tanggal_cetak,
            'qrcode' => $qrcode,
            'riwayat_informasi_foto' => $riwayat_informasi_foto,
            'informasi_data_diri' => $informasi_data_diri,
            'laboratorium' => $laboratorium,
        ];
        $folderPath = 'public/mcu/berkas/laboratorium/';
        $filename = "LAB_".str_replace('/', '_', $nomor_mcu).'_'.$id_mcu.'_'.$nik_peserta.'.pdf';
        $fullPath = storage_path("app/$folderPath$filename");
        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath, 0755, true);
        }
        if (!file_exists($fullPath)) {
            $pdf = PDF::loadView('paneladmin.laporan.berkas.pdf_berkas_laboratorium', ['data' => $data])
                ->setPaper('legal', 'portrait')
                ->setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
            $pdf->render();
            $pdf->get_canvas()->page_script(function ($pageNumber, $pageCount, $canvas) {
                if ($pageNumber > 1 && $pageNumber < $pageCount) {
                    $width = $canvas->get_width();
                    $text = "Halaman " . ($pageNumber - 1) . " Dari " . ($pageCount - 2);
                    $x = ($width / 2) + 175;              
                    $y = $canvas->get_height() - 40;
                    $canvas->text($x, $y, $text, null, 12);
                }
            
                if ($pageCount == $pageNumber) {
                    $width = $canvas->get_width();
                    $height = $canvas->get_height();
                    $canvas->image(public_path('mofi/assets/images/logo/compress_cover_back.jpg'), 0, 0, $width, $height);
                }
            });
            $pdf->save($fullPath);
        }
        return response()->file($fullPath);
    }
    public function berkas_kuitansi(Request $req){
        $data = $this->getData($req, 'Berkas Tindakan Kuitansi', [
            'Beranda' => route('admin.beranda'),
            'Berkas' => route('admin.laporan.berkas_kuitansi'),
        ]);
        return view('paneladmin.laporan.berkas.berkas_kuitansi', ['data' => $data]);
    }
    public function laporan_penjualan(Request $req){
        $data = $this->getData($req, 'Laporan Penjualan', [
            'Beranda' => route('admin.beranda'),
            'Laporan Tindakan' => route('admin.laporan.laporan_penjualan'),
        ]);
        return view('paneladmin.laporan.transaksi.laporan_penjualan', ['data' => $data]);
    }
    public function laporan_kuitansi(Request $req){
        $data = $this->getData($req, 'Cetak Kuitansi', [
            'Beranda' => route('admin.beranda'),
            'Kuitansi' => route('admin.laporan.laporan_kuitansi'),
        ]);
        return view('paneladmin.laporan.transaksi.laporan_kuitansi', ['data' => $data]);
    }
    public function laporan_insentif(Request $req){
        $data = $this->getData($req, 'Laporan Insentif', [
            'Beranda' => route('admin.beranda'),
            'Laporan Insentif' => route('admin.laporan.laporan_insentif'),
        ]);
        return view('paneladmin.laporan.transaksi.laporan_insentif', ['data' => $data]);
    }
    public function cetak_kuitansi_personal(Request $req){
        $dataparameter = json_decode(base64_decode($req->query('data')), true);
        $tanggal_cetak = date('d').' '.GlobalHelper::getNamaBulanIndonesia(date('n')).' '.date('Y');
        $id_mcu = $dataparameter['id_mcu'];
        $nomor_mcu = $dataparameter['nomor_mcu'];
        $nik_peserta = $dataparameter['nik_peserta'];
        $keterangan = $dataparameter['keterangan'];
        $jenis_kuitansi = $dataparameter['jenis_kuitansi'];
        $tablePrefix = config('database.connections.mysql.prefix');
        $data_informasi = TransaksiLab::join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'transaksi.no_mcu')
            ->join('users_member', 'users_member.id', '=', 'mcu_transaksi_peserta.user_id')
            ->where('mcu_transaksi_peserta.id', $id_mcu)
            ->where('users_member.nomor_identitas', $nik_peserta)
            ->selectRaw('
                '.$tablePrefix.'transaksi.no_nota AS no_nota,
                '.$tablePrefix.'users_member.nama_peserta AS nama_peserta,
                '.$tablePrefix.'transaksi.total_transaksi AS total_pembayaran,
                '.$tablePrefix.'transaksi.nominal_apotek AS nominal_apotek,
                '.$tablePrefix.'transaksi.jenis_layanan AS jenis_layanan
            ')->first();
        $parts_nota = explode("/", $data_informasi->no_nota);
        $no_nota = implode("/", array_slice($parts_nota, 0, 3));
        $no_mcu = implode("/", array_slice($parts_nota, 3));
        $qrcode_no_nota = base64_encode(QrCode::format('svg')
            ->size(75)
            ->margin(1)
            ->generate($no_nota));
        $atas_nama_nota = Pegawai::where('atas_nama_kuitansi', 1)->first();
        $qrcode_dokter = base64_encode(QrCode::format('svg')
            ->size(75)
            ->margin(1)
            ->generate($atas_nama_nota->nik));
        $data = [
            'title' => 'Berkas Tindakan MCU',
            'nomor_mcu' => $no_nota,
            'qrcode_no_nota' => $qrcode_no_nota,
            'qrcode_dokter' => $qrcode_dokter,
            'atas_nama_nota' => $atas_nama_nota->nama_pegawai,
            'nip' => $atas_nama_nota->nik,
            'keterangan' => $keterangan == "" ? "Kuitansi Jenis Tindakan ".ucwords(str_replace("_", " ", $data_informasi->jenis_layanan)) : $keterangan,
            'tanggal_cetak' => $tanggal_cetak,
            'nama_peserta' => $data_informasi->nama_peserta,
            'total_pembayaran' => "Rp ".number_format($data_informasi->total_pembayaran + $data_informasi->nominal_apotek,2,",","."),
            'terbilang' => ucwords(GlobalHelper::terbilang($data_informasi->total_pembayaran + $data_informasi->nominal_apotek))." Rupiah"
        ];
        $folderPath = 'public/kuitansi/personal/';
        $filename = "KUITANSI_".date('YmdHis').".pdf";
        $fullPath = storage_path("app/$folderPath$filename");
        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath, 0755, true);
        }
        if (!file_exists($fullPath)) {
            $pdf = PDF::loadView('paneladmin.laporan.kuitansi.pdf_kuitansi_personal', ['data' => $data])
                ->setPaper('legal', 'portrait')
                ->setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
            $pdf->render();
            $pdf->save($fullPath);
        }
        return response()->file($fullPath);
    }
    public function cetak_kuitansi_perusahaan(Request $req){
        $dataparameter = json_decode(base64_decode($req->query('data')), true);
        $tanggal_cetak = date('d').' '.GlobalHelper::getNamaBulanIndonesia(date('n')).' '.date('Y');
        $id_perusahaan = $dataparameter['id_perusahaan'];
        $kode_perusahaan = $dataparameter['kode_perusahaan'];
        $nama_perusahaan = $dataparameter['nama_perusahaan'];
        $keterangan = $dataparameter['keterangan'];
        $jenis_kuitansi = $dataparameter['jenis_kuitansi'];
        $jenis_transaksi = $dataparameter['jenis_transaksi'];
        $jenis_layanan = $dataparameter['jenis_layanan'];
        $status_pembayaran = $dataparameter['status_pembayaran'];
        $tablePrefix = config('database.connections.mysql.prefix');
        $data_informasi = TransaksiLab::join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'transaksi.no_mcu')
            ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
            ->where('mcu_transaksi_peserta.perusahaan_id', $id_perusahaan)
            ->selectRaw('
                '.$tablePrefix.'transaksi.no_nota AS no_nota,
                '.$tablePrefix.'company.id AS id_perusahaan,
                '.$tablePrefix.'company.company_name AS nama_peserta,
                SUM('.$tablePrefix.'transaksi.total_transaksi) AS total_pembayaran,
                SUM('.$tablePrefix.'transaksi.nominal_apotek) AS nominal_apotek,
                '.$tablePrefix.'transaksi.jenis_layanan AS jenis_layanan,
                COUNT('.$tablePrefix.'transaksi.no_nota) AS jumlah_peserta,
                MIN('.$tablePrefix.'transaksi.waktu_trx) AS tanggal_awal,
                MAX('.$tablePrefix.'transaksi.waktu_trx) AS tanggal_akhir
            ');
        if ($jenis_transaksi != ""){
            $data_informasi->where('transaksi.jenis_transaksi', $jenis_transaksi);
        }
        if ($jenis_layanan != ""){
            $data_informasi->where('transaksi.jenis_layanan', $jenis_layanan);
        }
        if ($status_pembayaran != ""){
            $data_informasi->where('transaksi.status_pembayaran', $status_pembayaran);
        } 
        $data_informasi = $data_informasi->first();
        $qrcode_no_nota = base64_encode(QrCode::format('svg')
            ->size(75)
            ->margin(1)
            ->generate(base64_encode($data_informasi->id_perusahaan)));
        $atas_nama_nota = Pegawai::where('atas_nama_kuitansi', 1)->first();
        $qrcode_dokter = base64_encode(QrCode::format('svg')
            ->size(75)
            ->margin(1)
            ->generate($atas_nama_nota->nik));
        $data = [
            'title' => 'Cetak Kuitansi Perusahaan',
            'nama_perusahaan' => $data_informasi->nama_peserta,
            'jumlah_peserta' => $data_informasi->jumlah_peserta,
            'qrcode_no_nota' => $qrcode_no_nota,
            'qrcode_dokter' => $qrcode_dokter,
            'atas_nama_nota' => $atas_nama_nota->nama_pegawai,
            'nip' => $atas_nama_nota->nik,
            'keterangan' => $keterangan == "" ? "Kuitansi Untuk Perusahaan Periode ".Carbon::parse($data_informasi->tanggal_awal)->format('d M Y')." s/d ".Carbon::parse($data_informasi->tanggal_akhir)->format('d M Y') : $keterangan,
            'tanggal_cetak' => $tanggal_cetak,
            'total_pembayaran' => "Rp ".number_format($data_informasi->total_pembayaran + $data_informasi->nominal_apotek,2,",","."),
            'terbilang' => ucwords(GlobalHelper::terbilang($data_informasi->total_pembayaran + $data_informasi->nominal_apotek))." Rupiah"
        ];
        $folderPath = 'public/kuitansi/perusahaan/';
        $filename = "KUITANSI_".date('YmdHis').".pdf";
        $fullPath = storage_path("app/$folderPath$filename");
        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath, 0755, true);
        }
        if (!file_exists($fullPath)) {
            $pdf = PDF::loadView('paneladmin.laporan.kuitansi.pdf_kuitansi_perusahaan', ['data' => $data])
                ->setPaper('legal', 'portrait')
                ->setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
            $pdf->render();
            $pdf->save($fullPath);
        }
        return response()->file($fullPath);
    }
    
    public function cetak_kuitansi_tagihan_perusahaan(Request $req){
        $dataparameter = json_decode(base64_decode($req->query('data')), true);
        $tanggal_cetak = date('d').' '.GlobalHelper::getNamaBulanIndonesia(date('n')).' '.date('Y');
        $id_perusahaan = $dataparameter['id_perusahaan'];
        $kode_perusahaan = $dataparameter['kode_perusahaan'];
        $nama_perusahaan = $dataparameter['nama_perusahaan'];
        $keterangan = $dataparameter['keterangan'];
        $jenis_kuitansi = $dataparameter['jenis_kuitansi'];
        $jenis_transaksi = $dataparameter['jenis_transaksi'];
        $jenis_layanan = $dataparameter['jenis_layanan'];
        $status_pembayaran = $dataparameter['status_pembayaran'];
        $tablePrefix = config('database.connections.mysql.prefix');
        $data_informasi = TransaksiLab::join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'transaksi.no_mcu')
            ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
            ->join('transaksi_detail', 'transaksi_detail.id_transaksi', '=', 'transaksi.id')
            ->where('mcu_transaksi_peserta.perusahaan_id', $id_perusahaan)
            ->selectRaw('
                '.$tablePrefix.'transaksi_detail.id_item AS id_item,
                '.$tablePrefix.'mcu_transaksi_peserta.no_transaksi AS no_nota,
                '.$tablePrefix.'transaksi_detail.nama_item AS nama_item,
                '.$tablePrefix.'company.id AS id_perusahaan,
                '.$tablePrefix.'company.company_name AS nama_perusahaan,
                '.$tablePrefix.'mcu_transaksi_peserta.jenis_transaksi_pendaftaran AS jenis_layanan,
                '.$tablePrefix.'transaksi.waktu_trx AS tanggal_awal,
                '.$tablePrefix.'transaksi.waktu_trx AS tanggal_akhir,
                SUM('.$tablePrefix.'transaksi_detail.jumlah) AS jumlah_qty,
                '.$tablePrefix.'transaksi_detail.harga_setelah_diskon AS harga_setelah_diskon,
                SUM('.$tablePrefix.'transaksi.nominal_apotek) AS nominal_apotek,
                SUM('.$tablePrefix.'transaksi.total_transaksi) AS total_transaksi,
                '.$tablePrefix.'transaksi.is_paket_mcu AS apakah_paket,
                '.$tablePrefix.'transaksi.nama_paket_mcu AS nama_paket_mcu
            ');
        if ($jenis_transaksi != ""){
            $data_informasi->where('transaksi.jenis_transaksi', $jenis_transaksi);
        }
        if ($jenis_layanan != ""){
            $data_informasi->where('transaksi.jenis_layanan', $jenis_layanan);
        }
        if ($status_pembayaran != ""){
            $data_informasi->where('transaksi.status_pembayaran', $status_pembayaran);
        } 
        $data_informasi = $data_informasi->groupby('mcu_transaksi_peserta.jenis_transaksi_pendaftaran','transaksi_detail.id_item')->get();
        $first_row = $data_informasi->first();
        $pattern = '/\/MCU\/(?:[^\/]+)\/(.+)/';
        preg_match($pattern, $first_row->no_nota, $matches);
        $bagian_dinamis = Carbon::parse($first_row->tanggal_awal)->format('dmY') . Carbon::parse($first_row->tanggal_akhir)->format('dmY');
        $new_nota = 'T/' . $bagian_dinamis."/".$matches[1];;
        $qrcode_no_nota = base64_encode(QrCode::format('svg')
            ->size(75)
            ->margin(1)
            ->generate(base64_encode($first_row->id_perusahaan)));
        $atas_nama_nota = Pegawai::where('atas_nama_kuitansi', 1)->first();
        $qrcode_dokter = base64_encode(QrCode::format('svg')
            ->size(75)
            ->margin(1)
            ->generate($atas_nama_nota->nik));
        $data = [
            'title' => 'Cetak Kuitansi Perusahaan',
            'detail_tagihan' => $data_informasi,
            'nama_perusahaan' => $first_row->nama_perusahaan,
            'no_transaksi_combine' => $new_nota,
            'qrcode_no_nota' => $qrcode_no_nota,
            'qrcode_dokter' => $qrcode_dokter,
            'atas_nama_nota' => $atas_nama_nota->nama_pegawai,
            'nip' => $atas_nama_nota->nik,
            'keterangan' => $keterangan == "" ? "Kuitansi Untuk Perusahaan Periode ".Carbon::parse($first_row->tanggal_awal)->format('d M Y')." s/d ".Carbon::parse($first_row->tanggal_akhir)->format('d M Y') : $keterangan,
            'tanggal_cetak' => $tanggal_cetak,
        ];
        $folderPath = 'public/kuitansi/tagihan/';
        $filename = "TAGIHAN_".date('YmdHis').".pdf";
        $fullPath = storage_path("app/$folderPath$filename");
        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath, 0755, true);
        }
        if (!file_exists($fullPath)) {
            $pdf = PDF::loadView('paneladmin.laporan.kuitansi.pdf_kuitansi_tagihan_perusahaan', ['data' => $data])
                ->setPaper('legal', 'portrait')
                ->setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
            $pdf->render();
            $pdf->save($fullPath);
        }
        return response()->file($fullPath);
    }
}

