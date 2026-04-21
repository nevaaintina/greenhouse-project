<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sensors - SmartGrow Ultimate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script>
        tailwind.config = {
            theme: { extend: { colors: { 'forest': '#2D5A27', 'emerald-accent': '#2E7D32', 'soft-bg': '#F9FBFA' }, fontFamily: { sans: ['Poppins'] } } }
        }
    </script>
    <style>
        @keyframes wiggle {
            0%, 100% { transform: rotate(-3deg); }
            50% { transform: rotate(3deg); }
        }
        .animate-wiggle { animation: wiggle 2s ease-in-out infinite; }
        
        .cute-card:hover .mascot { transform: scale(1.2) translateY(-5px); transition: all 0.3s ease; }
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
                    <span class="text-emerald-400/60 font-bold uppercase tracking-tight">Stable</span>
                </div>
            </div>
        </div>
    </aside>

    <div id="overlay" class="fixed inset-0 bg-black/50 z-30 hidden transition-opacity"></div>

    <main class="flex-1 overflow-y-auto p-5 md:p-8 text-slate-700">
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 mt-12 md:mt-0 gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-forest tracking-tight">Sensor Management</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Mengintip kesehatan tanamanmu!</p>
            </div>
            <div class="bg-emerald-50 px-5 py-2.5 rounded-2xl border border-emerald-100 flex items-center gap-3">
                <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                <span class="text-[10px] font-black text-emerald-700 uppercase tracking-wider">Sistem Terkoneksi dengan Baik</span>
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-8">
            
            <div class="cute-card bg-white p-6 rounded-[2.5rem] shadow-sm border-2 border-transparent hover:border-orange-200 flex flex-col gap-4 group transition-all duration-500">
                <div class="flex justify-between items-start">
                    <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500 shadow-inner">
                        <span class="material-symbols-rounded text-3xl">device_thermostat</span>
                    </div>
                    <div class="mascot text-3xl animate-wiggle">🔥</div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-forest">26.5<span class="text-lg text-slate-300 ml-1">°C</span></h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">Suhu Udara (Hangat-hangat kuku!)</p>
                </div>
                <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden mt-1 shadow-inner">
                    <div class="bg-gradient-to-r from-orange-400 to-yellow-400 h-full w-[65%] rounded-full"></div>
                </div>
                <div class="flex justify-between items-center text-[9px] font-bold">
                    <span class="text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded-md uppercase tracking-tighter">Kondisi Nyaman Banget!</span>
                    <span class="text-slate-300">ESP32-01</span>
                </div>
            </div>

            <div class="cute-card bg-white p-6 rounded-[2.5rem] shadow-sm border-2 border-transparent hover:border-blue-200 flex flex-col gap-4 group transition-all duration-500">
                <div class="flex justify-between items-start">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 shadow-inner">
                        <span class="material-symbols-rounded text-3xl">water_drop</span>
                    </div>
                    <div class="mascot text-3xl animate-wiggle">💧</div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-forest">70<span class="text-lg text-slate-300 ml-1">%</span></h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">Kadar Air Tanah (Segerrrr~)</p>
                </div>
                <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden mt-1 shadow-inner">
                    <div class="bg-gradient-to-r from-blue-400 to-cyan-400 h-full w-[70%] rounded-full"></div>
                </div>
                <div class="flex justify-between items-center text-[9px] font-bold">
                    <span class="text-blue-500 bg-blue-50 px-2 py-0.5 rounded-md uppercase tracking-tighter">Tanaman Lagi Minum!</span>
                    <span class="text-slate-300">ESP32-01</span>
                </div>
            </div>

            <div class="cute-card bg-white p-6 rounded-[2.5rem] shadow-sm border-2 border-transparent hover:border-emerald-200 flex flex-col gap-4 group transition-all duration-500">
                <div class="flex justify-between items-start">
                    <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 shadow-inner">
                        <span class="material-symbols-rounded text-3xl">humidity_mid</span>
                    </div>
                    <div class="mascot text-3xl animate-wiggle">☁️</div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-forest">55<span class="text-lg text-slate-300 ml-1">%</span></h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">Kelembaban (Adem tenan!)</p>
                </div>
                <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden mt-1 shadow-inner">
                    <div class="bg-gradient-to-r from-emerald-400 to-teal-400 h-full w-[55%] rounded-full"></div>
                </div>
                <div class="flex justify-between items-center text-[9px] font-bold">
                    <span class="text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded-md uppercase tracking-tighter">Sirkulasi Mantul!</span>
                    <span class="text-slate-300">ESP32-01</span>
                </div>
            </div>

            <div class="cute-card bg-white p-6 rounded-[2.5rem] shadow-sm border-2 border-transparent hover:border-yellow-200 flex flex-col gap-4 group transition-all duration-500">
                <div class="flex justify-between items-start">
                    <div class="w-12 h-12 bg-yellow-50 rounded-2xl flex items-center justify-center text-yellow-500 shadow-inner">
                        <span class="material-symbols-rounded text-3xl">wb_sunny</span>
                    </div>
                    <div class="mascot text-3xl animate-wiggle">⭐</div>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-forest">450<span class="text-lg text-slate-300 ml-1">Lux</span></h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">Cahaya (Lagi Berjemur!)</p>
                </div>
                <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden mt-1 shadow-inner">
                    <div class="bg-gradient-to-r from-yellow-400 to-orange-300 h-full w-[45%] rounded-full"></div>
                </div>
                <div class="flex justify-between items-center text-[9px] font-bold">
                    <span class="text-orange-500 bg-orange-50 px-2 py-0.5 rounded-md uppercase tracking-tighter">Butuh Sedikit Sinar!</span>
                    <span class="text-slate-300">ESP32-02</span>
                </div>
            </div>

        </div>

        <div class="mt-4 bg-white p-6 rounded-[2.5rem] shadow-sm border-2 border-emerald-50 flex items-center gap-6 relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-emerald-50/50 group-hover:text-emerald-100 transition-colors">
                <span class="material-symbols-rounded text-[100px]">pets</span>
            </div>
            <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center flex-shrink-0 animate-wiggle">
                <span class="material-symbols-rounded text-3xl">tips_and_updates</span>
            </div>
            <div class="relative z-10">
                <p class="text-[10px] font-black text-forest uppercase tracking-widest">Catatan Kecil untuk Neva</p>
                <p class="text-[11px] text-slate-500 font-medium leading-relaxed italic">
                    "Data diambil secara real-time dari robot ESP32 kesayanganmu. Kalau robotnya tidur (offline), jangan lupa dicek koneksi Wi-Fi-nya ya!"
                </p>
            </div>
        </div>
    </main>

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
    </script>
</body>
</html>