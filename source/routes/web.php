<?php

use Illuminate\Support\Facades\{Route,Session};
use App\Http\Controllers\Web\{BerandaController,PesertamcuController};
use Illuminate\Http\Request;

Route::domain(env('APP_URL'))->group(function () {
    Route::get('/', function (Request $req) {
       $data = [ 'tipe_halaman' => 'login'];
       return view('login', ['data' => $data]);
    })->name('login');
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('beranda', [BerandaController::class,"dashboard"])->name('beranda');
    });
});
Route::domain(env('APP_URL_SUBDOMAIN'))->group(function () {
    Route::get('/', [PesertamcuController::class, "register_mcu"]);
});
