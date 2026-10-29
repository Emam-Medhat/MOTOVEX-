@props(['brand' => null])
@php $b = $brand; @endphp
<button type="button"
        class="brand-tab group relative overflow-hidden rounded-2xl glass glass-hover p-6 lg:p-8 text-start transition-all duration-500 hover:flex-[1.8] flex-1 min-h-[200px] lg:min-h-[340px] flex flex-col justify-between"
        data-brand-slug="{{ $b->slug }}"
        data-brand-accent="{{ $b->accent_color ?? '#1ee05a' }}"
        style="--brand-accent: {{ $b->accent_color ?? '#1ee05a' }}"
        x-on:click="$dispatch('switch-brand', { accent: '{{ $b->accent_color ?? '#1ee05a' }}' })"
        aria-label="{{ $b->name }} — {{ $b->segment }}">
    {{-- accent wash on hover/open --}}
    <span class="pointer-events-none absolute inset-0 opacity-0 transition-opacity duration-500 group-hover:opacity-100"
          style="background: radial-gradient(120% 80% at 50% 100%, color-mix(in oklab, var(--brand-accent) 16%, transparent), transparent 60%);"></span>

    <div class="relative z-10 flex items-center justify-between">
        <span class="font-display text-[10px] uppercase tracking-[0.3em] text-apex-muted">{{ $b->segment }}</span>
        <span class="h-2 w-2 rounded-full transition-colors duration-500" style="background: var(--brand-accent); box-shadow: 0 0 12px var(--brand-accent);"></span>
    </div>

    <div class="relative z-10">
        <h3 class="font-display text-3xl lg:text-4xl font-bold tracking-tight group-hover:text-white transition-colors">{{ $b->name }}</h3>
        <p class="mt-2 text-sm text-apex-muted max-w-xs">{{ $b->tagline }}</p>
        <a href="{{ route('brands.show', $b->slug) }}"
           class="mt-5 inline-flex items-center gap-2 text-xs uppercase tracking-[0.2em] font-semibold transition-colors group-hover:text-[var(--brand-accent)]"
           style="color: var(--brand-accent)">
            View range
            <i data-lucide="arrow-right" class="h-4 w-4"></i>
        </a>
    </div>
</button>
