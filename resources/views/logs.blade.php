<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Log - SmartGrow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,300,0,0" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { 'forest': '#2D5A27', 'emerald-accent': '#2E7D32', 'soft-bg': '#F9FBFA' },
                    fontFamily: { sans: ['Poppins', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
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
        <header class="mb-10 mt-12 md:mt-0">
            <h2 class="text-2xl md:text-3xl font-extrabold text-forest uppercase tracking-tight">Activity Log</h2>
            <div class="flex items-center gap-2 mt-1">
                <span class="w-8 h-1 bg-emerald-400 rounded-full"></span>
                <p class="text-[11px] md:text-sm text-slate-400 font-semibold tracking-wide uppercase">Riwayat kejadian dan aktivitas sistem</p>
            </div>
        </header>

        <div class="bg-white rounded-[2rem] md:rounded-[3rem] shadow-sm border border-slate-50 overflow-hidden mb-10">
            <div class="p-6 md:p-8 border-b border-slate-50 flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white gap-4">
                <h3 class="font-black text-forest text-xs md:text-sm uppercase tracking-[0.2em] flex items-center gap-2">
                    <span class="material-symbols-rounded text-emerald-accent">list_alt</span> Daftar Riwayat Aktivitas
                </h3>
                <input type="date" class="w-full sm:w-auto bg-slate-50 border-none rounded-xl p-3 text-xs text-slate-500 font-bold outline-none focus:ring-2 focus:ring-forest/10 cursor-pointer">
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[650px]">
                    <thead class="bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">
                        <tr>
                            <th class="px-8 py-6">Timestamp</th>
                            <th class="px-8 py-6">Event Description</th>
                            <th class="px-8 py-6">Origin</th>
                            <th class="px-8 py-6 text-right">Value / Data</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-8 py-5 text-[11px] text-slate-400 font-bold">01 Apr, 21:42:05</td>
                            <td class="px-8 py-5 text-sm font-black text-forest uppercase tracking-tighter">Penyiraman Dimulai</td>
                            <td class="px-8 py-5">
                                <span class="bg-blue-50 text-blue-600 text-[9px] px-3 py-1 rounded-lg font-black tracking-widest">MANUAL</span>
                            </td>
                            <td class="px-8 py-5 text-sm text-slate-600 font-black text-right">500 ML</td>
                        </tr>
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-8 py-5 text-[11px] text-slate-400 font-bold">01 Apr, 20:15:10</td>
                            <td class="px-8 py-5 text-sm font-black text-forest uppercase tracking-tighter">Update Sensor Suhu</td>
                            <td class="px-8 py-5">
                                <span class="bg-emerald-50 text-emerald-700 text-[9px] px-3 py-1 rounded-lg font-black tracking-widest">SYSTEM</span>
                            </td>
                            <td class="px-8 py-5 text-sm text-slate-600 font-black text-right">26.5 °C</td>
                        </tr>
                        <tr class="hover:bg-slate-50/30 transition-colors group">
                            <td class="px-8 py-5 text-[11px] text-slate-400 font-bold">01 Apr, 19:30:00</td>
                            <td class="px-8 py-5 text-sm font-black text-forest uppercase tracking-tighter">Pompa Mati Otomatis</td>
                            <td class="px-8 py-5">
                                <span class="bg-emerald-50 text-emerald-700 text-[9px] px-3 py-1 rounded-lg font-black tracking-widest">SYSTEM</span>
                            </td>
                            <td class="px-8 py-5 text-sm text-slate-600 font-black text-right italic">DURASI 5 MENIT</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="p-6 bg-slate-50/30 flex justify-center border-t border-slate-50 text-[10px] font-black text-slate-300 tracking-[0.3em] uppercase">
                End of recent activities
            </div>
        </div>
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