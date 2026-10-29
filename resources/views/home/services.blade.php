<section id="services" class="relative z-10 py-20 lg:py-28">
    <div class="mx-auto max-w-[1600px] px-5 lg:px-10">
        <x-section-heading eyebrow="Ownership" title="Everything after the keys." description="From the first test ride to long after purchase, a single group owns your whole ownership experience." />

        <div class="mt-12 grid gap-4 sm:grid-cols-2 lg:grid-cols-3" data-reveal-stagger>
            @foreach($services as $service)
                <article class="group relative overflow-hidden rounded-2xl glass glass-hover p-6 lg:p-8 flex flex-col gap-4"
                         data-reveal-item>
                    {{-- icon with hover animation --}}
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl border border-white/10 text-accent transition-all duration-500 group-hover:border-accent group-hover:bg-accent/10 group-hover:-translate-y-1">
                        <i data-lucide="{{ $service->icon }}" class="h-7 w-7 transition-transform duration-500 group-hover:scale-110"></i>
                    </div>
                    <div>
                        <h3 class="font-display text-lg font-bold uppercase tracking-tight">{{ $service->title }}</h3>
                        <p class="mt-2 text-sm text-apex-muted leading-relaxed">{{ $service->description }}</p>
                    </div>
                    @if($service->cta_label)
                        <a href="{{ $service->cta_route ?? '#' }}"
                           class="mt-auto inline-flex items-center gap-2 text-[11px] font-display uppercase tracking-[0.2em] text-accent transition-transform group-hover:translate-x-1">
                            {{ $service->cta_label }}
                            <i data-lucide="arrow-right" class="h-4 w-4"></i>
                        </a>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</section>
