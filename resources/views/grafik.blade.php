<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Analytics - SmartGrow Slim</title>
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

    <main class="flex-1 overflow-hidden p-8 text-slate-700 flex flex-col">
        <header class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-extrabold text-forest tracking-tight">Data Analytics</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Analisis Performa Greenhouse</p>
            </div>
            
            <div class="flex gap-3">
                <button class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100 hover:bg-slate-50 transition group">
                    <span class="material-symbols-rounded text-forest text-sm group-hover:bounce">download</span>
                    <span class="text-[9px] font-black text-slate-500 uppercase">Export PDF</span>
                </button>
                <div class="flex items-center gap-2 bg-forest px-4 py-2 rounded-xl shadow-lg shadow-forest/20 text-white">
                    <span class="material-symbols-rounded text-sm">calendar_today</span>
                    <span class="text-[9px] font-black uppercase tracking-tighter">Last 24 Hours</span>
                </div>
            </div>
        </header>

        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-orange-50/50 p-4 rounded-[2.5rem] border border-orange-100 flex items-center gap-4 transition-transform hover:scale-[1.02]">
                <div class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center text-orange-500 shadow-sm">
                    <span class="material-symbols-rounded">trending_up</span>
                </div>
                <div>
                    <p class="text-[8px] font-bold text-orange-400 uppercase tracking-widest leading-none">Avg Temp</p>
                    <p class="text-sm font-black text-forest mt-1">28.4°C</p>
                </div>
            </div>
            <div class="bg-blue-50/50 p-4 rounded-[2.5rem] border border-blue-100 flex items-center gap-4 transition-transform hover:scale-[1.02]">
                <div class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center text-blue-500 shadow-sm">
                    <span class="material-symbols-rounded">water_lux</span>
                </div>
                <div>
                    <p class="text-[8px] font-bold text-blue-400 uppercase tracking-widest leading-none">Lowest Soil</p>
                    <p class="text-sm font-black text-forest mt-1">42%</p>
                </div>
            </div>
            <div class="bg-emerald-50/50 p-4 rounded-[2.5rem] border border-emerald-100 flex items-center gap-4 transition-transform hover:scale-[1.02]">
                <div class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center text-emerald-500 shadow-sm">
                    <span class="material-symbols-rounded">sprinkler</span>
                </div>
                <div>
                    <p class="text-[8px] font-bold text-emerald-400 uppercase tracking-widest leading-none">Watered</p>
                    <p class="text-sm font-black text-forest mt-1">5 Times</p>
                </div>
            </div>
        </div>

        <section class="bg-white p-8 rounded-[3.5rem] shadow-sm border border-slate-50 flex-1 flex flex-col min-h-0 relative">
            <div class="flex justify-between items-center mb-6">
                <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-300">Environmental Trend</h4>
                
                <div class="flex gap-4">
                    <div class="flex items-center gap-2 text-[8px] font-bold text-orange-500 uppercase">
                        <span class="w-2 h-2 bg-orange-500 rounded-full"></span> Suhu
                    </div>
                    <div class="flex items-center gap-2 text-[8px] font-bold text-blue-500 uppercase">
                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span> Tanah
                    </div>
                    <div class="flex items-center gap-2 text-[8px] font-bold text-emerald-500 uppercase">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span> Udara
                    </div>
                </div>
            </div>
            
            <div class="flex-1 w-full relative">
                <canvas id="mainAnalysisChart"></canvas>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('mainAnalysisChart').getContext('2d');
        
        // Gradient Colors
        const gradTemp = ctx.createLinearGradient(0, 0, 0, 300);
        gradTemp.addColorStop(0, 'rgba(249, 115, 22, 0.05)');
        gradTemp.addColorStop(1, 'rgba(249, 115, 22, 0)');

        const gradSoil = ctx.createLinearGradient(0, 0, 0, 300);
        gradSoil.addColorStop(0, 'rgba(59, 130, 246, 0.05)');
        gradSoil.addColorStop(1, 'rgba(59, 130, 246, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00'],
                datasets: [
                    { label: 'Suhu', data: [24, 27, 31, 32, 28, 26, 25], borderColor: '#f97316', borderWidth: 3, tension: 0.4, fill: true, backgroundColor: gradTemp, pointRadius: 0 },
                    { label: 'Tanah', data: [70, 65, 60, 58, 85, 80, 75], borderColor: '#3b82f6', borderWidth: 3, tension: 0.4, fill: true, backgroundColor: gradSoil, pointRadius: 0 },
                    { label: 'Udara', data: [55, 52, 50, 48, 55, 54, 53], borderColor: '#10b981', borderWidth: 3, tension: 0.4, fill: false, pointRadius: 0 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: '#f8fafc' }, ticks: { font: { size: 9, weight: 'bold' }, color: '#cbd5e1' } },
                    x: { grid: { display: false }, ticks: { font: { size: 9, weight: 'bold' }, color: '#cbd5e1' } }
                }
            }
        });
    </script>
</body>
</html>