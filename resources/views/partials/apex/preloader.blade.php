@php $group = config('group.name'); @endphp
<div data-preloader class="preloader fixed inset-0 z-[100] flex flex-col items-center justify-center bg-apex-bg" aria-hidden="true">
    <div class="flex flex-col items-center gap-8">
        <div class="font-display text-2xl sm:text-3xl font-bold tracking-[0.3em] text-apex-text" data-preloader-logo>
            {{ strtoupper(explode(' ', $group)[0]) }}
            <span class="text-accent">.</span>
        </div>
        <div class="relative h-px w-56 overflow-hidden rounded-full bg-white/10">
            <div data-preloader-bar class="h-full w-full origin-left rounded-full bg-accent" style="transform: scaleX(0)"></div>
        </div>
        <div class="font-display text-xs tabular-nums tracking-[0.4em] text-apex-muted">
            <span data-preloader-count>0</span><span class="text-apex-faint">/100</span>
        </div>
    </div>
</div>
