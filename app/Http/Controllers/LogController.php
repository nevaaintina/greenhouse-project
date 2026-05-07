<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    public function index(Request $request)
    {
        // =========================
        // BASE QUERY (USER LOGIN)
        // =========================
        $query = Log::where('user_id', Auth::id())
                    ->latest();

        // =========================
        // 🔍 FILTER ACTIVITY
        // =========================
        if ($request->filled('activity')) {
            $query->where('activity', 'like', '%' . $request->activity . '%');
        }

        // =========================
        // 📅 FILTER TANGGAL
        // =========================
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // =========================
        // PAGINATION
        // =========================
        $logs = $query->paginate(10)->withQueryString();

        return view('logs.index', compact('logs'));
    }
}