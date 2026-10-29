@php
    $filterLabels = [
        'Urban Mobility' => 'Urban',
        'Adventure & Touring' => 'Adventure',
        'Performance' => 'Performance',
        'Premium' => 'Premium',
    ];
    $showcase = $motorcycles->where('is_featured', true)->take(3);
@endphp
<section id="featured" class="relative z-10 py-20 lg:py-28"
         x-data="{ active: 'all' }">

    <div class="mx-auto max-w-[1600px] px-5 lg:px-10">

        <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
            <x-section-heading eyebrow="The Lineup" title="Featured Motorcycles" description="Filter by segment, then explore the hero models in a horizontal showcase." />

            {{-- Segment filter (shares `active` with the grid) --}}
            <div class="flex flex-wrap items-center gap-2" data-reveal>
                <button type="button" @click="active='all'"
                        :class="active==='all' ? 'border-accent text-accent' : 'border-white/10 text-apex-muted hover:text-apex-text'"
                        class="rounded-full border px-4 py-2 text-[11px] font-display uppercase tracking-[0.2em] transition-colors">All</button>
                @foreach($filterLabels as $value => $label)
                    <button type="button" @click="active='{{ $value }}'"
                            :class="active==='{{ $value }}' ? 'border-accent text-accent' : 'border-white/10 text-apex-muted hover:text-apex-text'"
                            class="rounded-full border px-4 py-2 text-[11px] font-display uppercase tracking-[0.2em] transition-colors">{{ $label }}</button>
                @endforeach
            </div>
        </div>

        {{-- Cards grid (shares `active`) --}}
        <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4" data-reveal>
            @foreach($motorcycles as $m)
                <div x-show="active === 'all' || '{{ $m->category }}' === active" x-cloak
                     x-transition:enter="transition ease-[cubic-bezier(0.16,1,0.3,1)] duration-500"
                     x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-[cubic-bezier(0.16,1,0.3,1)] duration-300"
                     x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                    <x-product-card :motorcycle="$m" />
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Horizontal pinned showcase (desktop) / stacked (mobile) --}}
<section data-pin-section class="relative z-10 py-10 lg:py-16">
    <div class="mx-auto max-w-[1600px] px-5 lg:px-10 mb-8">
        <x-section-heading eyebrow="Hero Models" title="Three to remember." />
    </div>
    <div data-pin-pin class="overflow-hidden">
        <div data-pin-row class="flex flex-col gap-6 px-5 lg:flex-row lg:px-10 will-change-transform">
            @foreach($showcase as $m)
                @php $panelAccent = $m->brand->accent_color ?? config('group.default_accent'); @endphp
                <article class="panel relative w-full overflow-hidden rounded-3xl glass p-6 lg:w-[46vw] xl:w-[40vw] shrink-0 min-h-[420px] flex flex-col justify-between"
                         style="--panel-accent: {{ $panelAccent }}">
                    <div class="flex items-center justify-between">
                        <span class="font-display text-[10px] uppercase tracking-[0.3em] text-apex-muted">{{ $m->brand->segment }}</span>
                        <span class="font-display text-sm text-accent">{{ $m->brand->name }}</span>
                    </div>
                    <div class="relative flex-1 flex items-center justify-center my-6">
                        <div class="absolute inset-0 rounded-3xl" style="background: radial-gradient(50% 50% at 50% 50%, color-mix(in oklab, var(--panel-accent) 12%, transparent), transparent 70%)"></div>
                        <img src="{{ $m->image_url }}" alt="{{ $m->brand->name }} {{ $m->model_name }}" loading="lazy"
                             width="1200" height="600" class="relative h-auto w-full max-w-md object-contain drop-shadow-[0_25px_50px_rgba(0,0,0,0.5)]">
                    </div>
                    <div>
                        <h3 class="font-display text-3xl font-bold uppercase tracking-tight">{{ $m->model_name }}</h3>
                        <p class="mt-2 text-sm text-apex-muted max-w-md">{{ $m->short_description }}</p>
                        <div class="mt-6 grid grid-cols-3 gap-4 border-t border-white/5 pt-5">
                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] uppercase tracking-wider text-apex-faint">Power</span>
                                <span class="font-display text-2xl text-accent tabular-nums" data-pin-counter="{{ $m->horsepower }}" data-suffix="hp">0</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] uppercase tracking-wider text-apex-faint">Torque</span>
                                <span class="font-display text-2xl text-apex-text tabular-nums" data-pin-counter="{{ $m->torque }}" data-suffix="Nm">0</span>
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] uppercase tracking-wider text-apex-faint">Engine</span>
                                <span class="font-display text-2xl text-apex-text tabular-nums" data-pin-counter="{{ (int) $m->engine_capacity }}" data-suffix="cc">0</span>
                            </div>
                        </div>
                        <a href="{{ route('motorcycles.show', $m->slug) }}" class="mt-6 btn btn-ghost !py-2.5 !px-5 !text-[10px] inline-flex w-fit">View Details</a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
