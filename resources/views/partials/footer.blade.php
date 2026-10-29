<footer class="bg-[#0c0c0d] py-12 text-white relative overflow-hidden">
    {{-- Decorative Background Elements --}}
    <div class="absolute -left-20 -top-20 h-48 w-48 rounded-full bg-[#ff9aa9]/5 blur-3xl"></div>
    <div class="absolute -right-20 -bottom-20 h-48 w-48 rounded-full bg-[#ff9aa9]/5 blur-3xl"></div>

    <div class="mx-auto max-w-7xl px-5 lg:px-8 relative z-10">
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
            {{-- Brand Column --}}
            <div class="reveal-on-scroll">
                <a href="{{ route('home') }}" class="text-2xl font-black tracking-tighter uppercase">
                    RIDE<span class="text-[#ff9aa9]">AXIS</span>
                </a>
                <p class="mt-4 text-sm leading-relaxed text-gray-400">
                    Discover performance, precision, and character across our collection of premium motorcycles. Built for the next ride.
                </p>
                <div class="mt-4 flex gap-3">
                    @foreach([
                        ['fab fa-facebook-f', '#'],
                        ['fab fa-twitter', '#'],
                        ['fab fa-instagram', '#'],
                        ['fab fa-youtube', '#']
                    ] as [$icon, $link])
                    <a href="{{ $link }}" class="flex h-9 w-9 items-center justify-center rounded-full border border-gray-800 transition-all duration-300 hover:bg-[#ff9aa9] hover:border-[#ff9aa9] group">
                        <i class="{{ $icon }} text-sm group-hover:scale-110 transition-transform"></i>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Quick Links Column --}}
            <div class="reveal-on-scroll">
                <h4 class="text-sm font-bold uppercase tracking-widest text-white">Quick Links</h4>
                <ul class="mt-4 space-y-3 text-gray-400 text-sm">
                    <li><a href="{{ route('home') }}" class="transition hover:text-[#ff9aa9] hover:translate-x-1 inline-block">About Us</a></li>
                    <li><a href="{{ route('contact') }}" class="transition hover:text-[#ff9aa9] hover:translate-x-1 inline-block">Contact Us</a></li>
                    <li><a href="{{ route('motorcycles.index') }}" class="transition hover:text-[#ff9aa9] hover:translate-x-1 inline-block">Shop</a></li>
                    <li><a href="#" class="transition hover:text-[#ff9aa9] hover:translate-x-1 inline-block">Latest Blogs</a></li>
                </ul>
            </div>

            {{-- Categories / Brands Column --}}
            <div class="reveal-on-scroll">
                <h4 class="text-sm font-bold uppercase tracking-widest text-white">Top Brands</h4>
                <ul class="mt-4 space-y-3 text-gray-400 text-sm">
                    @foreach($footerBrands as $brand)
                    <li><a href="{{ route('brands.show', $brand->slug) }}" class="transition hover:text-[#ff9aa9] hover:translate-x-1 inline-block">{{ $brand->name }}</a></li>
                    @endforeach
                    @if($footerBrands->isEmpty())
                        <li><a href="#" class="transition hover:text-[#ff9aa9] hover:translate-x-1 inline-block">Motor Bikes</a></li>
                        <li><a href="#" class="transition hover:text-[#ff9aa9] hover:translate-x-1 inline-block">Electric Bikes</a></li>
                        <li><a href="#" class="transition hover:text-[#ff9aa9] hover:translate-x-1 inline-block">Accessories</a></li>
                    @endif
                </ul>
            </div>

            {{-- Newsletter Column --}}
            <div class="reveal-on-scroll">
                <h4 class="text-sm font-bold uppercase tracking-widest text-white">Newsletter</h4>
                <p class="mt-4 text-sm text-gray-400">Subscribe to get the latest updates, offers and technical insights.</p>
                <form class="mt-4 flex flex-col gap-3">
                    <input type="email" placeholder="Email Address" 
                           class="w-full rounded-full bg-white/5 border border-white/10 px-5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#ff9aa9] transition-all">
                    <button type="submit" class="w-full rounded-full bg-[#ff9aa9] px-5 py-2.5 text-xs font-black uppercase tracking-widest text-white transition-all hover:bg-white hover:text-black active:scale-95">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>

        {{-- Bottom Copyright Bar --}}
        <div class="mt-10 border-t border-gray-900 pt-6 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-500">
            <p>&copy; {{ date('Y') }} RIDEAXIS. All Rights Reserved.</p>
            <div class="flex gap-6">
                <a href="#" class="hover:text-white transition">Privacy Policy</a>
                <a href="#" class="hover:text-white transition">Terms of Service</a>
                <a href="#" class="hover:text-white transition">Cookies Settings</a>
            </div>
        </div>
    </div>
</footer>
