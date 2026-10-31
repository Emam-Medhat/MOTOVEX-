<!DOCTYPE html>
<html lang="{{ app()->getLocale() === 'ar' ? 'ar' : 'en' }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ __('brands.meta_title') }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Noto+Kufi+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Plus Jakarta Sans', 'Noto Kufi Arabic', 'sans-serif']
          },
          colors: {
            brand: {
              50: '#f5f3ff',
              100: '#ede9fe',
              200: '#ddd6fe',
              300: '#c4b5fd',
              400: '#a78bfa',
              500: '#8b5cf6',
              600: '#7c3aed',
              700: '#6d28d9',
              800: '#5b21b6',
              900: '#4c1d95'
            }
          }
        }
      }
    }
  </script>
  <style>
    /* ---------------------------------------------------------------
       MOTOVEX — Brands directory. Page-scoped design tokens (.mvx-*)
       Palette: white + very light lavender surfaces, deep charcoal ink,
       restrained MOTOVEX purple accents. No page-level style bleed.
       --------------------------------------------------------------- */
    :root {
      --mvx-bg:            #050b18;
      --mvx-lav:           #08162a;
      --mvx-lav-2:         #0b1c34;
      --mvx-surface:       #08162a;
      --mvx-ink:           #f4f8ff;
      --mvx-ink-2:         #dceaff;
      --mvx-muted:         #91a9c6;
      --mvx-line:          #15385d;
      --mvx-line-2:        #1e4d7a;
      --mvx-primary:       #853cff;
      --mvx-primary-d:     #a66cff;
      --mvx-primary-soft:  rgba(139, 92, 246, 0.15);
      --mvx-radius:        18px;
      --mvx-shadow-sm:     0 4px 12px rgba(0, 0, 0, 0.3);
      --mvx-shadow-lg:     0 22px 44px -22px rgba(139, 92, 246, 0.4);
    }

    * { box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body {
      font-family: 'Plus Jakarta Sans', 'Noto Kufi Arabic', sans-serif;
      background: var(--mvx-bg);
      color: var(--mvx-ink);
      overflow-x: hidden;
      -webkit-font-smoothing: antialiased;
    }
    .is-hidden { display: none !important; }

    ::-webkit-scrollbar { width: 8px; height: 8px; }
    ::-webkit-scrollbar-track { background: var(--mvx-lav); }
    ::-webkit-scrollbar-thumb { background: #cdc6ea; border-radius: 20px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--mvx-primary); }

    .mvx-wrap {
      width: 100%;
      max-width: 80rem;
      margin-inline: auto;
      padding-inline: 1.15rem;
    }
    @media (min-width: 640px)  { .mvx-wrap { padding-inline: 1.5rem; } }
    @media (min-width: 1024px) { .mvx-wrap { padding-inline: 2rem; } }

    /* ---------------- Header ---------------- */
    .mvx-head {
      position: relative;
      border-bottom: 1px solid var(--mvx-line);
      background:
        radial-gradient(110% 120% at 100% 0%, rgba(139, 92, 246, .07), transparent 58%),
        linear-gradient(180deg, #071326 0%, #050b18 100%);
      padding-block: 2.25rem 2rem;
    }
    @media (min-width: 768px) { .mvx-head { padding-block: 3rem 2.5rem; } }

    .mvx-crumbs {
      display: flex;
      align-items: center;
      gap: .45rem;
      font-size: .78rem;
      color: var(--mvx-muted);
      margin-bottom: 1.1rem;
    }
    .mvx-crumbs a { color: var(--mvx-muted); text-decoration: none; transition: color .2s; }
    .mvx-crumbs a:hover { color: var(--mvx-primary-d); }
    .mvx-crumbs .sep { opacity: .5; font-size: .6rem; }
    .mvx-crumbs [aria-current] { color: var(--mvx-ink); font-weight: 700; }

    .mvx-badge {
      display: inline-flex;
      align-items: center;
      gap: .45rem;
      border-radius: 999px;
      border: 1px solid rgba(139, 92, 246, 0.3);
      background: var(--mvx-primary-soft);
      color: var(--mvx-primary-d);
      padding: .4rem .85rem;
      font-size: .72rem;
      font-weight: 700;
      letter-spacing: .01em;
    }

    .mvx-head-grid {
      display: grid;
      gap: 1.5rem;
      align-items: end;
    }
    @media (min-width: 900px) {
      .mvx-head-grid { grid-template-columns: minmax(0, 1fr) minmax(320px, 400px); gap: 2rem; }
    }

    .mvx-title {
      font-size: clamp(1.85rem, 3.4vw, 2.7rem);
      font-weight: 800;
      line-height: 1.15;
      letter-spacing: -.02em;
      color: var(--mvx-ink);
      margin-top: .9rem;
    }
    .mvx-title em { font-style: normal; color: var(--mvx-primary-d); }

    .mvx-sub {
      margin-top: .7rem;
      max-width: 34rem;
      font-size: .98rem;
      line-height: 1.8;
      color: var(--mvx-muted);
    }

    /* ---------------- Search ---------------- */
    .mvx-search { position: relative; }
    .mvx-search input {
      width: 100%;
      padding-block: .95rem;
      padding-inline-start: 3.05rem;
      padding-inline-end: 1.1rem;
      border-radius: 14px;
      border: 1px solid var(--mvx-line-2);
      background: #071326;
      color: var(--mvx-ink);
      font-size: .95rem;
      font-family: inherit;
      box-shadow: var(--mvx-shadow-sm);
      transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .mvx-search input::placeholder { color: #57748f; }
    .mvx-search input:focus {
      outline: none;
      border-color: var(--mvx-primary);
      box-shadow: 0 0 0 4px rgba(139, 92, 246, .14);
    }
    .mvx-search .mvx-search-ico {
      position: absolute;
      inset-inline-start: 1.05rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--mvx-muted);
      font-size: .95rem;
      pointer-events: none;
    }
    .mvx-search-hint {
      margin-top: .55rem;
      font-size: .74rem;
      color: #5f7a95;
      padding-inline-start: .25rem;
    }

    /* ---------------- Sticky toolbar ---------------- */
    .mvx-toolbar {
      position: sticky;
      top: 4rem;
      z-index: 40;
      background: rgba(5, 11, 24, .93);
      backdrop-filter: blur(14px);
      border-bottom: 1px solid var(--mvx-line);
    }
    .mvx-toolbar-inner {
      display: flex;
      align-items: center;
      gap: .85rem;
      padding-block: .85rem;
    }
    .mvx-chips {
      display: flex;
      align-items: center;
      gap: .5rem;
      flex: 1 1 auto;
      min-width: 0;
    }
    @media (max-width: 699px) {
      .mvx-chips {
        flex-wrap: nowrap;
        overflow-x: auto;
        scrollbar-width: none;
        padding-bottom: 2px;
      }
      .mvx-chips::-webkit-scrollbar { display: none; }
    }
    @media (min-width: 700px) { .mvx-chips { flex-wrap: wrap; } }

    .mvx-chip {
      display: inline-flex;
      align-items: center;
      gap: .45rem;
      padding: .5rem .9rem;
      border-radius: 999px;
      border: 1px solid var(--mvx-line-2);
      background: #071326;
      color: var(--mvx-ink-2);
      font-family: inherit;
      font-size: .84rem;
      font-weight: 600;
      white-space: nowrap;
      cursor: pointer;
      transition: background .2s, color .2s, border-color .2s;
    }
    .mvx-chip:hover { background: var(--mvx-lav); color: var(--mvx-ink); }
    .mvx-chip:focus-visible { outline: 2px solid var(--mvx-primary); outline-offset: 2px; }
    .mvx-chip.is-active {
      background: var(--mvx-primary-soft);
      border-color: #8d4cff;
      color: var(--mvx-primary-d);
    }
    .mvx-chip-n {
      display: inline-grid;
      place-items: center;
      min-width: 1.35rem;
      height: 1.35rem;
      padding-inline: .32rem;
      border-radius: 999px;
      background: #0c2039;
      color: var(--mvx-ink-2);
      font-size: .68rem;
      font-weight: 700;
    }
    .mvx-chip.is-active .mvx-chip-n { background: #1c3253; color: var(--mvx-primary-d); }

    .mvx-sort {
      position: relative;
      flex: 0 0 auto;
      display: flex;
      align-items: center;
      gap: .5rem;
    }
    .mvx-sort label { font-size: .78rem; color: var(--mvx-muted); }
    .mvx-select {
      appearance: none;
      -webkit-appearance: none;
      border: 1px solid var(--mvx-line-2);
      background: #071326;
      color: var(--mvx-ink);
      font-family: inherit;
      font-size: .82rem;
      font-weight: 600;
      padding: .55rem 2.1rem .55rem .85rem;
      border-radius: 11px;
      cursor: pointer;
      transition: border-color .2s, box-shadow .2s;
    }
    .mvx-select:focus { outline: none; border-color: var(--mvx-primary); box-shadow: 0 0 0 3px rgba(139, 92, 246, .14); }
    .mvx-sort i {
      position: absolute;
      inset-inline-end: .8rem;
      color: var(--mvx-muted);
      font-size: .65rem;
      pointer-events: none;
    }

    /* ---------------- Results ---------------- */
    .mvx-results { padding-block: 2rem 3rem; background: var(--mvx-bg); }
    .mvx-count {
      display: flex;
      align-items: center;
      gap: .4rem;
      font-size: .84rem;
      color: var(--mvx-muted);
      margin-bottom: 1.4rem;
    }
    .mvx-count b { color: var(--mvx-ink); font-weight: 700; }

    .mvx-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 1.35rem;
    }
    @media (min-width: 560px)  { .mvx-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (min-width: 1280px) { .mvx-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1.6rem; } }

    /* ---------------- Brand card ---------------- */
    .mvx-card {
      position: relative;
      display: flex;
      flex-direction: column;
      background: linear-gradient(155deg, #102642, #071326 72%);
      border: 1px solid var(--mvx-line);
      border-radius: var(--mvx-radius);
      overflow: hidden;
      box-shadow: 0 8px 24px rgba(0,0,0,0.3);
      transition: transform .35s cubic-bezier(.16, 1, .3, 1), box-shadow .35s, border-color .35s;
      animation: mvxRise .55s cubic-bezier(.16, 1, .3, 1) backwards;
    }
    @keyframes mvxRise { from { opacity: 0; transform: translateY(16px); } }
    @media (prefers-reduced-motion: reduce) { .mvx-card { animation: none; } }

    .mvx-card:hover {
      transform: translateY(-6px);
      border-color: #8d4cff;
      box-shadow: 0 0 25px rgba(115, 58, 255, 0.2);
    }
    .mvx-card:focus-within { border-color: #8d4cff; box-shadow: 0 0 25px rgba(115, 58, 255, 0.2); }

    .mvx-logo {
      position: relative;
      height: 13rem;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      border-bottom: 1px solid var(--mvx-line);
      background:
        radial-gradient(85% 85% at 50% 18%, #0d2039 0%, var(--mvx-lav) 58%, var(--mvx-lav-2) 100%);
      overflow: hidden;
    }
    .mvx-logo-img {
      display: block;
      max-height: 7.25rem;
      max-width: 78%;
      object-fit: contain;
      filter: brightness(0) invert(0.9);
      transition: transform .45s cubic-bezier(.16, 1, .3, 1);
    }
    .mvx-card:hover .mvx-logo-img { transform: scale(1.05); }

    /* Text-based brand mark — used when no logo asset exists (or it fails to load). */
    .mvx-mark {
      display: none;
      flex-direction: column;
      align-items: center;
      gap: .7rem;
      text-align: center;
      max-width: 100%;
    }
    .mvx-mono {
      display: grid;
      place-items: center;
      width: 4.25rem;
      height: 4.25rem;
      border-radius: 16px;
      background: #0c2039;
      border: 1px solid var(--mvx-line-2);
      color: var(--mvx-primary-d);
      font-size: 1.75rem;
      font-weight: 800;
      line-height: 1;
      box-shadow: 0 8px 20px -12px rgba(0, 0, 0, .35);
      transition: transform .45s cubic-bezier(.16, 1, .3, 1);
    }
    .mvx-card:hover .mvx-mono { transform: scale(1.05); }
    .mvx-word {
      font-size: 1rem;
      font-weight: 800;
      letter-spacing: .26em;
      color: var(--mvx-ink);
      text-transform: uppercase;
      word-break: break-word;
    }
    .mvx-logo.is-plain .mvx-mark { display: flex; }
    .mvx-logo.is-plain .mvx-logo-img { display: none; }

    .mvx-body {
      display: flex;
      flex-direction: column;
      flex: 1 1 auto;
      padding: 1.3rem 1.35rem 1.4rem;
    }
    .mvx-card-top {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: .75rem;
      margin-bottom: .6rem;
    }
    .mvx-seg {
      display: inline-flex;
      align-items: center;
      gap: .35rem;
      padding: .3rem .6rem;
      border-radius: 8px;
      background: var(--mvx-primary-soft);
      color: var(--mvx-primary-d);
      font-size: .7rem;
      font-weight: 700;
      white-space: nowrap;
      max-width: 100%;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .mvx-models {
      display: inline-flex;
      align-items: center;
      gap: .35rem;
      padding: .3rem .62rem;
      border-radius: 8px;
      border: 1px solid var(--mvx-line);
      background: #0c2039;
      color: var(--mvx-ink-2);
      font-size: .72rem;
      font-weight: 700;
      white-space: nowrap;
      flex: 0 0 auto;
    }
    .mvx-models i { color: var(--mvx-primary); font-size: .68rem; }

    .mvx-name {
      font-size: 1.35rem;
      font-weight: 800;
      letter-spacing: -.01em;
      color: var(--mvx-ink);
      line-height: 1.2;
    }
    .mvx-tagline {
      margin-top: .3rem;
      font-size: .82rem;
      font-weight: 600;
      font-style: italic;
      color: var(--mvx-primary-d);
      opacity: .85;
    }
    .mvx-country {
      display: inline-flex;
      align-items: center;
      gap: .4rem;
      margin-top: .5rem;
      font-size: .8rem;
      color: var(--mvx-muted);
    }
    .mvx-country i { font-size: .72rem; color: #57748f; }

    .mvx-desc {
      margin-top: .75rem;
      font-size: .88rem;
      line-height: 1.75;
      color: var(--mvx-muted);
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .mvx-divider {
      margin-top: 1.15rem;
      padding-top: 1.15rem;
      border-top: 1px solid var(--mvx-line);
    }
    .mvx-cta {
      margin-top: auto;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: .55rem;
      width: 100%;
      padding: .82rem 1.1rem;
      border-radius: 12px;
      background: #1c3253;
      color: #ffffff;
      font-size: .88rem;
      font-weight: 700;
      text-decoration: none;
      transition: background .25s, box-shadow .25s;
    }
    .mvx-card:hover .mvx-cta,
    .mvx-cta:hover { background: var(--mvx-primary-d); box-shadow: 0 12px 26px -14px rgba(166, 108, 255, .75); }
    .mvx-cta i { font-size: .72rem; }

    /* ---------------- Empty states ---------------- */
    .mvx-empty {
      text-align: center;
      padding: 3.5rem 1.25rem;
      border: 1px dashed var(--mvx-line-2);
      border-radius: var(--mvx-radius);
      background: var(--mvx-lav);
    }
    .mvx-empty .mvx-empty-ico {
      display: grid;
      place-items: center;
      width: 3.4rem;
      height: 3.4rem;
      margin: 0 auto 1rem;
      border-radius: 14px;
      background: #0c2039;
      border: 1px solid var(--mvx-line-2);
      color: var(--mvx-primary-d);
      font-size: 1.3rem;
    }
    .mvx-empty h3 { font-size: 1.15rem; font-weight: 800; color: var(--mvx-ink); }
    .mvx-empty p { margin-top: .45rem; font-size: .88rem; color: var(--mvx-muted); }
    .mvx-btn-ghost {
      display: inline-flex;
      align-items: center;
      gap: .5rem;
      margin-top: 1.35rem;
      padding: .7rem 1.25rem;
      border-radius: 11px;
      border: 1px solid var(--mvx-line-2);
      background: #0c2039;
      color: var(--mvx-ink);
      font-size: .85rem;
      font-weight: 700;
      text-decoration: none;
      transition: border-color .2s, color .2s, box-shadow .2s;
    }
    .mvx-btn-ghost:hover { border-color: var(--mvx-primary); color: var(--mvx-primary-d); box-shadow: 0 8px 20px -14px rgba(139, 92, 246, .8); }

    .mvx-pager { margin-top: 2.25rem; display: flex; justify-content: center; }

    /* ---------------- CTA band ---------------- */
    .mvx-cta-band { padding-block: 3rem 4rem; background: var(--mvx-bg); }
    .mvx-cta-box {
      position: relative;
      border-radius: 22px;
      border: 1px solid var(--mvx-line);
      background: linear-gradient(180deg, #0c1f38 0%, #071326 100%);
      padding: 2.6rem 1.4rem;
      text-align: center;
    }
    .mvx-cta-box h2 { font-size: clamp(1.35rem, 2.4vw, 1.85rem); font-weight: 800; letter-spacing: -.01em; color: var(--mvx-ink); }
    .mvx-cta-box p { margin: .7rem auto 0; max-width: 30rem; font-size: .92rem; line-height: 1.8; color: var(--mvx-muted); }
    .mvx-btn-primary {
      display: inline-flex;
      align-items: center;
      gap: .55rem;
      margin-top: 1.7rem;
      padding: .85rem 1.7rem;
      border-radius: 13px;
      background: var(--mvx-primary);
      color: #ffffff;
      font-size: .88rem;
      font-weight: 700;
      text-decoration: none;
      box-shadow: 0 14px 30px -16px rgba(133, 60, 255, .9);
      transition: background .25s, transform .25s, box-shadow .25s;
    }
    .mvx-btn-primary:hover { background: var(--mvx-primary-d); transform: translateY(-2px); box-shadow: 0 18px 36px -16px rgba(166, 108, 255, .95); }
  </style>
@include('partials.rtl-styles')
</head>
<body>

  @include('partials.navbar')

  @php
    /*
     |--------------------------------------------------------------------------
     | Real, database-derived presentation values (no invented data)
     |--------------------------------------------------------------------------
     | - $brands comes from BrandController@index: published brands with a
     |   published-motorcycle count, filtered by ?search= and paginated.
     | - Segment chips are built from the segments actually present.
     | - Logos are resolved only when the underlying asset truly exists on
     |   disk; otherwise we render a text-based brand mark instead of a
     |   broken <img>. (Root cause of the broken AURUM logo: brands.logo_url
     |   stores /images/bikes/aurum-logo.svg but no such file exists.)
     */
    $totalBrands = $brands->total();

    $segments = $brands->pluck('segment')
        ->map(fn ($s) => trim((string) $s))
        ->filter()
        ->countBy()
        ->sortKeys();

    $resolveLogo = function ($brand) {
        $logo = trim((string) $brand->logo_url);
        if ($logo === '') {
            return null;
        }
        // Remote URLs: keep as-is (the <img> carries an onerror safety net).
        if (preg_match('#^https?://#i', $logo)) {
            return $logo;
        }
        // Local path: only render it if the file genuinely exists.
        $relative = ltrim($logo, '/');
        return is_file(public_path($relative)) ? asset($relative) : null;
    };

    $meaningfulDescription = function ($brand) {
        $desc = trim(preg_replace('/\s+/u', ' ', (string) $brand->description));
        if ($desc === '' || mb_strlen($desc) < 24) {
            return null;
        }
        if (mb_strtolower($desc) === mb_strtolower(trim((string) $brand->name))) {
            return null;
        }
        return \Illuminate\Support\Str::limit($desc, 150);
    };
  @endphp

  {{-- ============================= HEADER ============================= --}}
  <section class="mvx-head">
    <div class="mvx-wrap">

      <nav class="mvx-crumbs" aria-label="{{ __('common.breadcrumb') }}">
        <a href="{{ route('home') }}">{{ __('brands.breadcrumb_home') }}</a>
        <i class="fa-solid fa-chevron-left sep" aria-hidden="true"></i>
        <span aria-current="page">{{ __('brands.breadcrumb_current') }}</span>
      </nav>

      <div class="mvx-head-grid">
        <div>
          <span class="mvx-badge">
            <i class="fa-solid fa-gem" aria-hidden="true"></i>
            {{ __('brands.badge_label') }}
          </span>

          <h1 class="mvx-title">{{ __('brands.heading_discover') }} <em>{{ __('brands.heading_brands') }}</em></h1>

          <p class="mvx-sub">
            {{ __('brands.subheading') }}
          </p>
        </div>

        <div>
          <form class="mvx-search" method="GET" action="{{ route('brands.index') }}" role="search">
            <label for="brandSearch" class="sr-only" style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0)">{{ __('brands.search_aria') }}</label>
            <i class="fa-solid fa-magnifying-glass mvx-search-ico" aria-hidden="true"></i>
            <input id="brandSearch" type="search" name="search"
                   value="{{ request('search') }}"
                   placeholder="{{ __('brands.search_placeholder') }}"
                   autocomplete="off" />
          </form>
          <p class="mvx-search-hint">{{ __('brands.search_hint') }}</p>
        </div>
      </div>

    </div>
  </section>

  {{-- ============================ TOOLBAR ============================ --}}
  @if($brands->isNotEmpty())
  <section class="mvx-toolbar">
    <div class="mvx-wrap">
      <div class="mvx-toolbar-inner">

        <div class="mvx-chips" id="brandFilters" role="group" aria-label="{{ __('brands.filter_aria') }}">
          <button type="button" class="mvx-chip is-active" data-segment="all" aria-pressed="true">
            {{ __('brands.all') }}
            <span class="mvx-chip-n">{{ $brands->count() }}</span>
          </button>
          @foreach($segments as $segment => $segmentCount)
            <button type="button" class="mvx-chip" data-segment="{{ mb_strtolower($segment) }}" aria-pressed="false">
              {{ $segment }}
              <span class="mvx-chip-n">{{ $segmentCount }}</span>
            </button>
          @endforeach
        </div>

        <div class="mvx-sort">
          <label for="brandSort">{{ __('brands.sort_label') }}</label>
          <select id="brandSort" class="mvx-select">
            <option value="newest">{{ __('brands.sort_newest') }}</option>
            <option value="models">{{ __('brands.sort_models') }}</option>
            <option value="az">{{ __('brands.sort_az') }}</option>
            <option value="za">{{ __('brands.sort_za') }}</option>
          </select>
          <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
        </div>

      </div>
    </div>
  </section>
  @endif

  {{-- ============================ RESULTS ============================ --}}
  <section class="mvx-results">
    <div class="mvx-wrap">

      @if($brands->isEmpty())
        <div class="mvx-empty">
          <div class="mvx-empty-ico"><i class="fa-solid fa-motorcycle" aria-hidden="true"></i></div>
          <h3>{{ __('brands.empty_heading') }}</h3>
          <p>{{ __('brands.empty_desc') }}</p>
          <a class="mvx-btn-ghost" href="{{ route('motorcycles.index') }}">
            {{ __('brands.empty_browse_bikes') }}
            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
          </a>
        </div>
      @else
        <p class="mvx-count">
          {{ __('brands.showing') }}
          <b id="brandCount">{{ $brands->count() }}</b>
          {{ __('brands.of_brands') }} <b>{{ $totalBrands }}</b>
        </p>

        <div class="mvx-grid" id="brandsGrid">
          @foreach($brands as $index => $brand)
            @php
              $logo = $resolveLogo($brand);
              $desc = $meaningfulDescription($brand);
              $searchIndex = mb_strtolower(trim(implode(' ', array_filter([
                  $brand->name,
                  $brand->segment,
                  $brand->country,
                  $brand->tagline,
                  $brand->description,
              ]))));
            @endphp

            <article class="mvx-card"
                     style="animation-delay: {{ min($index, 6) * 0.06 }}s"
                     data-name="{{ mb_strtolower($brand->name) }}"
                     data-segment="{{ mb_strtolower(trim((string) $brand->segment)) }}"
                     data-models="{{ (int) $brand->motorcycles_count }}"
                     data-created="{{ optional($brand->created_at)->timestamp ?? 0 }}"
                     data-search="{{ $searchIndex }}">

              {{-- Logo well: real asset → image; otherwise (or on failure) a clean text mark --}}
              <div class="mvx-logo {{ $logo ? '' : 'is-plain' }}">
                @if($logo)
                  <img class="mvx-logo-img" src="{{ $logo }}" alt="{{ $brand->name }}" loading="lazy"
                       onerror="this.closest('.mvx-logo').classList.add('is-plain')" />
                @endif
                <div class="mvx-mark" aria-hidden="true">
                  <span class="mvx-mono">{{ mb_strtoupper(mb_substr($brand->name, 0, 1)) }}</span>
                  <span class="mvx-word">{{ mb_strtoupper($brand->name) }}</span>
                </div>
              </div>

              <div class="mvx-body">

                <div class="mvx-card-top">
                  @if(filled($brand->segment))
                    <span class="mvx-seg">{{ $brand->segment }}</span>
                  @else
                    <span></span>
                  @endif

                  <span class="mvx-models">
                    <i class="fa-solid fa-motorcycle" aria-hidden="true"></i>
                    {{ str_replace(':count', (int) $brand->motorcycles_count, __('brands.models_count')) }}
                  </span>
                </div>

                <h3 class="mvx-name">{{ $brand->name }}</h3>

                @if(filled($brand->tagline))
                  <p class="mvx-tagline">{{ $brand->tagline }}</p>
                @endif

                @if(filled($brand->country))
                  <p class="mvx-country">
                    <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                    {{ $brand->country }}
                  </p>
                @endif

                @if($desc)
                  <p class="mvx-desc">{{ $desc }}</p>
                @endif

                <div class="mvx-divider">
                  <a class="mvx-cta" href="{{ route('brands.show', $brand->slug) }}">
                    {{ __('brands.explore_models') }}
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                  </a>
                </div>

              </div>
            </article>
          @endforeach
        </div>

        {{-- Client-side no-match state --}}
        <div class="mvx-empty is-hidden" id="brandsEmpty">
          <div class="mvx-empty-ico"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></div>
          <h3>{{ __('brands.no_match_heading') }}</h3>
          <p>{{ __('brands.no_match_desc') }}</p>
          <a class="mvx-btn-ghost" href="{{ route('brands.index') }}">
            {{ __('brands.reset_filters') }}
            <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
          </a>
        </div>

        @if($brands->hasPages())
          <div class="mvx-pager">{{ $brands->links() }}</div>
        @endif
      @endif

    </div>
  </section>

  {{-- ============================== CTA ============================== --}}
  <section class="mvx-cta-band">
    <div class="mvx-wrap">
      <div class="mvx-cta-box">
        <h2>{{ __('brands.cta_heading') }}</h2>
        <p>{{ __('brands.cta_desc') }}</p>
        <a class="mvx-btn-primary" href="{{ route('contact') }}">
          {{ __('brands.cta_button') }}
          <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
        </a>
      </div>
    </div>
  </section>

  @include('partials.footer')

  <script>
    (function () {
      var grid    = document.getElementById('brandsGrid');
      var empty   = document.getElementById('brandsEmpty');
      var search  = document.getElementById('brandSearch');
      var sort    = document.getElementById('brandSort');
      var countEl = document.getElementById('brandCount');
      if (!grid) { return; }

      var cards = Array.prototype.slice.call(grid.querySelectorAll('.mvx-card'));
      var chips = Array.prototype.slice.call(document.querySelectorAll('.mvx-chip'));
      var activeSegment = 'all';

      function applyFilters() {
        var q = (search && search.value ? search.value : '').trim().toLowerCase();
        var visible = 0;

        cards.forEach(function (card) {
          var segment = card.getAttribute('data-segment') || '';
          var haystack = card.getAttribute('data-search') || '';
          var matchesSegment = activeSegment === 'all' || (segment !== '' && segment.indexOf(activeSegment) !== -1);
          var matchesQuery = q === '' || haystack.indexOf(q) !== -1;
          var show = matchesSegment && matchesQuery;

          card.classList.toggle('is-hidden', !show);
          if (show) { visible++; }
        });

        if (countEl) { countEl.textContent = visible; }
        if (empty) { empty.classList.toggle('is-hidden', visible > 0); }
        grid.classList.toggle('is-hidden', visible === 0);
      }

      function applySort(key) {
        var comparators = {
          newest: function (a, b) { return (+b.getAttribute('data-created')) - (+a.getAttribute('data-created')); },
          models: function (a, b) { return (+b.getAttribute('data-models')) - (+a.getAttribute('data-models')); },
          az:     function (a, b) { return (a.getAttribute('data-name') || '').localeCompare(b.getAttribute('data-name') || '', 'ar'); },
          za:     function (a, b) { return (b.getAttribute('data-name') || '').localeCompare(a.getAttribute('data-name') || '', 'ar'); }
        }[key];

        if (!comparators) { return; }
        cards.sort(comparators).forEach(function (card) { grid.appendChild(card); });
      }

      chips.forEach(function (chip) {
        chip.addEventListener('click', function () {
          activeSegment = chip.getAttribute('data-segment') || 'all';
          chips.forEach(function (other) {
            var isActive = other === chip;
            other.classList.toggle('is-active', isActive);
            other.setAttribute('aria-pressed', isActive ? 'true' : 'false');
          });
          applyFilters();
        });
      });

      if (search) { search.addEventListener('input', applyFilters); }
      if (sort)   { sort.addEventListener('change', function () { applySort(sort.value); }); }

      applyFilters();
    })();
  </script>

</body>
</html>
