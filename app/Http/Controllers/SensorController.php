<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sensor;
use App\Models\SensorData;
use App\Models\Setting;
use Carbon\Carbon;

class SensorController extends Controller
{
    // ======================================================
    // SENSOR LIST (Initial HTML View Render)
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

        // Ambil data koleksi sensor
        $sensors = Sensor::with(['latestData'])
            ->where('greenhouse_id', $greenhouse->id)
            ->orderBy('id')
            ->get();

        $setting = Setting::firstOrCreate(
            ['greenhouse_id' => $greenhouse->id],
            [
                'system_mode' => 'Otomatis',
                'soil_moisture_min' => 45, 'soil_moisture_max' => 70,
                'temperature_min' => 20,   'temperature_max' => 28,
                'humidity_min' => 40,      'humidity_max' => 80,
                'light_min' => 300,        'light_max' => 800
            ]
        );

        return view('sensors.index', [
            'user'       => $user,
            'greenhouse' => $greenhouse,
            'sensors'    => $sensors,
            'soilMin'    => $setting->soil_moisture_min,
            'soilMax'    => $setting->soil_moisture_max,
            'tempMin'    => $setting->temperature_min,
            'tempMax'    => $setting->temperature_max,
            'humMin'     => $setting->humidity_min,
            'humMax'     => $setting->humidity_max,
            'lightMin'   => $setting->light_min,
            'lightMax'   => $setting->light_max,
            'systemMode' => $setting->system_mode
        ]);
    }

    // ======================================================
    // PERBAIKAN: API UNTUK MENYUPLAI DATA REAL-TIME AJAX POLLING SENSOR
    // ======================================================
    public function realtimeStats()
    {
        $user = Auth::user();

        if (!$user || !$user->activeGreenhouse) {
            return response()->json(['error' => 'Unauthorized atau Greenhouse tidak aktif'], 401);
        }

        $greenhouse = $user->activeGreenhouse;

        // Ambil data sensor terbaru dari database
        $sensors = Sensor::with('latestData')
            ->where('greenhouse_id', $greenhouse->id)
            ->get()
            ->keyBy('type');

        // Cari stempel waktu data sensor terakhir masuk untuk indikator "Last Update"
        $lastLog = SensorData::whereIn('sensor_id', $sensors->pluck('id'))
            ->latest('recorded_at')
            ->first();

        $formattedTime = $lastLog 
            ? Carbon::parse($lastLog->recorded_at)->translatedFormat('d M Y • H:i:s') 
            : now()->translatedFormat('d M Y • H:i:s');

        $setting = Setting::where('greenhouse_id', $greenhouse->id)->first();

        // Output JSON murni yang dibaca langsung oleh Script fetch() di file Blade
        return response()->json([
            'soil'        => $sensors->get('soil')?->latestData?->value ?? 0,
            'temp'        => $sensors->get('temperature')?->latestData?->value ?? 0,
            'hum'         => $sensors->get('humidity')?->latestData?->value ?? 0,
            'light'       => $sensors->get('light')?->latestData?->value ?? 0,
            'mode'        => $setting?->system_mode ?? 'Otomatis',
            'last_update' => $formattedTime
        ]);
    }

    // ======================================================
    // REDIRECT DASHBOARD
    // ======================================================
    public function dashboard()
    {
        return redirect()->route('dashboard.index');
    }
}