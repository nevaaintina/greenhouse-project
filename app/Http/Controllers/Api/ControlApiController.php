<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Greenhouse;
use App\Models\Setting;
use App\Models\Actuator;
use Illuminate\Http\Request;

class ControlApiController extends Controller
{
    public function status($greenhouseId)
    {
        // 1. Validasi Keberadaan Greenhouse
        $greenhouse = Greenhouse::find($greenhouseId);

        if (!$greenhouse) {
            return response()->json([
                'success' => false,
                'message' => 'Greenhouse tidak ditemukan'
            ], 404);
        }

        // 2. Ambil atau Buat Pengaturan Default (Threshold)
        $setting = Setting::firstOrCreate(
            ['greenhouse_id' => $greenhouseId],
            [
                'system_mode' => 'Otomatis',
                'soil_moisture_min' => 45,
                'soil_moisture_max' => 70,
                'temperature_min' => 20,
                'temperature_max' => 28,
                'humidity_min' => 40,
                'humidity_max' => 80,
                'light_min' => 300,
                'light_max' => 800
            ]
        );

        // 3. Ambil atau Buat Status Aktuator Pompa
        $pump = Actuator::firstOrCreate(
            [
                'greenhouse_id' => $greenhouseId,
                'type' => 'pump'
            ],
            [
                'name' => 'Pump',
                'status' => 'off',
                'mode' => 'manual'
            ]
        );

        // 4. Ambil atau Buat Status Aktuator Kipas
        $fan = Actuator::firstOrCreate(
            [
                'greenhouse_id' => $greenhouseId,
                'type' => 'fan'
            ],
            [
                'name' => 'Fan',
                'status' => 'off',
                'mode' => 'manual'
            ]
        );

        // 5. Ambil atau Buat Status Aktuator Lampu
        $lamp = Actuator::firstOrCreate(
            [
                'greenhouse_id' => $greenhouseId,
                'type' => 'lamp'
            ],
            [
                'name' => 'Lamp',
                'status' => 'off',
                'mode' => 'manual'
            ]
        );

        // 6. Return JSON Response Terformat String Murni untuk Alat IoT ESP32
        return response()->json([
            'success' => true,
            'mode' => $setting->system_mode,
            
            // Mengembalikan nilai string 'on' / 'off' langsung dari database
            'pump' => $pump->status, 
            'fan'  => $fan->status,  
            'lamp' => $lamp->status, 

            'soil_min'     => (int)$setting->soil_moisture_min,
            'soil_max'     => (int)$setting->soil_moisture_max,
            'temp_min'     => (float)$setting->temperature_min,
            'temp_max'     => (float)$setting->temperature_max,
            'humidity_min' => (int)$setting->humidity_min,
            'humidity_max' => (int)$setting->humidity_max,
            'light_min'    => (int)$setting->light_min,
            'light_max'    => (int)$setting->light_max
        ], 200);
    }

    /**
     * Endpoint Tambahan: Digunakan oleh Web UI untuk mengubah mode atau status aktuator secara manual
     */
    public function updateControl(Request $request, $greenhouseId)
    {
        // Update Mode Sistem (Otomatis / Manual)
        if ($request->has('mode')) {
            Setting::where('greenhouse_id', $greenhouseId)->update([
                'system_mode' => $request->mode
            ]);
        }

        // Update status fisik pompa dari tombol web
        if ($request->has('pump')) {
            Actuator::where('greenhouse_id', $greenhouseId)->where('type', 'pump')->update([
                'status' => $request->pump // menerima nilai string 'on' atau 'off'
            ]);
        }

        // Update status fisik kipas dari tombol web
        if ($request->has('fan')) {
            Actuator::where('greenhouse_id', $greenhouseId)->where('type', 'fan')->update([
                'status' => $request->fan
            ]);
        }

        // Update status fisik lampu dari tombol web
        if ($request->has('lamp')) {
            Actuator::where('greenhouse_id', $greenhouseId)->where('type', 'lamp')->update([
                'status' => $request->lamp
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kontrol manual berhasil diperbarui'
        ], 200);
    }
}