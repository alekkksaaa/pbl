<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:penggunas,email',
            'password' => 'required|string|min:6',
            'no_hp' => 'nullable|string',
            'is_admin' => 'nullable|boolean',
        ]);

        $user = Pengguna::create([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'password' => $data['password'], // ✅ AUTO HASH DARI MODEL
            'no_hp' => $data['no_hp'] ?? null,
            'is_admin' => $data['is_admin'] ?? false,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

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

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function adminLogin(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = Petugas::where('nama', $data['nama'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'nama' => ['Nama atau password salah'],
            ]);
        }

        $token = $user->createToken('admin-api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function adminRegister(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255|unique:petugas,nama',
            'password' => 'required|string|min:6',
        ]);

        $user = Petugas::create([
            'nama' => $data['nama'],
            'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
        ]);

        $token = $user->createToken('admin-api-token')->plainTextToken;

        return response()->json([
            'message' => 'Admin terdaftar',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json(['message' => 'Logged out']);
    }
}