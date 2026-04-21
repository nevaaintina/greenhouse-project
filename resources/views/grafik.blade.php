<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Analytics - SmartGrow Ultimate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script>
        tailwind.config = {
            theme: { extend: { colors: { 'forest': '#2D5A27', 'emerald-accent': '#2E7D32', 'soft-bg': '#F9FBFA' }, fontFamily: { sans: ['Poppins'] } } }
        }
    </script>
    <style>
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .animate-float { animation: float 3s ease-in-out infinite; }
    </style>
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
                    <span class="px-2 py-0.5 bg-emerald-400/20 text-emerald-400 rounded-md font-black italic border border-emerald-400/20 uppercase">Otomatis</span>
                </div>
                <div class="flex items-center justify-between text-[10px]">
                    <span class="opacity-60 text-white font-medium">Actuators Status</span>
                    <div class="flex items-center gap-1.5 font-bold text-emerald-400 italic">
                        <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse shadow-[0_0_8px_#34d399]"></div> Running
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

    <main class="flex-1 overflow-y-auto p-5 md:p-8 text-slate-700 flex flex-col">
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 mt-12 md:mt-0 gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-forest tracking-tight leading-tight">Data Analytics</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Analisis Performa Greenhouse</p>
            </div>
            
            <div class="flex gap-2 w-full sm:w-auto">
                <button class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100 hover:bg-slate-50 transition group">
                    <span class="material-symbols-rounded text-forest text-sm group-hover:bounce">download</span>
                    <span class="text-[9px] font-black text-slate-500 uppercase">Export PDF</span>
                </button>
                <div class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-forest px-4 py-2 rounded-xl shadow-lg shadow-forest/20 text-white">
                    <span class="material-symbols-rounded text-sm">calendar_today</span>
                    <span class="text-[9px] font-black uppercase tracking-tighter italic">Last 24 Hours</span>
                </div>
            </div>
        </header>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-orange-50/50 p-4 rounded-[2rem] border border-orange-100 flex items-center gap-4 hover:bg-orange-50 transition-colors">
                <div class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center text-orange-500 shadow-sm">
                    <span class="material-symbols-rounded">trending_up</span>
                </div>
                <div>
                    <p class="text-[8px] font-bold text-orange-400 uppercase tracking-widest">Avg Temp</p>
                    <p class="text-sm font-black text-forest mt-1">28.4°C</p>
                </div>
            </div>
            <div class="bg-blue-50/50 p-4 rounded-[2rem] border border-blue-100 flex items-center gap-4 hover:bg-blue-50 transition-colors">
                <div class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center text-blue-500 shadow-sm">
                    <span class="material-symbols-rounded">water_lux</span>
                </div>
                <div>
                    <p class="text-[8px] font-bold text-blue-400 uppercase tracking-widest">Lowest Soil</p>
                    <p class="text-sm font-black text-forest mt-1">42%</p>
                </div>
            </div>
            <div class="bg-emerald-50/50 p-4 rounded-[2rem] border border-emerald-100 flex items-center gap-4 hover:bg-emerald-50 transition-colors">
                <div class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center text-emerald-500 shadow-sm">
                    <span class="material-symbols-rounded">sprinkler</span>
                </div>
                <div>
                    <p class="text-[8px] font-bold text-emerald-400 uppercase tracking-widest">Watered</p>
                    <p class="text-sm font-black text-forest mt-1">5 Times</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
            <div class="lg:col-span-1 bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-50 flex flex-col items-center justify-center text-center relative overflow-hidden group">
                <div class="absolute inset-0 pointer-events-none">
                    <span class="material-symbols-rounded text-emerald-100/50 absolute top-2 left-4 animate-bounce">eco</span>
                </div>
                <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-3">Plant Mood</p>
                <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mb-3 border-4 border-white shadow-lg group-hover:rotate-12 transition-transform">
                    <span class="text-5xl animate-float">🌿</span>
                </div>
                <h4 class="text-xs font-black text-forest uppercase italic">Feeling Great!</h4>
                <p class="text-[9px] text-slate-400 mt-1">Lingkungan sangat mendukung pertumbuhan.</p>
            </div>

            <div class="lg:col-span-3 bg-gradient-to-br from-forest to-emerald-900 p-6 rounded-[2.5rem] shadow-xl text-white flex items-center gap-6 relative overflow-hidden">
                <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md flex-shrink-0">
                    <span class="material-symbols-rounded text-emerald-300 text-2xl animate-pulse">auto_awesome</span>
                </div>
                <div class="relative z-10">
                    <p class="text-[9px] font-black uppercase tracking-widest opacity-60">Smart System Insight</p>
                    <h4 class="text-base font-bold mt-1">Saran untuk Neva:</h4>
                    <p class="text-[11px] opacity-80 leading-relaxed italic mt-1">"Tanamanmu terlihat sangat aktif berfotosintesis siang ini. Pastikan ventilasi terbuka untuk sirkulasi CO2 yang optimal!"</p>
                </div>
                <div class="absolute -right-4 -bottom-6 opacity-10">
                    <span class="material-symbols-rounded text-[100px]">agriculture</span>
                </div>
            </div>
        </div>

        <section class="bg-white p-6 md:p-10 rounded-[2.5rem] md:rounded-[3.5rem] shadow-sm border border-slate-50 relative flex flex-col">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10 gap-4">
                <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-300">Environmental Growth Trend</h4>
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center gap-2 text-[9px] font-bold text-orange-500 uppercase">
                        <span class="w-3 h-3 bg-orange-500 rounded-full shadow-[0_0_8px_rgba(249,115,22,0.4)]"></span> Suhu
                    </div>
                    <div class="flex items-center gap-2 text-[9px] font-bold text-blue-500 uppercase">
                        <span class="w-3 h-3 bg-blue-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.4)]"></span> Tanah
                    </div>
                    <div class="flex items-center gap-2 text-[9px] font-bold text-emerald-500 uppercase">
                        <span class="w-3 h-3 bg-emerald-500 rounded-full shadow-[0_0_8px_rgba(16,185,129,0.4)]"></span> Udara
                    </div>
                </div>
            </div>
            <div class="relative w-full h-[350px] md:h-[400px] lg:h-[450px]">
                <canvas id="mainAnalysisChart"></canvas>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
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

        const ctx = document.getElementById('mainAnalysisChart').getContext('2d');
        const createGrad = (color) => {
            const g = ctx.createLinearGradient(0, 0, 0, 400);
            g.addColorStop(0, color.replace('1)', '0.15)'));
            g.addColorStop(1, color.replace('1)', '0)'));
            return g;
        };

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00'],
                datasets: [
                    { label: 'Suhu', data: [24, 27, 31, 32, 28, 26, 25], borderColor: '#f97316', borderWidth: 4, tension: 0.45, fill: true, backgroundColor: createGrad('rgba(249, 115, 22, 1)'), pointRadius: 0, pointHoverRadius: 6 },
                    { label: 'Tanah', data: [70, 65, 60, 58, 85, 80, 75], borderColor: '#3b82f6', borderWidth: 4, tension: 0.45, fill: true, backgroundColor: createGrad('rgba(59, 130, 246, 1)'), pointRadius: 0, pointHoverRadius: 6 },
                    { label: 'Udara', data: [55, 52, 48, 45, 55, 54, 53], borderColor: '#10b981', borderWidth: 4, tension: 0.45, fill: true, backgroundColor: createGrad('rgba(16, 185, 129, 1)'), pointRadius: 0, pointHoverRadius: 6 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false }, ticks: { font: { size: 10, weight: '600' }, color: '#cbd5e1', padding: 10 } },
                    x: { grid: { display: false }, ticks: { font: { size: 10, weight: '600' }, color: '#cbd5e1', padding: 10 } }
                }
            }
        });
    </script>
</body>
</html>