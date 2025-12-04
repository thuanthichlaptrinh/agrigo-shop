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
    
    <!-- Pagination CSS -->
    <style>
        /* Pagination Wrapper - Shared styles giống trang users */
        .pagination-wrapper {
            margin: 28px 0 0;
            display: flex;
            justify-content: center;
        }

        .pagination-wrapper nav {
            display: inline-flex;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.1);
            border-radius: 14px;
            padding: 4px;
            background: #fff;
        }

        .pagination-wrapper .pagination {
            margin: 0;
            gap: 6px;
        }

        .pagination-wrapper .page-item:first-child .page-link,
        .pagination-wrapper .page-item:last-child .page-link {
            border-radius: 10px;
        }

        .pagination-wrapper .page-link {
            border: 1px solid transparent;
            border-radius: 10px !important;
            padding: 8px 16px;
            color: #435ebe;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .pagination-wrapper .page-link:hover {
            background: rgba(67, 94, 190, 0.08);
            border-color: rgba(67, 94, 190, 0.2);
            color: #2b3f91;
        }

        .pagination-wrapper .page-item.active .page-link {
            background: linear-gradient(135deg, #435ebe 0%, #6f70f5 100%);
            color: #fff;
            border-color: transparent;
            box-shadow: 0 10px 20px rgba(67, 94, 190, 0.25);
        }

        .pagination-wrapper .page-item.disabled .page-link {
            color: #a0a7c4;
            background: transparent;
        }
    </style>
    
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
