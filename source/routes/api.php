<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{AuthController, RoleAndPermissionController};

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
        Route::prefix('permission')->group(function () {
            Route::post('tambahpermission', [RoleAndPermissionController::class,"addpermission"]);
            Route::get('daftarhakakses', [RoleAndPermissionController::class,"getpermission"]);
        });
    });
});
