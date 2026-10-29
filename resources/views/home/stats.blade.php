@php
    $stats = [
        ['value' => 12, 'suffix' => '+', 'label' => 'Showrooms'],
        ['value' => 9,  'suffix' => '',  'label' => 'Service Centers'],
        ['value' => 28, 'suffix' => '',  'label' => 'Years of Heritage'],
        ['value' => 50000, 'suffix' => '+', 'label' => 'Riders Served'],
    ];
    $marquee = $brands ?? collect();
@endphp
<section class="relative z-10 py-20 lg:py-24 border-y border-white/5">
    <div class="mx-auto max-w-[1600px] px-5 lg:px-10">
        <div class="grid grid-cols-2 gap-8 lg:grid-cols-4" data-reveal-stagger>
            @foreach($stats as $s)
                <x-stat :value="$s['value']" :suffix="$s['suffix']" :label="$s['label']" />
            @endforeach
        </div>
    </div>

    {{-- Infinite marquee of brand names --}}
    <div class="relative mt-16 overflow-hidden" aria-hidden="true">
        <div class="pointer-events-none absolute inset-y-0 start-0 z-10 w-24 bg-gradient-to-r from-apex-bg to-transparent"></div>
        <div class="pointer-events-none absolute inset-y-0 end-0 z-10 w-24 bg-gradient-to-l from-apex-bg to-transparent"></div>
        <div class="marquee-track gap-12 py-2">
            @for($i = 0; $i < 2; $i++)
                @foreach(['APEX MOTO GROUP', 'NÉO', 'ATLAS', 'VAYU', 'AURUM'] as $name)
                    <span class="font-display text-3xl sm:text-5xl font-bold uppercase tracking-tight text-apex-faint/80 whitespace-nowrap">{{ $name }}<span class="text-accent mx-8">/</span></span>
                @endforeach
            @endfor
        </div>
    </div>
</section>
