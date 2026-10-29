<nav class="sticky top-0 z-50 border-b border-gray-100 bg-white/80 backdrop-blur-lg">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-5 lg:px-8">
        <a href="{{ route('home') }}" class="group flex items-center gap-2" aria-label="MOTOVEX home">
            <span class="text-2xl font-black tracking-tighter text-[#1e1f20]">RIDE<span class="text-[#ff9aa9]">AXIS</span></span>
        </a>

        <div class="hidden items-center gap-10 text-sm font-bold md:flex">
            <a href="{{ route('home') }}" class="transition hover:text-[#ff9aa9] {{ request()->routeIs('home') ? 'text-[#ff9aa9]' : 'text-[#1e1f20]' }}">Home</a>
            <a href="{{ route('brands.index') }}" class="transition hover:text-[#ff9aa9] {{ request()->routeIs('brands*') ? 'text-[#ff9aa9]' : 'text-[#1e1f20]' }}">About Us</a>
            <a href="{{ route('motorcycles.index') }}" class="transition hover:text-[#ff9aa9] {{ request()->routeIs('motorcycles*') ? 'text-[#ff9aa9]' : 'text-[#1e1f20]' }}">Shop</a>
            <a href="{{ route('services') }}" class="transition hover:text-[#ff9aa9] {{ request()->routeIs('services') ? 'text-[#ff9aa9]' : 'text-[#1e1f20]' }}">Blogs</a>
            <a href="{{ route('contact') }}" class="transition hover:text-[#ff9aa9] {{ request()->routeIs('contact') ? 'text-[#ff9aa9]' : 'text-[#1e1f20]' }}">Pages</a>
        </div>

        <div class="flex items-center gap-5">
            <button class="text-[#1e1f20] hover:text-[#ff9aa9] transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </button>
            <a href="{{ route('login') }}" class="text-[#1e1f20] hover:text-[#ff9aa9] transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </a>
            <a href="{{ route('motorcycles.index') }}" class="relative text-[#1e1f20] hover:text-[#ff9aa9] transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 11-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span class="absolute -right-2 -top-2 flex h-4 w-4 items-center justify-center rounded-full bg-[#ff9aa9] text-[10px] font-bold text-white">0</span>
            </a>
            <button id="nav-toggle" class="md:hidden">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
            </button>
        </div>
    </div>
</nav>
