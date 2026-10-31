<!DOCTYPE html>
<html lang="{{ $locale ?? app()->getLocale() }}" dir="{{ $dir ?? (app()->getLocale() === 'ar' ? 'rtl' : 'ltr') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MOTOVEX | ' . __('messages.site_tagline'))</title>
    <meta name="description" content="@yield('description', __('messages.site_description'))">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @stack('head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.rtl-styles')
</head>
<body class="min-h-screen bg-[#0B0D0E] text-[#F5F7F4] antialiased">
    @include('partials.preloader')
    @include('partials.navbar')

    @if(session('success'))
        <div class="border-b border-[#D4AF37]/20 bg-[#D4AF37]/10 px-5 py-3 text-center text-sm text-[#D4AF37]">
            {{ session('success') }}
        </div>
    @endif

    @yield('content')

    @include('partials.footer')

    <script>
        const toggle = document.getElementById('nav-toggle');
        const mobile = document.getElementById('nav-mobile');
        if (toggle && mobile) {
            toggle.addEventListener('click', () => {
                const open = mobile.classList.toggle('hidden') === false;
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            });
            mobile.querySelectorAll('.nav-mobile-link').forEach((link) => {
                link.addEventListener('click', () => {
                    mobile.classList.add('hidden');
                    toggle.setAttribute('aria-expanded', 'false');
                });
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
