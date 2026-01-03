<?php

// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\AuthController;
// use App\Http\Controllers\Api\PenggunaController;
// use App\Http\Controllers\Api\PesananController; // Tambahkan ini
// use Illuminate\Http\JsonResponse;

// // --- PUBLIC ROUTES ---
// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/admin/login', [AuthController::class, 'adminLogin']);

// // --- PROTECTED ROUTES ---
// Route::middleware('auth:sanctum')->group(function () {

//     Route::post('/logout', [AuthController::class, 'logout']);

//     /* Area Pengguna (User) - Menggunakan middleware 'no_admin' */
//     Route::middleware('no_admin')->group(function () {
//         Route::get('/pengguna', [PenggunaController::class, 'index']);
//         Route::get('/pengguna/{id}', [PenggunaController::class, 'show']);
        
//         // User bisa melihat riwayat pesanannya sendiri
//         Route::get('/my-orders', [PesananController::class, 'userOrders']);
//     });

//     /* Area Admin - Menggunakan middleware 'is_admin' */
//     Route::prefix('admin')->middleware('is_admin')->group(function () {
//         Route::get('/dashboard', function (): JsonResponse {
//             return response()->json([
//                 'message' => 'Welcome to admin dashboard',
//                 'admin' => request()->user(),
//             ]);
//         });

//         // Alur Admin: Kelola Pesanan
//         Route::post('/pesanan', [PesananController::class, 'store']);
//         Route::get('/pesanan', [PesananController::class, 'index']); // Ambil semua data (Pending teratas)
//         Route::put('/pesanan/{id}/konfirmasi', [PesananController::class, 'updateStatus']); // Ubah status ke Success
//         Route::put('/admin/pesanan/{id}/konfirmasi', [App\Http\Controllers\api\PesananController::class, 'updateStatus']);
//     });

// });

// =========================================================================================================



// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\AuthController;
// use App\Http\Controllers\Api\PenggunaController;
// use App\Http\Controllers\Api\PesananController;
// use Illuminate\Http\JsonResponse;

// // --- PUBLIC ROUTES ---
// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/admin/login', [AuthController::class, 'adminLogin']);

// // --- PROTECTED ROUTES ---
// Route::middleware('auth:sanctum')->group(function () {

//     Route::post('/logout', [AuthController::class, 'logout']);

//     /* Area Pengguna (User) */
//     Route::middleware('no_admin')->group(function () {
//         // PINDAHKAN INI KE SINI AGAR USER BISA PESAN
//         Route::post('/pesanan', [PesananController::class, 'store']); 
        
//         Route::get('/pengguna', [PenggunaController::class, 'index']);
//         Route::get('/pengguna/{id}', [PenggunaController::class, 'show']);
//         Route::get('/my-orders', [PesananController::class, 'userOrders']);
//     });

//     /* Area Admin */
//     Route::prefix('admin')->middleware('is_admin')->group(function () {
//         Route::get('/dashboard', function (): JsonResponse {
//             return response()->json([
//                 'message' => 'Welcome to admin dashboard',
//                 'admin' => request()->user(),
//             ]);
//         });

//         Route::get('/pesanan', [PesananController::class, 'index']);
//         Route::put('/pesanan/{id}/konfirmasi', [PesananController::class, 'updateStatus']);
//     });
// });

// =========================================================================================================



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PenggunaController;
use App\Http\Controllers\Api\PesananController;
use Illuminate\Http\JsonResponse;

// --- PUBLIC ROUTES ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/admin/login', [AuthController::class, 'adminLogin']);

// --- PROTECTED ROUTES ---
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    /* Area Pengguna (User) */
    Route::middleware('no_admin')->group(function () {
        // Route ini harus di sini agar user bisa membuat pesanan
        Route::post('/pesanan', [PesananController::class, 'store']); 
        
        Route::get('/pengguna', [PenggunaController::class, 'index']);
        Route::get('/pengguna/{id}', [PenggunaController::class, 'show']);
        Route::get('/my-orders', [PesananController::class, 'userOrders']);
    });

    /* Area Admin */
    Route::prefix('admin')->middleware('is_admin')->group(function () {
        Route::get('/dashboard', function (): JsonResponse {
            return response()->json([
                'message' => 'Welcome to admin dashboard',
                'admin' => request()->user(),
            ]);
        });

        Route::get('/pesanan', [PesananController::class, 'index']);
        Route::put('/pesanan/{id}/konfirmasi', [PesananController::class, 'updateStatus']);
    });
});