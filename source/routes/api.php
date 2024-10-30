<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{AuthController, RoleAndPermissionController, UserController, MasterdataController};

Route::get('/', function(){return ResponseHelper::error(401);})->name('login');
Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('pintupendaftaran', [AuthController::class,"register"]);
        Route::post('pintumasuk', [AuthController::class,"login"]);
        Route::post('buattokenbaru', [AuthController::class,"refreshToken"]);
        Route::post('keluar', [AuthController::class,"logout"]);
        Route::post('tambahakses', [RoleAndPermissionController::class,"addpermission"]);
    });
    Route::middleware('jwt.auth')->group(function () {
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
        Route::prefix('masterdata')->group(function () {
            /* Master Data Perusahaan */
            Route::get('daftarperusahaan', [MasterdataController::class,"getperusahaan"]);
            Route::post('simpanperusahaan', [MasterdataController::class,"saveperusahaan"]);
            Route::get('hapusperusahaan', [MasterdataController::class,"deleteperusahaan"]);
            Route::get('detailperusahaan', [MasterdataController::class,"detailperusahaan"]);
            Route::post('ubahperusahaan', [MasterdataController::class,"editperusahaan"]);
            /* Master Data Paket MCU */
            Route::get('daftarpaketmcu', [MasterdataController::class,"getpaketmcu"]);
            Route::post('simpanpaketmcu', [MasterdataController::class,"savepaketmcu"]);
            Route::get('hapuspaketmcu', [MasterdataController::class,"deletepaketmcu"]);
            Route::get('detailpaketmcu', [MasterdataController::class,"detailpaketmcu"]);
            Route::post('ubahpaketmcu', [MasterdataController::class,"editpaketmcu"]);
        });
    });
});
