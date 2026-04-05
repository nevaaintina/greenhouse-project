<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartGrow Ultimate - Neva</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,300,0,0" />
    
    <script>
        tailwind.config = {
            theme: { extend: { colors: { 'forest': '#2D5A27', 'emerald-accent': '#2E7D32', 'soft-bg': '#F9FBFA' }, fontFamily: { sans: ['Poppins'] } } }
        }
    </script>
</head>
<body class="bg-soft-bg flex h-screen overflow-hidden">

    <aside class="w-64 bg-forest text-white flex flex-col p-6 shadow-2xl">
        <div class="flex items-center gap-3 mb-10 px-2">
            <span class="material-symbols-rounded text-emerald-400 text-3xl">potted_plant</span>
            <h1 class="font-bold text-xl uppercase tracking-tighter">SmartGrow</h1>
        </div>
        
        <nav class="flex-1 space-y-2">
            <a href="/dashboard" class="flex items-center gap-4 bg-white/10 p-3 rounded-2xl">
                <span class="material-symbols-rounded">dashboard</span> Dashboard
            </a>
            <a href="/sensors" class="flex items-center gap-4 p-3 opacity-60 hover:opacity-100 transition">
                <span class="material-symbols-rounded">sensors</span> Sensors
            </a>
            <a href="/logs" class="flex items-center gap-4 p-3 opacity-60 hover:opacity-100 transition">
                <span class="material-symbols-rounded">history</span> Log Activity
            </a>
        </nav>

        <div class="mt-auto bg-black/20 p-4 rounded-3xl border border-white/10">
            <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest mb-3">System Health</p>
            <div class="space-y-2">
                <div class="flex items-center justify-between text-[10px]">
                    <span class="opacity-60">Server</span>
                    <span class="flex items-center gap-1"><div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></div> Online</span>
                </div>
                <div class="flex items-center justify-between text-[10px]">
                    <span class="opacity-60">Node 01</span>
                    <span class="text-emerald-400">Connected</span>
                </div>
                <div class="flex items-center justify-between text-[10px]">
                    <span class="opacity-60">Node 02</span>
                    <span class="text-emerald-400">Connected</span>
                </div>
            </div>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto p-8 text-slate-700">
        <header class="flex justify-between items-center mb-10">
            <div>
                <h2 class="text-2xl font-bold text-forest">Greenhouse Overview</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase flex items-center gap-1">
                    <span class="material-symbols-rounded text-xs">sync</span> Last Update: Today, 14:30 PM
                </p>
            </div>
            <a href="/profile" class="flex items-center gap-3 bg-white p-1 pr-4 rounded-full shadow-sm border hover:bg-slate-50 transition">
                <div class="w-8 h-8 bg-forest rounded-full flex items-center justify-center text-white text-xs font-bold">N</div>
                <span class="text-xs font-semibold">Neva</span>
            </a>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
            <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-slate-50 flex flex-col items-center">
                <p class="text-[9px] font-bold text-slate-400 uppercase mb-3 tracking-widest">Soil Moisture</p>
                <div class="relative w-20 h-20 flex items-center justify-center">
                    <svg class="w-full h-full -rotate-90">
                        <circle cx="40" cy="40" r="35" stroke="#f1f5f9" stroke-width="6" fill="transparent" />
                        <circle cx="40" cy="40" r="35" stroke="#2E7D32" stroke-width="6" fill="transparent" stroke-dasharray="220" stroke-dashoffset="66" stroke-linecap="round" />
                    </svg>
                    <span class="absolute font-bold text-lg text-forest">70%</span>
                </div>
            </div>

            <div class="bg-white p-5 rounded-[2rem] shadow-sm border">
                <div class="flex justify-between mb-4">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Suhu</p>
                    <span class="material-symbols-rounded text-orange-400 text-xl">device_thermostat</span>
                </div>
                <h3 class="text-2xl font-bold text-forest">26.5°C</h3>
                <p class="text-[9px] text-emerald-500 font-bold mt-1">OPTIMAL</p>
            </div>

            <div class="bg-white p-5 rounded-[2rem] shadow-sm border">
                <div class="flex justify-between mb-4">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Humidity</p>
                    <span class="material-symbols-rounded text-blue-400 text-xl">humidity_mid</span>
                </div>
                <h3 class="text-2xl font-bold text-forest">55%</h3>
                <p class="text-[9px] text-slate-400 font-bold mt-1 uppercase">Normal</p>
            </div>

            <div class="bg-white p-5 rounded-[2rem] shadow-sm border">
                <div class="flex justify-between mb-4">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Cahaya</p>
                    <span class="material-symbols-rounded text-yellow-500 text-xl">wb_sunny</span>
                </div>
                <h3 class="text-2xl font-bold text-forest">450 <span class="text-xs text-slate-300">Lux</span></h3>
                <p class="text-[9px] text-orange-500 font-bold mt-1 uppercase">Gelap (ON)</p>
            </div>

            <div class="bg-white p-5 rounded-[2rem] shadow-sm border">
                <div class="flex justify-between mb-4">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter tracking-widest">Weather</p>
                    <span class="material-symbols-rounded text-yellow-500 text-xl">light_mode</span>
                </div>
                <div class="flex items-baseline gap-1">
                    <h3 class="text-xl font-bold text-forest">Cerah</h3>
                    <span class="text-[10px] font-bold text-slate-300 tracking-tighter">/ 28°C</span>
                </div>
                <p class="text-[9px] text-slate-400 font-bold mt-1 uppercase">Malang, ID</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border flex flex-col gap-6">
                <h4 class="font-bold text-xs uppercase tracking-[0.2em] text-slate-400">Manual Control</h4>
                
                <div class="space-y-4">
                    <div class="space-y-2">
                        <div class="flex justify-between items-center bg-slate-50 p-4 rounded-2xl">
                            <span class="text-xs font-semibold">Pompa Air</span>
                            <div class="w-10 h-5 bg-emerald-accent rounded-full flex items-center px-1 cursor-pointer">
                                <div class="w-3 h-3 bg-white rounded-full ml-auto shadow-sm"></div>
                            </div>
                        </div>
                        <button class="w-full bg-forest text-white py-4 rounded-2xl text-xs font-bold hover:bg-emerald-900 transition shadow-lg shadow-forest/20">SIRAM SEKARANG</button>
                        <p class="text-[9px] text-center text-slate-400 italic">Durasi: 5 Menit</p>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center bg-slate-50 p-4 rounded-2xl border-t border-slate-100">
                            <span class="text-xs font-semibold tracking-tight">Lampu UV</span>
                            <div class="w-10 h-5 bg-slate-200 rounded-full flex items-center px-1 cursor-pointer">
                                <div class="w-3 h-3 bg-white rounded-full shadow-sm"></div>
                            </div>
                        </div>
                        <button class="w-full border-2 border-forest text-forest py-4 rounded-2xl text-xs font-bold hover:bg-forest hover:text-white transition uppercase tracking-tighter">Nyalakan Lampu</button>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] shadow-sm border flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <h4 class="font-bold text-xs uppercase tracking-[0.2em] text-slate-400 text-[10px]">Moisture History</h4>
                    <span class="text-[9px] bg-slate-100 px-3 py-1.5 rounded-full text-slate-500 font-bold tracking-widest uppercase">7 Days Trend</span>
                </div>
                <div class="flex-1 min-h-[200px] relative">
                    <canvas id="soilChart"></canvas>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('soilChart').getContext('2d');
        
        // Buat Gradien Cantik
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
                    tension: 0.45, // Garis melengkung halus
                    borderWidth: 4,
                    pointRadius: 4,
                    pointBackgroundColor: '#2D5A27',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        max: 100, 
                        ticks: { stepSize: 20, font: { size: 10 } },
                        grid: { color: '#f1f5f9' }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });
    </script>
</body>
</html>