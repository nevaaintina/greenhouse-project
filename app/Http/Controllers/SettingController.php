<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Log;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    // =========================
    // SETTINGS PAGE
    // =========================
    public function index()
    {
        $setting = Setting::first() ?? new Setting();

        return view(
            'settings.index',
            compact('setting')
        );
    }

    // =========================
    // UPDATE SETTINGS
    // =========================
    public function update(Request $request)
    {
        // =========================
        // VALIDATION
        // =========================

        $request->validate([

            'soil_min'  => 'required|numeric',
            'soil_max'  => 'required|numeric',

            'temp_min'  => 'required|numeric',
            'temp_max'  => 'required|numeric',

            'hum_min'   => 'required|numeric',
            'hum_max'   => 'required|numeric',

            'light_min' => 'required|numeric',
            'light_max' => 'required|numeric',
        ]);

        // =========================
        // AMBIL SETTING
        // =========================

        $setting = Setting::first();

        // kalau belum ada
        if (!$setting) {

            $setting = new Setting();

            $setting->greenhouse_id = 1;

            // default mode
            $setting->system_mode = 'Otomatis';
        }

        // =========================
        // TEMPERATURE
        // =========================

        $setting->temperature_min =
            $request->temp_min;

        $setting->temperature_max =
            $request->temp_max;

        // =========================
        // SOIL
        // =========================

        $setting->soil_moisture_min =
            $request->soil_min;

        $setting->soil_moisture_max =
            $request->soil_max;

        // =========================
        // HUMIDITY
        // =========================

        $setting->humidity_min =
            $request->hum_min;

        $setting->humidity_max =
            $request->hum_max;

        // =========================
        // LIGHT
        // =========================

        $setting->light_min =
            $request->light_min;

        $setting->light_max =
            $request->light_max;

        // =========================
        // SAVE
        // =========================

        $setting->save();

        // =========================
        // LOG ACTIVITY
        // =========================

        Log::create([

            'user_id' =>
                auth()->id() ?? 1,

            'activity' =>
                'UPDATE SETTINGS',

            'description' =>
                'User mengubah threshold sensor greenhouse',

            'created_at' =>
                now()
        ]);

        // =========================
        // REDIRECT
        // =========================

        return back()->with(

            'success',

            'Settings berhasil disimpan!'
        );
    }
}