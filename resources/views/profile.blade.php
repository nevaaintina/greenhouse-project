<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - SmartGrow Premium</title>
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

    <main class="flex-1 overflow-y-auto p-5 md:p-10 text-slate-700">
        
        <header class="mb-8 mt-12 md:mt-0">
            <h2 class="text-2xl md:text-3xl font-black text-forest tracking-tighter uppercase">Administrator Profile</h2>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Management & System Identity</p>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-50 flex flex-col items-center text-center">
                    <div class="w-32 h-32 bg-forest rounded-[2.5rem] flex items-center justify-center text-white text-6xl font-black shadow-xl mb-6 border-4 border-emerald-50">
                        N
                    </div>
                    <h3 class="text-2xl font-black text-forest">Neva</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Front-End Developer</p>
                    <p class="text-[10px] text-slate-300 mt-2">Malang, Jawa Timur, ID</p>
                    
                    <div class="flex gap-2 mt-6 w-full">
                        <a href="{{ route('profile.edit') }}" class="flex-1 bg-forest text-white py-3 rounded-2xl text-[10px] font-bold hover:bg-emerald-900 transition flex items-center justify-center gap-2">
                            <span class="material-symbols-rounded text-sm">edit</span> EDIT
                        </a>
                        <button class="flex-1 border-2 border-slate-100 text-slate-400 py-3 rounded-2xl text-[10px] font-bold hover:bg-red-50 hover:text-red-500 transition flex items-center justify-center gap-2">
                            <span class="material-symbols-rounded text-sm">logout</span> KELUAR
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-50 overflow-hidden">
                    <div class="p-4 bg-slate-50/50 border-b border-slate-50 flex items-center gap-2">
                        <span class="material-symbols-rounded text-forest text-sm">link</span>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Connect With Me</span>
                    </div>
                    <div class="p-2 space-y-1">
                        <div class="flex justify-between items-center p-3 hover:bg-slate-50 rounded-xl transition cursor-pointer group">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">GitHub</span>
                            <span class="text-[10px] font-bold text-forest opacity-0 group-hover:opacity-100 transition">@neva_dev</span>
                        </div>
                        <div class="flex justify-between items-center p-3 hover:bg-slate-50 rounded-xl transition cursor-pointer group">
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Instagram</span>
                            <span class="text-[10px] font-bold text-forest opacity-0 group-hover:opacity-100 transition">@neva_ig</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-50">
                    <div class="space-y-5">
                        <div class="flex flex-col sm:flex-row sm:items-center py-3 border-b border-slate-50">
                            <span class="w-32 text-[10px] font-black text-slate-300 uppercase tracking-widest mb-1 sm:mb-0">Full Name</span>
                            <span class="text-sm font-bold text-forest">Neva Ardiansyah</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center py-3 border-b border-slate-50">
                            <span class="w-32 text-[10px] font-black text-slate-300 uppercase tracking-widest mb-1 sm:mb-0">Email</span>
                            <span class="text-sm font-bold text-forest">neva@student.ub.ac.id</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center py-3 border-b border-slate-50">
                            <span class="w-32 text-[10px] font-black text-slate-300 uppercase tracking-widest mb-1 sm:mb-0">Major</span>
                            <span class="text-sm font-bold text-forest">D3 Teknologi Informasi</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center py-3">
                            <span class="w-32 text-[10px] font-black text-slate-300 uppercase tracking-widest mb-1 sm:mb-0">University</span>
                            <span class="text-sm font-bold text-forest">Universitas Brawijaya</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-50">
                        <h4 class="text-[10px] font-black text-emerald-accent uppercase tracking-[0.3em] mb-6 flex items-center gap-2">
                            <span class="material-symbols-rounded text-sm">assignment</span> Project Status
                        </h4>
                        <div class="space-y-5">
                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase">IoT Hardware Setup</span>
                                    <span class="text-[9px] font-bold text-forest">85%</span>
                                </div>
                                <div class="w-full bg-slate-50 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-forest h-full w-[85%] rounded-full shadow-[0_0_8px_rgba(45,90,39,0.3)]"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase">Dashboard Development</span>
                                    <span class="text-[9px] font-bold text-forest">70%</span>
                                </div>
                                <div class="w-full bg-slate-50 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-forest h-full w-[70%] rounded-full shadow-[0_0_8px_rgba(45,90,39,0.3)]"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-50 flex flex-col">
                         <h4 class="text-[10px] font-black text-emerald-accent uppercase tracking-[0.3em] mb-6 flex items-center gap-2">
                            <span class="material-symbols-rounded text-sm">verified</span> Verified Skills
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-xl text-[9px] font-black border border-emerald-100 uppercase italic">Laravel 11</span>
                            <span class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-xl text-[9px] font-black border border-blue-100 uppercase italic">ESP32 & C++</span>
                            <span class="px-3 py-1.5 bg-orange-50 text-orange-700 rounded-xl text-[9px] font-black border border-orange-100 uppercase italic">Data Analysis</span>
                        </div>
                        <p class="mt-auto text-[9px] text-slate-400 italic leading-relaxed pt-4">
                            "Berfokus pada integrasi sistem cerdas untuk optimasi greenhouse."
                        </p>
                    </div>
                </div>
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