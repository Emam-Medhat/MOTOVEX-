{{-- =====================================================================
     UNIFIED SITE NAVBAR — shared across every public page.
     Bilingual (EN / AR) and direction-aware (LTR / RTL).
     ===================================================================== --}}
@php
    $isRtl = ($dir ?? (app()->getLocale() === 'ar' ? 'rtl' : 'ltr')) === 'rtl';
@endphp

<header class="sticky top-0 z-50 w-full border-b border-slate-100 bg-white/95 backdrop-blur-md">
    <div class="mx-auto flex h-20 w-full max-w-[1600px] items-center gap-4 px-4 sm:px-6 lg:px-8">
        
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-3 group">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-600 text-white shadow-lg shadow-brand-500/20 transition-all duration-300 group-hover:scale-105 group-hover:rotate-3">
                <i class="fa-solid fa-motorcycle text-xl"></i>
            </div>
            <div class="flex flex-col leading-tight">
                <span class="text-xl font-black tracking-tight text-slate-900">MOTOVEX</span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-brand-600 opacity-80">Premium Motorcycles</span>
            </div>
        </a>

        {{-- Search Bar --}}
        <div class="hidden max-w-md flex-1 md:block lg:mx-8">
            <form action="{{ route('motorcycles.index') }}" method="GET" class="relative group">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-brand-500 transition-colors">
                    <i class="fa-solid fa-search text-sm"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search motorcycles, brands, categories..."
                       class="w-full rounded-2xl border border-slate-200 bg-slate-50 py-2.5 pl-11 pr-4 text-sm text-slate-800 transition-all focus:border-brand-500 focus:bg-white focus:ring-4 focus:ring-brand-500/5 outline-none">
            </form>
        </div>

        {{-- Nav Links --}}
        <nav class="hidden items-center gap-6 lg:flex">
            @php
                $links = [
                    ['route' => 'home', 'label' => 'Home'],
                    ['route' => 'motorcycles.index', 'label' => 'Shop'],
                    ['route' => 'spare-parts.index', 'label' => 'Spare Parts'],
                    ['route' => 'brands.index', 'label' => 'Brands'],
                    ['route' => 'promotions', 'label' => 'Promotions'],
                    ['route' => 'services', 'label' => 'Services'],
                    ['route' => 'contact', 'label' => 'Contact'],
                ];
            @endphp
            @foreach($links as $link)
                <a href="{{ route($link['route']) }}" 
                   class="text-sm font-semibold transition-colors hover:text-brand-600 {{ request()->routeIs($link['route']) ? 'text-brand-600' : 'text-slate-600' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- Right Actions --}}
        <div class="ms-auto flex items-center gap-3">
            {{-- Language Switcher --}}
            <div class="hidden sm:block">
                <x-language-switcher variant="light" />
            </div>

            {{-- Cart --}}
            @php
                $cart = session('cart', ['motorcycles' => [], 'spare_parts' => []]);
                $cartCount = count($cart['motorcycles'] ?? []) + array_sum(array_column($cart['spare_parts'] ?? [], 'quantity'));
            @endphp
            <a href="{{ route('cart.index') }}" class="relative flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 text-slate-600 transition-all hover:border-brand-200 hover:bg-brand-50 hover:text-brand-600">
                <i class="fa-solid fa-cart-shopping text-lg"></i>
                @if($cartCount > 0)
                    <span class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-brand-600 text-[10px] font-bold text-white ring-4 ring-white">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>

            {{-- User / Account --}}
            @auth
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('account.dashboard') }}" 
                   class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 transition-all hover:border-brand-200 hover:bg-brand-50 hover:text-brand-600">
                    <i class="fa-solid fa-user-circle text-lg"></i>
                    <span>Account</span>
                </a>
            @else
                <a href="{{ route('login') }}" 
                   class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 transition-all hover:border-brand-200 hover:bg-brand-50 hover:text-brand-600">
                    <i class="fa-solid fa-user-circle text-lg"></i>
                    <span>Account</span>
                </a>
            @endauth

            {{-- Mobile Toggle --}}
            <button class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-50 text-slate-600 lg:hidden" id="menuButton">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobileMenu" class="hidden lg:hidden border-t border-slate-100 bg-white p-4">
        <nav class="flex flex-col gap-2">
            @foreach($links as $link)
                <a href="{{ route($link['route']) }}" 
                   class="rounded-xl px-4 py-3 text-sm font-semibold transition-colors hover:bg-brand-50 hover:text-brand-600 {{ request()->routeIs($link['route']) ? 'bg-brand-50 text-brand-600' : 'text-slate-600' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
            <div class="mt-4 pt-4 border-t border-slate-100 flex flex-col gap-2">
                <div class="flex items-center justify-between px-4 py-2">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Language</span>
                    <x-language-switcher variant="light" />
                </div>
            </div>
        </nav>
    </div>
</header>

<script>
    (function () {
        var btn = document.getElementById('menuButton');
        var menu = document.getElementById('mobileMenu');
        if (!btn || !menu) return;
        btn.addEventListener('click', function () {
            var isOpen = !menu.classList.toggle('hidden');
            btn.innerHTML = isOpen ? '<i class="fa-solid fa-xmark"></i>' : '<i class="fa-solid fa-bars"></i>';
        });
    })();
</script>

