<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\{AuthController, BerandaController, HakaksesController, MasterdataController, FileController, PendaftaranController, ProfileController, PemeriksaanFisikController, LaboratoriumController, PoliklinikController, LaporanController};
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
        Route::get('beranda', [BerandaController::class,"index"])->middleware('permission_cache:akses_beranda')->name('admin.beranda');
        Route::get('kasir', [BerandaController::class,"kasir"])->middleware('permission_cache:akses_kasir')->name('admin.kasir');
        Route::get('pengguna_aplikasi', [HakaksesController::class,"pengguna_aplikasi"])->middleware('permission_cache:akses_petugas')->name('admin.pengguna_aplikasi');
        Route::get('permission', [HakaksesController::class,"permission"])->middleware('permission_cache:akses_hak_permission')->name('admin.permission');
        Route::get('role', [HakaksesController::class,"role"])->middleware('permission_cache:akses_petugas')->name('admin.role');
    });
    Route::prefix('pendaftaran')->group(function () {
        Route::get('daftar_peserta', [PendaftaranController::class,"list_peserta"])->middleware('permission_cache:akses_pendaftaran_daftar_peserta')->name('admin.pendaftaran.daftar_peserta');
        Route::get('daftar_pasien', [PendaftaranController::class,"list_pasien"])->middleware('permission_cache:akses_pendaftaran_daftar_pasien')->name('admin.pendaftaran.daftar_pasien');
        Route::get('formulir_tambah_peserta/{uuid?}', [PendaftaranController::class,"add_form_patien_mcu"])->middleware('permission_cache:akses_pendaftaran_daftar_peserta')->name('admin.pendaftaran.formulir_tambah_peserta');
        Route::get('formulir_ubah_peserta/{uuid?}', [PendaftaranController::class,"update_form_patien_mcu"])->middleware('permission_cache:akses_pendaftaran_daftar_peserta')->name('admin.pendaftaran.formulir_ubah_peserta');
        /* Riwayat Informasi */
        Route::get('foto_pasien', [PendaftaranController::class,"foto_pasien"])->middleware('permission_cache:akses_pendaftaran_foto_pasien')->name('admin.pendaftaran.foto_pasien');
        Route::get('lingkungan_kerja', [PendaftaranController::class,"lingkungan_kerja"])->middleware('permission_cache:akses_pendaftaran_lingkungan_kerja')->name('admin.pendaftaran.lingkungan_kerja');
        Route::get('kecelakaan_kerja', [PendaftaranController::class,"kecelakaan_kerja"])->middleware('permission_cache:akses_pendaftaran_kecelakaan_kerja')->name('admin.pendaftaran.kecelakaan_kerja');
        Route::get('kebiasaan_hidup', [PendaftaranController::class,"kebiasaan_hidup"])->middleware('permission_cache:akses_pendaftaran_kebiasaan_hidup')->name('admin.pendaftaran.kebiasaan_hidup');
        Route::get('penyakit_terdahulu', [PendaftaranController::class,"penyakit_terdahulu"])->middleware('permission_cache:akses_pendaftaran_penyakit_terdahulu')->name('admin.pendaftaran.penyakit_terdahulu');
        Route::get('penyakit_keluarga', [PendaftaranController::class,"penyakit_keluarga"])->middleware('permission_cache:akses_pendaftaran_penyakit_keluarga')->name('admin.pendaftaran.penyakit_keluarga');
        Route::get('imunisasi', [PendaftaranController::class,"imunisasi"])->middleware('permission_cache:akses_pendaftaran_imunisasi')->name('admin.pendaftaran.imunisasi');
    });
    Route::prefix('pemeriksaan_fisik')->group(function () {
        Route::get('tingkat_kesadaran', [PemeriksaanFisikController::class,"tingkat_kesadaran"])->middleware('permission_cache:akses_pemeriksaan_fisik_tingkat_kesadaran')->name('admin.pemeriksaan_fisik.tingkat_kesadaran');
        Route::get('tanda_vital', [PemeriksaanFisikController::class,"tanda_vital"])->middleware('permission_cache:akses_pemeriksaan_fisik_tanda_vital')->name('admin.pemeriksaan_fisik.tanda_vital');
        Route::get('penglihatan', [PemeriksaanFisikController::class,"penglihatan"])->middleware('permission_cache:akses_pemeriksaan_fisik_penglihatan')->name('admin.pemeriksaan_fisik.penglihatan');
        Route::get('kondisi_fisik/{lokasi_fisik}', [PemeriksaanFisikController::class,"kondisi_fisik"])->middleware('permission_cache:akses_pemeriksaan_fisik_kondisi_fisik')->name('admin.pemeriksaan_fisik.kondisi_fisik');
    });
    Route::get('poli/{jenis_poli}', [PoliklinikController::class,"poliklinik"])->middleware('permission_cache:akses_poliklinik,akses_spirometri,akses_audiometri,akses_ekg,akses_threadmill,akses_ronsen')->name('admin.poliklinik');
    Route::prefix('masterdata')->middleware('permission_cache:akses_master_data')->group(function () {
        Route::get('daftar_perusahaan', [MasterdataController::class,"daftar_perusahaan"])->middleware('permission_cache:akses_master_perusahaan')->name('admin.masterdata.daftar_perusahaan');
        Route::get('daftar_paket_mcu', [MasterdataController::class,"daftar_paket_mcu"])->middleware('permission_cache:akses_paket_harga')->name('admin.masterdata.daftar_paket_mcu');
        Route::get('daftar_jasa_pelayanan', [MasterdataController::class,"daftar_jasa_pelayanan"])->middleware('permission_cache:akses_jasa_pelayanan')->name('admin.masterdata.daftar_jasa_pelayanan');
        Route::get('daftar_departemen_peserta', [MasterdataController::class,"daftar_departemen_peserta"])->middleware('permission_cache:akses_departemen_peserta')->name('admin.masterdata.daftar_departemen_peserta');
        Route::get('daftar_member_mcu', [MasterdataController::class,"daftar_member_mcu"])->middleware('permission_cache:akses_member_mcu')->name('admin.masterdata.daftar_member_mcu');
        Route::get('daftar_bank', [MasterdataController::class,"daftar_bank"])->middleware('permission_cache:akses_daftar_bank')->name('admin.masterdata.daftar_bank');
    });
    Route::prefix('laboratorium')->group(function () {
        Route::get('tarif', [LaboratoriumController::class,"tarif"])->middleware('permission_cache:akses_tarif_laboratorium')->name('admin.laboratorium.tarif');
        Route::get('kategori', [LaboratoriumController::class,"kategori"])->middleware('permission_cache:akses_kategori_laboratorium')->name('admin.laboratorium.kategori');
        Route::get('satuan', [LaboratoriumController::class,"satuan"])->middleware('permission_cache:akses_satuan_laboratorium')->name('admin.laboratorium.satuan');
        Route::get('rentang_kenormalan', [LaboratoriumController::class,"rentang_kenormalan"])->middleware('permission_cache:akses_rentang_kenormalan_laboratorium')->name('admin.laboratorium.rentang_kenormalan');
        Route::get('templating', [LaboratoriumController::class,"templating"])->middleware('permission_cache:akses_templating_laboratorium')->name('admin.laboratorium.templating');
        /* tindakan laboratorium */
        Route::get('daftar_tindakan', [LaboratoriumController::class,"daftar_tindakan"])->middleware('permission_cache:akses_tindakan_laboratorium')->name('admin.laboratorium.daftar_tindakan');
        Route::get('tindakan', [LaboratoriumController::class,"tindakan"])->middleware('permission_cache:akses_tindakan_laboratorium')->name('admin.laboratorium.tindakan');
    });
    Route::prefix('image')->group(function () {
        Route::get('user/signature/{filename}', [FileController::class, 'showSignature']);
    });
    Route::prefix('laporan')->group(function () {
        Route::get('validasi_mcu', [LaporanController::class,"validasi_mcu"])->middleware('permission_cache:akses_validasi_mcu')->name('admin.laporan.validasi_mcu');
        Route::get('validasi_mcu/nota/{no_nota}', [LaporanController::class,"validasi_mcu_nota"])->middleware('permission_cache:akses_validasi_mcu')->name('admin.laporan.validasi_mcu_nota');
        Route::get('validasi_rekap_kesimpulan', [LaporanController::class,"validasi_rekap_kesimpulan"])->middleware('permission_cache:akses_validasi_mcu')->name('admin.laporan.validasi_rekap_kesimpulan');
    });
});
Route::prefix('landing')->group(function () {
    Route::get('formulir', [PendaftaranController::class,"formulir_pendaftaran"])->name('landing.formulir_pendaftaran');
});

