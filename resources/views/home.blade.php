<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('group.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --bg-dark: #12161A;
            --bg-card: #1A1F24;
            --text-white: #FFFFFF;
            --accent-green: #37FE3D;
            --nav-gray: #B0BEC5;
            --button-green: #2ECC71;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--bg-dark);
            color: var(--text-white);
            font-family: Arial, sans-serif;
            min-height: 100vh;
        }

        /* ── HEADER ── */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 40px;
            background-color: var(--bg-card);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--text-white);
            font-weight: 700;
            font-size: 18px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .logo-dot {
            width: 10px; height: 10px;
            background: var(--accent-green);
            border-radius: 50%;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 8px;
            padding: 0;
        }
        nav li a {
            color: var(--nav-gray);
            text-decoration: none;
            font-size: 13px;
            padding: 6px 14px;
            border-radius: 6px;
            transition: color .2s, background .2s;
        }
        nav li a:hover {
            color: var(--text-white);
            background: rgba(255,255,255,0.06);
        }
        nav li a.active {
            color: var(--accent-green);
            background: rgba(55, 254, 61, 0.08);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-green {
            padding: 9px 20px;
            background: var(--button-green);
            color: #000;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: opacity .2s;
        }
        .btn-green:hover { opacity: .85; }
        .btn-white {
            padding: 9px 20px;
            background: var(--text-white);
            color: #000;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: opacity .2s;
        }
        .btn-white:hover { opacity: .85; }
        .btn-outline {
            padding: 9px 20px;
            background: transparent;
            color: var(--text-white);
            border: 1.5px solid rgba(255,255,255,0.3);
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: border-color .2s, color .2s;
        }
        .btn-outline:hover { border-color: var(--accent-green); color: var(--accent-green); }

        /* ── HERO ── */
        .hero {
            display: flex;
            align-items: center;
            padding: 60px 40px 40px;
            position: relative;
            min-height: 520px;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 60% 70% at 65% 50%, rgba(55,254,61,.10), transparent 70%);
            pointer-events: none;
        }
        .hero-text { flex: 0 0 45%; z-index: 2; }
        .hero-text .brand-name {
            font-size: 13px;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: var(--nav-gray);
            margin-bottom: 12px;
        }
        .hero-text h1 {
            font-size: clamp(44px, 6vw, 72px);
            font-weight: 900;
            line-height: 1;
            letter-spacing: -1px;
            margin-bottom: 8px;
        }
        .hero-text h1 span {
            color: var(--accent-green);
            display: block;
        }
        .hero-text .tagline {
            font-size: 13px;
            color: var(--nav-gray);
            margin: 20px 0 30px;
            line-height: 1.6;
            max-width: 340px;
        }
        .hero-actions { display: flex; gap: 12px; flex-wrap: wrap; }

        .hero-image {
            flex: 0 0 55%;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1;
        }
        .hero-image img {
            max-width: 100%;
            max-height: 400px;
            object-fit: contain;
            filter: drop-shadow(0 30px 60px rgba(0,0,0,.7));
        }
        .hero-image .no-bike {
            width: 100%;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px dashed rgba(255,255,255,.1);
            border-radius: 16px;
            color: var(--nav-gray);
            font-size: 13px;
        }

        /* ── SPECS STRIP ── */
        .specs-strip {
            display: flex;
            gap: 0;
            margin: 0 40px;
            border-top: 1px solid rgba(255,255,255,.06);
            border-bottom: 1px solid rgba(255,255,255,.06);
        }
        .spec-item {
            flex: 1;
            padding: 20px 24px;
            border-right: 1px solid rgba(255,255,255,.06);
        }
        .spec-item:last-child { border-right: none; }
        .spec-label {
            font-size: 10px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--nav-gray);
            margin-bottom: 6px;
        }
        .spec-value {
            font-size: 26px;
            font-weight: 700;
            color: var(--text-white);
        }
        .spec-value span { font-size: 11px; color: var(--nav-gray); margin-left: 4px; }

        /* ── FEATURES ── */
        .features {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
            padding: 50px 40px;
        }
        .feature-box {
            background: var(--bg-card);
            padding: 24px 20px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,.06);
            transition: border-color .2s;
        }
        .feature-box:hover { border-color: rgba(55,254,61,.3); }
        .feature-box.active {
            background: var(--accent-green);
            color: #000;
        }
        .feature-box.active .feat-label,
        .feature-box.active .feat-desc { color: rgba(0,0,0,.65); }
        .feat-label {
            font-size: 10px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--nav-gray);
            margin-bottom: 10px;
        }
        .feat-title {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .feat-desc {
            font-size: 12px;
            color: var(--nav-gray);
            line-height: 1.6;
        }

        /* ── LATEST MODELS ── */
        .section { padding: 50px 40px; }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 22px;
            font-weight: 700;
        }
        .see-all {
            font-size: 12px;
            color: var(--accent-green);
            text-decoration: none;
            letter-spacing: 1px;
        }
        .see-all:hover { text-decoration: underline; }

        .models-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }
        .model-card {
            background: var(--bg-card);
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,.06);
            text-decoration: none;
            color: var(--text-white);
            transition: border-color .2s, transform .2s;
            display: block;
        }
        .model-card:hover {
            border-color: rgba(55,254,61,.35);
            transform: translateY(-3px);
        }
        .model-card-img {
            width: 100%;
            height: 160px;
            object-fit: contain;
            background: #0e1215;
            padding: 16px;
        }
        .model-card-body { padding: 16px; }
        .model-card-brand {
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--accent-green);
            margin-bottom: 4px;
        }
        .model-card-name {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .model-card-hp {
            font-size: 12px;
            color: var(--nav-gray);
        }

        /* ── BRANDS ── */
        .brands-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            padding: 0 40px 50px;
        }
        .brand-pill {
            padding: 10px 22px;
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 30px;
            font-size: 13px;
            color: var(--nav-gray);
            text-decoration: none;
            transition: border-color .2s, color .2s;
        }
        .brand-pill:hover {
            border-color: var(--accent-green);
            color: var(--accent-green);
        }

        /* ── FOOTER ── */
        footer {
            background: var(--bg-card);
            border-top: 1px solid rgba(255,255,255,.06);
            padding: 50px 40px;
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1fr;
            gap: 40px;
        }
        .footer-brand .logo { margin-bottom: 12px; }
        .footer-tagline { font-size: 13px; color: var(--nav-gray); line-height: 1.6; }
        .footer-col h4 {
            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--accent-green);
            margin-bottom: 16px;
        }
        .footer-col ul { list-style: none; }
        .footer-col li { margin-bottom: 10px; }
        .footer-col a {
            color: var(--nav-gray);
            text-decoration: none;
            font-size: 13px;
            transition: color .2s;
        }
        .footer-col a:hover { color: var(--text-white); }
        .footer-bottom {
            padding: 20px 40px;
            border-top: 1px solid rgba(255,255,255,.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            color: var(--nav-gray);
        }

        @media (max-width: 900px) {
            header { padding: 14px 20px; }
            nav { display: none; }
            .hero { flex-direction: column; padding: 40px 20px; }
            .hero-text, .hero-image { flex: unset; width: 100%; }
            .hero-image { margin-top: 30px; }
            .features { grid-template-columns: 1fr 1fr; padding: 30px 20px; }
            .models-grid { grid-template-columns: 1fr 1fr; }
            .specs-strip { margin: 0 20px; flex-wrap: wrap; }
            .section { padding: 30px 20px; }
            .brands-row { padding: 0 20px 30px; }
            footer { grid-template-columns: 1fr 1fr; padding: 30px 20px; }
            .footer-bottom { padding: 16px 20px; flex-direction: column; gap: 8px; }
        }
    </style>
</head>
<body>

{{-- ── HEADER ── --}}
<header>
    <a href="{{ route('home') }}" class="logo">
        <span class="logo-dot"></span>
        {{ config('group.name') }}
    </a>

    <nav>
        <ul>
            <li><a href="{{ route('home') }}" class="active">Home</a></li>
            <li><a href="{{ route('motorcycles.index') }}">Motorcycles</a></li>
            <li><a href="{{ route('brands.index') }}">Brands</a></li>
            <li><a href="{{ route('services') }}">Services</a></li>
            <li><a href="{{ route('contact') }}">Contact</a></li>
        </ul>
    </nav>

    <div class="header-right">
        <a href="{{ route('cart.index') }}" class="btn-outline" style="padding:9px 14px;">🛒</a>
        @auth
            <a href="{{ route('account.dashboard') }}" class="btn-white">My Account</a>
            @php $roles = auth()->user()->roles->pluck('name'); @endphp
            @if($roles->contains('super_admin') || $roles->contains('admin') || $roles->contains('brand_manager'))
                <a href="{{ route('admin.dashboard') }}" class="btn-green">Admin Panel</a>
            @endif
        @else
            <a href="{{ route('register') }}" class="btn-white">Register</a>
            <a href="{{ route('login') }}" class="btn-green">Login</a>
        @endauth
    </div>
</header>

{{-- ── HERO ── --}}
<section class="hero">
    <div class="hero-text">
        @if($featuredMotorcycle)
            <p class="brand-name">{{ $featuredMotorcycle->brand->name }}</p>
            <h1>
                {{ $featuredMotorcycle->model_name }}
                @if($featuredMotorcycle->engine_capacity)
                    <span>{{ number_format($featuredMotorcycle->engine_capacity) }} CC</span>
                @endif
            </h1>
        @else
            <p class="brand-name">{{ config('group.name') }}</p>
            <h1>Premium<span>Motorcycles</span></h1>
        @endif
        <p class="tagline">{{ config('group.tagline', 'Fuelled By Your Conviction') }}</p>
        <div class="hero-actions">
            @if($featuredMotorcycle)
                <a href="{{ route('motorcycles.show', $featuredMotorcycle->slug) }}" class="btn-green">Explore Model</a>
            @endif
            <a href="{{ route('motorcycles.index') }}" class="btn-outline">All Motorcycles</a>
        </div>
    </div>

    <div class="hero-image">
        @if($featuredMotorcycle?->image_url)
            <img src="{{ $featuredMotorcycle->image_url }}"
                 alt="{{ $featuredMotorcycle->brand->name }} {{ $featuredMotorcycle->model_name }}">
        @else
            <div class="no-bike">No featured motorcycle yet</div>
        @endif
    </div>
</section>

{{-- ── SPECS STRIP ── --}}
@if($featuredMotorcycle)
<div class="specs-strip">
    @if($featuredMotorcycle->horsepower)
    <div class="spec-item">
        <p class="spec-label">Power</p>
        <p class="spec-value">{{ $featuredMotorcycle->horsepower }}<span>HP</span></p>
    </div>
    @endif
    @if($featuredMotorcycle->torque)
    <div class="spec-item">
        <p class="spec-label">Torque</p>
        <p class="spec-value">{{ $featuredMotorcycle->torque }}<span>NM</span></p>
    </div>
    @endif
    @if($featuredMotorcycle->engine_capacity)
    <div class="spec-item">
        <p class="spec-label">Engine</p>
        <p class="spec-value">{{ $featuredMotorcycle->engine_capacity }}<span>CC</span></p>
    </div>
    @endif
    @if($featuredMotorcycle->transmission)
    <div class="spec-item">
        <p class="spec-label">Transmission</p>
        <p class="spec-value" style="font-size:18px">{{ $featuredMotorcycle->transmission }}</p>
    </div>
    @endif
    <div class="spec-item">
        <p class="spec-label">Category</p>
        <p class="spec-value" style="font-size:18px">{{ $featuredMotorcycle->category ?? '—' }}</p>
    </div>
</div>
@endif

{{-- ── FEATURES ── --}}
<div class="features">
    <div class="feature-box active">
        <p class="feat-label">Featured</p>
        <h3 class="feat-title">{{ $featuredMotorcycle?->model_name ?? config('group.name') }}</h3>
        <p class="feat-desc">
            @if($featuredMotorcycle)
                <a href="{{ route('motorcycles.show', $featuredMotorcycle->slug) }}"
                   style="color:#000;font-weight:700;font-size:12px;">View Details →</a>
            @else
                Top-tier performance machines.
            @endif
        </p>
    </div>
    <div class="feature-box">
        <p class="feat-label">Performance</p>
        <h3 class="feat-title">{{ $featuredMotorcycle?->horsepower ? $featuredMotorcycle->horsepower.' HP' : 'High Power' }}</h3>
        <p class="feat-desc">Liquid cooled engine built for power, torque and fuel efficiency.</p>
    </div>
    <div class="feature-box">
        <p class="feat-label">Design</p>
        <h3 class="feat-title">Aerodynamic</h3>
        <p class="feat-desc">Crafted for road dominance with bold styling cues.</p>
    </div>
    <div class="feature-box">
        <p class="feat-label">Technology</p>
        <h3 class="feat-title">TFT Display</h3>
        <p class="feat-desc">Loaded with touring accessories, USB, Turbo, Perimeter frame.</p>
    </div>
    <div class="feature-box">
        <p class="feat-label">Safety</p>
        <h3 class="feat-title">Dual ABS</h3>
        <p class="feat-desc">Dual channel ABS, Full LED headlamp for all conditions.</p>
    </div>
</div>

{{-- ── LATEST MODELS ── --}}
@if($latestMotorcycles->count())
<div class="section">
    <div class="section-header">
        <h2 class="section-title">Latest Models</h2>
        <a href="{{ route('motorcycles.index') }}" class="see-all">See All →</a>
    </div>
    <div class="models-grid">
        @foreach($latestMotorcycles as $m)
        <a href="{{ route('motorcycles.show', $m->slug) }}" class="model-card">
            <img src="{{ $m->image_url }}" alt="{{ $m->model_name }}" class="model-card-img">
            <div class="model-card-body">
                <p class="model-card-brand">{{ $m->brand->name }}</p>
                <p class="model-card-name">{{ $m->model_name }}</p>
                <p class="model-card-hp">
                    @if($m->horsepower) {{ $m->horsepower }} HP @endif
                    @if($m->engine_capacity) &bull; {{ $m->engine_capacity }} CC @endif
                </p>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif

{{-- ── BRANDS ── --}}
@if($brands->count())
<div class="section" style="padding-top:0">
    <div class="section-header">
        <h2 class="section-title">Our Brands</h2>
        <a href="{{ route('brands.index') }}" class="see-all">See All →</a>
    </div>
    <div class="brands-row" style="padding:0">
        @foreach($brands as $brand)
        <a href="{{ route('brands.show', $brand->slug) }}" class="brand-pill">{{ $brand->name }}</a>
        @endforeach
    </div>
</div>
@endif

{{-- ── FOOTER ── --}}
<footer>
    <div class="footer-brand">
        <a href="{{ route('home') }}" class="logo" style="color:#fff;">
            <span class="logo-dot"></span>
            {{ config('group.name') }}
        </a>
        <p class="footer-tagline" style="margin-top:12px">{{ config('group.tagline', 'Fuelled By Your Conviction') }}</p>
    </div>
    <div class="footer-col">
        <h4>Explore</h4>
        <ul>
            <li><a href="{{ route('motorcycles.index') }}">All Motorcycles</a></li>
            <li><a href="{{ route('brands.index') }}">Brands</a></li>
            <li><a href="{{ route('services') }}">Services</a></li>
            <li><a href="{{ route('contact') }}">Contact</a></li>
        </ul>
    </div>
    <div class="footer-col">
        <h4>Account</h4>
        <ul>
            <li><a href="{{ route('login') }}">Login</a></li>
            <li><a href="{{ route('register') }}">Register</a></li>
            <li><a href="{{ route('account.dashboard') }}">My Account</a></li>
            <li><a href="{{ route('account.enquiries') }}">My Enquiries</a></li>
        </ul>
    </div>
    <div class="footer-col">
        <h4>Quick Links</h4>
        <ul>
            <li><a href="{{ route('cart.index') }}">Cart</a></li>
            <li><a href="{{ route('contact') }}">Book Test Ride</a></li>
            <li><a href="{{ route('admin.dashboard') }}">Admin Panel</a></li>
        </ul>
    </div>
</footer>
<div class="footer-bottom">
    <span>© {{ date('Y') }} {{ config('group.name') }}. All rights reserved.</span>
    <span>Built with Laravel</span>
</div>

</body>
</html>
