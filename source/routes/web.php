<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\{AuthController, BerandaController, HakaksesController, MasterdataController, FileController};
use Illuminate\Http\Request;

Route::get('generate-csrf-token', function () { $token = csrf_token(); return response()->json(['csrf_token' => $token]); });
Route::get('/', function (Request $req) { $data = [ 'tipe_halaman' => 'login']; return view('login', ['data' => $data]); })->name('login');
Route::get('403', function () { return view('error.403_error'); });
Route::group(['middleware' => ['jwt.cookie']], function () {
    Route::get('pintukeluar', [AuthController::class, "logout"]);
    Route::prefix('admin')->group(function () {
        Route::get('beranda', [BerandaController::class,"index"])->name('admin.beranda');
        Route::get('pengguna_aplikasi', [HakaksesController::class,"pengguna_aplikasi"])->name('admin.pengguna_aplikasi');
        Route::get('permission', [HakaksesController::class,"permission"])->name('admin.permission');
        Route::get('role', [HakaksesController::class,"role"])->name('admin.role');
    });
    Route::prefix('masterdata')->group(function () {
        Route::get('daftar_perusahaan', [MasterdataController::class,"daftar_perusahaan"])->name('admin.masterdata.daftar_perusahaan');
        Route::get('daftar_paket_mcu', [MasterdataController::class,"daftar_paket_mcu"])->name('admin.masterdata.daftar_paket_mcu');
        Route::get('daftar_jasa_pelayanan', [MasterdataController::class,"daftar_jasa_pelayanan"])->name('admin.masterdata.daftar_jasa_pelayanan');
        Route::get('daftar_departemen_peserta', [MasterdataController::class,"daftar_departemen_peserta"])->name('admin.masterdata.daftar_departemen_peserta');
    });
    Route::prefix('image')->group(function () {
        Route::get('user/signature/{filename}', [FileController::class, 'showSignature']);
    });
});

