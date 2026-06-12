<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sensor;
use App\Models\SensorData;
use App\Models\Greenhouse;
use App\Models\Actuator;
use App\Models\Setting;
use App\Models\Log; // Ditambahkan untuk mencatat histori otomatisasi

class SensorApiController extends Controller
{
    // ======================================================
    // STORE DATA SENSOR & SINKRONISASI AUTOMATION
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
        // 2. SIMPAN LOG DATA SENSOR KE DATABASE (Looping Relasi Master-Log)
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
        // 3. LOGIKA OTOMATISASI KENDALI PUSAT (Cloud-Driven)
        // ======================================================
        $setting = Setting::where('greenhouse_id', $greenhouse->id)->first();
        
        if ($setting && $setting->system_mode === 'Otomatis') {
            $pump = Actuator::where('greenhouse_id', $greenhouse->id)->where('type', 'pump')->first();
            $fan  = Actuator::where('greenhouse_id', $greenhouse->id)->where('type', 'fan')->first();
            $lamp = Actuator::where('greenhouse_id', $greenhouse->id)->where('type', 'lamp')->first();

            // --- Logika Pompa Air (Kelembapan Tanah) ---
            if ($pump) {
                if ($request->soil < $setting->soil_moisture_min && $pump->status !== 'on') {
                    $pump->update(['status' => 'on', 'mode' => 'auto']);
                    $this->createLog($greenhouse->id, 'SYSTEM AUTOMATION', 'Pompa menyala otomatis: Tanah kering (' . $request->soil . '%)');
                } elseif ($request->soil >= $setting->soil_moisture_max && $pump->status !== 'off') {
                    $pump->update(['status' => 'off', 'mode' => 'auto']);
                    $this->createLog($greenhouse->id, 'SYSTEM AUTOMATION', 'Pompa mati otomatis: Tanah lembab (' . $request->soil . '%)');
                }
            }

            // --- Logika Kipas (Suhu Udara) ---
            if ($fan) {
                if ($request->temperature > $setting->temperature_max && $fan->status !== 'on') {
                    $fan->update(['status' => 'on', 'mode' => 'auto']);
                    $this->createLog($greenhouse->id, 'SYSTEM AUTOMATION', 'Kipas menyala otomatis: Suhu panas (' . $request->temperature . '°C)');
                } elseif ($request->temperature <= $setting->temperature_min && $fan->status !== 'off') {
                    $fan->update(['status' => 'off', 'mode' => 'auto']);
                    $this->createLog($greenhouse->id, 'SYSTEM AUTOMATION', 'Kipas mati otomatis: Suhu stabil (' . $request->temperature . '°C)');
                }
            }

            // --- Logika Lampu UV (Intensitas Cahaya) ---
            if ($lamp) {
                if ($request->light < $setting->light_min && $lamp->status !== 'on') {
                    $lamp->update(['status' => 'on', 'mode' => 'auto']);
                    $this->createLog($greenhouse->id, 'SYSTEM AUTOMATION', 'Lampu UV menyala otomatis: Ruangan gelap (' . $request->light . ' Lux)');
                } elseif ($request->light >= $setting->light_max && $lamp->status !== 'off') {
                    $lamp->update(['status' => 'off', 'mode' => 'auto']);
                    $this->createLog($greenhouse->id, 'SYSTEM AUTOMATION', 'Lampu UV mati otomatis: Cahaya cukup (' . $request->light . ' Lux)');
                }
            }
        }

        return response()->json([
            'success' => true, 
            'message' => 'Data sensor berhasil disimpan dan otomasi cloud berhasil diproses!'
        ]);
    }

    /**
     * Helper internal untuk mencatat log aktivitas
     */
    private function createLog($greenhouseId, $activity, $description)
    {
        Log::create([
            'user_id' => auth()->id() ?? 1, 
            'greenhouse_id' => $greenhouseId,
            'activity' => $activity,
            'description' => $description,
            'created_at' => now()
        ]);
    }
}