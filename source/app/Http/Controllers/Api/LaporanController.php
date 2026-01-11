<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi\{Transaksi, UnggahCitra, LingkunganKerjaPeserta, RiwayatKecelakaanKerja, RiwayatKebiasaanHidup, RiwayatPenyakitTerdahulu, RiwayatPenyakitKeluarga, RiwayatImunisasi};
use App\Models\PemeriksaanFisik\{TingkatKesadaran, TandaVital, Penglihatan};
use App\Models\PemeriksaanFisik\KondisiFisik\{KondisiFisik, Gigi};
use App\Models\Laboratorium\{Transaksi as TransaksiLab, Kategori, TransaksiDetail, Kesimpulan as KesimpulanLabStatus};
use App\Models\Transaksi\UnggahanCitraLab;
use App\Models\Laporan\Kesimpulan;
use App\Models\EdsJasaPelayanan;
use App\Models\Masterdata\Jasalayanan;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\{Log, DB, Storage};
use Carbon\Carbon;


class LaporanController extends Controller
{
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
    private function determineTableNamePoliklinik($jenis_poli)
    {
        $tables = [
            'spirometri' => 'mcu_poli_spirometri',
            'ekg' => 'mcu_poli_ekg',
            'threadmill' => 'mcu_poli_threadmill',
            'rontgen_thorax' => 'mcu_poli_rontgen_thorax',
            'rontgen_lumbosacral' => 'mcu_poli_rontgen_lumbosacral',
            'usg_ubdomain' => 'mcu_poli_usg_ubdomain',
            'farmingham_score' => 'mcu_poli_farmingham_score',
            'audiometri' => 'mcu_poli_audiometri',
        ];
        return $tables[strtolower($jenis_poli)] ?? null;
    }
    public function validasi_mcu_nota(Request $req){
        try {
            $validator = Validator::make($req->all(), [
                'no_nota' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $tablePrefix = config('database.connections.mysql.prefix');
            $no_nota = base64_decode(rawurldecode($req->no_nota));
            $transaksi_id = Transaksi::where('no_transaksi', $no_nota)
                ->join('users_member', 'users_member.id', '=', 'mcu_transaksi_peserta.user_id')
                ->select('users_member.*', 'mcu_transaksi_peserta.id as transaksi_id')
                ->first();
            $informasi_user = DB::table('mcu_transaksi_peserta')
                ->leftJoin('transaksi', 'transaksi.no_mcu', '=', 'mcu_transaksi_peserta.id')
                ->join('users_member', 'users_member.id', '=', 'mcu_transaksi_peserta.user_id')
                ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
                ->join('departemen_peserta', 'departemen_peserta.id', '=', 'mcu_transaksi_peserta.departemen_id')
                ->select(
                    'users_member.*',
                    'departemen_peserta.nama_departemen',
                    'company.company_name',
                    'mcu_transaksi_peserta.id as transaksi_id',
                    'mcu_transaksi_peserta.status_peserta'
                )
                ->selectRaw('
                    COALESCE(COUNT('.$tablePrefix.'transaksi.no_mcu), 0) as kedatangan,
                    COALESCE(MAX('.$tablePrefix.'transaksi.created_at), "Belum Pernah Transaksi") as terakhir_datang,
                    COALESCE(SUM('.$tablePrefix.'transaksi.total_transaksi), 0) as valuasi,
                    TIMESTAMPDIFF(YEAR, '.$tablePrefix.'users_member.tanggal_lahir, CURDATE()) as umur
                ')
                ->where('mcu_transaksi_peserta.id', $transaksi_id->transaksi_id)
                ->groupBy('mcu_transaksi_peserta.id')
                ->first();

            /* Riwayat Informasi */
            $data_unggahan = UnggahCitra::where('transaksi_id', $transaksi_id->transaksi_id)->first();
            $jumlah_data_foto_data_diri = $data_unggahan ? $data_unggahan->count() : 0;
            $data_foto_diri = $data_unggahan ? url(env('APP_VERSI_API')."/file/unduh_foto?file_name=" . $data_unggahan->lokasi_gambar) : null;
            $jumlah_data_lingkungan_kerja = LingkunganKerjaPeserta::where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_kecelakaan_kerja = RiwayatKecelakaanKerja::where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_kebiasaan_hidup = RiwayatKebiasaanHidup::where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_penyakit_terdahulu = RiwayatPenyakitTerdahulu::where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_penyakit_keluarga = RiwayatPenyakitKeluarga::where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_imunisasi = RiwayatImunisasi::where('transaksi_id', $transaksi_id->transaksi_id)->count();
            /* Pemeriksaan Fisik */
            $jumlah_data_tingkat_kesehatan = TingkatKesadaran::where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_tanda_tanda_vital = TandaVital::where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_penglihatan = Penglihatan::where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_kepala = DB::table($this->determineTableNamePemeriksaanFisik('kepala'))->where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_telinga = DB::table($this->determineTableNamePemeriksaanFisik('telinga'))->where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_mata = DB::table($this->determineTableNamePemeriksaanFisik('mata'))->where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_tenggorokan = DB::table($this->determineTableNamePemeriksaanFisik('tenggorokan'))->where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_mulut = DB::table($this->determineTableNamePemeriksaanFisik('mulut'))->where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_gigi = DB::table($this->determineTableNamePemeriksaanFisik('gigi'))->where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_leher = DB::table($this->determineTableNamePemeriksaanFisik('leher'))->where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_thorax = DB::table($this->determineTableNamePemeriksaanFisik('thorax'))->where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_abdomen_urogenital = DB::table($this->determineTableNamePemeriksaanFisik('abdomen_urogenital'))->where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_anorectal_genital = DB::table($this->determineTableNamePemeriksaanFisik('anorectal_genital'))->where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_ekstremitas = DB::table($this->determineTableNamePemeriksaanFisik('ekstremitas'))->where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_neurologis = DB::table($this->determineTableNamePemeriksaanFisik('neurologis'))->where('transaksi_id', $transaksi_id->transaksi_id)->count();
            /* Poliklinik */
            $jumlah_data_spirometri = DB::table($this->determineTableNamePoliklinik('spirometri'))->where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_audiometri = DB::table($this->determineTableNamePoliklinik('audiometri'))->where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_ekg = DB::table($this->determineTableNamePoliklinik('ekg'))->where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_threadmill = DB::table($this->determineTableNamePoliklinik('threadmill'))->where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_rontgen_thorax = DB::table($this->determineTableNamePoliklinik('rontgen_thorax'))->where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_rontgen_lumbosacral = DB::table($this->determineTableNamePoliklinik('rontgen_lumbosacral'))->where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_usg_ubdomain = DB::table($this->determineTableNamePoliklinik('usg_ubdomain'))->where('transaksi_id', $transaksi_id->transaksi_id)->count();
            $jumlah_data_farmingham_score = DB::table($this->determineTableNamePoliklinik('farmingham_score'))->where('transaksi_id', $transaksi_id->transaksi_id)->count();
            /* Lab */
            $jumlah_data_lab = DB::table('transaksi')->where('no_mcu', $transaksi_id->transaksi_id)->count();
            $dynamicAttributes = [
                'detail_informasi_user' => $informasi_user,
                /* Riwayat Informasi */
                'jumlah_data_foto_data_diri' => $jumlah_data_foto_data_diri,
                'data_foto_diri' => $data_foto_diri,
                'jumlah_data_lingkungan_kerja' => $jumlah_data_lingkungan_kerja,
                'jumlah_data_kecelakaan_kerja' => $jumlah_data_kecelakaan_kerja,
                'jumlah_data_kebiasaan_hidup' => $jumlah_data_kebiasaan_hidup,
                'jumlah_data_penyakit_terdahulu' => $jumlah_data_penyakit_terdahulu,
                'jumlah_data_penyakit_keluarga' => $jumlah_data_penyakit_keluarga,
                'jumlah_data_imunisasi' => $jumlah_data_imunisasi,
                /* Pemeriksaan Fisik */
                'jumlah_data_tingkat_kesehatan' => $jumlah_data_tingkat_kesehatan,
                'jumlah_data_tanda_tanda_vital' => $jumlah_data_tanda_tanda_vital,
                'jumlah_data_penglihatan' => $jumlah_data_penglihatan,
                'jumlah_data_kepala' => $jumlah_data_kepala,
                'jumlah_data_telinga' => $jumlah_data_telinga,
                'jumlah_data_mata' => $jumlah_data_mata,
                'jumlah_data_tenggorokan' => $jumlah_data_tenggorokan,
                'jumlah_data_mulut' => $jumlah_data_mulut,
                'jumlah_data_gigi' => $jumlah_data_gigi,
                'jumlah_data_leher' => $jumlah_data_leher,
                'jumlah_data_thorax' => $jumlah_data_thorax,
                'jumlah_data_abdomen_urogenital' => $jumlah_data_abdomen_urogenital,
                'jumlah_data_anorectal_genital' => $jumlah_data_anorectal_genital,
                'jumlah_data_ekstremitas' => $jumlah_data_ekstremitas,
                'jumlah_data_neurologis' => $jumlah_data_neurologis,
                /* Poliklinik */
                'jumlah_data_spirometri' => $jumlah_data_spirometri,
                'jumlah_data_audiometri' => $jumlah_data_audiometri,
                'jumlah_data_ekg' => $jumlah_data_ekg,
                'jumlah_data_threadmill' => $jumlah_data_threadmill,
                'jumlah_data_rontgen_thorax' => $jumlah_data_rontgen_thorax,
                'jumlah_data_rontgen_lumbosacral' => $jumlah_data_rontgen_lumbosacral,
                'jumlah_data_usg_ubdomain' => $jumlah_data_usg_ubdomain,
                'jumlah_data_farmingham_score' => $jumlah_data_farmingham_score,
                /* Lab */
                'jumlah_data_lab' => $jumlah_data_lab,
            ];
            return ResponseHelper::data('Informasi Transaksi Tindakan', $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function validasi_mcu_modal(Request $req){
        try {
            $validator = Validator::make($req->all(), [
                'no_nota' => 'required',
                'kondisi' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $tablePrefix = config('database.connections.mysql.prefix');
            $no_nota = base64_decode(rawurldecode($req->no_nota));
            $transaksi_id = Transaksi::where('no_transaksi', $no_nota)
                ->join('users_member', 'users_member.id', '=', 'mcu_transaksi_peserta.user_id')
                ->select('users_member.*', 'mcu_transaksi_peserta.id as transaksi_id')
                ->first();
            if ($req->kondisi == 'fdd') {
                $informasi_mcu = UnggahCitra::where('transaksi_id', $transaksi_id->transaksi_id)->first();
                $informasi_mcu->data_foto = url(env('APP_VERSI_API')."/file/unduh_foto?file_name=" . $informasi_mcu->lokasi_gambar);
            }
            if ($req->kondisi == 'lk') {
                $informasi_mcu = LingkunganKerjaPeserta::where('transaksi_id', $transaksi_id->transaksi_id)->get();
            }
            if ($req->kondisi == 'kk') {
                $informasi_mcu = RiwayatKecelakaanKerja::where('transaksi_id', $transaksi_id->transaksi_id)->get();
            }
            if ($req->kondisi == 'kh') {
                $informasi_mcu = RiwayatKebiasaanHidup::where('transaksi_id', $transaksi_id->transaksi_id)->get();
            }
            if ($req->kondisi == 'pt') {
                $informasi_mcu = RiwayatPenyakitTerdahulu::where('transaksi_id', $transaksi_id->transaksi_id)->get();
            }
            if ($req->kondisi == 'pk') {
                $informasi_mcu = RiwayatPenyakitKeluarga::where('transaksi_id', $transaksi_id->transaksi_id)->get();
            }
            if ($req->kondisi == 'im') {
                $informasi_mcu = RiwayatImunisasi::where('transaksi_id', $transaksi_id->transaksi_id)->get();
            }
            if ($req->kondisi == 'tk') {
                $informasi_mcu = TingkatKesadaran::where('transaksi_id', $transaksi_id->transaksi_id)->get();
            }
            if ($req->kondisi == 'tv') {
                $informasi_mcu = TandaVital::where('transaksi_id', $transaksi_id->transaksi_id)->get();
            }
            if ($req->kondisi == 'eye') {
                $informasi_mcu = Penglihatan::where('transaksi_id', $transaksi_id->transaksi_id)->get();
            }
            if ($req->kondisi == 'kp' || $req->kondisi == 'tlg' || $req->kondisi == 'mt' || $req->kondisi == 'tng' || $req->kondisi == 'mlt' || $req->kondisi == 'gg' || $req->kondisi == 'lhr' || $req->kondisi == 'thx' || $req->kondisi == 'anu' || $req->kondisi == 'ang' || $req->kondisi == 'etm' || $req->kondisi == 'nu') {
                $table = "";
                if ($req->kondisi == 'kp') {
                    $table = $this->determineTableNamePemeriksaanFisik('kepala');
                }
                if ($req->kondisi == 'tlg') {
                    $table = $this->determineTableNamePemeriksaanFisik('telinga');
                }
                if ($req->kondisi == 'mt') {
                    $table = $this->determineTableNamePemeriksaanFisik('mata');
                }
                if ($req->kondisi == 'tng') {
                    $table = $this->determineTableNamePemeriksaanFisik('tenggorokan');
                }
                if ($req->kondisi == 'mlt') {
                    $table = $this->determineTableNamePemeriksaanFisik('mulut');
                }
                if ($req->kondisi == 'gg') {
                    $informasi_mcu_gigi = Gigi::where('transaksi_id', $transaksi_id->transaksi_id)->get();
                    $table = $this->determineTableNamePemeriksaanFisik('gigi');
                }
                if ($req->kondisi == 'lhr') {
                    $table = $this->determineTableNamePemeriksaanFisik('leher');
                }
                if ($req->kondisi == 'thx') {
                    $table = $this->determineTableNamePemeriksaanFisik('thorax');
                }
                if ($req->kondisi == 'anu') {
                    $table = $this->determineTableNamePemeriksaanFisik('abdomen_urogenital');
                }
                if ($req->kondisi == 'ang') {
                    $table = $this->determineTableNamePemeriksaanFisik('anorectal_genital');
                }
                if ($req->kondisi == 'etm') {
                    $table = $this->determineTableNamePemeriksaanFisik('ekstremitas');
                }
                if ($req->kondisi == 'nu') {
                    $table = $this->determineTableNamePemeriksaanFisik('neurologis');
                }
                $informasi_mcu = DB::table($table)->where('transaksi_id', $transaksi_id->transaksi_id)->get();
            }
            if ($req->kondisi == 'sp' || $req->kondisi == 'ekg' || $req->kondisi == 'tm' || $req->kondisi == 'rsn_thorax' || $req->kondisi == 'rsn_lumbosacral' || $req->kondisi == 'usg_ubdomain' || $req->kondisi == 'farmingham_score' || $req->kondisi == 'au') {
                if ($req->kondisi == 'sp') {
                    $table = $this->determineTableNamePoliklinik('spirometri');
                }
                if ($req->kondisi == 'ekg') {
                    $table = $this->determineTableNamePoliklinik('ekg');
                }
                if ($req->kondisi == 'tm') {
                    $table = $this->determineTableNamePoliklinik('threadmill');
                }
                if ($req->kondisi == 'rsn_thorax') {
                    $table = $this->determineTableNamePoliklinik('rontgen_thorax');
                }
                if ($req->kondisi == 'rsn_lumbosacral') {
                    $table = $this->determineTableNamePoliklinik('rontgen_lumbosacral');
                }
                if ($req->kondisi == 'usg_ubdomain') {
                    $table = $this->determineTableNamePoliklinik('usg_ubdomain');
                }
                if ($req->kondisi == 'farmingham_score') {
                    $table = $this->determineTableNamePoliklinik('farmingham_score');
                }
                if ($req->kondisi == 'au') {
                    $table = $this->determineTableNamePoliklinik('audiometri');
                }
                $informasi_mcu = DB::table($table)->where('transaksi_id', $transaksi_id->transaksi_id)->first();
            }
            $dynamicAttributes = [
                'informasi_mcu' => $informasi_mcu,
                'informasi_user' => $transaksi_id,
            ];
            if ($req->kondisi == 'gg') {
                $dynamicAttributes['informasi_mcu_gigi'] = $informasi_mcu_gigi;
            }
            
            
            return ResponseHelper::data('Informasi Transaksi Tindakan', $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function validasi_mcu_nota_akhir(Request $req){
        try {
            $validator = Validator::make($req->all(), [
                'mcu_transaksi_id' => 'required',
                'status' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $no_nota = base64_decode(rawurldecode($req->mcu_transaksi_id));
            Transaksi::where('no_transaksi', $no_nota)->update([
                'status_peserta' => $req->status,
            ]);
            $infromasi_nama_file = Transaksi::join('users_member','users_member.id','=','mcu_transaksi_peserta.user_id')
                ->select('users_member.nomor_identitas','mcu_transaksi_peserta.id as transaksi_id')
                ->where('no_transaksi', $no_nota)->first();
            $paket_mcu = Transaksi::join('paket_mcu','paket_mcu.id','=','mcu_transaksi_peserta.id_paket_mcu')
                ->select('paket_mcu.id','paket_mcu.nama_paket','paket_mcu.harga_paket','mcu_transaksi_peserta.jenis_transaksi_pendaftaran','mcu_transaksi_peserta.id as id_mcu_peserta')
                ->where('no_transaksi', $no_nota)->first();
            $dynamicAttributes = [];
            $folderPathMCU = 'mcu/berkas/mcu/';
            $file_mcu_lama = "MCU_" . str_replace('/', '_', $no_nota) . '_' . $infromasi_nama_file->transaksi_id . '_' . $infromasi_nama_file->nomor_identitas . '.pdf';
            $folderPathLab = 'mcu/berkas/laboratorium/';
            $file_lab_lama = "LAB_" . str_replace('/', '_', $no_nota) . '_' . $infromasi_nama_file->transaksi_id . '_' . $infromasi_nama_file->nomor_identitas . '.pdf';
            $relativePathMCU = $folderPathMCU . $file_mcu_lama;
            $relativePathLAB = $folderPathLab . $file_lab_lama;
            Storage::disk('public')->delete($relativePathMCU);
            Storage::disk('public')->delete($relativePathLAB);
            /* Insert Sama Seperti Laboratorium, Cuma Kalau Ini By Pass Tanpa Laboratorium */
            TransaksiLab::where('no_nota', $no_nota)->forceDelete();
            $userDetails = $req->get('user_details');
            $data_transaksi = [
                'no_mcu' => $paket_mcu->id_mcu_peserta,
                'no_nota' => $no_nota,
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
            $data_tindakan[] = [
                'id_transaksi' => $hasil_query_tranaksi->id,
                'id_item' => 1,
                'kode_item' => '1000001000001',
                'nama_item' => "Paket MCU ".$paket_mcu->nama_paket,
                'nilai_tindakan' => 0,
                'harga' => $paket_mcu->harga_paket,
                'diskon' => 0,
                'harga_setelah_diskon' => $paket_mcu->harga_paket,
                'jumlah' => 1,
                'keterangan' => "PAKET MCU ".$paket_mcu->nama_paket,
                'meta_data_kuantitatif' => "{}",
                'meta_data_kualitatif' => "{}",
                'meta_data_jasa' => "{}",
                'meta_data_jasa_fee' => "{}",
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ];
            TransaksiDetail::insert($data_tindakan);
            //Tambah fee untuk dokter validasi trigger
            if ($req->status != 'selesai') {
                EdsJasaPelayanan::where('id_mcu_peserta', $paket_mcu->id_mcu_peserta)
                    ->where('role','dokter_validasi')
                    ->delete();
            }else{
                $row = EdsJasaPelayanan::where('id_mcu_peserta', $paket_mcu->id_mcu_peserta)
                    ->where('jenis_poli', 'dokter')
                    ->where('role', 'dokter_validasi')
                    ->first();
                $userDetails = $req->get('user_details');
                $layanan = Jasalayanan::where('kode_jasa_pelayanan', 'JS_DOKTER_VALIDASI')->first();
                $nominal = $layanan ? $layanan->nominal_layanan : 0;
                if ($row) {
                    if ($row->nominal <= 0) {
                        $row->nominal = $nominal;
                        $row->save();
                    }
                } else {
                    EdsJasaPelayanan::create([
                        'id_mcu_peserta' => $paket_mcu->id_mcu_peserta,
                        'jenis_poli' => 'dokter',
                        'role' => 'dokter_validasi',
                        'pegawai_id' => $userDetails->id,
                        'nominal' => $nominal,
                    ]);
                }   
            }
            return ResponseHelper::success('Validasi atas nomor dokumen '.$no_nota.' berhasil diubah menjadi '.$req->status_text.'. Berkas lama akan dihapus dan digantikan dengan yang baru', $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function validasi_rekap_kesimpulan(Request $req){
        try {
            $validator = Validator::make($req->all(), [
                'id_mcu_let' => 'required',
                'nomor_mcu_let' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            $informasi_mcu = Transaksi::join('users_member', 'users_member.id', '=', 'mcu_transaksi_peserta.user_id')
                ->select('users_member.nama_peserta')
                ->where('no_transaksi', $req->nomor_mcu_let)->first();
            $is_mcu_exist = Kesimpulan::where('id_mcu', $req->id_mcu_let)->first();
            $data = [
                'id_mcu' => $req->id_mcu_let,
                'kesimpulan_riwayat_medis' => $req->hasil_kesimpulan_riwayat_medis,
                'kesimpulan_pemeriksaan_fisik' => $req->hasil_kesimpulan_pemeriksaan_fisik,
                'status_pemeriksaan_laboratorium' => $req->status_pemeriksaan_laboratorium,
                'kesimpulan_pemeriksaan_laboratorum' => $req->hasil_kesimpulan_pemeriksaan_laboratorium,
                'kesimpulan_pemeriksaan_threadmill' => $req->hasil_kesimpulan_pemeriksaan_threadmill,
                'kesimpulan_pemeriksaan_rontgen_thorax' => $req->hasil_kesimpulan_pemeriksaan_rontgen_thorax,
                'kesimpulan_pemeriksaan_rontgen_lumbosacral' => $req->hasil_kesimpulan_pemeriksaan_rontgen_lumbosacral,
                'kesimpulan_pemeriksaan_usg_ubdomain' => $req->hasil_kesimpulan_pemeriksaan_usg_ubdomain,
                'kesimpulan_pemeriksaan_farmingham_score' => $req->hasil_kesimpulan_pemeriksaan_farmingham_score,
                'kesimpulan_pemeriksaan_ekg' => $req->hasil_kesimpulan_pemeriksaan_ekg,
                'kesimpulan_pemeriksaan_audio_kiri' => $req->hasil_kesimpulan_pemeriksaan_audio_kiri,
                'kesimpulan_pemeriksaan_audio_kanan' => $req->hasil_kesimpulan_pemeriksaan_audio_kanan,
                'kesimpulan_pemeriksaan_spiro_restriksi' => $req->hasil_kesimpulan_pemeriksaan_spirometri_restriksi,
                'kesimpulan_pemeriksaan_spiro_obstruksi' => $req->hasil_kesimpulan_pemeriksaan_spirometri_obstruksi,
                'kesimpulan_keseluruhan' => $req->hasil_kesimpulan_pemeriksaan_kesimpulan_tindakan,
                'kesimpulan_hasil_medical_checkup' => $req->kesimpulan_hasil_medical_checkup,
                'saran_keseluruhan' => $req->hasil_kesimpulan_pemeriksaan_tindakan_saran,
                'kesimpulan_warna' => $req->kesimpulan_warna,
            ];
            if ($is_mcu_exist) {
                Kesimpulan::where('id_mcu', $req->id_mcu_let)->update($data);
            } else {
                Kesimpulan::create($data);
            }
            $row = EdsJasaPelayanan::where('id_mcu_peserta', $req->id_mcu_let)
                ->where('jenis_poli', 'admin_mcu')
                ->where('role', 'admin_mcu')
                ->first();
            $userDetails = $req->get('user_details');
            $layanan = Jasalayanan::where('kode_jasa_pelayanan', 'JS_ADMIN_MCU')->first();
            $nominal = $layanan ? $layanan->nominal_layanan : 0;
            if ($row) {
                if ($row->nominal <= 0) {
                    $row->nominal = $nominal;
                    $row->save();
                }
            } else {
                EdsJasaPelayanan::create([
                    'id_mcu_peserta' => $req->id_mcu_let,
                    'jenis_poli' => 'admin_mcu',
                    'role' => 'admin_mcu',
                    'pegawai_id' => $userDetails->id,
                    'nominal' => $nominal,
                ]);
            }
            $dynamicAttributes = [];
            return ResponseHelper::success('Informasi kesimpulan dari nomor dokumen '.$req->nomor_mcu_let.' berhasil disimpan atas nama '.$informasi_mcu->nama_peserta, $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function validasi_rekap_kesimpulan_get(Request $req){
       try {
            $id_mcu = $req->id_mcu_let;
            if ($req->id_mcu_let == "") {
                $id_mcu = Transaksi::where('no_transaksi', $req->nomor_mcu_let)->first()->id;
            }
            $informasi_mcu = Kesimpulan::where('id_mcu', $id_mcu)->first();
            $kesimpulan_tindakan = KesimpulanLabStatus::all();
            $kesimpulan_tindakan_status = KesimpulanLabStatus::where('id', $informasi_mcu->kesimpulan_keseluruhan ?? "")->first();
            $count_poliklinik_spirometri = DB::table('mcu_poli_spirometri')->where('transaksi_id', $id_mcu)->count();
            $count_poliklinik_ekg = DB::table('mcu_poli_ekg')->where('transaksi_id', $id_mcu)->count();
            $count_poliklinik_threadmill = DB::table('mcu_poli_threadmill')->where('transaksi_id', $id_mcu)->count();
            $count_poliklinik_rontgen_thorax = DB::table('mcu_poli_rontgen_thorax')->where('transaksi_id', $id_mcu)->count();
            $count_poliklinik_rontgen_lumbosacral = DB::table('mcu_poli_rontgen_lumbosacral')->where('transaksi_id', $id_mcu)->count();
            $count_poliklinik_usg_ubdomain = DB::table('mcu_poli_usg_ubdomain')->where('transaksi_id', $id_mcu)->count();
            $count_poliklinik_farmingham_score = DB::table('mcu_poli_farmingham_score')->where('transaksi_id', $id_mcu)->count();
            $count_poliklinik_audiometri = DB::table('mcu_poli_audiometri')->where('transaksi_id', $id_mcu)->count();
            $data_poliklinik = [
                'count_poliklinik_spirometri' => $count_poliklinik_spirometri,
                'count_poliklinik_ekg' => $count_poliklinik_ekg,
                'count_poliklinik_threadmill' => $count_poliklinik_threadmill,
                'count_poliklinik_rontgen_thorax' => $count_poliklinik_rontgen_thorax,
                'count_poliklinik_rontgen_lumbosacral' => $count_poliklinik_rontgen_lumbosacral,
                'count_poliklinik_usg_ubdomain' => $count_poliklinik_usg_ubdomain,
                'count_poliklinik_farmingham_score' => $count_poliklinik_farmingham_score,
                'count_poliklinik_audiometri' => $count_poliklinik_audiometri,
            ];
            $dynamicAttributes = [
                'data' => $informasi_mcu,
                'data_kesimpulan_tindakan' => $kesimpulan_tindakan,
                'data_kesimpulan_tindakan_status' => $kesimpulan_tindakan_status,
                'data_poliklinik' => $data_poliklinik,
            ];
            return ResponseHelper::data('Informasi Kesimpulan', $dynamicAttributes);
       } catch (\Throwable $th) {
            return ResponseHelper::error($th);
       }
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
    public function informasi_mcu(Request $req){
        try {
            $validator = Validator::make($req->all(), [
                'id_mcu' => 'required',
            ]);
            if ($validator->fails()) {
                $dynamicAttributes = ['errors' => $validator->errors()];
                return ResponseHelper::error_validation(__('auth.eds_required_data'), $dynamicAttributes);
            }
            /* Riwayat Informasi */
            $tablePrefix = config('database.connections.mysql.prefix');
            $riwayat_informasi_foto = UnggahCitra::where('transaksi_id', $req->id_mcu)->first();
            $informasi_data_diri = Transaksi::join('users_member', 'users_member.id', '=', 'mcu_transaksi_peserta.user_id')
                ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
                ->join('departemen_peserta', 'departemen_peserta.id', '=', 'mcu_transaksi_peserta.departemen_id')
                ->select('users_member.nama_peserta', 'users_member.nomor_identitas', 'users_member.tempat_lahir', 'users_member.tanggal_lahir', 'users_member.jenis_kelamin', 'users_member.alamat', 'company.company_name', 'departemen_peserta.nama_departemen', 'mcu_transaksi_peserta.tanggal_transaksi as tanggal_mcu', 'mcu_transaksi_peserta.jenis_transaksi_pendaftaran','mcu_transaksi_peserta.tipe_mcu_peserta')
                ->selectRaw('TIMESTAMPDIFF(YEAR, ' . $tablePrefix . 'users_member.tanggal_lahir, CURDATE()) AS umur')
                ->where('mcu_transaksi_peserta.id', $req->id_mcu)->first();
            if ($riwayat_informasi_foto) {
                $riwayat_informasi_foto->data_foto = url(env('APP_VERSI_API')."/file/unduh_foto?file_name=" . ($riwayat_informasi_foto->lokasi_gambar ?? ""));
            }else{
                $informasi_data_diri->data_foto = "";
            }
            $kesimpulan_tindakan = Kesimpulan::where('id_mcu', $req->id_mcu)->first();
            $penyakit_terdahulu = RiwayatPenyakitTerdahulu::where('transaksi_id', $req->id_mcu)->get();
            $riwayat_penyakit_keluarga = RiwayatPenyakitKeluarga::where('transaksi_id', $req->id_mcu)->get();
            $riwayat_kebiasaan_hidup = RiwayatKebiasaanHidup::where('transaksi_id', $req->id_mcu)->get();
            $riwayat_kecelakaan_kerja = RiwayatKecelakaanKerja::where('transaksi_id', $req->id_mcu)->get();
            $riwayat_imunisasi = RiwayatImunisasi::where('transaksi_id', $req->id_mcu)->get();
            $riwayat_lingkungan_kerja = LingkunganKerjaPeserta::where('transaksi_id', $req->id_mcu)->get();
            $tingkat_kesadaran = TingkatKesadaran::where('transaksi_id', $req->id_mcu)->first();
            $tanda_vital = TandaVital::where('transaksi_id', $req->id_mcu)->get();
            $penglihatan = Penglihatan::where('transaksi_id', $req->id_mcu)->first();
            $transaksi_header = TransaksiLab::where('no_mcu', $req->id_mcu)->first();
            $threadmill = DB::table($this->determineTableNamePoliklinik('threadmill'))->where('transaksi_id', $req->id_mcu)->get();
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
                    ->where('transaksi_id', $req->id_mcu);
            
                if ($query_kondisi_fisik) {
                    $query_kondisi_fisik->union($subquery);
                } else {
                    $query_kondisi_fisik = $subquery;
                }
            }
            $lampiran_berkas_pdf = $transaksi_header->id
                ? UnggahanCitraLab::where('id_trx_lab', $transaksi_header->id)->get()
                : collect();
            $lampiran_berkas_pdf = $lampiran_berkas_pdf->map(function ($item) {
                $item->data_foto = url(env('APP_VERSI_API') . "/file/unduh_lampiran_pdf?file_name=" . $item->nama_file);
                return $item;
            });
            $data_kondisi_fisik = $query_kondisi_fisik ? $query_kondisi_fisik->get() : collect([]);
            $laboratorium = $this->getHasilLaboratorium($req->id_mcu);
            $dynamicAttributes = [
                'riwayat_informasi_foto' => $riwayat_informasi_foto,
                'informasi_data_diri' => $informasi_data_diri,
                'kesimpulan_tindakan' => $kesimpulan_tindakan,
                'penyakit_terdahulu' => $penyakit_terdahulu,
                'riwayat_penyakit_keluarga' => $riwayat_penyakit_keluarga,
                'riwayat_kebiasaan_hidup' => $riwayat_kebiasaan_hidup,
                'riwayat_kecelakaan_kerja' => $riwayat_kecelakaan_kerja,
                'riwayat_imunisasi' => $riwayat_imunisasi,
                'riwayat_lingkungan_kerja' => $riwayat_lingkungan_kerja,
                'tingkat_kesadaran' => $tingkat_kesadaran,
                'penglihatan' => $penglihatan,
                'tanda_vital' => $tanda_vital,
                'kondisi_fisik' => $data_kondisi_fisik,
                'laboratorium' => $laboratorium,
                'transaksi_header' => $transaksi_header,
                'lampiran_berkas_pdf' => $lampiran_berkas_pdf,
                'threadmill' => $threadmill,
            ];
            return ResponseHelper::data('Informasi MCU', $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function laporan_tindakan(Request $request)
    {
        $perHalaman = (int) $request->length > 0 ? (int) $request->length : 0;
        $nomorHalaman = ($perHalaman > 0) ? (int) $request->start / $perHalaman : 0;
        $offset = $nomorHalaman * $perHalaman;
        $parameterpencarian = $request->parameter_pencarian;
        $status_pembayaran = $request->status_pembayaran;
        $jenis_layanan = $request->jenis_layanan;
        $jenis_transaksi = $request->jenis_transaksi;
        $jenis_laporan = $request->jenis_laporan;
        $tanggal_awal = Carbon::parse($request->tanggal_awal)->startOfDay();
        $tanggal_akhir = Carbon::parse($request->tanggal_akhir)->endOfDay();
        $tablePrefix = config('database.connections.mysql.prefix');
        try {
            if ($jenis_laporan === 'transaksi_tindakan') {
                $query = TransaksiLab::selectRaw('
                    '.$tablePrefix.'transaksi.jenis_transaksi AS jenis_transaksi,
                    '.$tablePrefix.'transaksi.status_pembayaran AS status_pembayaran,
                    '.$tablePrefix.'transaksi.jenis_layanan AS jenis_layanan,
                    '.$tablePrefix.'mcu_transaksi_peserta.no_transaksi AS nomor_mcu,
                    '.$tablePrefix.'transaksi.no_nota AS nomor_nota,
                    '.$tablePrefix.'transaksi.waktu_trx AS waktu_trx,
                    '.$tablePrefix.'transaksi.waktu_trx_sample AS waktu_trx_sample,
                    '.$tablePrefix.'transaksi.total_transaksi AS total_bayar_tindakan,
                    '.$tablePrefix.'transaksi.nominal_apotek AS total_bayar_apotek,
                    '.$tablePrefix.'transaksi.nama_dokter AS nama_dokter,
                    '.$tablePrefix.'transaksi.nama_pj AS nama_penanggung_jawab,
                    '.$tablePrefix.'transaksi.nama_paket_mcu AS paket_yang_diambil
                ')
                ->join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'transaksi.no_mcu')
                ->join('users_member', 'users_member.id', '=', 'mcu_transaksi_peserta.user_id')
                ->whereBetween('transaksi.created_at', [$tanggal_awal, $tanggal_akhir]);
                if ($jenis_transaksi != ""){
                    $query->where('transaksi.jenis_transaksi', $jenis_transaksi);
                }
                if ($jenis_layanan != ""){
                    $query->where('transaksi.jenis_layanan', $jenis_layanan);
                }
                if ($status_pembayaran != ""){
                    $query->where('transaksi.status_pembayaran', $status_pembayaran);
                }
            } else if ($jenis_laporan === 'transaksi_tindakan_detail' ) {
                $query = TransaksiLab::selectRaw('
                    '.$tablePrefix.'transaksi.id AS id_transaksi,
                    '.$tablePrefix.'transaksi.no_nota AS nomor_nota,
                    '.$tablePrefix.'mcu_transaksi_peserta.no_transaksi AS no_mcu,
                    '.$tablePrefix.'transaksi.waktu_trx AS waktu_trx,
                    '.$tablePrefix.'transaksi_detail.kode_item AS kode_item,
                    '.$tablePrefix.'transaksi_detail.nama_item AS nama_item,
                    '.$tablePrefix.'transaksi_detail.harga AS harga,
                    '.$tablePrefix.'transaksi_detail.diskon AS diskon,
                    '.$tablePrefix.'transaksi_detail.harga_setelah_diskon AS harga_setelah_diskon,
                    '.$tablePrefix.'transaksi_detail.jumlah AS jumlah,
                    '.$tablePrefix.'transaksi.nominal_apotek AS total_bayar_apotek,
                    '.$tablePrefix.'transaksi.total_transaksi AS total_bayar_tindakan
                ')
                ->join('transaksi_detail', 'transaksi_detail.id_transaksi', '=', 'transaksi.id')
                ->join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'transaksi.no_mcu')
                ->join('users_member', 'users_member.id', '=', 'mcu_transaksi_peserta.user_id')
                ->whereBetween('transaksi.created_at', [$tanggal_awal, $tanggal_akhir]);
                if ($jenis_transaksi != ""){
                    $query->where('transaksi.jenis_transaksi', $jenis_transaksi);
                }
                if ($jenis_layanan != ""){
                    $query->where('transaksi.jenis_layanan', $jenis_layanan);
                }
                if ($status_pembayaran != ""){
                    $query->where('transaksi.status_pembayaran', $status_pembayaran);
                }
            } else if ($jenis_laporan === 'transaksi_tindakan_terbanyak') {
                $query = TransaksiLab::selectRaw('
                    '.$tablePrefix.'transaksi.id AS id_transaksi,
                    '.$tablePrefix.'transaksi.no_nota AS nomor_nota,
                    '.$tablePrefix.'transaksi.waktu_trx AS waktu_trx,
                    '.$tablePrefix.'transaksi_detail.kode_item AS kode_item,
                    '.$tablePrefix.'transaksi_detail.nama_item AS nama_item,
                    SUM('.$tablePrefix.'transaksi_detail.jumlah) AS jumlah

                ')
                ->join('transaksi_detail', 'transaksi_detail.id_transaksi', '=', 'transaksi.id')
                ->whereBetween('transaksi.created_at', [$tanggal_awal, $tanggal_akhir]);
                if ($jenis_transaksi != ""){
                    $query->where('transaksi.jenis_transaksi', $jenis_transaksi);
                }
                if ($jenis_layanan != ""){
                    $query->where('transaksi.jenis_layanan', $jenis_layanan);
                }
                if ($status_pembayaran != ""){
                    $query->where('transaksi.status_pembayaran', $status_pembayaran);
                }
                $query->groupBy(['transaksi_detail.kode_item'])->orderByRaw('jumlah DESC, waktu_trx DESC');
            } else {
                return ResponseHelper::data_not_found('Jenis laporan tidak valid.');
            }
            $dataSUMGlobal = $query->get();
            $jumlahdata = $query->count();
            $fetchdata = ($perHalaman > 0)
                ? $query->skip($offset)->take($perHalaman)->get()
                : $query->get();
            return response()->json([
                'data' => $fetchdata,
                'data_total' => $dataSUMGlobal,
                'recordsFiltered' => $jumlahdata,
                'pages' => [
                    'limit' => $perHalaman,
                    'offset' => $offset,
                ],
            ]);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function laporan_insentif(Request $request){
        try {
            $perHalaman = (int) $request->length > 0 ? (int) $request->length : 0;
            $nomorHalaman = ($perHalaman > 0) ? (int) $request->start / $perHalaman : 0;
            $offset = $nomorHalaman * $perHalaman;
            $parameterpencarian = $request->parameter_pencarian;
            $status_pembayaran = $request->status_pembayaran;
            $jenis_layanan = $request->jenis_layanan;
            $jenis_transaksi = $request->jenis_transaksi;
            $jenis_laporan = $request->jenis_laporan;
            $tanggal_awal = Carbon::parse($request->tanggal_awal)->startOfDay();
            $tanggal_akhir = Carbon::parse($request->tanggal_akhir)->endOfDay();
            $pegawai_id = $request->pegawai_id;
            $tablePrefix = config('database.connections.mysql.prefix');
            if ($jenis_laporan == "insentif_tindakan") { 
                $query = EdsJasaPelayanan::join('users_pegawai', 'users_pegawai.id', '=', 'jasa_pelayanan.pegawai_id')
                ->join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'jasa_pelayanan.id_mcu_peserta')
                ->selectRaw('
                '.$tablePrefix.'users_pegawai.id as id_pegawai,
                '.$tablePrefix.'users_pegawai.nik,
                '.$tablePrefix.'users_pegawai.nama_pegawai,
                SUM('.$tablePrefix.'jasa_pelayanan.nominal) as nominal_jasa_pelayanan')
                ->whereBetween('jasa_pelayanan.created_at', [$tanggal_awal, $tanggal_akhir]);
                $query->groupBy(['jasa_pelayanan.pegawai_id'])->orderByRaw($tablePrefix.'jasa_pelayanan.jenis_poli ASC');
            }else if ($jenis_laporan == "detail_insentif_tindakan"){
                $query = EdsJasaPelayanan::join('users_pegawai', 'users_pegawai.id', '=', 'jasa_pelayanan.pegawai_id')
                ->join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'jasa_pelayanan.id_mcu_peserta')
                ->selectRaw('
                '.$tablePrefix.'mcu_transaksi_peserta.no_transaksi as no_mcu,
                '.$tablePrefix.'jasa_pelayanan.jenis_poli,
                '.$tablePrefix.'jasa_pelayanan.role as jenis_tindakan,
                '.$tablePrefix.'jasa_pelayanan.nominal')
                ->where('jasa_pelayanan.pegawai_id', $pegawai_id)
                ->whereBetween('jasa_pelayanan.created_at', [$tanggal_awal, $tanggal_akhir])
                ->orderByRaw($tablePrefix.'jasa_pelayanan.created_at DESC');
            }
            $dataSUMGlobal = $query->get();
            $jumlahdata = $query->count();
            $fetchdata = ($perHalaman > 0)
                ? $query->skip($offset)->take($perHalaman)->get()
                : $query->get();
            return response()->json([
                'data' => $fetchdata,
                'data_total' => $dataSUMGlobal,
                'recordsFiltered' => $jumlahdata,
                'pages' => [
                    'limit' => $perHalaman,
                    'offset' => $offset,
                ],
            ]);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function laporan_kuitansi(Request $request){
        try {
            $perHalaman = (int) $request->length > 0 ? (int) $request->length : 0;
            $nomorHalaman = ($perHalaman > 0) ? (int) $request->start / $perHalaman : 0;
            $offset = $nomorHalaman * $perHalaman;
            $parameterpencarian = $request->parameter_pencarian;
            $status_pembayaran = $request->status_pembayaran;
            $jenis_layanan = $request->jenis_layanan;
            $jenis_transaksi = $request->jenis_transaksi;
            $jenis_laporan = $request->jenis_laporan;
            $tanggal_awal = Carbon::parse($request->tanggal_awal)->startOfDay();
            $tanggal_akhir = Carbon::parse($request->tanggal_akhir)->endOfDay();
            $tablePrefix = config('database.connections.mysql.prefix');
            if ($jenis_laporan == "kuitansi_personal") { 
                $query = TransaksiLab::selectRaw('
                    '.$tablePrefix.'mcu_transaksi_peserta.id AS id_mcu,
                    '.$tablePrefix.'mcu_transaksi_peserta.no_transaksi AS nomor_mcu,
                    '.$tablePrefix.'users_member.nomor_identitas AS nik_peserta,
                    '.$tablePrefix.'users_member.nama_peserta AS nama_peserta,
                    '.$tablePrefix.'mcu_transaksi_peserta.jenis_transaksi_pendaftaran AS jenis_tindakan,
                    '.$tablePrefix.'company.company_name AS nama_perusahaan,
                    '.$tablePrefix.'departemen_peserta.nama_departemen AS nama_departemen,
                    '.$tablePrefix.'transaksi.total_transaksi + '.$tablePrefix.'transaksi.nominal_apotek AS total_transaksi
                ')
                ->join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'transaksi.no_mcu')
                ->join('users_member', 'users_member.id', '=', 'mcu_transaksi_peserta.user_id')
                ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
                ->join('departemen_peserta', 'departemen_peserta.id', '=', 'mcu_transaksi_peserta.departemen_id')
                ->whereBetween('transaksi.created_at', [$tanggal_awal, $tanggal_akhir]);
                if ($jenis_transaksi != ""){
                    $query->where('transaksi.jenis_transaksi', $jenis_transaksi);
                }
                if ($jenis_layanan != ""){
                    $query->where('transaksi.jenis_layanan', $jenis_layanan);
                }
                if ($status_pembayaran != ""){
                    $query->where('transaksi.status_pembayaran', $status_pembayaran);
                }
                $query->orderByRaw($tablePrefix.'transaksi.waktu_trx DESC');
            }else if($jenis_laporan == "kuitansi_perusahaan"){
                $query = TransaksiLab::selectRaw('
                    '.$tablePrefix.'company.id AS id_perusahaan,
                    '.$tablePrefix.'company.company_code AS kode_perusahaan,
                    '.$tablePrefix.'company.company_name AS nama_perusahaan,
                    '.$tablePrefix.'company.alamat AS alamat_perusahaan,
                    SUM('.$tablePrefix.'transaksi.total_transaksi + '.$tablePrefix.'transaksi.nominal_apotek) AS total_transaksi
                ')
                ->join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'transaksi.no_mcu')
                ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
                ->whereBetween('transaksi.created_at', [$tanggal_awal, $tanggal_akhir]);
                if ($jenis_transaksi != ""){
                    $query->where('transaksi.jenis_transaksi', $jenis_transaksi);
                }
                if ($jenis_layanan != ""){
                    $query->where('transaksi.jenis_layanan', $jenis_layanan);
                }
                if ($status_pembayaran != ""){
                    $query->where('transaksi.status_pembayaran', $status_pembayaran);
                }
                $query->groupBy('company.id')->orderByRaw($tablePrefix.'company.company_name ASC');
            }else if($jenis_laporan == "tagihan_perusahaan"){
                $query = TransaksiLab::selectRaw('
                    '.$tablePrefix.'company.id AS id_perusahaan,
                    '.$tablePrefix.'company.company_code AS kode_perusahaan,
                    '.$tablePrefix.'company.company_name AS nama_perusahaan,
                    '.$tablePrefix.'company.alamat AS alamat_perusahaan,
                    SUM('.$tablePrefix.'transaksi.total_transaksi + '.$tablePrefix.'transaksi.nominal_apotek) AS total_transaksi
                ')
                ->join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'transaksi.no_mcu')
                ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
                ->whereBetween('transaksi.created_at', [$tanggal_awal, $tanggal_akhir]);
                if ($jenis_transaksi != ""){
                    $query->where('transaksi.jenis_transaksi', $jenis_transaksi);
                }
                if ($jenis_layanan != ""){
                    $query->where('transaksi.jenis_layanan', $jenis_layanan);
                }
                if ($status_pembayaran != ""){
                    $query->where('transaksi.status_pembayaran', $status_pembayaran);
                }
                $query->groupBy('company.id')->orderByRaw($tablePrefix.'company.company_name ASC');
            }
            $dataSUMGlobal = $query->get();
            $jumlahdata = $query->count();
            $fetchdata = ($perHalaman > 0)
                ? $query->skip($offset)->take($perHalaman)->get()
                : $query->get();
            return response()->json([
                'data' => $fetchdata,
                'data_total' => $dataSUMGlobal,
                'recordsFiltered' => $jumlahdata,
                'pages' => [
                    'limit' => $perHalaman,
                    'offset' => $offset,
                ],
            ]);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
    public function laporan_rekap_perperusahaan(Request $request, string $jenis_laporan_rekap)
    {
        $tablePrefix = config('database.connections.mysql.prefix');
        $allowed = [
            'pemeriksaan_fisik',
            'vital',
            'spirometri',
            'audiometri',
            'ekg',
            'threadmill',
            'rontgen_thorax',
            'rontgen_lumbosacral',
            'usg_ubdomain',
            'farmingham_score',
            'vital_detail',
        ];
        if (!in_array($jenis_laporan_rekap, $allowed)) {
            return response()->json([
                'message' => 'Jenis laporan tidak valid'
            ], 404);
        }
        try {
            $perHalaman = (int) $request->length > 0 ? (int) $request->length : 0;
            $nomorHalaman = ($perHalaman > 0) ? (int) $request->start / $perHalaman : 0;
            $offset = $nomorHalaman * $perHalaman;
            $tanggal_awal = $request->input('tanggal_awal')." 00:00:00";
            $tanggal_akhir = $request->input('tanggal_akhir')." 23:59:59";
            if ($jenis_laporan_rekap === 'pemeriksaan_fisik'){
                $kategoris = ['kepala', 'telinga', 'mata','tenggorokan','mulut','gigi','leher','thorax','abdomen_urogenital','anorectal_genital','ekstremitas','neurologis'];
                $id_perusahaan = $request->input('id_perusahaan');
                $query = null;
                foreach ($kategoris as $kategori) {
                        $tableName = $this->determineTableNamePemeriksaanFisik($kategori);
                        
                        $subQuery = DB::table($tableName)
                            ->join('mcu_transaksi_peserta', $tableName.'.transaksi_id', '=', 'mcu_transaksi_peserta.id')
                            ->join('company', 'mcu_transaksi_peserta.perusahaan_id', '=', 'company.id')
                            ->select(
                                'company.company_name',
                                'company.id as id_perusahaan',
                                DB::raw("'$kategori' as kategori_pemeriksaan"),
                                $tableName.'.nama_atribut',
                                $tableName.'.jenis_atribut',
                                DB::raw("SUM(CASE WHEN ".$tablePrefix.$tableName.".status_atribut = 'normal' THEN 1 ELSE 0 END) as jumlah_normal"),
                                DB::raw("SUM(CASE WHEN ".$tablePrefix.$tableName.".status_atribut = 'abnormal' THEN 1 ELSE 0 END) as jumlah_abnormal")
                            )
                            ->whereBetween($tableName.'.created_at', [$tanggal_awal, $tanggal_akhir]);

                        if ($id_perusahaan != "") {
                            $subQuery->where('company.id', $id_perusahaan);
                        }

                        $subQuery->groupBy('company.id', 'company.company_name', $tableName.'.jenis_atribut');

                        if ($query == null) {
                            $query = $subQuery;
                        } else {
                            $query->unionAll($subQuery);
                        }
                    }
            }else if ($jenis_laporan_rekap === 'vital') {
                $parameter_pencarian = $request->input('parameter_pencarian');
                $dari_perusahaan  = $request->input('dari_perusahaan');
                $query = TandaVital::join('mcu_transaksi_peserta','mcu_pf_tanda_vital.transaksi_id','=','mcu_transaksi_peserta.id')
                    ->join('company','mcu_transaksi_peserta.perusahaan_id','=','company.id')
                    ->select(
                        'company.id as id_perusahaan',
                        'company.company_name',
                        DB::raw('COUNT(DISTINCT '.$tablePrefix.'mcu_transaksi_peserta.id) as total_peserta')
                    )
                    ->whereBetween('mcu_pf_tanda_vital.created_at', [$tanggal_awal, $tanggal_akhir])
                    ->groupBy('company.id', 'company.company_name')->orderBy('company.company_name', 'asc');
            } else if($jenis_laporan_rekap === 'vital_detail') {
                $id_perusahaan = $request->input('id_perusahaan');
                $query = TandaVital::join('mcu_transaksi_peserta','mcu_pf_tanda_vital.transaksi_id','=','mcu_transaksi_peserta.id')
                    ->join('users_member','users_member.id','=','mcu_transaksi_peserta.user_id')
                    ->where('mcu_transaksi_peserta.perusahaan_id', $id_perusahaan)
                    ->whereBetween('mcu_pf_tanda_vital.created_at', [$tanggal_awal, $tanggal_akhir]);
            } else if($jenis_laporan_rekap === 'spirometri') {
                $id_perusahaan = $request->input('id_perusahaan');
                $query = DB::table($this->determineTableNamePoliklinik('spirometri'))
                    ->join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'mcu_poli_spirometri.transaksi_id')
                    ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
                    ->select(
                        'company.company_name',
                        'mcu_transaksi_peserta.perusahaan_id',
                        'mcu_poli_spirometri.kesimpulan', // Kolom Restriksi
                        'mcu_poli_spirometri.kesimpulan2', // Kolom Obstruksi
                        // Menghitung jumlah per kategori Restriksi
                        DB::raw('COUNT(CASE WHEN '.$tablePrefix.'mcu_poli_spirometri.kesimpulan IS NOT NULL THEN 1 END) as jumlah_restriksi'),
                        // Menghitung jumlah per kategori Obstruksi
                        DB::raw('COUNT(CASE WHEN '.$tablePrefix.'mcu_poli_spirometri.kesimpulan2 IS NOT NULL THEN 1 END) as jumlah_obstruksi')
                    )
                    ->whereBetween('mcu_poli_spirometri.created_at', [$tanggal_awal, $tanggal_akhir]);

                if ($id_perusahaan != "") {
                    $query->where('company.id', $id_perusahaan);
                }

                // GroupBy harus menyertakan jenis kesimpulannya agar tidak tergabung jadi satu
                $query->groupBy(
                    'mcu_transaksi_peserta.perusahaan_id', 
                    'mcu_poli_spirometri.kesimpulan', 
                    'mcu_poli_spirometri.kesimpulan2'
                )
                ->orderBy('company.company_name', 'asc');
            } else if($jenis_laporan_rekap === 'audiometri') {
                $id_perusahaan = $request->input('id_perusahaan');
                $query = DB::table($this->determineTableNamePoliklinik('audiometri'))
                    ->join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'mcu_poli_audiometri.transaksi_id')
                    ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
                    ->select(
                        'company.company_name',
                        'mcu_transaksi_peserta.perusahaan_id',
                        'mcu_poli_audiometri.kesimpulan',
                        'mcu_poli_audiometri.kesimpulan2',
                        // Menghitung jumlah per kategori Restriksi
                        DB::raw('COUNT(CASE WHEN '.$tablePrefix.'mcu_poli_audiometri.kesimpulan IS NOT NULL THEN 1 END) as jumlah_kiri'),
                        // Menghitung jumlah per kategori Obstruksi
                        DB::raw('COUNT(CASE WHEN '.$tablePrefix.'mcu_poli_audiometri.kesimpulan2 IS NOT NULL THEN 1 END) as jumlah_kanan')
                    )->whereBetween('mcu_poli_audiometri.created_at', [$tanggal_awal, $tanggal_akhir]);
                if ($id_perusahaan != "") $query->where('company.id', $id_perusahaan);
                // GroupBy harus menyertakan jenis kesimpulannya agar tidak tergabung jadi satu
                $query->groupBy(
                    'mcu_transaksi_peserta.perusahaan_id', 
                    'mcu_poli_audiometri.kesimpulan', 
                    'mcu_poli_audiometri.kesimpulan2'
                )
                ->orderBy('company.company_name', 'asc');
            } else if($jenis_laporan_rekap === 'ekg') {
                $id_perusahaan = $request->input('id_perusahaan');
                $query = DB::table($this->determineTableNamePoliklinik('ekg'))
                    ->join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'mcu_poli_ekg.transaksi_id')
                    ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
                    ->select(
                        'company.company_name',
                        'mcu_transaksi_peserta.perusahaan_id',
                        'mcu_poli_ekg.kesimpulan',
                        DB::raw('COUNT('.$tablePrefix.'mcu_transaksi_peserta.id) as total_kesimpulan')
                    )->whereBetween('mcu_poli_ekg.created_at', [$tanggal_awal, $tanggal_akhir]);
                if ($id_perusahaan != "") $query->where('company.id', $id_perusahaan);
                $query->groupBy('mcu_transaksi_peserta.perusahaan_id')->orderBy('company.company_name', 'asc');
            } else if($jenis_laporan_rekap === 'threadmill') {
                $id_perusahaan = $request->input('id_perusahaan');
                $query = DB::table($this->determineTableNamePoliklinik('threadmill'))
                    ->join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'mcu_poli_threadmill.transaksi_id')
                    ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
                    ->select(
                        'company.company_name',
                        'mcu_transaksi_peserta.perusahaan_id',
                        'mcu_poli_threadmill.kesimpulan',
                        DB::raw('COUNT('.$tablePrefix.'mcu_transaksi_peserta.id) as total_kesimpulan')
                    )->whereBetween('mcu_poli_threadmill.created_at', [$tanggal_awal, $tanggal_akhir]);
                if ($id_perusahaan != "") $query->where('company.id', $id_perusahaan);
                $query->groupBy('mcu_transaksi_peserta.perusahaan_id')->orderBy('company.company_name', 'asc');
            } else if($jenis_laporan_rekap === 'rontgen_thorax') {
                $id_perusahaan = $request->input('id_perusahaan');
                $query = DB::table($this->determineTableNamePoliklinik('rontgen_thorax'))
                    ->join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'mcu_poli_rontgen_thorax.transaksi_id')
                    ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
                    ->select(
                        'company.company_name',
                        'mcu_transaksi_peserta.perusahaan_id',
                        'mcu_poli_rontgen_thorax.kesimpulan',
                        DB::raw('COUNT('.$tablePrefix.'mcu_transaksi_peserta.id) as total_kesimpulan')
                    )->whereBetween('mcu_poli_rontgen_thorax.created_at', [$tanggal_awal, $tanggal_akhir]);
                if ($id_perusahaan != "") $query->where('company.id', $id_perusahaan);
                $query->groupBy('mcu_transaksi_peserta.perusahaan_id')->orderBy('company.company_name', 'asc');
            } else if($jenis_laporan_rekap === 'rontgen_lumbosacral') {
                $id_perusahaan = $request->input('id_perusahaan');
                $query = DB::table($this->determineTableNamePoliklinik('rontgen_lumbosacral'))
                    ->join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'mcu_poli_rontgen_lumbosacral.transaksi_id')
                    ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
                    ->select(
                        'company.company_name',
                        'mcu_transaksi_peserta.perusahaan_id',
                        'mcu_poli_rontgen_lumbosacral.kesimpulan',
                        DB::raw('COUNT('.$tablePrefix.'mcu_transaksi_peserta.id) as total_kesimpulan')
                    )->whereBetween('mcu_poli_rontgen_lumbosacral.created_at', [$tanggal_awal, $tanggal_akhir]);
                if ($id_perusahaan != "") $query->where('company.id', $id_perusahaan);
                $query->groupBy('mcu_transaksi_peserta.perusahaan_id')->orderBy('company.company_name', 'asc');
            } else if($jenis_laporan_rekap === 'usg_ubdomain') {
                $id_perusahaan = $request->input('id_perusahaan');
                $query = DB::table($this->determineTableNamePoliklinik('usg_ubdomain'))
                    ->join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'mcu_poli_usg_ubdomain.transaksi_id')
                    ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
                    ->select(
                        'company.company_name',
                        'mcu_transaksi_peserta.perusahaan_id',
                        'mcu_poli_usg_ubdomain.kesimpulan',
                        DB::raw('COUNT('.$tablePrefix.'mcu_transaksi_peserta.id) as total_kesimpulan')
                    )->whereBetween('mcu_poli_usg_ubdomain.created_at', [$tanggal_awal, $tanggal_akhir]);
                if ($id_perusahaan != "") $query->where('company.id', $id_perusahaan);
                $query->groupBy('mcu_transaksi_peserta.perusahaan_id')->orderBy('company.company_name', 'asc');
            } else if($jenis_laporan_rekap === 'farmingham_score') {
                $id_perusahaan = $request->input('id_perusahaan');
                $query = DB::table($this->determineTableNamePoliklinik('farmingham_score'))
                    ->join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'mcu_poli_farmingham_score.transaksi_id')
                    ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
                    ->select(
                        'company.company_name',
                        'mcu_transaksi_peserta.perusahaan_id',
                        'mcu_poli_farmingham_score.kesimpulan',
                        DB::raw('COUNT('.$tablePrefix.'mcu_transaksi_peserta.id) as total_kesimpulan')
                    )->whereBetween('mcu_poli_farmingham_score.created_at', [$tanggal_awal, $tanggal_akhir]);
                if ($id_perusahaan != "") $query->where('company.id', $id_perusahaan);
                $query->groupBy('mcu_transaksi_peserta.perusahaan_id')->orderBy('company.company_name', 'asc');
            }
            $dataSUMGlobal = $query->get();
            $jumlahdata = $query->count();
            $fetchdata = ($perHalaman > 0)
                ? $query->skip($offset)->take($perHalaman)->get()
                : $query->get();
            return response()->json([
                'data' => $fetchdata,
                'data_total' => $dataSUMGlobal,
                'recordsFiltered' => $jumlahdata,
                'pages' => [
                    'limit' => $perHalaman,
                    'offset' => $offset,
                ],
            ]);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }

}
