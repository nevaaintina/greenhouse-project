<?php

namespace App\Http\Controllers;

use App\Models\Actuator;
use App\Models\Setting;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ControlController extends Controller
{
    // ======================================================
    // GET ACTIVE GREENHOUSE
    // ======================================================
    private function greenhouse()
    {
        $user = Auth::user()->fresh();

        if (!$user) {
            return null;
        }

        return $user->activeGreenhouse;
    }

    // ======================================================
    // GET USER SETTING (Pola Pull: Mengandalkan Sinkronisasi DB)
    // ======================================================
    private function setting($greenhouseId)
    {
        return Setting::firstOrCreate(
            [
                'greenhouse_id' => $greenhouseId
            ],
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
    }

    // ======================================================
    // GET ACTUATOR
    // ======================================================
    private function actuator($greenhouseId, $type)
    {
        return Actuator::firstOrCreate(
            [
                'greenhouse_id' => $greenhouseId,
                'type' => $type
            ],
            [
                'name' => ucfirst($type),
                'status' => 'off',
                'mode' => 'manual'
            ]
        );
    }

    // ======================================================
    // LOG ACTIVITY
    // ======================================================
    private function log($activity, $description)
    {
        $greenhouse = $this->greenhouse();

        if (!$greenhouse) {
            return;
        }

        Log::create([
            'user_id' => auth()->id(),
            'greenhouse_id' => $greenhouse->id,
            'activity' => $activity,
            'description' => $description,
            'created_at' => now()
        ]);
    }

    // ======================================================
    // API ENDPOINT: MENANGANI LOGIKA OTOMATISASI DARI HARDWARE ESP32
    // ======================================================
    public function receiveSensorData(Request $request)
    {
        // Ambil data greenhouse aktif
        $greenhouse = $this->greenhouse();
        if (!$greenhouse) {
            return response()->json([
                'success' => false,
                'message' => 'Greenhouse aktif tidak ditemukan'
            ], 404);
        }

        // Request input data sensor dari ESP32
        $soilInput  = $request->input('soil');
        $tempInput  = $request->input('temp');
        $lightInput = $request->input('light');
        $humInput   = $request->input('hum'); // Opsional jika ingin digunakan nanti

        $setting = $this->setting($greenhouse->id);

        // Eksekusi Logika Otomatisasi hanya jika Mode Sistem = 'Otomatis'
        if ($setting->system_mode === 'Otomatis') {
            
            // 1. Logika Otomatisasi Pompa Air (Berdasarkan Kelembapan Tanah)
            $pump = $this->actuator($greenhouse->id, 'pump');
            if ($soilInput < $setting->soil_moisture_min && $pump->status !== 'on') {
                $pump->update(['status' => 'on', 'mode' => 'auto']);
                $this->log('SYSTEM AUTOMATION', 'Pompa dinyalakan otomatis: Kelembapan tanah ' . $soilInput . '% kurang dari batas min (' . $setting->soil_moisture_min . '%)');
            } elseif ($soilInput >= $setting->soil_moisture_max && $pump->status !== 'off') {
                $pump->update(['status' => 'off', 'mode' => 'auto']);
                $this->log('SYSTEM AUTOMATION', 'Pompa dimatikan otomatis: Kelembapan tanah ' . $soilInput . '% sudah mencapai batas max (' . $setting->soil_moisture_max . '%)');
            }

            // 2. Logika Otomatisasi Kipas (Berdasarkan Suhu Udara)
            $fan = $this->actuator($greenhouse->id, 'fan');
            if ($tempInput > $setting->temperature_max && $fan->status !== 'on') {
                $fan->update(['status' => 'on', 'mode' => 'auto']);
                $this->log('SYSTEM AUTOMATION', 'Kipas dinyalakan otomatis: Suhu udara ' . $tempInput . '°C melebihi batas max (' . $setting->temperature_max . '°C)');
            } elseif ($tempInput <= $setting->temperature_min && $fan->status !== 'off') {
                $fan->update(['status' => 'off', 'mode' => 'auto']);
                $this->log('SYSTEM AUTOMATION', 'Kipas dimatikan otomatis: Suhu udara ' . $tempInput . '°C sudah turun ke batas min (' . $setting->temperature_min . '°C)');
            }

            // 3. Logika Otomatisasi Lampu UV (Berdasarkan Intensitas Cahaya / LDR)
            $lamp = $this->actuator($greenhouse->id, 'lamp');
            if ($lightInput < $setting->light_min && $lamp->status !== 'on') {
                $lamp->update(['status' => 'on', 'mode' => 'auto']);
                $this->log('SYSTEM AUTOMATION', 'Lampu UV dinyalakan otomatis: Intensitas cahaya ' . $lightInput . ' Lux kurang dari batas min (' . $setting->light_min . ' Lux)');
            } elseif ($lightInput >= $setting->light_max && $lamp->status !== 'off') {
                $lamp->update(['status' => 'off', 'mode' => 'auto']);
                $this->log('SYSTEM AUTOMATION', 'Lampu UV dimatikan otomatis: Intensitas cahaya ' . $lightInput . ' Lux sudah terpenuhi batas max (' . $setting->light_max . ' Lux)');
            }
        }

        // Return status aktuator final (baik dalam mode manual maupun otomatis) agar dibaca oleh ESP32
        return response()->json([
            'success'     => true,
            'system_mode' => $setting->system_mode,
            'actuators'   => [
                'pump' => $this->actuator($greenhouse->id, 'pump')->status,
                'fan'  => $this->actuator($greenhouse->id, 'fan')->status,
                'lamp' => $this->actuator($greenhouse->id, 'lamp')->status,
            ]
        ]);
    }

    // ======================================================
    // TOGGLE ACTUATOR DENGAN AJAX JSON RESPONSE (UNTUK WEB MANUAL)
    // ======================================================
    private function toggle($type)
    {
        $greenhouse = $this->greenhouse();

        if (!$greenhouse) {
            return response()->json([
                'success' => false,
                'message' => 'Greenhouse aktif tidak ditemukan'
            ], 404);
        }

        if (!in_array($type, ['pump', 'fan', 'lamp'])) {
            return response()->json([
                'success' => false,
                'message' => 'Actuator tidak valid'
            ], 400);
        }

        $setting = $this->setting($greenhouse->id);

        // Validasi perlindungan agar tidak bisa asal klik saat mode Otomatis berjalan
        if ($setting->system_mode === 'Otomatis') {
            return response()->json([
                'success' => false,
                'message' => 'Mode otomatis sedang aktif! Ubah ke mode manual terlebih dahulu.'
            ], 422);
        }

        $actuator = $this->actuator($greenhouse->id, $type);

        // Balikkan status lokal database
        $newStatus = $actuator->status === 'on' ? 'off' : 'on';

        $actuator->update([
            'status' => $newStatus,
            'mode' => 'manual'
        ]);

        // LOGGING
        $this->log(
            strtoupper($type) . ' CONTROL',
            ucfirst($type) . ' diubah menjadi ' . strtoupper($newStatus) . ' melalui Website'
        );

        return response()->json([
            'success' => true,
            'message' => ucfirst($type) . ' berhasil diubah ke ' . strtoupper($newStatus),
            'type' => $type,
            'status' => $newStatus
        ]);
    }

    // ======================================================
    // CHANGE SYSTEM MODE DENGAN AJAX JSON RESPONSE
    // ======================================================
    public function changeMode($mode)
    {
        $greenhouse = $this->greenhouse();
        if (!$greenhouse) {
            return response()->json([
                'success' => false,
                'message' => 'Greenhouse aktif tidak ditemukan'
            ], 404);
        }

        if (!in_array($mode, ['Manual', 'Otomatis'])) {
            return response()->json([
                'success' => false,
                'message' => 'Mode tidak valid'
            ], 400);
        }

        $setting = $this->setting($greenhouse->id);
        $setting->update([
            'system_mode' => $mode
        ]);

        $types = ['pump', 'fan', 'lamp'];

        foreach ($types as $type) {
            $this->actuator($greenhouse->id, $type)->update([
                'mode' => $mode === 'Manual' ? 'manual' : 'auto'
            ]);
        }

        $this->log(
            'MODE CHANGE',
            'System mode berhasil diubah ke ' . strtoupper($mode)
        );

        return response()->json([
            'success' => true,
            'message' => 'Mode berhasil diubah ke ' . strtoupper($mode),
            'mode' => $mode
        ]);
    }

    // ======================================================
    // CONTROL BUTTONS MAPPER
    // ======================================================
    public function pump() { return $this->toggle('pump'); }
    public function fan()  { return $this->toggle('fan'); }
    public function lamp() { return $this->toggle('lamp'); }
}