@php

use App\Models\Setting;
use App\Models\Actuator;

// ======================
// USER & GREENHOUSE
// ======================

$user = auth()->user();

$greenhouse = $user?->activeGreenhouse;

// ======================
// SETTING
// ======================

$setting = null;

if ($greenhouse)
{
    $setting = Setting::where(

        'greenhouse_id',

        $greenhouse->id

    )->first();
}

// ======================
// MODE
// ======================

$mode =

    $setting?->system_mode

    ?? 'Otomatis';

// ======================
// ACTUATOR
// ======================

$actuatorList = collect();

if ($greenhouse)
{
    $actuatorList = Actuator::where(

        'greenhouse_id',

        $greenhouse->id

    )->get();
}

// ======================
// ACTUATOR STATUS
// ======================

$isRunning = $actuatorList->contains(

    function ($actuator)
    {
        return $actuator->status === 'on';
    }
);

// ======================
// ESP STATUS
// ======================

$espStatus = 'offline';

if (
    $greenhouse &&
    $greenhouse->last_seen &&
    now()->diffInSeconds(

        $greenhouse->last_seen

    ) <= 60
)
{
    $espStatus = 'online';
}

@endphp

<aside id="sidebar"
class="w-64 bg-forest text-white flex flex-col p-6 shadow-2xl
fixed inset-y-0 left-0 z-40 -translate-x-full transition-transform duration-300
md:translate-x-0 md:static md:h-auto">

    <!-- LOGO -->
    <div class="flex items-center gap-3 mb-8 px-2">

        <span class="material-symbols-rounded text-emerald-400 text-3xl">
            potted_plant
        </span>

        <h1 class="font-semibold text-lg uppercase tracking-[0.1em] text-white/90">
            SmartGrow
        </h1>

    </div>

    <!-- MENU -->
    <nav class="flex-1 space-y-1">

        <a href="/dashboard"
        class="flex items-center gap-3
        {{ request()->is('dashboard')
            ? 'bg-white/10'
            : 'opacity-60' }}
        p-2.5 rounded-xl hover:opacity-100 transition text-sm">

            <span class="material-symbols-rounded text-[20px]">
                dashboard
            </span>

            Dashboard

        </a>

        <a href="/sensors"
        class="flex items-center gap-3
        {{ request()->is('sensors')
            ? 'bg-white/10'
            : 'opacity-60' }}
        p-2.5 rounded-xl hover:opacity-100 transition text-sm">

            <span class="material-symbols-rounded text-[20px]">
                sensors
            </span>

            Sensors

        </a>

        <a href="/grafik"
        class="flex items-center gap-3
        {{ request()->is('grafik')
            ? 'bg-white/10'
            : 'opacity-60' }}
        p-2.5 rounded-xl hover:opacity-100 transition text-sm">

            <span class="material-symbols-rounded text-[20px]">
                show_chart
            </span>

            Grafik & Riwayat

        </a>

        <a href="/logs"
        class="flex items-center gap-3
        {{ request()->is('logs')
            ? 'bg-white/10'
            : 'opacity-60' }}
        p-2.5 rounded-xl hover:opacity-100 transition text-sm">

            <span class="material-symbols-rounded text-[20px]">
                history
            </span>

            Log Activity

        </a>

        <a href="/profile"
        class="flex items-center gap-3
        {{ request()->is('profile*')
            ? 'bg-white/10'
            : 'opacity-60' }}
        p-2.5 rounded-xl hover:opacity-100 transition text-sm">

            <span class="material-symbols-rounded text-[20px]">
                person
            </span>

            Profile

        </a>

        <a href="/settings"
        class="flex items-center gap-3
        {{ request()->is('settings')
            ? 'bg-white/10'
            : 'opacity-60' }}
        p-2.5 rounded-xl hover:opacity-100 transition text-sm">

            <span class="material-symbols-rounded text-[20px]">
                settings
            </span>

            Settings

        </a>

    </nav>

    <!-- SYSTEM STATUS -->
    <div class="mt-auto bg-black/20 p-4 rounded-3xl border border-white/10">

        <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest mb-3">
            System Status
        </p>

        <div class="space-y-3">

            <!-- MODE -->
            <div class="flex items-center justify-between text-[10px]">

                <span class="opacity-60 text-white font-medium">
                    Operation Mode
                </span>

                <span class="px-2 py-0.5 rounded-md font-black italic border uppercase

                    {{ $mode == 'Otomatis'
                        ? 'bg-emerald-400/20 text-emerald-400 border-emerald-400/20'
                        : 'bg-orange-400/20 text-orange-400 border-orange-400/20' }}">

                    {{ $mode }}

                </span>

            </div>

            <!-- ACTUATOR STATUS -->
            <div class="flex items-center justify-between text-[10px]">

                <span class="opacity-60 text-white font-medium">
                    Actuators Status
                </span>

                <div class="flex items-center gap-1.5 font-bold italic

                    {{ $isRunning
                        ? 'text-emerald-400'
                        : 'text-gray-400' }}">

                    <div class="w-1.5 h-1.5 rounded-full

                        {{ $isRunning
                            ? 'bg-emerald-400 animate-pulse shadow-[0_0_8px_#34d399]'
                            : 'bg-gray-400' }}">
                    </div>

                    {{ $isRunning ? 'Running' : 'Idle' }}

                </div>

            </div>
<!-- ESP STATUS -->
<div class="pt-2 border-t border-white/5 flex items-center justify-between text-[9px]">

    <span class="opacity-40 text-white italic">

        ESP32 Connection

    </span>

    <div class="flex items-center gap-1.5">

        <div class="w-2 h-2 rounded-full

            {{ $espStatus == 'online'
                ? 'bg-emerald-400 animate-pulse shadow-[0_0_8px_#34d399]'
                : 'bg-red-400'
            }}">
        </div>

        <span class="font-bold uppercase

            {{ $espStatus == 'online'
                ? 'text-emerald-400'
                : 'text-red-400'
            }}">

            {{ $espStatus }}

        </span>

    </div>

</div>

        </div>

    </div>

</aside>