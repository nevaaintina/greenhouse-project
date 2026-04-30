<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Log; // ✅ pindah ke atas
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first() ?? new Setting();

        return view('settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::first();

        if (!$setting) {
            $setting = new Setting();
            $setting->greenhouse_id = 1;
        }

        // 🔥 SYSTEM MODE
        $setting->system_mode = $request->system_mode == 'auto'
            ? 'Otomatis'
            : 'Manual';

        // 🔥 SUHU
        $setting->temperature_min = $request->temp_min;
        $setting->temperature_max = $request->temp_max;

        // 🔥 TANAH
        $setting->soil_moisture_min = $request->soil_min;
        $setting->soil_moisture_max = $request->soil_max;

        // 🔥 HUMIDITY
        $setting->humidity_min = $request->hum_min;
        $setting->humidity_max = $request->hum_max;

        // 🔥 CAHAYA
        $setting->light_min = $request->light_min;
        $setting->light_max = $request->light_max;

        $setting->save();

        // 🔥 LOG ACTIVITY (PINDAH KE DALAM METHOD)
       Log::create([
    'user_id' => auth()->id() ?? 1,
    'activity' => 'UPDATE SETTINGS',
    'description' => 'User mengubah pengaturan sistem',
    'created_at' => now() // 🔥 penting
]);

        return back()->with('success', 'Settings berhasil disimpan!');
    }
}