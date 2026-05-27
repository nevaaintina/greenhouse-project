<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\SensorData;
use App\Models\Actuator;
use App\Models\Setting;
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

            return optional(
                optional($map->get($type))->latestData
            )->value ?? 0;
        };

        $soil  = $getLatest('soil');
        $temp  = $getLatest('temperature');
        $hum   = $getLatest('humidity');
        $light = $getLatest('light');

        // ======================
        // WEEKLY SOIL CHART
        // ======================

        $soilWeekly = [];

        if (isset($map['soil'])) {

            $data = SensorData::where(
                    'sensor_id',
                    $map['soil']->id
                )
                ->whereBetween('recorded_at', [

                    Carbon::now()
                        ->subDays(6)
                        ->startOfDay(),

                    Carbon::now()
                        ->endOfDay()
                ])
                ->orderBy('recorded_at')
                ->get()
                ->groupBy(fn($d) =>

                    Carbon::parse(
                        $d->recorded_at
                    )->format('D')
                );

            $days = [
                'Mon',
                'Tue',
                'Wed',
                'Thu',
                'Fri',
                'Sat',
                'Sun'
            ];

            foreach ($days as $day) {

                $soilWeekly[] = round(

                    optional(
                        $data[$day] ?? collect()
                    )->avg('value') ?? 0
                );
            }

        } else {

            $soilWeekly = [0,0,0,0,0,0,0];
        }

        // ======================
        // SETTING
        // ======================

        $setting = Setting::first();

        // 🔥 ENUM SETTINGS
        $mode = $setting->system_mode ?? 'Otomatis';

        // ======================
        // AMBANG BATAS
        // ======================

        $soilMin = $setting->soil_moisture_min ?? 45;
        $soilMax = $setting->soil_moisture_max ?? 70;

        $tempMin = $setting->temperature_min ?? 20;
        $tempMax = $setting->temperature_max ?? 28;

        $humMin = $setting->humidity_min ?? 40;
        $humMax = $setting->humidity_max ?? 80;

        $lightMin = $setting->light_min ?? 300;
        $lightMax = $setting->light_max ?? 800;

        // ======================
        // AUTO ACTUATOR
        // ======================

        // 🌱 PUMP
        $pumpAuto = $soil < $soilMin
            ? 'on'
            : 'off';

        // 🌡️ FAN
        $fanAuto = $temp > $tempMax
            ? 'on'
            : 'off';

        // 💡 LAMP
        $lampAuto = $light < $lightMin
            ? 'on'
            : 'off';

        // ======================
        // ACTUATOR DATABASE
        // ======================

        $pump = Actuator::firstOrCreate(

            [
                'greenhouse_id' => 1,
                'type' => 'pump'
            ],

            [
                'name' => 'Pompa Air',
                'status' => 'off',

                // 🔥 actuator enum
                'mode' => 'auto'
            ]
        );

        $fan = Actuator::firstOrCreate(

            [
                'greenhouse_id' => 1,
                'type' => 'fan'
            ],

            [
                'name' => 'Kipas',
                'status' => 'off',

                // 🔥 actuator enum
                'mode' => 'auto'
            ]
        );

        $lamp = Actuator::firstOrCreate(

            [
                'greenhouse_id' => 1,
                'type' => 'lamp'
            ],

            [
                'name' => 'Lampu UV',
                'status' => 'off',

                // 🔥 actuator enum
                'mode' => 'auto'
            ]
        );

        // ======================
        // MODE MANUAL
        // ======================

        if ($mode == 'Manual') {

            $actuators = [

                'pump' => $pump->status,

                'fan'  => $fan->status,

                'lamp' => $lamp->status
            ];
        }

        // ======================
        // MODE AUTO
        // ======================

        else {

            $pump->update([

                'status' => $pumpAuto,

                // 🔥 actuator enum
                'mode' => 'auto'
            ]);

            $fan->update([

                'status' => $fanAuto,

                // 🔥 actuator enum
                'mode' => 'auto'
            ]);

            $lamp->update([

                'status' => $lampAuto,

                // 🔥 actuator enum
                'mode' => 'auto'
            ]);

            $actuators = [

                'pump' => $pumpAuto,

                'fan'  => $fanAuto,

                'lamp' => $lampAuto
            ];
        }

        // ======================
        // RETURN VIEW
        // ======================

        return view('dashboard.index', [

            'sensors' => $sensors,

            'soil' => $soil,
            'temp' => $temp,
            'hum' => $hum,
            'light' => $light,

            'soilWeekly' => $soilWeekly,

            'actuators' => $actuators,

            'mode' => $mode,

            'soilMin' => $soilMin,
            'soilMax' => $soilMax,

            'tempMin' => $tempMin,
            'tempMax' => $tempMax,

            'humMin' => $humMin,
            'humMax' => $humMax,

            'lightMin' => $lightMin,
            'lightMax' => $lightMax
        ]);
    }
}