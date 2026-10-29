<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="color-scheme" content="light" />
    <title>Services | MOTOVEX Premium Motorcycles</title>
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
                        brand: { 400:'#a78bfa', 500:'#8b5cf6', 600:'#7c3aed', 700:'#6d28d9', 50:'#f5f3ff', 100:'#ede9fe' }
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

        .card-hover {
            transition: all 0.35s cubic-bezier(0.4,0,0.2,1);
        }
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 24px 48px -12px rgba(124,58,237,.18);
            border-color: rgba(139,92,246,.45);
        }

        .reveal {
            opacity: 0;
            transform: translateY(36px);
            transition: all 0.75s cubic-bezier(0.16,1,0.3,1);
        }
        .reveal.active { opacity:1; transform:translateY(0); }

        .stat-number {
            background: linear-gradient(135deg, #0f172a 0%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        ::-webkit-scrollbar { width: 7px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #7c3aed; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 overflow-x-hidden">

{{-- Announcement --}}
<div class="bg-gradient-to-r from-brand-700 via-brand-600 to-indigo-600 py-2.5 text-center text-xs font-semibold text-white">
    <i class="fa-solid fa-motorcycle mr-2"></i>
    2026 Collection Available Now · Free Test Rides · Certified Inspections
</div>

{{-- Header --}}
<header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur-xl shadow-sm">
    <div class="mx-auto flex h-16 max-w-7xl items-center gap-6 px-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-3 group">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-lg text-white shadow-md shadow-brand-500/25 group-hover:scale-105 transition-transform duration-300">
                <i class="fa-solid fa-motorcycle"></i>
            </span>
            <div>
                <strong class="block text-xl font-extrabold tracking-tight text-slate-900">MOTOVEX</strong>
                <small class="text-[9px] font-bold uppercase tracking-[0.2em] text-brand-600">Premium Motorcycles</small>
            </div>
        </a>

        <nav class="ml-8 hidden items-center gap-7 text-sm font-medium text-slate-500 lg:flex">
            <a href="{{ route('home') }}" class="hover:text-slate-900 transition">Home</a>
            <a href="{{ route('motorcycles.index') }}" class="hover:text-slate-900 transition">Shop</a>
            <a href="{{ route('brands.index') }}" class="hover:text-slate-900 transition">Brands</a>
            <a href="{{ route('services') }}" class="text-brand-600 font-semibold">Services</a>
            <a href="{{ route('contact') }}" class="hover:text-slate-900 transition">Contact</a>
        </nav>

        <div class="ml-auto flex items-center gap-3">
            <a href="{{ route('contact') }}" class="hidden sm:inline-flex items-center gap-2 rounded-full bg-brand-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-brand-500 transition shadow-md shadow-brand-500/20">
                Book a Ride <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
            <button id="menuButton" class="flex h-10 w-10 items-center justify-center rounded-full hover:bg-slate-100 lg:hidden text-slate-600">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>
    <nav id="mobileMenu" class="hidden border-t border-slate-100 bg-white px-5 py-4 text-sm text-slate-600 lg:hidden">
        <div class="flex flex-wrap gap-5">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('motorcycles.index') }}">Shop</a>
            <a href="{{ route('brands.index') }}">Brands</a>
            <a href="{{ route('services') }}">Services</a>
            <a href="{{ route('contact') }}">Contact</a>
        </div>
    </nav>
</header>

{{-- Hero --}}
<section class="hero-gradient relative overflow-hidden pt-16 pb-24">
    {{-- Subtle orbs --}}
    <div class="absolute top-1/4 right-1/4 w-80 h-80 bg-brand-500/8 rounded-full blur-[100px] animate-pulse-slow pointer-events-none"></div>
    <div class="absolute bottom-0 left-1/4 w-96 h-64 bg-indigo-500/6 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            {{-- Text --}}
            <div class="space-y-7">
                <div class="inline-flex items-center gap-2 rounded-full border border-brand-500/25 bg-brand-50 px-4 py-1.5 text-xs font-semibold text-brand-700 animate-fade-in">
                    <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span>
                    Expert Motorcycle Services
                </div>

                <h1 class="text-5xl sm:text-6xl font-extrabold leading-[1.08] tracking-tight">
                    <span class="block opacity-0 animate-fade-up text-slate-900" style="animation-delay:.1s">Everything</span>
                    <span class="block opacity-0 animate-fade-up text-brand-600" style="animation-delay:.25s">Your Ride Needs</span>
                </h1>

                <p class="text-lg text-slate-500 max-w-lg leading-relaxed opacity-0 animate-fade-up" style="animation-delay:.4s">
                    MOTOVEX offers comprehensive ownership support — from the day you choose your bike to every mile after. Factory-trained experts, genuine parts, full protection.
                </p>

                <div class="flex flex-wrap gap-4 opacity-0 animate-fade-up" style="animation-delay:.55s">
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-3 rounded-2xl bg-brand-600 px-7 py-4 text-sm font-bold text-white shadow-xl shadow-brand-500/25 hover:-translate-y-0.5 hover:bg-brand-500 transition-all duration-300">
                        <i class="fa-solid fa-calendar-check"></i> Book a Service
                    </a>
                    <a href="{{ route('motorcycles.index') }}" class="inline-flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-7 py-4 text-sm font-semibold text-slate-700 hover:border-brand-300 hover:text-brand-600 transition-all duration-300">
                        Browse Motorcycles <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>

                {{-- Mini stats --}}
                <div class="flex gap-8 pt-4 opacity-0 animate-fade-up" style="animation-delay:.7s">
                    <div>
                        <div class="text-3xl font-extrabold stat-number">150+</div>
                        <div class="text-xs text-slate-500 mt-1">Premium Bikes</div>
                    </div>
                    <div>
                        <div class="text-3xl font-extrabold stat-number">12K+</div>
                        <div class="text-xs text-slate-500 mt-1">Happy Customers</div>
                    </div>
                    <div>
                        <div class="text-3xl font-extrabold stat-number">98%</div>
                        <div class="text-xs text-slate-500 mt-1">Satisfaction</div>
                    </div>
                </div>
            </div>

            {{-- Visual --}}
            <div class="relative opacity-0 animate-scale-in" style="animation-delay:.3s">
                <div class="relative rounded-3xl overflow-hidden border border-slate-200 shadow-2xl shadow-brand-500/10">
                    <img src="https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=900&q=90"
                         alt="MOTOVEX Service" class="w-full aspect-[4/3] object-cover" />
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 via-transparent to-transparent"></div>
                    <div class="absolute bottom-5 left-5 right-5 rounded-2xl border border-white/20 bg-white/90 backdrop-blur-md p-4 shadow-lg">
                        <div class="flex items-center gap-4">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-600 text-white">
                                <i class="fa-solid fa-shield-halved text-lg"></i>
                            </div>
                            <div>
                                <div class="font-bold text-sm text-slate-900">100% Certified</div>
                                <div class="text-xs text-slate-500">Full Inspection + Warranty</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="absolute -top-5 -right-5 w-22 h-22 w-20 h-20 rounded-2xl border border-brand-200 bg-brand-50 flex items-center justify-center animate-float shadow-md">
                    <i class="fa-solid fa-wrench text-2xl text-brand-600"></i>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Services Grid --}}
<section class="py-24 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto reveal">
            <span class="text-brand-600 text-sm font-bold uppercase tracking-widest">What We Offer</span>
            <h2 class="mt-3 text-4xl sm:text-5xl font-extrabold text-slate-900">Our Services</h2>
            <p class="mt-4 text-slate-500">Every detail designed to make your ownership experience exceptional from day one.</p>
        </div>

        <div class="mt-16 grid sm:grid-cols-2 gap-7">
            @foreach([
                [
                    'icon'  => 'fa-gears',
                    'title' => 'Scheduled Maintenance',
                    'desc'  => 'Factory-trained technicians keep your motorcycle performing at its best using genuine OEM parts and manufacturer-approved procedures.',
                    'items' => ['Oil & filter changes','Brake inspections & fluid flush','Chain & sprocket service','Tyre checks & replacements','Full pre-season inspection'],
                    'color' => 'bg-violet-50 text-violet-600',
                ],
                [
                    'icon'  => 'fa-box-open',
                    'title' => 'Genuine Spare Parts',
                    'desc'  => 'Every component sourced directly from manufacturers or authorised distributors — ensuring fit, reliability, and warranty compliance.',
                    'items' => ['OEM-style parts sourcing','Supported MOTOVEX brands','Regional delivery options','Compatibility guidance','Transparent pricing'],
                    'color' => 'bg-indigo-50 text-indigo-600',
                ],
                [
                    'icon'  => 'fa-shield-halved',
                    'title' => 'Warranty & Protection',
                    'desc'  => 'Comprehensive plans that protect your investment and keep you moving, wherever the road takes you.',
                    'items' => ['Standard manufacturer warranty','Extended protection plans','Accidental damage cover','Roadside assistance','Track-day add-on cover'],
                    'color' => 'bg-emerald-50 text-emerald-600',
                ],
                [
                    'icon'  => 'fa-motorcycle',
                    'title' => 'Test Rides & Delivery',
                    'desc'  => 'Experience your dream motorcycle before you commit. Our experts guide you through every feature and help arrange delivery.',
                    'items' => ['Booked showroom test rides','Home delivery available','White-glove handover','Full model orientation','Finance arranged same day'],
                    'color' => 'bg-amber-50 text-amber-600',
                ],
            ] as $i => $service)
            <div class="card-hover reveal rounded-2xl border border-slate-200 bg-white p-8 shadow-sm" style="transition-delay: {{ $i * 0.1 }}s">
                <div class="flex items-start gap-5">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl {{ $service['color'] }} text-2xl">
                        <i class="fa-solid {{ $service['icon'] }}"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">{{ $service['title'] }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-500">{{ $service['desc'] }}</p>
                    </div>
                </div>
                <ul class="mt-6 space-y-2.5">
                    @foreach($service['items'] as $item)
                    <li class="flex items-center gap-3 text-sm text-slate-600">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-brand-50 text-brand-600 shrink-0">
                            <i class="fa-solid fa-check text-[9px]"></i>
                        </span>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Features Strip --}}
<section class="py-16 bg-slate-50 border-y border-slate-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 text-center">
            @foreach([
                ['fa-rotate-left','Free Return','30 days money back'],
                ['fa-gauge-high','Test Rides','Book online today'],
                ['fa-headset','Support 24/7','Expert help always'],
                ['fa-shield-halved','Secure Payment','100% encrypted'],
                ['fa-certificate','Certified','150-point check'],
                ['fa-crown','Premium Brands','Top brands only'],
            ] as $f)
            <div class="reveal flex flex-col items-center gap-2">
                <div class="w-12 h-12 rounded-full bg-brand-100 flex items-center justify-center text-brand-600 text-lg">
                    <i class="fa-solid {{ $f[0] }}"></i>
                </div>
                <span class="text-xs font-bold text-slate-800">{{ $f[1] }}</span>
                <span class="text-[10px] text-slate-500">{{ $f[2] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-24 bg-white relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-brand-50/40 to-transparent pointer-events-none"></div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-brand-100 bg-gradient-to-br from-brand-50 via-white to-indigo-50 p-10 sm:p-16 text-center reveal shadow-xl shadow-brand-500/8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-brand-600 text-white mb-6">
                <i class="fa-solid fa-calendar-check text-2xl"></i>
            </div>
            <h2 class="text-4xl sm:text-5xl font-extrabold text-slate-900 leading-tight">Ready to Book a Service?</h2>
            <p class="mt-4 text-slate-500 text-lg max-w-xl mx-auto">
                Contact our team and we'll get you booked in at the next available slot. Fast, professional, and hassle-free.
            </p>
            <div class="mt-10 flex flex-wrap justify-center gap-4">
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center gap-3 rounded-2xl bg-brand-600 px-8 py-4 text-sm font-bold text-white shadow-xl shadow-brand-500/25 hover:bg-brand-500 hover:-translate-y-0.5 transition-all duration-300">
                    <i class="fa-solid fa-paper-plane"></i> Book Now
                </a>
                <a href="tel:+201234567890"
                   class="inline-flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-8 py-4 text-sm font-semibold text-slate-700 hover:border-brand-300 hover:text-brand-600 transition-all duration-300">
                    <i class="fa-solid fa-phone"></i> Call Us
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Footer --}}
<footer class="border-t border-slate-200 bg-slate-900 text-slate-300">
    <div class="mx-auto grid max-w-7xl grid-cols-1 gap-10 px-4 py-12 sm:px-6 md:grid-cols-2 lg:grid-cols-4 lg:px-8">
        <div>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-white">
                    <i class="fa-solid fa-motorcycle"></i>
                </span>
                <div>
                    <strong class="block text-xl font-extrabold text-white">MOTOVEX</strong>
                    <small class="text-[9px] uppercase tracking-[0.2em] text-brand-400">Premium Motorcycles</small>
                </div>
            </a>
            <p class="mt-4 max-w-xs text-sm leading-relaxed text-slate-400">Premium motorcycle dealership with certified inspections, genuine parts, and expert support.</p>
            <div class="mt-5 flex gap-2">
                @foreach(['fa-facebook-f','fa-instagram','fa-youtube'] as $icon)
                <a href="#" class="flex h-9 w-9 items-center justify-center rounded-full bg-white/5 hover:bg-brand-600 transition">
                    <i class="fa-brands {{ $icon }}"></i>
                </a>
                @endforeach
            </div>
        </div>
        <div>
            <h3 class="mb-4 text-xs font-bold uppercase tracking-wider text-white">Quick Links</h3>
            <ul class="space-y-2.5 text-sm text-slate-400">
                <li><a href="{{ route('home') }}" class="hover:text-white transition">Home</a></li>
                <li><a href="{{ route('motorcycles.index') }}" class="hover:text-white transition">Shop</a></li>
                <li><a href="{{ route('services') }}" class="hover:text-white transition">Services</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-white transition">Contact</a></li>
            </ul>
        </div>
        <div>
            <h3 class="mb-4 text-xs font-bold uppercase tracking-wider text-white">Top Brands</h3>
            <ul class="space-y-2.5 text-sm text-slate-400">
                @foreach($footerBrands as $fb)
                <li><a href="{{ route('brands.show', $fb->slug) }}" class="hover:text-white transition">{{ $fb->name }}</a></li>
                @endforeach
            </ul>
        </div>
        <div>
            <h3 class="mb-3 text-xs font-bold uppercase tracking-wider text-white">Newsletter</h3>
            <p class="mb-4 text-sm text-slate-400">Get updates on new arrivals and exclusive offers.</p>
            <form class="flex gap-2">
                <input type="email" required placeholder="Your email"
                       class="min-w-0 flex-1 rounded-lg border border-white/10 bg-white/5 px-3 py-2.5 text-sm text-white outline-none placeholder:text-slate-500 focus:border-brand-500" />
                <button type="submit" class="rounded-lg bg-brand-600 px-4 text-sm font-bold text-white hover:bg-brand-500 transition">Go</button>
            </form>
        </div>
    </div>
    <div class="border-t border-white/10">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-5 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <span>© {{ date('Y') }} MOTOVEX Premium Motorcycle Group. All rights reserved.</span>
            <div class="flex gap-5">
                <a href="#" class="hover:text-white transition">Privacy</a>
                <a href="#" class="hover:text-white transition">Terms</a>
            </div>
        </div>
    </div>
</footer>

{{-- Scroll to top --}}
<button id="scrollTop" class="fixed bottom-6 right-6 z-40 flex h-12 w-12 items-center justify-center rounded-full bg-brand-600 text-white shadow-lg shadow-brand-500/30 opacity-0 pointer-events-none transition-all duration-300 hover:bg-brand-500">
    <i class="fa-solid fa-arrow-up"></i>
</button>

<script>
    // Reveal on scroll
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

    // Scroll to top
    const scrollTopBtn = document.getElementById('scrollTop');
    window.addEventListener('scroll', () => {
        scrollTopBtn.classList.toggle('opacity-0', window.scrollY < 400);
        scrollTopBtn.classList.toggle('pointer-events-none', window.scrollY < 400);
    });
    scrollTopBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    // Mobile menu
    document.getElementById('menuButton').addEventListener('click', () => {
        document.getElementById('mobileMenu').classList.toggle('hidden');
    });

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const target = document.querySelector(a.getAttribute('href'));
            if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth' }); }
        });
    });
</script>
</body>
</html>
