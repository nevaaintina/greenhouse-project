<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sensor;
use App\Models\SensorData;
use App\Models\Greenhouse;
use App\Models\Actuator;
use App\Models\Setting;

class SensorApiController extends Controller
{
    // ======================================================
    // STORE DATA SENSOR & SINKRONISASI AKTUTATOR
    // ======================================================
    public function store(Request $request)
    {
        // 1. Jalankan Validasi Input dari ESP32
        $request->validate([
            'greenhouse_id' => 'required|integer',
            'soil'          => 'required|numeric',
            'temperature'   => 'required|numeric',
            'humidity'      => 'required|numeric',
            'light'         => 'required|numeric'
        ]);

        $greenhouse = Greenhouse::find($request->greenhouse_id);

        if (!$greenhouse) {
            return response()->json([
                'success' => false, 
                'message' => 'Greenhouse tidak ditemukan'
            ], 404);
        }

        // ======================================================
        // 2. SIMPAN LOG DATA SENSOR KE DATABASE
        // ======================================================
        $sensorValues = [
            'soil'        => $request->soil,
            'temperature' => $request->temperature,
            'humidity'    => $request->humidity,
            'light'       => $request->light
        ];

        foreach ($sensorValues as $type => $value) {
            // Pastikan sensor terdaftar, jika belum otomatis dibuat
            $sensor = Sensor::firstOrCreate(
                ['greenhouse_id' => $greenhouse->id, 'type' => $type],
                ['name' => ucfirst($type)]
            );

            // Insert data baru ke tabel log data sensor
            SensorData::create([
                'sensor_id'   => $sensor->id,
                'value'       => $value,
                'recorded_at' => now()
            ]);
        }

        // ======================================================
        // 3. PERBAIKAN: UPDATE STATUS RIIL AKTUTATOR (Khusus Mode Otomatis)
        // ======================================================
        $setting = Setting::where('greenhouse_id', $greenhouse->id)->first();
        
        // Website hanya mau menerima update status dari alat JIKA sistem berada di mode 'Otomatis'
        if ($setting && $setting->system_mode === 'Otomatis') {
            $actuators = ['pump', 'fan', 'lamp'];
            
            foreach ($actuators as $act) {
                $inputKey = $act . '_status'; // e.g., pump_status
                
                if ($request->has($inputKey)) {
                    // Paksa database memperbarui nilainya sesuai kondisi fisik di lapangan
                    Actuator::where('greenhouse_id', $greenhouse->id)
                        ->where('type', $act)
                        ->update([
                            'status' => strtolower($request->input($inputKey)), // Mengubah 'on'/'off' menjadi huruf kecil
                            'mode'   => 'auto'
                        ]);
                }
            }
        }

        return response()->json([
            'success' => true, 
            'message' => 'Data sensor & status hardware otomatis berhasil disinkronkan!'
        ]);
    }
}