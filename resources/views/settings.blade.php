<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - SmartGrow Slim</title>
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

    <aside class="w-64 bg-forest text-white flex flex-col p-6 shadow-2xl z-20">
    <div class="flex items-center gap-3 mb-10 px-2">
        <span class="material-symbols-rounded text-emerald-400 text-3xl">potted_plant</span>
        <h1 class="font-bold text-xl uppercase tracking-tighter">SmartGrow</h1>
    </div>
    
    <nav class="flex-1 space-y-2">
        <a href="/dashboard" class="flex items-center gap-4 {{ request()->is('dashboard') ? 'bg-white/10' : 'opacity-60' }} p-3 rounded-2xl hover:opacity-100 transition">
            <span class="material-symbols-rounded">dashboard</span> Dashboard
        </a>
        
        <a href="/sensors" class="flex items-center gap-4 {{ request()->is('sensors') ? 'bg-white/10' : 'opacity-60' }} p-3 rounded-2xl hover:opacity-100 transition">
            <span class="material-symbols-rounded">sensors</span> Sensors
        </a>
        
        <a href="/grafik" class="flex items-center gap-4 {{ request()->is('grafik') ? 'bg-white/10' : 'opacity-60' }} p-3 rounded-2xl hover:opacity-100 transition">
            <span class="material-symbols-rounded">show_chart</span> Grafik & Riwayat
        </a>
        
        <a href="/logs" class="flex items-center gap-4 {{ request()->is('logs') ? 'bg-white/10' : 'opacity-60' }} p-3 rounded-2xl hover:opacity-100 transition">
            <span class="material-symbols-rounded">history</span> Log Activity
        </a>

        <a href="/settings" class="flex items-center gap-4 {{ request()->is('settings') ? 'bg-white/10' : 'opacity-60' }} p-3 rounded-2xl hover:opacity-100 transition">
            <span class="material-symbols-rounded">settings</span> Pengaturan
        </a>
    </nav>

    <div class="mt-auto bg-black/20 p-4 rounded-3xl border border-white/10">
        <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest mb-3">System Health</p>
        <div class="space-y-2">
            <div class="flex items-center justify-between text-[10px]">
                <span class="opacity-60">Server</span>
                <span class="flex items-center gap-1">
                    <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></div> Online
                </span>
            </div>
            <div class="flex items-center justify-between text-[10px]">
                <span class="opacity-60">Node 01</span>
                <span class="text-emerald-400 font-semibold">Connected</span>
            </div>
            <div class="flex items-center justify-between text-[10px]">
                <span class="opacity-60">Node 02</span>
                <span class="text-emerald-400 font-semibold">Connected</span>
            </div>
        </div>
    </div>
</aside>

    <main class="flex-1 overflow-y-auto p-8 text-slate-700">
        <header class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-extrabold text-forest tracking-tight">System Configuration</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Atur Parameter Otomatisasi</p>
            </div>
            <div class="flex items-center gap-3 bg-white px-3 py-1.5 rounded-xl shadow-sm border border-slate-100">
                <div class="text-right leading-none">
                    <p class="text-[10px] font-black text-forest uppercase">NEVA</p>
                    <p class="text-[8px] text-slate-400 font-bold mt-1 uppercase tracking-tighter">Admin</p>
                </div>
                <div class="w-8 h-8 bg-forest rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-md shadow-forest/20">N</div>
            </div>
        </header>

        <form action="/settings/update" method="POST" class="space-y-6">
            @csrf
            
            <section>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex items-center p-4 bg-white border-2 border-transparent rounded-3xl cursor-pointer shadow-sm hover:shadow-md transition-all has-[:checked]:border-forest has-[:checked]:bg-emerald-50/30">
                        <input type="radio" name="system_mode" value="auto" class="hidden peer" checked>
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center mr-4">
                            <span class="material-symbols-rounded text-forest text-2xl">smart_toy</span>
                        </div>
                        <div class="leading-tight">
                            <p class="text-xs font-bold text-forest">MODE OTOMATIS</p>
                            <p class="text-[9px] text-slate-400">Aktif via Sensor</p>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 bg-white border-2 border-transparent rounded-3xl cursor-pointer shadow-sm hover:shadow-md transition-all has-[:checked]:border-forest has-[:checked]:bg-emerald-50/30">
                        <input type="radio" name="system_mode" value="manual" class="hidden peer">
                        <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center mr-4">
                            <span class="material-symbols-rounded text-slate-400 text-2xl">touch_app</span>
                        </div>
                        <div class="leading-tight">
                            <p class="text-xs font-bold text-slate-400 peer-checked:text-forest">MODE MANUAL</p>
                            <p class="text-[9px] text-slate-400">Kontrol Dashboard</p>
                        </div>
                    </label>
                </div>
            </section>

            <section>
                <h4 class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-300 mb-3 px-1">Threshold Parameters</h4>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white p-5 rounded-[2.5rem] shadow-sm border border-slate-50">
                        <div class="flex justify-between items-center mb-4">
                            <span class="material-symbols-rounded text-orange-400 text-xl">device_thermostat</span>
                            <span class="text-[8px] font-black text-slate-200 uppercase">Temp</span>
                        </div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase mb-2 block">Suhu Maks (°C)</label>
                        <input type="number" step="0.1" name="temp_max" value="30.0" class="w-full bg-soft-bg rounded-xl p-3 text-lg font-black text-forest outline-none border border-transparent focus:border-forest/20">
                    </div>

                    <div class="bg-white p-5 rounded-[2.5rem] shadow-sm border border-slate-50">
                        <div class="flex justify-between items-center mb-4">
                            <span class="material-symbols-rounded text-blue-500 text-xl">water_drop</span>
                            <span class="text-[8px] font-black text-slate-200 uppercase">Soil</span>
                        </div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase mb-2 block">Tanah Min (%)</label>
                        <input type="number" name="soil_min" value="40" class="w-full bg-soft-bg rounded-xl p-3 text-lg font-black text-forest outline-none border border-transparent focus:border-forest/20">
                    </div>

                    <div class="bg-white p-5 rounded-[2.5rem] shadow-sm border border-slate-50">
                        <div class="flex justify-between items-center mb-4">
                            <span class="material-symbols-rounded text-emerald-500 text-xl">humidity_mid</span>
                            <span class="text-[8px] font-black text-slate-200 uppercase">Humidity</span>
                        </div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase mb-2 block">Udara Min (%)</label>
                        <input type="number" name="hum_min" value="50" class="w-full bg-soft-bg rounded-xl p-3 text-lg font-black text-forest outline-none border border-transparent focus:border-forest/20">
                    </div>

                    <div class="bg-white p-5 rounded-[2.5rem] shadow-sm border border-slate-50">
                        <div class="flex justify-between items-center mb-4">
                            <span class="material-symbols-rounded text-yellow-500 text-xl">wb_sunny</span>
                            <span class="text-[8px] font-black text-slate-200 uppercase">Light</span>
                        </div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase mb-2 block">Cahaya Min (Lux)</label>
                        <input type="number" name="light_min" value="500" class="w-full bg-soft-bg rounded-xl p-3 text-lg font-black text-forest outline-none border border-transparent focus:border-forest/20">
                    </div>
                </div>
            </section>

            <section class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden group">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-[10px] text-slate-500 font-medium">
                    <p class="flex items-center gap-2 italic">
                        <span class="material-symbols-rounded text-emerald-400 text-sm">info</span>
                        Pompa & Lampu aktif otomatis mengikuti batas di atas.
                    </p>
                    <p class="flex items-center gap-2 italic md:justify-end">
                        <span class="material-symbols-rounded text-emerald-400 text-sm">verified</span>
                        Perubahan disimpan real-time ke sistem hardware.
                    </p>
                </div>
            </section>

            <div class="flex justify-end pt-2">
                <button type="submit" class="bg-forest text-white px-8 py-3.5 rounded-2xl text-[11px] font-bold hover:bg-emerald-900 transition-all shadow-lg shadow-forest/20 flex items-center gap-2 active:scale-95">
                    <span class="material-symbols-rounded text-lg">save</span>
                    UPDATE KONFIGURASI
                </button>
            </div>
        </form>
    </main>
</body>
</html>