<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use Illuminate\Http\Request;

Route::get('generate-csrf-token', function () {$token = csrf_token();return response()->json(['csrf_token' => $token]);});
Route::get('/', function (Request $req) {
    $data = [ 'tipe_halaman' => 'login'];
    return view('login', ['data' => $data]);
})->name('login');
Route::get('pintumasuk', [AuthController::class, "login"]);
Route::get('pintukeluar', [AuthController::class, "logout"]);
