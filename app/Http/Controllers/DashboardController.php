<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\SensorData;
use App\Models\Actuator;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // ======================================================
    // DASHBOARD MAIN PAGE
    // ======================================================
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $greenhouse = $user->activeGreenhouse;

        if (!$greenhouse) {
            return back()->with('error', 'Greenhouse aktif tidak ditemukan');
        }

        // 1. Ambil Data Sensor Terakhir
        $sensors = Sensor::with('latestData')
            ->where('greenhouse_id', $greenhouse->id)
            ->get();

        $map = $sensors->keyBy('type');

        $getLatest = function ($type) use ($map) {
            return $map->get($type)?->latestData?->value ?? 0;
        };

        $soil  = $getLatest('soil');
        $temp  = $getLatest('temperature');
        $hum   = $getLatest('humidity');
        $light = $getLatest('light');

        // 2. Olah Grafik Mingguan Kelembapan Tanah (7 Hari Terakhir)
        $soilWeekly = [];
        $weeklyLabels = [];

        if (isset($map['soil'])) {
            $soilData = SensorData::where('sensor_id', $map['soil']->id)
                ->whereBetween('recorded_at', [
                    Carbon::now()->subDays(6)->startOfDay(),
                    Carbon::now()->endOfDay()
                ])
                ->latest('recorded_at')
                ->take(500)
                ->get()
                ->groupBy(function ($d) {
                    return Carbon::parse($d->recorded_at)->format('Y-m-d');
                });

            $days = collect();
            for ($i = 6; $i >= 0; $i--) {
                $days->push(Carbon::now()->subDays($i));
            }

            foreach ($days as $index => $day) {
                $key = $day->format('Y-m-d');
                $weeklyLabels[] = $day->translatedFormat('D');

                $soilWeekly[$index] = round(
                    optional($soilData->get($key) ?? collect())->avg('value') ?? 0
                );
            }
        }

        // 3. Ambil Threshold Pengaturan Atas & Bawah
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

        // 4. Ambil Status Aktuator Terkini
        $pump = Actuator::firstOrCreate(['greenhouse_id' => $greenhouse->id, 'type' => 'pump'], ['name' => 'Pompa Air', 'status' => 'off', 'mode' => 'auto']);
        $fan  = Actuator::firstOrCreate(['greenhouse_id' => $greenhouse->id, 'type' => 'fan'],  ['name' => 'Kipas', 'status' => 'off', 'mode' => 'auto']);
        $lamp = Actuator::firstOrCreate(['greenhouse_id' => $greenhouse->id, 'type' => 'lamp'], ['name' => 'Lampu UV', 'status' => 'off', 'mode' => 'auto']);

        return view('dashboard.index', [
            'greenhouse'   => $greenhouse,
            'sensors'      => $sensors,
            'soil'         => $soil,
            'temp'         => $temp,
            'hum'          => $hum,
            'light'        => $light,
            'soilWeekly'   => $soilWeekly,
            'weeklyLabels' => $weeklyLabels,
            'actuators'    => ['pump' => $pump->status, 'fan' => $fan->status, 'lamp' => $lamp->status],
            'mode'         => $setting->system_mode,
            'soilMin'      => $setting->soil_moisture_min,
            'soilMax'      => $setting->soil_moisture_max,
            'tempMin'      => $setting->temperature_min,
            'tempMax'      => $setting->temperature_max,
            'humMin'       => $setting->humidity_min,
            'humMax'       => $setting->humidity_max,
            'lightMin'     => $setting->light_min,
            'lightMax'     => $setting->light_max
        ]);
    }

    // ======================================================
    // API DATA REAL-TIME PENYUPLAI AJAX POLLING (TERINTEGRASI FULL ACTUATORS STATE)
    // ======================================================
    public function realtimeStats()
    {
        $user = Auth::user();

        if (!$user || !$user->activeGreenhouse) {
            return response()->json(['error' => 'Unauthorized / Greenhouse tidak aktif'], 401);
        }

        $greenhouse = $user->activeGreenhouse;

        // Ambil data sensor terbaru
        $sensors = Sensor::with('latestData')
            ->where('greenhouse_id', $greenhouse->id)
            ->get()
            ->keyBy('type');

        // Ambil stempel log update paling terakhir masuk ke DB (Lengkap data detik)
        $lastUpdateData = SensorData::whereIn('sensor_id', $sensors->pluck('id'))
            ->latest('recorded_at')
            ->first();

        $lastUpdateFormatted = $lastUpdateData 
            ? Carbon::parse($lastUpdateData->recorded_at)->translatedFormat('d M Y • H:i:s') 
            : now()->translatedFormat('d M Y • H:i:s');

        // Ambil status sakelar hardware terkini
        $pump = Actuator::where('greenhouse_id', $greenhouse->id)->where('type', 'pump')->first();
        $fan  = Actuator::where('greenhouse_id', $greenhouse->id)->where('type', 'fan')->first();
        $lamp = Actuator::where('greenhouse_id', $greenhouse->id)->where('type', 'lamp')->first();
        $setting = Setting::where('greenhouse_id', $greenhouse->id)->first();

        // Mengembalikan struktur payload JSON utuh yang dinantikan oleh JavaScript Dashboard
        return response()->json([
            'soil'        => $sensors->get('soil')?->latestData?->value ?? 0,
            'temp'        => $sensors->get('temperature')?->latestData?->value ?? 0,
            'hum'         => $sensors->get('humidity')?->latestData?->value ?? 0,
            'light'       => $sensors->get('light')?->latestData?->value ?? 0,
            'mode'        => $setting?->system_mode ?? 'Otomatis',
            'last_update' => $lastUpdateFormatted,
            'actuators'   => [
                'pump' => $pump?->status ?? 'off',
                'fan'  => $fan?->status ?? 'off',
                'lamp' => $lamp?->status ?? 'off'
            ]
        ]);
    }

    // ======================================================
    // PERBAIKAN: API UNTUK MENDUKUNG STATUS REALTIME PADA SIDEBAR
    // ======================================================
    public function sidebarStatus()
    {
        $user = Auth::user();

        if (!$user || !$user->activeGreenhouse) {
            return response()->json([
                'mode' => 'Otomatis',
                'isRunning' => false,
                'espStatus' => 'offline'
            ]);
        }

        $greenhouse = $user->activeGreenhouse;

        // 1. Ambil status skema manajemen mode sistem
        $setting = Setting::where('greenhouse_id', $greenhouse->id)->first();
        $mode = $setting?->system_mode ?? 'Otomatis';

        // 2. Ambil semua list aktuator untuk mengecek status running/active
        $actuatorList = Actuator::where('greenhouse_id', $greenhouse->id)->get();
        $isRunning = $actuatorList->contains(function ($actuator) {
            return $actuator->status === 'on' || $actuator->status === true || $actuator->status === 1;
        });

        // 3. Pengecekan UNIX Timestamp detak jantung konektivitas hardware ESP32
        $espStatus = 'offline';
        if ($greenhouse->last_seen) {
            $lastSeenTime = Carbon::parse($greenhouse->last_seen);
            $detikSelisih = now()->timestamp - $lastSeenTime->timestamp;
            
            // Fail-safe check jika detak jantung ESP dilaporkan di bawah interval 90 detik
            if ($detikSelisih >= 0 && $detikSelisih <= 90) {
                $espStatus = 'online';
            }
        }

        return response()->json([
            'mode' => $mode,
            'isRunning' => $isRunning,
            'espStatus' => $espStatus
        ]);
    }
}