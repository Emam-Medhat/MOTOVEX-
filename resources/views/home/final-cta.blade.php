<section id="final-cta" class="relative z-10 py-20 lg:py-32 overflow-hidden">
    {{-- ambient backdrop --}}
    <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
        <div class="absolute inset-0" style="background: radial-gradient(50% 60% at 50% 40%, color-mix(in oklab, var(--color-accent) 12%, transparent), transparent 70%)"></div>
    </div>

    <div class="mx-auto max-w-[1600px] px-5 lg:px-10">
        <div class="grid gap-12 lg:grid-cols-2 lg:items-center">

            {{-- Headline --}}
            <div data-reveal>
                <span class="eyebrow flex items-center gap-3">
                    <span class="inline-block h-px w-8 bg-accent"></span> Test Ride
                </span>
                <h2 class="mt-4 font-display text-[clamp(2.5rem,7vw,5.5rem)] font-bold uppercase tracking-tight leading-[0.9]">
                    Ride Your<br><span class="text-accent accent-text-glow">Next Chapter</span>
                </h2>
                <p class="mt-6 max-w-md text-base text-apex-muted leading-relaxed">
                    Book a no-obligation test ride at your nearest showroom. Our team will confirm your slot by phone.
                </p>
                <ul class="mt-8 flex flex-col gap-3">
                    @foreach(['Manufacturer-trained instructors', 'Full safety kit provided', 'Available across the region'] as $point)
                        <li class="flex items-center gap-3 text-sm text-apex-muted">
                            <i data-lucide="check-circle-2" class="h-5 w-5 text-accent"></i>{{ $point }}
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Form --}}
            <div class="rounded-2xl glass p-6 sm:p-8" data-reveal
                 x-data="{
                    form: { name: '', phone: '', email: '', motorcycle_id: '', preferred_showroom: '' },
                    errors: {},
                    sending: false,
                    toast: '',
                    submit() {
                        this.errors = {};
                        if(!this.form.name.trim()) this.errors.name = 'Please tell us your name.';
                        if(!this.form.phone.trim()) this.errors.phone = 'A phone number is required.';
                        else if(!/^[0-9+\-\s()]{6,30}$/.test(this.form.phone)) this.errors.phone = 'That phone number does not look right.';
                        if(this.form.email && !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(this.form.email)) this.errors.email = 'Enter a valid email.';
                        if(Object.keys(this.errors).length) return;
                        this.sending = true;
                        fetch('{{ route('test-ride.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-Token': document.querySelector('meta[name=csrf-token]')?.content || '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify(this.form),
                        }).then(r => r.json()).then(data => {
                            this.sending = false;
                            this.toast = data.message || 'Request received — we will call you to confirm.';
                            this.form = { name: '', phone: '', email: '', motorcycle_id: '', preferred_showroom: '' };
                            setTimeout(() => this.toast = '', 6000);
                        }).catch(() => {
                            this.sending = false;
                            this.toast = 'Something went wrong. Please try again.';
                        });
                    }
                 }">

                {{-- success toast --}}
                <template x-if="toast">
                    <div role="status" class="mb-5 flex items-center gap-3 rounded-xl border border-accent/40 bg-accent/10 px-4 py-3 text-sm text-apex-text">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-accent text-black">
                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        </span>
                        <span x-text="toast"></span>
                    </div>
                </template>

                <form @submit.prevent="submit()" novalidate class="flex flex-col gap-4">
                    @csrf
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="tr-name" class="mb-1.5 block text-[11px] font-display uppercase tracking-[0.2em] text-apex-muted">Full Name</label>
                            <input id="tr-name" type="text" x-model="form.name" autocomplete="name"
                                   class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-apex-text placeholder-apex-faint focus:border-accent focus:outline-none"
                                   :class="errors.name ? 'border-red-500' : ''">
                            <p x-show="errors.name" x-text="errors.name" class="mt-1 text-xs text-red-400"></p>
                        </div>
                        <div>
                            <label for="tr-phone" class="mb-1.5 block text-[11px] font-display uppercase tracking-[0.2em] text-apex-muted">Phone</label>
                            <input id="tr-phone" type="tel" x-model="form.phone" autocomplete="tel"
                                   class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-apex-text placeholder-apex-faint focus:border-accent focus:outline-none"
                                   :class="errors.phone ? 'border-red-500' : ''">
                            <p x-show="errors.phone" x-text="errors.phone" class="mt-1 text-xs text-red-400"></p>
                        </div>
                    </div>

                    <div>
                        <label for="tr-email" class="mb-1.5 block text-[11px] font-display uppercase tracking-[0.2em] text-apex-muted">Email <span class="text-apex-faint normal-case">(optional)</span></label>
                        <input id="tr-email" type="email" x-model="form.email" autocomplete="email"
                               class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-apex-text placeholder-apex-faint focus:border-accent focus:outline-none"
                               :class="errors.email ? 'border-red-500' : ''">
                        <p x-show="errors.email" x-text="errors.email" class="mt-1 text-xs text-red-400"></p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="tr-model" class="mb-1.5 block text-[11px] font-display uppercase tracking-[0.2em] text-apex-muted">Model</label>
                            <select id="tr-model" x-model="form.motorcycle_id"
                                    class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-apex-text focus:border-accent focus:outline-none">
                                <option value="">Any model</option>
                                @foreach($motorcycles as $m)
                                    <option value="{{ $m->id }}">{{ $m->brand->name }} {{ $m->model_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="tr-showroom" class="mb-1.5 block text-[11px] font-display uppercase tracking-[0.2em] text-apex-muted">Preferred Showroom</label>
                            <select id="tr-showroom" x-model="form.preferred_showroom"
                                    class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-apex-text focus:border-accent focus:outline-none">
                                <option value="">No preference</option>
                                @foreach($showrooms as $showroom)
                                    <option value="{{ $showroom->name }}">{{ $showroom->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button type="submit" :disabled="sending"
                            class="btn btn-primary mt-2 w-full sm:w-auto sm:self-start disabled:opacity-60" data-magnetic>
                        <span x-show="!sending">Book My Test Ride</span>
                        <span x-show="sending" class="flex items-center gap-2">
                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-opacity="0.3" stroke-width="3"/><path d="M22 12a10 10 0 0 0-10-10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>
                            Sending…
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
