<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Petugas;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PetugasLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login()
    {
        // Gunakan model Petugas karena adminLogin mencari di tabel petugas
        Petugas::create([
            'nama' => 'admin_test',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/admin/login', [
            'nama' => 'admin_test',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token', 'message']);
    }
}
// class AdminLoginTest extends TestCase
// {
// use RefreshDatabase;

// public function test_admin_can_login()
// {
//     Pengguna::create([
//     'nama' => 'Admin',
//     'email' => 'admin@test.com',
//     'password' => 'password123',
//     'is_admin' => true,
//     ]);

//     $response = $this->postJson('/api/admin/login', [
//     'email' => 'admin@test.com',
//     'password' => 'password123',
//     ]);

//     $response->assertStatus(200)
//     ->assertJsonStructure(['token']);
//     }

// public function test_admin_can_login()
// {
//     // 1. Buat data di tabel Petugas (Sesuai Petugas.php)
//     \App\Models\Petugas::create([
//         'nama' => 'admin_test',
//         'password' => bcrypt('password123'),
//     ]);

//     // 2. Login menggunakan 'nama' (Bukan email, sesuai AuthController@adminLogin)
//     $response = $this->postJson('/api/admin/login', [
//         'nama' => 'admin_test',
//         'password' => 'password123',
//     ]);

//     // 3. Pastikan mengarah ke dashboard atau dapet token
//     $response->assertStatus(200)
//              ->assertJsonStructure(['token', 'message']);
// }
// }