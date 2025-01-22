<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi\{Transaksi, UnggahCitra, LingkunganKerjaPeserta, RiwayatKecelakaanKerja, RiwayatKebiasaanHidup, RiwayatPenyakitTerdahulu, RiwayatPenyakitKeluarga, RiwayatImunisasi};
use App\Models\PemeriksaanFisik\{TingkatKesadaran, TandaVital, Penglihatan};
use App\Models\PemeriksaanFisik\KondisiFisik\{KondisiFisik, Gigi};
use App\Models\Laboratorium\Transaksi as TransaksiLab;
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
            'ronsen' => 'mcu_poli_ronsen',
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
                ->select('users_member.*','transaksi.total_transaksi','departemen_peserta.nama_departemen','company.company_name', 'transaksi.waktu_trx', 'users_member.tempat_lahir', 'users_member.tanggal_lahir', 'transaksi.*', 'mcu_transaksi_peserta.id as transaksi_id')
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
            $jumlah_data_ronsen = DB::table($this->determineTableNamePoliklinik('ronsen'))->where('transaksi_id', $transaksi_id->transaksi_id)->count();
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
                'jumlah_data_ronsen' => $jumlah_data_ronsen,
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
            if ($req->kondisi == 'sp' || $req->kondisi == 'ekg' || $req->kondisi == 'tm' || $req->kondisi == 'rsn' || $req->kondisi == 'au') {
                if ($req->kondisi == 'sp') {
                    $table = $this->determineTableNamePoliklinik('spirometri');
                }
                if ($req->kondisi == 'ekg') {
                    $table = $this->determineTableNamePoliklinik('ekg');
                }
                if ($req->kondisi == 'tm') {
                    $table = $this->determineTableNamePoliklinik('threadmill');
                }
                if ($req->kondisi == 'rsn') {
                    $table = $this->determineTableNamePoliklinik('ronsen');
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
}
