<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sensor;
use App\Models\SensorData;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $date  = $request->date;
        $range = $request->range ?? 'daily';

        $sensors = Sensor::pluck('id', 'type');

        // =========================
        // FUNCTION AMBIL DATA
        // =========================
        $getData = function ($type) use ($sensors, $date, $range) {

            if (!isset($sensors[$type])) return collect();

            $query = SensorData::where('sensor_id', $sensors[$type]);

            // 🔥 FILTER
            if ($date) {
                if ($range == 'daily') {
                    $query->whereDate('recorded_at', $date);
                } 
                elseif ($range == 'weekly') {
                    $query->whereBetween('recorded_at', [
                        Carbon::parse($date)->startOfWeek(),
                        Carbon::parse($date)->endOfWeek()
                    ]);
                } 
                elseif ($range == 'monthly') {
                    $query->whereMonth('recorded_at', Carbon::parse($date)->month)
                          ->whereYear('recorded_at', Carbon::parse($date)->year);
                }
            }

            $data = $query->orderBy('recorded_at', 'asc')->get();

            // =========================
            // GROUPING (RAW DATABASE TIME)
            // =========================
            if ($range == 'daily') {
                return $data->groupBy(function($d){
                    return substr($d->recorded_at, 11, 8);
                });
            }

            if ($range == 'weekly') {
                return $data->groupBy(function($d){
                    return date('D', strtotime($d->recorded_at));
                });
            }

            if ($range == 'monthly') {
                return $data->groupBy(function($d){
                    return date('d', strtotime($d->recorded_at));
                });
            }

            return collect();
        };

        // =========================
        // AMBIL DATA SEMUA SENSOR
        // =========================
        $tempData  = $getData('temperature');
        $soilData  = $getData('soil');
        $humData   = $getData('humidity');
        $lightData = $getData('light');

        // =========================
        // 🔥 GABUNG SEMUA LABEL
        // =========================
        $allTimes = collect()
            ->merge($tempData->keys())
            ->merge($soilData->keys())
            ->merge($humData->keys())
            ->merge($lightData->keys())
            ->unique()
            ->sort()
            ->values();

        // =========================
        // NORMALISASI DATA
        // =========================
        $normalize = function ($grouped, $allTimes) {
            return $allTimes->map(function ($time) use ($grouped) {
                return isset($grouped[$time])
                    ? round($grouped[$time]->avg('value'), 2)
                    : null;
            });
        };

        // =========================
        // FILTER INFO
        // =========================
        $filterInfo = 'Semua Data';

        if ($date) {
            if ($range == 'daily') {
                $filterInfo = 'Harian - ' . Carbon::parse($date)->translatedFormat('d F Y');
            } elseif ($range == 'weekly') {
                $start = Carbon::parse($date)->startOfWeek();
                $end   = Carbon::parse($date)->endOfWeek();
                $filterInfo = 'Mingguan - ' . $start->translatedFormat('d M') . ' - ' . $end->translatedFormat('d M Y');
            } elseif ($range == 'monthly') {
                $filterInfo = 'Bulanan - ' . Carbon::parse($date)->translatedFormat('F Y');
            }
        }

        // =========================
        // RETURN VIEW
        // =========================
        return view('grafik.index', [
            'labels' => $allTimes,
            'temp'   => $normalize($tempData, $allTimes),
            'soil'   => $normalize($soilData, $allTimes),
            'hum'    => $normalize($humData, $allTimes),
            'light'  => $normalize($lightData, $allTimes),
            'filterInfo' => $filterInfo
        ]);
    }
}