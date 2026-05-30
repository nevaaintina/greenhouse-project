<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;
use Carbon\Carbon;

class LogController extends Controller
{
    // ======================================================
    // LOG PAGE
    // ======================================================

    public function index(Request $request)
    {
        // ======================================================
        // USER LOGIN
        // ======================================================

        $user = Auth::user()->fresh();

        // ======================================================
        // VALIDASI USER
        // ======================================================

        if (!$user)
        {
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Silakan login terlebih dahulu'
                );
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
        // FILTER INPUT
        // ======================================================

        $activity = trim(

            $request->activity
            ?? ''
        );

        $date = $request->date;

        // ======================================================
        // VALIDASI DATE
        // ======================================================

        if ($date && !strtotime($date))
        {
            $date = null;
        }

        // ======================================================
        // BASE QUERY
        // ======================================================

        $query = Log::where(

                'greenhouse_id',
                $greenhouse->id

            )->latest(

                'created_at'
            );

        // ======================================================
        // FILTER ACTIVITY
        // ======================================================

        if (!empty($activity))
        {
            $query->where(

                'activity',
                'like',
                '%' . $activity . '%'
            );
        }

        // ======================================================
        // FILTER DATE
        // ======================================================

        if ($date)
        {
            $query->whereDate(

                'created_at',
                Carbon::parse(
                    $date
                )
            );
        }

        // ======================================================
        // PAGINATION
        // ======================================================

        $logs = $query

            ->paginate(10)
            ->withQueryString();

        // ======================================================
        // EMPTY MESSAGE
        // ======================================================

        $emptyMessage =

            $logs->isEmpty()
            ? 'Belum ada aktivitas log'
            : null;

        // ======================================================
        // RETURN VIEW
        // ======================================================

        return view(

            'logs.index',

            [

                'logs' => $logs,
                'greenhouse' => $greenhouse,
                'emptyMessage' => $emptyMessage,
                'activity' => $activity,
                'date' => $date
            ]
        );
    }
}