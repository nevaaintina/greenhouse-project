<?php

namespace App\Http\Controllers;

use App\Models\Sensor;

class DashboardController extends Controller
{
    public function index()
    {
        $sensors = Sensor::orderBy('id')->take(5)->get();

        return view('dashboard.index', compact('sensors'));
    }
}