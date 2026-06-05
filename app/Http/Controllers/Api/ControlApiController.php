<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Greenhouse;
use App\Models\Setting;
use App\Models\Actuator;

class ControlApiController extends Controller
{
    public function status($greenhouseId)
    {
        $greenhouse = Greenhouse::find(
            $greenhouseId
        );

        if (!$greenhouse)
        {
            return response()->json([
                'success' => false,
                'message' => 'Greenhouse tidak ditemukan'
            ], 404);
        }

        $setting = Setting::firstOrCreate(

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

        $pump = Actuator::firstOrCreate(
            [
                'greenhouse_id' => $greenhouseId,
                'type' => 'pump'
            ],
            [
                'name' => 'Pump',
                'status' => 'off',
                'mode' => 'manual'
            ]
        );

        $fan = Actuator::firstOrCreate(
            [
                'greenhouse_id' => $greenhouseId,
                'type' => 'fan'
            ],
            [
                'name' => 'Fan',
                'status' => 'off',
                'mode' => 'manual'
            ]
        );

        $lamp = Actuator::firstOrCreate(
            [
                'greenhouse_id' => $greenhouseId,
                'type' => 'lamp'
            ],
            [
                'name' => 'Lamp',
                'status' => 'off',
                'mode' => 'manual'
            ]
        );

        return response()->json([

            'success' => true,

            'mode' =>
                $setting->system_mode,

            'pump' =>
                $pump->status === 'on',

            'fan' =>
                $fan->status === 'on',

            'lamp' =>
                $lamp->status === 'on',

            'soil_min' =>
                $setting->soil_moisture_min,

            'soil_max' =>
                $setting->soil_moisture_max,

            'temp_min' =>
                $setting->temperature_min,

            'temp_max' =>
                $setting->temperature_max,

            'humidity_min' =>
                $setting->humidity_min,

            'humidity_max' =>
                $setting->humidity_max,

            'light_min' =>
                $setting->light_min,

            'light_max' =>
                $setting->light_max
        ]);
    }
}