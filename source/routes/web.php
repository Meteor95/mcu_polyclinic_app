<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\{AuthController, BerandaController, HakaksesController, MasterdataController, FileController, PendaftaranController, ProfileController, PemeriksaanFisikController, LaboratoriumController, PoliklinikController};
use Illuminate\Http\Request;

Route::get('generate-csrf-token', function () { $token = csrf_token(); return response()->json(['csrf_token' => $token]); });
Route::get('/', function (Request $req) { $data = [ 'tipe_halaman' => 'login']; return view('login', ['data' => $data]); })->name('login');
Route::get('403', function () { return view('error.403_error'); });
Route::group(['middleware' => ['jwt.cookie']], function () {
    Route::get('pintukeluar', [AuthController::class, "logout"]);
    Route::prefix('akun')->group(function () {
        Route::get('profile', [ProfileController::class,"profile"])->name('admin.akun.profile');
    });
    Route::prefix('admin')->group(function () {
        Route::get('beranda', [BerandaController::class,"index"])->name('admin.beranda');
        Route::get('pengguna_aplikasi', [HakaksesController::class,"pengguna_aplikasi"])->name('admin.pengguna_aplikasi');
        Route::get('permission', [HakaksesController::class,"permission"])->name('admin.permission');
        Route::get('role', [HakaksesController::class,"role"])->name('admin.role');
    });
    Route::prefix('pendaftaran')->group(function () {
        Route::get('daftar_peserta', [PendaftaranController::class,"list_peserta"])->name('admin.pendaftaran.daftar_peserta');
        Route::get('daftar_pasien', [PendaftaranController::class,"list_pasien"])->name('admin.pendaftaran.daftar_pasien');
        Route::get('formulir_tambah_peserta/{uuid?}', [PendaftaranController::class,"add_form_patien_mcu"])->name('admin.pendaftaran.formulir_tambah_peserta');
        Route::get('formulir_ubah_peserta/{uuid?}', [PendaftaranController::class,"update_form_patien_mcu"])->name('admin.pendaftaran.formulir_ubah_peserta');
        /* Riwayat Informasi */
        Route::get('foto_pasien', [PendaftaranController::class,"foto_pasien"])->name('admin.pendaftaran.foto_pasien');
        Route::get('lingkungan_kerja', [PendaftaranController::class,"lingkungan_kerja"])->name('admin.pendaftaran.lingkungan_kerja');
        Route::get('kecelakaan_kerja', [PendaftaranController::class,"kecelakaan_kerja"])->name('admin.pendaftaran.kecelakaan_kerja');
        Route::get('kebiasaan_hidup', [PendaftaranController::class,"kebiasaan_hidup"])->name('admin.pendaftaran.kebiasaan_hidup');
        Route::get('penyakit_terdahulu', [PendaftaranController::class,"penyakit_terdahulu"])->name('admin.pendaftaran.penyakit_terdahulu');
        Route::get('penyakit_keluarga', [PendaftaranController::class,"penyakit_keluarga"])->name('admin.pendaftaran.penyakit_keluarga');
        Route::get('imunisasi', [PendaftaranController::class,"imunisasi"])->name('admin.pendaftaran.imunisasi');
    });
    Route::prefix('pemeriksaan_fisik')->group(function () {
        Route::get('tingkat_kesadaran', [PemeriksaanFisikController::class,"tingkat_kesadaran"])->name('admin.pemeriksaan_fisik.tingkat_kesadaran');
        Route::get('tanda_vital', [PemeriksaanFisikController::class,"tanda_vital"])->name('admin.pemeriksaan_fisik.tanda_vital');
        Route::get('penglihatan', [PemeriksaanFisikController::class,"penglihatan"])->name('admin.pemeriksaan_fisik.penglihatan');
        Route::get('kondisi_fisik/{lokasi_fisik}', [PemeriksaanFisikController::class,"kondisi_fisik"])->name('admin.pemeriksaan_fisik.kondisi_fisik');
    });
    Route::prefix('poliklinik')->group(function () {
        Route::get('spirometri', [PoliklinikController::class,"spirometri"])->name('admin.poliklinik.spirometri');
        Route::get('audiometri', [PoliklinikController::class,"audiometri"])->name('admin.poliklinik.audiometri');
        Route::get('ekg', [PoliklinikController::class,"ekg"])->name('admin.poliklinik.ekg');
        Route::get('threadmill', [PoliklinikController::class,"threadmill"])->name('admin.poliklinik.threadmill');
        Route::get('ronsen', [PoliklinikController::class,"ronsen"])->name('admin.poliklinik.ronsen');
    });
    Route::prefix('masterdata')->group(function () {
        Route::get('daftar_perusahaan', [MasterdataController::class,"daftar_perusahaan"])->name('admin.masterdata.daftar_perusahaan');
        Route::get('daftar_paket_mcu', [MasterdataController::class,"daftar_paket_mcu"])->name('admin.masterdata.daftar_paket_mcu');
        Route::get('daftar_jasa_pelayanan', [MasterdataController::class,"daftar_jasa_pelayanan"])->name('admin.masterdata.daftar_jasa_pelayanan');
        Route::get('daftar_departemen_peserta', [MasterdataController::class,"daftar_departemen_peserta"])->name('admin.masterdata.daftar_departemen_peserta');
        Route::get('daftar_member_mcu', [MasterdataController::class,"daftar_member_mcu"])->name('admin.masterdata.daftar_member_mcu');
        Route::get('daftar_bank', [MasterdataController::class,"daftar_bank"])->name('admin.masterdata.daftar_bank');
    });
    Route::prefix('laboratorium')->group(function () {
        Route::get('kategori', [LaboratoriumController::class,"kategori"])->name('admin.laboratorium.kategori');
        Route::get('satuan', [LaboratoriumController::class,"satuan"])->name('admin.laboratorium.satuan');
        Route::get('tindakan', [LaboratoriumController::class,"tindakan"])->name('admin.laboratorium.tindakan');
        Route::get('template', [LaboratoriumController::class,"template"])->name('admin.laboratorium.template');
        Route::get('rentang_kenormalan', [LaboratoriumController::class,"rentang_kenormalan"])->name('admin.laboratorium.rentang_kenormalan');
        Route::get('transaksi', [LaboratoriumController::class,"transaksi"])->name('admin.laboratorium.transaksi');
    });
    Route::prefix('image')->group(function () {
        Route::get('user/signature/{filename}', [FileController::class, 'showSignature']);
    });
});
Route::prefix('landing')->group(function () {
    Route::get('formulir', [PendaftaranController::class,"formulir_pendaftaran"])->name('landing.formulir_pendaftaran');
});

