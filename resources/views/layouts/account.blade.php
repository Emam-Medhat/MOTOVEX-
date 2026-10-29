<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Account') | MOTOVEX</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#0B0D0E] text-[#F5F7F4] antialiased">
    @include('partials.preloader')
    @include('partials.navbar')

    <div class="mx-auto max-w-7xl px-5 py-10 lg:px-8">
        <header class="mb-8">
            <p class="eyebrow">My Account</p>
            <h1 class="mt-2 text-3xl font-black uppercase tracking-tight">@yield('heading', 'Overview')</h1>
            <p class="mt-2 text-sm text-[#707873]">Welcome back, {{ auth()->user()->name }}.</p>
        </header>

        @if(session('success'))
            <div class="mb-6 rounded-lg border border-[#D4AF37]/30 bg-[#D4AF37]/10 px-4 py-3 text-sm text-[#D4AF37]">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-8 lg:grid-cols-[240px_1fr]">
            <aside>
                <nav class="flex gap-2 overflow-x-auto rounded-xl border border-[#292F2C] bg-[#181C1D] p-2 lg:flex-col lg:overflow-visible"
                     aria-label="Account navigation">
                    @foreach([
                        ['account.dashboard',  'Overview',     'account.dashboard'],
                        ['account.enquiries',  'My Enquiries', 'account.enquiries'],
                        ['account.profile',    'Profile',      'account.profile'],
                        ['account.security',   'Security',     'account.security'],
                    ] as [$route, $label, $pattern])
                        <a href="{{ route($route) }}"
                           class="whitespace-nowrap rounded-lg px-4 py-2.5 text-sm transition
                                  {{ request()->routeIs($pattern) ? 'bg-[#D4AF37]/10 font-semibold text-[#D4AF37]' : 'text-[#A1A7A2] hover:bg-[#292F2C] hover:text-white' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                    <form method="POST" action="{{ route('logout') }}" class="lg:mt-2 lg:border-t lg:border-[#292F2C] lg:pt-2">
                        @csrf
                        <button type="submit" class="w-full whitespace-nowrap rounded-lg px-4 py-2.5 text-start text-sm text-[#EF6464] transition hover:bg-[#EF6464]/10">
                            Sign Out
                        </button>
                    </form>
                </nav>
            </aside>

            <div class="min-w-0">
                @yield('content')
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>
