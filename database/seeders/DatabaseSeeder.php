<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create an admin pengguna
        Pengguna::create([
            'nama' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password',
            'no_hp' => '08123456789',
            'is_admin' => true,
        ]);

        // Create a normal test pengguna
        Pengguna::create([
            'nama' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'no_hp' => '08129876543',
            'is_admin' => false,
        ]);
    }
}