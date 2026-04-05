<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = { theme: { extend: { colors: { 'forest': '#2D5A27', 'emerald': '#2E7D32' }, fontFamily: { sans: ['Poppins'] } } } }
    </script>
</head>
<body class="bg-slate-50 h-screen flex items-center justify-center p-6">
    <div class="bg-white w-full max-w-md p-10 rounded-[2.5rem] shadow-xl shadow-emerald-900/5 border border-slate-100">
        <div class="text-center mb-10">
            <div class="bg-forest w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-forest/20">
                <span class="text-3xl text-white">🌿</span>
            </div>
            <h1 class="text-2xl font-bold text-forest">SmartGrow</h1>
            <p class="text-slate-400 text-sm">Monitoring Greenhouse Krisan</p>
        </div>

        <form action="/dashboard" class="space-y-6">
            <div>
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Email</label>
                <input type="email" class="w-full mt-2 p-4 bg-slate-50 border border-slate-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald/20 transition" placeholder="neva@student.ub.ac.id">
            </div>
            <div>
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Password</label>
                <input type="password" class="w-full mt-2 p-4 bg-slate-50 border border-slate-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald/20 transition" placeholder="••••••••">
            </div>
            <button type="submit" class="w-full bg-forest text-white py-4 rounded-2xl font-bold shadow-lg shadow-forest/20 hover:bg-emerald transition">MASUK KE DASHBOARD</button>
        </form>
    </div>
</body>
</html>