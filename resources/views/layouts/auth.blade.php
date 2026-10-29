<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Account') | MOTOVEX</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#0B0D0E] text-[#F5F7F4] antialiased">
    @include('partials.preloader')
    <div class="mx-auto flex min-h-screen w-full max-w-6xl flex-col px-5 py-8 lg:px-8">
        <header class="flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-xl font-black tracking-[0.2em]" aria-label="MOTOVEX home">
                MOTO<span class="text-[#D4AF37]">VEX</span>
            </a>
            <a href="{{ route('home') }}" class="text-xs text-[#707873] transition hover:text-white">← Back to site</a>
        </header>

        <main class="flex flex-1 items-center justify-center py-10">
            <div class="w-full max-w-md">
                @if(session('success'))
                    <div class="mb-5 rounded-lg border border-[#D4AF37]/30 bg-[#D4AF37]/10 px-4 py-3 text-sm text-[#D4AF37]">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <footer class="pt-6 text-center text-[11px] text-[#707873]">
            Assessment demo project — not for commercial use.
        </footer>
    </div>
</body>
</html>
