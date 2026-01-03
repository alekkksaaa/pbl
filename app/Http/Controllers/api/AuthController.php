<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // REGISTER USER ONLY
    public function register(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:penggunas,email',
            'password' => 'required|string|min:6',
            'no_hp' => 'nullable|string',
        ]);

        $user = Pengguna::create([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'password' => $data['password'],
            'no_hp' => $data['no_hp'] ?? null,
            'is_admin' => false,
        ]);

        $token = $user->createToken('user-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    // LOGIN USER (EMAIL)
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = Pengguna::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah'],
            ]);
        }

        if ($user->is_admin) {
            return response()->json([
                'message' => 'Admin tidak boleh login di halaman user',
            ], 403);
        }

        $token = $user->createToken('user-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    // LOGIN ADMIN (NAMA + PASSWORD)
    public function adminLogin(Request $request)
{
    $data = $request->validate([
        'nama' => 'required|string',
        'password' => 'required|string',
    ]);

    $admin = Petugas::where('nama', $data['nama'])->first();

    if (! $admin) {
        throw ValidationException::withMessages([
            'nama' => ['Nama atau password admin salah'],
        ]);
    }

    // ✅ JIKA PASSWORD SUDAH BCRYPT
    if (Hash::needsRehash($admin->password) === false) {
        if (! Hash::check($data['password'], $admin->password)) {
            throw ValidationException::withMessages([
                'nama' => ['Nama atau password admin salah'],
            ]);
        }
    } 
    // ⚠️ JIKA PASSWORD MASIH PLAIN TEXT (DATA LAMA)
    else {
        if ($data['password'] !== $admin->password) {
            throw ValidationException::withMessages([
                'nama' => ['Nama atau password admin salah'],
            ]);
        }

        // 🔥 AUTO HASH ULANG
        $admin->password = Hash::make($data['password']);
        $admin->save();
    }

    $token = $admin->createToken('admin-api-token')->plainTextToken;

    return response()->json([
        'message' => 'Login admin berhasil',
        'user' => $admin,
        'token' => $token,
    ]);
}


    // LOGOUT
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out',
        ]);
    }
}