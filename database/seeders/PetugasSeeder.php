<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Petugas;

class PetugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Petugas::create([
            'nama' => 'admin',
            'password' => bcrypt('admin123'),
        ]);

        echo "Admin account created: nama=admin, password=admin123\n";
    }
}
