<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Sensor;
use App\Models\SensorData;

class SensorController extends Controller
{
    // halaman sensor list
    public function index()
    {
        $sensors = Sensor::with('latestData')->get();
        return view('sensors.index', compact('sensors'));
    }

    // dashboard utama
    public function dashboard()
    {
        $sensors = Sensor::with('latestData')
                    ->orderBy('id')
                    ->take(5)
                    ->get();

        return view('dashboard', compact('sensors'));
    }

    
}