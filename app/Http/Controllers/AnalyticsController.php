<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\SensorData;

class AnalyticsController extends Controller
{
    public function index()
    {
        $tempSensor  = Sensor::where('type', 'temperature')->first();
        $soilSensor  = Sensor::where('type', 'soil')->first();
        $humSensor   = Sensor::where('type', 'humidity')->first();
        $lightSensor = Sensor::where('type', 'light')->first(); // 🔥 tambah ini

        if (!$tempSensor || !$soilSensor || !$humSensor || !$lightSensor) {
            return view('analytics', [
                'labels' => [],
                'temp' => [],
                'soil' => [],
                'hum'  => [],
                'light'=> []
            ]);
        }

        // ambil data terakhir
        $tempData = SensorData::where('sensor_id', $tempSensor->id)->latest()->take(7)->get()->reverse();
        $soilData = SensorData::where('sensor_id', $soilSensor->id)->latest()->take(7)->get()->reverse();
        $humData  = SensorData::where('sensor_id', $humSensor->id)->latest()->take(7)->get()->reverse();
        $lightData= SensorData::where('sensor_id', $lightSensor->id)->latest()->take(7)->get()->reverse(); // 🔥

        $labels = $tempData->map(fn($d) => date('H:i', strtotime($d->recorded_at)));

        return view('grafik.index', [
            'labels' => $labels,
            'temp'   => $tempData->pluck('value'),
            'soil'   => $soilData->pluck('value'),
            'hum'    => $humData->pluck('value'),
            'light'  => $lightData->pluck('value'), // 🔥
        ]);
    }
}