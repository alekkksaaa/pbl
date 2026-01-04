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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PesananController extends Controller
{
    /**
     * Menampilkan daftar semua pesanan (Untuk Admin Panel)
     */
    public function index()
    {
        try {
            // Mengambil pesanan dengan relasi
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
     * Menyimpan pesanan baru (Halaman Invoice User)
     */
    public function store(Request $request)
    {
        try {
            // Menggunakan Auth::user() yang lebih stabil
            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Sesi habis, silakan login kembali.'], 401);
            }

            // Validasi sederhana agar data tidak kosong
            $request->validate([
                'tanggal_pesan' => 'required',
                'jumlah_tiket' => 'required|numeric',
                'total_harga' => 'required|numeric',
            ]);

            $pesanan = new Pesanan();
            $pesanan->pengguna_id = $user->id;
            $pesanan->tanggal_pesan = $request->tanggal_pesan;
            $pesanan->jumlah_tiket = $request->jumlah_tiket;
            // Jika kolom alat_mendaki belum ada di DB, baris ini akan menyebabkan error 500
            $pesanan->alat_mendaki = $request->alat_mendaki ?? "-";
            $pesanan->total_harga = $request->total_harga;
            $pesanan->status = 'pending';
            $pesanan->save();

            return response()->json(['success' => true, 'data' => $pesanan], 201);

        } catch (\Exception $e) {
            Log::error("Gagal Simpan Pesanan: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim pesanan. Pastikan database sudah di-migrate.'
            ], 500);
        }
    }

    /**
     * Menampilkan riwayat pesanan user yang sedang login
     */
    public function userOrders()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $orders = Pesanan::where('pengguna_id', $user->id)
                    ->with('pembayaran')
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
        return response()->json($orders);
    }

    /**
     * Update Status (Untuk Admin Konfirmasi)
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $pesanan = Pesanan::find($id);
            
            if (!$pesanan) {
                return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan'], 404);
            }

            $pesanan->update(['status' => 'success']);

            if ($pesanan->pembayaran) {
                $pesanan->pembayaran->update(['status_bayar' => 'success']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil dikonfirmasi!',
                'data'    => $pesanan
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}