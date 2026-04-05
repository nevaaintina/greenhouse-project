<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Log - SmartGrow</title>
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
            <h1 class="font-bold text-xl uppercase tracking-tighter text-white">SmartGrow</h1>
        </div>
        <nav class="flex-1 space-y-2">
            <a href="/dashboard" class="flex items-center gap-4 p-3 opacity-60 hover:opacity-100 transition">
                <span class="material-symbols-rounded">dashboard</span> Dashboard
            </a>
            <a href="/sensors" class="flex items-center gap-4 p-3 opacity-60 hover:opacity-100 transition">
                <span class="material-symbols-rounded">sensors</span> Sensors
            </a>
            <a href="/logs" class="flex items-center gap-4 bg-white/10 p-3 rounded-2xl transition">
                <span class="material-symbols-rounded">history</span> Log Activity
            </a>
        </nav>
    </aside>

    <main class="flex-1 overflow-y-auto p-8 text-slate-700">
        <header class="flex justify-between items-center mb-10">
            <div>
                <h2 class="text-2xl font-bold text-forest uppercase tracking-tight">Activity Log</h2>
                <p class="text-sm text-slate-400 font-medium">Riwayat kejadian dan aktivitas sistem</p>
            </div>
            <div class="flex items-center gap-4 bg-white p-2 pr-5 rounded-full shadow-sm border border-slate-100">
                <div class="w-10 h-10 bg-forest rounded-full flex items-center justify-center text-white font-bold">N</div>
                <span class="text-sm font-semibold">Neva</span>
            </div>
        </header>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-50 overflow-hidden">
            <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-white">
                <h3 class="font-bold text-forest text-sm uppercase tracking-widest flex items-center gap-2">
                    <span class="material-symbols-rounded text-emerald-accent">list_alt</span> Daftar Riwayat Aktivitas
                </h3>
                <input type="date" class="bg-slate-50 border-none rounded-xl p-3 text-xs text-slate-500 outline-none focus:ring-2 focus:ring-forest/10">
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-50">
                        <tr>
                            <th class="px-8 py-5">Timestamp</th>
                            <th class="px-8 py-5">Event</th>
                            <th class="px-8 py-5">Source</th>
                            <th class="px-8 py-5">Value / Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-8 py-5 text-sm text-slate-400 font-medium">01 Apr, 21:42:05</td>
                            <td class="px-8 py-5 text-sm font-bold text-forest">Penyiraman Dimulai</td>
                            <td class="px-8 py-5">
                                <span class="bg-blue-50 text-blue-600 text-[10px] px-3 py-1 rounded-full font-bold">MANUAL</span>
                            </td>
                            <td class="px-8 py-5 text-sm text-slate-600 font-semibold">500ml</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-8 py-5 text-sm text-slate-400 font-medium">01 Apr, 20:15:10</td>
                            <td class="px-8 py-5 text-sm font-bold text-forest">Update Sensor Suhu</td>
                            <td class="px-8 py-5">
                                <span class="bg-emerald-50 text-emerald-700 text-[10px] px-3 py-1 rounded-full font-bold">SYSTEM</span>
                            </td>
                            <td class="px-8 py-5 text-sm text-slate-600 font-semibold">26.5°C</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-8 py-5 text-sm text-slate-400 font-medium">01 Apr, 19:30:00</td>
                            <td class="px-8 py-5 text-sm font-bold text-forest">Pompa Mati Otomatis</td>
                            <td class="px-8 py-5">
                                <span class="bg-emerald-50 text-emerald-700 text-[10px] px-3 py-1 rounded-full font-bold">SYSTEM</span>
                            </td>
                            <td class="px-8 py-5 text-sm text-slate-600 font-semibold">Durasi 5 Menit</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="p-6 bg-slate-50/30 flex justify-center border-t border-slate-50 text-[10px] font-bold text-slate-400 tracking-widest">
                MENAMPILKAN 10 RIWAYAT TERAKHIR
            </div>
        </div>
    </main>

</body>
</html>