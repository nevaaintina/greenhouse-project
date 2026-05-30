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
    // ANALYTICS PAGE
    // ======================================================

    public function index(Request $request)
    {
        $analytics = $this->getAnalyticsData(
            $request
        );

        return view(
            'grafik.index',
            $analytics
        );
    }

    // ======================================================
    // EXPORT PDF
    // ======================================================

    public function exportPdf(Request $request)
    {
        $analytics = $this->getAnalyticsData(
            $request
        );

        $pdf = Pdf::loadView(
            'grafik.pdf',
            $analytics

        )->setPaper(
            'a4',
            'landscape'
        );

        return $pdf->download(
            'smartgrow-report.pdf'
        );
    }

    // ======================================================
    // GET ANALYTICS DATA
    // ======================================================

    private function getAnalyticsData($request)
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
            return [
                'greenhouse' => null,
                'labels' => collect(),
                'temp' => collect(),
                'soil' => collect(),
                'hum' => collect(),
                'light' => collect(),
                'filterInfo' => 'User belum login',
                'startDate' => null,
                'endDate' => null
            ];
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
            return [
                'greenhouse' => null,
                'labels' => collect(),
                'temp' => collect(),
                'soil' => collect(),
                'hum' => collect(),
                'light' => collect(),
                'filterInfo' => 'Greenhouse tidak ditemukan',
                'startDate' => null,
                'endDate' => null
            ];
        }

        // ======================================================
        // FILTER DATE
        // ======================================================

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // ======================================================
        // VALIDASI DATE
        // ======================================================

        if ($startDate && !strtotime($startDate))
        {
            $startDate = null;
        }

        if ($endDate && !strtotime($endDate))
        {
            $endDate = null;
        }

        // ======================================================
        // SENSOR USER
        // ======================================================

        $sensors = Sensor::where(
                'greenhouse_id',
                $greenhouse->id

            )->pluck(
                'id',
                'type'
            );

        // ======================================================
        // FUNCTION GET DATA
        // ======================================================

        $getData = function (
            $type

        ) use (
            $sensors,
            $startDate,
            $endDate
        ) {

            // ======================================================
            // VALIDASI SENSOR
            // ======================================================

            if (!isset($sensors[$type]))
            {
                return collect();
            }

            // ======================================================
            // QUERY
            // ======================================================

            $query = SensorData::where(
                'sensor_id',
                $sensors[$type]
            );

            // ======================================================
            // FILTER START DATE
            // ======================================================

            if ($startDate)
            {
                $query->where(
                    'recorded_at',
                    '>=',
                    Carbon::parse(
                        $startDate
                    )->startOfDay()
                );
            }

            // ======================================================
            // FILTER END DATE
            // ======================================================

            if ($endDate)
            {
                $query->where(
                    'recorded_at',
                    '<=',
                    Carbon::parse(
                        $endDate
                    )->endOfDay()
                );
            }

            // ======================================================
            // GET DATA
            // ======================================================

            $data = $query
                ->orderBy(
                    'recorded_at',
                    'asc'
                )

                ->take(500)
                ->get();

            // ======================================================
            // GROUP DATETIME
            // ======================================================

            return $data->groupBy(

                function ($d)
                {
                    return Carbon::parse(
                        $d->recorded_at
                    )->format(

                        'Y-m-d H:i'
                    );
                }
            );
        };

        // ======================================================
        // GET SENSOR DATA
        // ======================================================

        $tempData = $getData(

            'temperature'
        );

        $soilData = $getData(
            'soil'
        );

        $humData = $getData(
            'humidity'
        );

        $lightData = $getData(
            'light'
        );

        // ======================================================
        // COMBINE LABELS
        // ======================================================

        $allTimes = collect()

            ->merge(
                $tempData->keys()
            )

            ->merge(
                $soilData->keys()
            )

            ->merge(
                $humData->keys()
            )

            ->merge(
                $lightData->keys()
            )

            ->unique()
            ->sort()
            ->values();

        // ======================================================
        // NORMALIZE
        // ======================================================

        $normalize = function (

            $grouped,
            $allTimes

        ) {

            return $allTimes->map(
                function ($time)
                use (
                    $grouped
                ) {
                    return isset(
                        $grouped[$time]
                    )

                    ? round(
                        $grouped[$time]
                            ->avg('value'),
                        2
                    )

                    : null;
                }
            );
        };

        // ======================================================
        // FILTER INFO
        // ======================================================

        $filterInfo = 'Semua Data';

        if ($startDate && $endDate)
        {
            $filterInfo =
                Carbon::parse(
                    $startDate

                )->translatedFormat(
                    'd F Y'
                )

                . ' - ' .

                Carbon::parse(
                    $endDate

                )->translatedFormat(
                    'd F Y'
                );
        }

        elseif ($startDate)
        {
            $filterInfo =
                'Mulai '
                .
                Carbon::parse(
                    $startDate
                )->translatedFormat(
                    'd F Y'
                );
        }

        elseif ($endDate)
        {
            $filterInfo =
                'Sampai '
                .
                Carbon::parse(
                    $endDate
                )->translatedFormat(
                    'd F Y'
                );
        }

        // ======================================================
        // RETURN
        // ======================================================

        return [

            'greenhouse' => $greenhouse,
            'labels' => $allTimes,
            'temp' => $normalize(
                $tempData,
                $allTimes
            ),

            'soil' => $normalize(
                $soilData,
                $allTimes
            ),

            'hum' => $normalize(
                $humData,
                $allTimes
            ),

            'light' => $normalize(
                $lightData,
                $allTimes
            ),

            'filterInfo' => $filterInfo,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];
    }
}