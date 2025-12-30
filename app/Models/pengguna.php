<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pengguna extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'penggunas';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_hp',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}