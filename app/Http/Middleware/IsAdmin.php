<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Petugas;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Pengecekan apakah user login DAN apakah dia instance dari Petugas
        if (! $request->user() || ! ($request->user() instanceof Petugas)) {
            return response()->json([
                'message' => 'Unauthorized. Akses khusus Petugas.',
            ], 403);
        }

        return $next($request);
    }
}