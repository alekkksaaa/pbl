<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Petugas;

use Illuminate\Support\Facades\Hash;

class PetugasTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin login functionality.
     *
     * @return void
     */
    public function testAdminLogin()
    {
        // Arrange
        $admin = Petugas::create([
            'nama' => 'admin',
            'password' => Hash::make('password123'),
        ]);

        $credentials = [
            'nama' => 'admin',
            'password' => 'password123',
        ];

        // Act
        $response = $this->post('/api/admin/login', $credentials);

        // Assert
        $response->assertStatus(200);
        $this->assertArrayHasKey('token', $response->json());
    }
}