<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Petugas;

class RedirectIfAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Jika yang login adalah Petugas, blokir akses ke rute Pengguna
        if ($request->user() && ($request->user() instanceof Petugas)) {
            return response()->json([
                'message' => 'Admin dilarang masuk area user',
            ], 403);
        }

        return $next($request);
    }
}