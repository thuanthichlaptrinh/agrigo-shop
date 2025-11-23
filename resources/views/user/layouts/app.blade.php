<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Organic Shop - Nông sản xanh')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="{{ asset('template/Assets/Bootstrap5/css/bootstrap.min.css') }}" />
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 Free CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <!-- Boxicons CDN (Backup) -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('template/Assets/css/base.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/Assets/css/animation.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/Assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/Assets/css/promo-section.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/Assets/css/articles-section.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/Assets/css/header-dropdown.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/Assets/css/chatbot.css') }}" />
    
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
    @php
        $sharedAlerts = collect([
            ['message' => session('status'), 'type' => session('status_type', 'info')],
            ['message' => session('success'), 'type' => 'success'],
            ['message' => session('error'), 'type' => 'error'],
            ['message' => session('warning'), 'type' => 'warning'],
        ])->filter(fn ($alert) => filled($alert['message'] ?? null))->values()->all();
    @endphp

    <x-alert-stack :messages="$sharedAlerts" />

    <!-- Header -->
    @include('user.partials.header')

    <!-- Main Content -->
    <main class="container" style="margin-top: var(--header-height)">
        @yield('content')
    </main>

    <!-- Chatbot Widget -->
    @include('user.partials.chatbot-widget', [
        'botName' => 'Organic Shop Support',
        'avatarUrl' => asset('template/Assets/Images/logo2.png'),
        'greeting' => 'Xin chào! Chào mừng bạn đến với Organic Shop. Tôi có thể giúp gì cho bạn hôm nay?'
    ])

    <!-- Bootstrap 5 JS -->
    <script src="{{ asset('template/Assets/Bootstrap5/js/bootstrap.min.js') }}"></script>
    <!-- Custom JS -->
    <script src="{{ asset('template/Assets/js/main.js') }}"></script>
    <!-- Chatbot JS -->
    <script src="{{ asset('template/Assets/js/chatbot.js') }}"></script>

    <script>
        (function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                return;
            }

            const endpoints = {
                add: "{{ route('user.cart.add', [], false) }}",
                cart: "{{ route('user.cart.index', [], false) }}"
            };

            const updateCartBadges = (count) => {
                document.querySelectorAll('[data-cart-count-target], [data-cart-count]').forEach(el => {
                    if (typeof count !== 'undefined' && count !== null) {
                        el.textContent = count;
                    }
                });
            };

            const notify = (message, type = 'success') => {
                if (!message) {
                    return;
                }

                let resolvedType = type;
                if (typeof resolvedType === 'boolean') {
                    resolvedType = resolvedType ? 'error' : 'success';
                }
                if (typeof resolvedType !== 'string') {
                    resolvedType = 'success';
                }

                if (window.AppAlert && typeof window.AppAlert.show === 'function') {
                    window.AppAlert.show(message, { type: resolvedType });
                } else {
                    alert(message);
                }

                if (resolvedType === 'error') {
                    console.error(message);
                }
            };

            const parseQuantity = (trigger) => {
                if (trigger.dataset.quantityField) {
                    const field = document.querySelector(trigger.dataset.quantityField);
                    if (field) {
                        const value = parseInt(field.value || field.textContent || '1', 10);
                        if (!Number.isNaN(value) && value > 0) {
                            return value;
                        }
                    }
                }
                const fallback = parseInt(trigger.dataset.quantity || '1', 10);
                return Number.isNaN(fallback) || fallback < 1 ? 1 : fallback;
            };

            const setLoading = (el, isLoading) => {
                if (isLoading) {
                    el.setAttribute('data-loading', 'true');
                    el.classList.add('is-loading');
                } else {
                    el.removeAttribute('data-loading');
                    el.classList.remove('is-loading');
                }
            };

            const handleResponse = (trigger, data) => {
                if (typeof data?.count !== 'undefined') {
                    updateCartBadges(data.count);
                }

                const redirectUrl = trigger.dataset.redirectUrl || (trigger.dataset.goCart === 'true' ? endpoints.cart : null);
                if (redirectUrl) {
                    window.location.href = redirectUrl;
                    return;
                }

                const responseType = typeof data?.type === 'string' ? data.type : 'success';
                notify(data?.message || 'Đã thêm sản phẩm vào giỏ hàng.', responseType);
            };

            document.addEventListener('click', (event) => {
                const trigger = event.target.closest('[data-add-to-cart]');
                if (!trigger) {
                    return;
                }

                event.preventDefault();

                if (trigger.getAttribute('data-loading') === 'true') {
                    return;
                }

                const productId = trigger.dataset.productId;
                if (!productId) {
                    console.warn('Missing product id for cart action.');
                    return;
                }

                const quantity = parseQuantity(trigger);
                const body = new URLSearchParams({ product_id: productId, quantity: quantity });

                setLoading(trigger, true);

                fetch(endpoints.add, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
                    },
                    body
                })
                    .then(async (response) => {
                        if (response.status === 401) {
                            notify('Vui lòng đăng nhập để tiếp tục mua sắm.', 'warning');
                            throw new Error('unauthenticated');
                        }

                        if (!response.ok) {
                            throw new Error('request-failed');
                        }
                        try {
                            return await response.json();
                        } catch (error) {
                            return {};
                        }
                    })
                    .then((data) => handleResponse(trigger, data))
                    .catch((error) => {
                        if (error?.message === 'unauthenticated') {
                            return;
                        }
                        notify('Không thể thêm sản phẩm vào giỏ hàng.', 'error');
                    })
                    .finally(() => setLoading(trigger, false));
            });

            window.CartUI = window.CartUI || {};
            window.CartUI.updateCount = updateCartBadges;
            window.CartUI.notify = notify;
        })();
    </script>
    
    @stack('scripts')
</body>
</html>
