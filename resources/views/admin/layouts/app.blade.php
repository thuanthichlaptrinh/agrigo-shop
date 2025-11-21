<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Organic Shop')</title>
    
    <!-- Boxicons -->
    <link href="{{ asset('template/Assets/vendor/boxicons/boxicons.min.css') }}" rel="stylesheet" />
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="{{ asset('template/Assets/vendor/fontawesome/6.5.2/css/all.min.css') }}" />
    
    <!-- Admin CSS -->
    <link rel="stylesheet" href="{{ asset('template/Admin/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/Admin/products.css') }}" />
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <section id="content">
        <!-- Navbar -->
        @include('admin.partials.navbar')

        <!-- Main -->
        <main>
            @yield('content')
        </main>
    </section>

    <!-- Admin JS -->
    <script src="{{ asset('template/Admin/script.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
