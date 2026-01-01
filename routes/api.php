<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\penggunaController;

// AUTH (PUBLIC)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/admin/login', [AuthController::class, 'adminLogin']);
Route::post('/admin/register', [AuthController::class, 'adminRegister']);

// AUTH (PROTECTED)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // PENGGUNA (READ ONLY)
    Route::get('/pengguna', [penggunaController::class, 'index']);
    Route::get('/pengguna/{id}', [penggunaController::class, 'show']);

    // ADMIN DASHBOARD (PROTECTED)
    Route::get('/admin/dashboard', function () {
        return response()->json([
            'message' => 'Welcome to admin dashboard',
            'user' => auth('sanctum')->user(),
        ]);
    });
});