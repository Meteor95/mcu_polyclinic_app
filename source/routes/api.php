<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{AuthController};
use App\Helpers\ResponseHelper;

Route::get('/', function(){return ResponseHelper::error(401);})->name('login');
Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('pintumasuk', [AuthController::class,"login"]);
    });
});
