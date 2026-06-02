<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
    content="width=device-width, initial-scale=1.0">

    <title>

        @yield('title', 'SmartGrow')

    </title>

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

<body class="font-sans bg-softBg">

    @yield('content')

</body>

</html>