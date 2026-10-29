{{-- =====================================================================
     UNIFIED SITE NAVBAR — shared across every public page.
     Source of truth: /motorcycles (Shop) page header.
     Fully responsive: mobile → tablet → desktop → ultrawide.
     ===================================================================== --}}

{{-- TOP ANNOUNCEMENT BAR --}}
<div dir="ltr" class="w-full bg-slate-900 px-3 py-2 text-center text-[10px] font-semibold leading-tight text-white sm:text-xs">
    <i class="fa-solid fa-motorcycle mr-1.5 text-brand-400"></i>
    <span class="hidden sm:inline">2026 Models In Stock · Free Test Rides · Certified Inspections · Nationwide Delivery</span>
    <span class="sm:hidden">Free Test Rides · Nationwide Delivery</span>
</div>

{{-- MAIN HEADER NAV --}}
<header dir="ltr" class="sticky top-0 z-50 w-full border-b border-slate-200 bg-white/95 backdrop-blur-xl shadow-sm">
    <div class="mx-auto flex h-16 w-full max-w-[1600px] items-center gap-3 px-4 sm:px-6 lg:px-8 xl:px-10">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2.5 sm:gap-3 group">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-base text-white shadow-md shadow-brand-500/25 transition-transform duration-300 group-hover:scale-105 sm:h-10 sm:w-10 sm:text-lg">
                <i class="fa-solid fa-motorcycle"></i>
            </span>
            <div class="leading-none">
                <strong class="block text-lg font-extrabold tracking-tight text-slate-900 sm:text-xl">MOTOVEX</strong>
                <small class="hidden text-[9px] font-bold uppercase tracking-[0.2em] text-brand-600 sm:block">Premium Motorcycles</small>
            </div>
        </a>

        {{-- Search bar (tablet + up) --}}
        <form method="GET" action="{{ route('motorcycles.index') }}" class="mx-2 hidden min-w-0 flex-1 md:flex lg:mx-4">
            <div class="relative w-full">
                <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search motorcycles, brands, categories..."
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-sm text-slate-800 outline-none transition focus:border-brand-500 focus:bg-white">
            </div>
        </form>

        {{-- Desktop nav links --}}
        <nav class="hidden shrink-0 items-center gap-5 text-sm font-medium text-slate-500 lg:flex xl:gap-7">
            <a href="{{ route('home') }}" class="whitespace-nowrap transition hover:text-slate-900 {{ request()->routeIs('home') ? 'text-brand-600 font-semibold' : '' }}">Home</a>
            <a href="{{ route('motorcycles.index') }}" class="whitespace-nowrap transition hover:text-slate-900 {{ request()->routeIs('motorcycles.*') ? 'text-brand-600 font-semibold' : '' }}">Shop</a>
            <a href="{{ route('brands.index') }}" class="whitespace-nowrap transition hover:text-slate-900 {{ request()->routeIs('brands.*') ? 'text-brand-600 font-semibold' : '' }}">Brands</a>
            <a href="{{ route('services') }}" class="whitespace-nowrap transition hover:text-slate-900 {{ request()->routeIs('services') ? 'text-brand-600 font-semibold' : '' }}">Services</a>
            <a href="{{ route('contact') }}" class="whitespace-nowrap transition hover:text-slate-900 {{ request()->routeIs('contact') ? 'text-brand-600 font-semibold' : '' }}">Contact</a>
        </nav>

        {{-- Right actions --}}
        <div class="ml-auto flex shrink-0 items-center gap-2 lg:ml-3">
            <a href="{{ route('cart.index') }}" aria-label="Cart"
               class="hidden h-10 w-10 items-center justify-center rounded-full border border-slate-200 text-slate-600 transition hover:border-brand-300 hover:text-brand-600 sm:flex">
                <i class="fa-solid fa-cart-shopping"></i>
            </a>

            @auth
                <a href="{{ route('account.dashboard') }}" class="hidden items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 transition hover:border-brand-300 sm:inline-flex">
                    <i class="fa-solid fa-user"></i> Account
                </a>
            @else
                <a href="{{ route('login') }}" class="hidden items-center gap-2 rounded-full bg-brand-600 px-5 py-2.5 text-xs font-bold text-white shadow-md shadow-brand-500/20 transition hover:bg-brand-500 sm:inline-flex">
                    Sign In
                </a>
            @endauth

            <button id="menuButton" type="button" aria-label="Open menu" aria-expanded="false"
                    class="flex h-10 w-10 items-center justify-center rounded-full text-slate-600 transition hover:bg-slate-100 lg:hidden">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>

    {{-- Mobile / tablet menu --}}
    <nav id="mobileMenu" class="hidden w-full border-t border-slate-100 bg-white px-4 pb-5 pt-4 sm:px-6 lg:hidden">
        {{-- Mobile search --}}
        <form method="GET" action="{{ route('motorcycles.index') }}" class="mb-4 md:hidden">
            <div class="relative w-full">
                <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search motorcycles..."
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-sm text-slate-800 outline-none transition focus:border-brand-500 focus:bg-white">
            </div>
        </form>

        {{-- Mobile links --}}
        <div class="flex flex-col gap-1 text-sm font-medium text-slate-600">
            <a href="{{ route('home') }}" class="rounded-lg px-3 py-2.5 transition hover:bg-slate-50 hover:text-brand-600 {{ request()->routeIs('home') ? 'bg-brand-50 text-brand-600 font-semibold' : '' }}">Home</a>
            <a href="{{ route('motorcycles.index') }}" class="rounded-lg px-3 py-2.5 transition hover:bg-slate-50 hover:text-brand-600 {{ request()->routeIs('motorcycles.*') ? 'bg-brand-50 text-brand-600 font-semibold' : '' }}">Shop</a>
            <a href="{{ route('brands.index') }}" class="rounded-lg px-3 py-2.5 transition hover:bg-slate-50 hover:text-brand-600 {{ request()->routeIs('brands.*') ? 'bg-brand-50 text-brand-600 font-semibold' : '' }}">Brands</a>
            <a href="{{ route('services') }}" class="rounded-lg px-3 py-2.5 transition hover:bg-slate-50 hover:text-brand-600 {{ request()->routeIs('services') ? 'bg-brand-50 text-brand-600 font-semibold' : '' }}">Services</a>
            <a href="{{ route('contact') }}" class="rounded-lg px-3 py-2.5 transition hover:bg-slate-50 hover:text-brand-600 {{ request()->routeIs('contact') ? 'bg-brand-50 text-brand-600 font-semibold' : '' }}">Contact</a>
        </div>

        {{-- Mobile actions --}}
        <div class="mt-4 flex flex-col gap-2 border-t border-slate-100 pt-4">
            <a href="{{ route('cart.index') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                <i class="fa-solid fa-cart-shopping w-5 text-center"></i> Cart
            </a>
            @auth
                <a href="{{ route('account.dashboard') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                    <i class="fa-solid fa-user w-5 text-center"></i> My Account
                </a>
            @else
                <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 rounded-xl bg-brand-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-brand-500">
                    <i class="fa-solid fa-right-to-bracket"></i> Sign In
                </a>
                <a href="{{ route('register') }}" class="flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-3 text-sm font-bold text-slate-700 transition hover:border-brand-300">
                    Create Account
                </a>
            @endauth
        </div>
    </nav>
</header>

<script>
    (function () {
        var btn = document.getElementById('menuButton');
        var menu = document.getElementById('mobileMenu');
        if (!btn || !menu) return;
        btn.addEventListener('click', function () {
            var isOpen = !menu.classList.toggle('hidden');
            btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            btn.innerHTML = isOpen
                ? '<i class="fa-solid fa-xmark"></i>'
                : '<i class="fa-solid fa-bars"></i>';
        });
        // Close the menu when the viewport grows to desktop size.
        window.addEventListener('resize', function () {
            if (window.innerWidth >= 1024) {
                menu.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
                btn.innerHTML = '<i class="fa-solid fa-bars"></i>';
            }
        });
    })();
</script>
