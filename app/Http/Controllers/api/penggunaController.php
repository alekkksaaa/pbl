<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;

class penggunaController extends Controller
{
    public function index()
    {
        return response()->json(
            Pengguna::select('id', 'nama', 'email', 'no_hp')->get()
        );
    }

    public function show($id)
    {
        return response()->json(
            Pengguna::select('id', 'nama', 'email', 'no_hp')->findOrFail($id)
        );
    }
}