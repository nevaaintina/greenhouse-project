<?php

namespace App\Http\Controllers;
use App\Models\Sensor;
use App\Models\SensorData;
use App\Models\Actuator;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // ======================================================
    // DASHBOARD
    // ======================================================

    public function index()
    {
        // ======================================================
        // USER LOGIN
        // ======================================================

        $user = Auth::user();

        // ======================================================
        // VALIDASI USER
        // ======================================================

        if (!$user)
        {
            return redirect()
                ->route('login');
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

        $sensors = Sensor::with(

                'latestData'

            )->where(

                'greenhouse_id',
                $greenhouse->id

            )->get();

        // ======================================================
        // SENSOR MAP
        // ======================================================

        $map = $sensors->keyBy(
            'type'
        );

        // ======================================================
        // GET LATEST VALUE
        // ======================================================

        $getLatest = function ($type) use ($map)
        {
            return $map
                ->get($type)
                ?->latestData
                ?->value ?? 0;
        };

        // ======================================================
        // SENSOR VALUE
        // ======================================================

        $soil = $getLatest(
            'soil'
        );

        $temp = $getLatest(
            'temperature'
        );

        $hum = $getLatest(
            'humidity'
        );

        $light = $getLatest(
            'light'
        );

        // ======================================================
        // WEEKLY SOIL CHART
        // ======================================================

        $soilWeekly = [];
        $weeklyLabels = [];

        // ======================================================
        // SOIL SENSOR
        // ======================================================

        if (isset($map['soil']))
        {
            $soilData = SensorData::where(

                    'sensor_id',
                    $map['soil']->id

                )->whereBetween(

                    'recorded_at',

                    [

                        Carbon::now()
                            ->subDays(6)
                            ->startOfDay(),

                        Carbon::now()
                            ->endOfDay()
                    ]

                )->latest(
                    'recorded_at'

                )->take(
                    500

                )->get()

                ->groupBy(function ($d)
                {
                    return Carbon::parse(
                        $d->recorded_at

                    )->format(
                        'Y-m-d'
                    );
                });

            // ======================================================
            // LAST 7 DAYS
            // ======================================================

            $days = collect();

            for ($i = 6; $i >= 0; $i--)
            {
                $days->push(
                    Carbon::now()
                        ->subDays($i)
                );
            }

            // ======================================================
            // BUILD WEEKLY DATA
            // ======================================================

            foreach (

                $days as $index => $day

            ) {

                // ======================================================
                // DATE KEY
                // ======================================================

                $key = $day->format(
                    'Y-m-d'
                );

                // ======================================================
                // LABEL HARI
                // ======================================================

                $weeklyLabels[] = $day

                    ->translatedFormat('D');

                // ======================================================
                // WEEKLY VALUE
                // ======================================================

                $soilWeekly[$index] = round(

                    optional(
                        $soilData[$key]
                        ?? collect()

                    )->avg(

                        'value'
                    ) ?? 0
                );
            }
        }

        // ======================================================
        // SETTING
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
        // MODE
        // ======================================================

        $mode = $setting->system_mode;

        // ======================================================
        // THRESHOLD
        // ======================================================

        $soilMin =
            $setting->soil_moisture_min;

        $soilMax =
            $setting->soil_moisture_max;

        $tempMin =
            $setting->temperature_min;

        $tempMax =
            $setting->temperature_max;

        $humMin =
            $setting->humidity_min;

        $humMax =
            $setting->humidity_max;

        $lightMin =
            $setting->light_min;

        $lightMax =
            $setting->light_max;

        // ======================================================
        // AUTO STATUS
        // ======================================================

        $pumpAuto =
            $soil > 0 && $soil < $soilMin
            ? 'on'
            : 'off';

        $fanAuto =
            $temp > 0 && $temp > $tempMax
            ? 'on'
            : 'off';

        $lampAuto =
            $light > 0 && $light < $lightMin
            ? 'on'
            : 'off';

        // ======================================================
        // ACTUATORS
        // ======================================================

        $pump = Actuator::firstOrCreate(

            [
                'greenhouse_id' =>
                    $greenhouse->id,
                'type' => 'pump'
            ],

            [
                'name' => 'Pompa Air',
                'status' => 'off',
                'mode' => 'auto'
            ]
        );

        $fan = Actuator::firstOrCreate(

            [
                'greenhouse_id' =>
                    $greenhouse->id,
                'type' => 'fan'
            ],

            [
                'name' => 'Kipas',
                'status' => 'off',
                'mode' => 'auto'
            ]
        );

        $lamp = Actuator::firstOrCreate(

            [
                'greenhouse_id' =>
                    $greenhouse->id,
                'type' => 'lamp'
            ],

            [
                'name' => 'Lampu UV',
                'status' => 'off',
                'mode' => 'auto'
            ]
        );

        // ======================================================
        // MODE MANUAL
        // ======================================================

        if ($mode == 'Manual')
        {
            $actuators = [

                'pump' => $pump->status,
                'fan' => $fan->status,
                'lamp' => $lamp->status
            ];
        }

        // ======================================================
        // MODE AUTO
        // ======================================================

        else
        {
            // ======================================================
            // PUMP
            // ======================================================

            if (

                $pump->status != $pumpAuto
                ||
                $pump->mode != 'auto'
            ) {

                $pump->update([
                    'status' => $pumpAuto,
                    'mode' => 'auto'
                ]);
            }

            // ======================================================
            // FAN
            // ======================================================

            if (

                $fan->status != $fanAuto
                ||
                $fan->mode != 'auto'
            ) {

                $fan->update([
                    'status' => $fanAuto,
                    'mode' => 'auto'
                ]);
            }

            // ======================================================
            // LAMP
            // ======================================================

            if (

                $lamp->status != $lampAuto
                ||
                $lamp->mode != 'auto'
            ) {

                $lamp->update([
                    'status' => $lampAuto,
                    'mode' => 'auto'
                ]);
            }

            // ======================================================
            // STATUS
            // ======================================================

            $actuators = [

                'pump' => $pumpAuto,
                'fan' => $fanAuto,
                'lamp' => $lampAuto
            ];
        }

        // ======================================================
        // RETURN VIEW
        // ======================================================

        return view(

            'dashboard.index',

            [

                'greenhouse' => $greenhouse,
                'sensors' => $sensors,
                'soil' => $soil,
                'temp' => $temp,
                'hum' => $hum,
                'light' => $light,
                'soilWeekly' => $soilWeekly,
                'weeklyLabels' => $weeklyLabels,
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
            ]
        );
    }
}