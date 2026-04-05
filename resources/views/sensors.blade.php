<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sensor Details - SmartGrow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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
</head>
<body class="bg-soft-bg flex h-screen overflow-hidden">

    <aside class="w-64 bg-forest text-white flex flex-col p-6 shadow-2xl">
        <div class="flex items-center gap-3 mb-10 px-2">
            <span class="material-symbols-rounded text-emerald-400 text-3xl">potted_plant</span>
            <h1 class="font-bold text-xl uppercase tracking-tighter">SmartGrow</h1>
        </div>
        <nav class="flex-1 space-y-2">
            <a href="/dashboard" class="flex items-center gap-4 p-3 opacity-60 hover:opacity-100 transition">
                <span class="material-symbols-rounded">dashboard</span> Dashboard
            </a>
            <a href="/sensors" class="flex items-center gap-4 bg-white/10 p-3 rounded-2xl">
                <span class="material-symbols-rounded">sensors</span> Sensors
            </a>
            <a href="/logs" class="flex items-center gap-4 p-3 opacity-60 hover:opacity-100 transition">
                <span class="material-symbols-rounded">history</span> Log Activity
            </a>
        </nav>
    </aside>

    <main class="flex-1 overflow-y-auto p-8 text-slate-700">
        <header class="flex justify-between items-center mb-10">
            <div>
                <h2 class="text-2xl font-bold text-forest uppercase tracking-tight">Detail Perangkat Sensor</h2>
                <p class="text-sm text-slate-400 font-medium">Informasi teknis dan status hardware IoT</p>
            </div>
            <a href="/profile" class="flex items-center gap-4 bg-white p-2 pr-5 rounded-full shadow-sm border border-slate-100">
                <div class="w-10 h-10 bg-forest rounded-full flex items-center justify-center text-white font-bold">N</div>
                <span class="text-sm font-semibold">Neva</span>
            </a>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-50">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="p-4 bg-orange-50 text-orange-500 rounded-3xl">
                            <span class="material-symbols-rounded text-3xl">thermostat</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-forest">Sensors Node 01</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Type: DHT22 (Air)</p>
                        </div>
                    </div>
                    <span class="px-4 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-full uppercase">Online</span>
                </div>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-5 bg-slate-50 rounded-3xl">
                        <span class="text-sm font-medium text-slate-500">Suhu Udara</span>
                        <span class="font-bold text-xl text-forest">26.5°C</span>
                    </div>
                    <div class="flex justify-between items-center p-5 bg-slate-50 rounded-3xl">
                        <span class="text-sm font-medium text-slate-500">Kelembaban Udara</span>
                        <span class="font-bold text-xl text-forest">55%</span>
                    </div>
                    <div class="flex justify-between items-center p-5 bg-slate-50 rounded-3xl">
                        <span class="text-sm font-medium text-slate-500">Status Alat</span>
                        <span class="text-[10px] font-bold text-emerald-600 uppercase">Optimal</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-50">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="p-4 bg-blue-50 text-blue-500 rounded-3xl">
                            <span class="material-symbols-rounded text-3xl">sensors</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-forest">Sensors Node 02</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Type: Capacitive & LDR</p>
                        </div>
                    </div>
                    <span class="px-4 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-full uppercase">Online</span>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center p-5 bg-slate-50 rounded-3xl">
                        <span class="text-sm font-medium text-slate-500">Kelembaban Tanah</span>
                        <div class="text-right">
                            <span class="font-bold text-xl text-forest">70%</span>
                            <p class="text-[9px] text-emerald-600 font-bold uppercase">Tanah Lembab</p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center p-5 bg-slate-50 rounded-3xl">
                        <span class="text-sm font-medium text-slate-500">Intensitas Cahaya</span>
                        <div class="text-right">
                            <span class="font-bold text-xl text-forest">450 Lux</span>
                            <p class="text-[9px] text-orange-500 font-bold uppercase">Gelap (Lampu ON)</p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center p-5 bg-slate-50 rounded-3xl">
                        <span class="text-sm font-medium text-slate-500">Terakhir Update</span>
                        <span class="font-bold text-slate-400 text-xs tracking-tight">1 Menit yang lalu</span>
                    </div>
                </div>
            </div>
            
        </div>
        
        <div class="mt-8 p-6 bg-forest/5 rounded-[2rem] border border-forest/10 flex items-center justify-center gap-4">
            <span class="material-symbols-rounded text-forest">info</span>
            <p class="text-xs text-forest/70 font-medium italic text-center">
                Halaman ini menampilkan detail teknis per modul sensor. Untuk kontrol manual pompa dan lampu, silakan kembali ke <a href="/dashboard" class="font-bold underline">Dashboard</a>.
            </p>
        </div>
    </main>

</body>
</html>