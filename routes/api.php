<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\penggunaController;

// AUTH (PUBLIC)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// AUTH (PROTECTED)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // PENGGUNA (READ ONLY)
    Route::get('/pengguna', [penggunaController::class, 'index']);
    Route::get('/pengguna/{id}', [penggunaController::class, 'show']);
});