<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi\{Transaksi, UnggahCitra, LingkunganKerjaPeserta, RiwayatKecelakaanKerja, RiwayatKebiasaanHidup, RiwayatPenyakitTerdahulu, RiwayatPenyakitKeluarga, RiwayatImunisasi};
use App\Models\PemeriksaanFisik\{TingkatKesadaran, TandaVital, Penglihatan};
use App\Models\PemeriksaanFisik\KondisiFisik\{KondisiFisik, Gigi};
use App\Models\Laboratorium\{Transaksi as TransaksiLab, Kategori, TransaksiDetail, Kesimpulan as KesimpulanLabStatus};
use App\Models\Laporan\Kesimpulan;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\{Log, DB};

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
            $informasi_user = TransaksiLab::join('mcu_transaksi_peserta', 'mcu_transaksi_peserta.id', '=', 'transaksi.no_mcu')
                ->join('users_member', 'users_member.id', '=', 'mcu_transaksi_peserta.user_id')
                ->join('company', 'company.id', '=', 'mcu_transaksi_peserta.perusahaan_id')
                ->join('departemen_peserta', 'departemen_peserta.id', '=', 'mcu_transaksi_peserta.departemen_id')
                ->select('users_member.*','transaksi.total_transaksi','departemen_peserta.nama_departemen','company.company_name', 'transaksi.waktu_trx', 'users_member.tempat_lahir', 'users_member.tanggal_lahir', 'transaksi.*', 'mcu_transaksi_peserta.id as transaksi_id','mcu_transaksi_peserta.status_peserta')
                ->selectRaw('
                    COUNT(' . $tablePrefix . 'transaksi.no_mcu) as kedatangan, 
                    MAX(' . $tablePrefix . 'transaksi.created_at) as terakhir_datang, 
                    SUM(' . $tablePrefix . 'transaksi.total_transaksi) as valuasi, 
                    TIMESTAMPDIFF(YEAR, ' . $tablePrefix . 'users_member.tanggal_lahir, CURDATE()) as umur
                ')
                ->where('transaksi.no_mcu', $transaksi_id->transaksi_id)
                ->groupBy('transaksi.no_mcu')
                ->limit(1)
                ->orderBy('terakhir_datang', 'desc')
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
            $dynamicAttributes = [];
            return ResponseHelper::success('Validasi atas nomor dokumen '.$no_nota.' berhasil diubah menjadi '.$req->status_text, $dynamicAttributes);
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
            ];
            if ($is_mcu_exist) {
                Kesimpulan::where('id_mcu', $req->id_mcu_let)->update($data);
            } else {
                Kesimpulan::create($data);
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
            $kesimpulan_tindakan = KesimpulanLabStatus::where('id', $informasi_mcu->kesimpulan_keseluruhan)->first();
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
                ->select('users_member.nama_peserta', 'users_member.nomor_identitas', 'users_member.tempat_lahir', 'users_member.tanggal_lahir', 'users_member.jenis_kelamin', 'users_member.alamat', 'company.company_name', 'departemen_peserta.nama_departemen', 'mcu_transaksi_peserta.tanggal_transaksi as tanggal_mcu', 'mcu_transaksi_peserta.jenis_transaksi_pendaftaran')
                ->selectRaw('TIMESTAMPDIFF(YEAR, ' . $tablePrefix . 'users_member.tanggal_lahir, CURDATE()) AS umur')
                ->where('mcu_transaksi_peserta.id', $req->id_mcu)->first();
            $riwayat_informasi_foto->data_foto = url(env('APP_VERSI_API')."/file/unduh_foto?file_name=" . $riwayat_informasi_foto->lokasi_gambar);
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
                'laboratorium' => $laboratorium
            ];
            return ResponseHelper::data('Informasi MCU', $dynamicAttributes);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
}
