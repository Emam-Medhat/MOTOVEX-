@props(['motorcycle' => null])
@php
    $m = $motorcycle;
    $symbols = ['USD' => '$', 'EUR' => '€', 'GBP' => '£', 'AED' => 'AED ', 'SAR' => 'SAR ', 'QAR' => 'QAR '];
    $sym = $symbols[$m->currency ?? 'USD'] ?? '$';
    $price = $m->price ? $sym . number_format((float) $m->price, 0) : null;
    $brandColor = $m->brand->accent_color ?? null;
@endphp
<a href="{{ route('motorcycles.show', $m->slug) }}"
   class="product-card group relative block rounded-2xl glass glass-hover p-5 overflow-hidden focus-visible:outline-2"
   aria-label="{{ $m->brand->name }} {{ $m->model_name }} — view details"
   @if($brandColor) style="--card-accent: {{ $brandColor }}" @endif>
    {{-- cursor-follow glow --}}
    <span class="pointer-events-none absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500"
          style="background: radial-gradient(240px circle at var(--mx,50%) var(--my,50%), color-mix(in oklab, var(--card-accent, var(--color-accent)) 18%, transparent), transparent 60%)"></span>

    <div data-card-tilt class="relative transition-transform duration-300 ease-out">
        {{-- segment badge --}}
        <div class="flex items-center justify-between">
            <span class="text-[10px] uppercase tracking-[0.28em] text-apex-muted">{{ $m->brand->segment ?? $m->category }}</span>
            <span class="text-[10px] uppercase tracking-[0.2em] text-accent">{{ $m->brand->name }}</span>
        </div>

        {{-- image --}}
        <div class="relative mt-4 mb-5 aspect-[16/10] flex items-center justify-center overflow-hidden rounded-xl"
             style="background: radial-gradient(circle at 50% 60%, color-mix(in oklab, var(--card-accent, var(--color-accent)) 9%, transparent), transparent 70%);">
            <img src="{{ $m->image_url }}"
                 alt="{{ $m->brand->name }} {{ $m->model_name }}"
                 loading="lazy"
                 width="640" height="400"
                 class="h-full w-full object-contain transition-transform duration-700 group-hover:scale-[1.07] drop-shadow-[0_20px_40px_rgba(0,0,0,0.5)]">
        </div>

        {{-- name + price --}}
        <h3 class="font-display text-lg font-bold uppercase tracking-tight leading-tight">{{ $m->model_name }}</h3>
        @if($price)
            <p class="mt-1 text-xs text-apex-muted">Starting from <span class="text-apex-text font-semibold">{{ $price }}</span></p>
        @endif

        {{-- 3 key specs --}}
        <div class="mt-4 grid grid-cols-3 gap-2 border-t border-white/5 pt-4">
            <div class="flex flex-col">
                <span class="text-[10px] uppercase tracking-wider text-apex-faint">Power</span>
                <span class="font-display text-sm text-apex-text">{{ $m->horsepower }}<span class="text-[10px] text-apex-muted ml-0.5">hp</span></span>
            </div>
            <div class="flex flex-col">
                <span class="text-[10px] uppercase tracking-wider text-apex-faint">Torque</span>
                <span class="font-display text-sm text-apex-text">{{ $m->torque }}<span class="text-[10px] text-apex-muted ml-0.5">Nm</span></span>
            </div>
            <div class="flex flex-col">
                <span class="text-[10px] uppercase tracking-wider text-apex-faint">Engine</span>
                <span class="font-display text-sm text-apex-text">{{ rtrim(rtrim(number_format($m->engine_capacity, 2), '0'), '.') }}<span class="text-[10px] text-apex-muted ml-0.5">cc</span></span>
            </div>
        </div>

        {{-- CTA --}}
        <div class="mt-5 flex items-center justify-between">
            <span class="btn btn-ghost !py-2.5 !px-4 !text-[10px] group-hover:text-accent group-hover:border-accent transition-colors">View Details</span>
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/10 text-accent transition-transform group-hover:translate-x-1" aria-hidden="true">
                <i data-lucide="arrow-up-right" class="h-4 w-4"></i>
            </span>
        </div>
    </div>
</a>
