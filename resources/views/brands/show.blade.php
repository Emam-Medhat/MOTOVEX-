<!DOCTYPE html>
<html lang="{{ app()->getLocale() === 'ar' ? 'ar' : 'en' }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $brand->name }} | MOTOVEX — Premium Motorcycle Group</title>
  <meta name="description" content="{{ $brand->description ? Str::limit($brand->description, 155) : $brand->name . ' motorcycles at MOTOVEX' }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Noto+Kufi+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['Plus Jakarta Sans', 'Noto Kufi Arabic', 'sans-serif'] },
          colors: {
            brand: {
              50:'#f5f3ff', 100:'#ede9fe', 200:'#ddd6fe',
              300:'#c4b5fd', 400:'#a78bfa', 500:'#8b5cf6',
              600:'#7c3aed', 700:'#6d28d9', 800:'#5b21b6', 900:'#4c1d95'
            }
          }
        }
      }
    }
  </script>
  <style>
    :root {
      --mvx-bg:           #ffffff;
      --mvx-lav:          #f7f6fd;
      --mvx-lav-2:        #f0edfa;
      --mvx-surface:      #ffffff;
      --mvx-ink:          #172033;
      --mvx-ink-2:        #3f4a5c;
      --mvx-muted:        #64748b;
      --mvx-line:         #e8e7f2;
      --mvx-line-2:       #dcd9ee;
      --mvx-primary:      #7c3aed;
      --mvx-primary-d:    #6d28d9;
      --mvx-primary-soft: #f3e8ff;
      --mvx-radius:       18px;
      --mvx-shadow-sm:    0 1px 2px rgba(23,32,51,.04);
      --mvx-shadow-lg:    0 22px 44px -22px rgba(124,58,237,.28);
    }
    * { box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body {
      font-family: 'Plus Jakarta Sans','Noto Kufi Arabic',sans-serif;
      background: var(--mvx-bg);
      color: var(--mvx-ink);
      overflow-x: hidden;
      -webkit-font-smoothing: antialiased;
    }
    ::-webkit-scrollbar { width: 7px; }
    ::-webkit-scrollbar-track { background: var(--mvx-lav); }
    ::-webkit-scrollbar-thumb { background: #cdc6ea; border-radius: 20px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--mvx-primary); }

    .mvx-wrap {
      width: 100%; max-width: 80rem;
      margin-inline: auto; padding-inline: 1.15rem;
    }
    @media (min-width:640px)  { .mvx-wrap { padding-inline: 1.5rem; } }
    @media (min-width:1024px) { .mvx-wrap { padding-inline: 2rem; } }

    /* ── Reveal animation ── */
    .reveal {
      opacity: 0; transform: translateY(30px);
      transition: all .75s cubic-bezier(.16,1,.3,1);
    }
    .reveal.active { opacity: 1; transform: translateY(0); }
    @media (prefers-reduced-motion: reduce) {
      .reveal { transition: none !important; transform: none !important; opacity: 1 !important; }
    }

    /* ── Breadcrumbs ── */
    .mvx-crumbs {
      display: flex; align-items: center; gap: .45rem;
      font-size: .78rem; color: var(--mvx-muted); margin-bottom: 1.1rem;
    }
    .mvx-crumbs a { color: var(--mvx-muted); text-decoration: none; transition: color .2s; }
    .mvx-crumbs a:hover { color: var(--mvx-primary-d); }
    .mvx-crumbs .sep { opacity: .5; font-size: .6rem; }
    .mvx-crumbs [aria-current] { color: var(--mvx-ink); font-weight: 700; }

    /* ── Badge ── */
    .mvx-badge {
      display: inline-flex; align-items: center; gap: .45rem;
      border-radius: 999px; border: 1px solid #e2d8fb;
      background: var(--mvx-primary-soft); color: var(--mvx-primary-d);
      padding: .4rem .85rem; font-size: .72rem; font-weight: 700; letter-spacing: .01em;
    }

    /* ── Hero section ── */
    .mvx-hero {
      position: relative;
      border-bottom: 1px solid var(--mvx-line);
      background:
        radial-gradient(110% 120% at 100% 0%, rgba(124,58,237,.07), transparent 58%),
        linear-gradient(180deg,#fbfaff 0%,#ffffff 100%);
      padding-block: 2.5rem 2.5rem;
    }
    @media (min-width:768px) { .mvx-hero { padding-block: 3.5rem; } }

    .mvx-hero-grid {
      display: grid; gap: 2.5rem; align-items: center;
    }
    @media (min-width:900px) {
      .mvx-hero-grid { grid-template-columns: 1fr 1fr; gap: 3.5rem; }
    }

    .mvx-hero-title {
      font-size: clamp(2rem, 4.5vw, 3.4rem);
      font-weight: 900; line-height: 1.1; letter-spacing: -.025em;
      color: var(--mvx-ink); margin-top: .8rem;
    }

    .mvx-hero-sub {
      margin-top: .85rem; max-width: 32rem;
      font-size: .97rem; line-height: 1.8; color: var(--mvx-muted);
    }

    /* Stat pills */
    .mvx-stat-row {
      display: flex; flex-wrap: wrap; gap: .65rem; margin-top: 1.4rem;
    }
    .mvx-stat {
      display: flex; align-items: center; gap: .55rem;
      padding: .55rem 1rem; border-radius: 12px;
      border: 1px solid var(--mvx-line); background: var(--mvx-lav);
      font-size: .82rem; font-weight: 700; color: var(--mvx-ink-2);
    }
    .mvx-stat i { color: var(--mvx-primary); font-size: .78rem; }

    /* Action buttons */
    .mvx-btn-primary {
      display: inline-flex; align-items: center; gap: .55rem;
      padding: .85rem 1.75rem; border-radius: 13px;
      background: var(--mvx-primary); color: #fff; font-size: .9rem; font-weight: 700;
      text-decoration: none;
      box-shadow: 0 12px 28px -14px rgba(124,58,237,.8);
      transition: background .25s, transform .25s, box-shadow .25s;
    }
    .mvx-btn-primary:hover { background: var(--mvx-primary-d); transform: translateY(-2px); box-shadow: 0 18px 36px -14px rgba(109,40,217,.9); }

    .mvx-btn-outline {
      display: inline-flex; align-items: center; gap: .55rem;
      padding: .83rem 1.6rem; border-radius: 13px;
      border: 1px solid var(--mvx-line-2); background: var(--mvx-surface);
      color: var(--mvx-ink-2); font-size: .9rem; font-weight: 700; text-decoration: none;
      transition: border-color .2s, color .2s, background .2s;
    }
    .mvx-btn-outline:hover { border-color: var(--mvx-primary); color: var(--mvx-primary-d); background: var(--mvx-lav); }

    /* Hero image */
    .mvx-hero-img-wrap {
      position: relative;
      border-radius: 22px; overflow: hidden;
      border: 1px solid var(--mvx-line);
      background: var(--mvx-lav);
      aspect-ratio: 16/10;
      box-shadow: 0 28px 56px -22px rgba(124,58,237,.15);
    }
    .mvx-hero-img-wrap img { width:100%; height:100%; object-fit:cover; display:block; }
    .mvx-hero-fallback {
      width:100%; height:100%; display:flex; align-items:center; justify-content:center;
      flex-direction:column; gap:.6rem;
      background: radial-gradient(120% 100% at 50% 20%, var(--mvx-lav) 0%, var(--mvx-lav-2) 100%);
    }
    .mvx-hero-initial {
      font-size: 5rem; font-weight: 900; color: var(--mvx-primary-d);
      letter-spacing: -.04em; line-height: 1;
    }
    .mvx-hero-name {
      font-size: .9rem; font-weight: 800; letter-spacing: .3em;
      text-transform: uppercase; color: var(--mvx-ink-2);
    }
    /* Logo pill floating over hero image */
    .mvx-logo-pill {
      position: absolute; bottom: -1.25rem; right: 1.5rem;
      display: flex; align-items: center; justify-content: center;
      width: 5.5rem; height: 5.5rem; border-radius: 16px;
      background: #fff; border: 1px solid var(--mvx-line);
      box-shadow: 0 12px 28px -12px rgba(23,32,51,.18);
      padding: .9rem; overflow: hidden;
    }
    .mvx-logo-pill img { max-height: 100%; max-width: 100%; object-fit: contain; }

    /* ── Models section ── */
    .mvx-models-sec {
      padding-block: 3rem 4rem;
      background: var(--mvx-bg);
    }
    .mvx-models-head {
      display: flex; align-items: flex-end; justify-content: space-between; gap: 1rem;
      margin-bottom: 2rem;
    }
    .mvx-models-title {
      font-size: clamp(1.45rem, 2.8vw, 2rem);
      font-weight: 800; letter-spacing: -.015em; color: var(--mvx-ink);
    }
    .mvx-models-title em { font-style: normal; color: var(--mvx-primary-d); }
    .mvx-models-sub {
      margin-top: .3rem; font-size: .9rem; color: var(--mvx-muted);
    }
    .mvx-view-all {
      display: inline-flex; align-items: center; gap: .45rem;
      font-size: .85rem; font-weight: 700; color: var(--mvx-primary);
      text-decoration: none; white-space: nowrap;
      transition: color .2s, gap .2s;
    }
    .mvx-view-all:hover { color: var(--mvx-primary-d); gap: .65rem; }

    /* ── Moto grid ── */
    .mvx-moto-grid {
      display: grid; gap: 1.35rem;
    }
    @media (min-width:560px)  { .mvx-moto-grid { grid-template-columns: repeat(2, minmax(0,1fr)); } }
    @media (min-width:900px)  { .mvx-moto-grid { grid-template-columns: repeat(3, minmax(0,1fr)); } }
    @media (min-width:1280px) { .mvx-moto-grid { grid-template-columns: repeat(4, minmax(0,1fr)); gap: 1.6rem; } }

    /* ── Moto card ── */
    .mvx-moto-card {
      position: relative; display: flex; flex-direction: column;
      background: var(--mvx-surface); border: 1px solid var(--mvx-line);
      border-radius: var(--mvx-radius); overflow: hidden;
      box-shadow: var(--mvx-shadow-sm);
      transition: transform .35s cubic-bezier(.16,1,.3,1), box-shadow .35s, border-color .35s;
      text-decoration: none; color: inherit;
      animation: mvxRise .55s cubic-bezier(.16,1,.3,1) backwards;
    }
    @keyframes mvxRise { from { opacity:0; transform:translateY(16px); } }
    @media (prefers-reduced-motion:reduce) { .mvx-moto-card { animation: none; } }
    .mvx-moto-card:hover {
      transform: translateY(-6px);
      border-color: #ded7f5;
      box-shadow: var(--mvx-shadow-lg);
    }

    .mvx-moto-img {
      position: relative; aspect-ratio: 16/10;
      overflow: hidden; background: var(--mvx-lav);
    }
    .mvx-moto-img img {
      width: 100%; height: 100%; object-fit: cover; display: block;
      transition: transform .6s cubic-bezier(.16,1,.3,1);
    }
    .mvx-moto-card:hover .mvx-moto-img img { transform: scale(1.06); }

    .mvx-moto-badge-year {
      position: absolute; top: .75rem; right: .75rem;
      padding: .28rem .65rem; border-radius: 999px;
      background: rgba(255,255,255,.92); backdrop-filter: blur(8px);
      font-size: .72rem; font-weight: 700; color: var(--mvx-ink);
      border: 1px solid rgba(255,255,255,.6);
    }
    .mvx-moto-badge-cat {
      position: absolute; top: .75rem; left: .75rem;
      padding: .28rem .65rem; border-radius: 999px;
      background: rgba(124,58,237,.88); backdrop-filter: blur(8px);
      font-size: .7rem; font-weight: 700; color: #fff;
    }

    .mvx-moto-body {
      display: flex; flex-direction: column; flex: 1 1 auto;
      padding: 1.1rem 1.2rem 1.2rem;
    }
    .mvx-moto-brand {
      font-size: .7rem; font-weight: 700; text-transform: uppercase;
      letter-spacing: .18em; color: var(--mvx-primary); margin-bottom: .25rem;
    }
    .mvx-moto-name {
      font-size: 1rem; font-weight: 800; color: var(--mvx-ink);
      line-height: 1.3; margin-bottom: .55rem;
    }

    .mvx-moto-specs {
      display: flex; flex-wrap: wrap; gap: .4rem; margin-bottom: .9rem;
    }
    .mvx-spec-tag {
      display: inline-flex; align-items: center; gap: .3rem;
      padding: .28rem .6rem; border-radius: 8px;
      border: 1px solid var(--mvx-line); background: #f8f9fc;
      font-size: .72rem; font-weight: 600; color: var(--mvx-ink-2);
    }
    .mvx-spec-tag i { font-size: .62rem; color: var(--mvx-primary); }

    .mvx-moto-footer {
      margin-top: auto; padding-top: .9rem;
      border-top: 1px solid var(--mvx-line);
      display: flex; align-items: center; justify-content: space-between;
    }
    .mvx-moto-price {
      font-size: 1.15rem; font-weight: 900; color: var(--mvx-primary-d);
    }
    .mvx-moto-price-none {
      font-size: .82rem; font-weight: 600; color: var(--mvx-muted);
    }
    .mvx-moto-link {
      font-size: .82rem; font-weight: 700; color: var(--mvx-primary);
      display: flex; align-items: center; gap: .35rem;
      transition: gap .2s, color .2s;
    }
    .mvx-moto-card:hover .mvx-moto-link { gap: .55rem; color: var(--mvx-primary-d); }

    /* ── Empty state ── */
    .mvx-empty {
      text-align: center; padding: 3.5rem 1.25rem;
      border: 1px dashed var(--mvx-line-2);
      border-radius: var(--mvx-radius); background: var(--mvx-lav);
    }
    .mvx-empty-ico {
      display: grid; place-items: center;
      width: 3.4rem; height: 3.4rem;
      margin: 0 auto 1rem; border-radius: 14px;
      background: #fff; border: 1px solid var(--mvx-line-2);
      color: var(--mvx-primary-d); font-size: 1.3rem;
    }
    .mvx-empty h3 { font-size: 1.1rem; font-weight: 800; color: var(--mvx-ink); }
    .mvx-empty p { margin-top: .4rem; font-size: .88rem; color: var(--mvx-muted); }
    .mvx-btn-ghost {
      display: inline-flex; align-items: center; gap: .5rem;
      margin-top: 1.35rem; padding: .7rem 1.25rem;
      border-radius: 11px; border: 1px solid var(--mvx-line-2);
      background: #fff; color: var(--mvx-ink);
      font-size: .85rem; font-weight: 700; text-decoration: none;
      transition: border-color .2s, color .2s, box-shadow .2s;
    }
    .mvx-btn-ghost:hover { border-color: var(--mvx-primary); color: var(--mvx-primary-d); box-shadow: 0 8px 20px -14px rgba(124,58,237,.8); }

    .mvx-pager { margin-top: 2.5rem; display: flex; justify-content: center; }

    /* ── CTA band ── */
    .mvx-cta-band { padding-block: 3rem 4.5rem; background: var(--mvx-bg); }
    .mvx-cta-box {
      position: relative; border-radius: 22px;
      border: 1px solid var(--mvx-line);
      background: linear-gradient(160deg,#faf8ff 0%,#f3effb 100%);
      padding: 3rem 1.5rem; text-align: center; overflow: hidden;
    }
    .mvx-cta-box::before {
      content: ''; position: absolute;
      top: -50%; left: 50%; transform: translateX(-50%);
      width: 40rem; height: 18rem;
      background: radial-gradient(ellipse, rgba(124,58,237,.09), transparent 65%);
      pointer-events: none;
    }
    .mvx-cta-box h2 {
      position: relative;
      font-size: clamp(1.35rem,2.5vw,1.9rem);
      font-weight: 800; letter-spacing: -.015em; color: var(--mvx-ink);
    }
    .mvx-cta-box p {
      position: relative;
      margin: .65rem auto 0; max-width: 30rem;
      font-size: .92rem; line-height: 1.8; color: var(--mvx-muted);
    }
    .mvx-cta-btns {
      position: relative;
      display: flex; flex-wrap: wrap; justify-content: center; gap: .9rem;
      margin-top: 1.9rem;
    }
  </style>
@include('partials.rtl-styles')
</head>
<body>

  @include('partials.navbar')

  {{-- ════════════════════════ HERO ════════════════════════ --}}
  <section class="mvx-hero">
    <div class="mvx-wrap">

      {{-- Breadcrumb --}}
      <nav class="mvx-crumbs" aria-label="Breadcrumb">
        <a href="{{ route('home') }}"><i class="fa-solid fa-house" aria-hidden="true"></i> {{ __('brands.breadcrumb_home') }}</a>
        <i class="fa-solid fa-chevron-left sep" aria-hidden="true"></i>
        <a href="{{ route('brands.index') }}">{{ __('brands.breadcrumb_current') }}</a>
        <i class="fa-solid fa-chevron-left sep" aria-hidden="true"></i>
        <span aria-current="page">{{ $brand->name }}</span>
      </nav>

      <div class="mvx-hero-grid">

        {{-- ── Text side ── --}}
        <div class="reveal">
          @if($brand->segment)
            <span class="mvx-badge">
              <i class="fa-solid fa-gem" aria-hidden="true"></i>
              {{ $brand->segment }}
            </span>
          @else
            <span class="mvx-badge">
              <i class="fa-solid fa-gem" aria-hidden="true"></i>
              {{ __('brands.show_hero_badge') }}
            </span>
          @endif

          <h1 class="mvx-hero-title">{{ $brand->name }}</h1>

          @if($brand->tagline)
            <p style="margin-top:.4rem; font-size:.92rem; font-style:italic; font-weight:600; color:var(--mvx-primary-d);">
              {{ $brand->tagline }}
            </p>
          @endif

          @if($brand->description)
            <p class="mvx-hero-sub">{{ $brand->description }}</p>
          @endif

          {{-- Stats row --}}
          <div class="mvx-stat-row">
            <div class="mvx-stat">
              <i class="fa-solid fa-motorcycle" aria-hidden="true"></i>
              <span>{{ $motorcycles->total() }} {{ __('brands.show_models_unit') }}</span>
            </div>
            @if($brand->country)
              <div class="mvx-stat">
                <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                <span>{{ $brand->country }}</span>
              </div>
            @endif
            <div class="mvx-stat">
              <i class="fa-solid fa-crown" aria-hidden="true"></i>
              <span>{{ __('brands.show_featured_brand') }}</span>
            </div>
          </div>

          {{-- CTA buttons --}}
          <div style="display:flex;flex-wrap:wrap;gap:.85rem;margin-top:1.8rem;">
            <a href="{{ route('motorcycles.index') }}?brand[]={{ $brand->id }}" class="mvx-btn-primary">
              <i class="fa-solid fa-motorcycle" aria-hidden="true"></i>
              {{ __('brands.show_explore_all') }}
              <i class="fa-solid fa-arrow-left text-xs" aria-hidden="true"></i>
            </a>
            @if($brand->website_url)
              <a href="{{ $brand->website_url }}" target="_blank" rel="noopener" class="mvx-btn-outline">
                <i class="fa-solid fa-arrow-up-right-from-square text-xs" aria-hidden="true"></i>
                {{ __('brands.show_official_website') }}
              </a>
            @endif
          </div>
        </div>

        {{-- ── Image side ── --}}
        <div class="reveal" style="transition-delay:.1s; padding-bottom:1.5rem;">
          <div class="mvx-hero-img-wrap">
            @php
              $heroImage = $brand->logo_url;
              if (!$heroImage && $motorcycles->isNotEmpty()) {
                $heroImage = $motorcycles->first()->image_url;
              }
            @endphp

            @if($heroImage)
              <img src="{{ $heroImage }}"
                   alt="{{ $brand->name }}"
                   onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
              <div class="mvx-hero-fallback" style="display:none;">
                <div class="mvx-hero-initial">{{ strtoupper(substr($brand->name,0,1)) }}</div>
                <div class="mvx-hero-name">{{ $brand->name }}</div>
              </div>
            @else
              <div class="mvx-hero-fallback">
                <div class="mvx-hero-initial">{{ strtoupper(substr($brand->name,0,1)) }}</div>
                <div class="mvx-hero-name">{{ $brand->name }}</div>
              </div>
            @endif

            <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(23,32,51,.08),transparent);pointer-events:none;"></div>
          </div>

          {{-- Floating logo pill (only when logo != hero image) --}}
          @if($brand->logo_url && $motorcycles->isNotEmpty() && $motorcycles->first()->image_url && $brand->logo_url !== $motorcycles->first()->image_url)
            <div class="mvx-logo-pill hidden md:flex">
              <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }} logo"
                   onerror="this.parentElement.style.display='none';">
            </div>
          @endif
        </div>

      </div>
    </div>
  </section>


  {{-- ════════════════════════ MODELS ════════════════════════ --}}
  <section class="mvx-models-sec">
    <div class="mvx-wrap">

      <div class="mvx-models-head reveal">
        <div>
          <h2 class="mvx-models-title">{{ __('brands.show_models_heading') }} <em>{{ __('brands.show_models_heading_em') }}</em></h2>
          <p class="mvx-models-sub">{{ str_replace(':brand', $brand->name, __('brands.show_models_sub')) }}</p>
        </div>
        <a href="{{ route('motorcycles.index') }}?brand[]={{ $brand->id }}" class="mvx-view-all">
          {{ __('brands.show_view_all_catalogue') }}
          <i class="fa-solid fa-arrow-left text-xs" aria-hidden="true"></i>
        </a>
      </div>

      @if($motorcycles->isEmpty())
        <div class="mvx-empty reveal">
          <div class="mvx-empty-ico"><i class="fa-solid fa-motorcycle" aria-hidden="true"></i></div>
          <h3>{{ __('brands.show_empty_heading') }}</h3>
          <p>{{ str_replace(':brand', $brand->name, __('brands.show_empty_desc')) }}</p>
          <a href="{{ route('brands.index') }}" class="mvx-btn-ghost">
            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
            {{ __('brands.show_back_to_brands') }}
          </a>
        </div>
      @else
        <div class="mvx-moto-grid">
          @foreach($motorcycles as $index => $moto)
            <a href="{{ route('motorcycles.show', $moto->slug) }}"
               class="mvx-moto-card reveal"
               style="animation-delay:{{ min($index,8) * 0.06 }}s; transition-delay:{{ min($index,6) * 0.05 }}s;">

              <div class="mvx-moto-img">
                <img src="{{ $moto->image_url ?: 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=80' }}"
                     alt="{{ $moto->model_name }}"
                     loading="lazy"
                     onerror="this.src='https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=80';">
                @if($moto->year)
                  <span class="mvx-moto-badge-year">{{ $moto->year }}</span>
                @endif
                @if($moto->category)
                  <span class="mvx-moto-badge-cat">{{ ucfirst($moto->category) }}</span>
                @endif
              </div>

              <div class="mvx-moto-body">
                <p class="mvx-moto-brand">{{ $brand->name }}</p>
                <h3 class="mvx-moto-name">{{ $moto->model_name }}</h3>

                <div class="mvx-moto-specs">
                  @if($moto->engine_capacity)
                    <span class="mvx-spec-tag">
                      <i class="fa-solid fa-gauge-high" aria-hidden="true"></i>
                      {{ number_format($moto->engine_capacity) }} cc
                    </span>
                  @endif
                  @if($moto->horsepower)
                    <span class="mvx-spec-tag">
                      <i class="fa-solid fa-bolt" aria-hidden="true"></i>
                      {{ $moto->horsepower }} hp
                    </span>
                  @endif
                  @if($moto->availability_status === 'available')
                    <span class="mvx-spec-tag" style="border-color:#bbf7d0;background:#f0fdf4;color:#15803d;">
                      <i class="fa-solid fa-circle-check" aria-hidden="true" style="color:#16a34a;"></i>
                      {{ __('brands.show_available') }}
                    </span>
                  @endif
                </div>

                <div class="mvx-moto-footer">
                  @if($moto->price)
                    <span class="mvx-moto-price">${{ number_format($moto->price) }}</span>
                  @else
                    <span class="mvx-moto-price-none">{{ __('brands.show_call_for_price') }}</span>
                  @endif
                  <span class="mvx-moto-link">
                    {{ __('brands.show_details') }}
                    <i class="fa-solid fa-arrow-left text-xs" aria-hidden="true"></i>
                  </span>
                </div>
              </div>

            </a>
          @endforeach
        </div>

        @if($motorcycles->hasPages())
          <div class="mvx-pager">{{ $motorcycles->links() }}</div>
        @endif
      @endif

    </div>
  </section>


  {{-- ════════════════════════ CTA ════════════════════════ --}}
  <section class="mvx-cta-band">
    <div class="mvx-wrap">
      <div class="mvx-cta-box reveal">
        <h2>{{ __('brands.show_cta_heading') }}</h2>
        <p>{{ __('brands.show_cta_desc') }}</p>
        <div class="mvx-cta-btns">
          <a href="{{ route('motorcycles.index') }}?brand[]={{ $brand->id }}" class="mvx-btn-primary">
            {{ __('brands.show_cta_browse') }}
            <i class="fa-solid fa-arrow-left text-xs" aria-hidden="true"></i>
          </a>
          <a href="{{ route('brands.index') }}" class="mvx-btn-outline">
            <i class="fa-solid fa-layer-group" aria-hidden="true"></i>
            {{ __('brands.show_cta_all_brands') }}
          </a>
        </div>
      </div>
    </div>
  </section>

  @include('partials.footer')

  <script>
    (function () {
      var reveals = document.querySelectorAll('.reveal');
      if (!reveals.length) return;
      var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('active');
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.08 });
      reveals.forEach(function (el) { observer.observe(el); });
    })();
  </script>

</body>
</html>
