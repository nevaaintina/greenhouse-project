<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Greenhouse;
use App\Models\Setting;
use App\Models\Actuator;
use App\Models\Log;
use Illuminate\Http\Request;

class ControlApiController extends Controller
{
    /**
     * ENDPOINT UTAMA: ESP32 melakukan POST data sensor ke sini.
     * Laravel menghitung otomasi, mengupdate DB, lalu mengembalikan status & threshold terbaru.
     */
    public function receiveSensorData(Request $request, $greenhouseId)
    {
        // 1. Validasi Keberadaan Greenhouse
        $greenhouse = Greenhouse::find($greenhouseId);
        if (!$greenhouse) {
            return response()->json([
                'success' => false,
                'message' => 'Greenhouse tidak ditemukan'
            ], 404);
        }

        // 2. Ambil Pengaturan Batas Aman (Threshold) dan Mode
        $setting = Setting::where('greenhouse_id', $greenhouseId)->first();
        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Pengaturan tidak ditemukan'
            ], 404);
        }

        // 3. Tangkap Data Sensor Fisik dari ESP32
        $soilInput  = $request->input('soil');
        $tempInput  = $request->input('temp');
        $lightInput = $request->input('light');
        $humInput   = $request->input('hum'); 

        // Ambil data aktuator dari database
        $pump = Actuator::where('greenhouse_id', $greenhouseId)->where('type', 'pump')->first();
        $fan  = Actuator::where('greenhouse_id', $greenhouseId)->where('type', 'fan')->first();
        $lamp = Actuator::where('greenhouse_id', $greenhouseId)->where('type', 'lamp')->first();

        // 4. JALANKAN LOGIKA OTOMATISASI KENDALI PUSAT (Hanya jika mode = 'Otomatis')
        if ($setting->system_mode === 'Otomatis') {
            
            // --- Logika Otomatisasi Pompa Air (Berdasarkan Kelembapan Tanah) ---
            if ($pump) {
                if ($soilInput < $setting->soil_moisture_min && $pump->status !== 'on') {
                    $pump->update(['status' => 'on', 'mode' => 'auto']);
                    $this->createLog($greenhouseId, 'SYSTEM AUTOMATION', 'Pompa menyala otomatis: Tanah kering (' . $soilInput . '%)');
                } elseif ($soilInput >= $setting->soil_moisture_max && $pump->status !== 'off') {
                    $pump->update(['status' => 'off', 'mode' => 'auto']);
                    $this->createLog($greenhouseId, 'SYSTEM AUTOMATION', 'Pompa mati otomatis: Tanah lembab (' . $soilInput . '%)');
                }
            }

            // --- Logika Otomatisasi Kipas (Berdasarkan Suhu Udara) ---
            if ($fan) {
                if ($tempInput > $setting->temperature_max && $fan->status !== 'on') {
                    $fan->update(['status' => 'on', 'mode' => 'auto']);
                    $this->createLog($greenhouseId, 'SYSTEM AUTOMATION', 'Kipas menyala otomatis: Suhu panas (' . $tempInput . '°C)');
                } elseif ($tempInput <= $setting->temperature_min && $fan->status !== 'off') {
                    $fan->update(['status' => 'off', 'mode' => 'auto']);
                    $this->createLog($greenhouseId, 'SYSTEM AUTOMATION', 'Kipas mati otomatis: Suhu stabil (' . $tempInput . '°C)');
                }
            }

            // --- Logika Otomatisasi Lampu UV (Berdasarkan Intensitas Cahaya) ---
            if ($lamp) {
                if ($lightInput < $setting->light_min && $lamp->status !== 'on') {
                    $lamp->update(['status' => 'on', 'mode' => 'auto']);
                    $this->createLog($greenhouseId, 'SYSTEM AUTOMATION', 'Lampu UV menyala otomatis: Ruangan gelap (' . $lightInput . ' Lux)');
                } elseif ($lightInput >= $setting->light_max && $lamp->status !== 'off') {
                    $lamp->update(['status' => 'off', 'mode' => 'auto']);
                    $this->createLog($greenhouseId, 'SYSTEM AUTOMATION', 'Lampu UV mati otomatis: Cahaya cukup (' . $lightInput . ' Lux)');
                }
            }

            // Refresh model agar memuat status terbaru setelah di-update
            if ($pump) $pump->refresh();
            if ($fan)  $fan->refresh();
            if ($lamp) $lamp->refresh();
        }

        // 5. Kembalikan Response Gabungan (Status Alat + Batas Threshold Dinamis)
        return response()->json([
            'success'   => true,
            'mode'      => $setting->system_mode,
            'pump'      => $pump ? $pump->status : 'off',
            'fan'       => $fan ? $fan->status : 'off',
            'lamp'      => $lamp ? $lamp->status : 'off',
            
            // Mengirimkan ambang batas dinamis agar dibaca sebagai acuan alarm buzzer ESP32
            'soil_min'  => (int)$setting->soil_moisture_min,
            'soil_max'  => (int)$setting->soil_moisture_max,
            'temp_min'  => (float)$setting->temperature_min,
            'temp_max'  => (float)$setting->temperature_max,
            'light_min' => (int)$setting->light_min,
            'light_max' => (int)$setting->light_max
        ], 200);
    }

    /**
     * Endpoint GET: Digunakan jika hardware hanya ingin polling status tanpa mengirim data sensor
     */
    public function status($greenhouseId)
    {
        $greenhouse = Greenhouse::find($greenhouseId);
        if (!$greenhouse) {
            return response()->json(['success' => false, 'message' => 'Greenhouse tidak ditemukan'], 404);
        }

        $setting = Setting::firstOrCreate(['greenhouse_id' => $greenhouseId], [
            'system_mode' => 'Otomatis', 'soil_moisture_min' => 45, 'soil_moisture_max' => 70,
            'temperature_min' => 20, 'temperature_max' => 28, 'humidity_min' => 40, 'humidity_max' => 80,
            'light_min' => 300, 'light_max' => 800
        ]);

        $pump = Actuator::firstOrCreate(['greenhouse_id' => $greenhouseId, 'type' => 'pump'], ['name' => 'Pump', 'status' => 'off', 'mode' => 'manual']);
        $fan  = Actuator::firstOrCreate(['greenhouse_id' => $greenhouseId, 'type' => 'fan'], ['name' => 'Fan', 'status' => 'off', 'mode' => 'manual']);
        $lamp = Actuator::firstOrCreate(['greenhouse_id' => $greenhouseId, 'type' => 'lamp'], ['name' => 'Lamp', 'status' => 'off', 'mode' => 'manual']);

        return response()->json([
            'success' => true,
            'mode' => $setting->system_mode,
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
     * Endpoint untuk memproses request pembaruan manual dari halaman Web UI Dashboard
     */
    public function updateControl(Request $request, $greenhouseId)
    {
        if ($request->has('mode')) {
            Setting::where('greenhouse_id', $greenhouseId)->update(['system_mode' => $request->mode]);
        }
        if ($request->has('pump')) {
            Actuator::where('greenhouse_id', $greenhouseId)->where('type', 'pump')->update(['status' => $request->pump]);
        }
        if ($request->has('fan')) {
            Actuator::where('greenhouse_id', $greenhouseId)->where('type', 'fan')->update(['status' => $request->fan]);
        }
        if ($request->has('lamp')) {
            Actuator::where('greenhouse_id', $greenhouseId)->where('type', 'lamp')->update(['status' => $request->lamp]);
        }

        return response()->json(['success' => true, 'message' => 'Kontrol manual berhasil diperbarui'], 200);
    }

    /**
     * Helper internal: Membuat rekapan histori aktivitas log
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