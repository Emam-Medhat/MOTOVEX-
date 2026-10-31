<!DOCTYPE html>
<html lang="{{ app()->getLocale() === 'ar' ? 'ar' : 'en' }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('contact.meta_title') }}</title>
    <meta name="description" content="{{ __('contact.meta_desc') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: false,
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        brand: { 50:'#f5f3ff',100:'#ede9fe',200:'#ddd6fe',300:'#c4b5fd',
                                 400:'#a78bfa',500:'#8b5cf6',600:'#7c3aed',700:'#6d28d9',
                                 800:'#5b21b6',900:'#4c1d95' }
                    }
                }
            }
        };
    </script>
    <style>
        html, body { background: #f8f9fa; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: #0f172a; overflow-x: hidden; -webkit-font-smoothing: antialiased; }
        ::-webkit-scrollbar { width: 7px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #7c3aed; border-radius: 10px; }

        .cp-input {
            width: 100%;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            background: #f8fafc;
            padding: 11px 14px;
            font-size: 14px;
            color: #0f172a;
            font-family: inherit;
            outline: none;
            transition: border-color .2s, background .2s, box-shadow .2s;
        }
        .cp-input::placeholder { color: #94a3b8; }
        .cp-input:focus {
            border-color: #8b5cf6;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(139,92,246,.12);
        }
        .cp-input.is-error { border-color: #ef4444; background: #fff5f5; }
        .cp-input.is-error:focus { box-shadow: 0 0 0 3px rgba(239,68,68,.12); }

        .cp-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }
        .cp-error-msg {
            margin-top: 5px;
            font-size: 12px;
            color: #ef4444;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .reveal { opacity: 0; transform: translateY(24px); transition: all .65s cubic-bezier(.16,1,.3,1); }
        .reveal.active { opacity: 1; transform: translateY(0); }
    </style>
@include('partials.rtl-styles')
</head>
<body>

@include('partials.navbar')

{{-- Hero --}}
<section class="bg-white border-b border-slate-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-14 text-center">
        <div class="inline-flex items-center gap-2 rounded-full bg-brand-50 border border-brand-200 px-4 py-1.5 text-xs font-bold text-brand-700 mb-5 reveal">
            <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span>
            {{ __('contact.hero_badge') }}
        </div>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight reveal" style="transition-delay:.1s">
            {{ __('contact.hero_heading') }}
        </h1>
        <p class="mt-4 text-slate-500 text-lg max-w-xl mx-auto reveal" style="transition-delay:.2s">
            {{ __('contact.hero_sub') }}
        </p>
    </div>
</section>

{{-- Main --}}
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-14">
    <div class="grid lg:grid-cols-5 gap-10 lg:gap-14">

        {{-- ── Left: Info + Stats ──────────────────────────────── --}}
        <aside class="lg:col-span-2 flex flex-col gap-8">

            {{-- Contact details --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-7 shadow-sm reveal">
                <h2 class="text-base font-bold text-slate-900 mb-6">{{ __('contact.details_heading') }}</h2>
                <div class="flex flex-col gap-5">
                    @foreach([
                        ['fa-location-dot', __('contact.label_address'), '123 Street, Cairo, Egypt'],
                        ['fa-envelope',     __('contact.label_email'),   'info@motovex.com'],
                        ['fa-phone',        __('contact.label_phone'),   '(+012) 3456 7890'],
                        ['fa-clock',        __('contact.label_hours'),   'Sun – Thu: 10am – 8pm'],
                    ] as [$icon, $label, $value])
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-brand-50 border border-brand-100 flex items-center justify-center text-brand-600 shrink-0">
                            <i class="fa-solid {{ $icon }} text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ $label }}</p>
                            <p class="text-sm font-semibold text-slate-800 mt-0.5">{{ $value }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-3 reveal" style="transition-delay:.1s">
                @foreach([['150+',__('contact.stat_bikes)],['12K+',__('contact.stat_clients)],['98%',__('contact.stat_satisfaction')]] as [$n,$l])
                <div class="bg-gradient-to-br from-brand-600 to-brand-800 rounded-2xl p-5 text-center text-white shadow-lg shadow-brand-500/25">
                    <div class="text-2xl font-extrabold">{{ $n }}</div>
                    <div class="text-[11px] font-medium opacity-80 mt-1">{{ $l }}</div>
                </div>
                @endforeach
            </div>

            {{-- Guarantees --}}
            <div class="flex flex-wrap gap-2 reveal" style="transition-delay:.2s">
                @foreach([__('contact.guarantee_1'),__('contact.guarantee_2'),__('contact.guarantee_3')] as $tag)
                <span class="inline-flex items-center gap-2 bg-brand-50 border border-brand-200 rounded-xl px-3 py-2 text-xs font-semibold text-brand-700">
                    <i class="fa-solid fa-check text-[10px]"></i> {{ $tag }}
                </span>
                @endforeach
            </div>
        </aside>

        {{-- ── Right: Form ─────────────────────────────────────── --}}
        <div class="lg:col-span-3 reveal" style="transition-delay:.15s">

            {{-- Success flash --}}
            @if(session('success'))
            <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-2xl px-5 py-4 text-sm font-semibold text-emerald-800">
                <i class="fa-solid fa-circle-check text-emerald-500 text-lg shrink-0"></i>
                {{ session('success') }}
            </div>
            @endif

            {{-- Errors summary --}}
            @if($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700">
                <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                Please fix the errors below.
            </div>
            @endif

            <div class="bg-white border border-slate-200 rounded-3xl shadow-sm p-7 sm:p-10">
                <h2 class="text-xl font-extrabold text-slate-900 mb-7">Send Your Message</h2>

                <form method="POST" action="{{ route('contact.store') }}" novalidate>
                    @csrf

                    {{-- Motorcycle pre-fill --}}
                    @if(isset($motorcycle) && $motorcycle)
                        <input type="hidden" name="motorcycle_id" value="{{ $motorcycle->id }}">
                        <input type="hidden" name="enquiry_type"  value="product">
                        <div class="mb-5 flex items-center gap-3 rounded-xl bg-brand-50 border border-brand-200 px-4 py-3 text-sm font-semibold text-brand-800">
                            <i class="fa-solid fa-motorcycle text-brand-500"></i>
                            Product enquiry: {{ $motorcycle->brand->name }} {{ $motorcycle->model_name }}
                        </div>
                    @endif

                    {{-- Spare parts note (when coming from spare-parts page) --}}
                    @if(!empty($prefillSubject) && str_contains($prefillSubject, 'Spare Part'))
                    <div class="mb-5 flex items-center gap-3 rounded-xl bg-amber-50 border border-amber-200 px-4 py-3 text-sm font-semibold text-amber-800">
                        <i class="fa-solid fa-gears text-amber-500"></i>
                        Spare Part Enquiry — form pre-filled for you
                    </div>
                    @endif

                    <div class="grid sm:grid-cols-2 gap-5 mb-5">
                        {{-- Name --}}
                        <div>
                            <label class="cp-label">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()?->name ?? '') }}"
                                   placeholder="Your full name"
                                   class="cp-input {{ $errors->has('name') ? 'is-error' : '' }}">
                            @error('name')<p class="cp-error-msg"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="cp-label">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()?->email ?? '') }}"
                                   placeholder="you@example.com"
                                   class="cp-input {{ $errors->has('email') ? 'is-error' : '' }}">
                            @error('email')<p class="cp-error-msg"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-5 mb-5">
                        {{-- Phone --}}
                        <div>
                            <label class="cp-label">Phone Number</label>
                            <input type="tel" name="phone" value="{{ old('phone', auth()->user()?->phone ?? '') }}"
                                   placeholder="+20 ..."
                                   class="cp-input">
                        </div>

                        {{-- Enquiry type --}}
                        @unless(isset($motorcycle) && $motorcycle)
                        <div>
                            <label class="cp-label">Enquiry Type <span class="text-red-500">*</span></label>
                            <select name="enquiry_type"
                                    class="cp-input {{ $errors->has('enquiry_type') ? 'is-error' : '' }}"
                                    style="cursor:pointer">
                                <option value="">Select type…</option>
                                <option value="general"    {{ old('enquiry_type', $prefillEnquiryType ?? '') === 'general'    ? 'selected' : '' }}>General Enquiry</option>
                                <option value="test_drive" {{ old('enquiry_type', $prefillEnquiryType ?? '') === 'test_drive' ? 'selected' : '' }}>Test Ride</option>
                                <option value="parts"      {{ old('enquiry_type', $prefillEnquiryType ?? '') === 'parts'      ? 'selected' : '' }}>Spare Parts</option>
                                <option value="service"    {{ old('enquiry_type', $prefillEnquiryType ?? '') === 'service'    ? 'selected' : '' }}>Service Booking</option>
                            </select>
                            @error('enquiry_type')<p class="cp-error-msg"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                        </div>
                        @endunless
                    </div>

                    {{-- Subject --}}
                    <div class="mb-5">
                        <label class="cp-label">Subject <span class="text-red-500">*</span></label>
                        <input type="text" name="subject"
                               value="{{ old('subject', $prefillSubject ?? '') }}"
                               placeholder="What is your message about?"
                               class="cp-input {{ $errors->has('subject') ? 'is-error' : '' }}">
                        @error('subject')<p class="cp-error-msg"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                    </div>

                    {{-- Message --}}
                    <div class="mb-7">
                        <label class="cp-label">Message <span class="text-red-500">*</span></label>
                        <textarea name="message" rows="5"
                                  placeholder="Write your message here…"
                                  class="cp-input {{ $errors->has('message') ? 'is-error' : '' }}"
                                  style="resize:vertical">{{ old('message') }}</textarea>
                        @error('message')<p class="cp-error-msg"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>@enderror
                    </div>

                    <button type="submit"
                            class="w-full flex items-center justify-center gap-3 bg-brand-600 text-white rounded-2xl py-4 font-bold text-sm shadow-lg shadow-brand-500/25 hover:bg-brand-700 hover:-translate-y-0.5 transition-all duration-300">
                        <i class="fa-solid fa-paper-plane"></i> Send Message
                    </button>

                    <p class="mt-4 text-center text-xs text-slate-400">
                        <i class="fa-solid fa-lock mr-1"></i>
                        Your information is kept private and never shared.
                    </p>
                </form>
            </div>
        </div>

    </div>
</div>

@include('partials.footer')

<script>
    const reveals = document.querySelectorAll('.reveal');
    const observer = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('active'); observer.unobserve(e.target); } });
    }, { threshold: 0.08 });
    reveals.forEach(el => observer.observe(el));
</script>

</body>
</html>
