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
        // ======================================================
        // USER LOGIN
        // ======================================================

        $user = Auth::user()->fresh();

        // ======================================================
        // VALIDASI USER
        // ======================================================

        if (!$user)
        {
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Silakan login terlebih dahulu'
                );
        }

        // ======================================================
        // ACTIVE GREENHOUSE
        // ======================================================

        $greenhouse = $user->activeGreenhouse;

        // ======================================================
        // VALIDASI GREENHOUSE
        // ======================================================

        if (!$greenhouse)
        {
            return back()->with(

                'error',
                'Greenhouse aktif tidak ditemukan'
            );
        }

        // ======================================================
        // USER SETTINGS
        // ======================================================

        $setting = $this->setting(

            $greenhouse->id
        );

        // ======================================================
        // RETURN VIEW
        // ======================================================

        return view(

            'settings.index',

            [

                'setting' => $setting,
                'greenhouse' => $greenhouse
            ]
        );
    }

    // ======================================================
    // UPDATE SETTINGS
    // ======================================================

    public function update(Request $request)
    {
        // ======================================================
        // USER LOGIN
        // ======================================================

        $user = Auth::user()->fresh();

        // ======================================================
        // VALIDASI USER
        // ======================================================

        if (!$user)
        {
            return redirect()

                ->route('login')
                ->with(
                    'error',
                    'Silakan login terlebih dahulu'
                );
        }

        // ======================================================
        // ACTIVE GREENHOUSE
        // ======================================================

        $greenhouse = $user->activeGreenhouse;

        // ======================================================
        // VALIDASI GREENHOUSE
        // ======================================================

        if (!$greenhouse)
        {
            return back()->with(

                'error',
                'Greenhouse aktif tidak ditemukan'
            );
        }

        // ======================================================
        // VALIDATION
        // ======================================================

        $validated = $request->validate([

            // ======================================================
            // SOIL
            // ======================================================

            'soil_min' =>
                'required|numeric|min:0|max:100',

            'soil_max' =>
                'required|numeric|gt:soil_min|max:100',

            // ======================================================
            // TEMPERATURE
            // ======================================================

            'temp_min' =>
                'required|numeric',

            'temp_max' =>
                'required|numeric|gt:temp_min',

            // ======================================================
            // HUMIDITY
            // ======================================================

            'hum_min' =>
                'required|numeric|min:0|max:100',

            'hum_max' =>
                'required|numeric|gt:hum_min|max:100',

            // ======================================================
            // LIGHT
            // ======================================================

            'light_min' =>
                'required|numeric|min:0',

            'light_max' =>
                'required|numeric|gt:light_min',
        ]);

        // ======================================================
        // USER SETTINGS
        // ======================================================

        $setting = $this->setting(
            $greenhouse->id
        );

        // ======================================================
        // UPDATE SETTINGS
        // ======================================================

        $setting->update([

            // ======================================================
            // TEMPERATURE
            // ======================================================

            'temperature_min' =>
                $validated['temp_min'],

            'temperature_max' =>
                $validated['temp_max'],

            // ======================================================
            // SOIL
            // ======================================================

            'soil_moisture_min' =>
                $validated['soil_min'],

            'soil_moisture_max' =>
                $validated['soil_max'],

            // ======================================================
            // HUMIDITY
            // ======================================================

            'humidity_min' =>
                $validated['hum_min'],

            'humidity_max' =>
                $validated['hum_max'],

            // ======================================================
            // LIGHT
            // ======================================================

            'light_min' =>
                $validated['light_min'],

            'light_max' =>
                $validated['light_max'],
        ]);

        // ======================================================
        // LOG ACTIVITY
        // ======================================================

        Log::create([

            'user_id' =>
                $user->id,

            'greenhouse_id' =>
                $greenhouse->id,

            'activity' =>
                'UPDATE SETTINGS',

            'description' =>
                'User mengubah threshold sensor greenhouse',

            'created_at' =>
                now()
        ]);

        // ======================================================
        // RESPONSE
        // ======================================================

        return back()->with(

            'success',
            'Settings berhasil disimpan!'
        );
    }

    // ======================================================
    // DEFAULT USER SETTINGS
    // ======================================================

    private function setting($greenhouseId)
    {
        return Setting::firstOrCreate(

            [
                'greenhouse_id' =>
                    $greenhouseId
            ],

            [

                // ======================================================
                // MODE
                // ======================================================

                'system_mode' =>
                    'Otomatis',

                // ======================================================
                // TEMPERATURE
                // ======================================================

                'temperature_min' => 20,
                'temperature_max' => 28,

                // ======================================================
                // SOIL
                // ======================================================

                'soil_moisture_min' => 45,
                'soil_moisture_max' => 70,

                // ======================================================
                // HUMIDITY
                // ======================================================

                'humidity_min' => 40,
                'humidity_max' => 80,

                // ======================================================
                // LIGHT
                // ======================================================

                'light_min' => 300,
                'light_max' => 800
            ]
        );
    }
}