<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sensor;
use App\Models\SensorData;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    // ======================================================
    // ANALYTICS PAGE (Initial HTML View Load)
    // ======================================================
    public function index(Request $request)
    {
        $analytics = $this->getAnalyticsData($request);

        return view('grafik.index', $analytics);
    }

    // ======================================================
    // EXPORT PDF REPORT GENERATOR
    // ======================================================
    public function exportPdf(Request $request)
    {
        $analytics = $this->getAnalyticsData($request);

        $pdf = Pdf::loadView('grafik.pdf', $analytics)
            ->setPaper('a4', 'landscape');

        return $pdf->download('smartgrow-report-' . now()->format('Ymd-His') . '.pdf');
    }

    // ======================================================
    // PERBAIKAN CORE: API DATA UNTUK AJAX AUTOMATIC POLLING GRAPH & TABLE
    // ======================================================
    public function realtimeAnalytics(Request $request)
    {
        // Paksa request tanggal kosong khusus engine real-time agar selalu mengambil data teranyar saat ini
        $request->merge(['start_date' => null, 'end_date' => null]);
        
        $analytics = $this->getAnalyticsData($request);
        
        // Saring dan batasi hanya mengambil maksimal 50 data log sensor terbaru 
        // demi menjaga kestabilan memori RAM laptop saat grafik bergerak real-time
        $allLabels = $analytics['labels'] ?? collect();
        $totalItems = $allLabels->count();
        $sliceSize = 50;

        if ($totalItems > $sliceSize) {
            $analytics['labels'] = $allLabels->slice($totalItems - $sliceSize)->values();
            $analytics['temp']   = ($analytics['temp'] ?? collect())->slice($totalItems - $sliceSize)->values();
            $analytics['soil']   = ($analytics['soil'] ?? collect())->slice($totalItems - $sliceSize)->values();
            $analytics['hum']    = ($analytics['hum'] ?? collect())->slice($totalItems - $sliceSize)->values();
            $analytics['light']  = ($analytics['light'] ?? collect())->slice($totalItems - $sliceSize)->values();
        }

        // Mengembalikan data murni JSON dengan stempel waktu ISO presisi tinggi
        return response()->json([
            'labels' => $analytics['labels'],
            'temp'   => $analytics['temp'],
            'soil'   => $analytics['soil'],
            'hum'    => $analytics['hum'],
            'light'  => $analytics['light']
        ]);
    }

    // ======================================================
    // GET ANALYTICS DATA CORE ENGINE
    // ======================================================
    private function getAnalyticsData($request)
    {
        // ======================================================
        // USER LOGIN VALIDATION
        // ======================================================
        $user = Auth::user();

        if (!$user) {
            return [
                'greenhouse'   => null,
                'labels'       => collect(),
                'temp'         => collect(),
                'soil'         => collect(),
                'hum'          => collect(),
                'light'        => collect(),
                'filterInfo'   => 'User belum login',
                'startDate'    => null,
                'endDate'      => null
            ];
        }

        // ======================================================
        // ACTIVE GREENHOUSE CHECK
        // ======================================================
        $greenhouse = $user->activeGreenhouse;

        if (!$greenhouse) {
            return [
                'greenhouse'   => null,
                'labels'       => collect(),
                'temp'         => collect(),
                'soil'         => collect(),
                'hum'          => collect(),
                'light'        => collect(),
                'filterInfo'   => 'Greenhouse tidak ditemukan',
                'startDate'    => null,
                'endDate'      => null
            ];
        }

        // ======================================================
        // FILTER DATE PREPARATION
        // ======================================================
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        if ($startDate && !strtotime($startDate)) { $startDate = null; }
        if ($endDate && !strtotime($endDate)) { $endDate = null; }

        // ======================================================
        // PLUCK DICTIONARY SENSOR MAP
        // ======================================================
        $sensors = Sensor::where('greenhouse_id', $greenhouse->id)
            ->pluck('id', 'type');

        // ======================================================
        // CLOSURE FUNCTION TO FETCH AND GROUP DATA
        // ======================================================
        $getData = function ($type) use ($sensors, $startDate, $endDate) {
            
            if (!isset($sensors[$type])) {
                return collect();
            }

            $query = SensorData::where('sensor_id', $sensors[$type]);

            if ($startDate) {
                $query->where('recorded_at', '>=', Carbon::parse($startDate)->startOfDay());
            }

            if ($endDate) {
                $query->where('recorded_at', '<=', Carbon::parse($endDate)->endOfDay());
            }

            // Ambil data terbaru dari database
            $data = $query->orderBy('recorded_at', 'desc')
                ->take(500)
                ->get()
                ->reverse() 
                ->values();

            // MENGIRIM STRING WAKTU STANDAR ISO AGAR DATA DETIK RIIL TERBAWA KE JAVASCRIPT ENGINE
            return $data->groupBy(function ($d) {
                return Carbon::parse($d->recorded_at)->toIso8601String();
            });
        };

        // ======================================================
        // GET UNIQUE DATA FOR EACH SENSORS
        // ======================================================
        $tempData  = $getData('temperature');
        $soilData  = $getData('soil');
        $humData   = $getData('humidity');
        $lightData = $getData('light');

        // ======================================================
        // COMBINE AND MAP SENSOR VALUES TO ISO LABELS
        // ======================================================
        $allTimes = collect()
            ->merge($tempData->keys())
            ->merge($soilData->keys())
            ->merge($humData->keys())
            ->merge($lightData->keys())
            ->unique()
            ->sort()
            ->values();

        // ======================================================
        // NORMALIZE SENSOR DATA AVERAGES
        // ======================================================
        $normalize = function ($grouped, $allTimes) {
            return $allTimes->map(function ($time) use ($grouped) {
                return isset($grouped[$time])
                    ? round($grouped[$time]->avg('value'), 1)
                    : null;
            });
        };

        // ======================================================
        // GENERATE HUMAN READABLE FILTER INFO
        // ======================================================
        $filterInfo = 'Menampilkan Semua Riwayat Monitoring';

        if ($startDate && $endDate) {
            $filterInfo = 'Periode Data: ' . 
                Carbon::parse($startDate)->translatedFormat('d F Y') . ' s/d ' . 
                Carbon::parse($endDate)->translatedFormat('d F Y');
        } elseif ($startDate) {
            $filterInfo = 'Data Mulai Tanggal: ' . Carbon::parse($startDate)->translatedFormat('d F Y');
        } elseif ($endDate) {
            $filterInfo = 'Data Sampai Tanggal: ' . Carbon::parse($endDate)->translatedFormat('d F Y');
        }

        // ======================================================
        // RETURN CONTEXT DICTIONARY COMPONENT
        // ======================================================
        return [
            'greenhouse'   => $greenhouse,
            'labels'       => $allTimes,
            'temp'         => $normalize($tempData, $allTimes),
            'soil'         => $normalize($soilData, $allTimes),
            'hum'          => $normalize($humData, $allTimes),
            'light'        => $normalize($lightData, $allTimes),
            'filterInfo'   => $filterInfo,
            'startDate'    => $startDate,
            'endDate'      => $endDate
        ];
    }
}