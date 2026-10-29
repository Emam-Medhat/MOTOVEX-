@php $group = config('group.name'); @endphp
<footer class="relative z-10 border-t border-white/5 bg-apex-surface/40" aria-label="Site footer">
    <div class="mx-auto max-w-[1600px] px-5 py-16 lg:px-10 lg:py-24">
        <div class="grid gap-12 lg:grid-cols-[1.4fr_1fr_1fr_1fr]">
            {{-- Brand block --}}
            <div class="flex flex-col gap-5">
                <a href="{{ route('home') }}" class="font-display text-2xl font-bold tracking-[0.3em] uppercase">
                    {{ explode(' ', $group)[0] }}<span class="text-accent">.</span>
                </a>
                <p class="max-w-sm text-sm text-apex-muted leading-relaxed">
                    A multi-brand motorcycle group engineered for the next ride — across urban, adventure, performance and premium machines in the Middle East.
                </p>
                <div class="flex items-center gap-3">
                    {{-- lucide v1 dropped brand marks, so each network maps to the
                         closest generic glyph available in the icon set. --}}
                    @foreach([['Instagram', 'camera'], ['X (Twitter)', 'at-sign'], ['YouTube', 'play'], ['LinkedIn', 'briefcase']] as [$social, $icon])
                        <a href="#" aria-label="{{ $social }}" class="flex h-10 w-10 items-center justify-center rounded-full border border-white/10 text-apex-muted transition-colors hover:border-accent hover:text-accent">
                            <i data-lucide="{{ $icon }}" class="h-4 w-4"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Brand links --}}
            <div class="flex flex-col gap-4">
                <h3 class="font-display text-[11px] uppercase tracking-[0.3em] text-apex-faint">Brands</h3>
                @foreach($brands ?? [] as $brand)
                    <a href="{{ route('brands.show', $brand->slug) }}" class="text-sm text-apex-muted hover:text-apex-text transition-colors">{{ $brand->name }}</a>
                @endforeach
            </div>

            {{-- Explore --}}
            <div class="flex flex-col gap-4">
                <h3 class="font-display text-[11px] uppercase tracking-[0.3em] text-apex-faint">Explore</h3>
                <a href="{{ route('motorcycles.index') }}" class="text-sm text-apex-muted hover:text-apex-text transition-colors">Motorcycles</a>
                <a href="{{ route('services') }}" class="text-sm text-apex-muted hover:text-apex-text transition-colors">Services</a>
                <a href="#showrooms" class="text-sm text-apex-muted hover:text-apex-text transition-colors">Showrooms</a>
                <a href="#final-cta" class="text-sm text-apex-muted hover:text-apex-text transition-colors">Book a Test Ride</a>
            </div>

            {{-- Newsletter --}}
            <div class="flex flex-col gap-4">
                <h3 class="font-display text-[11px] uppercase tracking-[0.3em] text-apex-faint">Newsletter</h3>
                <p class="text-sm text-apex-muted">Ride notes, drops and events — a few times a year.</p>
                <form class="flex items-center gap-2" x-data="{ email: '' }">
                    <label for="newsletter-email" class="sr-only">Email address</label>
                    <input id="newsletter-email" type="email" x-model="email" placeholder="Email address"
                           class="w-full rounded-full border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-apex-text placeholder-apex-faint focus:border-accent focus:outline-none">
                    <button type="submit" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-accent text-black transition-transform hover:scale-105" aria-label="Subscribe">
                        <i data-lucide="arrow-right" class="h-4 w-4"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-14 flex flex-col gap-4 border-t border-white/5 pt-8 text-xs text-apex-faint sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ date('Y') }} {{ $group }}. All rights reserved.</p>
            <p class="max-w-xl sm:text-end">Created solely for recruitment assessment and demonstration purposes; not for commercial use.</p>
        </div>
    </div>
</footer>
