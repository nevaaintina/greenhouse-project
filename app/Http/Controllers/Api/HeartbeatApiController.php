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

            'greenhouse_id' =>
                'required|integer'
        ]);

        $greenhouse = Greenhouse::find(

            $request->greenhouse_id
        );

        if (!$greenhouse)
        {
            return response()->json([

                'success' => false,
                'message' =>
                    'Greenhouse tidak ditemukan'
            ], 404);
        }

        // ======================================================
        // UPDATE LAST SEEN
        // ======================================================

        $greenhouse->update([

            'last_seen' => now()
        ]);

        return response()->json([

            'success' => true,
            'time' => now()
        ]);
    }
}