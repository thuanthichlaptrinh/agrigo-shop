<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Organic Shop - Nông sản xanh')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="{{ asset('template/Assets/Bootstrap5/css/bootstrap.min.css') }}" />
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('template/Assets/css/base.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/Assets/css/animation.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/Assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/Assets/css/promo-section.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/Assets/css/articles-section.css') }}" />
    
    <!-- Remix Icon -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.4.0/remixicon.css"
        integrity="sha512-hH7VMMVfPgfkpYx2GazOEG6RrYM+y8cS7FzccwBTWQeyhPv01XYk0MVcuhh4EAimOELWvqKjhNwes/UsYoyN6w=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    />
    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    @include('partials.header')

    <!-- Main Content -->
    <main class="container" style="margin-top: var(--header-height)">
        @yield('content')
    </main>

    <!-- Fixed Icons -->
    <div class="fixed-icon">
        <a href="#" class="scroll-up">
            <img src="{{ asset('template/Assets/Images/up-arrow1.png') }}" alt="Scroll Up" />
        </a>
        <a href="">
            <img src="{{ asset('template/Assets/Icon/facebook.png') }}" alt="Facebook" />
        </a>
        <a href="">
            <img src="{{ asset('template/Assets/Icon/youtube.png') }}" alt="YouTube" />
        </a>
        <a href="">
            <img src="{{ asset('template/Assets/Images/phone-call.png') }}" alt="Phone" />
        </a>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="{{ asset('template/Assets/Bootstrap5/js/bootstrap.min.js') }}"></script>
    
    <!-- Custom JS -->
    <script src="{{ asset('template/Assets/js/main.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
