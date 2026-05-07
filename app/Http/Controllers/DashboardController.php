<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\SensorData;
use App\Models\Actuator;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ======================
        // SENSOR
        // ======================
        $sensors = Sensor::with('latestData')->get();

        $map = $sensors->keyBy('type');

        $getLatest = function ($type) use ($map) {
            return optional(optional($map->get($type))->latestData)->value ?? 0;
        };

        $soil  = $getLatest('soil');
        $temp  = $getLatest('temperature');
        $hum   = $getLatest('humidity');
        $light = $getLatest('light');

        // ======================
        // CHART WEEKLY SOIL
        // ======================
        $soilWeekly = [];

        if (isset($map['soil'])) {
            $data = SensorData::where('sensor_id', $map['soil']->id)
                ->whereBetween('recorded_at', [
                    Carbon::now()->subDays(6)->startOfDay(),
                    Carbon::now()->endOfDay()
                ])
                ->orderBy('recorded_at')
                ->get()
                ->groupBy(fn($d) => Carbon::parse($d->recorded_at)->format('D'));

            $days = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];

            foreach ($days as $day) {
                $soilWeekly[] = round(
                    optional($data[$day] ?? collect())->avg('value') ?? 0
                );
            }
        } else {
            $soilWeekly = [0,0,0,0,0,0,0];
        }

        // ======================
        // ACTUATOR
        // ======================
        $actuators = Actuator::pluck('status', 'type');
        /*
        hasil:
        [
            'pump' => 'on',
            'fan'  => 'off',
            'lamp' => 'on'
        ]
        */

        // ======================
        // RETURN
        // ======================
        return view('dashboard.index', [
            'sensors' => $sensors,
            'soil' => $soil,
            'temp' => $temp,
            'hum' => $hum,
            'light' => $light,
            'soilWeekly' => $soilWeekly,
            'actuators' => $actuators
        ]);
    }
}