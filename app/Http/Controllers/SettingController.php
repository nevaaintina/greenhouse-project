<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    // ======================================================
    // SETTINGS PAGE
    // ======================================================
    public function index()
    {
        $user = Auth::user()->fresh();

        if (!$user) {
            return redirect()
                ->route('login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        $greenhouse = $user->activeGreenhouse;

        if (!$greenhouse) {
            return back()->with('error', 'Greenhouse aktif tidak ditemukan');
        }

        $setting = $this->setting($greenhouse->id);

        return view('settings.index', [
            'setting' => $setting,
            'greenhouse' => $greenhouse
        ]);
    }

    // ======================================================
    // UPDATE SETTINGS
    // ======================================================
    public function update(Request $request)
    {
        $user = Auth::user()->fresh();

        if (!$user) {
            return redirect()
                ->route('login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        $greenhouse = $user->activeGreenhouse;

        if (!$greenhouse) {
            return back()->with('error', 'Greenhouse aktif tidak ditemukan');
        }

        // Ambil atau buat data setting terlebih dahulu untuk menjamin datanya eksis
        $setting = $this->setting($greenhouse->id);

        // Jalankan Validasi Form Request
        $validated = $request->validate([
            'soil_min'  => 'required|numeric|min:0|max:100',
            'soil_max'  => 'required|numeric|gt:soil_min|max:100',
            
            'temp_min'  => 'required|numeric',
            'temp_max'  => 'required|numeric|gt:temp_min',
            
            'hum_min'   => 'required|numeric|min:0|max:100',
            'hum_max'   => 'required|numeric|gt:hum_min|max:100',
            
            'light_min' => 'required|numeric|min:0',
            'light_max' => 'required|numeric|gt:light_min',
        ]);

        // Update data setting berdasarkan data yang lolos validasi
        $setting->update([
            'temperature_min'   => $validated['temp_min'],
            'temperature_max'   => $validated['temp_max'],
            'soil_moisture_min' => $validated['soil_min'],
            'soil_moisture_max' => $validated['soil_max'],
            'humidity_min'      => $validated['hum_min'],
            'humidity_max'      => $validated['hum_max'],
            'light_min'         => $validated['light_min'],
            'light_max'         => $validated['light_max'],
        ]);

        // Catat Aktivitas ke Log
        Log::create([
            'user_id'       => $user->id,
            'greenhouse_id' => $greenhouse->id,
            'activity'      => 'UPDATE SETTINGS',
            'description'   => 'User mengubah threshold sensor greenhouse',
            'created_at'    => now()
        ]);

        return back()->with('success', 'Settings berhasil disimpan!');
    }

    // ======================================================
    // DEFAULT USER SETTINGS (Fail-safe)
    // ======================================================
    private function setting($greenhouseId)
    {
        return Setting::firstOrCreate(
            [
                'greenhouse_id' => $greenhouseId
            ],
            [
                'system_mode'       => 'Otomatis',
                'temperature_min'   => 20,
                'temperature_max'   => 28,
                'soil_moisture_min' => 45,
                'soil_moisture_max' => 70,
                'humidity_min'      => 40,
                'humidity_max'      => 80,
                'light_min'         => 300,
                'light_max'         => 800
            ]
        );
    }
}