<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseTestingSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tambah Pengguna
        $userId = DB::table('penggunas')->insertGetId([
            'nama' => 'Erik User',
            'email' => 'erik@example.com',
            'password' => Hash::make('password'),
            'created_at' => now(),
        ]);

        // 2. Tambah Pesanan (Status Pending)
        $pesananId = DB::table('pesanans')->insertGetId([
            'pengguna_id' => $userId,
            'tanggal_pesan' => now()->toDateString(),
            'jumlah_tiket' => 2,
            'total_harga' => 300000,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        // 3. Tambah Pembayaran
        DB::table('pembayarans')->insert([
            'pesanan_id' => $pesananId,
            'metode' => 'Transfer Bank',
            'tanggal_bayar' => now()->toDateString(),
            'jumlah_bayar' => 300000,
            'status_bayar' => 'pending',
            'created_at' => now(),
        ]);
    }
}