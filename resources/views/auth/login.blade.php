<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SmartGrow Ultimate</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" />

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
    
    <!-- LEFT -->
    <div class="hidden lg:flex lg:w-7/12 relative overflow-hidden bg-forest">
        <div class="absolute inset-0 bg-gradient-to-br from-forest/90 to-emerald-950/80 z-10"></div>
        
        <img src="https://images.unsplash.com/photo-1585320806297-9794b3e4eeae?auto=format&fit=crop&w=1200&q=80" 
             class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-60 scale-110">

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
                &copy; 2026 SmartGrow.
            </div>
        </div>

    <!-- RIGHT -->
    <div class="w-full lg:w-5/12 flex flex-col items-center justify-center p-8 md:p-20 relative bg-white">
            
            <div class="w-full max-w-sm animate-fade-up">
                <header class="mb-10 text-center lg:text-left">
                    <h2 class="text-3xl font-black text-slate-800 tracking-tight">Login Akun Petani</h2>
                    <p class="text-slate-400 text-sm mt-2 font-medium">Masukkan email dan password secara manual.</p>
                </header>

            <!-- ERROR -->
            @if ($errors->any())
                <div class="bg-red-100 text-red-600 p-3 rounded-xl text-sm mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- FORM -->
            <form method="POST" action="/login" class="space-y-6">
                @csrf

                <!-- EMAIL -->
                <div>
                    <label class="text-xs font-bold text-slate-400">Email</label>
                    <input type="email" name="email" required
                        value="{{ old('email') }}"
                        class="w-full p-4 mt-1 border rounded-xl focus:ring-2 focus:ring-forest/20"
                        placeholder="email@gmail.com">
                </div>

                <!-- PASSWORD -->
                <div>
                    <label class="text-xs font-bold text-slate-400">Password</label>
                    <input type="password" name="password" required
                        class="w-full p-4 mt-1 border rounded-xl focus:ring-2 focus:ring-forest/20"
                        placeholder="********">
                </div>

                <!-- BUTTON -->
                <button type="submit"
                    class="w-full bg-forest text-white py-4 rounded-xl font-bold hover:bg-green-900 transition">
                    MASUK SEKARANG
                </button>
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