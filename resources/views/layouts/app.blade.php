<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SmartGrow</title>

    <!-- TAILWIND -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FONT -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- MATERIAL ICON -->
    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" />

    <!-- TAILWIND CONFIG -->
    <script>

        tailwind.config =
        {
            theme:
            {
                extend:
                {
                    colors:
                    {
                        forest: '#2D5A27',
                        softBg: '#F9FBFA'
                    },

                    fontFamily:
                    {
                        sans: ['Poppins', 'sans-serif']
                    }
                }
            }
        }

    </script>

</head>

<body class="bg-softBg flex min-h-screen">

    {{-- =========================
    SUCCESS TOAST
    ========================= --}}

    @if(session('success'))

    <div
        id="successToast"

        class="fixed top-5 right-5 z-[9999]
        animate-toast">

        <div
            class="bg-emerald-500 text-white px-5 py-4
            rounded-3xl shadow-2xl
            flex items-start gap-4 min-w-[320px]">

            <!-- ICON -->
            <div
                class="w-12 h-12 rounded-2xl
                bg-white/20 flex items-center justify-center">

                <span class="material-symbols-rounded text-3xl">
                    check_circle
                </span>

            </div>

            <!-- TEXT -->
            <div class="flex-1">

                <h4 class="font-black uppercase tracking-wide text-sm">

                    Success

                </h4>

                <p class="text-xs opacity-90 mt-1 leading-relaxed">

                    {{ session('success') }}

                </p>

            </div>

            <!-- CLOSE -->
            <button
                onclick="closeToast()"

                class="opacity-70 hover:opacity-100 transition">

                <span class="material-symbols-rounded text-xl">
                    close
                </span>

            </button>

        </div>

    </div>

    @endif


    {{-- =========================
    ERROR TOAST
    ========================= --}}

    @if(session('error'))

    <div
        id="errorToast"

        class="fixed top-5 right-5 z-[9999]
        animate-toast">

        <div
            class="bg-red-500 text-white px-5 py-4
            rounded-3xl shadow-2xl
            flex items-start gap-4 min-w-[320px]">

            <!-- ICON -->
            <div
                class="w-12 h-12 rounded-2xl
                bg-white/20 flex items-center justify-center">

                <span class="material-symbols-rounded text-3xl">
                    warning
                </span>

            </div>

            <!-- TEXT -->
            <div class="flex-1">

                <h4 class="font-black uppercase tracking-wide text-sm">

                    Warning

                </h4>

                <p class="text-xs opacity-90 mt-1 leading-relaxed">

                    {{ session('error') }}

                </p>

            </div>

            <!-- CLOSE -->
            <button
                onclick="closeErrorToast()"

                class="opacity-70 hover:opacity-100 transition">

                <span class="material-symbols-rounded text-xl">
                    close
                </span>

            </button>

        </div>

    </div>

    @endif


    <!-- SIDEBAR -->
    @include('components.sidebar')


    <!-- MAIN CONTENT -->
    <main class="flex-1 p-6 md:p-8 text-slate-700 overflow-y-auto">

        @yield('content')

    </main>


    {{-- =========================
    SCRIPT
    ========================= --}}

    <script>

    function closeToast()
    {
        const toast =
            document.getElementById('successToast');

        if (toast)
        {
            toast.remove();
        }
    }

    function closeErrorToast()
    {
        const toast =
            document.getElementById('errorToast');

        if (toast)
        {
            toast.remove();
        }
    }

    // AUTO CLOSE
    setTimeout(() =>
    {
        closeToast();

        closeErrorToast();

    }, 4000);

    </script>


    {{-- =========================
    STYLE
    ========================= --}}

    <style>

    @keyframes toastSlide
    {
        0%
        {
            transform: translateX(120%);
            opacity: 0;
        }

        100%
        {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .animate-toast
    {
        animation: toastSlide .35s ease;
    }

    </style>

</body>

</html>