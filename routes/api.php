<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PemesananApiController;

Route::get('/cuaca', [PemesananApiController::class, 'cuaca']);
Route::get('/pemesanan', [PemesananApiController::class, 'index']);
Route::post('/pemesanan', [PemesananApiController::class, 'store']);