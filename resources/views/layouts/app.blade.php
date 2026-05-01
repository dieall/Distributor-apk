<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Distributor APK</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}" sizes="16x16">
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

    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Wrapper -->
    <main class="dashboard-main">
        <!-- Top Navbar -->
        @include('partials.navbar')

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
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function formatRibuan(angka) {
            if (angka === null || angka === undefined) return '';
            let number_string = angka.toString().replace(/[^,\d]/g, ''),
                split   = number_string.split(','),
                sisa    = split[0].length % 3,
                rupiah  = split[0].substr(0, sisa),
                ribuan  = split[0].substr(sisa).match(/\d{3}/gi);
            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            return rupiah + (split[1] !== undefined ? ',' + split[1] : '');
        }

        // Initialize formatting for inputs
        const initRibuan = () => {
            document.querySelectorAll('.input-ribuan').forEach(input => {
                if(input.value) input.value = formatRibuan(input.value);
            });
        };
        initRibuan();

        // Format as user types using event delegation
        document.body.addEventListener('input', function(e) {
            if (e.target && e.target.classList.contains('input-ribuan')) {
                e.target.value = formatRibuan(e.target.value);
            }
        });

        // Strip dots on submit
        document.body.addEventListener('submit', function(e) {
            if (e.target && e.target.tagName === 'FORM') {
                e.target.querySelectorAll('.input-ribuan').forEach(input => {
                    input.value = input.value.replace(/\./g, '');
                });
            }
        });
        
        // Expose function globally if needed
        window.formatRibuan = formatRibuan;
    });
    </script>
    @stack('scripts')
</body>
</html>
