@php
    $first = $slides->first();
    $accent = $first->brand->accent_color ?? config('group.default_accent');
    $tagline = config('group.tagline', 'FUELLED BY YOUR CONVICTION');
    // Split tagline into two balanced lines.
    $words = explode(' ', $tagline);
    $half = (int) ceil(count($words) / 2);
    $line1 = implode(' ', array_slice($words, 0, $half));
    $line2 = implode(' ', array_slice($words, $half));

    // Build the hero payload here and pass a single variable to @json —
    // the directive splits its argument on commas, so inline arrays break it.
    $heroData = collect($slides)->map(fn ($m) => [
        'model' => $m->model_name,
        'slug' => $m->slug,
        'image' => $m->image_url,
        'hp' => (int) $m->horsepower,
        'torque' => (int) $m->torque,
        'engine' => (float) $m->engine_capacity,
        'transmission' => $m->transmission,
        'short' => $m->short_description,
        'brand' => [
            'name' => $m->brand->name,
            'slug' => $m->brand->slug,
            'accent' => $m->brand->accent_color ?? config('group.default_accent'),
            'segment' => $m->brand->segment,
        ],
    ])->values();
@endphp

{{-- Lock scroll on homepage: the hero IS the page --}}
<style>
    html.home-page, html.home-page body {
        overflow: hidden;
        height: 100%;
    }
</style>
<script>document.documentElement.classList.add('home-page');</script>

<section id="hero" data-hero class="relative h-[100dvh] w-full overflow-hidden flex flex-col">

    {{-- Ambient glow (re-themes with accent) --}}
    <div data-hero-glow class="pointer-events-none absolute inset-0 -z-10 transition-transform duration-300"
         style="background: radial-gradient(46% 55% at 55% 45%, color-mix(in oklab, var(--color-accent) 18%, transparent), transparent 70%);"></div>
    <div class="pointer-events-none absolute inset-x-0 bottom-0 h-40 z-0" style="background: linear-gradient(to top, var(--color-apex-bg), transparent)"></div>

    {{-- Giant outlined model name behind the bike --}}
    <div class="pointer-events-none absolute inset-0 -z-10 flex items-center justify-center overflow-hidden">
        <span data-hero-outline class="text-outline text-hero text-center whitespace-nowrap select-none opacity-90">{{ $first->model_name }}</span>
    </div>

    {{-- Stage: bike + hotspots --}}
    <div class="relative z-10 flex-1 flex flex-col justify-center px-5 lg:px-10 py-24">

        {{-- Side social icons (far left) --}}
        <div class="absolute start-6 top-1/2 -translate-y-1/2 z-30 hidden lg:flex flex-col gap-5 text-apex-faint">
            <a href="#" aria-label="Facebook" class="h-4 w-4 hover:text-accent transition-colors">
                <svg viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
            </a>
            <a href="#" aria-label="Twitter/X" class="h-4 w-4 hover:text-accent transition-colors">
                <svg viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M4 4l16 16M20 4 4 20" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/></svg>
            </a>
            <a href="#" aria-label="Instagram" class="h-4 w-4 hover:text-accent transition-colors">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="h-4 w-4"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
            </a>
            <a href="#" aria-label="LinkedIn" class="h-4 w-4 hover:text-accent transition-colors">
                <svg viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6zM2 9h4v12H2zm2-3a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg>
            </a>
        </div>

        <div class="mx-auto w-full max-w-[1600px] grid lg:grid-cols-[1fr_1.3fr] gap-8 items-center">

            {{-- ─── LEFT: Info ─────────────────────────────────── --}}
            <div class="relative z-20 flex flex-col justify-center pt-8 lg:pt-0">
                <div data-hero-text-wrap>

                    {{-- Brand + Model large stack --}}
                    <div class="flex flex-col mb-6">
                        <span class="text-hero-solid text-3xl lg:text-6xl uppercase opacity-15 leading-none" data-hero-brand>{{ $first->brand->name }}</span>
                        <span class="text-hero-solid text-5xl lg:text-[5.5rem] uppercase -mt-2 leading-none" data-hero-model-main>{{ $first->model_name }}</span>
                    </div>

                    {{-- Tagline --}}
                    <div class="max-w-md mb-8">
                        <h1 class="font-display font-bold uppercase tracking-tight leading-[0.9] text-[clamp(1.4rem,3.5vw,3rem)]">
                            <span class="mask-line" data-mask><span class="line-inner">{{ $line1 }}</span></span>
                            <span class="mask-line text-accent accent-text-glow" data-mask><span class="line-inner">{{ $line2 }}</span></span>
                        </h1>
                        <p class="mt-5 text-sm text-apex-muted leading-relaxed max-w-sm" data-hero-description>
                            {{ $first->short_description }}
                        </p>
                    </div>

                    {{-- Specs strip --}}
                    <div data-hero-specs class="flex items-stretch gap-10 mb-8">
                        <div class="flex flex-col gap-1">
                            <span class="eyebrow text-[9px] opacity-60">Power</span>
                            <span class="font-display text-2xl sm:text-3xl">{{ $first->horsepower }}<span class="text-[10px] text-apex-muted ms-1">HP</span></span>
                        </div>
                        <div class="w-px bg-white/10 self-stretch"></div>
                        <div class="flex flex-col gap-1">
                            <span class="eyebrow text-[9px] opacity-60">Torque</span>
                            <span class="font-display text-2xl sm:text-3xl">{{ $first->torque }}<span class="text-[10px] text-apex-muted ms-1">NM</span></span>
                        </div>
                        @if($first->engine_capacity)
                        <div class="w-px bg-white/10 self-stretch"></div>
                        <div class="flex flex-col gap-1">
                            <span class="eyebrow text-[9px] opacity-60">Engine</span>
                            <span class="font-display text-2xl sm:text-3xl">{{ $first->engine_capacity }}<span class="text-[10px] text-apex-muted ms-1">CC</span></span>
                        </div>
                        @endif
                    </div>

                    {{-- CTA buttons --}}
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('motorcycles.show', $first->slug) }}" class="btn btn-primary">
                            <i data-lucide="eye" class="h-4 w-4"></i>
                            Explore
                        </a>
                        <a href="#final-cta" class="btn btn-ghost">
                            Book Test Ride
                        </a>
                    </div>
                </div>
            </div>

            {{-- ─── RIGHT: Bike Stage ───────────────────────────── --}}
            <div class="relative flex items-center justify-center">

                {{-- Touring accessories badge — top right of bike area --}}
                <div class="absolute top-0 right-0 z-20 text-right hidden lg:block" data-hero-badge>
                    <p class="font-display text-[9px] uppercase tracking-[0.3em] text-apex-muted leading-relaxed">
                        NOW COMES WITH TOURING<br>
                        READY <span class="text-accent">ACCESSORIES.</span>
                    </p>
                </div>

                <div data-hero-bike-wrap class="relative w-full">
                    {{-- Ground shadow / glow --}}
                    <div class="absolute inset-x-[10%] -bottom-6 h-16 rounded-[50%] blur-3xl opacity-50"
                         style="background: color-mix(in oklab, var(--color-accent) 35%, transparent)"></div>

                    <img data-hero-bike
                         src="{{ $first->image_url }}"
                         alt="{{ $first->brand->name }} {{ $first->model_name }}"
                         class="relative mx-auto h-auto w-full max-w-[700px] drop-shadow-[0_40px_100px_rgba(0,0,0,0.7)]">

                    {{-- Hotspot: Wind Visor — top right of bike --}}
                    <div class="absolute top-[28%] right-[8%] z-20 group hidden lg:block">
                        {{-- Connector line going left from hotspot --}}
                        <div class="absolute top-1/2 -translate-y-1/2 right-5 flex items-center">
                            <div class="w-24 h-px bg-white/30" style="transform: rotate(-15deg); transform-origin: right center;"></div>
                        </div>
                        <div class="hotspot"></div>
                        {{-- Label box to the right --}}
                        <div class="absolute -top-10 left-8 w-52 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                            <div class="h-px w-10 bg-accent/60 mb-2"></div>
                            <span class="block font-display text-[10px] uppercase tracking-[0.25em] text-white mb-1">Wind Visor</span>
                            <span class="block text-[9px] text-apex-muted leading-tight">
                                Allows fresh air to enter the vehicle while keeping rain and wind out.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── Vertical slide counter (far right) ──────────────── --}}
    <div class="absolute end-4 top-1/2 z-30 -translate-y-1/2 flex flex-col items-center gap-5 lg:end-8">
        <button type="button" data-hero-prev
                class="group p-2 text-apex-muted hover:text-accent transition-colors border border-white/10 hover:border-accent/40 rounded"
                aria-label="Previous">
            <i data-lucide="chevron-up" class="h-4 w-4"></i>
        </button>

        <div class="flex flex-col items-center gap-4 font-display text-base tracking-tight">
            @for($i = 1; $i <= $slides->count(); $i++)
                <span data-hero-dot="{{ $i-1 }}"
                      class="cursor-pointer transition-all {{ $i === 1 ? 'text-accent scale-110' : 'text-apex-faint hover:text-apex-muted' }}">
                    0{{ $i }}
                </span>
            @endfor
        </div>

        <button type="button" data-hero-next
                class="group p-2 text-apex-muted hover:text-accent transition-colors border border-white/10 hover:border-accent/40 rounded"
                aria-label="Next">
            <i data-lucide="chevron-down" class="h-4 w-4"></i>
        </button>
    </div>

    {{-- ─── Bottom Tab Bar ──────────────────────────────────── --}}
    <div class="relative z-20 w-full border-t border-white/5 bg-apex-bg/60 backdrop-blur-md">
        <div class="mx-auto flex w-full max-w-[1600px]" data-hero-tabs>

            {{-- First tab: bike name + up/down arrows --}}
            <button type="button" data-tab="0" class="hero-tab is-active group min-w-[160px] lg:min-w-[200px]">
                <div class="flex items-start justify-between w-full">
                    <div class="flex flex-col">
                        <span class="font-display text-[8px] uppercase tracking-[0.25em] opacity-60 mb-1 group-[.is-active]:opacity-80" data-hero-tab-brand>{{ $first->brand->name }}</span>
                        <span class="font-display text-sm font-bold uppercase leading-tight" data-hero-tab-model>{{ $first->model_name }}</span>
                    </div>
                    {{-- Internal nav arrows --}}
                    <div class="flex flex-col gap-1 ms-3">
                        <button type="button" data-hero-prev-tab class="opacity-70 hover:opacity-100 transition-opacity" aria-label="Previous slide">
                            <i data-lucide="chevron-up" class="h-3 w-3"></i>
                        </button>
                        <button type="button" data-hero-next-tab class="opacity-70 hover:opacity-100 transition-opacity" aria-label="Next slide">
                            <i data-lucide="chevron-down" class="h-3 w-3"></i>
                        </button>
                    </div>
                </div>
            </button>

            @foreach(['Performance', 'Design', 'Technology', 'Safety'] as $i => $label)
                <button type="button" data-tab="{{ $i + 1 }}" class="hero-tab group hidden sm:flex">
                    <span class="font-display text-[9px] uppercase tracking-widest opacity-60 mb-2">{{ $label }}</span>
                    <div class="flex flex-col gap-1" data-tab-preview="{{ $i + 1 }}">
                        {{-- Content filled by JS --}}
                    </div>
                </button>
            @endforeach

            {{-- Video / image thumbnail tab at end --}}
            <div class="hero-tab flex items-center justify-center border-r-0 min-w-[80px] lg:min-w-[120px]">
                <div class="relative h-14 w-24 overflow-hidden rounded-md group cursor-pointer">
                    <img src="{{ $first->image_url }}"
                         class="h-full w-full object-cover opacity-35 group-hover:scale-110 transition-transform duration-500"
                         alt="Preview">
                    <div class="absolute inset-0 flex items-center justify-center bg-black/20">
                        <i data-lucide="play" class="h-5 w-5 fill-white text-white drop-shadow-md"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>



    {{-- Hero data payload + default accent --}}
    <span data-default-accent="{{ $accent }}" hidden aria-hidden="true"></span>
    <script type="application/json" id="hero-data">@json($heroData)</script>
</section>
