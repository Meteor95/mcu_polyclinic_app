<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{AuthController, RoleAndPermissionController, UserController, MasterdataController, PendaftaranController, TransaksiController, FileController, AtributController, PemeriksaanFisikController};

Route::get('/', function(){return ResponseHelper::error(401);})->name('login');
Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('pintupendaftaran', [AuthController::class,"register"]);
        Route::post('pintumasuk', [AuthController::class,"login"]);
        Route::post('buattokenbaru', [AuthController::class,"refreshToken"]);
        Route::post('keluar', [AuthController::class,"logout"]);
        Route::post('tambahakses', [RoleAndPermissionController::class,"addpermission"]);
    });
    Route::prefix('file')->group(function () {
        Route::get('unduh_foto', [FileController::class, "download_foto"]);
    });
    Route::middleware(['jwt.auth', 'jwt.cookie'])->group(function () {  
        Route::prefix('pengguna')->group(function () {
            Route::post('tambahpengguna', [UserController::class,"adduser"]);
            Route::get('daftarpengguna', [UserController::class,"getuser"]);
            Route::get('hapuspengguna', [UserController::class,"deleteuser"]);
            Route::get('detailpengguna', [UserController::class,"detailuser"]);
            Route::post('editpengguna', [UserController::class,"edituser"]);
        });
        Route::prefix('permission')->group(function () {
            Route::post('tambahhakakses', [RoleAndPermissionController::class,"addpermission"]);
            Route::get('daftarhakakses', [RoleAndPermissionController::class,"getpermission"]);
            Route::get('hapushakakses', [RoleAndPermissionController::class,"deletepermission"]);
            Route::post('edithakakses', [RoleAndPermissionController::class,"editpermission"]);
        });
        Route::prefix('role')->group(function () {
            Route::post('tambahrole', [RoleAndPermissionController::class,"addrole"]);
            Route::get('daftarrole', [RoleAndPermissionController::class,"getrole"]);
            Route::get('hapusrole', [RoleAndPermissionController::class,"deleterole"]);
            Route::get('detailrole', [RoleAndPermissionController::class,"detailrole"]);
            Route::post('editrole', [RoleAndPermissionController::class,"editrole"]);
        });
        Route::prefix('komponen')->group(function () {
            Route::get('daftarpoli', [MasterdataController::class,"getpoli"]);
        });
        Route::prefix('pendaftaran')->group(function () {
            Route::get('daftarpeserta', [PendaftaranController::class,"getpeserta"]);
            Route::get('getdatapasien', [TransaksiController::class,"getdatapasien"]);
            Route::get('hapuspeserta', [PendaftaranController::class,"deletepeserta"]);
            Route::get('getdatapeserta', [PendaftaranController::class,"getdatapeserta"]);
            Route::get('daftarpasien', [TransaksiController::class,"getpasien"]);
            /* Daftar Pasien Unggah Citra */
            Route::get('daftarpasien_unggah_citra', [TransaksiController::class,"getpasien_unggah_citra"]);
            Route::post('upload_citra_peserta',[TransaksiController::class,"upload_images_mcu"]);
            Route::get('hapusunduhan_citra_peserta', [TransaksiController::class,"hapusunduhan_citra_peserta"]);
            /* Riwayat Lingkungan Kerja */
            Route::post('simpanriwayatlingkungankerja', [PendaftaranController::class,"simpanriwayatlingkungankerja"]);
            Route::get('riwayatlingkungankerja', [PendaftaranController::class,"riwayatlingkungankerja"]);
            Route::get('daftarpasien_riwayatlingkungankerja', [PendaftaranController::class,"getpasien_riwayatlingkungankerja"]);
            Route::get('hapusriwayatlingkungankerja', [PendaftaranController::class,"hapusriwayatlingkungankerja"]);
            /* Riwayat Kecelakaan Kerja */
            Route::post('simpanriwayatkecelakaankerja', [PendaftaranController::class,"simpanriwayatkecelakaankerja"]);
            Route::get('daftarpasien_riwayatkecelakaankerja', [PendaftaranController::class,"getpasien_riwayatkecelakaankerja"]);
            Route::get('riwayatkecelakaankerja', [PendaftaranController::class,"riwayatkecelakaankerja"]);
            Route::delete('hapusriwayatkecelakaankerja', [PendaftaranController::class,"hapusriwayatkecelakaankerja"]);
            /* Riwayat Kebiasaan Hidup */
            Route::post('simpankebiasaanhidup', [PendaftaranController::class,"simpankebiasaanhidup"]);
            Route::get('daftarpasien_riwayatkebiasaanhidup', [PendaftaranController::class,"getpasien_riwayatkebiasaanhidup"]);
            Route::delete('hapusriwayatkebiasaanhidup', [PendaftaranController::class,"hapusriwayatkebiasaanhidup"]);
            Route::get('riwayatkebiasaanhidup', [PendaftaranController::class,"riwayatkebiasaanhidup"]);
            /* Riwayat Penyakit Terdahulu */
            Route::post('simpanriwayatpenyakitterdahulu', [PendaftaranController::class,"simpanriwayatpenyakitterdahulu"]);
            Route::get('daftarpasien_riwayatpenyakitterdahulu', [PendaftaranController::class,"getpasien_riwayatpenyakitterdahulu"]);
            Route::get('riwayatpenyakitterdahulu', [PendaftaranController::class,"riwayatpenyakitterdahulu"]);
            Route::delete('hapusriwayatpenyakitterdahulu', [PendaftaranController::class,"hapusriwayatpenyakitterdahulu"]);
            /* Riwayat Penyakit Keluarga */
            Route::post('simpanriwayatpenyakitkeluarga', [PendaftaranController::class,"simpanriwayatpenyakitkeluarga"]);
            Route::get('daftarpasien_riwayatpenyakitkeluarga', [PendaftaranController::class,"getpasien_riwayatpenyakitkeluarga"]);
            Route::get('riwayatpenyakitkeluarga', [PendaftaranController::class,"riwayatpenyakitkeluarga"]);
            Route::delete('hapusriwayatpenyakitkeluarga', [PendaftaranController::class,"hapusriwayatpenyakitkeluarga"]);
            /* Riwayat Imunisasi */
            Route::post('simpanimunisasi', [PendaftaranController::class,"simpanimunisasi"]);
            Route::get('daftarpasien_imunisasi', [PendaftaranController::class,"getpasien_imunisasi"]);
            Route::get('imunisasi', [PendaftaranController::class,"imunisasi"]);
            Route::delete('hapusimunisasi', [PendaftaranController::class,"hapusimunisasi"]);
        });
        Route::prefix('pemeriksaan_fisik')->group(function () {
            /* Tingkat Kesadaran */
            Route::post('simpantingkatkesadaran', [PemeriksaanFisikController::class,"simpantingkatkesadaran"]);
            Route::get('daftar_tingkat_kesadaran', [PemeriksaanFisikController::class,"gettingkatkesadaran"]);
            Route::delete('hapus_tingkat_kesadaran', [PemeriksaanFisikController::class,"hapus_tingkat_kesadaran"]);
            Route::get('get_tingkat_kesadaran', [PemeriksaanFisikController::class,"get_tingkat_kesadaran"]);
        });
        Route::prefix('masterdata')->group(function () {
            /* Master Data Perusahaan */
            Route::get('daftarperusahaan', [MasterdataController::class,"getperusahaan"]);
            Route::post('simpanperusahaan', [MasterdataController::class,"saveperusahaan"]);
            Route::get('hapusperusahaan', [MasterdataController::class,"deleteperusahaan"]);
            Route::post('ubahperusahaan', [MasterdataController::class,"editperusahaan"]);
            /* Master Data Paket MCU */
            Route::get('daftarpaketmcu', [MasterdataController::class,"getpaketmcu"]);
            Route::post('simpanpaketmcu', [MasterdataController::class,"savepaketmcu"]);
            Route::get('hapuspaketmcu', [MasterdataController::class,"deletepaketmcu"]);
            Route::post('ubahpaketmcu', [MasterdataController::class,"editpaketmcu"]);
            /* Master Data Jasa Pelayanan */
            Route::get('daftarjasa', [MasterdataController::class,"getjasa"]);
            Route::post('simpanjasa', [MasterdataController::class,"savejasa"]);
            Route::get('hapusjasa', [MasterdataController::class,"deletejasa"]);
            Route::post('ubahjasa', [MasterdataController::class,"editjasa"]);
            /* Master Data Departemen Peserta */
            Route::get('daftardepartemenpeserta', [MasterdataController::class,"getdepartemenpeserta"]);
            Route::post('simpandepartemenpeserta', [MasterdataController::class,"savedepartemenpeserta"]);
            Route::get('hapusdepartemenpeserta', [MasterdataController::class,"deletedepartemenpeserta"]);
            Route::post('ubahdepartemenpeserta', [MasterdataController::class,"editdepartemenpeserta"]);
            /* Master Data Member MCU */
            Route::get('daftarmembermcu', [MasterdataController::class,"getmembermcu"]);
            Route::post('simpanmembermcu', [MasterdataController::class,"savemembermcu"]);
            Route::get('hapusmembermcu', [MasterdataController::class,"deletemembermcu"]);
            Route::post('ubahmembermcu', [MasterdataController::class,"editmembermcu"]);
            /* Master Data Bank Penerima */
            Route::get('daftarbank', [MasterdataController::class,"getbank"]);
            Route::post('simpanbank', [MasterdataController::class,"savebank"]);
            Route::get('hapusbank', [MasterdataController::class,"deletebank"]);
            Route::post('ubahbank', [MasterdataController::class,"editbank"]);
        });
        Route::prefix('atribut')->group(function () {
            Route::get('lingkungankerja', [AtributController::class,"getlingkungankerja"]);
        });
        Route::prefix('transaksi')->group(function () {
            Route::post('simpanpeserta', [TransaksiController::class,"savepeserta"]);
            Route::get('hapuspeserta', [TransaksiController::class,"deletepeserta"]);
        });
    });
});
