<?php

use Illuminate\Support\Facades\{Route,Session};
use App\Http\Controllers\Web\{BerandaController,PesertamcuController};
use App\Http\Controllers\Web\Paneladmin\Pengaturan\RoleAndPermissionController;
use Illuminate\Http\Request;
use App\Http\Middleware\VerifyIsLogin;
Route::get('generate-csrf-token', function () {$token = csrf_token();return response()->json(['csrf_token' => $token]);});
Route::get('/', function (Request $req) {
    $data = [ 'tipe_halaman' => 'login'];
    return view('login', ['data' => $data]);
})->name('login');
Route::middleware('auth:jwt')->group(function () {
     Route::get('beranda', [BerandaController::class,"dashboard"])->name('beranda');
     Route::get('hakakses', [RoleAndPermissionController::class,"createRole"])->name('daftar_hakakses'); 
});
Route::middleware([VerifyIsLogin::class])->group(function () {
    Route::get('dashboard', function () {
        return "AAAAA";
    })->name('dashboard');
});
Route::domain(env('APP_URL_SUBDOMAIN'))->group(function () {
    Route::get('/', [PesertamcuController::class, "register_mcu"]);
});
