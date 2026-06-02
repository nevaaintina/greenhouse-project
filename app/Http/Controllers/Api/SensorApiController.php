<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sensor;
use App\Models\SensorData;
use App\Models\Greenhouse;

class SensorApiController extends Controller
{
    // ======================================================
    // STORE SENSOR DATA
    // ======================================================

    public function store(Request $request)
    {
        // ======================================================
        // VALIDASI
        // ======================================================

        $request->validate([

            'greenhouse_id' => 'required|integer',
            'soil' => 'required|numeric',
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric',
            'light' => 'required|numeric'
        ]);

        // ======================================================
        // GREENHOUSE
        // ======================================================

        $greenhouse = Greenhouse::find(

            $request->greenhouse_id
        );

        if (!$greenhouse)
        {
            return response()->json([

                'success' => false,
                'message' => 'Greenhouse tidak ditemukan'

            ], 404);
        }

        // ======================================================
        // SENSOR DATA
        // ======================================================

        $sensorValues = [

            'soil' => $request->soil,
            'temperature' => $request->temperature,
            'humidity' => $request->humidity,
            'light' => $request->light
        ];

        // ======================================================
        // SAVE ALL SENSOR
        // ======================================================

        foreach ($sensorValues as $type => $value)
        {
            $sensor = Sensor::firstOrCreate(

                [

                    'greenhouse_id' => $greenhouse->id,
                    'type' => $type
                ],

                [

                    'name' => ucfirst($type)
                ]
            );

            SensorData::create([

                'sensor_id' => $sensor->id,
                'value' => $value,
                'recorded_at' => now()
            ]);
        }

        // ======================================================
        // SUCCESS
        // ======================================================

        return response()->json([

            'success' => true,
            'message' => 'Data sensor berhasil disimpan',

            'data' => [
                'greenhouse_id' => $greenhouse->id,
                'soil' => $request->soil,
                'temperature' => $request->temperature,
                'humidity' => $request->humidity,
                'light' => $request->light
            ]
        ]);
    }
}