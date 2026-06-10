@php
use App\Models\Setting;
use App\Models\Actuator;
use Carbon\Carbon;

$user = auth()->user();
$greenhouse = $user?->activeGreenhouse;

$setting = null;
$actuatorList = collect();

if ($greenhouse) {
    $setting = Setting::where('greenhouse_id', $greenhouse->id)->first();
    $actuatorList = Actuator::where('greenhouse_id', $greenhouse->id)->get();
}

$mode = $setting?->system_mode ?? 'Otomatis';

$isRunning = $actuatorList->contains(function ($actuator) {
    return $actuator->status === 'on' || $actuator->status === true || $actuator->status === 1;
});

$espStatus = 'offline';

if ($greenhouse && $greenhouse->last_seen) {
    $lastSeenTime = Carbon::parse($greenhouse->last_seen);
    $detikSelisih = now()->timestamp - $lastSeenTime->timestamp;
    
    if ($detikSelisih >= 0 && $detikSelisih <= 90) {
        $espStatus = 'online';
    }
}
@endphp

<div class="md:hidden fixed top-4 left-4 z-50">
    <button id="sidebar-toggle" class="p-2 bg-forest text-white rounded-xl shadow-lg hover:bg-forest/90 transition flex items-center justify-center">
        <span id="toggle-icon" class="material-symbols-rounded text-2xl">menu</span>
    </button>
</div>

<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-30 hidden md:hidden transition-opacity duration-300 opacity-0"></div>

<aside id="sidebar"
class="w-64 bg-forest text-white flex flex-col p-6 shadow-2xl
fixed inset-y-0 left-0 z-40 -translate-x-full transition-transform duration-300 ease-in-out
md:translate-x-0 md:static md:h-auto">

    <div class="flex items-center justify-between mb-8 px-2">
        <div class="flex items-center gap-3">
            <span class="material-symbols-rounded text-emerald-400 text-3xl">
                potted_plant
            </span>
            <h1 class="font-semibold text-lg uppercase tracking-[0.1em] text-white/90">
                SmartGrow
            </h1>
        </div>
        <button id="sidebar-close" class="md:hidden text-white/60 hover:text-white transition">
            <span class="material-symbols-rounded">close</span>
        </button>
    </div>

    <nav class="flex-1 space-y-1">
        {{-- Dashboard --}}
        <a href="{{ url('dashboard') }}"
        class="flex items-center gap-3 p-2.5 rounded-xl transition text-sm duration-200
        {{ request()->is('dashboard') 
            ? 'bg-white/15 text-white font-semibold border-l-4 border-emerald-400 pl-1.5 opacity-100' 
            : 'opacity-60 text-white/90 hover:bg-white/5 hover:opacity-100' }}">
            <span class="material-symbols-rounded text-[20px]">dashboard</span>
            Dashboard
        </a>

        {{-- Sensors --}}
        <a href="{{ url('sensors') }}"
        class="flex items-center gap-3 p-2.5 rounded-xl transition text-sm duration-200
        {{ request()->is('sensors') 
            ? 'bg-white/15 text-white font-semibold border-l-4 border-emerald-400 pl-1.5 opacity-100' 
            : 'opacity-60 text-white/90 hover:bg-white/5 hover:opacity-100' }}">
            <span class="material-symbols-rounded text-[20px]">sensors</span>
            Sensors
        </a>

        {{-- Grafik & Riwayat --}}
        <a href="{{ url('grafik') }}"
        class="flex items-center gap-3 p-2.5 rounded-xl transition text-sm duration-200
        {{ request()->is('grafik') 
            ? 'bg-white/15 text-white font-semibold border-l-4 border-emerald-400 pl-1.5 opacity-100' 
            : 'opacity-60 text-white/90 hover:bg-white/5 hover:opacity-100' }}">
            <span class="material-symbols-rounded text-[20px]">show_chart</span>
            Grafik & Riwayat
        </a>

        {{-- Log Activity --}}
        <a href="{{ url('logs') }}"
        class="flex items-center gap-3 p-2.5 rounded-xl transition text-sm duration-200
        {{ request()->is('logs') 
            ? 'bg-white/15 text-white font-semibold border-l-4 border-emerald-400 pl-1.5 opacity-100' 
            : 'opacity-60 text-white/90 hover:bg-white/5 hover:opacity-100' }}">
            <span class="material-symbols-rounded text-[20px]">history</span>
            Log Activity
        </a>

        {{-- Profile --}}
        <a href="{{ url('profile') }}"
        class="flex items-center gap-3 p-2.5 rounded-xl transition text-sm duration-200
        {{ request()->is('profile') 
            ? 'bg-white/15 text-white font-semibold border-l-4 border-emerald-400 pl-1.5 opacity-100' 
            : 'opacity-60 text-white/90 hover:bg-white/5 hover:opacity-100' }}">
            <span class="material-symbols-rounded text-[20px]">person</span>
            Profile
        </a>

        {{-- Settings --}}
        <a href="{{ url('settings') }}"
        class="flex items-center gap-3 p-2.5 rounded-xl transition text-sm duration-200
        {{ request()->is('settings') 
            ? 'bg-white/15 text-white font-semibold border-l-4 border-emerald-400 pl-1.5 opacity-100' 
            : 'opacity-60 text-white/90 hover:bg-white/5 hover:opacity-100' }}">
            <span class="material-symbols-rounded text-[20px]">settings</span>
            Settings
        </a>
    </nav>

    <div class="mt-auto bg-black/20 p-4 rounded-3xl border border-white/10">
        <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest mb-3">
            System Status
        </p>

        <div class="space-y-3">
            {{-- Operation Mode Component --}}
            <div class="flex items-center justify-between text-[10px]">
                <span class="opacity-60 text-white font-medium">Operation Mode</span>
                <span id="sidebar-mode" class="px-2 py-0.5 rounded-md font-black italic border uppercase 
                    {{ $mode == 'Otomatis'
                        ? 'bg-emerald-400/20 text-emerald-400 border-emerald-400/20'
                        : 'bg-orange-400/20 text-orange-400 border-orange-400/20' }}">
                    {{ $mode }}
                </span>
            </div>

            {{-- Actuators Status Component --}}
            <div class="flex items-center justify-between text-[10px]">
                <span class="opacity-60 text-white font-medium">Actuators Status</span>
                <div id="sidebar-actuator-container" class="flex items-center gap-1.5 font-bold italic {{ $isRunning ? 'text-emerald-400' : 'text-gray-400' }}">
                    <div id="sidebar-actuator-dot" class="w-1.5 h-1.5 rounded-full {{ $isRunning ? 'bg-emerald-400 animate-pulse shadow-[0_0_8px_#34d399]' : 'bg-gray-400' }}"></div>
                    <span id="sidebar-actuator-text">{{ $isRunning ? 'Running' : 'Idle' }}</span>
                </div>
            </div>

            {{-- ESP32 Connection Component --}}
            <div class="pt-2 border-t border-white/5 flex items-center justify-between text-[9px]">
                <span class="opacity-40 text-white italic">ESP32 Connection</span>
                <div class="flex items-center gap-1.5">
                    <div id="sidebar-esp-dot" class="w-2 h-2 rounded-full {{ $espStatus == 'online' ? 'bg-emerald-400 animate-pulse shadow-[0_0_8px_#34d399]' : 'bg-red-400' }}"></div>
                    <span id="sidebar-esp-text" class="font-bold uppercase {{ $espStatus == 'online' ? 'text-emerald-400' : 'text-red-400' }}">
                        {{ $espStatus }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</aside>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // --- LOGIKA TOGGLE MOBILE ---
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebar-toggle');
    const closeBtn = document.getElementById('sidebar-close');
    const overlay = document.getElementById('sidebar-overlay');
    const toggleIcon = document.getElementById('toggle-icon');

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        setTimeout(() => {
            overlay.classList.add('opacity-100');
        }, 20);
        toggleIcon.textContent = 'close';
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.remove('opacity-100');
        toggleIcon.textContent = 'menu';
        setTimeout(() => {
            if (sidebar.classList.contains('-translate-x-full')) {
                overlay.classList.add('hidden');
            }
        }, 300);
    }

    toggleBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        if (sidebar.classList.contains('-translate-x-full')) {
            openSidebar();
        } else {
            closeSidebar();
        }
    });

    closeBtn.addEventListener('click', closeSidebar);
    overlay.addEventListener('click', closeSidebar);

    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            overlay.classList.add('hidden');
            overlay.classList.remove('opacity-100');
        }
    });

    // --- REALTIME DATA FETCHING ---
    const modeEl = document.getElementById('sidebar-mode');
    const actContainer = document.getElementById('sidebar-actuator-container');
    const actDot = document.getElementById('sidebar-actuator-dot');
    const actText = document.getElementById('sidebar-actuator-text');
    const espDot = document.getElementById('sidebar-esp-dot');
    const espText = document.getElementById('sidebar-esp-text');

    function updateSidebarRealtime() {
        fetch('/api/greenhouse/status')
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                // 1. Sinkronisasi Mode Operasional
                modeEl.textContent = data.mode;
                if (data.mode === 'Otomatis') {
                    modeEl.className = "px-2 py-0.5 rounded-md font-black italic border uppercase bg-emerald-400/20 text-emerald-400 border-emerald-400/20";
                } else {
                    modeEl.className = "px-2 py-0.5 rounded-md font-black italic border uppercase bg-orange-400/20 text-orange-400 border-orange-400/20";
                }

                // 2. Sinkronisasi Status Aktuator (Running / Idle)
                if (data.isRunning) {
                    actText.textContent = 'Running';
                    actContainer.className = "flex items-center gap-1.5 font-bold italic text-emerald-400";
                    actDot.className = "w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_8px_#34d399]";
                } else {
                    actText.textContent = 'Idle';
                    actContainer.className = "flex items-center gap-1.5 font-bold italic text-gray-400";
                    actDot.className = "w-1.5 h-1.5 rounded-full bg-gray-400";
                }

                // 3. Sinkronisasi Jantung Koneksi Hardware ESP32
                espText.textContent = data.espStatus.toUpperCase();
                if (data.espStatus === 'online') {
                    espText.className = "font-bold uppercase text-emerald-400";
                    espDot.className = "w-2 h-2 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_8px_#34d399]";
                } else {
                    espText.className = "font-bold uppercase text-red-400";
                    espDot.className = "w-2 h-2 rounded-full bg-red-400";
                }
            })
            .catch(error => console.error('Error fetching real-time sidebar status:', error));
    }

    setInterval(updateSidebarRealtime, 5000);
});
</script>