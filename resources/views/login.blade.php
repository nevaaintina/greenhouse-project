<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SmartGrow Ultimate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script>
        tailwind.config = {
            theme: { 
                extend: { 
                    colors: { 'forest': '#2D5A27', 'emerald-accent': '#2E7D32', 'soft-bg': '#F9FBFA' }, 
                    fontFamily: { sans: ['Poppins'] } 
                } 
            }
        }
    </script>
    <style>
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up { animation: fadeUp 0.8s ease-out forwards; }
    </style>
</head>
<body class="bg-soft-bg font-sans overflow-hidden">

    <div class="flex h-screen w-full">
        
        <div class="hidden lg:flex lg:w-7/12 relative overflow-hidden bg-forest">
            <div class="absolute inset-0 bg-gradient-to-br from-forest/90 to-emerald-950/80 z-10"></div>
            
            <img src="https://images.unsplash.com/photo-1585320806297-9794b3e4eeae?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" 
                 class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-60 scale-110" 
                 alt="Greenhouse Visual">

            <div class="relative z-20 m-auto p-16 w-full animate-fade-up">
                <div class="w-20 h-20 bg-white/10 backdrop-blur-xl rounded-[2.5rem] flex items-center justify-center mb-8 border border-white/20">
                    <span class="material-symbols-rounded text-white text-4xl">potted_plant</span>
                </div>
                <h1 class="text-6xl font-black text-white leading-tight tracking-tighter italic">
                    SmartGrow <br> <span class="text-emerald-300 not-italic">Ultimate.</span>
                </h1>
                <p class="text-white/70 text-lg mt-6 max-w-lg leading-relaxed font-light italic">
                    "Skip repetitive gardening tasks. Get highly productive through IoT automation and monitoring for your Greenhouse."
                </p>
            </div>

            <div class="absolute bottom-10 left-16 z-20 text-white/30 text-[10px] font-bold tracking-[0.3em] uppercase">
                &copy; 2026 SmartGrow Neva.
            </div>
        </div>

        <div class="w-full lg:w-5/12 flex flex-col items-center justify-center p-8 md:p-20 relative bg-white">
            
            <div class="w-full max-w-sm animate-fade-up">
                <header class="mb-10 text-center lg:text-left">
                    <h2 class="text-3xl font-black text-slate-800 tracking-tight">Login Akun Petani</h2>
                    <p class="text-slate-400 text-sm mt-2 font-medium">Masukkan email dan password secara manual.</p>
                </header>

                <form action="/dashboard" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-300 uppercase tracking-widest ml-1">Email Address</label>
                        <div class="relative group">
                            <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-forest transition">mail</span>
                            <input type="email" required 
                                class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-forest/10 focus:bg-white transition text-sm font-semibold text-slate-600" 
                                placeholder="neva@student.ub.ac.id">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <label class="text-[10px] font-black text-slate-300 uppercase tracking-widest ml-1">Password</label>
                        </div>
                        <div class="relative group">
                            <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-forest transition">lock</span>
                            <input type="password" required 
                                class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-forest/10 focus:bg-white transition text-sm font-semibold text-slate-600" 
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" 
                            class="w-full bg-forest text-white py-5 rounded-2xl font-black text-xs shadow-xl shadow-forest/20 hover:bg-emerald-950 transition-all active:scale-95 uppercase tracking-widest">
                            MASUK SEKARANG
                        </button>
                    </div>
                </form>

                <footer class="mt-12 text-center">
                    <p class="text-[10px] text-slate-300 font-bold uppercase tracking-widest leading-relaxed">
                        Secure Connection <br>
                        <span class="text-emerald-500 italic">SmartGrow Management System</span>
                    </p>
                </footer>
            </div>
        </div>
    </div>

</body>
</html>