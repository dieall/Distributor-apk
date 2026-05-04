<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') - Distributor APK</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" sizes="16x16">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <!-- Remix Icon -->
    <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}">
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @stack('styles')
</head>
<body class="dark:bg-neutral-800 bg-neutral-100 dark:text-white font-inter">

    @yield('content')

    <!-- jQuery -->
    <script src="{{ asset('assets/js/lib/jquery-3.7.1.min.js') }}"></script>
    <!-- Iconify -->
    <script src="{{ asset('assets/js/lib/iconify-icon.min.js') }}"></script>
    <!-- Main JS -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <script>
        function initializePasswordToggle(toggleSelector) {
            $(toggleSelector).on('click', function () {
                $(this).toggleClass("ri-eye-off-line");
                var input = $($(this).attr("data-toggle"));
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });
        }
        initializePasswordToggle('.toggle-password');
    </script>
</body>
</html>
