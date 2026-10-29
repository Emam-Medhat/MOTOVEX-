@php
    $promo = $promotions->where('is_active', true)->firstWhere(fn($p) => $p->end_date && $p->end_date->isFuture()) ?? $promotions->first();
@endphp
@if($promo)
<section class="relative z-10 py-12 lg:py-20">
    <div class="mx-auto max-w-[1600px] px-5 lg:px-10">
        <div class="relative overflow-hidden rounded-3xl border border-white/5" data-reveal>
            {{-- Parallax background --}}
            <div data-parallax="0.15" class="pointer-events-none absolute inset-0 -z-10">
                <div class="absolute inset-0 opacity-60" style="background: radial-gradient(60% 60% at 80% 30%, color-mix(in oklab, var(--color-accent) 18%, transparent), transparent 70%)"></div>
                @if($promo->image_url)
                    <img src="{{ $promo->image_url }}" alt="" aria-hidden="true" class="absolute inset-0 h-full w-full object-cover opacity-10">
                @endif
            </div>
            <div class="absolute inset-0 -z-10 bg-apex-surface/40 backdrop-blur-sm"></div>

            <div class="relative p-8 sm:p-12 lg:p-16">
                <div class="flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-xl">
                        <span class="eyebrow flex items-center gap-3">
                            <span class="inline-block h-px w-8 bg-accent"></span> Limited Offer
                        </span>
                        <h2 class="mt-4 font-display text-display font-bold uppercase tracking-tight leading-[0.95]">{{ $promo->title }}</h2>
                        <p class="mt-4 text-base text-apex-muted leading-relaxed">{{ $promo->description }}</p>
                        <a href="#final-cta" class="mt-8 btn btn-primary inline-flex" data-magnetic>Claim This Offer</a>
                    </div>

                    {{-- Countdown --}}
                    <div data-countdown="{{ $promo->end_date?->toIso8601String() }}" class="shrink-0">
                        <p class="mb-4 font-display text-[11px] uppercase tracking-[0.3em] text-apex-muted text-center lg:text-end">Offer ends in</p>
                        <div class="grid grid-cols-4 gap-2 sm:gap-3">
                            @foreach([['d','Days'],['h','Hrs'],['m','Min'],['s','Sec']] as [$k,$lbl])
                                <div class="flex flex-col items-center rounded-xl glass px-3 py-3 sm:px-5 sm:py-4 min-w-[64px]">
                                    <span data-cd="{{ $k }}" class="font-display text-2xl sm:text-3xl font-bold tabular-nums text-accent">00</span>
                                    <span class="mt-1 text-[9px] uppercase tracking-[0.2em] text-apex-faint">{{ $lbl }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
