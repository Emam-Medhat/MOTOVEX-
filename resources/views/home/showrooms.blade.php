<section id="showrooms" class="relative z-10 py-20 lg:py-28"
         x-data="{ active: 0 }">
    <div class="mx-auto max-w-[1600px] px-5 lg:px-10">
        <x-section-heading eyebrow="Find Us" title="Showrooms across the region." description="Step into any of our locations — from Riyadh to Muscat." />

        <div class="mt-12 grid gap-6 lg:grid-cols-[1fr_1.3fr] lg:items-start">

            {{-- City tabs --}}
            <div class="flex flex-col gap-2" data-reveal>
                @foreach($showrooms as $i => $showroom)
                    <button type="button" @click="active = {{ $i }}"
                            :class="active === {{ $i }} ? 'border-accent/40 bg-white/[0.04] text-apex-text' : 'border-white/5 text-apex-muted hover:text-apex-text'"
                            class="group flex items-center justify-between rounded-xl border px-5 py-4 text-start transition-all"
                            :aria-selected="(active === {{ $i }}).toString()">
                        <div class="flex items-center gap-3">
                            <i data-lucide="map-pin" class="h-5 w-5 text-accent"></i>
                            <span class="font-display text-sm uppercase tracking-wider">{{ $showroom->name }}</span>
                        </div>
                        <i data-lucide="chevron-right" class="h-4 w-4 transition-transform" :class="active === {{ $i }} ? 'translate-x-1 text-accent' : ''"></i>
                    </button>
                @endforeach
            </div>

            {{-- Detail card + map --}}
            <div class="relative" data-reveal>
                @foreach($showrooms as $i => $showroom)
                    <div x-show="active === {{ $i }}" x-cloak
                         x-transition:enter="transition ease-[cubic-bezier(0.16,1,0.3,1)] duration-500"
                         x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="relative overflow-hidden rounded-2xl glass">
                            {{-- stylized map placeholder (no external keys) --}}
                            <div class="relative h-44 overflow-hidden border-b border-white/5" aria-hidden="true">
                                <div class="absolute inset-0" style="background: radial-gradient(circle at 50% 50%, color-mix(in oklab, var(--color-accent) 10%, transparent), transparent 70%), var(--color-apex-surface)"></div>
                                <svg class="absolute inset-0 h-full w-full opacity-20" viewBox="0 0 400 200" preserveAspectRatio="xMidYMid slice">
                                    <g stroke="currentColor" stroke-width="0.5" fill="none" class="text-apex-text">
                                        @for($g = 0; $g <= 10; $g++) <path d="M{{ $g*40 }} 0 V200" /><path d="M0 {{ $g*20 }} H400" /> @endfor
                                        <path d="M0 120 Q100 80 200 110 T400 90" stroke-width="1.5"/>
                                        <path d="M60 0 Q80 60 120 120 T160 200" stroke-width="1.5"/>
                                    </g>
                                </svg>
                                <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
                                    <span class="hotspot" style="position:relative"></span>
                                </div>
                            </div>

                            <div class="p-6 sm:p-8">
                                <h3 class="font-display text-xl font-bold uppercase tracking-tight">{{ $showroom->name }}</h3>
                                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                    <div class="flex items-start gap-3">
                                        <i data-lucide="map-pin" class="mt-0.5 h-4 w-4 shrink-0 text-accent"></i>
                                        <span class="text-sm text-apex-muted">{{ $showroom->location }}</span>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <i data-lucide="phone" class="mt-0.5 h-4 w-4 shrink-0 text-accent"></i>
                                        <span class="text-sm text-apex-muted">{{ $showroom->phone }}</span>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <i data-lucide="mail" class="mt-0.5 h-4 w-4 shrink-0 text-accent"></i>
                                        <span class="text-sm text-apex-muted">{{ $showroom->email }}</span>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <i data-lucide="clock" class="mt-0.5 h-4 w-4 shrink-0 text-accent"></i>
                                        <span class="text-sm text-apex-muted">{{ $showroom->opening_hours }}</span>
                                    </div>
                                </div>
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($showroom->location) }}"
                                   target="_blank" rel="noopener"
                                   class="btn btn-ghost mt-6 inline-flex !py-2.5 !px-5 !text-[10px]">
                                    Get Directions
                                    <i data-lucide="navigation" class="h-4 w-4"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
