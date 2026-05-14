<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Sistem Sawit</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" sizes="16x16">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <!-- Remix Icon -->
    <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}">
    <!-- Apex Chart -->
    <link rel="stylesheet" href="{{ asset('assets/css/lib/apexcharts.css') }}">
    <!-- Data Table -->
    <link rel="stylesheet" href="{{ asset('assets/css/lib/dataTables.min.css') }}">
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @stack('styles')
</head>
<body class="font-inter bg-neutral-100 dark:bg-neutral-900 dark:text-white">

    <!-- Sidebar Sawit -->
    @include('sawit.partials.sidebar')

    <!-- Main Wrapper -->
    <main class="dashboard-main">
        <!-- Top Navbar Sawit -->
        @include('sawit.partials.navbar')

        <!-- Content -->
        <div class="dashboard-main-body">
            @yield('content')
        </div>
    </main>

    <!-- jQuery -->
    <script src="{{ asset('assets/js/lib/jquery-3.7.1.min.js') }}"></script>
    <!-- Apex Chart -->
    <script src="{{ asset('assets/js/lib/apexcharts.min.js') }}"></script>
    <!-- Iconify -->
    <script src="{{ asset('assets/js/lib/iconify-icon.min.js') }}"></script>
    <!-- Flowbite -->
    <script src="{{ asset('assets/js/flowbite.min.js') }}"></script>
    <!-- Main JS -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
