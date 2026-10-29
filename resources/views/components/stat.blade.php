@props([
    'value' => 0,
    'suffix' => '',
    'label' => null,
])
<div class="flex flex-col gap-2" data-reveal-item>
    <span class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold text-accent accent-text-glow tabular-nums"
          data-counter="{{ (int) $value }}" data-suffix="{{ $suffix }}">0</span>
    <span class="text-xs sm:text-sm uppercase tracking-[0.25em] text-apex-muted font-sans">{{ $label }}</span>
</div>
