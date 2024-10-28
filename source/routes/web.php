<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\{AuthController, BerandaController, HakaksesController};
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
});

