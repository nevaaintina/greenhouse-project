@php
use App\Models\Setting;
use App\Models\Actuator;
use Carbon\Carbon;

// Fetch data dengan fail-safe check
$user = auth()->user();
$greenhouse = $user?->activeGreenhouse;

$setting = null;
$actuatorList = collect();

if ($greenhouse) {
    $setting = Setting::where('greenhouse_id', $greenhouse->id)->first();
    $actuatorList = Actuator::where('greenhouse_id', $greenhouse->id)->get();
}

$mode = $setting?->system_mode ?? 'Otomatis';

// Cek apakah ada minimal satu aktuator yang sedang aktif
$isRunning = $actuatorList->contains(function ($actuator) {
    return $actuator->status === 'on' || $actuator->status === true;
});

// =======================================================
// PERBAIKAN MUTLAK: PENENTUAN STATUS KONEKSI ESP32 VIA UNIX TIMESTAMP
// =======================================================
$espStatus = 'offline';

if ($greenhouse && $greenhouse->last_seen) {
    $lastSeenTime = Carbon::parse($greenhouse->last_seen);
    
    // Hitung selisih detik murni (Waktu Sekarang dikurangi Waktu Terakhir ESP Lapor)
    $detikSelisih = now()->timestamp - $lastSeenTime->timestamp;
    
    // Jika ESP lapor kurang dari 90 detik yang lalu, dan selisihnya logis (di atas 0 detik)
    if ($detikSelisih >= 0 && $detikSelisih <= 90) {
        $espStatus = 'online';
    }
}
@endphp

<aside id="sidebar"
class="w-64 bg-forest text-white flex flex-col p-6 shadow-2xl
fixed inset-y-0 left-0 z-40 -translate-x-full transition-transform duration-300
md:translate-x-0 md:static md:h-auto">

    <div class="flex items-center gap-3 mb-8 px-2">
        <span class="material-symbols-rounded text-emerald-400 text-3xl">
            potted_plant
        </span>
        <h1 class="font-semibold text-lg uppercase tracking-[0.1em] text-white/90">
            SmartGrow
        </h1>
    </div>

    <nav class="flex-1 space-y-1">
        <a href="/dashboard"
        class="flex items-center gap-3 {{ request()->is('dashboard') ? 'bg-white/10 opacity-100 font-medium' : 'opacity-60' }} p-2.5 rounded-xl hover:opacity-100 transition text-sm">
            <span class="material-symbols-rounded text-[20px]">dashboard</span>
            Dashboard
        </a>

        <a href="/sensors"
        class="flex items-center gap-3 {{ request()->is('sensors') ? 'bg-white/10 opacity-100 font-medium' : 'opacity-60' }} p-2.5 rounded-xl hover:opacity-100 transition text-sm">
            <span class="material-symbols-rounded text-[20px]">sensors</span>
            Sensors
        </a>

        <a href="/grafik"
        class="flex items-center gap-3 {{ request()->is('grafik') ? 'bg-white/10 opacity-100 font-medium' : 'opacity-60' }} p-2.5 rounded-xl hover:opacity-100 transition text-sm">
            <span class="material-symbols-rounded text-[20px]">show_chart</span>
            Grafik & Riwayat
        </a>

        <a href="/logs"
        class="flex items-center gap-3 {{ request()->is('logs') ? 'bg-white/10 opacity-100 font-medium' : 'opacity-60' }} p-2.5 rounded-xl hover:opacity-100 transition text-sm">
            <span class="material-symbols-rounded text-[20px]">history</span>
            Log Activity
        </a>

        <a href="/profile"
        class="flex items-center gap-3 {{ request()->is('profile*') ? 'bg-white/10 opacity-100 font-medium' : 'opacity-60' }} p-2.5 rounded-xl hover:opacity-100 transition text-sm">
            <span class="material-symbols-rounded text-[20px]">person</span>
            Profile
        </a>

        <a href="/settings"
        class="flex items-center gap-3 {{ request()->is('settings') ? 'bg-white/10 opacity-100 font-medium' : 'opacity-60' }} p-2.5 rounded-xl hover:opacity-100 transition text-sm">
            <span class="material-symbols-rounded text-[20px]">settings</span>
            Settings
        </a>
    </nav>

    <div class="mt-auto bg-black/20 p-4 rounded-3xl border border-white/10">
        <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest mb-3">
            System Status
        </p>

        <div class="space-y-3">
            <div class="flex items-center justify-between text-[10px]">
                <span class="opacity-60 text-white font-medium">Operation Mode</span>
                <span class="px-2 py-0.5 rounded-md font-black italic border uppercase 
                    {{ $mode == 'Otomatis'
                        ? 'bg-emerald-400/20 text-emerald-400 border-emerald-400/20'
                        : 'bg-orange-400/20 text-orange-400 border-orange-400/20' }}">
                    {{ $mode }}
                </span>
            </div>

            <div class="flex items-center justify-between text-[10px]">
                <span class="opacity-60 text-white font-medium">Actuators Status</span>
                <div class="flex items-center gap-1.5 font-bold italic {{ $isRunning ? 'text-emerald-400' : 'text-gray-400' }}">
                    <div class="w-1.5 h-1.5 rounded-full {{ $isRunning ? 'bg-emerald-400 animate-pulse shadow-[0_0_8px_#34d399]' : 'bg-gray-400' }}"></div>
                    {{ $isRunning ? 'Running' : 'Idle' }}
                </div>
            </div>

            <div class="pt-2 border-t border-white/5 flex items-center justify-between text-[9px]">
                <span class="opacity-40 text-white italic">ESP32 Connection</span>
                <div class="flex items-center gap-1.5">
                    <div class="w-2 h-2 rounded-full {{ $espStatus == 'online' ? 'bg-emerald-400 animate-pulse shadow-[0_0_8px_#34d399]' : 'bg-red-400' }}"></div>
                    <span class="font-bold uppercase {{ $espStatus == 'online' ? 'text-emerald-400' : 'text-red-400' }}">
                        {{ $espStatus }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</aside>