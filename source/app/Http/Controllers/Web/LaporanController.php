<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Log;
use App\Models\Transaksi\{Transaksi, UnggahCitra, LingkunganKerjaPeserta, RiwayatKecelakaanKerja, RiwayatKebiasaanHidup, RiwayatPenyakitTerdahulu, RiwayatPenyakitKeluarga, RiwayatImunisasi,UnggahanCitraLab};
use App\Models\PemeriksaanFisik\{TingkatKesadaran, TandaVital, Penglihatan};
use App\Models\PemeriksaanFisik\KondisiFisik\{KondisiFisik, Gigi};
use App\Models\Laboratorium\{Kesimpulan as KesimpulanLabStatus, Transaksi as TransaksiLab, Kategori, TransaksiDetail};
use App\Models\Laporan\{Kesimpulan,EdsStatusCekKesimpulan};
use App\Helpers\QuillHelper;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Helpers\GlobalHelper;
use Illuminate\Support\Facades\DB;
use App\Models\Poliklinik\{Poliklinik, UnggahanCitra};
use Illuminate\Support\Facades\Storage;
use App\Models\Pegawai;
use Carbon\Carbon;
use App\Models\User;
use Codedge\Fpdf\Fpdf\Fpdf;


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
        $data['status_validasi'] = EdsStatusCekKesimpulan::where('no_mcu', base64_decode($no_nota))->first();
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
        $user = $req->attributes->get('user_details');
        $perusahaan = json_decode($user->json_perusahaan, true) ?? [];
        $ids = array_column($perusahaan, 'id');
        $data = $this->getData($req, 'Berkas Tindakan MCU', [
            'Beranda' => route('admin.beranda'),
            'Berkas' => route('admin.laporan.berkas_mcu'),
        ]);
        $data['jenis_berkas'] = $req->segment(2);
        $data['id_perusahaan'] = $ids;
        return view('paneladmin.laporan.berkas.berkas_mcu', ['data' => $data]);
    }
    public function berkas_mcu_threadmill(Request $req){
        $user = $req->attributes->get('user_details');
        $perusahaan = json_decode($user->json_perusahaan, true) ?? [];
        $ids = array_column($perusahaan, 'id');
        $data = $this->getData($req, 'Berkas Treadmill', [
            'Beranda' => route('admin.beranda'),
            'Berkas' => route('admin.laporan.berkas_mcu_threadmill'),
        ]);
        $data['jenis_berkas'] = $req->segment(2);
        $data['id_perusahaan'] = $ids;
        return view('paneladmin.laporan.berkas.berkas_mcu_threadmill', ['data' => $data]);
    }
    private function determineTableNamePemeriksaanFisik($lokasiFisik){
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
    private function formatItem($detail, $id_transaksi){
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
                'users_pegawai.nik as nik_petugas',
                'petugas.nama_pegawai as nama_petugas',
                'petugas.departemen as departemen_petugas',
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
    public function cetak_berkas_mcu(Request $req, Fpdf $fpdf){
        $dataparameter = json_decode(base64_decode($req->query('data')), true);
        $tanggal_cetak = date('d').' '.GlobalHelper::getNamaBulanIndonesia(date('n')).' '.date('Y');
        $id_mcu = $dataparameter['id_mcu'];
        $nomor_mcu = $dataparameter['nomor_mcu'];
        $nik_peserta = $dataparameter['nik_peserta'];
        $tablePrefix = config('database.connections.mysql.prefix');
        $riwayat_informasi_foto = UnggahCitra::where('transaksi_id', $id_mcu)->first();
        $status_kesimpulan_lab = KesimpulanLabStatus::all();
        $groupedData = $status_kesimpulan_lab->groupBy('status');
        $informasi_data_pegawai = Pegawai::all();
        $pegawaiMap = $informasi_data_pegawai->keyBy('nik')->toArray();
        $informasi_data_diri = Transaksi::join('users_member', 'users_member.id', '=', 'mcu_transaksi_peserta.user_id')
            ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
            ->join('departemen_peserta', 'departemen_peserta.id', '=', 'mcu_transaksi_peserta.departemen_id')
            ->select('users_member.nama_peserta', 'users_member.nomor_identitas', 'users_member.tempat_lahir', 'users_member.tanggal_lahir', 'users_member.jenis_kelamin', 'users_member.alamat', 'company.company_name', 'departemen_peserta.nama_departemen', 'mcu_transaksi_peserta.tanggal_transaksi as tanggal_mcu', 'mcu_transaksi_peserta.jenis_transaksi_pendaftaran','mcu_transaksi_peserta.tipe_mcu_peserta')
            ->selectRaw('TIMESTAMPDIFF(YEAR, ' . $tablePrefix . 'users_member.tanggal_lahir, CURDATE()) AS umur')
            ->where('mcu_transaksi_peserta.id', $id_mcu)->first();
        $riwayat_informasi_foto->data_foto = url(env('APP_VERSI_API')."/file/unduh_foto?file_name=" . $riwayat_informasi_foto->lokasi_gambar);
        $riwayat_informasi_foto->data_signature = url(env('APP_VERSI_API')."/file/unduh_foto_signature?file_name=" . $riwayat_informasi_foto->signature);
        $logoPath = public_path('mofi/assets/images/logo/Logo_AMC_Full.png');
        $qrcode = base64_encode(QrCode::format('png')
                    ->size(200)
                    ->margin(1)
                    ->merge($logoPath, 0.2, true)
                    ->generate($informasi_data_diri->nomor_identitas));
        $riwayat_penyakit_terdahulu = RiwayatPenyakitTerdahulu::where('transaksi_id', $id_mcu)->get();
        $riwayat_penyakit_keluarga = RiwayatPenyakitKeluarga::where('transaksi_id', $id_mcu)->get();
        $riwayat_kecelakaan_kerja = RiwayatKecelakaanKerja::where('transaksi_id', $id_mcu)->first();
        $riwayat_kebiasaan_hidup = RiwayatKebiasaanHidup::where('transaksi_id', $id_mcu)->get();
        $riwayat_imunisasi = RiwayatImunisasi::where('transaksi_id', $id_mcu)->get();
        $riwayat_lingkungan_kerja = LingkunganKerjaPeserta::where('transaksi_id', $id_mcu)->get();
        $tingkat_kesadaran = TingkatKesadaran::where('transaksi_id', $id_mcu)->first();
        $tanda_vital = TandaVital::where('transaksi_id', $id_mcu)->get();
        $penglihatan = Penglihatan::where('transaksi_id', $id_mcu)->get();
        $transaksi_laboratorium = TransaksiLab::where('no_mcu', $id_mcu)->first();
        $kesimpulan_tindakan = Kesimpulan::join('lab_kesimpulan', 'lab_kesimpulan.id', '=', 'transaksi_kesimpulan.kesimpulan_keseluruhan')->where('transaksi_kesimpulan.id_mcu', $id_mcu)->first();
        $kategori_pemeriksaan = ['kepala','telinga','mata','tenggorokan','mulut','gigi','leher','thorax','abdomen_urogenital','anorectal_genital','ekstremitas','neurologis'];
        $query_kondisi_fisik = "";
        $ada_lampiran_laboratorium_pdf = $transaksi_laboratorium?->lampirkan_berkas_pdf ?? '0';
        $total_tindakan = $transaksi_laboratorium?->total_tindakan ?? '0';
        foreach ($kategori_pemeriksaan as $kategori) {
            $subquery = DB::table($this->determineTableNamePemeriksaanFisik($kategori))
                ->select([
                    DB::raw("'$kategori' AS kategori"),
                    'kategori_atribut',
                    'jenis_atribut',
                    'status_atribut',
                    'keterangan_atribut',
                    'nama_atribut',
                    'transaksi_id'
                ])
                ->where('transaksi_id', $id_mcu);
            if ($query_kondisi_fisik) {
                $query_kondisi_fisik->union($subquery);
            } else {
                $query_kondisi_fisik = $subquery;
            }
        }
        $orderString = "'" . implode("','", $kategori_pemeriksaan) . "'";
        $data_kondisi_fisik = $query_kondisi_fisik
            ? $query_kondisi_fisik
                ->orderByRaw("FIELD(kategori, $orderString)")
                ->orderBy('kategori_atribut')
                ->orderByRaw("
                    CASE 
                        WHEN jenis_atribut = 'Lainnya' THEN 1
                        ELSE 0
                    END
                ")
                ->orderBy('jenis_atribut')
                ->get()
            : collect([]);

        $data_kondisi_fisik = $data_kondisi_fisik->toArray();
        $laboratorium = $this->getHasilLaboratorium($id_mcu);
        $model = new Poliklinik();
        $jenis_polis = ['spirometri', 'ekg', 'threadmill', 'rontgen_thorax', 'rontgen_lumbosacral', 'audiometri', 'usg_ubdomain', 'farmingham_score'];
        $all_citra_data = collect();
        $all_citra_laboratorium = collect();
        foreach ($jenis_polis as $jenis_poli) {
            $citra_data = $this->fetchInformasiPoliklinik($jenis_poli, $id_mcu, $model);
            $all_citra_data = $all_citra_data->merge($citra_data);
        }
        $idTrxLab = $transaksi_laboratorium?->id;
        $lampiran_berkas_pdf = $idTrxLab
            ? UnggahanCitraLab::where('id_trx_lab', $idTrxLab)->get()
            : collect();
        $lampiran_berkas_pdf = $lampiran_berkas_pdf->map(function ($item) {
            $item->data_foto = url(env('APP_VERSI_API') . "/file/unduh_lampiran_pdf?file_name=" . $item->nama_file);
            return $item;
        });

        $data = [
            'title' => 'Berkas Tindakan MCU',
            'id_mcu' => $id_mcu,
            'nomor_mcu' => $nomor_mcu,
            'nik_peserta' => $nik_peserta,
            'ada_lampiran_laboratorium_pdf' => $ada_lampiran_laboratorium_pdf,
            'total_tindakan' => $total_tindakan ,
            'tanggal_cetak' => $tanggal_cetak,
            'qrcode' => $qrcode,
            'pegawai_map' => $pegawaiMap,
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
            'quill_pemeriksaan_tanda_vital_dan_gizi' => $kesimpulan_tindakan->kesimpulan_pemeriksaan_tanda_vital_dan_gizi,
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
            'lampiran_berkas_pdf' => $lampiran_berkas_pdf
        ];
        $vendorFontPath = base_path('vendor/codedge/laravel-fpdf/src/Fpdf/font/');
        $customFontPath = storage_path('app/public/fonts/');
        $fpdf = new class($data) extends \Codedge\Fpdf\Fpdf\Fpdf {
            protected $data;
            public function __construct($data) {
                parent::__construct('P', 'mm', 'A4'); 
                $this->data = $data;
            }
            public function gantiPath($path) { $this->fontpath = $path;}
            protected $extgstates = array();
            function SetAlpha($alpha, $bm='Normal'){
                $gs = $this->AddExtGState(array('ca'=>$alpha, 'CA'=>$alpha, 'BM'=>'/'.$bm));
                $this->SetExtGState($gs);
            }
            function AddExtGState($parms){
                $n = count($this->extgstates)+1;
                $this->extgstates[$n]['parms'] = $parms;
                return $n;
            }
            function SetExtGState($gs){
                $this->_out(sprintf('/GS%d gs', $gs));
            }
            function _enddoc(){
                if(!empty($this->extgstates) && $this->PDFVersion<'1.4')
                    $this->PDFVersion='1.4';
                parent::_enddoc();
            }
            function _putextgstates(){
                for ($i = 1; $i <= count($this->extgstates); $i++)
                {
                    $this->_newobj();
                    $this->extgstates[$i]['n'] = $this->n;
                    $this->_put('<</Type /ExtGState');
                    $parms = $this->extgstates[$i]['parms'];
                    $this->_put(sprintf('/ca %.3F', $parms['ca']));
                    $this->_put(sprintf('/CA %.3F', $parms['CA']));
                    $this->_put('/BM '.$parms['BM']);
                    $this->_put('>>');
                    $this->_put('endobj');
                }
            }
            function _putresourcedict(){
                parent::_putresourcedict();
                $this->_put('/ExtGState <<');
                foreach($this->extgstates as $k=>$extgstate)
                    $this->_put('/GS'.$k.' '.$extgstate['n'].' 0 R');
                $this->_put('>>');
            }
            function _putresources(){
                $this->_putextgstates();
                parent::_putresources();
            }
            function AliasNbPages($alias = '{total_hal}') {
                parent::AliasNbPages($alias);
            }
            function _putpages() {
                $total_sebenarnya = count($this->pages) - 1;
                for($n=1; $n<=count($this->pages); $n++) {
                    $this->pages[$n] = str_replace('{total_hal}', $total_sebenarnya, $this->pages[$n]);
                }
                parent::_putpages();
            }
            private function bulletRow($label, $value) {
                $this->SetFont('Times', '', 10);
                $this->Cell(5, 6, chr(149), 0, 0, 'C'); // Karakter Bullet
                $this->Cell(50, 6, $label, 0, 0, 'L');
                $this->Cell(5, 6, ':', 0, 0, 'C');
                $this->MultiCell(0, 6, $value, 0, 'L');
            }
            function RoundedRect($x, $y, $w, $h, $r, $style = '', $angle = '1234') {
                $k = $this->k;
                $hp = $this->h;
                if($style=='F') $op='f';
                elseif($style=='FD' || $style=='DF') $op='B';
                elseif($style=='CN') $op='W n'; // Khusus untuk Clipping
                else $op='S';
                
                $MyArc = 4/3 * (sqrt(2) - 1);
                $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k));

                $xc = $x+$w-$r; $yc = $y+$r;
                $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k));
                if (strpos($angle, '2')===false) $this->_out(sprintf('%.2F %.2F l', ($x+$w)*$k,($hp-$y)*$k));
                else $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);

                $xc = $x+$w-$r; $yc = $y+$h-$r;
                $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
                if (strpos($angle, '3')===false) $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-($y+$h))*$k));
                else $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);

                $xc = $x+$r; $yc = $y+$h-$r;
                $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
                if (strpos($angle, '4')===false) $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-($y+$h))*$k));
                else $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);

                $xc = $x+$r ; $yc = $y+$r;
                $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k));
                if (strpos($angle, '1')===false) {
                    $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$y)*$k));
                    $this->_out(sprintf('%.2F %.2F l',($x+$r)*$k,($hp-$y)*$k));
                } else $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
                $this->_out($op);
            }
            function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {
                $h = $this->h;
                $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k, 
                    $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
            }
            function ClippingRoundedRect($x, $y, $w, $h, $r, $outline=false, $angle='23') {
                $this->_out(sprintf('q %.2F %.2F m', ($x+$r)*$this->k, ($this->h-$y)*$this->k));
                // ... (Logika path rounded sama dengan RoundedRect sebelumnya)
                // Bedanya di akhir kita panggil 'W n' untuk clipping
                $this->RoundedRect($x, $y, $w, $h, $r, 'CN', $angle); 
            }
            function StartRoundedClipping($x, $y, $w, $h, $r, $angle='23') {
                $this->_out('q'); // Save state
                $this->RoundedRect($x, $y, $w, $h, $r, 'CN', $angle); // Buat jalur potong
                $this->_out('W n'); // Aktifkan clipping
            }
            function StopClipping() {
                $this->_out('Q'); // Restore state (mematikan clipping)
            }
            private function hexToRgb($hex) {
                $hex = str_replace("#", "", $hex);
                if(strlen($hex) == 3) {
                    $r = hexdec(substr($hex,0,1).substr($hex,0,1));
                    $g = hexdec(substr($hex,1,1).substr($hex,1,1));
                    $b = hexdec(substr($hex,2,1).substr($hex,2,1));
                } else {
                    $r = hexdec(substr($hex,0,2));
                    $g = hexdec(substr($hex,2,2));
                    $b = hexdec(substr($hex,4,2));
                }
                return [$r, $g, $b];
            }
            function GetNbLines($w, $txt) {
                $cw = &$this->CurrentFont['cw'];
                if($w == 0) $w = $this->w - $this->rMargin - $this->x;
                $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
                $s = str_replace("\r", '', $txt);
                $nb = strlen($s);
                if($nb > 0 and $s[$nb-1] == "\n") $nb--;
                $sep = -1; $i = 0; $j = 0; $l = 0; $nl = 1;
                while($i < $nb) {
                    $c = $s[$i];
                    if($c == "\n") { $i++; $sep = -1; $j = $i; $l = 0; $nl++; continue; }
                    if($c == ' ') $sep = $i;
                    $l += $cw[$c];
                    if($l > $wmax) {
                        if($sep == -1) { if($i == $j) $i++; } else $i = $sep + 1;
                        $sep = -1; $j = $i; $l = 0; $nl++;
                    } else $i++;
                }
                return $nl;
            }
            function CheckPageBreak($h) {
                if($this->GetY() + $h > $this->PageBreakTrigger)
                    $this->AddPage($this->CurOrientation);
            }
            function GenerateRow($widths, $data, $lineHeight = 5, $fill = false) {
                // Cari tinggi maksimal baris
                $maxNb = 0;
                foreach($data as $i => $txt) {
                    $nb = $this->GetNbLines($widths[$i], $txt);
                    if($nb > $maxNb) $maxNb = $nb;
                }
                $h = max($maxNb * $lineHeight, 7);

                // Cek Page Break
                if($this->GetY() + $h > 270) $this->AddPage();

                // Warna Zebra Cross
                $this->SetFillColor($fill ? 245 : 255, $fill ? 245 : 255, $fill ? 245 : 255);

                $startX = $this->GetX();
                $startY = $this->GetY();

                foreach($data as $i => $txt) {
                    $this->SetXY($startX, $startY);
                    
                    if($i == count($data)-1) {
                        // Kolom terakhir (KETERANGAN)
                        $this->Cell($widths[$i], $h, '', 0, 0, 'L', true); 
                        $this->SetXY($startX, $startY);
                        $this->MultiCell($widths[$i], $lineHeight, $txt, 0, 'C');
                    } else {
                        // Kolom 0-3
                        $align = ($i == 0) ? 'L' : 'C';
                        $padding = ($i == 0) ? "  " : "";
                        $this->Cell($widths[$i], $h, $padding . $txt, 0, 0, $align, true);
                        $startX += $widths[$i];
                    }
                }
                $this->SetY($startY + $h);
            }
            function CheckPageBreakWithHeader($widths, $headers, $totalWidth, &$yHeaderRef) {
                if ($this->GetY() + 15 > 270) {
                    $this->Rect(10, $yHeaderRef, $totalWidth, $this->GetY() - $yHeaderRef, 'D');
                    
                    $this->AddPage();
                    $this->drawHeaderMcuTable();
                    $this->Line(10, $this->GetY(), 200, $this->GetY());
                    $this->ln(5);
                    // 2. Reset koordinat Header untuk halaman baru
                    $yHeaderRef = $this->GetY();
                    
                    // 3. Cetak ulang Header Hijau
                    $this->SetFillColor(44, 148, 42);
                    $this->SetTextColor(255);
                    $this->SetFont('Times', 'B', 12);
                    foreach ($headers as $i => $title) {
                        $this->Cell($widths[$i], 8, $title, 0, 0, 'C', true);
                    }
                    $this->Ln();
                    
                    // Kembalikan font ke normal untuk isi tabel
                    $this->SetTextColor(0);
                    $this->SetFont('Times', '', 11);
                }
            }
            function drawCheckbox($x, $y, $hRow, $wCol, $isChecked, $border = 'LR') {
                $this->SetXY($x, $y);
                $this->Cell($wCol, $hRow, '', $border, 0, 'C'); // Gambar border sel
                
                // Gambar kotak kecil di tengah sel
                $boxSize = 4;
                $boxX = $x + ($wCol - $boxSize) / 2;
                $boxY = $y + ($hRow - $boxSize) / 2;
                
                $this->Rect($boxX, $boxY, $boxSize, $boxSize); // Kotak luar checkbox
                
                if ($isChecked) {
                    // 1. Simpan Font lama agar bisa dikembalikan nanti
                    $currentFont = $this->FontFamily;
                    $currentSize = $this->FontSizePt;

                    // 2. Set ke font ZapfDingbats
                    // Karakter '4' adalah centang biasa, '5' adalah centang tebal
                    $this->SetFont('ZapfDingbats', '', 10);
                    
                    // 3. Tentukan posisi teks agar berada di tengah kotak
                    // Kita gunakan Text() agar tidak mengganggu aliran Cell
                    $this->Text($boxX + 0.5, $boxY + $boxSize - 0.5, '4');

                    // 4. Kembalikan ke font awal
                    $this->SetFont($currentFont, '', $currentSize);
                }

                // Kembalikan kursor ke posisi setelah kolom
                $this->SetXY($x + $wCol, $y);
            }
            public function SetTextOutline($width) {
                // Set ketebalan garis (stroke)
                $this->SetLineWidth($width);
                // Mode 2 = Fill then Stroke
                $this->_out(sprintf('2 Tr %.2F w', $width * $this->k));
            }
            public function ResetTextOutline() {
                // Mode 0 = Fill saja (Normal)
                $this->_out('0 Tr');
            }
            //variables of html parser
            protected $B;
            protected $I;
            protected $U;
            protected $HREF;
            protected $fontlist;
            protected $issetfont;
            protected $issetcolor;
            protected $is_list;
            protected $list_count;
            function txtentities($html){
                $trans = get_html_translation_table(HTML_ENTITIES);
                $trans = array_flip($trans);
                return strtr($html, $trans);
            }
            function WriteHTML($html){
                //HTML parser
                $html=strip_tags($html,"<b><u><i><a><img><p><br><strong><em><font><tr><blockquote><ol><li>"); 
                $html=str_replace("\n",' ',$html); 
                $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
                foreach($a as $i=>$e)
                {
                    if($i%2==0)
                    {
                        //Text
                        if($this->HREF)
                            $this->PutLink($this->HREF,$e);
                        else
                            $this->Write(5,$this->txtentities($e));
                    }
                    else
                    {
                        //Tag
                        if($e[0]=='/')
                            $this->CloseTag(strtoupper(substr($e,1)));
                        else
                        {
                            //Extract attributes
                            $a2=explode(' ',$e);
                            $tag=strtoupper(array_shift($a2));
                            $attr=array();
                            foreach($a2 as $v)
                            {
                                if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                                    $attr[strtoupper($a3[1])]=$a3[2];
                            }
                            $this->OpenTag($tag,$attr);
                        }
                    }
                }
            }
            function OpenTag($tag, $attr){
                //Opening tag
                switch($tag){
                    case 'STRONG':
                        $this->SetStyle('B',true);
                        break;
                    case 'EM':
                        $this->SetStyle('I',true);
                        break;
                    case 'B':
                    case 'I':
                    case 'U':
                        $this->SetStyle($tag,true);
                        break;
                    case 'A':
                        $this->HREF=$attr['HREF'];
                        break;
                    case 'IMG':
                        if(isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
                            if(!isset($attr['WIDTH']))
                                $attr['WIDTH'] = 0;
                            if(!isset($attr['HEIGHT']))
                                $attr['HEIGHT'] = 0;
                            $this->Image($attr['SRC'], $this->GetX(), $this->GetY(), px2mm($attr['WIDTH']), px2mm($attr['HEIGHT']));
                        }
                        break;
                    case 'TR':
                    case 'BLOCKQUOTE':
                    case 'BR':
                        break;
                    case 'P':
                        break;
                    case 'FONT':
                        if (isset($attr['COLOR']) && $attr['COLOR']!='') {
                            $coul=hex2dec($attr['COLOR']);
                            $this->SetTextColor($coul['R'],$coul['V'],$coul['B']);
                            $this->issetcolor=true;
                        }
                        if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist)) {
                            $this->SetFont(strtolower($attr['FACE']));
                            $this->issetfont=true;
                        }
                        break;
                    case 'OL':
                        $this->is_list = true;
                        $this->list_count = 0;
                        break;
                        
                    case 'LI':
                        $this->Ln(5);
                        $this->SetX($this->GetX() + 5); // Geser ke kanan sedikit
                        if($this->is_list){
                            $this->list_count++;
                            $this->Write(5, $this->list_count . ". ");
                        } else {
                            $this->Write(5, chr(149) . " "); // Jika ingin bullet (•)
                        }
                        break;
                }
            }
            function CloseTag($tag){
                //Closing tag
                if($tag=='STRONG')
                    $tag='B';
                if($tag=='EM')
                    $tag='I';
                if($tag=='B' || $tag=='I' || $tag=='U')
                    $this->SetStyle($tag,false);
                if($tag=='A')
                    $this->HREF='';
                if($tag=='FONT'){
                    if ($this->issetcolor==true) {
                        $this->SetTextColor(0);
                    }
                    if ($this->issetfont) {
                        $this->SetFont('Times');
                        $this->issetfont=false;
                    }
                }
                if($tag == 'OL') {
                    $this->is_list = false;
                    $this->list_count = 0;
                    $this->Ln(2);
                }
                if($tag == 'LI') {}
            }
            function SetStyle($tag, $enable){
                //Modify style and select corresponding font
                $this->$tag+=($enable ? 1 : -1);
                $style='';
                foreach(array('B','I','U') as $s)
                {
                    if($this->$s>0)
                        $style.=$s;
                }
                $this->SetFont('',$style);
            }
            function PutLink($URL, $txt){
                //Put a hyperlink
                $this->SetTextColor(0,0,255);
                $this->SetStyle('U',true);
                $this->Write(5,$txt,$URL);
                $this->SetStyle('U',false);
                $this->SetTextColor(0);
            }


            function Header() {
                if ($this->PageNo() == 1) return;

                // 1. Ambil dimensi halaman saat ini secara dinamis
                $pageWidth = $this->GetPageWidth();
                $pageHeight = $this->GetPageHeight();
                $isLandscape = ($this->CurOrientation == 'L');

                // 2. Gambar Border Atas (Mengikuti lebar halaman)
                $this->SetAlpha(0.5);
                // Menggunakan $pageWidth agar gambar border menutupi seluruh lebar kertas
                $this->Image(public_path('mofi/assets/images/logo/border_hasil_mcu_atas.png'), 0, 0, $pageWidth);
                $this->SetAlpha(1);
                // 3. Logo AMC (Posisi tetap di kiri)
                $this->Image(public_path('mofi/assets/images/logo/Logo_AMC_Full.png'), 10, 0, 60);
                // 4. Pengaturan Teks Klinik (Menyesuaikan sisa lebar halaman)
                // Hitung sisa lebar untuk teks: Total Lebar - Margin Kiri Logo (60) - Margin Kanan (10)
                $textWidth = $pageWidth - 70; 
                $this->SetFont('Times', 'B', 25);
                $this->SetXY(60, 3);
                // Gunakan $textWidth agar alignment 'R' benar-benar mentok ke kanan kertas
                $this->Cell($textWidth, 10, 'Klinik Artha Medical Centre', 0, 1, 'R');
                $this->SetFont('Times', '', 12);
                $this->SetX(60);
                $this->MultiCell($textWidth, 4, "Jl. Sendawar Raya RT 029 Kel. Melak Ulu Kec. Melak, Kutai Barat 75765\nE-Mail: amc.clinic.yhs@gmail.com | website: arthamedicalcentre.com\nContact Person: 0812-3456-7890 | 0812-3456-7890", 0, 'R');
            }
            function drawHeaderMcuTable() {
                $this->ln(5);
                $d = $this->data['informasi_data_diri'];
                
                // 1. Hitung lebar halaman dinamis
                // GetPageWidth() memberikan 210 (P) atau 297 (L)
                $totalWidth = $this->GetPageWidth() - 20; // Dikurangi margin kiri 10 & kanan 10
                
                // 2. Tentukan persentase lebar kolom
                // Kita bagi dua kolom besar (Kiri & Kanan)
                $wLabel1 = $totalWidth * 0.13; // Kolom Label Kiri (~25mm di Portrait)
                $wValue1 = $totalWidth * 0.39; // Kolom Isi Kiri (~75mm di Portrait)
                $wLabel2 = $totalWidth * 0.11; // Kolom Label Kanan (~20mm di Portrait)
                $wValue2 = $totalWidth * 0.37; // Kolom Isi Kanan (~70mm di Portrait)

                $this->SetFont('Times', 'B', 10);
                $this->SetFillColor(255, 255, 255);
                $h = 4; // Tinggi baris

                // Baris 1
                $this->Cell($wLabel1, $h, 'Nama', 0, 0);
                $this->Cell($wValue1, $h, ': ' . strtoupper($d['nama_peserta']), 0, 0);
                $this->Cell($wLabel2, $h, 'No MCU', 0, 0);
                $this->Cell($wValue2, $h, ': ' . $this->data['nomor_mcu'], 0, 1);
                
                // Baris 2
                $this->Cell($wLabel1, $h, 'TTL / Umur', 0, 0);
                $this->Cell($wValue1, $h, ': ' . $d['tempat_lahir'] . ', ' . date('d-m-Y', strtotime($d['tanggal_lahir'])) . ' / ' . $d['umur'] . ' Thn', 0, 0);
                $this->Cell($wLabel2, $h, 'Tanggal', 0, 0);
                $this->Cell($wValue2, $h, ': ' . date('d-m-Y', strtotime($d['tanggal_mcu'])), 0, 1);

                // Baris 3
                $this->Cell($wLabel1, $h, 'NIK', 0, 0);
                $this->Cell($wValue1, $h, ': ' . $d['nomor_identitas'], 0, 0);
                $this->Cell($wLabel2, $h, 'Perusahaan', 0, 0);
                $this->Cell($wValue2, $h, ': ' . $d['company_name'], 0, 1);

                // Baris 4
                $this->Cell($wLabel1, $h, 'Jenis Kelamin', 0, 0);
                $this->Cell($wValue1, $h, ': ' . $d['jenis_kelamin'], 0, 0);
                $this->Cell($wLabel2, $h, 'Departemen', 0, 0);
                $this->Cell($wValue2, $h, ': ' . $d['nama_departemen'], 0, 1);

                // Baris 5
                $this->Cell($wLabel1, $h, 'Tipe MCU', 0, 0);
                $this->Cell($wValue1, $h, ': ' . $d['tipe_mcu_peserta'], 0, 0);
                $this->Cell($wLabel2, $h, 'Dokter', 0, 0);
                $this->Cell($wValue2, $h, ': dr. Muhammad Taufiq Amrullah, S.Ked' , 0, 1);
            }
            function Close() {
                // Logika pengurangan halaman
                $total_asli = $this->page; 
                $total_baru = ($total_asli > 1) ? ($total_asli - 1) : 1;

                for ($n = 1; $n <= $total_asli; $n++) {
                    // Mengganti placeholder {total_hal} dengan angka yang sudah dikurangi
                    $this->pages[$n] = str_replace('{total_hal}', $total_baru, $this->pages[$n]);
                }
                
                parent::Close(); // Melanjutkan proses internal FPDF
            }
            function Footer() {
                if ($this->PageNo() == 1) return;

                // $this->SetAlpha(0.5);
                // $this->Image(public_path('mofi/assets/images/logo/border_hasil_mcu_bawah.png'), 0, 256, 210);
                // $this->SetAlpha(1);

                $this->SetXY(0, 280);
                $this->SetFont('Times', '', 12);
                $hal_sekarang = $this->PageNo() - 1;
                $teks = $hal_sekarang . ' of {total_hal}';
                $this->SetX(150); 
                $this->Cell(50, 10, $teks, 0, 0, 'R');
                
                // Logo-logo sertifikasi (Kiri)
                $this->Image(public_path('mofi/assets/images/logo/IASCB.png'), 10, 275, 15);
                $this->Image(public_path('mofi/assets/images/logo/KEMENTAKER.png'), 25, 275, 15);
                $this->Image(public_path('mofi/assets/images/logo/VRC.png'), 40, 278, 25);
            }
            /*section 1*/
            public function renderProfilPeserta() {
                $this->SetAlpha(0.1);
                $this->Image(public_path('mofi/assets/images/logo/confidential_wlogo.png'), 50, 120, 110);
                $this->SetAlpha(1);
                // 1. Judul Tengah
                $this->SetFont('Times', 'B', 14);
                $this->Ln(10);
                $this->Cell(0, 7, 'PEMERIKSAAN KESEHATAN', 0, 1, 'C');
                $this->Cell(0, 7, '(MEDICAL CHECKUP)', 0, 1, 'C');
                $this->Ln(5);

                // 2. Foto Peserta (Sesuai img height: 250px)
                // Kita posisikan di tengah secara manual
                $fotoUrl = $this->data['riwayat_informasi_foto']['data_foto']?? null;
                if ($fotoUrl && @get_headers($fotoUrl)[0] == 'HTTP/1.1 404 Not Found') {
                    // Jika 404, ganti dengan gambar placeholder transparan atau default
                    $fotoUrl = public_path('/mofi/assets/images/logo/Logo_AMC_Full.png');
                }
                $xCenter = (210 - 50) / 2; // (Lebar kertas - lebar foto) / 2
                $this->Image($fotoUrl, $xCenter, $this->GetY(), 50, 65); 
                $this->Ln(70); // Kasih jarak setelah foto

                // 3. Tabel Identitas (Sesuai table width: 80%)
                $this->SetFont('Times', 'B', 12);
                $leftMargin = 25; // Agar terlihat center (width 80%)
                $this->SetX($leftMargin);
                
                $d = $this->data['informasi_data_diri'];
                $rows = [
                    ['NOMOR MEDICAL CHECKUP', ': ' . $this->data['nomor_mcu']],
                    ['NAMA PESERTA', ': ' . $d['nama_peserta']],
                    ['NIK / NRR', ': ' . $d['nomor_identitas']],
                    ['TEMPAT TGL LAHIR / UMUR', ': ' . $d['tempat_lahir'] . ', ' . date('d-m-Y', strtotime($d['tanggal_lahir'])) . ' / ' . $d['umur'] . ' Tahun'],
                    ['JENIS KELAMIN', ': ' . $d['jenis_kelamin']],
                    ['PERUSAHAAN', ': ' . $d['company_name']],
                    ['DEPARTEMEN JABATAN', ': ' . $d['nama_departemen']],
                    ['ALAMAT', ': ' . $d['alamat']],
                    ['TANGGAL MCU / TIPE MCU', ': ' . date('d-m-Y', strtotime($d['tanggal_mcu'])) . ' / ' . $d['tipe_mcu_peserta']],
                ];

                foreach ($rows as $row) {
                    $this->SetX($leftMargin);
                    $this->Cell(60, 8, $row[0], 0, 0);
                    $this->Cell(5, 8, '', 0, 0); // Spacer
                    
                    // Gunakan MultiCell jika Alamat terlalu panjang
                    if ($row[0] == 'ALAMAT') {
                        $this->MultiCell(100, 8, $row[1], 0, 'L');
                    } else {
                        $this->Cell(100, 8, $row[1], 0, 1);
                    }
                }

                // 4. ISO Certified (Sesuai position: absolute bottom: 80px)
                $this->SetY(-45);
                $this->SetX(10);
                $this->SetFont('Times', 'B', 11);
                $this->Cell(0, 5, 'ISO 9001:2015 Certified', 0, 1, 'L');
                $this->SetFont('Times', '', 11);
                $this->Cell(0, 5, '2410010019699K001', 0, 1, 'L');
                $this->SetFont('Times', 'B', 11);
                $this->Cell(0, 5, 'ISO 14001:2015 Certified', 0, 1, 'L');
                $this->SetFont('Times', '', 11);
                $this->Cell(0, 5, '24100100196914K001', 0, 1, 'L');
            }
            /*section 2*/
            public function renderLaporanKesimpulan() {
                $this->SetAlpha(0.1);
                $this->Image(public_path('mofi/assets/images/logo/confidential_wlogo.png'), 50, 120, 110);
                $this->SetAlpha(1);
                $this->drawHeaderMcuTable();
                $this->Line(10, $this->GetY(), 200, $this->GetY());
                $this->ln(5);
                $y_awal = $this->GetY();$lebar_bg = 110;$tinggi_bg = 10;
                $this->_out('q'); 
                $this->RoundedRect(10, $y_awal, 100, 10, 5, 'CN', '23'); 
                $this->Image(public_path('mofi/assets/images/logo/gradient_bg_title.png'), 10, $y_awal, 120, 10);
                $this->_out('Q');

                $this->SetTextColor(255, 255, 255);
                $this->SetFont('Times', 'B', 14);
                $this->Cell(0, 10, 'LAPORAN HASIL MEDICAL CHECKUP', 0, 1, 'L');
                
                $this->Ln(5);
                $this->SetTextColor(0, 0, 0);
                $this->SetFont('Times', 'B', 12);
                $this->Cell(0, 7, 'HASIL PEMERIKSAAN', 0, 1, 'L');

                // 2. Tabel Hasil Pemeriksaan
                $this->SetFont('Times', '', 10);
                
                $pemeriksaan = [
                    ['label' => 'RIWAYAT MEDIS', 'data' => $this->data['quill_pemeriksaan_riwayat_medis']],
                    ['label' => 'PEMERIKSAAN FISIK', 'data' => $this->data['quill_pemeriksaan_fisik']],
                    ['label' => 'PEMERIKSAAN MATA', 'data' => $this->data['penglihatan'][0]],
                    ['label' => 'HASIL LABORATORIUM', 'data' => $this->data['quill_pemeriksaan_laboratorium']],
                    ['label' => 'RO THORAX', 'data' => $this->data['quill_pemeriksaan_rontgen_thorax']],
                    ['label' => 'RO LUMBOSACRAL', 'data' => $this->data['quill_pemeriksaan_rontgen_lumbosacral']],
                    ['label' => 'USG UB DOMAIN', 'data' => $this->data['quill_pemeriksaan_usg_ubdomain']],
                    ['label' => 'EKG', 'data' => $this->data['quill_pemeriksaan_ekg']],
                ];

                foreach ($pemeriksaan as $item) {
                    // 1. Filter agar JSON mentah tidak muncul (seperti di gambar sebelumnya)
                    if (is_string($item['data']) && (strpos($item['data'], '{') !== false || strpos($item['data'], '[') !== false)) continue;
                    $cleanData = trim(strip_tags($item['data']));
                    $isJson = false;
                    if (is_string($item['data'])) {
                        $decoded = json_decode($item['data']);
                        if (json_last_error() === JSON_ERROR_NONE && is_object($decoded)) {
                            $isJson = true;
                        }
                    }

                    // Jika ini string JSON mentah DAN bukan label Pemeriksaan Mata, lewati agar tidak berantakan
                    if ($isJson && $item['label'] !== "PEMERIKSAAN MATA") {
                        continue;
                    }
                    if ($item['label'] === "PEMERIKSAAN MATA") {
                        $p = $this->data['penglihatan'][0];
                        // --- BARIS 1: Label & Data Visus ---
                        $this->SetFont('Times', '', 10); 
                        $this->SetTextColor(0, 0, 0);    
                        
                        // Gunakan struktur yang sama persis dengan fungsi bulletRow Anda
                        $this->Cell(5, 6, chr(149), 0, 0, 'C'); 
                        $this->Cell(50, 6, 'PEMERIKSAAN MATA', 0, 0, 'L');
                        $this->Cell(5, 6, ':', 0, 0, 'C');

                        // Isi Data Jauh
                        $text_pakai_kacamata = "(Tanpa Kacamata)";
                        $nilaiJauhOD = $p->visus_od_tanpa_kacamata_jauh;
                        $nilaiJauhOS = $p->visus_od_tanpa_kacamata_jauh;
                        $nilaiDekatOD = $p->visus_od_tanpa_kacamata_dekat;
                        $nilaiDekatOS = $p->visus_od_tanpa_kacamata_dekat;

                        if (!empty($p->visus_od_kacamata_jauh) || !empty($p->visus_os_kacamata_jauh) || !empty($p->visus_od_kacamata_dekat) || !empty($p->visus_os_kacamata_dekat)) {
                            $text_pakai_kacamata = "(Dengan Kacamata)";
                            $nilaiJauhOD = $p->visus_od_kacamata_jauh;
                            $nilaiJauhOS = $p->visus_os_kacamata_jauh;
                            $nilaiDekatOD = $p->visus_od_kacamata_dekat;
                            $nilaiDekatOS = $p->visus_os_kacamata_dekat;
                        }
                        $this->Write(6, 'Jauh : ');
                        $this->SetFont('Times', 'B', 10); $this->Write(6, 'OD ');
                        $this->SetFont('Times', '', 10);  $this->Write(6, ($nilaiJauhOD ?? '-') . ' ');
                        $this->SetFont('Times', 'B', 10); $this->Write(6, 'OS ');
                        $this->SetFont('Times', '', 10);  $this->Write(6, ($nilaiJauhOS ?? '-') . ',  ');

                        // Isi Data Dekat
                        $this->Write(6, 'Dekat : ');
                        $this->SetFont('Times', 'B', 10); $this->Write(6, 'OD ');
                        $this->SetFont('Times', '', 10);  $this->Write(6, ($nilaiDekatOD ?? '-') . ' ');
                        $this->SetFont('Times', 'B', 10); $this->Write(6, 'OS ');
                        $this->SetFont('Times', '', 10);  $this->Write(6, ($nilaiDekatOS ?? '-') . ' '.$text_pakai_kacamata);
                        $this->Ln(6);

                        // --- BARIS 2: Tes Buta Warna ---
                        // Sejajarkan posisi X (5mm bullet + 50mm label + 5mm titik dua = 60mm)
                        // Tambahkan sedikit offset manual (misal ke 65) agar teks lurus di bawah kata "Jauh"
                        $this->SetX(70); 
                        $this->SetFont('Times', 'B', 10);
                        $this->Write(6, 'Tes Buta Warna : ');
                        $this->SetFont('Times', '', 10);
                        $this->Write(6, $p->buta_warna_keterangan.', ');
                        $lapangPandang = 'Normal';
                        if (
                            $p->lapang_pandang_superior_os != '+' ||
                            $p->lapang_pandang_inferior_os != '+' ||
                            $p->lapang_pandang_temporal_os != '+' ||
                            $p->lapang_pandang_nasal_os != '+' ||
                            $p->lapang_pandang_superior_od != '+' ||
                            $p->lapang_pandang_inferior_od != '+' ||
                            $p->lapang_pandang_temporal_od != '+' ||
                            $p->lapang_pandang_nasal_od != '+'
                        ) {
                            $lapangPandang = 'Abnormal';
                        }
                        $this->SetFont('Times', 'B', 10);
                        $this->Write(6, 'Lapang Pandang : ');
                        $this->SetFont('Times', '', 10);
                        $this->Write(6, $lapangPandang);
                        $this->Ln(8);
                        
                        continue; 
                    }

                    if (!empty($cleanData)) {
                        $this->SetTextColor(0, 0, 0);
                        $this->bulletRow($item['label'], $cleanData);
                    }
                }

                // Audiometri (Logika Khusus)
                $audioKiri = trim(strip_tags($this->data['quill_pemeriksaan_audio_kiri']));
                $audioKanan = trim(strip_tags($this->data['quill_pemeriksaan_audio_kanan']));
                if (!empty($audioKiri) || !empty($audioKanan)) {
                    $this->bulletRow('AUDIOMETRI', ''); 
                    $this->SetY($this->GetY() - 6); 
                    $this->SetX(70); 
                    $this->SetFont('Times', 'B', 10);
                    $this->Write(6, 'Kiri: ');
                    $this->SetFont('Times', '', 10);
                    $this->Write(6, $audioKiri . '   ');
                    $this->SetFont('Times', 'B', 10);
                    $this->Write(6, 'Kanan: ');
                    $this->SetFont('Times', '', 10);
                    $this->Write(6, $audioKanan);
                    $this->Ln(6); 
                }

                // Spirometri (Logika Khusus)
                $spiroRes = trim(strip_tags($this->data['quill_pemeriksaan_spiro_restriksi']));
                $spiroObs = trim(strip_tags($this->data['quill_pemeriksaan_spiro_obstruksi']));
                if (!empty($spiroRes) || !empty($spiroObs)) {
                    $this->bulletRow('SPIROMETRI', '');
                    $this->SetY($this->GetY() - 6);
                    $this->SetX(70);
                    
                    $this->SetFont('Times', 'B', 10);
                    $this->Write(6, 'Restriksi: ');
                    $this->SetFont('Times', '', 10);
                    $this->Write(6, $spiroRes . '   ');
                    
                    $this->SetFont('Times', 'B', 10);
                    $this->Write(6, 'Obstruksi: ');
                    $this->SetFont('Times', '', 10);
                    $this->Write(6, $spiroObs);
                    $this->Ln(6);
                }
                $treadmillData = trim(strip_tags($this->data['quill_pemeriksaan_threadmill'] ?? ''));
                if (!empty($treadmillData)) {
                    $this->SetTextColor(0, 0, 0);
                    $this->bulletRow('TREADMILL', $treadmillData);
                }
                $this->Ln(5);
                $judul = "KESIMPULAN HASIL MEDICAL CHECKUP";
                $status = strtoupper(str_replace("_", " ", $this->data['kesimpulan_hasil_medical_checkup']));
                $startX = 11;
                $startY = $this->GetY();
                $width = 95;
                $height = 15;
                $this->SetLineWidth(1.0);
                $this->Rect($startX, $startY, $width, $height);
                // 4. Isi Teks Baris Pertama (Judul)
                $this->SetY($startY + 2); // Beri sedikit margin atas di dalam kotak
                $this->SetX($startX);
                $this->SetFont('Times', 'B', 12); // Font judul ukuran 14
                $this->Cell($width, 7, $judul, 0, 1, 'C'); // Border 0 karena sudah ada Rect
                // 5. Isi Teks Baris Kedua (Status/Hasil)
                $this->SetX($startX - 1);
                $this->SetFont('Times', 'B', 14); // Anda bisa buat status lebih besar lagi, misal 16
                $this->Cell($width, 3, $status, 0, 1, 'C');
                // 6. Kembalikan pengaturan ke standar
                $this->SetLineWidth(0.2);
                $this->SetY($startY + $height + 2); // Pindahkan kursor ke bawah kotak untuk konten berikutnya
                $this->SetFont('Times', 'B', 12);
                $this->Ln(2);
                $this->Cell(0, 7, 'SARAN HASIL MEDICAL CHECKUP', 0, 1, 'L');
                $this->SetFont('Times', '', 10);
                $this->MultiCell(0, 5, trim(strip_tags($this->data['quill_tindakan_saran'])), 0, 'L');

                // 5. Tanda Tangan (Footer Page)
                $this->SetY(-65); // Pindah ke area bawah halaman
                $ySkg = $this->GetY();
                
                // Sisi Kiri (QR Keaslian)
                $this->SetFont('Times', '', 10);
                $this->SetXY(10, $ySkg);
                $this->MultiCell(90, 4, "Pindai untuk periksa keaslian dokumen\nDokumen ini tervalidasi dan dicetak secara otomatis", 0, 'C');
                if($this->data['qrcode']) {
                    $this->Image('data://text/plain;base64,' . $this->data['qrcode'], 40, $this->GetY(), 25, 25, 'PNG');
                }

                // // Sisi Kanan (Dokter)
                $this->SetXY(110, $ySkg);
                $this->MultiCell(90, 4, "Mengetahui\nSendawar, " . $this->data['tanggal_cetak'], 0, 'C');
                if($this->data['qrcode']) {
                    $this->Image('data://text/plain;base64,' . $this->data['qrcode'], 142, $this->GetY(), 25, 25, 'PNG');
                }
                $this->SetXY(110, 265);
                
                // Nama Dokter (Gunakan posisi absolut agar tidak terdorong tinggi QR yang dinamis)
                $this->SetFont('Times', 'BU', 10);
                $this->SetXY(110, $ySkg + 35); // Letakkan tepat di bawah QR TTD (25 + margin 2)
                $this->MultiCell(90, 4, 'dr. Muhammad Taufiq Amrullah, S.Ked', 0, 'C');

                // SIP Dokter (Tanpa Bold/Underline)
                $this->SetFont('Times', 'B', 10);
                $this->SetX(110); 
                $this->MultiCell(90, 4, '440.007.2/127/SIP-DINKES/XI/2023', 0, 'C');
            }
            /*section 3*/
            public function statusKesehatan(){
                $this->drawHeaderMcuTable();
                $this->Line(10, $this->GetY(), 200, $this->GetY());
                $this->ln(5);
                // Judul "STATUS KESEHATAN"
                $y_awal = $this->GetY();$lebar_bg = 110;$tinggi_bg = 10;
                $this->_out('q'); 
                $this->RoundedRect(10, $y_awal, 65, 10, 5, 'CN', '23'); 
                $this->Image(public_path('mofi/assets/images/logo/gradient_bg_title.png'), 10, $y_awal, 120, 10);
                $this->_out('Q');

                $this->SetTextColor(255, 255, 255);
                $this->SetFont('Times', 'B', 14);
                $this->Cell(0, 10, 'STATUS KESEHATAN', 0, 1, 'L');
                $this->Ln(2);

                $groupedItems = [];
                foreach ($this->data['status_kesimpulan_lab'] as $status => $items) {
                    foreach ($items as $item) {
                        $raw_status = strtoupper(trim($item->status));
                        $normalized_status = (str_contains($raw_status, 'FIT TO WORK') || str_contains($raw_status, 'FIT WITH NOTE')) 
                                            ? 'FIT' : $raw_status;
                        $groupedItems[$normalized_status][] = $item;
                    }
                }

                $target_kesimpulan = strtoupper(trim($this->data['kesimpulan_hasil_medical_checkup']));
                if (str_contains($target_kesimpulan, 'FIT_TO_WORK') || str_contains($target_kesimpulan, 'FIT_WITH_NOTE')) {
                    $target_kesimpulan = 'FIT';
                }
                $id_terpilih = $this->data['kesimpulan_tindakan']->kesimpulan_keseluruhan;
                $id_terpilih_hex = $this->data['kesimpulan_tindakan']->kesimpulan_warna;
                $rgbTerpilih = $this->hexToRgb($id_terpilih_hex);
                // --- 3. Tabel MCU ---
                // Header
                $this->SetFillColor(44, 148, 42);
                $this->SetTextColor(255, 255, 255);
                $this->SetFont('Times', 'B', 12);
                $this->Cell(40, 8, 'STATUS', 1, 0, 'C', true);
                $this->Cell(40, 8, 'KATEGORI', 1, 0, 'C', true);
                $this->Cell(110, 8, 'CATATAN', 1, 1, 'C', true);
                $this->SetTextColor(0, 0, 0);
                $this->SetFont('Times', '', 11);
                $lineHeight = 7;
                foreach ($groupedItems as $normalized_status => $items) {
                    // A. Hitung total tinggi untuk kolom STATUS (Rowspan)
                    $groupHeight = 0;
                    foreach ($items as $item) {
                        $nb = $this->GetNbLines(110, $item->catatan);
                        $groupHeight += ($nb * $lineHeight);
                    }

                    $startX = $this->GetX();
                    $startY = $this->GetY();

                    // B. Cetak Kolom STATUS (Hanya 1x per grup)
                    $isHighlightedGroup = ($normalized_status == $target_kesimpulan);
                    $this->SetFillColor($isHighlightedGroup ? $rgbTerpilih[0] : 255, $isHighlightedGroup ? $rgbTerpilih[1] : 255, $isHighlightedGroup ? $rgbTerpilih[2] : 255);
                    $this->Cell(40, $groupHeight, $normalized_status, 1, 0, 'C', true);

                    // C. Cetak baris-baris di dalam grup
                    foreach ($items as $index => $item) {
                        // Hitung tinggi spesifik baris ini berdasarkan kolom CATATAN
                        $nbLines = $this->GetNbLines(110, $item->catatan);
                        $rowHeight = $nbLines * $lineHeight;

                        // Tentukan warna baris ini
                        if ($item->id == $id_terpilih) {
                            $this->SetFillColor($rgbTerpilih[0], $rgbTerpilih[1], $rgbTerpilih[2]);
                        } else {
                            $this->SetFillColor(255, 255, 255);
                        }

                        // Pindah ke posisi kolom KATEGORI
                        $this->SetXY($startX + 40, $this->GetY());
                        // Kolom KATEGORI (Tinggi harus sama dengan rowHeight)
                        $this->Cell(40, $rowHeight, $item->kategori, 1, 0, 'C', true);
                        // Kolom CATATAN (Gunakan MultiCell)
                        $this->MultiCell(110, $lineHeight, $item->catatan, 1, 'L', true);
                    }
                }
                // --- 4. Keterangan & Catatan ---
                $this->Ln(5);
                $this->SetFont('Times', 'B', 12);
                $this->Cell(0, 5, 'KETERANGAN:', 0, 1);
                
                // Legend (Simulasi kotak warna)
                $this->SetFont('Times', '', 9);
                $yLeg = $this->GetY() + 2;
                $lebarKotak = 40;
                $tinggiKotak = 5;
                $jarakAntar = 1;
                // Contoh satu kotak legend
                // 1. SEHAT (Hijau Muda)
                $this->SetFillColor(144, 238, 144); 
                $this->Rect(10, $yLeg, $lebarKotak, $tinggiKotak, 'DF'); 
                $this->SetXY(10, $yLeg + $tinggiKotak + 1);
                $this->MultiCell($lebarKotak, 4, 'SEHAT', 0, 'C');

                // 2. SEHAT RESIKO RINGAN (Kuning)
                $newX = 10 + $lebarKotak + $jarakAntar;
                $this->SetFillColor(255, 255, 0);
                $this->Rect($newX, $yLeg, $lebarKotak, $tinggiKotak, 'DF');
                $this->SetXY($newX, $yLeg + $tinggiKotak + 1);
                $this->MultiCell($lebarKotak, 4, 'SEHAT RESIKO RINGAN', 0, 'C');

                // 3. RESIKO SEDANG / TINGGI (Oranye)
                $newX = 10 + (($lebarKotak + $jarakAntar) * 2);
                $this->SetFillColor(255, 165, 0);
                $this->Rect($newX, $yLeg, $lebarKotak + 10, $tinggiKotak, 'DF');
                $this->SetXY($newX, $yLeg + $tinggiKotak + 1);
                $this->MultiCell($lebarKotak + 10, 3.5, "RESIKO SEDANG / TINGGI DAN\nPERLU PENGOBATAN", 0, 'C');

                // 4. TIDAK SEHAT (Merah Muda)
                $newX = 10 + (($lebarKotak + 3.3 + $jarakAntar) * 3);
                $this->SetFillColor(255, 114, 114);
                $this->Rect($newX, $yLeg, $lebarKotak + 17, $tinggiKotak, 'DF');
                $this->SetXY($newX, $yLeg + $tinggiKotak + 1);
                $this->MultiCell($lebarKotak + 17, 3.5, "TIDAK SEHAT / PERLU PENGOBATAN DAN PERAWATAN RUTIN", 0, 'C');

                $this->SetFont('Times', 'B', 12);
                $this->Cell(0, 5, 'CATATAN:', 0, 1);
                $this->SetFont('Times', '', 11);
                $this->MultiCell(0, 5, "1. Kesimpulan yang dikeluarkan berdasarkan hasil temuan yang didapatkan pada pemeriksaan medical check up.\n2. Kesimpulan hasil medical check up tidak dapat diganggu gugat.\n3. Bila masih ada hal yang perlu dijelaskan, mohon segera menghubungi dokter. Terima kasih atas kerja samanya.", 0, 'L');
                // --- 5. Footer (Tanda Tangan) ---
                $this->SetY(-75);
                $yFooter = $this->GetY();

                // Kiri: Tim Dokter
                $this->SetFont('Times', 'B', 10);
                $this->SetXY(10, $yFooter);
                $this->Cell(90, 5, "Tim Dokter Medical Check Up:", 0, 1, 'L');
                $this->SetFont('Times', '', 9);
                $this->Cell(90, 4, "1. dr. Muhammad Taufiq Amrullah, S.Ked", 0, 1, 'L');
                $this->Cell(90, 4, "2. dr. Khadijah Amir, S.Ked", 0, 1, 'L');
                $this->Cell(90, 4, "3. dr. Devi Grania Amelia Selekede, Sp.P.", 0, 1, 'L');
                $this->Cell(90, 4, "4. dr. Zainal Fathurohim, Sp. JP", 0, 1, 'L');
                $this->Cell(90, 4, "5. dr. Amir. Sp.Rad", 0, 1, 'L');

                // Kanan: QR & Pengesahan
                $this->SetXY(110, $yFooter);
                $this->SetFont('Times', '', 10);
                $this->MultiCell(90, 5, "Mengetahui\nSendawar, " . $this->data['tanggal_cetak'], 0, 'C');

                if($this->data['qrcode']) {
                    // Posisi QR di tengah kolom kanan
                    $this->Image('data://text/plain;base64,' . $this->data['qrcode'], 142, $this->GetY() + 2, 25, 25, 'PNG');
                }

                $this->SetXY(110, $yFooter + 40);
                $this->SetFont('Times', 'BU', 10);
                $this->Cell(90, 5, 'dr. Muhammad Taufiq Amrullah, S.Ked', 0, 1, 'C');
                $this->SetX(110);
                $this->SetFont('Times', 'B', 10);
                $this->Cell(90, 5, '440.007.2/127/SIP-DINKES/XI/2023', 0, 1, 'C');
            }
            /*section 4*/
            public function cetakRiwayat() {
                $this->drawHeaderMcuTable();
                $this->Line(10, $this->GetY(), 200, $this->GetY());
                $this->ln(5);
                $lineHeight = 6;
                // --- RIWAYAT PENYAKIT TERDAHULU & KELUARGA ---
                $sections = [
                    'RIWAYAT PENYAKIT TERDAHULU' => $this->data['riwayat_penyakit_terdahulu'],
                    'RIWAYAT PENYAKIT KELUARGA' => $this->data['riwayat_penyakit_keluarga'],
                ];
                $widths = [90, 30, 70];
                $totalWidth = array_sum($widths);
                foreach ($sections as $judul => $items) {
                    $this->SetFillColor(255, 87, 34); 
                    $this->SetTextColor(255, 255, 255);
                    $this->SetFont('Times', 'B', 12);
                    $xPos = $this->GetX();
                    $yPos = $this->GetY();
                    $w = $this->GetStringWidth($judul) + 10; // Lebar otomatis sesuai teks + padding
                    $h = 8;
                    $this->RoundedRect($xPos, $yPos, $w, $h, 3.5, 'F');
                    $this->SetXY($xPos, $yPos);
                    $this->Cell($w, $h, $judul, 0, 1, 'C');
                    $this->ln(2);
                    // 1. Simpan posisi Y TEPAT sebelum Header dimulai
                    $yHeader = $this->GetY(); 
                    
                    // Header Tabel
                    $this->SetFillColor(44, 148, 42);
                    $this->SetTextColor(255, 255, 255);
                    $this->Cell(90, 8, 'PERTANYAAN', 0, 0, 'C', true);
                    $this->Cell(30, 8, 'JAWABAN', 0, 0, 'C', true);
                    $this->Cell(70, 8, 'KETERANGAN', 0, 1, 'C', true);

                    $this->SetTextColor(0, 0, 0);
                    $this->SetFont('Times', '', 11);

                    $isGrey = false;

                    foreach ($items as $item) {
                        // 2. Cek apakah baris ini akan memicu halaman baru
                        // Kita butuh estimasi tinggi baris, misal 7mm
                        if($this->GetY() + 10 > 270) {
                            // A. Tutup kotak di halaman lama
                            $totalH = $this->GetY() - $yHeader;
                            $this->Rect(10, $yHeader, $totalWidth, $totalH, 'D');
                            
                            $this->AddPage();

                            // B. Cetak Logo & Garis Pembatas (Sesuai maumu, manual di tiap new page)
                            $this->drawHeaderMcuTable();
                            $this->Line(10, $this->GetY(), 200, $this->GetY());
                            $this->ln(5);

                            // C. Reset Y Header di halaman baru (PENTING: Setelah logo & sebelum header hijau)
                            $yHeader = $this->GetY(); 

                            // D. Cetak Ulang Header Hijau di Halaman Baru
                            $this->SetFillColor(44, 148, 42);
                            $this->SetTextColor(255, 255, 255);
                            $this->SetFont('Times', 'B', 12);
                            $this->Cell(90, 8, 'PERTANYAAN', 0, 0, 'C', true);
                            $this->Cell(30, 8, 'JAWABAN', 0, 0, 'C', true);
                            $this->Cell(70, 8, 'KETERANGAN', 0, 1, 'C', true);
                            
                            // Kembalikan font ke normal untuk isi tabel
                            $this->SetTextColor(0, 0, 0);
                            $this->SetFont('Times', '', 11);
                        }

                        $row = [
                            $item->nama_atribut_saat_ini,
                            ($item->status == "1" ? "Ya" : "Tidak"),
                            ($item->keterangan ?: "-")
                        ];
                        
                        $this->GenerateRow($widths, $row, 7, $isGrey);
                        $isGrey = !$isGrey; 
                    }

                    // 3. Gambar kotak penutup untuk section ini
                    $yEndBody = $this->GetY();
                    $totalHeight = $yEndBody - $yHeader;
                    $this->Rect(10, $yHeader, $totalWidth, $totalHeight, 'D'); 
                    
                    $this->Ln(2);
                }
                
                $this->SetFillColor(255, 87, 34); 
                $this->SetTextColor(255, 255, 255);
                $this->SetFont('Times', 'B', 12);

                $xPos = $this->GetX();
                $yPos = $this->GetY();
                $text = 'RIWAYAT KECELAKAAN KERJA';
                $w = $this->GetStringWidth($text) + 10; 
                $h = 8; 
                $this->RoundedRect($xPos, $yPos, $w, $h, 3.5, 'F'); 
                $this->Cell($w, $h, $text, 0, 1, 'L'); 
                $this->Ln(2); 
                $this->SetTextColor(0, 0, 0);
                $this->SetFont('Times', '', 10);
                $this->MultiCell(0, 6, strip_tags($this->data['riwayat_kecelakaan_kerja']), 0, 'L');
                $this->Ln(2);
                
                // --- RIWAYAT KEBIASAAN ---
                $this->SetFillColor(255, 87, 34); 
                $this->SetTextColor(255, 255, 255);
                $this->SetFont('Times', 'B', 12);

                $xPos = $this->GetX();
                $yPos = $this->GetY();
                $text = 'RIWAYAT KEBIASAAN';
                $w = $this->GetStringWidth($text) + 10; 
                $h = 8; 
                $this->RoundedRect($xPos, $yPos, $w, $h, 3.5, 'F'); 
                $this->Cell($w, $h, $text, 0, 1, 'L'); 
                $this->Ln(2); 
                $this->SetTextColor(0, 0, 0);

                // Lebar: 50 + 25 + 25 + 30 + 60 = 190
                $widthsKebiasaan = [50, 25, 25, 30, 60];
                $totalWidthK = array_sum($widthsKebiasaan);

                // Header
                $yHeaderK = $this->GetY();
                $this->SetFillColor(44, 148, 42); $this->SetTextColor(255);
                $this->SetFont('Times', 'B', 12);
                $this->Cell(50, 8, 'PERTANYAAN', 0, 0, 'C', true);
                $this->Cell(25, 8, 'JAWABAN', 0, 0, 'C', true);
                $this->Cell(25, 8, 'NILAI', 0, 0, 'C', true);
                $this->Cell(30, 8, 'SATUAN', 0, 0, 'C', true);
                $this->Cell(60, 8, 'KETERANGAN', 0, 1, 'C', true);

                // Body
                $this->SetTextColor(0); $this->SetFont('Times', '', 11);
                $isGrey = false;

                foreach ($this->data['riwayat_kebiasaan_hidup'] as $item) {
                    if ($item->jenis_kebiasaan == 1) {
                        if ($this->GetY() + 8 > 270) {
                            $this->Rect(10, $yHeaderK, $totalWidthK, $this->GetY() - $yHeaderK, 'D'); // Tutup lama
                            $this->AddPage();
                            $this->drawHeaderMcuTable(); // Logo New Page
                            $this->Line(10, $this->GetY(), 200, $this->GetY());
                            $this->ln(5);
                            
                            $yHeaderK = $this->GetY(); // Reset Y awal border
                            // Cetak ulang Header Hijau
                            $this->SetFillColor(44, 148, 42); $this->SetTextColor(255); $this->SetFont('Times', 'B', 9);
                            $this->Cell(50, 8, 'PERTANYAAN', 0, 0, 'C', true);
                            $this->Cell(25, 8, 'JAWABAN', 0, 0, 'C', true);
                            $this->Cell(25, 8, 'NILAI', 0, 0, 'C', true);
                            $this->Cell(30, 8, 'SATUAN', 0, 0, 'C', true);
                            $this->Cell(60, 8, 'KETERANGAN', 0, 1, 'C', true);
                            $this->SetTextColor(0); $this->SetFont('Times', '', 9);
                        }
                        $row = [
                            $item->nama_kebiasaan,
                            ($item->status_kebiasaan == 0 ? 'Tidak' : 'Ya'),
                            $item->nilai_kebiasaan,
                            $item->satuan_kebiasaan,
                            ($item->keterangan ?: '-')
                        ];
                        $this->GenerateRow($widthsKebiasaan, $row, 7, $isGrey);
                        $isGrey = !$isGrey;
                    }
                }

                // Border Luar
                $this->Rect(10, $yHeaderK, $totalWidthK, $this->GetY() - $yHeaderK, 'D');
                if ($this->data['informasi_data_diri']->jenis_kelamin == 'Perempuan') {
                    $this->Ln(5);
                    
                    // Label Merah
                    $this->SetFillColor(255, 87, 34); 
                    $this->SetTextColor(255, 255, 255);
                    $this->SetFont('Times', 'B', 12);

                    $xPos = $this->GetX();
                    $yPos = $this->GetY();
                    $text = 'KHUSUS WANITA';
                    $w = $this->GetStringWidth($text) + 10; 
                    $h = 8; 
                    $this->RoundedRect($xPos, $yPos, $w, $h, 3.5, 'F'); 
                    $this->Cell($w, $h, $text, 0, 1, 'L'); 
                    $this->Ln(2); 
                    $this->SetTextColor(0, 0, 0);
                    
                    // Lebar: 50 + 25 + 45 + 70 = 190
                    $widthsWanita = [50, 25, 45, 70];
                    $totalWidthW = array_sum($widthsWanita);

                    // Header
                    $yHeaderW = $this->GetY();
                    $this->SetFillColor(44, 148, 42); $this->SetFont('Times', 'B', 12);
                    $this->Cell(50, 8, 'PERTANYAAN', 0, 0, 'C', true);
                    $this->Cell(25, 8, 'JAWABAN', 0, 0, 'C', true);
                    $this->Cell(45, 8, 'WAKTU', 0, 0, 'C', true);
                    $this->Cell(70, 8, 'KETERANGAN', 0, 1, 'C', true);

                    // Body
                    $this->SetTextColor(0); $this->SetFont('Times', '', 11);
                    $isGrey = false;

                    foreach ($this->data['riwayat_kebiasaan_hidup'] as $item) {
                        if ($item->jenis_kebiasaan == 2) {
                            // --- CEK PAGE BREAK ---
                            if ($this->GetY() + 8 > 270) {
                                $this->Rect(10, $yHeaderW, $totalWidthW, $this->GetY() - $yHeaderW, 'D'); // Tutup lama
                                $this->AddPage();
                                $this->drawHeaderMcuTable(); // Logo New Page
                                $this->Line(10, $this->GetY(), 200, $this->GetY());
                                $this->ln(5);
                                
                                $yHeaderW = $this->GetY(); // Reset Y awal border
                                // Cetak ulang Header Hijau
                                $this->SetFillColor(44, 148, 42); $this->SetTextColor(255); $this->SetFont('Times', 'B', 9);
                                $this->Cell(50, 8, 'PERTANYAAN', 0, 0, 'C', true);
                                $this->Cell(25, 8, 'JAWABAN', 0, 0, 'C', true);
                                $this->Cell(45, 8, 'WAKTU', 0, 0, 'C', true);
                                $this->Cell(70, 8, 'KETERANGAN', 0, 1, 'C', true);
                                $this->SetTextColor(0); $this->SetFont('Times', '', 8);
                            }
                            $waktu = ($item->waktu_kebiasaan) ? date('d-m-Y H:i', strtotime($item->waktu_kebiasaan)) : '-';
                            $row = [
                                $item->nama_kebiasaan,
                                ($item->status_kebiasaan == 0 ? 'Tidak' : 'Ya'),
                                $waktu,
                                ($item->keterangan ?: '-')
                            ];
                            $this->GenerateRow($widthsWanita, $row, 7, $isGrey);
                            $isGrey = !$isGrey;
                        }
                    }

                    // Border Luar
                    $this->Rect(10, $yHeaderW, $totalWidthW, $this->GetY() - $yHeaderW, 'D');
                }
                // RIWAYAT IMUNISASI
                $this->ln(2);
                $sections = [
                    'RIWAYAT IMUNISASI' => $this->data['riwayat_imunisasi'],
                ];
                $widths = [90, 30, 70];
                $totalWidth = array_sum($widths);
                foreach ($sections as $judul => $items) {
                    $this->SetFillColor(255, 87, 34); 
                    $this->SetTextColor(255, 255, 255);
                    $this->SetFont('Times', 'B', 12);
                    $xPos = $this->GetX();
                    $yPos = $this->GetY();
                    $w = $this->GetStringWidth($judul) + 10; // Lebar otomatis sesuai teks + padding
                    $h = 8;
                    $this->RoundedRect($xPos, $yPos, $w, $h, 3.5, 'F');
                    $this->SetXY($xPos, $yPos);
                    $this->Cell($w, $h, $judul, 0, 1, 'L');
                    $this->ln(2);

                    // Header Tabel
                    $yHeader = $this->GetY(); // Simpan posisi awal tabel
                    $this->SetFillColor(44, 148, 42);
                    $this->SetTextColor(255, 255, 255);
                    $this->Cell(90, 8, 'PERTANYAAN', 0, 0, 'C', true);
                    $this->Cell(30, 8, 'JAWABAN', 0, 0, 'C', true);
                    $this->Cell(70, 8, 'KETERANGAN', 0, 1, 'C', true);

                    $this->SetTextColor(0, 0, 0);
                    $this->SetFont('Times', '', 11);

                    $isGrey = false;
                    $yStartBody = $this->GetY();

                    foreach ($items as $item) {
                        $row = [
                            $item->nama_atribut_saat_ini,
                            ($item->status == "1" ? "Ya" : "Tidak"),
                            ($item->keterangan ?: "-")
                        ];
                        $this->GenerateRow($widths, $row, 7, $isGrey);
                        $isGrey = !$isGrey; 
                    }

                    // --- BAGIAN PENTING: Gambar Border Pinggir ---
                    $yEndBody = $this->GetY();
                    $totalHeight = $yEndBody - $yHeader;
                    
                    // Gambar kotak kosong (tanpa fill) hanya di pinggiran tabel
                    $this->Rect(10, $yHeader, $totalWidth, $totalHeight, 'D'); 
                    $this->Ln(2);
                }

                //RIWAYAT PAPARAN KERJA
                if ($this->GetY() > 250) { 
                    $this->AddPage();
                    $this->drawHeaderMcuTable(); // Cetak logo klinik lagi di halaman baru
                    $this->Line(10, $this->GetY(), 200, $this->GetY());
                    $this->ln(5);
                }
                $widthsPaparan = [60, 25, 35, 30, 40];
                $totalWidthPaparan = array_sum($widthsPaparan);
                $headerTitles = ['PERTANYAAN', 'STATUS', 'JAM / HARI', 'X TAHUN', 'KETERANGAN'];
                
                $this->SetFillColor(255, 87, 34); 
                $this->SetTextColor(0, 0, 0);
                $this->SetFont('Times', 'B', 12);

                $xPos = $this->GetX();
                $yPos = $this->GetY();
                $text = 'RIWAYAT PAPARAN KERJA';
                $w = $this->GetStringWidth($text) + 10; 
                $h = 8; 
                $this->RoundedRect($xPos, $yPos, $w, $h, 3.5, 'F'); 
                $this->SetTextColor(255, 255, 255);
                $this->Cell($w, $h, $text, 0, 1, 'L'); 
                $this->Ln(2); 
                $this->SetTextColor(0, 0, 0);

                // Ambil posisi Y awal tepat sebelum header pertama kali dicetak
                $yHeaderPaparan = $this->GetY();

                // Header Tabel Pertama
                $this->SetFillColor(44, 148, 42); 
                $this->SetTextColor(255);
                $this->SetFont('Times', 'B', 12);
                foreach ($headerTitles as $i => $title) {
                    $this->Cell($widthsPaparan[$i], 8, $title, 0, 0, 'C', true);
                }
                $this->Ln();

                // Body Tabel
                $this->SetTextColor(0);
                $this->SetFont('Times', '', 11);
                $isGrey = false;

                foreach ($this->data['riwayat_lingkungan_kerja'] as $item) {
                    $this->CheckPageBreakWithHeader($widthsPaparan, $headerTitles, $totalWidthPaparan, $yHeaderPaparan);
                    $row = [
                        str_replace('≥', '>=', $item->nama_atribut_saat_ini),
                        ($item->status == "1" ? "Ya" : "Tidak"),
                        $item->nilai_jam_per_hari,
                        $item->nilai_selama_x_tahun,
                        ($item->keterangan ?: "-")
                    ];

                    $this->GenerateRow($widthsPaparan, $row, 7, $isGrey);
                    $isGrey = !$isGrey;
                }
                $this->Rect(10, $yHeaderPaparan, $totalWidthPaparan, $this->GetY() - $yHeaderPaparan, 'D'); 
                $this->Ln(2);
                $this->SetFont('Times', 'B', 11);
                $this->Cell(0, 7, 'PERNYATAAN PERSETUJUAN PEMERIKSAAN KESEHATAN', 0, 1, 'C');
                $this->SetFont('Times', '', 10);
                $this->MultiCell(0, 5, "Melalui pengisian formulir MCU secara elektronik maupun tertulis, dengan ini saya menyatakan persetujuan ketentuan sebagai berikut:", 0, 'L');
                
                $persetujuan = [
                    "Seluruh pernyataan yang saya jawab di atas adalah benar dan dapat dipertanggungjawabkan, apabila terdapat ketidaksesuaian dikemudian hari, saya bersedia diberi sanksi sesuai dengan ketentuan perusahaan.",
                    "Saya menyetujui bahwa hasil pemeriksaan kesehatan yang telah dilakukan dapat disimpan dalam bentuk tertulis (hardcopy) dan elektronik (softcopy) oleh perusahaan.",
                    "Saya menyetujui dan memberikan kewenangan pada staf kesehatan kerja perusahaan untuk melakukan analisa terkait hasil pemeriksaan kesehatan saya. Hal tersebut terkait kegunaan untuk dievaluasi berkaitan dengan pekerjaan saya di perusahaan ini",
                    "Saya menyetujui dan memberikan kewenangan pada staf kesehatan kerja perusahaan untuk memberikan hasil analisa dan evaluasi pemeriksaan terhadap kesehatan saya kepada manajemen perusahaan agar dilakukan tindak lanjut berdasarkan hasil pemeriksaan kondisi fisik dan kesehatan saya."
                ];
                foreach ($persetujuan as $i => $teks) {
                    $this->Cell(5, 5, ($i+1).". ", 0, 0, 'L');
                    $this->MultiCell(185, 5, $teks, 0, 'L');
                }
                $this->Cell(0, 7, 'Demikian pernyataan persetujuan ini saya buat dengan sebenar-benarnya dalam keadaan sadar dan tanpa ada paksaan dari pihak manapun.', 0, 1, 'L');
                $this->Ln(5);
                $yFooter = $this->GetY();
                // Kiri: Tim Dokter
                // $this->SetFont('Times', 'B', 10);
                // $this->SetXY(10, $yFooter);
                // $this->Cell(90, 5, "Tim Dokter Medical Check Up:", 0, 1, 'L');
                // $this->SetFont('Times', '', 9);
                // $this->Cell(90, 4, "1. dr. Muhammad Taufiq Amrullah, S.Ked", 0, 1, 'L');
                // $this->Cell(90, 4, "2. dr. Khadijah Amir, S.Ked", 0, 1, 'L');
                // $this->Cell(90, 4, "3. dr. Devi Grania Amelia Selekede, Sp.P.", 0, 1, 'L');
                // $this->Cell(90, 4, "4. dr. Muhammad Asrul. M.Kes Sp.JP(K)", 0, 1, 'L');
                // $this->Cell(90, 4, "5. dr. Amir. Sp.Rad", 0, 1, 'L');

                // Kanan: QR & Pengesahan
                $this->SetXY(110, $yFooter);
                $this->SetFont('Times', '', 10);
                $this->MultiCell(90, 5, "MENYETUJUI\nSendawar, " . $this->data['tanggal_cetak'], 0, 'C');

                $fotoUrlSignature = $this->data['riwayat_informasi_foto']['data_signature']?? null;
                if ($fotoUrlSignature && @get_headers($fotoUrlSignature)[0] == 'HTTP/1.1 404 Not Found') {
                    $fotoUrlSignature = public_path('/mofi/assets/images/logo/Logo_AMC_Full.png');
                }
                $this->Image($fotoUrlSignature, 130, $this->GetY() + 2, 50, 25, 'PNG'); 
                $d1 = $this->data['informasi_data_diri'];
                $this->SetXY(110, $yFooter + 40);
                $this->SetFont('Times', 'BU', 10);
                $this->Cell(90, 5, $d1['nama_peserta'], 0, 1, 'C');
                $this->SetX(110);
                $this->SetFont('Times', 'B', 10);
                $this->Cell(90, 5, $d1['nomor_identitas'], 0, 1, 'C');
            }
            /*section 5*/
            public function cetakPemeriksaanKondisiFisik() {
                $this->drawHeaderMcuTable();
                $this->Line(10, $this->GetY(), 200, $this->GetY());
                $this->ln(5);
                $y_awal = $this->GetY();$lebar_bg = 110;$tinggi_bg = 10;
                $this->_out('q'); 
                $this->RoundedRect(10, $y_awal, 100, 10, 5, 'CN', '23'); 
                $this->Image(public_path('mofi/assets/images/logo/gradient_bg_title.png'), 10, $y_awal, 120, 10);
                $this->_out('Q');

                $this->SetTextColor(255, 255, 255);
                $this->SetFont('Times', 'B', 14);
                $this->Cell(0, 10, 'PEMERIKSAAN KONDISI FISIK', 0, 1, 'L');
                $this->SetFillColor(72, 171, 198); // Warna Biru Laut
                $this->SetTextColor(255);
                $this->SetFont('Times', 'B', 11);
                $this->Ln(3);
                $judul = "TINGKAT KESADARAN";
                $wJudul = $this->GetStringWidth($judul) + 10;
                $hJudul = 8;

                $this->RoundedRect($this->GetX(), $this->GetY(), $wJudul, $hJudul, 3.5, 'F');
                $this->Cell($wJudul, $hJudul, $judul, 0, 1, 'C');
                $this->Ln(2);
                // Reset warna teks ke hitam
                $this->SetTextColor(0);
                $this->SetFont('Times', '', 10);

                // Simpan Y awal untuk border tabel
                $yAwalTabel = $this->GetY();
                $lebarTotal = 190; // Standar lebar A4 (210 - margin 10*2)
                $wKolom = $lebarTotal / 3; // Pembagian rata 3 kolom

                // Ambil data (asumsi variabel $data tersedia)
                $keadaan = ucfirst($this->data['tingkat_kesadaran']->nama_atribut_tingkat_kesadaran);
                $status = ucfirst($this->data['tingkat_kesadaran']->nama_atribut_status_tingkat_kesadaran);
                $keluhan = $this->data['tingkat_kesadaran']->keluhan ?: "-";
                $keterangan = strip_tags($this->data['tingkat_kesadaran']->keterangan_status_tingkat_kesadaran) ?: "-";

                // --- BARIS 1: 3 KOLOM ---
                // Kita gunakan Cell dengan border 'LTR' (Left, Top, Right) 
                // agar garis antar kolom terlihat
                $this->Cell($wKolom, 10, " Keadaan Umum : " . $keadaan, 1, 0, 'L');
                $this->Cell($wKolom, 10, " Status Kesadaran : " . $status, 1, 0, 'L');
                $this->Cell($wKolom + 1, 10, " Keluhan : " . $keluhan, 1, 1, 'L');

                // --- BARIS 2: KETERANGAN (COLSPAN 3) ---
                // Gunakan MultiCell jika keterangan sangat panjang
                $this->SetFont('Times', 'I', 9); // Font italic untuk keterangan agar beda
                $this->MultiCell($lebarTotal + 1, 8, " Keterangan : " . $keterangan, 0, 'L');
                $this->Ln(2);
                // Ambil data asli
                $items = collect($this->data['tanda_vital'])->values();
                // Hitung BMI
                $BB = 0;
                $TB = 0;
                foreach ($items as $item) {
                    $name = strtolower(str_replace(' ', '', $item->nama_atribut_saat_ini));
                    if ($name === 'beratbadan') {
                        $BB = $item->nilai_tanda_vital;
                    } elseif ($name === 'tinggibadan') {
                        $TB = $item->nilai_tanda_vital / 100;
                    }
                }

                $status_gizi = "-";
                $rgbColor = [0, 0, 0]; // Default Hitam

                if ($TB > 0) {
                    $BMI = $BB / ($TB * $TB);
                    $BMI_formatted = number_format(ceil($BMI * 100) / 100, 2);
                    
                    // if ($BMI < 18.5) {
                    //     $status_gizi = "KEKURANGAN BERAT BADAN";
                    //     $rgbColor = [255, 140, 0]; // Orange
                    // } elseif ($BMI >= 18.5 && $BMI <= 24.9) {
                    //     $status_gizi = "NORMAL";
                    //     $rgbColor = [0, 128, 0]; // Hijau
                    // } elseif ($BMI >= 25 && $BMI <= 29.9) {
                    //     $status_gizi = "KELEBIHAN BERAT BADAN";
                    //     $rgbColor = [255, 0, 0]; // Merah
                    // } else {
                    //     $status_gizi = "OBESITAS";
                    //     $rgbColor = [139, 0, 0]; // Merah Tua
                    // }
                    $status_gizi = strtoupper($this->data['quill_pemeriksaan_tanda_vital_dan_gizi']);
                    switch($status_gizi) {
                        case 'underweight':
                            $rgbColor = [52, 152, 219]; 
                            break;
                        case 'normal':
                            $rgbColor = [46, 204, 113]; 
                            break;
                        case 'overweight':
                            $rgbColor = [241, 196, 15]; 
                            break;
                        case 'obesitas_1':
                            $rgbColor = [230, 126, 34]; 
                            break;
                        case 'obesitas_2':
                            $rgbColor = [231, 76, 60]; 
                            break;
                        default:
                            $rgbColor = [255, 140, 0]; 
                            break;
                    }
                    // Tambahkan BMI ke list
                    $items->push((object)[
                        'nama_atribut_saat_ini' => 'BMI',
                        'nilai_tanda_vital' => $BMI_formatted,
                        'satuan_tanda_vital' => 'IMT',
                    ]);

                    // Tambahkan Status Gizi ke list
                    $items->push((object)[
                        'nama_atribut_saat_ini' => 'Status Gizi',
                        'nilai_tanda_vital' => $status_gizi,
                        'satuan_tanda_vital' => '',
                        'is_status' => true // Penanda untuk warna khusus
                    ]);
                }

                $totalItems = $items->count();
                $columns = 3;
                $rowsPerCol = ceil($totalItems / $columns);

                // --- JUDUL (Warna Biru Langit) ---
                $this->SetFillColor(72, 171, 198); 
                $this->SetTextColor(255);
                $this->SetFont('Times', 'B', 11);

                $judul = "TANDA VITAL DAN GIZI";
                $wJ = $this->GetStringWidth($judul) + 12;
                $this->RoundedRect($this->GetX(), $this->GetY(), $wJ, 8, 3.5, 'F');
                $this->Cell($wJ, 8, $judul, 0, 1, 'C');
                $this->Ln(3); // Spasi tambahan setelah judul

                // --- PENGATURAN TABEL 3 KOLOM ---
                $this->SetTextColor(0);
                $this->SetFont('Times', '', 9);

                $lebarTotal = 190;
                $lebarKolom = $lebarTotal / 3;
                $tinggiSel = 5; // Memberikan padding atas & bawah yang lega
                $startY = $this->GetY();

                for ($i = 0; $i < $totalItems; $i++) {
                    // Hitung posisi kolom dan baris
                    $col = floor($i / $rowsPerCol);
                    $rowInCol = $i % $rowsPerCol;
                    
                    // Hitung koordinat X dan Y
                    $x = 10 + ($col * $lebarKolom);
                    $y = $startY + ($rowInCol * ($tinggiSel + 1)); // +2 untuk memberi jarak antar kotak (spasi vertikal)
                    
                    $item = $items[$i];
                    $label = $item->nama_atribut_saat_ini;
                    $val = $item->nilai_tanda_vital;
                    $satuan = $item->satuan_tanda_vital ?? '';

                    $this->SetXY($x, $y);
                    
                    if (isset($item->is_status)) {
                        // Khusus Status Gizi dengan warna dinamis
                        $this->Cell($lebarKolom - 2, $tinggiSel, "", 1, 0); // Gambar Border
                        $this->SetXY($x, $y); // Reset posisi ke dalam kotak
                        
                        $this->Write($tinggiSel, "  $label : "); // Spasi awal sebagai padding kiri
                        $this->SetTextColor($rgbColor[0], $rgbColor[1], $rgbColor[2]);
                        $this->SetFont('Times', 'B', 9);
                        $this->Write($tinggiSel, $val);
                        
                        $this->SetTextColor(0); // Reset ke hitam
                        $this->SetFont('Times', '', 9);
                    } else {
                        // Sel Standar: Tambahkan spasi di awal string " $label" agar ada padding kiri
                        $this->Cell($lebarKolom - 2, $tinggiSel, "  $label : $val $satuan", 1, 0, 'L');
                    }
                }
                $this->SetY($startY + ($rowsPerCol * ($tinggiSel + 2)));

                // tabel penglihatan
                $this->SetFillColor(72, 171, 198); 
                $this->SetTextColor(255);
                $this->SetFont('Times', 'B', 12);

                $judul = "PENGLIHATAN";
                $wJ = $this->GetStringWidth($judul) + 12;
                $this->RoundedRect($this->GetX(), $this->GetY(), $wJ, 8, 3.5, 'F');
                $this->Cell($wJ, 8, $judul, 0, 1, 'C');
                $this->Ln(2);

                // --- PROSES DATA JSON ---
                $p = $this->data['penglihatan'][0];
                if (is_string($p)) {
                    $p = json_decode($p);
                }

                $this->SetFont('Times', 'B', 9);
                $this->SetFillColor(44, 148, 42); // Hijau
                $this->SetTextColor(255);

                $this->SetX(10);
                $this->Cell(25, 6, 'Pemeriksaan', 1, 0, 'C', true); // Lebar harus sama dengan kolom 'Status' di bawahnya
                $this->Cell(165, 6, 'VISUS', 1, 1, 'C', true);       // Sisa lebar (190 - 25 = 165)

                // Header Baris 2
                $this->SetX(10);
                $this->Cell(25, 12, 'Status', 1, 0, 'C', true); // Lebar dikurangi dari 30 ke 25
                $this->Cell(60, 6, 'Tanpa Kacamata', 1, 0, 'C', true); // Lebar dikurangi dari 80 ke 60
                $this->Cell(60, 6, 'Dengan Kacamata', 1, 0, 'C', true); // Lebar dikurangi dari 80 ke 60
                $this->Cell(45, 12, 'KETERANGAN', 1, 1, 'C', true); // Kolom baru (Rowspan 2)

                // Header Baris 3 (Sub-kolom OD/OS)
                // Kita naikkan posisi Y karena Keterangan & Status pakai rowspan
                $currentY = $this->GetY();
                $this->SetY($currentY - 6); 
                $this->SetX(35); // Geser setelah kolom Status (10 + 25)
                $this->Cell(30, 6, 'OD', 1, 0, 'C', true);
                $this->Cell(30, 6, 'OS', 1, 0, 'C', true);
                $this->Cell(30, 6, 'OD', 1, 0, 'C', true);
                $this->Cell(30, 6, 'OS', 1, 1, 'C', true);

                // Isi Data Visus
                $this->SetTextColor(0);
                $this->SetFont('Times', '', 9);

                // Baris Jauh
                $this->Cell(25, 8, ' Jauh', 1, 0, 'L');
                $this->Cell(30, 8, $p->visus_od_tanpa_kacamata_jauh ?? '-', 1, 0, 'C');
                $this->Cell(30, 8, $p->visus_os_tanpa_kacamata_jauh ?? '-', 1, 0, 'C');
                $this->Cell(30, 8, $p->visus_od_kacamata_jauh ?? '-', 1, 0, 'C');
                $this->Cell(30, 8, $p->visus_os_kacamata_jauh ?? '-', 1, 0, 'C');
                // Kolom Keterangan Jauh (Ambil data normal/abnormal)
                $this->Cell(45, 8, $p->keterangan_jauh ?? 'NORMAL', 1, 1, 'C'); 

                // Baris Dekat
                $this->Cell(25, 8, ' Dekat', 1, 0, 'L');
                $this->Cell(30, 8, $p->visus_od_tanpa_kacamata_dekat ?? '-', 1, 0, 'C');
                $this->Cell(30, 8, $p->visus_os_tanpa_kacamata_dekat ?? '-', 1, 0, 'C');
                $this->Cell(30, 8, $p->visus_od_kacamata_dekat ?? '-', 1, 0, 'C');
                $this->Cell(30, 8, $p->visus_os_kacamata_dekat ?? '-', 1, 0, 'C');
                // Kolom Keterangan Dekat
                $this->Cell(45, 8, $p->keterangan_dekat ?? 'NORMAL', 1, 1, 'C');

                $this->SetFillColor(44, 148, 42);
                $this->SetTextColor(255);
                $this->SetFont('Times', 'B', 9);

                // Header Baris 1
                $this->Cell(30, 12, 'Pemeriksaan', 1, 0, 'C', true); 
                // Lebar dikurangi dari 90 menjadi 60 karena kolom 'Normal' (lebar 30) dihapus
                $this->Cell(80, 6, 'Tes Buta Warna', 1, 0, 'C', true); 
                $this->Cell(80, 12, 'Keterangan', 1, 0, 'C', true); 
                $this->Ln(6);

                // Header Baris 2 (Sub-kolom)
                $this->SetX(40); // Pastikan posisi X sesuai dengan lebar kolom 'Pemeriksaan'
                $this->Cell(40, 6, 'Normal', 1, 0, 'C', true);
                $this->Cell(40, 6, 'Buta Warna', 1, 1, 'C', true); // ln diset ke 1 untuk pindah baris

                // Isi Data
                $this->SetTextColor(0);
                $this->SetFont('Times', '', 9);

                $buta_warna = strtolower($p->buta_warna ?? '');
                $buta_warna_red_green = strtolower($p->buta_warna_red_green ?? '');
                $isnormal = ($buta_warna == 0) ? 'Ya' : 'Tidak';
                $isbuta_warna = ($buta_warna == 1) ? 'Ya' : 'Tidak';
                $isbuta_warna_red_green = ($buta_warna_red_green == 1) ? 'Ya' : 'Tidak';

                $this->Cell(30, 8, 'Hasil Tes', 1, 0, 'L');       // Kolom Pemeriksaan
                $this->Cell(40, 8, $isnormal, 1, 0, 'C');        // Kolom Red-Green
                $this->Cell(40, 8, $isbuta_warna, 1, 0, 'C');          // Kolom Colour Blind
                // Kolom Normal dihapus di sini
                $this->Cell(80, 8, $p->buta_warna_keterangan ?? '-', 1, 1, 'C'); // Kolom Keterangan
                
                $this->SetTextColor(255);
                $this->SetFont('Times', 'B', 9);

                // --- BARIS 1 ---
                $this->SetX(10);
                // Cell Pemeriksaan: Lebarnya harus SAMA dengan kolom "Posisi Mata" di bawahnya (misal: 30)
                $this->Cell(30, 6, 'Pemeriksaan', 1, 0, 'C', true); 
                // Cell LAPANG PANDANG: Mengambil sisa lebar (190 - 30 = 160)
                $this->Cell(160, 6, 'LAPANG PANDANG', 1, 1, 'C', true);

                // --- BARIS 2 ---
                $this->SetX(10);
                // Kolom Posisi Mata tepat di bawah Pemeriksaan
                $this->Cell(30, 6, 'Posisi Mata', 1, 0, 'C', true); 
                // Pembagian kolom arah mata (misal 25 x 4 = 100)
                $this->Cell(25, 6, 'Superior', 1, 0, 'C', true);
                $this->Cell(25, 6, 'Inferior', 1, 0, 'C', true);
                $this->Cell(25, 6, 'Temporal', 1, 0, 'C', true);
                $this->Cell(25, 6, 'Nasal', 1, 0, 'C', true);
                // Sisa lebar untuk Keterangan (160 - 100 = 60)
                $this->Cell(60, 6, 'Keterangan', 1, 1, 'C', true);

                // Data Lapang Pandang
                $this->SetTextColor(0);
                $this->SetFont('Times', '', 9);

                // Mata Kiri (OS)
                $this->Cell(30, 8, ' Mata Kiri', 1, 0, 'L');
                $this->Cell(25, 8, $p->lapang_pandang_superior_os, 1, 0, 'C');
                $this->Cell(25, 8, $p->lapang_pandang_inferior_os, 1, 0, 'C');
                $this->Cell(25, 8, $p->lapang_pandang_temporal_os, 1, 0, 'C');
                $this->Cell(25, 8, $p->lapang_pandang_nasal_os, 1, 0, 'C');
                $this->Cell(60, 8, " " . $p->lapang_pandang_keterangan_os, 1, 1, 'L');

                // Mata Kanan (OD)
                $this->Cell(30, 8, ' Mata Kanan', 1, 0, 'L');
                $this->Cell(25, 8, $p->lapang_pandang_superior_od, 1, 0, 'C');
                $this->Cell(25, 8, $p->lapang_pandang_inferior_od, 1, 0, 'C');
                $this->Cell(25, 8, $p->lapang_pandang_temporal_od, 1, 0, 'C');
                $this->Cell(25, 8, $p->lapang_pandang_nasal_od, 1, 0, 'C');
                $this->Cell(60, 8, " " . $p->lapang_pandang_keterangan_od, 1, 1, 'L');
                $this->Ln(2);

                // --- JUDUL (Warna Biru Langit) ---
                $this->SetFillColor(72, 171, 198); 
                $this->SetTextColor(255);
                $this->SetFont('Times', 'B', 11);

                $judul = "KONDISI FISIK";
                $wJ = $this->GetStringWidth($judul) + 12;
                $this->RoundedRect($this->GetX(), $this->GetY(), $wJ, 8, 3.5, 'F');
                $this->Cell($wJ, 8, $judul, 0, 1, 'C');
                $this->Ln(2);
                $groupedData = [];
                foreach ($this->data['kondisi_fisik'] as $item) {
                    $groupedData[$item->kategori][$item->kategori_atribut][] = $item;
                }
                $this->SetFillColor(44, 148, 42); // Warna Hijau
                $this->SetTextColor(255);
                $this->SetFont('Times', 'B', 12);
                
                $gap = 2;
                $widths = [50, 65, 15, 15, 50];
                $headers = ['PEMERIKSAAN', 'JENIS PEMERIKSAAN', 'AB', 'N', 'KETERANGAN'];
                $totalWidth = array_sum($widths);

                // Titik awal koordinat Y untuk border luar
                $yHeaderRef = $this->GetY(); 

                // Cetak Header Pertama kali di section ini
                $this->SetFillColor(44, 148, 42);
                $this->SetTextColor(255);
                foreach ($headers as $i => $title) {
                    $this->Cell($widths[$i], 8, $title, 1, 0, 'C', true);
                }
                $this->Ln();
                $this->SetTextColor(0);

                foreach ($groupedData as $kategori => $groupAtribut) {
                    $isFirstKategoriRow = true;

                    foreach ($groupAtribut as $namaAtribut => $items) {
                        $hRow = 6;
                        
                        // 1. Pengecekan Lowercase: Jika sama, jangan tampilkan judul atributnya
                        // Kita bandingkan kategori_atribut (namaAtribut) dengan nama_atribut (dari item pertama)
                        $firstItem = $items[0];
                        $isSame = strtolower(trim($namaAtribut)) === strtolower(trim($firstItem->nama_atribut));

                        if (!$isSame) {
                            // JIKA TIDAK SAMA: Tampilkan judul kategori_atribut (misal: "ginjal")
                            $this->CheckPageBreakWithHeader($widths, $headers, $totalWidth, $yHeaderRef);
                            
                            // Kolom 1: Kategori Utama
                            $this->SetFont('Times', '', 11);
                            $this->Cell($widths[0], $hRow, ($isFirstKategoriRow ? " " . strtoupper(str_replace('_', ' ', $kategori)) : ""), 'LR', 0, 'C');
                            $isFirstKategoriRow = false;

                            // Kolom 2: Judul Atribut (Garis bawah dihilangkan dengan 'LR' atau 'LRT')
                            $this->SetFont('Times', '', 11);
                            $this->Cell($widths[1], $hRow, " " . ucwords(str_replace('_', ' ', $namaAtribut)), 'LR', 0, 'L');

                            // Kolom lainnya kosong
                            $this->Cell($widths[2], $hRow, "", 'LR', 0, 'C');
                            $this->Cell($widths[3], $hRow, "", 'LR', 0, 'C');
                            $this->Cell($widths[4], $hRow, "", 'LR', 1, 'L');
                        }

                        // 2. Tampilkan Detail Jenis Atribut
                        $this->SetFont('Times', '', 11);
                        foreach ($items as $item) {
                            $this->CheckPageBreakWithHeader($widths, $headers, $totalWidth, $yHeaderRef);
                            
                            // Kolom 1
                            $this->Cell($widths[0], $hRow, ($isFirstKategoriRow ? " " . strtoupper($kategori) : ""), 'LR', 0, 'C');
                            $isFirstKategoriRow = false;

                            // Kolom 2: Isi Detail
                            // Hilangkan garis atas agar menyatu dengan judul di atasnya
                            $borderDetail = 'LR'; 
                            if ($isSame) {
                                $this->Cell($widths[1], $hRow, " " . $item->jenis_atribut, $borderDetail, 0, 'L');
                            }else{
                                $this->Cell($widths[1], $hRow, "   - " . $item->jenis_atribut, $borderDetail, 0, 'L');
                            }

                            // Kolom 3 & 4: Checkbox
                            $this->drawCheckbox($this->GetX(), $this->GetY(), $hRow, $widths[2], ($item->status_atribut === 'abnormal'), $borderDetail);
                            $this->drawCheckbox($this->GetX(), $this->GetY(), $hRow, $widths[3], ($item->status_atribut === 'normal'), $borderDetail);

                            // Kolom 5: Keterangan
                            $ket = $item->keterangan_atribut ?: 'Normal';
                            $this->Cell($widths[4], $hRow, " " . $ket, $borderDetail, 1, 'L');
                        }
                    }
                    // Garis horizontal HANYA muncul saat ganti Kategori Utama (misal dari Abdomen ke Jantung)
                    $this->Line(10, $this->GetY(), 10 + $totalWidth, $this->GetY());
                }
                // 5. Tanda Tangan (Footer Page)
                $this->SetY(-65); // Pindah ke area bawah halaman
                $ySkg = $this->GetY();
                
                // Sisi Kiri (QR Keaslian)
                $this->SetFont('Times', '', 10);
                $this->SetXY(10, $ySkg);
                $this->MultiCell(90, 4, "Pindai untuk periksa keaslian dokumen\nDokumen ini tervalidasi dan dicetak secara otomatis", 0, 'C');
                if($this->data['qrcode']) {
                    $this->Image('data://text/plain;base64,' . $this->data['qrcode'], 40, $this->GetY(), 25, 25, 'PNG');
                }

                // // Sisi Kanan (Dokter)
                $this->SetXY(110, $ySkg);
                $this->MultiCell(90, 4, "Mengetahui\nSendawar, " . $this->data['tanggal_cetak'], 0, 'C');
                if($this->data['qrcode']) {
                    $this->Image('data://text/plain;base64,' . $this->data['qrcode'], 142, $this->GetY(), 25, 25, 'PNG');
                }
                $this->SetXY(110, 265);
                // Nama Dokter (Gunakan posisi absolut agar tidak terdorong tinggi QR yang dinamis)
                $this->SetFont('Times', 'BU', 10);
                $this->SetXY(110, $ySkg + 35); // Letakkan tepat di bawah QR TTD (25 + margin 2)
                $this->MultiCell(90, 4, 'dr. Muhammad Taufiq Amrullah, S.Ked', 0, 'C');

                // SIP Dokter (Tanpa Bold/Underline)
                $this->SetFont('Times', 'B', 10);
                $this->SetX(110); 
                $this->MultiCell(90, 4, '440.007.2/127/SIP-DINKES/XI/2023', 0, 'C');
            }
            public function cetakLaboratorium() {
                $this->drawHeaderMcuTable();
                $this->Line(10, $this->GetY(), 200, $this->GetY());
                $this->ln(2);
                $y_awal = $this->GetY();$lebar_bg = 110;$tinggi_bg = 10;
                $this->_out('q'); 
                $this->RoundedRect(10, $y_awal, 60, 10, 5, 'CN', '23'); 
                $this->Image(public_path('mofi/assets/images/logo/gradient_bg_title.png'), 10, $y_awal, 80, 10);
                $this->_out('Q');

                $this->SetTextColor(255, 255, 255);
                $this->SetFont('Times', 'B', 14);
                $this->Cell(0, 10, 'LABORATORIUM', 0, 1, 'L');
                $this->SetTextColor(0, 0, 0);
                if ($this->data['ada_lampiran_laboratorium_pdf'] == 0 && $this->data['total_tindakan'] > 0) {
               
                    // --- HEADER TABEL ---
                    $this->SetFillColor(44, 148, 42); // Hijau (#2c942a)
                    $this->SetFont('Times', 'B', 10);
                    $widths = [50, 35, 45, 30, 30]; // Sesuaikan total 190mm (A4)
                    $headers = ['PARAMETER', 'HASIL', 'NILAI RUJUKAN', 'SATUAN', 'STATUS'];
                    foreach ($headers as $i => $head) {
                        $this->Cell($widths[$i], 8, $head, 1, 0, 'C', true);
                    }
                    $this->Ln();
                    // --- ISI TABEL (Render Kategori) ---
                    $this->SetTextColor(0, 0, 0);
                    $this->SetFont('Times', '', 9);
                    foreach ($this->data['laboratorium'] as $kategori) {
                        $this->renderKategoriFPDF($kategori, $widths);
                    }
                    // --- FOOTER TANDA TANGAN (Dinamis di bawah tabel) ---
                    $yFooter = $this->GetY() + 10;
                    if ($yFooter > 240) { $this->AddPage(); $yFooter = 20; }
                    $this->renderFooterDokter($yFooter, $data);
                } else {
                    // Hitung total lampiran
                    $totalLampiran = count($this->data['lampiran_berkas_pdf']);

                    foreach ($this->data['lampiran_berkas_pdf'] as $index => $item) {
                        // 1. Perhitungan dimensi dan posisi (sama seperti sebelumnya)
                        $maxW = 200;
                        $maxH = 215;

                        $imgPath = $item->data_foto;
                        $size = @getimagesize($imgPath);
                        if ($size === false) {
                            $imgPath = public_path('mofi/assets/images/logo/doc_not_found.jpg');
                            $size = @getimagesize($imgPath);
                        }

                        $scale = min($maxW / $size[0], $maxH / $size[1]);
                        $finalW = $size[0] * $scale;
                        $finalH = $size[1] * $scale;

                        $posX = ($this->GetPageWidth() - $finalW) / 2;
                        $posY = $this->GetY() - 10;

                        // 2. Cetak Gambar
                        $this->Image($imgPath, $posX, $posY + 10, $finalW, $finalH);
                        $this->SetY($posY + $finalH + 10);
                        $this->SetTextColor(0, 0, 0);

                        // 3. LOGIKA ADD PAGE:
                        // Cek apakah ini BUKAN gambar terakhir (index dimulai dari 0)
                        if (($index + 1) < $totalLampiran) {
                            $this->AddPage();
                        }
                    }
                }
            }
            public function poliRontgenThorax(){
                foreach ($this->data['all_citra_data']->groupBy('jenis_poli') as $jenis_poli => $dataPoli) {
                    $cek_poli = strtoupper(str_replace(' ', '', trim($jenis_poli)));
                    if ($cek_poli === "POLI_RONTGEN_THORAX") {
                        $this->AddPage();
                        $this->drawHeaderMcuTable();
                        $this->Line(10, $this->GetY(), 200, $this->GetY());
                        $this->ln(2);
                        $y_awal = $this->GetY();$lebar_bg = 110;$tinggi_bg = 10;
                        $this->_out('q'); 
                        $this->RoundedRect(10, $y_awal, 100, 10, 5, 'CN', '23'); 
                        $this->Image(public_path('mofi/assets/images/logo/gradient_bg_title.png'), 10, $y_awal, 100, 10);
                        $this->_out('Q');

                        $this->SetTextColor(255, 255, 255);
                        $this->SetFont('Times', 'B', 14);
                        $this->Cell(0, 10, 'FOTO RONTGEN THORAX', 0, 1, 'L');
                        $this->SetTextColor(0, 0, 0);
                        // Render Semua Gambar dalam Poli ini
                        foreach ($dataPoli as $item) {
                            $maxW = 200;
                            $maxH = 220;

                            $imgPath = $item->data_foto;
                            $size = @getimagesize($imgPath);
                            if ($size === false) {
                                $imgPath = public_path('mofi/assets/images/logo/doc_not_found.jpg');
                                $size = @getimagesize($imgPath);
                            }

                            $scale = min($maxW / $size[0], $maxH / $size[1]);
                            $finalW = $size[0] * $scale;
                            $finalH = $size[1] * $scale;

                            $posX = ($this->GetPageWidth() - $finalW) / 2;
                            $posY = $this->GetY() - 10;

                            // 2. Cetak Gambar
                            $this->Image($imgPath, $posX, $posY + 10, $finalW, $finalH);
                            $this->SetTextColor(0, 0, 0);
                        }
                        // 2. HALAMAN INTERPRETASI
                        $this->AddPage();
                        $this->drawHeaderMcuTable();
                        $this->Line(10, $this->GetY(), 200, $this->GetY());
                        $this->ln(2);

                        $firstItem = $dataPoli->first();
                        $this->SetTextColor(0, 0, 0);
                        $this->SetFont('Times', 'B', 14);
                        $this->Cell(0, 5, 'INTERPRETASI HASIL RONTGEN THORAX', 0, 1, 'C');
                        $this->Ln(4);

                        // 1. Ambil data mentah
                        $htmlRaw = $firstItem->kesimpulan_citra_spirometri;
                        // 2. Bersihkan spasi dan tag "pembuka" hanya di awal string (^)
                        // Ini menghapus spasi, &nbsp;, <br>, <p>, dan <div> yang muncul di depan
                        $htmlClean = preg_replace('/^(\s|&nbsp;|<br\s*\/?>|<p[^>]*>|<div>)+/i', '', trim($htmlRaw));
                        // 3. Bersihkan tag "penutup" yang menggantung di akhir string ($)
                        // Agar tidak menyisakan ruang kosong di bawah tabel
                        $htmlClean = preg_replace('/(<\/p>|<\/div>|<br\s*\/?>|\s)+$/i', '', $htmlClean);
                        // 4. Decode entitas HTML (seperti &amp; menjadi &)
                        $htmlClean = html_entity_decode($htmlClean);

                        $fields = [
                            'Resume'     => 'USE_HTML_CONTENT', 
                            'Kesan'      => $firstItem->catatan_kaki ?? '-',
                        ];                     
                        $originalMargin = $this->lMargin;

                        foreach ($fields as $label => $value) {
                            $startY = $this->GetY();
                            
                            // 1. Cetak Label & Titik Dua
                            $this->SetFont('Times', 'B', 11);
                            $this->Cell(60, 5, $label, 0, 0);
                            $this->Cell(5, 5, ':', 0, 0);
                            
                            // Koordinat X setelah titik dua
                            $xKolom3 = $this->GetX();
                            $this->SetFont('Times', '', 11);

                            // --- KUNCI AGAR LURUS (INDENTASI) ---
                            // Atur margin kiri tepat di posisi kolom 3
                            $this->SetLeftMargin($xKolom3); 
                            // Kembalikan posisi Y ke baris yang sama dengan label
                            $this->SetY($startY); 

                            if ($value === 'USE_HTML_CONTENT') {
                                // Sekarang baris 2, 3, dst akan otomatis lurus dengan baris 1
                                $this->WriteHTML($htmlClean);
                                $this->Ln(2); 
                            } else {
                                // MultiCell juga akan mengikuti margin baru ini
                                $this->MultiCell(0, 5, $value, 0, 'L');
                            }

                            // --- KEMBALIKAN MARGIN ---
                            $this->SetLeftMargin($originalMargin);
                            
                            // Pastikan Y baris berikutnya di bawah konten paling panjang
                            $this->SetY(max($this->GetY(), $startY + 5)); 
                            $this->SetX($originalMargin); 
                        }
                        // 3. TANDA TANGAN (Posisi Absolute Bawah)
                        $this->SetY(-80); // Set posisi dari bawah kertas
                        $yTtd = $this->GetY();
                        
                        // // QR Code dan Nama Petugas (Kiri)
                        // $this->SetXY(10, $yTtd);
                        // $this->MultiCell(95, 5, "Petugas " . ucwords(str_replace('_', ' ', $jenis_poli)) . "\nSendawar, " . $this->data['tanggal_cetak'], 0, 'C');
                        // $this->Image('data:image/png;base64,' . $this->data['qrcode'], 45, $this->GetY() + 2, 25, 25, 'png');
                        // $this->SetY($this->GetY() + 28);
                        // $this->SetFont('Arial', 'BU', 11);
                        // $this->Cell(95, 5, $firstItem->nama_petugas, 0, 1, 'C');
                        // $this->SetFont('Arial', 'B', 11);
                        // $this->Cell(95, 5, $firstItem->departemen_petugas, 0, 0, 'C');

                        // QR Code dan Nama Dokter (Kanan)
                        $this->SetXY(105, $yTtd);
                        $this->MultiCell(95, 5, "Mengetahui\nSendawar, " . $this->data['tanggal_cetak'], 0, 'C');
                        $nikPetugas = $firstItem->nik_petugas;
                        $infoDokter = $this->data['pegawai_map'][$nikPetugas] ?? null;
                        $posX = 140; 
                        $posY = $this->GetY();

                        if ($infoDokter && isset($infoDokter['tanda_tangan_pegawai'])) {
                            $pathTTD = storage_path('app/public/user/ttd/' . $infoDokter['tanda_tangan_pegawai']);
                            if (!empty($infoDokter['tanda_tangan_pegawai']) && file_exists($pathTTD)) {
                                $this->Image($pathTTD, $posX, $posY, 25, 25, 'PNG'); 
                            } else {
                                $logoPath = public_path('/mofi/assets/images/logo/qr_not_found.jpg');
                                $this->Image($logoPath, $posX, $posY, 25, 27, 'JPEG');
                            }
                        } else {
                            $logoPath = public_path('/mofi/assets/images/logo/qr_not_found.jpg');
                            if (file_exists($logoPath)) {
                                $this->Image($logoPath, $posX, $posY, 25, 27, 'JPEG');
                            }
                        }
                        $this->SetY($this->GetY() + 28);
                        $this->SetFont('Times', 'BU', 11);
                        $this->SetX(105);
                        $this->Cell(95, 5, $firstItem->nama_pegawai, 0, 1, 'C');
                        $this->SetFont('Times', 'B', 11);
                        $this->SetX(105);
                        $this->Cell(95, 5, $firstItem->departemen, 0, 1, 'C');
                    }
                    if ($cek_poli === "POLI_RONTGEN_LUMBOSACRAL") {
                        $this->AddPage();
                        $this->drawHeaderMcuTable();
                        $this->Line(10, $this->GetY(), 200, $this->GetY());
                        $this->ln(2);
                        $y_awal = $this->GetY();$lebar_bg = 110;$tinggi_bg = 10;
                        $this->_out('q'); 
                        $this->RoundedRect(10, $y_awal, 100, 10, 5, 'CN', '23'); 
                        $this->Image(public_path('mofi/assets/images/logo/gradient_bg_title.png'), 10, $y_awal, 100, 10);
                        $this->_out('Q');

                        $this->SetTextColor(255, 255, 255);
                        $this->SetFont('Times', 'B', 14);
                        $this->Cell(0, 10, 'HASIL ' . strtoupper(str_replace('_', ' ', $jenis_poli)), 0, 1, 'L');
                        $this->SetTextColor(0, 0, 0);
                        // Render Semua Gambar dalam Poli ini
                        foreach ($dataPoli as $item) {
                            $maxW = 200;
                            $maxH = 220;

                            $imgPath = $item->data_foto;
                            $size = @getimagesize($imgPath);
                            if ($size === false) {
                                $imgPath = public_path('mofi/assets/images/logo/doc_not_found.jpg');
                                $size = @getimagesize($imgPath);
                            }

                            $scale = min($maxW / $size[0], $maxH / $size[1]);
                            $finalW = $size[0] * $scale;
                            $finalH = $size[1] * $scale;

                            $posX = ($this->GetPageWidth() - $finalW) / 2;
                            $posY = $this->GetY() - 10;

                            // 2. Cetak Gambar
                            $this->Image($imgPath, $posX, $posY + 10, $finalW, $finalH);
                            $this->SetTextColor(0, 0, 0);
                        }
                        // 2. HALAMAN INTERPRETASI
                        $this->AddPage();
                        $this->drawHeaderMcuTable();
                        $this->Line(10, $this->GetY(), 200, $this->GetY());
                        $this->ln(2);
                        $firstItem = $dataPoli->first();
                        $this->SetTextColor(0, 0, 0);
                        $this->SetFont('Times', 'B', 14);
                        $this->Cell(0, 5, 'INTERPRETASI HASIL ' . strtoupper(str_replace('_', ' ', $jenis_poli)), 0, 1, 'C');
                        // Tabel Interpretasi
                        $this->SetFont('Times', '', 11);
                        $html = $firstItem->kesimpulan_citra_spirometri;
                        $html = preg_replace('/<body[^>]*>/i', '', $html);
                        $html = preg_replace('/<\/body>/i', '', $html);
                        $html = preg_replace('/<(p|br|li|ol|ul)[^>]*>/i', '<$1>', $html);
                        $this->WriteHTML($html);
                        $this->ln(5);

                        $fields = [
                            //'Dokter Yang Bertugas' => $firstItem->nama_pegawai ?? '-',
                            //'Petugas Poliklinik'   => $firstItem->nama_petugas ?? '-',
                            //'Judul Interpretasi'   => $firstItem->judul_laporan ?? '-',
                            //'Catatan Kaki'         => $firstItem->catatan_kaki ?? '-',
                            'Kesimpulan'           => $firstItem->kesimpulan ?? '-',
                        ];

                        foreach ($fields as $label => $value) {
                            $this->Cell(60, 4, $label, 0, 0);
                            $this->Cell(5, 4, ':', 0, 0);
                            $this->MultiCell(0, 4, $value, 0, 'L');
                        }

                        // 3. TANDA TANGAN (Posisi Absolute Bawah)
                        $this->SetY(-80); // Set posisi dari bawah kertas
                        $yTtd = $this->GetY();
                        
                        // QR Code dan Nama Petugas (Kiri)
                        // $this->SetXY(10, $yTtd);
                        // $this->MultiCell(95, 5, "Petugas " . ucwords(str_replace('_', ' ', $jenis_poli)) . "\nSendawar, " . $this->data['tanggal_cetak'], 0, 'C');
                        // $this->Image('data:image/png;base64,' . $this->data['qrcode'], 45, $this->GetY() + 2, 25, 25, 'png');
                        // $this->SetY($this->GetY() + 28);
                        // $this->SetFont('Times', 'BU', 11);
                        // $this->Cell(95, 5, $firstItem->nama_petugas, 0, 1, 'C');
                        // $this->SetFont('Times', 'B', 11);
                        // $this->Cell(95, 5, $firstItem->departemen_petugas, 0, 0, 'C');

                        // QR Code dan Nama Dokter (Kanan)
                        $this->SetXY(105, $yTtd);
                        $this->MultiCell(95, 5, "Mengetahui\nSendawar, " . $this->data['tanggal_cetak'], 0, 'C');
                        $nikPetugas = $firstItem->nik_petugas;
                        $infoDokter = $this->data['pegawai_map'][$nikPetugas] ?? null;
                        $posX = 140; 
                        $posY = $this->GetY();

                        if ($infoDokter && isset($infoDokter['tanda_tangan_pegawai'])) {
                            $pathTTD = storage_path('app/public/user/ttd/' . $infoDokter['tanda_tangan_pegawai']);
                            if (!empty($infoDokter['tanda_tangan_pegawai']) && file_exists($pathTTD)) {
                                $this->Image($pathTTD, $posX, $posY, 25, 25, 'PNG'); 
                            } else {
                                $logoPath = public_path('/mofi/assets/images/logo/qr_not_found.jpg');
                                $this->Image($logoPath, $posX, $posY, 25, 27, 'JPEG');
                            }
                        } else {
                            $logoPath = public_path('/mofi/assets/images/logo/qr_not_found.jpg');
                            if (file_exists($logoPath)) {
                                $this->Image($logoPath, $posX, $posY, 25, 27, 'JPEG');
                            }
                        }
                        $this->SetY($this->GetY() + 28);
                        $this->SetFont('Times', 'BU', 11);
                        $this->SetX(105);
                        $this->Cell(95, 5, $firstItem->nama_pegawai, 0, 1, 'C');
                        $this->SetFont('Times', 'B', 11);
                        $this->SetX(105);
                        $this->Cell(95, 5, $firstItem->departemen, 0, 1, 'C');
                    }
                }
            }
            public function poliEkg(){
                foreach ($this->data['all_citra_data']->groupBy('jenis_poli') as $jenis_poli => $dataPoli) {
                    $cek_poli = strtoupper(str_replace(' ', '', trim($jenis_poli)));
                    if ($cek_poli === "POLI_EKG") {
                        $this->AddPage();
                        $this->drawHeaderMcuTable();
                        $this->Line(10, $this->GetY(), 200, $this->GetY());
                        $this->ln(2);
                        $y_awal = $this->GetY();$lebar_bg = 110;$tinggi_bg = 10;
                        $this->_out('q'); 
                        $this->RoundedRect(10, $y_awal, 100, 10, 5, 'CN', '23'); 
                        $this->Image(public_path('mofi/assets/images/logo/gradient_bg_title.png'), 10, $y_awal, 100, 10);
                        $this->_out('Q');

                        $this->SetTextColor(255, 255, 255);
                        $this->SetFont('Times', 'B', 14);
                        //strtoupper(str_replace('_', ' ', $jenis_poli))
                        $this->Cell(0, 10, 'HASIL ELEKTROKARDIOGRAFI', 0, 1, 'L');
                        $this->SetTextColor(0, 0, 0);
                        // Render Semua Gambar dalam Poli ini
                        foreach ($dataPoli as $item) {
                            $maxW = 200;
                            $maxH = 215;

                            $imgPath = $item->data_foto;
                            $size = @getimagesize($imgPath);
                            if ($size === false) {
                                $imgPath = public_path('mofi/assets/images/logo/doc_not_found.jpg');
                                $size = @getimagesize($imgPath);
                            }

                            $scale = min($maxW / $size[0], $maxH / $size[1]);
                            $finalW = $size[0] * $scale;
                            $finalH = $size[1] * $scale;

                            $posX = ($this->GetPageWidth() - $finalW) / 2;
                            $posY = $this->GetY() - 10;

                            // 2. Cetak Gambar
                            $this->Image($imgPath, $posX, $posY + 10, $finalW, $finalH);
                            $this->SetTextColor(0, 0, 0);
                        }
                        // 2. HALAMAN INTERPRETASI
                        $this->AddPage();
                        $this->drawHeaderMcuTable();
                        $this->Line(10, $this->GetY(), 200, $this->GetY());
                        $this->ln(2);
                        $firstItem = $dataPoli->first();
                        $this->SetTextColor(0, 0, 0);
                        $this->SetFont('Times', 'B', 14);
                        $this->Cell(0, 5, 'INTERPRETASI HASIL ELEKTROKARDIOGRAFI', 0, 1, 'C');
                        // // Tabel Interpretasi
                        // $this->SetFont('Times', '', 11);
                        // $html = $firstItem->kesimpulan_citra_spirometri;
                        // $html = preg_replace('/<body[^>]*>/i', '', $html);
                        // $html = preg_replace('/<\/body>/i', '', $html);
                        // $html = preg_replace('/<(p|br|li|ol|ul)[^>]*>/i', '<$1>', $html);
                        // $this->WriteHTML($html);
                        $this->ln(5);
                        $this->SetFont('Times', '', 11);
                        $fields = [
                            'Kesimpulan'           => $firstItem->kesimpulan ?? '-',
                        ];

                        foreach ($fields as $label => $value) {
                            $this->Cell(60, 4, $label, 0, 0);
                            $this->Cell(5, 4, ':', 0, 0);
                            $this->MultiCell(0, 4, $value, 0, 'L');
                        }

                        // 3. TANDA TANGAN (Posisi Absolute Bawah)
                        $this->SetY(-80); // Set posisi dari bawah kertas
                        $yTtd = $this->GetY();
                        
                        // QR Code dan Nama Petugas (Kiri)
                        // $this->SetXY(10, $yTtd);
                        // $this->MultiCell(95, 5, "Petugas " . ucwords(str_replace('_', ' ', $jenis_poli)) . "\nSendawar, " . $this->data['tanggal_cetak'], 0, 'C');
                        // $this->Image('data:image/png;base64,' . $this->data['qrcode'], 45, $this->GetY() + 2, 25, 25, 'png');
                        // $this->SetY($this->GetY() + 28);
                        // $this->SetFont('Arial', 'BU', 11);
                        // $this->Cell(95, 5, $firstItem->nama_petugas, 0, 1, 'C');
                        // $this->SetFont('Arial', 'B', 11);
                        // $this->Cell(95, 5, $firstItem->departemen_petugas, 0, 0, 'C');

                        // QR Code dan Nama Dokter (Kanan)
                        $this->SetXY(105, $yTtd);
                        $this->MultiCell(95, 5, "Mengetahui\nSendawar, " . $this->data['tanggal_cetak'], 0, 'C');
                        $nikPetugas = $firstItem->nik_petugas;
                        $infoDokter = $this->data['pegawai_map'][$nikPetugas] ?? null;
                        $posX = 140; 
                        $posY = $this->GetY();

                        if ($infoDokter && isset($infoDokter['tanda_tangan_pegawai'])) {
                            $pathTTD = storage_path('app/public/user/ttd/' . $infoDokter['tanda_tangan_pegawai']);
                            if (!empty($infoDokter['tanda_tangan_pegawai']) && file_exists($pathTTD)) {
                                $this->Image($pathTTD, $posX, $posY, 25, 25, 'PNG'); 
                            } else {
                                $logoPath = public_path('/mofi/assets/images/logo/qr_not_found.jpg');
                                $this->Image($logoPath, $posX, $posY, 25, 27, 'JPEG');
                            }
                        } else {
                            $logoPath = public_path('/mofi/assets/images/logo/qr_not_found.jpg');
                            if (file_exists($logoPath)) {
                                $this->Image($logoPath, $posX, $posY, 25, 27, 'JPEG');
                            }
                        }
                        $this->SetY($this->GetY() + 28);
                        $this->SetFont('Times', 'BU', 11);
                        $this->SetX(105);
                        $this->Cell(95, 5, $firstItem->nama_pegawai, 0, 1, 'C');
                        $this->SetFont('Times', 'B', 11);
                        $this->SetX(105);
                        $this->Cell(95, 5, $firstItem->departemen, 0, 1, 'C');
                    }
                }
            }
            public function poliAudiometri(){
                foreach ($this->data['all_citra_data']->groupBy('jenis_poli') as $jenis_poli => $dataPoli) {
                    $cek_poli = strtoupper(str_replace(' ', '', trim($jenis_poli)));
                    if ($cek_poli === "POLI_AUDIOMETRI") {
                        $this->AddPage();
                        $this->drawHeaderMcuTable();
                        $this->Line(10, $this->GetY(), 200, $this->GetY());
                        $this->ln(2);
                        $y_awal = $this->GetY();$lebar_bg = 110;$tinggi_bg = 10;
                        $this->_out('q'); 
                        $this->RoundedRect(10, $y_awal, 100, 10, 5, 'CN', '23'); 
                        $this->Image(public_path('mofi/assets/images/logo/gradient_bg_title.png'), 10, $y_awal, 100, 10);
                        $this->_out('Q');

                        $this->SetTextColor(255, 255, 255);
                        $this->SetFont('Times', 'B', 14);
                        $this->Cell(0, 10, 'HASIL AUDIOMETRI', 0, 1, 'L');
                        $this->SetTextColor(0, 0, 0);
                        // Render Semua Gambar dalam Poli ini
                        foreach ($dataPoli as $item) {
                            $maxW = 200;
                            $maxH = 215;

                            $imgPath = $item->data_foto;
                            $size = @getimagesize($imgPath);
                            if ($size === false) {
                                $imgPath = public_path('mofi/assets/images/logo/doc_not_found.jpg');
                                $size = @getimagesize($imgPath);
                            }

                            $scale = min($maxW / $size[0], $maxH / $size[1]);
                            $finalW = $size[0] * $scale;
                            $finalH = $size[1] * $scale;

                            $posX = ($this->GetPageWidth() - $finalW) / 2;
                            $posY = $this->GetY() - 10;

                            // 2. Cetak Gambar
                            $this->Image($imgPath, $posX, $posY + 10, $finalW, $finalH);
                            $this->SetTextColor(0, 0, 0);
                        }
                        // 2. HALAMAN INTERPRETASI
                        $this->AddPage();
                        $this->drawHeaderMcuTable();
                        $this->Line(10, $this->GetY(), 200, $this->GetY());
                        $this->ln(2);
                        $firstItem = $dataPoli->first();
                        $this->SetTextColor(0, 0, 0);
                        $this->SetFont('Times', 'B', 14);
                        $this->Cell(0, 5, 'INTERPRETASI HASIL AUDIOMETRI', 0, 1, 'C');
                        // Tabel Interpretasi
                        $this->ln(5);
                        $this->SetFont('Times', '', 11);
                        $fields = [
                            'Kesimpulan' => [
                                'Kanan' => $firstItem->kesimpulan ?? '-',
                                'Kiri'  => $firstItem->kesimpulan2 ?? '-',
                            ],
                        ];

                        foreach ($fields as $label => $subValues) {
                            $startY = $this->GetY();
                            $originalMargin = $this->lMargin;

                            // 1. Cetak Label Utama (Kesimpulan)
                            $this->SetFont('Times', 'B', 11);
                            $this->Cell(30, 5, $label, 0, 0); 
                            
                            // Simpan posisi X setelah kolom "Kesimpulan" untuk baris-baris sub-nilai
                            $xSubLabel = $this->GetX(); 

                            if (is_array($subValues)) {
                                $isFirst = true;
                                foreach ($subValues as $subLabel => $text) {
                                    if (!$isFirst) {
                                        // Beri jarak sedikit untuk baris kedua (Kiri)
                                        $this->SetX($xSubLabel); 
                                    }

                                    // 2. Cetak Sub-Label (Kanan / Kiri)
                                    $this->SetFont('Times', '', 11);
                                    $this->Cell(20, 5, $subLabel, 0, 0); // Lebar 20mm cukup untuk kata "Kanan"
                                    
                                    // 3. Cetak Titik Dua
                                    $this->Cell(5, 5, ':', 0, 0);
                                    
                                    // 4. Cetak Isinya (Normal / dsb)
                                    $xValue = $this->GetX();
                                    
                                    // Set margin kiri sementara agar jika teks "Normal" sangat panjang, dia ngetab lurus
                                    $this->SetLeftMargin($xValue);
                                    $this->SetY($this->GetY()); 
                                    
                                    $this->MultiCell(0, 5, $text, 0, 'L');
                                    
                                    // Kembalikan margin ke posisi kolom sub-label untuk baris berikutnya
                                    $this->SetLeftMargin($originalMargin);
                                    $isFirst = false;
                                }
                            } else {
                                // Jika teks biasa (bukan array Kanan/Kiri)
                                $this->Cell(5, 5, ':', 0, 0);
                                $this->MultiCell(0, 5, $subValues, 0, 'L');
                            }

                            $this->Ln(2);
                            $this->SetX($originalMargin);
                        }

                        // 3. TANDA TANGAN (Posisi Absolute Bawah)
                        $this->SetY(-80); // Set posisi dari bawah kertas
                        $yTtd = $this->GetY();
                        
                        // QR Code dan Nama Petugas (Kiri)
                        // $this->SetXY(10, $yTtd);
                        // $this->MultiCell(95, 5, "Petugas " . ucwords(str_replace('_', ' ', $jenis_poli)) . "\nSendawar, " . $this->data['tanggal_cetak'], 0, 'C');
                        // $this->Image('data:image/png;base64,' . $this->data['qrcode'], 45, $this->GetY() + 2, 25, 25, 'png');
                        // $this->SetY($this->GetY() + 28);
                        // $this->SetFont('Arial', 'BU', 11);
                        // $this->Cell(95, 5, $firstItem->nama_petugas, 0, 1, 'C');
                        // $this->SetFont('Arial', 'B', 11);
                        // $this->Cell(95, 5, $firstItem->departemen_petugas, 0, 0, 'C');

                        // QR Code dan Nama Dokter (Kanan)
                        $this->SetXY(105, $yTtd);
                        $this->MultiCell(95, 5, "Mengetahui\nSendawar, " . $this->data['tanggal_cetak'], 0, 'C');
                        $nikPetugas = $firstItem->nik_petugas;
                        $infoDokter = $this->data['pegawai_map'][$nikPetugas] ?? null;
                        $posX = 140; 
                        $posY = $this->GetY();

                        if ($infoDokter && isset($infoDokter['tanda_tangan_pegawai'])) {
                            $pathTTD = storage_path('app/public/user/ttd/' . $infoDokter['tanda_tangan_pegawai']);
                            if (!empty($infoDokter['tanda_tangan_pegawai']) && file_exists($pathTTD)) {
                                $this->Image($pathTTD, $posX, $posY, 25, 25, 'PNG'); 
                            } else {
                                $logoPath = public_path('/mofi/assets/images/logo/qr_not_found.jpg');
                                $this->Image($logoPath, $posX, $posY, 25, 27, 'JPEG');
                            }
                        } else {
                            $logoPath = public_path('/mofi/assets/images/logo/qr_not_found.jpg');
                            if (file_exists($logoPath)) {
                                $this->Image($logoPath, $posX, $posY, 25, 27, 'JPEG');
                            }
                        }
                        $this->SetY($this->GetY() + 28);
                        $this->SetFont('Times', 'BU', 11);
                        $this->SetX(105);
                        $this->Cell(95, 5, $firstItem->nama_pegawai, 0, 1, 'C');
                        $this->SetFont('Times', 'B', 11);
                        $this->SetX(105);
                        $this->Cell(95, 5, $firstItem->departemen, 0, 1, 'C');
                    }
                }
            }
            public function poliSpirometri(){
                foreach ($this->data['all_citra_data']->groupBy('jenis_poli') as $jenis_poli => $dataPoli) {
                    $cek_poli = strtoupper(str_replace(' ', '', trim($jenis_poli)));
                    if ($cek_poli === "POLI_SPIROMETRI") {
                        $this->AddPage();
                        $this->drawHeaderMcuTable();
                        $this->Line(10, $this->GetY(), 200, $this->GetY());
                        $this->ln(2);
                        $y_awal = $this->GetY();$lebar_bg = 110;$tinggi_bg = 10;
                        $this->_out('q'); 
                        $this->RoundedRect(10, $y_awal, 100, 10, 5, 'CN', '23'); 
                        $this->Image(public_path('mofi/assets/images/logo/gradient_bg_title.png'), 10, $y_awal, 100, 10);
                        $this->_out('Q');

                        $this->SetTextColor(255, 255, 255);
                        $this->SetFont('Times', 'B', 14);
                        $this->Cell(0, 10, 'HASIL SPIROMETRI', 0, 1, 'L');
                        $this->SetTextColor(0, 0, 0);
                        // Render Semua Gambar dalam Poli ini
                        foreach ($dataPoli as $item) {
                            $maxW = 200;
                            $maxH = 215;

                            $imgPath = $item->data_foto;
                            $size = @getimagesize($imgPath);
                            if ($size === false) {
                                $imgPath = public_path('mofi/assets/images/logo/doc_not_found.jpg');
                                $size = @getimagesize($imgPath);
                            }

                            $scale = min($maxW / $size[0], $maxH / $size[1]);
                            $finalW = $size[0] * $scale;
                            $finalH = $size[1] * $scale;

                            $posX = ($this->GetPageWidth() - $finalW) / 2;
                            $posY = $this->GetY() - 10;

                            // 2. Cetak Gambar
                            $this->Image($imgPath, $posX, $posY + 10, $finalW, $finalH);
                            $this->SetTextColor(0, 0, 0);
                        }
                        // 2. HALAMAN INTERPRETASI
                        $this->AddPage();
                        $this->drawHeaderMcuTable();
                        $this->Line(10, $this->GetY(), 200, $this->GetY());
                        $this->ln(2);
                        $firstItem = $dataPoli->first();
                        $this->SetTextColor(0, 0, 0);
                        $this->SetFont('Times', 'B', 14);
                        $this->Cell(0, 5, 'INTERPRETASI HASIL SPIROMETRI', 0, 1, 'C');
                        // Tabel Interpretasi
                        // $this->SetFont('Times', '', 11);
                        // $html = $firstItem->kesimpulan_citra_spirometri;
                        // $html = preg_replace('/<body[^>]*>/i', '', $html);
                        // $html = preg_replace('/<\/body>/i', '', $html);
                        // $html = preg_replace('/<(p|br|li|ol|ul)[^>]*>/i', '<$1>', $html);
                        // $this->WriteHTML($html);
                        $this->ln(5);
                        $this->SetFont('Times', '', 11);
                        $fields = [
                            // 'Dokter Yang Bertugas' => $firstItem->nama_pegawai ?? '-',
                            // 'Petugas Poliklinik'   => $firstItem->nama_petugas ?? '-',
                            // 'Judul Interpretasi'   => $firstItem->judul_laporan ?? '-',
                            // 'Catatan Kaki'         => $firstItem->catatan_kaki ?? '-',
                            'Kesimpulan'           => $firstItem->kesimpulan ?? '-',
                        ];

                        foreach ($fields as $label => $value) {
                            $this->Cell(60, 4, $label, 0, 0);
                            $this->Cell(5, 4, ':', 0, 0);
                            $this->MultiCell(0, 4, $value, 0, 'L');
                        }

                        // 3. TANDA TANGAN (Posisi Absolute Bawah)
                        $this->SetY(-80); // Set posisi dari bawah kertas
                        $yTtd = $this->GetY();
                        
                        // // QR Code dan Nama Petugas (Kiri)
                        // $this->SetXY(10, $yTtd);
                        // $this->MultiCell(95, 5, "Petugas " . ucwords(str_replace('_', ' ', $jenis_poli)) . "\nSendawar, " . $this->data['tanggal_cetak'], 0, 'C');
                        // $this->Image('data:image/png;base64,' . $this->data['qrcode'], 45, $this->GetY() + 2, 25, 25, 'png');
                        // $this->SetY($this->GetY() + 28);
                        // $this->SetFont('Arial', 'BU', 11);
                        // $this->Cell(95, 5, $firstItem->nama_petugas, 0, 1, 'C');
                        // $this->SetFont('Arial', 'B', 11);
                        // $this->Cell(95, 5, $firstItem->departemen_petugas, 0, 0, 'C');

                        // QR Code dan Nama Dokter (Kanan)
                        $this->SetXY(105, $yTtd);
                        $this->MultiCell(95, 5, "Mengetahui\nSendawar, " . $this->data['tanggal_cetak'], 0, 'C');
                        $nikPetugas = $firstItem->nik_petugas;
                        $infoDokter = $this->data['pegawai_map'][$nikPetugas] ?? null;
                        $posX = 140; 
                        $posY = $this->GetY();

                        if ($infoDokter && isset($infoDokter['tanda_tangan_pegawai'])) {
                            $pathTTD = storage_path('app/public/user/ttd/' . $infoDokter['tanda_tangan_pegawai']);
                            if (!empty($infoDokter['tanda_tangan_pegawai']) && file_exists($pathTTD)) {
                                $this->Image($pathTTD, $posX, $posY, 25, 25, 'PNG'); 
                            } else {
                                $logoPath = public_path('/mofi/assets/images/logo/qr_not_found.jpg');
                                $this->Image($logoPath, $posX, $posY, 25, 27, 'JPEG');
                            }
                        } else {
                            $logoPath = public_path('/mofi/assets/images/logo/qr_not_found.jpg');
                            if (file_exists($logoPath)) {
                                $this->Image($logoPath, $posX, $posY, 25, 27, 'JPEG');
                            }
                        }
                        $this->SetY($this->GetY() + 28);
                        $this->SetFont('Times', 'BU', 11);
                        $this->SetX(105);
                        $this->Cell(95, 5, $firstItem->nama_pegawai, 0, 1, 'C');
                        $this->SetFont('Times', 'B', 11);
                        $this->SetX(105);
                        $this->Cell(95, 5, $firstItem->departemen, 0, 1, 'C');
                    }
                }
            }
            public function poliThreadmill(){
                foreach ($this->data['all_citra_data']->groupBy('jenis_poli') as $jenis_poli => $dataPoli) {
                    $cek_poli = strtoupper(str_replace(' ', '', trim($jenis_poli)));
                    if ($cek_poli === "POLI_THREADMILL") {
                        // 2. HALAMAN INTERPRETASI
                        $this->AddPage();
                        $this->drawHeaderMcuTable();
                        $this->Line(10, $this->GetY(), 200, $this->GetY());
                        $this->ln(2);
                        $firstItem = $dataPoli->first();
                        $this->SetTextColor(0, 0, 0);
                        $this->SetFont('Times', 'B', 14);
                        $this->Cell(0, 5, 'INTERPRETASI HASIL THREADMILL TEST', 0, 1, 'C');
                        // Tabel Interpretasi
                        $this->SetFont('Times', '', 11);
                        // $html = $firstItem->kesimpulan_citra_spirometri;
                        // $html = preg_replace('/<body[^>]*>/i', '', $html);
                        // $html = preg_replace('/<\/body>/i', '', $html);
                        // $html = preg_replace('/<(p|br|li|ol|ul)[^>]*>/i', '<$1>', $html);
                        // $this->WriteHTML($html);
                        $this->ln(5);
                        // 1. Ambil data mentah
                        $htmlRaw = $firstItem->kesimpulan_citra_spirometri;
                        // 2. Bersihkan spasi dan tag "pembuka" hanya di awal string (^)
                        // Ini menghapus spasi, &nbsp;, <br>, <p>, dan <div> yang muncul di depan
                        $htmlClean = preg_replace('/^(\s|&nbsp;|<br\s*\/?>|<p[^>]*>|<div>)+/i', '', trim($htmlRaw));
                        // 3. Bersihkan tag "penutup" yang menggantung di akhir string ($)
                        // Agar tidak menyisakan ruang kosong di bawah tabel
                        $htmlClean = preg_replace('/(<\/p>|<\/div>|<br\s*\/?>|\s)+$/i', '', $htmlClean);
                        // 4. Decode entitas HTML (seperti &amp; menjadi &)
                        $htmlClean = html_entity_decode($htmlClean);

                        $fields = [
                            'Resume'     => 'USE_HTML_CONTENT', 
                            'Kesimpulan' => $firstItem->kesimpulan ?? '-',
                            'Saran'      => $firstItem->catatan_kaki ?? '-',
                        ];                     
                        $originalMargin = $this->lMargin;

                        foreach ($fields as $label => $value) {
                            $startY = $this->GetY();
                            
                            // 1. Cetak Label & Titik Dua
                            $this->SetFont('Times', 'B', 11);
                            $this->Cell(60, 5, $label, 0, 0);
                            $this->Cell(5, 5, ':', 0, 0);
                            
                            // Koordinat X setelah titik dua
                            $xKolom3 = $this->GetX();
                            $this->SetFont('Times', '', 11);

                            // --- KUNCI AGAR LURUS (INDENTASI) ---
                            // Atur margin kiri tepat di posisi kolom 3
                            $this->SetLeftMargin($xKolom3); 
                            // Kembalikan posisi Y ke baris yang sama dengan label
                            $this->SetY($startY); 

                            if ($value === 'USE_HTML_CONTENT') {
                                // Sekarang baris 2, 3, dst akan otomatis lurus dengan baris 1
                                $this->WriteHTML($htmlClean);
                                $this->Ln(2); 
                            } else {
                                // MultiCell juga akan mengikuti margin baru ini
                                $this->MultiCell(0, 5, $value, 0, 'L');
                            }

                            // --- KEMBALIKAN MARGIN ---
                            $this->SetLeftMargin($originalMargin);
                            
                            // Pastikan Y baris berikutnya di bawah konten paling panjang
                            $this->SetY(max($this->GetY(), $startY + 5)); 
                            $this->SetX($originalMargin); 
                        }

                        // 3. TANDA TANGAN (Posisi Absolute Bawah)
                        $this->SetY(-80); // Set posisi dari bawah kertas
                        $yTtd = $this->GetY();
                        
                        // QR Code dan Nama Petugas (Kiri)
                        // $this->SetXY(10, $yTtd);
                        // $this->MultiCell(95, 5, "Petugas " . ucwords(str_replace('_', ' ', $jenis_poli)) . "\nSendawar, " . $this->data['tanggal_cetak'], 0, 'C');
                        // $this->Image('data:image/png;base64,' . $this->data['qrcode'], 45, $this->GetY() + 2, 25, 25, 'png');
                        // $this->SetY($this->GetY() + 28);
                        // $this->SetFont('Arial', 'BU', 11);
                        // $this->Cell(95, 5, $firstItem->nama_petugas, 0, 1, 'C');
                        // $this->SetFont('Arial', 'B', 11);
                        // $this->Cell(95, 5, $firstItem->departemen_petugas, 0, 0, 'C');

                        // QR Code dan Nama Dokter (Kanan)
                        $this->SetXY(105, $yTtd);
                        $this->MultiCell(95, 5, "Mengetahui\nSendawar, " . $this->data['tanggal_cetak'], 0, 'C');
                        $nikPetugas = $firstItem->nik_petugas;
                        $infoDokter = $this->data['pegawai_map'][$nikPetugas] ?? null;
                        $posX = 140; 
                        $posY = $this->GetY();

                        if ($infoDokter && isset($infoDokter['tanda_tangan_pegawai'])) {
                            $pathTTD = storage_path('app/public/user/ttd/' . $infoDokter['tanda_tangan_pegawai']);
                            if (!empty($infoDokter['tanda_tangan_pegawai']) && file_exists($pathTTD)) {
                                $this->Image($pathTTD, $posX, $posY, 25, 25, 'PNG'); 
                            } else {
                                $logoPath = public_path('/mofi/assets/images/logo/qr_not_found.jpg');
                                $this->Image($logoPath, $posX, $posY, 25, 27, 'JPEG');
                            }
                        } else {
                            $logoPath = public_path('/mofi/assets/images/logo/qr_not_found.jpg');
                            if (file_exists($logoPath)) {
                                $this->Image($logoPath, $posX, $posY, 25, 27, 'JPEG');
                            }
                        }
                        $this->SetY($this->GetY() + 28);
                        $this->SetFont('Times', 'BU', 11);
                        $this->SetX(105);
                        $this->Cell(95, 5, $firstItem->nama_pegawai, 0, 1, 'C');
                        $this->SetFont('Times', 'B', 11);
                        $this->SetX(105);
                        $this->Cell(95, 5, $firstItem->departemen, 0, 1, 'C');

                        $this->AddPage('L', 'A4');
                        $this->drawHeaderMcuTable();
                        $pageWidth = $this->GetPageWidth();
                        $this->Line(10, $this->GetY(), $pageWidth - 10, $this->GetY());
                        $this->ln(2);
                        $y_awal = $this->GetY();$lebar_bg = 110;$tinggi_bg = 10;
                        $this->_out('q'); 
                        $this->RoundedRect(10, $y_awal, 100, 10, 5, 'CN', '23'); 
                        $this->Image(public_path('mofi/assets/images/logo/gradient_bg_title.png'), 10, $y_awal, 100, 10);
                        $this->_out('Q');
                        $this->SetTextColor(255, 255, 255);
                        $this->SetFont('Times', 'B', 14);
                        $this->Cell(0, 10, 'HASIL THREADMILL TEST', 0, 1, 'L');
                        $this->SetTextColor(0, 0, 0);
                        // Render Semua Gambar dalam Poli ini
                        $totalLampiran = count($dataPoli);
                        foreach ($dataPoli as $index => $item) {
                            $maxW = $this->GetPageWidth() - 20;
                            $maxH = 140;
                            if ($index > 0){
                                $maxH = 180;   
                            }

                            $imgPath = $item->data_foto;
                            $size = @getimagesize($imgPath);
                            if ($size === false) {
                                $imgPath = public_path('mofi/assets/images/logo/doc_not_found.jpg');
                                $size = @getimagesize($imgPath);
                            }

                            $scale = min($maxW / $size[0], $maxH / $size[1]);
                            $finalW = $size[0] * $scale;
                            $finalH = $size[1] * $scale;

                            $posX = ($this->GetPageWidth() - $finalW) / 2;
                            $posY = $this->GetY() - 10;

                            // 2. Cetak Gambar
                            $this->Image($imgPath, $posX, $posY + 10, $finalW, $finalH);
                            $this->SetTextColor(0, 0, 0);
                            if (($index + 1) < $totalLampiran) {
                               $this->AddPage('L', 'A4');
                            }
                        }
                    }
                }
            }

        };
        $fpdf->AliasNbPages();
        // 1. Halaman Cover
        $fpdf->AddPage('P');
        $fpdf->Image(public_path('mofi/assets/images/logo/compress_cover.jpg'), 0, 0, 210, 297);
        $fpdf->gantiPath($customFontPath);
        $fpdf->AddFont('SquadaOne', '', 'SquadaOne.php');
        $fpdf->SetFont('SquadaOne', '', 80);
        $fpdf->SetDrawColor(255, 255, 0);       
        $fpdf->SetTextColor(255, 87, 34);   
        $fpdf->SetTextOutline(0.8);         
        $fpdf->SetTextColor(255, 87, 34);
        $tahunSekarang = date('Y');
        $fpdf->SetXY(15, 20);
        $fpdf->Cell(50, 20, $tahunSekarang, 0, 0, 'L');
        $fpdf->gantiPath($vendorFontPath);
        $fpdf->SetFont('Times', '', 11);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->SetDrawColor(0, 0, 0);
        // 2. Halaman Profil (Section yang barusan kamu berikan)
        $fpdf->AddPage('P');
        $fpdf->renderProfilPeserta();
        // 3. Halaman Hasil Pemeriksaan
        $fpdf->AddPage('P');
        $fpdf->renderLaporanKesimpulan();
        // 4. Halaman Status Kesehatan
        $fpdf->AddPage('P');
        $fpdf->statusKesehatan();
        // 4. Halaman Riwayat Kesehatan
        $fpdf->AddPage('P');
        $fpdf->cetakRiwayat();
        // 5. PEMERIKSAAN KONDISI FISIK
        $fpdf->AddPage('P');
        $fpdf->cetakPemeriksaanKondisiFisik();
        $fpdf->AddPage('P');
        $fpdf->cetakLaboratorium();
        $fpdf->poliRontgenThorax();
        $fpdf->poliEkg();
        $fpdf->poliAudiometri();
        $fpdf->poliSpirometri();
        $fpdf->poliThreadmill();
        // 7. Penutup Cover
        $fpdf->AddPage('P');
        $fpdf->SetFont('Times','B',16);
        $fpdf->SetXY(0, 0);
        $fpdf->Image(public_path('mofi/assets/images/logo/compress_cover_back.jpg'), 0, 0, 210, 297);
        $nomor_mcu = $data['nomor_mcu'];
        $nik_peserta = $data['nik_peserta'];
        $tanggal_cetak = $data['tanggal_cetak'];
        $namaFile = 'Laporan_' . $nomor_mcu . '_' . $nik_peserta . '_' . str_replace(' ', '', $tanggal_cetak) . '.pdf';
        $fpdf->SetTitle('Laporan '.$namaFile);
        $fpdf->SetAuthor('Artha Medical Centre');
        $fpdf->SetSubject('Hasil Medical Check Up');
        $fpdf->SetCreator('Aplikasi MCU');
        return response($fpdf->Output('S'))->header('Content-Type', 'application/pdf')->header('Content-Disposition', 'inline; filename="'.$namaFile.'"');
        // // 3. Halaman Hasil Pemeriksaan (Sesuai CSS page-break-after: always)
        // $pdf->AddPage('P');
        // $pdf->SetMargins(10, 65, 10);
        // Lanjut render kategori laboratorium...
        // $folderPath = 'public/mcu/berkas/mcu/';
        // $filename = "MCU_".str_replace('/', '_', $nomor_mcu).'_'.$id_mcu.'_'.$nik_peserta.'.pdf';
        // $fullPath = storage_path("app/$folderPath$filename");
        // $pdf = PDF::loadView('paneladmin.laporan.berkas.pdf_berkas_mcu', ['data' => $data])
        //     ->setPaper('a4', 'portrait')
        //     ->setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
        // $pdf->render();
        // $pdf->get_canvas()->page_script(function ($pageNumber, $pageCount, $canvas) {
        //     if ($pageNumber > 1 && $pageNumber < $pageCount) {
        //         $width = $canvas->get_width();
        //         $text = "Halaman " . ($pageNumber - 1) . " Dari " . ($pageCount - 2);
        //         $x = ($width / 2) + 175;              
        //         $y = $canvas->get_height() - 40;
        //         $canvas->text($x, $y, $text, null, 12);
        //     }
        //     if ($pageCount == $pageNumber) {
        //         $width = $canvas->get_width();
        //         $height = $canvas->get_height();
        //         $canvas->image(public_path('mofi/assets/images/logo/compress_cover_back.jpg'), 0, 0, $width, $height);
        //     }
        // });
        // $pdf->save($fullPath);
        /*if (!Storage::exists($folderPath)) {
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
        }*/
        return response()->file($fullPath);
    }
    public function berkas_laboratorium(Request $req){
        $user = $req->attributes->get('user_details');
        $perusahaan = json_decode($user->json_perusahaan, true) ?? [];
        $ids = array_column($perusahaan, 'id');
        $data = $this->getData($req, 'Berkas Tindakan Laboratorium', [
            'Beranda' => route('admin.beranda'),
            'Berkas' => route('admin.laporan.berkas_laboratorium'),
        ]);
        $data['jenis_berkas'] = $req->segment(2);
        $data['id_perusahaan'] = $ids;
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
        $transaksi_laboratorium = TransaksiLab::where('no_mcu', $id_mcu)->first();
        $ada_lampiran_laboratorium_pdf = $transaksi_laboratorium?->lampirkan_berkas_pdf ?? '0';
        $total_tindakan = $transaksi_laboratorium?->total_tindakan ?? '0';
        $idTrxLab = $transaksi_laboratorium?->id;
        $lampiran_berkas_pdf = $idTrxLab
            ? UnggahanCitraLab::where('id_trx_lab', $idTrxLab)->get()
            : collect();
        $lampiran_berkas_pdf = $lampiran_berkas_pdf->map(function ($item) {
            $item->data_foto = url(env('APP_VERSI_API') . "/file/unduh_lampiran_pdf?file_name=" . $item->nama_file);
            return $item;
        });
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
            'ada_lampiran_laboratorium_pdf' => $ada_lampiran_laboratorium_pdf,
            'total_tindakan' => $total_tindakan,
            'lampiran_berkas_pdf' => $lampiran_berkas_pdf,
        ];
        $folderPath = 'public/mcu/berkas/laboratorium/';
        $filename = "LAB_".str_replace('/', '_', $nomor_mcu).'_'.$id_mcu.'_'.$nik_peserta.'.pdf';
        $fullPath = storage_path("app/$folderPath$filename");
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
        $data['keuangan'] = User::role('keuangan')->join('users_pegawai', 'users.id', '=', 'users_pegawai.id')->get();
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
        $keterangan = $dataparameter['keterangan'] ?? '-';
        $jenis_kuitansi = $dataparameter['jenis_kuitansi'];
        $id_direktur_keuangan = $dataparameter['id_direktur_keuangan'];
        $nama_direktur_keuangan = $dataparameter['nama_direktur_keuangan'];
        $nomor_surat = $dataparameter['nomor_surat'] ?? '-';
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
                '.$tablePrefix.'transaksi.jenis_layanan AS jenis_layanan,
                '.$tablePrefix.'transaksi.nama_paket_mcu AS nama_paket_mcu
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
            ->generate(00000));
        $data = [
            'title' => 'Berkas Tindakan MCU',
            'nomor_mcu' => $no_nota,
            'qrcode_no_nota' => $qrcode_no_nota,
            'qrcode_dokter' => $qrcode_dokter,
            'atas_nama_nota' => "Aries",
            'nip' => "000",
            'keterangan' => "Kuitansi Jenis Tindakan ".ucwords(str_replace("_", " ", $data_informasi->jenis_layanan)." ".$data_informasi->nama_paket_mcu),
            'tanggal_cetak' => $tanggal_cetak,
            'nama_peserta' => $data_informasi->nama_peserta,
            'total_pembayaran' => "Rp ".number_format($data_informasi->total_pembayaran + $data_informasi->nominal_apotek,2,",","."),
            'terbilang' => ucwords(GlobalHelper::terbilang($data_informasi->total_pembayaran + $data_informasi->nominal_apotek))." Rupiah",
            'nama_direktur_keuangan' => $nama_direktur_keuangan,
            'id_direktur_keuangan' => $id_direktur_keuangan,
            'nomor_surat' => $nomor_surat
        ];
        $folderPath = 'public/kuitansi/personal/';
        $filename = "KUITANSI_".date('YmdHis').".pdf";
        $fullPath = storage_path("app/$folderPath$filename");
        // if (!Storage::exists($folderPath)) {
        //     Storage::makeDirectory($folderPath, 0755, true);
        // }
        // if (!file_exists($fullPath)) {
        // }
        $width_mm = 215; 
        $height_mm = 130;
        $width_pt = $width_mm * 2.83465;
        $height_pt = $height_mm * 2.83465;
        $pdf = PDF::loadView('paneladmin.laporan.kuitansi.pdf_kuitansi_personal', ['data' => $data])
            ->setPaper([0, 0, $width_pt, $height_pt], 'portrait')
            ->setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
        $pdf->render();
        $pdf->save($fullPath);
        return response()->file($fullPath);
    }
    public function cetak_kuitansi_perusahaan(Request $req){
        $dataparameter = json_decode(base64_decode($req->query('data')), true);
        $tanggal_cetak = date('d').' '.GlobalHelper::getNamaBulanIndonesia(date('n')).' '.date('Y');
        $id_perusahaan = $dataparameter['id_perusahaan'];
        $kode_perusahaan = $dataparameter['kode_perusahaan'];
        $nama_perusahaan = $dataparameter['nama_perusahaan'];
        $id_direktur_keuangan = $dataparameter['id_direktur_keuangan'];
        $nama_direktur_keuangan = $dataparameter['nama_direktur_keuangan'];
        $nomor_surat = $dataparameter['nomor_surat'];
        $jenis_kuitansi = $dataparameter['jenis_kuitansi'];
        $jenis_transaksi = $dataparameter['jenis_transaksi'];
        $jenis_layanan = $dataparameter['jenis_layanan'];
        $status_pembayaran = $dataparameter['status_pembayaran'];
        $tablePrefix = config('database.connections.mysql.prefix');
        $data_informasi = TransaksiLab::join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'transaksi.no_mcu')
            ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
            ->join('transaksi_detail', 'transaksi_detail.id_transaksi', '=', 'transaksi.id')
            ->join('transaksi_tagihan','transaksi_tagihan.array_mcu_peserta_id','=','mcu_transaksi_peserta.id')
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
        $data_informasi = $data_informasi->groupby('mcu_transaksi_peserta.jenis_transaksi_pendaftaran','transaksi_detail.harga_setelah_diskon')->get();
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
            ->generate("0000"));
        $inv_resume_mcu_peserta = TransaksiLab::join('mcu_transaksi_peserta','transaksi.no_mcu','=','mcu_transaksi_peserta.id')
            ->join('users_member','users_member.id','=','mcu_transaksi_peserta.user_id')
            ->join('departemen_peserta','departemen_peserta.id','=','mcu_transaksi_peserta.departemen_id')
            ->where('mcu_transaksi_peserta.perusahaan_id', $id_perusahaan)
            ->get();
        $data = [
            'title' => 'Cetak Kuitansi Perusahaan',
            'detail_tagihan' => $data_informasi,
            'nama_perusahaan' => $first_row->nama_perusahaan,
            'no_transaksi_combine' => $new_nota,
            'qrcode_no_nota' => $qrcode_no_nota,
            'qrcode_dokter' => $qrcode_dokter,
            'atas_nama_nota' => "000",
            'nip' => "0000",
            'keterangan' => "",
            'tanggal_cetak' => $tanggal_cetak,
            'id_direktur_keuangan' => $id_direktur_keuangan,
            'nama_direktur_keuangan' => $nama_direktur_keuangan,
            'nomor_surat' => $nomor_surat,
            'grandTotal' => 0,
            'inv_resume_mcu_peserta' => $inv_resume_mcu_peserta,
        ];
        $folderPath = 'public/kuitansi/tagihan/';
        $filename = "TAGIHAN_".date('YmdHis').".pdf";
        $fullPath = storage_path("app/$folderPath$filename");
        $pdf = PDF::loadView('paneladmin.laporan.kuitansi.pdf_kuitansi_tagihan_perusahaan', ['data' => $data])
            ->setPaper('legal', 'portrait')
            ->setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
        $pdf->render();
        $pdf->save($fullPath);
        return response()->file($fullPath);
    }  
    public function cetak_kuitansi_tagihan_perusahaan(Request $req){
        $dataparameter = json_decode(base64_decode($req->query('data')), true);
        $tanggal_cetak = date('d').' '.GlobalHelper::getNamaBulanIndonesia(date('n')).' '.date('Y');
        $id_perusahaan = $dataparameter['id_perusahaan'];
        $kode_perusahaan = $dataparameter['kode_perusahaan'];
        $nama_perusahaan = $dataparameter['nama_perusahaan'];
        $id_direktur_keuangan = $dataparameter['id_direktur_keuangan'];
        $nama_direktur_keuangan = $dataparameter['nama_direktur_keuangan'];
        $nomor_surat = $dataparameter['nomor_surat'];
        $jenis_kuitansi = $dataparameter['jenis_kuitansi'];
        $jenis_transaksi = $dataparameter['jenis_transaksi'];
        $jenis_layanan = $dataparameter['jenis_layanan'];
        $status_pembayaran = $dataparameter['status_pembayaran'];
        $tablePrefix = config('database.connections.mysql.prefix');
        $inv_resume_mcu_peserta = TransaksiLab::join('mcu_transaksi_peserta','transaksi.no_mcu','=','mcu_transaksi_peserta.id')
            ->join('users_member','users_member.id','=','mcu_transaksi_peserta.user_id')
            ->join('departemen_peserta','departemen_peserta.id','=','mcu_transaksi_peserta.departemen_id')
            ->where('mcu_transaksi_peserta.perusahaan_id', $id_perusahaan)
            ->get();
        $qrcode_no_nota = base64_encode(QrCode::format('svg')
            ->size(75)
            ->margin(1)
            ->generate(base64_encode($id_perusahaan)));
        $atas_nama_nota = Pegawai::where('atas_nama_kuitansi', 1)->first();
        $qrcode_dokter = base64_encode(QrCode::format('svg')
            ->size(75)
            ->margin(1)
            ->generate($id_direktur_keuangan));
        $data = [
            'title' => 'Cetak Kuitansi Perusahaan',
            'inv_resume_mcu_peserta' => $inv_resume_mcu_peserta,
            'nama_perusahaan' => $nama_perusahaan,
            'qrcode_no_nota' => $qrcode_no_nota,
            'qrcode_dokter' => $qrcode_dokter,
            'atas_nama_nota' => $nama_direktur_keuangan,
            'nip' => "Direktur Keuangan",
            'nomor_surat' => $nomor_surat,
            'tanggal_cetak' => $tanggal_cetak,
            'total_pembayaran' => "Rp ".number_format(0,2,",","."),
            'terbilang' => ucwords(GlobalHelper::terbilang(0))." Rupiah"
        ];
        $folderPath = 'public/kuitansi/perusahaan/';
        $filename = "KUITANSI_".date('YmdHis').".pdf";
        $fullPath = storage_path("app/$folderPath$filename");
        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath, 0755, true);
        }
        if (!file_exists($fullPath)) {
            $pdf = PDF::loadView('paneladmin.laporan.kuitansi.pdf_kuitansi_perusahaan', ['data' => $data])
                ->setPaper('a4', 'portrait')
                ->setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
            $pdf->render();
            $pdf->save($fullPath);
        }
        return response()->file($fullPath);
    }
    public function cetak_berkas_mcu_threadmill(Request $req){
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
        $model = new Poliklinik();
        $jenis_polis = ['threadmill'];
        $all_citra_data = collect();
        $all_citra_laboratorium = collect();
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
            'all_citra_data' => $all_citra_data,
        ];
        $folderPath = 'public/mcu/berkas/laboratorium/';
        $filename = "LAB_".str_replace('/', '_', $nomor_mcu).'_'.$id_mcu.'_'.$nik_peserta.'.pdf';
        $fullPath = storage_path("app/$folderPath$filename");
        $pdf = PDF::loadView('paneladmin.laporan.berkas.pdf_berkas_threadmill', ['data' => $data])
            ->setPaper('a4', 'landscape')
            ->setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
        $pdf->render();
        $pdf->get_canvas()->page_script(function ($pageNumber, $pageCount, $canvas) {
            if ($pageNumber > 1 && $pageNumber < $pageCount) {
                $width = $canvas->get_width();
                $text = "Halaman " . ($pageNumber - 1) . " Dari " . ($pageCount - 2);
                $x = ($width / 2) + 275;              
                $y = $canvas->get_height() - 40;
                $canvas->text($x, $y, $text, null, 12);
            }
        
            if ($pageCount == $pageNumber) {
                $width = $canvas->get_width();
                $height = $canvas->get_height();
                $canvas->image(public_path('mofi/assets/images/logo/backcover_threadmill.jpg'), 0, 0, $width, $height);
            }
        });
        $pdf->save($fullPath);
        return response()->file($fullPath);
    }
    protected function laporanRekap(Request $req, string $jenis){
        $user = $req->attributes->get('user_details');
        $perusahaan = json_decode($user->json_perusahaan, true) ?? [];
        $ids = array_column($perusahaan, 'id');
        $title = 'Laporan Rekap ' . strtoupper(str_replace('_', ' ', $jenis));
        $data = $this->getData($req, $title, [
            'Beranda'       => route('admin.beranda'),
            'Laporan Rekap' => route("admin.laporan.laporan_rekap_$jenis"),
        ]);
        return view("paneladmin.laporan.rekap.$jenis", [
            'data'  => $data,
            'jenis' => $jenis,
            'ids'   => $ids,
        ]);
    }
    public function laporan_rekap_pemeriksaan_fisik(Request $req){
        return $this->laporanRekap($req, 'pemeriksaan_fisik');
    }

    public function laporan_rekap_vital(Request $req){
        return $this->laporanRekap($req, 'vital');
    }

    public function laporan_rekap_spirometri(Request $req){
        return $this->laporanRekap($req, 'spirometri');
    }

    public function laporan_rekap_audiometri(Request $req){
        return $this->laporanRekap($req, 'audiometri');
    }

    public function laporan_rekap_ekg(Request $req){
        return $this->laporanRekap($req, 'ekg');
    }

    public function laporan_rekap_threadmill(Request $req){
        return $this->laporanRekap($req, 'threadmill');
    }

    public function laporan_rekap_rontgen_thorax(Request $req){
        return $this->laporanRekap($req, 'rontgen_thorax');
    }

    public function laporan_rekap_rontgen_lumbosacral(Request $req){
        return $this->laporanRekap($req, 'rontgen_lumbosacral');
    }

    public function laporan_rekap_usg_ubdomain(Request $req){
        return $this->laporanRekap($req, 'usg_ubdomain');
    }

    public function laporan_rekap_farmingham_score(Request $req){
        return $this->laporanRekap($req, 'farmingham_score');
    }

}