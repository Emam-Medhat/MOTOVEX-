<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="color-scheme" content="light" />
    <title>Promotions | MOTOVEX Premium Motorcycles</title>
    <style>html,body{background:#f8f9fa!important;color:#0f172a}</style>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <script>
        tailwind.config = {
            darkMode: false,
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans','sans-serif'] },
                    colors: {
                        brand: { 50:'#f5f3ff', 100:'#ede9fe', 200:'#ddd6fe', 300:'#c4b5fd', 400:'#a78bfa', 500:'#8b5cf6', 600:'#7c3aed', 700:'#6d28d9', 800:'#5b21b6', 900:'#4c1d95' }
                    },
                    animation: {
                        'float':      'float 6s ease-in-out infinite',
                        'fade-up':    'fadeUp 0.8s ease-out forwards',
                        'fade-in':    'fadeIn 1s ease-out forwards',
                        'scale-in':   'scaleIn 0.6s ease-out forwards',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4,0,0.6,1) infinite',
                    },
                    keyframes: {
                        float:   { '0%,100%':{ transform:'translateY(0)' }, '50%':{ transform:'translateY(-16px)' } },
                        fadeUp:  { '0%':{ opacity:'0', transform:'translateY(36px)' }, '100%':{ opacity:'1', transform:'translateY(0)' } },
                        fadeIn:  { '0%':{ opacity:'0' }, '100%':{ opacity:'1' } },
                        scaleIn: { '0%':{ opacity:'0', transform:'scale(0.92)' }, '100%':{ opacity:'1', transform:'scale(1)' } },
                    }
                }
            }
        };
    </script>
    <style>
        html { scroll-behavior: smooth; background: #f8f9fa; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8f9fa; color: #0f172a; }

        .hero-gradient {
            background:
                radial-gradient(ellipse 70% 50% at 50% -10%, rgba(124,58,237,.12), transparent),
                linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
        }

        .card-hover { transition: all 0.35s cubic-bezier(0.4,0,0.2,1); }
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 24px 48px -12px rgba(124,58,237,.18);
            border-color: rgba(139,92,246,.45);
        }

        .reveal { opacity: 0; transform: translateY(36px); transition: all 0.75s cubic-bezier(0.16,1,0.3,1); }
        .reveal.active { opacity:1; transform:translateY(0); }

        ::-webkit-scrollbar { width: 7px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #7c3aed; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 overflow-x-hidden">

@include('partials.navbar')

{{-- Hero --}}
<section class="hero-gradient relative overflow-hidden pt-16 pb-20">
    <div class="absolute top-1/4 right-1/4 w-80 h-80 bg-brand-500/8 rounded-full blur-[100px] animate-pulse-slow pointer-events-none"></div>
    <div class="absolute bottom-0 left-1/4 w-96 h-64 bg-indigo-500/6 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        <div class="max-w-2xl space-y-6">
            <div class="inline-flex items-center gap-2 rounded-full border border-brand-500/25 bg-brand-50 px-4 py-1.5 text-xs font-semibold text-brand-700 animate-fade-in">
                <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span>
                Limited-Time Offers
            </div>

            <h1 class="text-5xl sm:text-6xl font-extrabold leading-[1.08] tracking-tight">
                <span class="block opacity-0 animate-fade-up text-slate-900" style="animation-delay:.1s">Exclusive</span>
                <span class="block opacity-0 animate-fade-up text-brand-600" style="animation-delay:.25s">Ride Offers</span>
            </h1>

            <p class="text-lg text-slate-500 leading-relaxed opacity-0 animate-fade-up" style="animation-delay:.4s">
                Hand-picked promotions across our showrooms — financing deals, trade-in bonuses and seasonal service offers.
                Every offer below is live right now.
            </p>

            <div class="flex flex-wrap gap-4 opacity-0 animate-fade-up" style="animation-delay:.55s">
                <a href="{{ route('motorcycles.index') }}" class="inline-flex items-center gap-3 rounded-2xl bg-brand-600 px-7 py-4 text-sm font-bold text-white shadow-xl shadow-brand-500/25 hover:-translate-y-0.5 hover:bg-brand-500 transition-all duration-300">
                    <i class="fa-solid fa-motorcycle"></i> Browse Motorcycles
                </a>
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-7 py-4 text-sm font-semibold text-slate-700 hover:border-brand-300 hover:text-brand-600 transition-all duration-300">
                    Talk to an Advisor <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Promotions Grid --}}
<section class="py-20 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto reveal">
            <span class="text-brand-600 text-sm font-bold uppercase tracking-widest">Current Promotions</span>
            <h2 class="mt-3 text-4xl sm:text-5xl font-extrabold text-slate-900">Offers On Right Now</h2>
            <p class="mt-4 text-slate-500">Only active and unexpired offers are shown here. Check back often — new deals drop regularly.</p>
        </div>

        @if($promotions->isEmpty())
            <div class="mt-16 max-w-xl mx-auto rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-16 text-center reveal">
                <i class="fa-solid fa-tags text-4xl text-slate-300"></i>
                <h3 class="mt-4 text-lg font-bold text-slate-800">No active promotions right now</h3>
                <p class="mt-2 text-sm text-slate-500">Our latest offers are being prepared. In the meantime, explore the full collection.</p>
                <a href="{{ route('motorcycles.index') }}"
                   class="mt-6 inline-flex items-center gap-2 rounded-xl bg-brand-600 px-6 py-3 text-sm font-bold text-white hover:bg-brand-500 transition-colors">
                    <i class="fa-solid fa-motorcycle"></i> Browse Motorcycles
                </a>
            </div>
        @else
            <div class="mt-16 grid sm:grid-cols-2 lg:grid-cols-3 gap-7">
                @foreach($promotions as $i => $promo)
                    @php
                        $mc = $promo->motorcycle;
                        $mcLinkable = $mc && $mc->is_published && $mc->brand && $mc->brand->is_published;

                        // Resolve the image (absolute, storage or site-relative).
                        $rawImage = $promo->image_url;
                        $imgUrl = $rawImage
                            ? (str_starts_with($rawImage, 'http') || str_starts_with($rawImage, '//')
                                ? $rawImage
                                : asset(ltrim($rawImage, '/')))
                            : null;

                        // CTA target — always points to a reachable public page.
                        if ($mcLinkable) {
                            $ctaUrl = route('motorcycles.show', $mc);
                            $ctaLabel = 'View ' . $mc->model_name;
                        } elseif ($promo->brand && $promo->brand->is_published) {
                            $ctaUrl = route('brands.show', $promo->brand);
                            $ctaLabel = 'Explore ' . $promo->brand->name;
                        } else {
                            $ctaUrl = route('motorcycles.index');
                            $ctaLabel = 'Browse Motorcycles';
                        }

                        $hasDiscount = $promo->discount_value !== null && (float) $promo->discount_value > 0;
                        $currency = $mc && $mc->currency ? $mc->currency . ' ' : '';
                    @endphp

                    <article class="card-hover reveal group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" style="transition-delay: {{ $i * 0.1 }}s">
                        <div class="relative">
                            @if($imgUrl)
                                <img src="{{ $imgUrl }}" alt="{{ $promo->title }}" class="h-52 w-full object-cover" loading="lazy">
                            @else
                                <div class="h-52 w-full bg-gradient-to-br from-brand-600 via-brand-500 to-indigo-600 flex items-center justify-center">
                                    <i class="fa-solid fa-tags text-5xl text-white/70"></i>
                                </div>
                            @endif

                            @if($hasDiscount)
                                <span class="absolute top-4 left-4 rounded-full bg-brand-600 px-3 py-1 text-xs font-bold text-white shadow-lg">
                                    @if($promo->discount_type === 'percentage')
                                        {{ rtrim(rtrim(number_format((float) $promo->discount_value, 2), '0'), '.') }}% OFF
                                    @elseif($mc)
                                        Save {{ $currency }}{{ number_format((float) $promo->discount_value) }}
                                    @else
                                        Special Offer
                                    @endif
                                </span>
                            @endif
                        </div>

                        <div class="flex flex-1 flex-col p-7">
                            <div class="flex items-center gap-2 text-xs">
                                <span class="rounded-full bg-brand-50 px-3 py-1 font-semibold text-brand-700">
                                    {{ $promo->brand->name ?? 'MOTOVEX' }}
                                </span>
                                @if($promo->end_date)
                                    <span class="text-slate-400">
                                        <i class="fa-regular fa-clock"></i> Ends {{ $promo->end_date->format('d M Y') }}
                                    </span>
                                @endif
                            </div>

                            <h3 class="mt-3 text-xl font-bold text-slate-900">{{ $promo->title }}</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-500">{{ $promo->description }}</p>

                            @if($promo->hasPriceableDiscount())
                                <div class="mt-5 flex items-baseline gap-3">
                                    <span class="text-2xl font-extrabold text-brand-600">
                                        {{ $currency }}{{ number_format($promo->discountedPrice()) }}
                                    </span>
                                    <span class="text-sm text-slate-400 line-through">
                                        {{ $currency }}{{ number_format((float) $mc->price) }}
                                    </span>
                                </div>
                            @endif

                            @if($promo->start_date || $promo->end_date)
                                <p class="mt-4 text-xs text-slate-400">
                                    <i class="fa-regular fa-calendar"></i>
                                    {{ $promo->start_date ? $promo->start_date->format('d M Y') : 'Now' }}
                                    &ndash;
                                    {{ $promo->end_date ? $promo->end_date->format('d M Y') : 'Ongoing' }}
                                </p>
                            @endif

                            <a href="{{ $ctaUrl }}"
                               class="mt-auto pt-6 inline-flex items-center gap-2 text-sm font-bold text-brand-600 transition-colors hover:text-brand-700">
                                {{ $ctaLabel }}
                                <i class="fa-solid fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>

@include('partials.footer')

{{-- Scroll to top --}}
<button id="scrollTop" class="fixed bottom-6 right-6 z-40 flex h-12 w-12 items-center justify-center rounded-full bg-brand-600 text-white shadow-lg shadow-brand-500/30 opacity-0 pointer-events-none transition-all duration-300 hover:bg-brand-500">
    <i class="fa-solid fa-arrow-up"></i>
</button>

<script>
    const reveals = document.querySelectorAll('.reveal');
    const revealOnScroll = () => {
        reveals.forEach(el => {
            if (el.getBoundingClientRect().top < window.innerHeight - 100) {
                el.classList.add('active');
            }
        });
    };
    window.addEventListener('scroll', revealOnScroll);
    revealOnScroll();

    const scrollTopBtn = document.getElementById('scrollTop');
    window.addEventListener('scroll', () => {
        scrollTopBtn.classList.toggle('opacity-0', window.scrollY < 400);
        scrollTopBtn.classList.toggle('pointer-events-none', window.scrollY < 400);
    });
    scrollTopBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
</script>
</body>
</html>
