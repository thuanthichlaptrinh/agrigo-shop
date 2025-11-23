<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Organic Shop')</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 Free CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <!-- Boxicons CDN (Backup) -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        
    <!-- Admin CSS -->
    <link rel="stylesheet" href="{{ asset('template/Admin/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/Admin/products.css') }}" />
    
    @stack('styles')
</head>
<body>
    @php
        $sharedAlerts = collect([
            ['message' => session('status'), 'type' => session('status_type', 'info')],
            ['message' => session('success'), 'type' => 'success'],
            ['message' => session('error'), 'type' => 'error'],
            ['message' => session('warning'), 'type' => 'warning'],
        ])->filter(fn ($alert) => filled($alert['message'] ?? null))->values()->all();
    @endphp

    <x-alert-stack :messages="$sharedAlerts" />

    <!-- SIDEBAR -->
    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <section id="content">
        <!-- Navbar -->
        @include('admin.partials.navbar')

        <!-- Main -->
        <main style="margin-top: 64px;">
            @yield('content')
        </main>
    </section>

    <!-- Vendor JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Admin JS -->
    <script src="{{ asset('template/Admin/script.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
