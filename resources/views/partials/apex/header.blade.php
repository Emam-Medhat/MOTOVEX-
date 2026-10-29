@php
    $group = config('group.name');
    $first = explode(' ', $group)[0];
    $currentRoute = request()->route()?->getName() ?? '';
@endphp
<header data-header class="fixed inset-x-0 top-0 z-50 border-b border-white/5">
    <nav class="mx-auto flex max-w-[1600px] items-center justify-between gap-4 px-5 py-3 lg:px-10"
         x-data="{ open: false }"
         @keydown.escape.window="open = false">

        {{-- Left: Logo --}}
        <a href="{{ route('home') }}"
           class="flex items-center gap-2 font-display text-sm font-bold tracking-[0.2em] uppercase shrink-0"
           aria-label="{{ $group }} home">
            <span class="flex h-7 w-7 items-center justify-center rounded bg-accent text-black font-black text-xs">
                {{ strtoupper(substr($first, 0, 1)) }}
            </span>
            <span class="hidden sm:block">{{ $group }}</span>
        </a>

        {{-- Center: Main nav links (desktop) --}}
        <div class="hidden lg:flex items-center gap-1">
            @php
                $navLinks = [
                    ['Motorcycles', route('motorcycles.index'), 'motorcycles'],
                    ['Brands',      route('brands.index'),      'brands'],
                    ['Services',    route('services'),           'services'],
                    ['Contact',     route('contact'),            'contact'],
                ];
            @endphp
            @foreach($navLinks as [$label, $url, $key])
                <a href="{{ $url }}"
                   class="px-4 py-2 rounded-lg font-display text-[10px] uppercase tracking-[0.2em] transition-colors
                          {{ str_contains($currentRoute, $key) ? 'text-accent bg-accent/10' : 'text-apex-muted hover:text-white hover:bg-white/5' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- Right cluster --}}
        <div class="flex items-center gap-2">

            {{-- Cart --}}
            <a href="{{ route('cart.index') }}"
               class="flex h-9 w-9 items-center justify-center rounded-lg text-apex-muted hover:text-white hover:bg-white/5 transition-colors relative"
               title="Cart">
                <i data-lucide="shopping-cart" class="h-4 w-4"></i>
            </a>

            @auth
                {{-- Account dropdown --}}
                <div class="relative" x-data="{ accountOpen: false }" @click.outside="accountOpen = false">
                    <button @click="accountOpen = !accountOpen"
                            class="flex items-center gap-2 h-9 px-3 rounded-lg text-apex-muted hover:text-white hover:bg-white/5 transition-colors">
                        <i data-lucide="user" class="h-4 w-4"></i>
                        <span class="hidden sm:block font-display text-[10px] uppercase tracking-widest">{{ auth()->user()->name }}</span>
                        <i data-lucide="chevron-down" class="h-3 w-3 transition-transform" :class="accountOpen && 'rotate-180'"></i>
                    </button>

                    <div x-show="accountOpen" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 top-full mt-2 w-52 glass rounded-xl border border-white/8 overflow-hidden shadow-2xl">
                        <div class="px-4 py-3 border-b border-white/5">
                            <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-apex-muted truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="py-1">
                            <a href="{{ route('account.dashboard') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-apex-muted hover:text-white hover:bg-white/5 transition-colors">
                                <i data-lucide="layout-dashboard" class="h-4 w-4 shrink-0"></i> Dashboard
                            </a>
                            <a href="{{ route('account.profile') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-apex-muted hover:text-white hover:bg-white/5 transition-colors">
                                <i data-lucide="settings" class="h-4 w-4 shrink-0"></i> Profile
                            </a>
                            <a href="{{ route('account.enquiries') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-apex-muted hover:text-white hover:bg-white/5 transition-colors">
                                <i data-lucide="inbox" class="h-4 w-4 shrink-0"></i> My Enquiries
                            </a>

                            @php
                                $roles = auth()->user()->roles->pluck('name');
                            @endphp
                            @if($roles->contains('super_admin') || $roles->contains('admin') || $roles->contains('brand_manager'))
                            <div class="my-1 border-t border-white/5"></div>
                            <a href="{{ route('admin.dashboard') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-accent hover:bg-accent/10 transition-colors">
                                <i data-lucide="shield" class="h-4 w-4 shrink-0"></i> Admin Panel
                            </a>
                            @endif
                        </div>
                        <div class="border-t border-white/5 py-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-red-400 hover:bg-red-500/10 transition-colors">
                                    <i data-lucide="log-out" class="h-4 w-4 shrink-0"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}"
                   class="flex h-9 px-4 items-center gap-2 rounded-lg bg-accent text-black font-display text-[10px] uppercase tracking-widest font-bold hover:opacity-90 transition-opacity">
                    <i data-lucide="log-in" class="h-4 w-4"></i>
                    <span class="hidden sm:block">Login</span>
                </a>
            @endauth

            {{-- Mobile menu button --}}
            <button type="button"
                    class="lg:hidden flex h-9 w-9 items-center justify-center rounded-lg text-apex-muted hover:text-white hover:bg-white/5 transition-colors"
                    @click="open = true"
                    aria-label="Open menu">
                <i data-lucide="menu" class="h-5 w-5"></i>
            </button>
        </div>

        {{-- Mobile overlay --}}
        <div x-show="open" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[55] flex flex-col bg-apex-bg/98 backdrop-blur-xl lg:hidden"
             @click.self="open = false"
             role="dialog" aria-modal="true" aria-label="Main menu">

            <div class="flex items-center justify-between px-5 py-4 border-b border-white/5">
                <span class="font-display text-sm font-bold tracking-widest uppercase">{{ $group }}</span>
                <button type="button" @click="open = false"
                        class="flex h-9 w-9 items-center justify-center rounded-lg text-apex-muted hover:text-white hover:bg-white/5 transition-colors"
                        aria-label="Close menu">
                    <i data-lucide="x" class="h-5 w-5"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-5 py-6">
                <p class="font-display text-[9px] uppercase tracking-[0.3em] text-apex-faint mb-4">Navigation</p>
                <ul class="flex flex-col gap-1 mb-8">
                    @foreach([
                        ['Home',         route('home'),               'home'],
                        ['Motorcycles',  route('motorcycles.index'),  'bike'],
                        ['Brands',       route('brands.index'),       'tag'],
                        ['Services',     route('services'),           'wrench'],
                        ['Contact',      route('contact'),            'mail'],
                        ['Cart',         route('cart.index'),         'shopping-cart'],
                    ] as [$label, $url, $icon])
                    <li>
                        <a href="{{ $url }}" @click="open = false"
                           class="flex items-center gap-3 px-4 py-3 rounded-xl text-apex-muted hover:text-white hover:bg-white/5 transition-colors">
                            <i data-lucide="{{ $icon }}" class="h-4 w-4 text-accent shrink-0"></i>
                            <span class="font-display text-sm uppercase tracking-widest">{{ $label }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>

                @auth
                <p class="font-display text-[9px] uppercase tracking-[0.3em] text-apex-faint mb-4">Account</p>
                <ul class="flex flex-col gap-1 mb-8">
                    @foreach([
                        ['Dashboard',    route('account.dashboard'),  'layout-dashboard'],
                        ['Profile',      route('account.profile'),    'settings'],
                        ['Enquiries',    route('account.enquiries'),  'inbox'],
                    ] as [$label, $url, $icon])
                    <li>
                        <a href="{{ $url }}" @click="open = false"
                           class="flex items-center gap-3 px-4 py-3 rounded-xl text-apex-muted hover:text-white hover:bg-white/5 transition-colors">
                            <i data-lucide="{{ $icon }}" class="h-4 w-4 text-accent shrink-0"></i>
                            <span class="font-display text-sm uppercase tracking-widest">{{ $label }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>

                @php $roles = auth()->user()->roles->pluck('name'); @endphp
                @if($roles->contains('super_admin') || $roles->contains('admin') || $roles->contains('brand_manager'))
                <p class="font-display text-[9px] uppercase tracking-[0.3em] text-apex-faint mb-4">Admin</p>
                <ul class="flex flex-col gap-1 mb-8">
                    @foreach([
                        ['Dashboard',    route('admin.dashboard'),            'layout-dashboard'],
                        ['Motorcycles',  route('admin.motorcycles.index'),    'bike'],
                        ['Brands',       route('admin.brands.index'),         'tag'],
                        ['Users',        route('admin.users.index'),          'users'],
                        ['Enquiries',    route('admin.enquiries.index'),      'inbox'],
                        ['Promotions',   route('admin.promotions.index'),     'megaphone'],
                        ['Showrooms',    route('admin.showrooms.index'),      'map-pin'],
                    ] as [$label, $url, $icon])
                    <li>
                        <a href="{{ $url }}" @click="open = false"
                           class="flex items-center gap-3 px-4 py-3 rounded-xl text-apex-muted hover:text-accent hover:bg-accent/5 transition-colors">
                            <i data-lucide="{{ $icon }}" class="h-4 w-4 text-accent shrink-0"></i>
                            <span class="font-display text-sm uppercase tracking-widest">{{ $label }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
                @endif

                <div class="border-t border-white/5 pt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="flex w-full items-center gap-3 px-4 py-3 rounded-xl text-red-400 hover:bg-red-500/10 transition-colors">
                            <i data-lucide="log-out" class="h-4 w-4 shrink-0"></i>
                            <span class="font-display text-sm uppercase tracking-widest">Logout</span>
                        </button>
                    </form>
                </div>
                @else
                <div class="flex flex-col gap-3">
                    <a href="{{ route('login') }}" @click="open = false"
                       class="btn btn-primary w-full justify-center">
                        <i data-lucide="log-in" class="h-4 w-4"></i> Login
                    </a>
                    <a href="{{ route('register') }}" @click="open = false"
                       class="btn btn-ghost w-full justify-center">
                        Register
                    </a>
                </div>
                @endauth
            </div>
        </div>

    </nav>
</header>

<style>[x-cloak]{display:none!important}</style>
