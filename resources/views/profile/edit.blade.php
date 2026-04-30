<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - SmartGrow Premium</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script>
        tailwind.config = {
            theme: { extend: { colors: { 'forest': '#2D5A27', 'emerald-accent': '#2E7D32', 'soft-bg': '#F9FBFA' }, fontFamily: { sans: ['Poppins'] } } }
        }
    </script>
</head>
<body class="bg-soft-bg flex h-screen overflow-hidden font-sans text-slate-700">

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

    <main class="flex-1 overflow-y-auto p-5 md:p-10 relative">
        
        <header class="flex justify-between items-center mb-10 mt-12 md:mt-0">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-forest tracking-tighter uppercase">Edit Information</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Perbarui Data Akun Pengelola</p>
            </div>
            <a href="/profile" class="bg-white border-2 border-slate-100 text-slate-400 px-5 py-2 rounded-2xl text-[10px] font-bold hover:bg-slate-50 transition flex items-center gap-2">
                <span class="material-symbols-rounded text-sm">arrow_back</span> KEMBALI
            </a>
        </header>

        <form action="/profile/update" method="POST" class="pb-10">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
                
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-50 flex flex-col items-center text-center">
                        <div class="relative group">
                            <div class="w-32 h-32 bg-forest rounded-[2.5rem] flex items-center justify-center text-white text-6xl font-black shadow-xl border-4 border-emerald-50 overflow-hidden transition-transform group-hover:scale-105">
                                N
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer">
                                    <span class="material-symbols-rounded text-white text-3xl">photo_camera</span>
                                </div>
                            </div>
                        </div>
                        <h3 class="mt-6 text-lg font-black text-forest uppercase tracking-tight">Neva</h3>
                        <div class="flex items-center gap-2 mt-1 px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full">
                            <span class="material-symbols-rounded text-sm">agriculture</span>
                            <span class="text-[10px] font-black uppercase tracking-widest">Petani Modern</span>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-50">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Account Security</span>
                            <span class="px-3 py-1 bg-emerald-500 text-white text-[9px] font-black rounded-lg">VERIFIED</span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-sm border border-slate-50">
                        <h4 class="text-[10px] font-black text-emerald-accent uppercase tracking-[0.3em] mb-8 flex items-center gap-2">
                            <span class="material-symbols-rounded text-sm">person_edit</span> Personal Information
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            <div class="flex flex-col py-2 border-b border-slate-50">
                                <label class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">Full Name</label>
                                <input type="text" name="name" value="Neva Ardiansyah" class="bg-transparent text-sm font-bold text-forest outline-none">
                            </div>
                            <div class="flex flex-col py-2 border-b border-slate-50">
                                <label class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">Email Address</label>
                                <input type="email" name="email" value="neva@student.ub.ac.id" class="bg-transparent text-sm font-bold text-forest outline-none">
                            </div>
                            <div class="flex flex-col py-2 border-b border-slate-50">
                                <label class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">Institution</label>
                                <input type="text" name="institution" value="Universitas Brawijaya" class="bg-transparent text-sm font-bold text-forest outline-none">
                            </div>
                            <div class="flex flex-col py-2 border-b border-slate-50">
                                <label class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">System Role</label>
                                <select name="role" class="bg-transparent text-sm font-bold text-forest outline-none cursor-pointer appearance-none uppercase">
                                    <option value="Petani" selected>Petani</option>
                                    <option value="IoT Tech">Teknisi IoT</option>
                                    <option value="Admin">Admin</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-8">
                            <label class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-2 block">Project Bio</label>
                            <textarea name="bio" rows="2" class="w-full bg-soft-bg rounded-2xl p-4 text-[11px] font-medium text-slate-500 outline-none border-2 border-transparent focus:border-forest/20 transition-all resize-none italic">"Mengelola lahan cerdas berbasis teknologi untuk masa depan pangan yang lebih baik."</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-5">
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-50 h-full">
                        <h4 class="text-[10px] font-black text-emerald-accent uppercase tracking-[0.3em] mb-6 flex items-center gap-2">
                            <span class="material-symbols-rounded text-sm">lock_person</span> Security
                        </h4>
                        <div class="space-y-4">
                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-slate-300 uppercase ml-1">Current Password</label>
                                <input type="password" placeholder="••••••••" class="w-full bg-soft-bg rounded-xl p-3 text-xs font-bold text-forest outline-none border-2 border-transparent focus:border-forest/10 transition-all">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-slate-300 uppercase ml-1">New Password</label>
                                <input type="password" placeholder="Min. 8 characters" class="w-full bg-soft-bg rounded-xl p-3 text-xs font-bold text-forest outline-none border-2 border-transparent focus:border-forest/10 transition-all">
                            </div>
                        </div>
                        <p class="mt-4 text-[8px] text-slate-400 italic font-medium tracking-tight">Biarkan kosong jika tidak ingin merubah sandi.</p>
                    </div>
                </div>

                <div class="lg:col-span-7">
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-50">
                        <h4 class="text-[10px] font-black text-emerald-accent uppercase tracking-[0.3em] mb-6 flex items-center gap-2">
                            <span class="material-symbols-rounded text-sm">notifications_active</span> Alert Preferences
                        </h4>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-4 bg-soft-bg rounded-2xl hover:bg-emerald-50/50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-white text-forest rounded-xl flex items-center justify-center shadow-sm">
                                        <span class="material-symbols-rounded text-lg">water_drop</span>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-forest uppercase">Soil Moisture Alert</p>
                                        <p class="text-[9px] text-slate-400 font-medium">Peringatan saat tanah terlalu kering</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-forest"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-soft-bg rounded-2xl hover:bg-orange-50/50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-white text-orange-500 rounded-xl flex items-center justify-center shadow-sm">
                                        <span class="material-symbols-rounded text-lg">thermostat</span>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-forest uppercase">System Overheat</p>
                                        <p class="text-[9px] text-slate-400 font-medium">Peringatan saat suhu > 35°C</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-forest"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-3 mt-8">
                <button type="reset" class="px-8 py-4 rounded-2xl text-[10px] font-black text-slate-400 hover:bg-white transition uppercase tracking-widest">
                    BATALKAN
                </button>
                <button type="submit" class="bg-forest text-white px-10 py-5 rounded-2xl text-[10px] font-black hover:bg-emerald-900 transition-all shadow-xl shadow-forest/20 flex items-center justify-center gap-3 active:scale-95">
                    <span class="material-symbols-rounded text-sm">cloud_upload</span> SIMPAN PERUBAHAN
                </button>
            </div>
        </form>
    </main>

    <script>
        // SIDEBAR MOBILE SCRIPT
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