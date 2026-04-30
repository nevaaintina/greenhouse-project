<?php

namespace App\Http\Controllers;

use App\Models\Log;

class LogController extends Controller
{
    public function index()
    {
        $logs = Log::with('user')
                    ->latest()
                    ->get();

        return view('logs.index', compact('logs'));
    }
}