<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartGrow</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" />

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        forest: '#2D5A27',
                        softBg: '#F9FBFA'
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif']
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-softBg flex min-h-screen">

    @include('components.sidebar')

    <main class="flex-1 p-6 md:p-8 text-slate-700">
        @yield('content')
    </main>

</body>
</html>