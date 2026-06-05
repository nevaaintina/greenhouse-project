<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Greenhouse;

class HeartbeatApiController extends Controller
{
    // ======================================================
    // HEARTBEAT
    // ======================================================
    public function store(Request $request)
    {
        $request->validate([
            'greenhouse_id' => 'required|integer'
        ]);

        // Melakukan update secara langsung, mengembalikan jumlah baris yang terpengaruh (0 atau 1)
        $updated = Greenhouse::where('id', $request->greenhouse_id)
            ->update([
                'last_seen' => now()
            ]);

        // Jika tidak ada baris yang ter-update (berarti ID tidak ditemukan)
        if (!$updated) {
            return response()->json([
                'success' => false,
                'message' => 'Greenhouse tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'time' => now()
        ]);
    }
}