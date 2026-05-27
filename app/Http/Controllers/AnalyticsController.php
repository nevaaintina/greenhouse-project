<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $analytics = $this->getAnalyticsData($request);

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
        $analytics = $this->getAnalyticsData($request);

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
        // FILTER
        // ======================================================

        $startDate = $request->start_date;

        $endDate = $request->end_date;


        // ======================================================
        // SENSOR MAP
        // ======================================================

        $sensors = Sensor::pluck(

            'id',
            'type'
        );


        // ======================================================
        // FUNCTION GET DATA
        // ======================================================

        $getData = function ($type) use (

            $sensors,
            $startDate,
            $endDate

        ) {

            if (!isset($sensors[$type]))
            {
                return collect();
            }

            $query = SensorData::where(

                'sensor_id',
                $sensors[$type]
            );


            // ======================================================
            // FILTER DATE RANGE
            // ======================================================

            if ($startDate && $endDate)
            {
                $query->whereBetween(

                    'recorded_at',

                    [
                        Carbon::parse($startDate)
                            ->startOfDay(),

                        Carbon::parse($endDate)
                            ->endOfDay()
                    ]
                );
            }


            // ======================================================
            // GET DATA
            // ======================================================

            $data = $query

                ->orderBy(

                    'recorded_at',
                    'asc'

                )->get();


            // ======================================================
            // GROUPING DATETIME
            // ======================================================

            return $data->groupBy(

                function ($d)
                {
                    return Carbon::parse(

                        $d->recorded_at

                    )->format('Y-m-d H:i');
                }
            );
        };


        // ======================================================
        // GET ALL SENSOR
        // ======================================================

        $tempData = $getData('temperature');

        $soilData = $getData('soil');

        $humData = $getData('humidity');

        $lightData = $getData('light');


        // ======================================================
        // COMBINE LABELS
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
        // NORMALIZE
        // ======================================================

        $normalize = function (

            $grouped,
            $allTimes

        ) {

            return $allTimes->map(

                function ($time) use ($grouped)
                {
                    return isset($grouped[$time])

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

                Carbon::parse($startDate)

                    ->translatedFormat('d F Y')

                . ' - ' .

                Carbon::parse($endDate)

                    ->translatedFormat('d F Y');
        }


        // ======================================================
        // RETURN
        // ======================================================

        return [

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