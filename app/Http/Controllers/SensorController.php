<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sensor;
use App\Models\Setting;

class SensorController extends Controller
{
    // ======================================================
    // SENSOR LIST
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
        // SENSOR USER
        // ======================================================

        $sensors = Sensor::with([

                'latestData'

            ])->where(

                'greenhouse_id',
                $greenhouse->id

            )->orderBy(

                'id'

            )->get();

        // ======================================================
        // USER SETTINGS
        // ======================================================

        $setting = Setting::firstOrCreate(

            [
                'greenhouse_id' =>
                    $greenhouse->id
            ],

            [

                'system_mode' =>
                    'Otomatis',

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

        // ======================================================
        // RETURN VIEW
        // ======================================================

        return view(

            'sensors.index',

            [

                'user' => $user,
                'greenhouse' => $greenhouse,
                'sensors' => $sensors,

                // ======================================================
                // SOIL
                // ======================================================

                'soilMin' =>
                    $setting->soil_moisture_min,

                'soilMax' =>
                    $setting->soil_moisture_max,

                // ======================================================
                // TEMPERATURE
                // ======================================================

                'tempMin' =>
                    $setting->temperature_min,

                'tempMax' =>
                    $setting->temperature_max,

                // ======================================================
                // HUMIDITY
                // ======================================================

                'humMin' =>
                    $setting->humidity_min,

                'humMax' =>
                    $setting->humidity_max,

                // ======================================================
                // LIGHT
                // ======================================================

                'lightMin' =>
                    $setting->light_min,

                'lightMax' =>
                    $setting->light_max,

                // ======================================================
                // MODE
                // ======================================================

                'systemMode' =>
                    $setting->system_mode
            ]
        );
    }

    // ======================================================
    // REDIRECT DASHBOARD
    // ======================================================

    public function dashboard()
    {
        return redirect()

            ->route(

                'dashboard'
            );
    }
}