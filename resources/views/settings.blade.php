<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - SmartGrow Ultimate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script>
        tailwind.config = {
            theme: { extend: { colors: { 'forest': '#2D5A27', 'emerald-accent': '#2E7D32', 'soft-bg': '#F9FBFA' }, fontFamily: { sans: ['Poppins'] } } }
        }
    </script>
</head>
<body class="bg-soft-bg flex h-screen overflow-hidden font-sans">

    <div class="md:hidden fixed top-4 left-4 z-50">
        <button id="menuBtn" class="bg-forest text-white p-2.5 rounded-xl shadow-lg active:scale-90 transition-transform">
            <span id="menuIcon" class="material-symbols-rounded">menu</span>
        </button>
    </div>

    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-forest text-white flex flex-col p-6 shadow-2xl transition-transform duration-300 -translate-x-full md:translate-x-0 md:static md:flex h-screen">
        <div class="flex items-center gap-3 mb-8 px-2">
    <span class="material-symbols-rounded text-emerald-400 text-3xl">potted_plant</span>
    <h1 class="font-semibold text-lg uppercase tracking-[0.1em] text-white/90">SmartGrow</h1>
</div>
        
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

        <div class="mt-auto bg-black/20 p-4 rounded-3xl border border-white/10">
    <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest mb-3">System Status</p>
    <div class="space-y-3">
        <div class="flex items-center justify-between text-[10px]">
            <span class="opacity-60 text-white font-medium">Operation Mode</span>
            <span class="px-2 py-0.5 bg-emerald-400/20 text-emerald-400 rounded-md font-black italic border border-emerald-400/20">
                OTOMATIS
            </span>
        </div>

        <div class="flex items-center justify-between text-[10px]">
            <span class="opacity-60 text-white font-medium">Actuators Status</span>
            <div class="flex items-center gap-1.5">
                <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse shadow-[0_0_8px_#34d399]"></div>
                <span class="text-emerald-400 font-bold uppercase tracking-tighter italic">Running</span>
            </div>
        </div>

        <div class="pt-2 border-t border-white/5 flex items-center justify-between text-[9px]">
            <span class="opacity-40 text-white italic">ESP32 Connection</span>
            <span class="text-emerald-400/60 font-bold uppercase">Stable</span>
        </div>
    </div>
</div>
    </aside>

    <div id="overlay" class="fixed inset-0 bg-black/50 z-30 hidden transition-opacity"></div>

    <main class="flex-1 overflow-y-auto p-5 md:p-10 text-slate-700">
        <header class="mb-8 mt-12 md:mt-0">
            <h2 class="text-2xl md:text-3xl font-extrabold text-forest tracking-tight leading-tight">System Configuration</h2>
            <div class="flex items-center gap-2 mt-1">
                <span class="w-8 h-1 bg-emerald-400 rounded-full"></span>
                <p class="text-[10px] md:text-sm text-slate-400 font-bold uppercase tracking-widest">Atur Parameter Otomatisasi</p>
            </div>
        </header>

        <form action="/settings/update" method="POST" class="space-y-8 pb-10">
            @csrf
            
            <section class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <label class="relative flex items-center p-5 bg-white border-2 border-transparent rounded-[2rem] cursor-pointer shadow-sm hover:shadow-md transition-all has-[:checked]:border-forest has-[:checked]:bg-emerald-50/30 group">
                    <input type="radio" name="system_mode" value="auto" class="hidden" checked>
                    <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-rounded text-forest text-2xl">smart_toy</span>
                    </div>
                    <div>
                        <p class="text-sm font-black text-forest uppercase tracking-tight">Mode Otomatis</p>
                        <p class="text-[10px] text-slate-400 font-medium">Aktif berdasarkan data sensor</p>
                    </div>
                </label>

                <label class="relative flex items-center p-5 bg-white border-2 border-transparent rounded-[2rem] cursor-pointer shadow-sm hover:shadow-md transition-all has-[:checked]:border-forest has-[:checked]:bg-emerald-50/30 group">
                    <input type="radio" name="system_mode" value="manual" class="hidden">
                    <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-rounded text-slate-400 text-2xl">touch_app</span>
                    </div>
                    <div>
                        <p class="text-sm font-black text-slate-400 peer-checked:text-forest uppercase tracking-tight">Mode Manual</p>
                        <p class="text-[10px] text-slate-400 font-medium">Kontrol penuh via Dashboard</p>
                    </div>
                </label>
            </section>

            <section>
                <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-300 mb-5 px-1">Threshold Parameters</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-50">
                        <div class="flex justify-between items-center mb-6">
                            <span class="material-symbols-rounded text-orange-400 text-2xl">device_thermostat</span>
                            <span class="text-[9px] font-black text-slate-200 uppercase tracking-widest">Temp</span>
                        </div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase mb-2 block">Suhu Maks (°C)</label>
                        <input type="number" step="0.1" name="temp_max" value="30.0" class="w-full bg-soft-bg rounded-2xl p-4 text-xl font-black text-forest outline-none border-2 border-transparent focus:border-forest/20 transition-all">
                    </div>

                    <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-50">
                        <div class="flex justify-between items-center mb-6">
                            <span class="material-symbols-rounded text-blue-500 text-2xl">water_drop</span>
                            <span class="text-[9px] font-black text-slate-200 uppercase tracking-widest">Soil</span>
                        </div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase mb-2 block">Tanah Min (%)</label>
                        <input type="number" name="soil_min" value="40" class="w-full bg-soft-bg rounded-2xl p-4 text-xl font-black text-forest outline-none border-2 border-transparent focus:border-forest/20 transition-all">
                    </div>

                    <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-50">
                        <div class="flex justify-between items-center mb-6">
                            <span class="material-symbols-rounded text-emerald-500 text-2xl">humidity_mid</span>
                            <span class="text-[9px] font-black text-slate-200 uppercase tracking-widest">Hum</span>
                        </div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase mb-2 block">Udara Min (%)</label>
                        <input type="number" name="hum_min" value="50" class="w-full bg-soft-bg rounded-2xl p-4 text-xl font-black text-forest outline-none border-2 border-transparent focus:border-forest/20 transition-all">
                    </div>

                    <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-50">
                        <div class="flex justify-between items-center mb-6">
                            <span class="material-symbols-rounded text-yellow-500 text-2xl">wb_sunny</span>
                            <span class="text-[9px] font-black text-slate-200 uppercase tracking-widest">Light</span>
                        </div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase mb-2 block">Cahaya Min (Lux)</label>
                        <input type="number" name="light_min" value="500" class="w-full bg-soft-bg rounded-2xl p-4 text-xl font-black text-forest outline-none border-2 border-transparent focus:border-forest/20 transition-all">
                    </div>
                </div>
            </section>

            <section class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-[11px] text-slate-500 font-medium">
                    <p class="flex items-center gap-3 italic">
                        <span class="material-symbols-rounded text-emerald-400 text-lg">info</span>
                        Aktuasi hardware otomatis mengikuti ambang batas di atas.
                    </p>
                    <p class="flex items-center gap-3 italic md:justify-end">
                        <span class="material-symbols-rounded text-emerald-400 text-lg">sync_saved_locally</span>
                        Data disimpan dan disinkronkan secara real-time.
                    </p>
                </div>
            </section>

            <div class="flex justify-end">
                <button type="submit" class="w-full sm:w-auto bg-forest text-white px-10 py-5 rounded-2xl text-xs font-black hover:bg-emerald-900 transition-all shadow-xl shadow-forest/20 flex items-center justify-center gap-3 active:scale-95">
                    <span class="material-symbols-rounded text-xl">save_as</span>
                    UPDATE CONFIGURATION
                </button>
            </div>
        </form>
    </main>

    <script>
        // SCRIPT SIDEBAR MOBILE
        const menuBtn = document.getElementById('menuBtn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const menuIcon = document.getElementById('menuIcon');

        menuBtn.addEventListener('click', () => {
            const isOpen = sidebar.classList.contains('translate-x-0');
            if (isOpen) {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
                overlay.classList.add('hidden');
                menuIcon.innerText = 'menu';
            } else {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                overlay.classList.remove('hidden');
                menuIcon.innerText = 'close';
            }
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0');
            overlay.classList.add('hidden');
            menuIcon.innerText = 'menu';
        });
    </script>
</body>
</html>