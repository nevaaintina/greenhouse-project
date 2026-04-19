<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sensor Details - SmartGrow Slim</title>
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
        <header class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-2xl font-extrabold text-forest tracking-tight">Sensor Management</h2>
        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Status Hardware & Kalibrasi</p>
    </div>
    <div class="bg-emerald-50 px-4 py-2 rounded-2xl border border-emerald-100 flex items-center gap-2">
        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
        <span class="text-[10px] font-bold text-emerald-700 uppercase">Semua Node Aktif</span>
    </div>
</header>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-50 flex flex-col gap-4 group hover:shadow-xl transition-all duration-500">
                <div class="flex justify-between items-start">
                    <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500 transition-transform group-hover:scale-110">
                        <span class="material-symbols-rounded text-3xl">device_thermostat</span>
                    </div>
                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">DHT11 Sensor</span>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-forest">26.5<span class="text-lg text-slate-300 ml-1">°C</span></h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">Suhu Udara Ruangan</p>
                </div>
                <div class="w-full bg-slate-50 h-1.5 rounded-full overflow-hidden mt-2">
                    <div class="bg-orange-400 h-full w-[65%] rounded-full"></div>
                </div>
                <div class="flex justify-between items-center text-[9px] font-bold">
                    <span class="text-emerald-500 uppercase">Optimal Condition</span>
                    <span class="text-slate-300">Node: ESP32-01</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-50 flex flex-col gap-4 group hover:shadow-xl transition-all duration-500">
                <div class="flex justify-between items-start">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 transition-transform group-hover:scale-110">
                        <span class="material-symbols-rounded text-3xl">water_drop</span>
                    </div>
                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Capacitive Moisture</span>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-forest">70<span class="text-lg text-slate-300 ml-1">%</span></h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">Kadar Air dalam Tanah</p>
                </div>
                <div class="w-full bg-slate-50 h-1.5 rounded-full overflow-hidden mt-2">
                    <div class="bg-blue-500 h-full w-[70%] rounded-full"></div>
                </div>
                <div class="flex justify-between items-center text-[9px] font-bold">
                    <span class="text-emerald-500 uppercase">Kondisi Basah</span>
                    <span class="text-slate-300">Node: ESP32-01</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-50 flex flex-col gap-4 group hover:shadow-xl transition-all duration-500">
                <div class="flex justify-between items-start">
                    <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 transition-transform group-hover:scale-110">
                        <span class="material-symbols-rounded text-3xl">humidity_mid</span>
                    </div>
                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">DHT11 Sensor</span>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-forest">55<span class="text-lg text-slate-300 ml-1">%</span></h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">Kelembaban Atmosfer</p>
                </div>
                <div class="w-full bg-slate-50 h-1.5 rounded-full overflow-hidden mt-2">
                    <div class="bg-emerald-500 h-full w-[55%] rounded-full"></div>
                </div>
                <div class="flex justify-between items-center text-[9px] font-bold">
                    <span class="text-emerald-500 uppercase">Sirkulasi Baik</span>
                    <span class="text-slate-300">Node: ESP32-01</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-50 flex flex-col gap-4 group hover:shadow-xl transition-all duration-500">
                <div class="flex justify-between items-start">
                    <div class="w-12 h-12 bg-yellow-50 rounded-2xl flex items-center justify-center text-yellow-500 transition-transform group-hover:scale-110">
                        <span class="material-symbols-rounded text-3xl">wb_sunny</span>
                    </div>
                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">LDR Sensor</span>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-forest">450<span class="text-lg text-slate-300 ml-1">Lux</span></h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">Intensitas Cahaya</p>
                </div>
                <div class="w-full bg-slate-50 h-1.5 rounded-full overflow-hidden mt-2">
                    <div class="bg-yellow-500 h-full w-[45%] rounded-full"></div>
                </div>
                <div class="flex justify-between items-center text-[9px] font-bold">
                    <span class="text-orange-500 uppercase">Cahaya Kurang</span>
                    <span class="text-slate-300">Node: ESP32-02</span>
                </div>
            </div>

        </div>

        <div class="mt-8 bg-forest/5 p-6 rounded-[2rem] border border-forest/10 flex items-center gap-4">
            <span class="material-symbols-rounded text-forest">info</span>
            <p class="text-[10px] text-slate-500 font-medium">
                Data sensor di atas diambil secara langsung dari mikrokontroler melalui protokol MQTT/HTTP. Pastikan koneksi Wi-Fi pada Node tetap stabil untuk akurasi data real-time.
            </p>
        </div>
    </main>
</body>
</html>