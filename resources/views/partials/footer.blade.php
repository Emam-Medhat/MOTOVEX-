{{-- =====================================================================
     UNIFIED SITE FOOTER — shared across every public page.
     Refined layout: Links Grid first, Contact Cards at bottom.
     Compact height version.
     ===================================================================== --}}
<footer dir="ltr" class="w-full border-t border-slate-200 bg-white pt-10 pb-6 text-xs text-slate-600 sm:pt-12">
    <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-8 xl:px-10">
        
        {{-- Links Grid (Top) --}}
        <div class="grid grid-cols-2 gap-8 border-b border-slate-200 pb-8 md:grid-cols-4 xl:gap-12">
            <div class="col-span-2 md:col-span-1">
                <a href="{{ route('home') }}" class="mb-3 flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-600 text-sm text-white">
                        <i class="fa-solid fa-motorcycle"></i>
                    </span>
                    <strong class="text-base font-extrabold text-slate-900">MOTOVEX</strong>
                </a>
                <p class="max-w-sm leading-relaxed text-slate-500">Premium motorcycle dealership with certified inspections and expert support.</p>
            </div>
            <div>
                <h4 class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-900">Explore</h4>
                <ul class="space-y-2 text-slate-500">
                    <li><a href="{{ route('motorcycles.index') }}" class="transition hover:text-brand-600">All Motorcycles</a></li>
                    <li><a href="{{ route('brands.index') }}" class="transition hover:text-brand-600">Our Brands</a></li>
                    <li><a href="{{ route('services') }}" class="transition hover:text-brand-600">Services</a></li>
                    <li><a href="{{ route('contact') }}" class="transition hover:text-brand-600">Contact</a></li>
                </ul>
            </div>
            <div>
                <h4 class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-900">Account</h4>
                <ul class="space-y-2 text-slate-500">
                    <li><a href="{{ route('login') }}" class="transition hover:text-brand-600">Login</a></li>
                    <li><a href="{{ route('register') }}" class="transition hover:text-brand-600">Register</a></li>
                    <li><a href="{{ route('account.dashboard') }}" class="transition hover:text-brand-600">My Account</a></li>
                    <li><a href="{{ route('account.enquiries') }}" class="transition hover:text-brand-600">My Enquiries</a></li>
                </ul>
            </div>
            <div>
                <h4 class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-900">Top Brands</h4>
                <ul class="space-y-2 text-slate-500">
                    @foreach($footerBrands ?? [] as $fb)
                    <li><a href="{{ route('brands.show', $fb->slug) }}" class="transition hover:text-brand-600">{{ $fb->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Contact Cards (Bottom) --}}
        <div class="grid grid-cols-1 gap-4 py-8 sm:grid-cols-2 lg:grid-cols-4 lg:gap-5">
            @foreach([
                ['fa-location-dot','Our Showroom','123 Motorway Street, Cairo, Egypt'],
                ['fa-envelope','Mail Us','info@motovex-group.com'],
                ['fa-phone','Direct Line','+20 (012) 3456 7890'],
                ['fa-motorcycle','MOTOVEX Group','Official Authorized Distributor'],
            ] as [$icon,$title,$text])
            <div class="flex items-center gap-3.5 rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-brand-200">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-lg font-bold text-brand-600">
                    <i class="fa-solid {{ $icon }}"></i>
                </div>
                <div class="min-w-0">
                    <h4 class="text-xs font-bold text-slate-900">{{ $title }}</h4>
                    <p class="mt-0.5 truncate text-[10px] text-slate-500">{{ $text }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Copyright Bar --}}
        <div class="flex flex-col items-center justify-between gap-3 border-t border-slate-200 pt-6 text-center text-slate-400 sm:flex-row sm:text-left">
            <span>© {{ date('Y') }} MOTOVEX Premium Motorcycle Group. All rights reserved.</span>
            <div class="flex flex-wrap justify-center gap-5">
                <a href="#" class="transition hover:text-slate-700">Privacy</a>
                <a href="#" class="transition hover:text-slate-700">Terms</a>
                <a href="#" class="transition hover:text-slate-700">Cookies</a>
            </div>
        </div>
    </div>
</footer>
