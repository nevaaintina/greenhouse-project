<aside id="sidebar"
class="w-64 bg-forest text-white flex flex-col p-6 shadow-2xl
fixed inset-y-0 left-0 z-40 -translate-x-full transition-transform duration-300
md:translate-x-0 md:static md:h-auto">

    <!-- Logo -->
    <div class="flex items-center gap-3 mb-8 px-2">
        <span class="material-symbols-rounded text-emerald-400 text-3xl">potted_plant</span>
        <h1 class="font-semibold text-lg uppercase tracking-[0.1em] text-white/90">SmartGrow</h1>
    </div>
    
    <!-- Menu -->
    <nav class="flex-1 space-y-1">
        <a href="/dashboard" class="flex items-center gap-3 {{ request()->is('dashboard') ? 'bg-white/10' : 'opacity-60' }} p-2.5 rounded-xl hover:opacity-100 transition text-sm">
            <span class="material-symbols-rounded text-[20px]">dashboard</span> Dashboard
        </a>
        <a href="/sensors" class="flex items-center gap-3 {{ request()->is('sensors') ? 'bg-white/10' : 'opacity-60' }} p-2.5 rounded-xl hover:opacity-100 transition text-sm">
            <span class="material-symbols-rounded text-[20px]">sensors</span> Sensors
        </a>
        <a href="/grafik" class="flex items-center gap-3 {{ request()->is('grafik') ? 'bg-white/10' : 'opacity-60' }} p-2.5 rounded-xl hover:opacity-100 transition text-sm">
            <span class="material-symbols-rounded text-[20px]">show_chart</span> Grafik & Riwayat
        </a>
        <a href="/logs" class="flex items-center gap-3 {{ request()->is('logs') ? 'bg-white/10' : 'opacity-60' }} p-2.5 rounded-xl hover:opacity-100 transition text-sm">
            <span class="material-symbols-rounded text-[20px]">history</span> Log Activity
        </a>
        <a href="/profile" class="flex items-center gap-3 {{ request()->is('profile*') ? 'bg-white/10' : 'opacity-60' }} p-2.5 rounded-xl hover:opacity-100 transition text-sm">
            <span class="material-symbols-rounded text-[20px]">person</span> Profile
        </a>
        <a href="/settings" class="flex items-center gap-3 {{ request()->is('settings') ? 'bg-white/10' : 'opacity-60' }} p-2.5 rounded-xl hover:opacity-100 transition text-sm">
            <span class="material-symbols-rounded text-[20px]">settings</span> Pengaturan
        </a>
    </nav>

    <!-- System Status -->
    <div class="mt-auto bg-black/20 p-4 rounded-3xl border border-white/10">
        <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest mb-3">
            System Status
        </p>

        <div class="space-y-3">
            <div class="flex items-center justify-between text-[10px]">
                <span class="opacity-60 text-white font-medium">Operation Mode</span>
                <span class="px-2 py-0.5 bg-emerald-400/20 text-emerald-400 rounded-md font-black italic border border-emerald-400/20 uppercase">
                    Otomatis
                </span>
            </div>

            <div class="flex items-center justify-between text-[10px]">
                <span class="opacity-60 text-white font-medium">Actuators Status</span>
                <div class="flex items-center gap-1.5 font-bold text-emerald-400 italic">
                    <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse shadow-[0_0_8px_#34d399]"></div>
                    Running
                </div>
            </div>

            <div class="pt-2 border-t border-white/5 flex items-center justify-between text-[9px]">
                <span class="opacity-40 text-white italic">ESP32 Connection</span>
                <span class="text-emerald-400/60 font-bold uppercase">
                    Stable
                </span>
            </div>
        </div>
    </div>

</aside>