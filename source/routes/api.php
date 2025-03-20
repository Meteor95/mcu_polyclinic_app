<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{AuthController, RoleAndPermissionController, UserController, MasterdataController, PendaftaranController, TransaksiController, FileController, AtributController, PemeriksaanFisikController, PoliklinikController, LaboratoriumController, LaporanController, DeveloperController};
use App\Http\Controllers\Api\EndUser\FormulirController;

Route::get('/', function(){return ResponseHelper::error(401);})->name('login');
Route::prefix('v1')->group(function () {
    Route::prefix('developer')->group(function () {
        Route::get('error_log_app', [DeveloperController::class, "error_log_app"]);
        Route::delete('error_log_app/{id}', [DeveloperController::class, "hapus_error_log_app"]);
    });
    Route::prefix('auth')->group(function () {
        Route::post('pintupendaftaran', [AuthController::class,"register"]);
        Route::post('pintumasuk', [AuthController::class,"login"]);
        Route::post('buattokenbaru', [AuthController::class,"refreshToken"]);
        Route::post('keluar', [AuthController::class,"logout"]);
        Route::post('tambahakses', [RoleAndPermissionController::class,"addpermission"]);
    });
    Route::prefix('file')->group(function () {
        Route::get('unduh_foto', [FileController::class, "download_foto"]);
        Route::get('unduh_citra_poliklinik', [FileController::class, "downlad_citra_poliklinik"]);
        Route::get('unduh_surat_pengantar', [FileController::class, "download_surat_pengantar"]);
        Route::get('unduh_berkas_apotek', [FileController::class, "download_berkas_apotek"]);
    });
    Route::middleware(['jwt.auth', 'jwt.cookie'])->group(function () {  
        Route::prefix('pengguna')->group(function () {
            Route::post('tambahpengguna', [UserController::class,"adduser"]);
            Route::get('daftarpengguna', [UserController::class,"getuser"]);
            Route::get('hapuspengguna', [UserController::class,"deleteuser"]);
            Route::get('detailpengguna', [UserController::class,"detailuser"]);
            Route::post('editpengguna', [UserController::class,"edituser"]);
            Route::get('load_data_dokter_bertugas', [UserController::class,"load_data_dokter_bertugas"]);
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
            Route::get('daftarantrian_beranda', [MasterdataController::class,"daftarantrian_beranda"]);
            Route::post('daftarantrian', [MasterdataController::class,"daftarantrian"]);
            Route::get('daftarantrian', [MasterdataController::class,"daftarantrian_get"]);
            Route::get('statusantrian', [MasterdataController::class,"statusantrian"]);
            Route::get('daftarpoli', [MasterdataController::class,"getpoli"]);
        });
        Route::prefix('pendaftaran')->group(function () {
            /*peserta*/
            Route::get('validasi_peserta', [PendaftaranController::class,"validasi_peserta"]);
            Route::post('validasi_peserta', [PendaftaranController::class,"validasi_pasien"]);
            Route::get('daftarpeserta', [PendaftaranController::class,"getpeserta"]);
            /*pasien*/
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
            /* Tanda Vital */
            Route::post('simpantandavital', [PemeriksaanFisikController::class,"simpantandavital"]);
            Route::get('daftar_tanda_vital', [PemeriksaanFisikController::class,"daftar_tanda_vital"]);
            Route::delete('hapustandavital', [PemeriksaanFisikController::class,"hapustandavital"]);
            Route::get('get_tandavital', [PemeriksaanFisikController::class,"get_tandavital"]);
            /* Penglihatan */
            Route::post('simpanpenglihatan', [PemeriksaanFisikController::class,"simpanpenglihatan"]);
            Route::get('daftar_penglihatan', [PemeriksaanFisikController::class,"daftar_penglihatan"]);
            Route::delete('hapus_penglihatan', [PemeriksaanFisikController::class,"hapus_penglihatan"]);
            Route::get('get_penglihatan', [PemeriksaanFisikController::class,"get_penglihatan"]);
            /* Kondisi Fisik */
            Route::post('simpankondisifisik', [PemeriksaanFisikController::class,"simpan_kondisi_fisik"]);
            Route::get('daftar_kondisi_fisik', [PemeriksaanFisikController::class,"daftar_kondisi_fisik"]);
            Route::delete('hapus_kondisi_fisik', [PemeriksaanFisikController::class,"hapus_kondisi_fisik"]);
            Route::get('get_kondisi_fisik', [PemeriksaanFisikController::class,"get_kondisi_fisik"]);
            /* Detail Atribut Kondisi Fisik */
            /* Lokasi Gigi */
            Route::get('get_kondisi_fisik_gigi', [PemeriksaanFisikController::class,"get_kondisi_fisik_gigi"]);
        });
        Route::prefix('masterdata')->group(function () {
            /* Master Data Perusahaan */
            Route::get('daftarperusahaan', [MasterdataController::class,"getperusahaan"]);
            Route::post('simpanperusahaan', [MasterdataController::class,"saveperusahaan"]);
            Route::get('hapusperusahaan', [MasterdataController::class,"deleteperusahaan"]);
            Route::post('ubahperusahaan', [MasterdataController::class,"editperusahaan"]);
            /* Master Data Paket MCU */
            Route::get('daftarpaketmcu', [MasterdataController::class,"getpaketmcu"]);
            Route::get('daftarpaketmcu_non_dt', [MasterdataController::class,"getpaketmcu_non_dt"]);
            Route::post('simpanpaketmcu', [MasterdataController::class,"savepaketmcu"]);
            Route::get('hapuspaketmcu', [MasterdataController::class,"deletepaketmcu"]);
            Route::post('ubahpaketmcu', [MasterdataController::class,"editpaketmcu"]);
            /* Master Data Jasa Pelayanan */
            Route::get('daftarjasa', [MasterdataController::class,"getjasa"]);
            Route::get('daftarjasa_laboratorium', [MasterdataController::class,"getjasa_laboratorium"]);
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
        Route::prefix('poliklinik')->group(function () {
            Route::post('simpan/{poliklinik}', [PoliklinikController::class,"simpan_poliklinik"]);
            Route::get('daftar_citra_unggahan_poliklinik', [PoliklinikController::class,"daftar_citra_unggahan_poliklinik"]);
            Route::get('detail_citra_unggahan_poliklinik', [PoliklinikController::class,"detail_citra_unggahan_poliklinik"]);
            Route::get('hapus_citra_unggahan_poliklinik', [PoliklinikController::class,"hapus_citra_unggahan_poliklinik"]);
            Route::get('hapus_foto_unggahan_satuan', [PoliklinikController::class,"hapus_foto_unggahan_satuan"]);
        });
        Route::prefix('laboratorium')->group(function () {
            Route::post('simpan_tarif_laboratorium', [LaboratoriumController::class,"simpan_tarif_laboratorium"]);
            Route::get('daftar_tarif', [LaboratoriumController::class,"daftar_tarif"]);
            Route::get('pencarian_tarif_laboratorium', [LaboratoriumController::class,"pencarian_tarif_laboratorium"]);
            Route::get('detail_tarif_laboratorium', [LaboratoriumController::class,"detail_tarif_laboratorium"]);
            Route::delete('hapus_tarif_laboratorium', [LaboratoriumController::class,"hapus_tarif_laboratorium"]);
            /* Template Laboratorium */
            Route::get('daftar_template_laboratorium', [LaboratoriumController::class,"daftar_template_laboratorium"]);
            Route::post('simpan_template_laboratorium', [LaboratoriumController::class,"simpan_template_laboratorium"]);
            Route::delete('hapus_template_laboratorium', [LaboratoriumController::class,"hapus_template_laboratorium"]);
            Route::get('detail_template_laboratorium', [LaboratoriumController::class,"detail_template_laboratorium"]);
            Route::get('pilih_template_tindakan_mcu', [LaboratoriumController::class,"pilih_template_tindakan_mcu"]);
            /* Transaksi Tindakan */
            Route::post('simpan_tindakan', [LaboratoriumController::class,"simpan_tindakan"]);
            Route::get('daftar_tindakan', [LaboratoriumController::class,"daftar_tindakan"]);
            Route::delete('hapus_tindakan', [LaboratoriumController::class,"hapus_tindakan"]);
            Route::get('detail_tindakan', [LaboratoriumController::class,"detail_tindakan"]);
            Route::get('ubah_data_apotek', [LaboratoriumController::class,"ubah_data_apotek"]);
            Route::post('ubah_data_apotek', [LaboratoriumController::class,"ubah_data_apotek"]);
            Route::delete('hapus_berkas_apotek', [LaboratoriumController::class,"hapus_berkas_apotek"]);
            /* Tindakan Kesimpulan */
            Route::get('tindakan_kesimpulan_pilihan', [LaboratoriumController::class,"tindakan_kesimpulan_pilihan"]);
        });
        Route::prefix('atribut')->group(function () {
            Route::get('lingkungankerja', [AtributController::class,"getlingkungankerja"]);
            /* Kategori Laboratorium */
            Route::get('kategori', [LaboratoriumController::class,"getkategori_laboratorium"]);
            Route::get('daftar_kategori', [LaboratoriumController::class,"daftar_kategori_laboratorium"]);
            Route::post('simpan_kategori', [LaboratoriumController::class,"simpan_kategori_laboratorium"]);
            Route::delete('hapus_kategori', [LaboratoriumController::class,"hapus_kategori_laboratorium"]);
            Route::get('detail_kategori', [LaboratoriumController::class,"detail_kategori_laboratorium"]);
            /* Satuan Laboratorium */
            Route::get('satuan', [LaboratoriumController::class,"getsatuan_laboratorium"]);
            Route::get('daftar_satuan', [LaboratoriumController::class,"daftar_satuan_laboratorium"]);
            Route::post('simpan_satuan', [LaboratoriumController::class,"simpan_satuan_laboratorium"]);
            Route::delete('hapus_satuan', [LaboratoriumController::class,"hapus_satuan_laboratorium"]);
            Route::get('detail_satuan', [LaboratoriumController::class,"detail_satuan_laboratorium"]);
            /* Nilai Rentang Kenormalan */
            Route::get('daftar_rentang_kenormalan', [LaboratoriumController::class,"daftar_rentang_kenormalan"]);
            Route::post('simpan_rentang_kenormalan', [LaboratoriumController::class,"simpan_rentang_kenormalan"]);
            Route::delete('hapus_rentang_kenormalan', [LaboratoriumController::class,"hapus_rentang_kenormalan"]);
            Route::get('detail_rentang_kenormalan', [LaboratoriumController::class,"detail_rentang_kenormalan"]);
        });
        Route::prefix('transaksi')->group(function () {
            Route::post('simpanpeserta', [TransaksiController::class,"savepeserta"]);
            Route::get('hapuspeserta', [TransaksiController::class,"deletepeserta"]);
            Route::post('konfirmasi_pembayaran', [TransaksiController::class,"konfirmasi_pembayaran"]);
        });
        Route::prefix('laporan')->group(function () {
            Route::get('validasi_mcu_nota', [LaporanController::class,"validasi_mcu_nota"]);
            Route::get('validasi_mcu_modal', [LaporanController::class,"validasi_mcu_modal"]);
            Route::get('validasi_mcu_nota_akhir', [LaporanController::class,"validasi_mcu_nota_akhir"]);
            Route::post('validasi_rekap_kesimpulan', [LaporanController::class,"validasi_rekap_kesimpulan"]);
            Route::get('validasi_rekap_kesimpulan', [LaporanController::class,"validasi_rekap_kesimpulan_get"]);
            Route::get('informasi_mcu', [LaporanController::class,"informasi_mcu"]);
            /* Transaksi Tindakan */
            Route::get('tindakan/{jenis_laporan}', [LaporanController::class,"laporan_tindakan"]);
            Route::get('kuitansi/{jenis_laporan}', [LaporanController::class,"laporan_kuitansi"]);
            Route::get('insentif/{jenis_laporan}', [LaporanController::class,"laporan_insentif"]);
        });
    });
    Route::prefix('enduser')->group(function () {
        Route::post('formulir/{status_formulir}', [FormulirController::class,"kirim_data_permohonan"]);
    });
});

