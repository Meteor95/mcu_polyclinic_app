<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\{AuthController, BerandaController, HakaksesController, MasterdataController, FileController, PendaftaranController, ProfileController};
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
    });
    Route::prefix('masterdata')->group(function () {
        Route::get('daftar_perusahaan', [MasterdataController::class,"daftar_perusahaan"])->name('admin.masterdata.daftar_perusahaan');
        Route::get('daftar_paket_mcu', [MasterdataController::class,"daftar_paket_mcu"])->name('admin.masterdata.daftar_paket_mcu');
        Route::get('daftar_jasa_pelayanan', [MasterdataController::class,"daftar_jasa_pelayanan"])->name('admin.masterdata.daftar_jasa_pelayanan');
        Route::get('daftar_departemen_peserta', [MasterdataController::class,"daftar_departemen_peserta"])->name('admin.masterdata.daftar_departemen_peserta');
        Route::get('daftar_member_mcu', [MasterdataController::class,"daftar_member_mcu"])->name('admin.masterdata.daftar_member_mcu');
    });
    Route::prefix('image')->group(function () {
        Route::get('user/signature/{filename}', [FileController::class, 'showSignature']);
    });
});
Route::prefix('landing')->group(function () {
    Route::get('formulir', [PendaftaranController::class,"formulir_pendaftaran"])->name('landing.formulir_pendaftaran');
});

