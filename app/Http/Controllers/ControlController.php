<?php

namespace App\Http\Controllers;

use App\Models\Actuator;
use App\Models\Setting;
use App\Models\Log;
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
    // TOGGLE ACTUATOR DENGAN AJAX JSON RESPONSE
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
    // PERBAIKAN SINKRONISASI: CHANGE SYSTEM MODE DENGAN AJAX JSON RESPONSE
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
            $actuator = $this->actuator($greenhouse->id, $type);
            
            // PERBAIKAN: Biarkan 'status' tetap mempertahankan kondisi terakhir dari alat (tidak dipaksa 'off')
            // untuk mencegah tabrakan data (data override) dari request post sensor ESP32
            $actuator->update([
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
    // RESET NODE DENGAN AJAX JSON RESPONSE
    // ======================================================
    public function resetNode()
    {
        $greenhouse = $this->greenhouse();

        if (!$greenhouse) {
            return response()->json([
                'success' => false,
                'message' => 'Greenhouse aktif tidak ditemukan'
            ], 404);
        }

        Actuator::where('greenhouse_id', $greenhouse->id)->update([
            'status' => 'off',
            'mode' => 'auto'
        ]);

        $setting = $this->setting($greenhouse->id);
        $setting->update([
            'system_mode' => 'Otomatis'
        ]);

        $this->log(
            'RESET NODE',
            'Node greenhouse berhasil direset kembali ke default sistem'
        );

        return response()->json([
            'success' => true,
            'message' => 'Node greenhouse berhasil direset ke mode otomatis awal'
        ]);
    }

    // ======================================================
    // CONTROL BUTTONS MAPPER
    // ======================================================
    public function pump() { return $this->toggle('pump'); }
    public function fan()  { return $this->toggle('fan'); }
    public function lamp() { return $this->toggle('lamp'); }
}