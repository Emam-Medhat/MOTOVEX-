@php
    $group = config('group.name');
    $tagline = config('group.tagline');
@endphp
<!DOCTYPE html>
<html lang="en" dir="ltr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title', $group . ' | Premium Motorcycle Group')</title>
    <meta name="description" content="@yield('description', $group . ' — a multi-brand motorcycle group engineered for the next ride. Urban, adventure, performance and premium machines across the Middle East.')">
    <meta name="theme-color" content="#07090a">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('og-title', $group . ' | Premium Motorcycle Group')">
    <meta property="og:description" content="@yield('og-description', $group . ' — engineered for the next ride.')">
    <meta property="og:image" content="{{ asset('images/bikes/vayu-r1-1100.svg') }}">

    {{-- Preload hero image --}}
    <link rel="preload" as="image" href="@yield('hero-image', $featuredMotorcycle->image_url ?? '')" fetchpriority="high">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-apex-bg text-apex-text antialiased selection:bg-accent/30">

    {{-- Preloader --}}
    @include('partials.apex.preloader')

    {{-- Header + overlay menu --}}
    @include('partials.apex.header')

    {{-- Success toast --}}
    @if(session('success'))
        <div role="status" class="fixed top-20 inset-x-0 z-[60] mx-auto max-w-md rounded-xl glass border border-accent/40 px-5 py-3 text-sm text-center text-apex-text">
            {{ session('success') }}
        </div>
    @endif

    <main id="main">
        @yield('content')
    </main>

    {{-- Footer (hidden on pages that define @section('no-footer')) --}}
    @if(!$__env->hasSection('no-footer'))
        @include('partials.apex.footer')
    @endif

    @stack('scripts')
</body>
</html>
