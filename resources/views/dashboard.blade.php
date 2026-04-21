<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartGrow Ultimate - Neva</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,300,0,0" />
    
    <script>
        tailwind.config = {
            theme: { extend: { colors: { 'forest': '#2D5A27', 'emerald-accent': '#2E7D32', 'soft-bg': '#F9FBFA' }, fontFamily: { sans: ['Poppins'] } } }
        }
    </script>
    <style>
        /* CSS KHUSUS UNTUK BACKGROUND VISUAL GREENHOUSE */
        .smartgrow-visual-bg {
            background-color: #F9FBFA;
            /* Pattern Garis Kaca Tipis (Simulasi Atap Greenhouse) */
            background-image: 
                linear-gradient(rgba(45, 90, 39, 0.01) 1px, transparent 1px),
                linear-gradient(90deg, rgba(45, 90, 39, 0.01) 1px, transparent 1px);
            background-size: 20px 20px;
            background-attachment: fixed;
        }

        /* Siluet Bangunan Greenhouse Raksasa (Super Transparan) */
        .corner-greenhouse {
            position: absolute;
            opacity: 0.02; 
            pointer-events: none;
            z-index: 0;
            filter: grayscale(100%) brightness(0.9); 
        }
    </style>
</head>
<body class="smartgrow-visual-bg flex h-screen overflow-hidden font-sans">

    <img src="https://i.ibb.co/5G7mXjV/greenhouse-silhouette-left.png" class="corner-greenhouse top-0 left-64 w-[600px]" alt="bg-greenhouse">
    <img src="https://i.ibb.co/nsR5P3H/greenhouse-silhouette-right.png" class="corner-greenhouse bottom-0 right-0 w-[500px]" alt="bg-greenhouse">

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
                    <span class="text-emerald-400/60 font-bold uppercase tracking-tight">Stable</span>
                </div>
            </div>
        </div>
    </aside>

    <div id="overlay" class="fixed inset-0 bg-black/50 z-30 hidden transition-opacity"></div>

    <main class="flex-1 overflow-y-auto p-5 md:p-8 text-slate-700 relative z-10">
        <header class="flex justify-between items-center mb-10 mt-12 md:mt-0 relative z-10">
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-forest leading-tight uppercase tracking-tighter">Greenhouse Overview</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase flex items-center gap-1 mt-1 tracking-wider">
                    <span class="material-symbols-rounded text-xs animate-spin-slow">sync</span> Last Update: Today, 14:30 PM
                </p>
            </div>
            <a href="/profile" class="flex items-center gap-3 bg-white/80 backdrop-blur-sm p-1 pr-4 rounded-full shadow-sm border border-white hover:bg-slate-50 transition hover:shadow-lg">
                <div class="w-8 h-8 bg-forest rounded-full flex items-center justify-center text-white text-xs font-bold shadow-md">N</div>
                <span class="hidden sm:inline text-xs font-semibold text-forest">Neva</span>
            </a>
        </header>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <div class="bg-white/80 backdrop-blur-md p-5 rounded-[2rem] shadow-sm border border-white hover:shadow-xl transition-all duration-300 flex flex-col items-center group">
                <p class="text-[9px] font-bold text-slate-400 uppercase mb-3 tracking-widest text-center group-hover:text-forest transition">Soil Moisture</p>
                <div class="relative w-20 h-20 flex items-center justify-center group-hover:scale-105 transition-transform">
                    <svg class="w-full h-full -rotate-90">
                        <circle cx="40" cy="40" r="35" stroke="#f1f5f9" stroke-width="6" fill="transparent" />
                        <circle cx="40" cy="40" r="35" stroke="#2E7D32" stroke-width="6" fill="transparent" stroke-dasharray="220" stroke-dashoffset="66" stroke-linecap="round" />
                    </svg>
                    <span class="absolute font-black text-xl text-forest">70%</span>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-md p-5 rounded-[2rem] shadow-sm border border-white hover:shadow-xl transition-all duration-300 flex flex-col items-center group">
                <div class="flex justify-between mb-4 w-full px-2">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest group-hover:text-forest transition">Suhu</p>
                    <span class="material-symbols-rounded text-orange-400 text-xl animate-pulse">device_thermostat</span>
                </div>
                <h3 class="text-2xl font-black text-forest">26.5<span class="text-base text-slate-300 ml-1 font-medium">°C</span></h3>
                <p class="text-[9px] text-emerald-600 font-bold mt-1.5 bg-emerald-50 px-2 py-0.5 rounded-md inline-block uppercase tracking-tight">Optimal</p>
            </div>

            <div class="bg-white/80 backdrop-blur-md p-5 rounded-[2rem] shadow-sm border border-white hover:shadow-xl transition-all duration-300 flex flex-col items-center group">
                <div class="flex justify-between mb-4 w-full px-2">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest group-hover:text-forest transition">Humidity</p>
                    <span class="material-symbols-rounded text-blue-400 text-xl animate-pulse">humidity_mid</span>
                </div>
                <h3 class="text-2xl font-black text-forest">55<span class="text-base text-slate-300 ml-1 font-medium">%</span></h3>
                <p class="text-[9px] text-slate-400 font-bold mt-1.5 uppercase tracking-tight">Normal</p>
            </div>

            <div class="bg-white/80 backdrop-blur-md p-5 rounded-[2rem] shadow-sm border border-white hover:shadow-xl transition-all duration-300 flex flex-col items-center group">
                <div class="flex justify-between mb-4 w-full px-2">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest group-hover:text-forest transition">Cahaya</p>
                    <span class="material-symbols-rounded text-yellow-500 text-xl animate-spin-slow">wb_sunny</span>
                </div>
                <h3 class="text-2xl font-black text-forest">450 <span class="text-xs text-slate-300 ml-1 font-medium">Lux</span></h3>
                <p class="text-[9px] text-orange-600 font-bold mt-1.5 bg-orange-50 px-2 py-0.5 rounded-md inline-block uppercase tracking-tight">Gelap (ON)</p>
            </div>

            <div class="bg-white/80 backdrop-blur-md p-5 rounded-[2rem] shadow-sm border border-white hover:shadow-xl transition-all duration-300 flex flex-col items-center group">
                <div class="flex justify-between mb-4 w-full px-2">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest group-hover:text-forest transition">Weather</p>
                    <span class="material-symbols-rounded text-yellow-500 text-xl animate-bounce">light_mode</span>
                </div>
                <div class="flex items-baseline gap-1 group-hover:scale-105 transition-transform">
                    <h3 class="text-xl font-bold text-forest">Cerah</h3>
                    <span class="text-[10px] font-bold text-slate-300">/ 28°C</span>
                </div>
                <p class="text-[9px] text-slate-400 font-bold mt-1 uppercase tracking-tighter">Malang, ID</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-2 bg-white/90 backdrop-blur-sm p-8 rounded-[3rem] shadow-sm border border-white flex flex-col md:flex-row items-center gap-8 relative overflow-hidden group hover:shadow-2xl transition-all duration-500">
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-forest/5 rounded-full blur-3xl group-hover:bg-emerald-400/10 transition-colors"></div>
                <div class="relative w-40 h-40 flex-shrink-0">
                    <svg viewBox="0 0 200 200" class="w-full h-full drop-shadow-xl group-hover:scale-105 transition-transform duration-500">
                        <path d="M40,160 L160,160 L160,80 L100,30 L40,80 Z" fill="none" stroke="#2D5A27" stroke-width="4" stroke-linejoin="round"/>
                        <path d="M40,80 L100,30 L160,80" fill="#2D5A27" class="opacity-10 transition-opacity group-hover:opacity-20"/>
                        <path d="M80,160 Q80,120 100,120 Q120,120 120,160" fill="#2E7D32" />
                        <circle cx="100" cy="110" r="8" fill="#4ADE80" class="animate-bounce" />
                        <g class="animate-pulse opacity-40">
                            <circle cx="70" cy="130" r="3" fill="#3b82f6" />
                            <circle cx="130" cy="140" r="3" fill="#3b82f6" />
                            <circle cx="100" cy="150" r="3" fill="#3b82f6" />
                        </g>
                    </svg>
                </div>
                <div class="flex-1 space-y-4 relative z-10 text-center md:text-left">
                    <div>
                        <h4 class="text-xl font-black text-forest uppercase tracking-tight italic">Live Ecosystem</h4>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Kondisi Visual Greenhouse</p>
                    </div>
                    <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                        <div class="flex items-center gap-2 px-3 py-2 bg-emerald-50 text-emerald-700 rounded-xl text-[9px] font-black uppercase shadow-inner border border-emerald-100">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_5px_#10b981]"></span> Pompa: Aktif
                        </div>
                        <div class="flex items-center gap-2 px-3 py-2 bg-yellow-50 text-yellow-700 rounded-xl text-[9px] font-black uppercase shadow-inner border border-yellow-100">
                            <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full animate-ping shadow-[0_0_5px_#f59e0b]"></span> Cahaya: On
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 flex flex-col gap-4">
                <button onclick="confirm('Matikan semua sistem?')" class="group relative bg-white/90 backdrop-blur-sm border-2 border-red-100 p-6 rounded-[2.5rem] flex items-center justify-between hover:bg-red-500 transition-all duration-500 overflow-hidden shadow-sm hover:shadow-xl active:scale-95">
                    <div class="relative z-10 flex items-center gap-4 text-left">
                        <div class="w-12 h-12 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center group-hover:bg-white transition-all shadow-inner group-hover:scale-110">
                            <span class="material-symbols-rounded text-2xl">emergency_home</span>
                        </div>
                        <div class="leading-tight">
                            <p class="text-sm font-black text-red-500 group-hover:text-white transition">SHUTDOWN</p>
                            <p class="text-[9px] text-red-300 font-bold group-hover:text-red-100 transition uppercase tracking-tighter">Emergency Stop</p>
                        </div>
                    </div>
                    <span class="material-symbols-rounded text-red-100 group-hover:text-white transition-all group-hover:translate-x-1">arrow_forward_ios</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-red-600 to-red-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </button>
                <button class="bg-forest text-white p-5 rounded-[2rem] text-[10px] font-black hover:bg-emerald-900 transition shadow-lg shadow-forest/20 flex items-center justify-center gap-3 uppercase tracking-widest active:scale-95">
                    <span class="material-symbols-rounded text-sm">restart_alt</span> Reset Gateway Node
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 relative z-10">
            <div class="bg-white/90 backdrop-blur-sm p-8 rounded-[2.5rem] shadow-sm border border-white flex flex-col gap-6 hover:shadow-xl transition-all duration-300">
                <h4 class="font-bold text-xs uppercase tracking-[0.2em] text-slate-400">Manual Control</h4>
                <div class="space-y-4">
                    <div class="space-y-2">
                        <div class="flex justify-between items-center bg-white/50 p-4 rounded-2xl border border-white/50 shadow-inner hover:bg-white/80 transition-colors cursor-pointer group">
                            <span class="text-xs font-semibold text-slate-600 group-hover:text-forest transition">Pompa Air</span>
                            <div class="w-10 h-5 bg-emerald-accent rounded-full flex items-center px-1 cursor-pointer shadow-md">
                                <div class="w-3 h-3 bg-white rounded-full ml-auto shadow-sm animate-pulse"></div>
                            </div>
                        </div>
                        <button class="w-full bg-forest text-white py-4 rounded-2xl text-xs font-bold hover:bg-emerald-900 transition shadow-lg shadow-forest/20 active:scale-95 uppercase tracking-wider">SIRAM SEKARANG</button>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center bg-white/50 p-4 rounded-2xl border border-white/50 shadow-inner hover:bg-white/80 transition-colors cursor-pointer group">
                            <span class="text-xs font-semibold text-slate-600 group-hover:text-forest transition">Lampu UV</span>
                            <div class="w-10 h-5 bg-slate-200 rounded-full flex items-center px-1 cursor-pointer shadow-md">
                                <div class="w-3 h-3 bg-white rounded-full shadow-sm"></div>
                            </div>
                        </div>
                        <button class="w-full border-2 border-forest text-forest py-4 rounded-2xl text-xs font-bold hover:bg-forest hover:text-white transition uppercase tracking-wider active:scale-95">Nyalakan Lampu</button>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 bg-white/90 backdrop-blur-sm p-6 md:p-8 rounded-[2.5rem] shadow-sm border border-white flex flex-col hover:shadow-xl transition-all duration-300">
                <div class="flex justify-between items-center mb-6">
                    <h4 class="font-bold text-xs uppercase tracking-[0.2em] text-slate-400 text-[10px]">Moisture History</h4>
                    <span class="hidden sm:inline text-[9px] bg-white border border-slate-100 px-3 py-1.5 rounded-full text-slate-500 font-bold uppercase tracking-widest shadow-sm">7 Days Trend</span>
                </div>
                <div class="flex-1 min-h-[250px] relative">
                    <canvas id="soilChart"></canvas>
                </div>
            </div>
        </div>
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

        const ctx = document.getElementById('soilChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(46, 125, 50, 0.2)');
        gradient.addColorStop(1, 'rgba(46, 125, 50, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Soil Moisture (%)',
                    data: [65, 75, 70, 85, 60, 55, 70],
                    borderColor: '#2D5A27',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.45,
                    borderWidth: 4,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#2D5A27',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, max: 100, ticks: { font: { size: 10 } }, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                }
            }
        });
    </script>
</body>
</html>