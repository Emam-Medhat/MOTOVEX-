@props([
    'eyebrow' => null,
    'title' => null,
    'description' => null,
    'align' => 'start',          // start | center
    'id' => null,
])
@php
    $alignClass = $align === 'center' ? 'items-center text-center mx-auto' : 'items-start text-start';
@endphp
<div class="flex flex-col gap-4 {{ $alignClass }} max-w-2xl" data-reveal>
    @if($eyebrow)
        <span class="eyebrow flex items-center gap-3">
            <span class="inline-block h-px w-8 bg-accent"></span>
            {{ $eyebrow }}
        </span>
    @endif
    @if($title)
        <h2 class="font-display text-section font-bold uppercase tracking-tight">{{ $title }}</h2>
    @endif
    @if($description)
        <p class="text-base text-apex-muted leading-relaxed">{{ $description }}</p>
    @endif
</div>
