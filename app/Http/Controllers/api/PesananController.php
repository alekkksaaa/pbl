<?php

// namespace App\Http\Controllers\api;

// use App\Http\Controllers\Controller;
// use App\Models\Pesanan;
// use Illuminate\Http\Request;

// class PesananController extends Controller
// {
//     public function index()
// {
//     try {
//         $data = Pesanan::with(['pengguna', 'pembayaran'])
//             ->orderByRaw("CASE WHEN status = 'pending' THEN 1 ELSE 2 END")
//             ->orderBy('created_at', 'DESC')
//             ->get();

//         return response()->json($data);
//     } catch (\Exception $e) {
//         // Ini akan memunculkan pesan error asli jika terjadi kesalahan relasi
//         return response()->json(['error' => $e->getMessage()], 500);
//     }
// }

//     public function updateStatus(Request $request, $id)
//     {
//         $pesanan = Pesanan::find($id);
        
//         if (!$pesanan) {
//             return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
//         }

//         // Admin mengubah status menjadi success
//         $pesanan->update(['status' => 'success']);

//         // Update juga status di tabel pembayaran jika ada
//         if ($pesanan->pembayaran) {
//             $pesanan->pembayaran->update(['status_bayar' => 'success']);
//         }

//         return response()->json([
//             'message' => 'Status berhasil dikonfirmasi!',
//             'data' => $pesanan
//         ]);
//     }
// }



namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PesananController extends Controller
{
    /**
     * Menampilkan daftar semua pesanan (Untuk Admin)
     */
    public function index()
    {
        try {
            // Mengambil pesanan dengan relasi pengguna dan pembayaran
            // Diurutkan: Pending di atas, lalu berdasarkan waktu terbaru
            $data = Pesanan::with(['pengguna', 'pembayaran'])
                ->orderByRaw("CASE WHEN status = 'pending' THEN 1 ELSE 2 END")
                ->orderBy('created_at', 'DESC')
                ->get();

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menyimpan pesanan baru (Untuk User/Pelanggan)
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            // Jika user null, kirim response yang jelas, bukan error 500
            if (!$user) {
                return response()->json(['message' => 'User tidak terdeteksi. Silakan login ulang.'], 401);
            }

            // Simpan data
            $pesanan = new \App\Models\Pesanan();
            $pesanan->pengguna_id = $user->id;
            $pesanan->tanggal_pesan = $request->tanggal_pesan;
            $pesanan->jumlah_tiket = $request->jumlah_tiket;
            $pesanan->alat_mendaki = $request->alat_mendaki ?? "-";
            $pesanan->total_harga = $request->total_harga;
            $pesanan->status = 'pending';
            $pesanan->save();

            return response()->json(['success' => true, 'data' => $pesanan], 201);

        } catch (\Exception $e) {
            // Catat error ke log agar bisa dibaca di storage/logs/laravel.log
            Log::error("Gagal Simpan Pesanan: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error Database: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengonfirmasi status pesanan (Untuk Admin)
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $pesanan = Pesanan::find($id);
            
            if (!$pesanan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan'
                ], 404);
            }

            // Update status pesanan menjadi success
            $pesanan->update(['status' => 'success']);

            // Jika ada relasi pembayaran, update juga status bayarnya
            if ($pesanan->pembayaran) {
                $pesanan->pembayaran->update(['status_bayar' => 'success']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil dikonfirmasi!',
                'data'    => $pesanan
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update status: ' . $e->getMessage()
            ], 500);
        }
    }
}