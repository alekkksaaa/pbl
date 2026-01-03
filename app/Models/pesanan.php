<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanans';

    protected $fillable = [
        'pengguna_id',
        'tanggal_pesan',
        'jumlah_tiket',
        'alat_mendaki',
        'total_harga',
        'status'
    ];

    /**
     * Relasi ke Pengguna (Model Login Anda)
     */
    public function pengguna(): BelongsTo
    {
        // Pastikan mengarah ke model Pengguna sesuai file yang Anda kirim
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'pesanan_id');
    }
}