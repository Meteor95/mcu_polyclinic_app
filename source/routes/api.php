<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{AuthController, RoleAndPermissionController, UserController, MasterdataController, PendaftaranController, TransaksiController};

Route::get('/', function(){return ResponseHelper::error(401);})->name('login');
Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('pintupendaftaran', [AuthController::class,"register"]);
        Route::post('pintumasuk', [AuthController::class,"login"]);
        Route::post('buattokenbaru', [AuthController::class,"refreshToken"]);
        Route::post('keluar', [AuthController::class,"logout"]);
        Route::post('tambahakses', [RoleAndPermissionController::class,"addpermission"]);
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
            Route::get('hapuspeserta', [PendaftaranController::class,"deletepeserta"]);
            Route::get('getdatapeserta', [PendaftaranController::class,"getdatapeserta"]);
            Route::get('daftarpasien', [TransaksiController::class,"getpasien"]);
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
        Route::prefix('transaksi')->group(function () {
            Route::post('simpanpeserta', [TransaksiController::class,"savepeserta"]);
        });
    });
});
