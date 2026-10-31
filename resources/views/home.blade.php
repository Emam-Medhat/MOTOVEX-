@extends('layouts.app')

@section('title', 'MOTOVEX | ' . __('home.hero_title'))

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--purple:#7847ff;--light:#f6f7fb;--muted:#8994a8}
html{scroll-behavior:smooth;scroll-padding-top:85px}
body{background:var(--light);color:#111827;font-family:{{ app()->getLocale()==='ar'?"'Cairo',sans-serif":"'Inter','Cairo',sans-serif" }}}
a{text-decoration:none;color:inherit}
.wrap{width:min(1200px,calc(100% - 36px));margin:auto}

/* ── HERO ── */
.hero{min-height:680px;display:flex;align-items:center;color:#111827;position:relative;isolation:isolate;overflow:hidden;background:#f6f7fb}
.hero:before{content:"";position:absolute;inset:0;z-index:-2;background:linear-gradient(90deg,#f6f7fb 0%,#f0eaffdf 30%,#e8e0ff55 75%),url('https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=2200&q=90') center/cover;animation:zoom 18s ease-in-out infinite alternate}
.hero:after{content:"";position:absolute;inset:0;z-index:-1;background:radial-gradient(circle at 75% 45%,#7847ff22,transparent 32%),linear-gradient(0deg,#f6f7fb,transparent 30%)}
@keyframes zoom{to{transform:scale(1.13)}}
.heroText{max-width:650px;padding:100px 0}
.eyebrow{font-size:11px;font-weight:900;letter-spacing:2px;color:#7847ff}
.hero h1{font-size:clamp(42px,6vw,76px);line-height:1.13;margin:20px 0;font-weight:900;color:#111827}
.gradient{background:linear-gradient(90deg,#7847ff,#6936f5,#5b21b6);background-clip:text;color:transparent}
.hero p{max-width:530px;color:#475569;line-height:2;font-size:14px}
.stats{display:flex;gap:35px;margin-top:50px;flex-wrap:wrap}
.stats b{display:block;font-size:26px;color:#111827}
.stats span{font-size:11px;color:#64748b}

/* ── BUTTONS ── */
.btn-legacy{display:inline-flex;align-items:center;gap:10px;padding:13px 23px;border-radius:50px;background:linear-gradient(110deg,#6936f5,#a06cff);color:#fff;font-weight:900;font-size:12px;box-shadow:0 12px 35px #7847ff55;transition:.3s}
.btn-legacy:hover{transform:translateY(-4px);box-shadow:0 18px 45px #7847ff88}
.btn-legacy.outline{background:transparent;border:1px solid #7847ff70;box-shadow:none;color:#7847ff}

/* ── SECTIONS ── */
.section{padding:82px 0}
.head{display:flex;justify-content:space-between;align-items:end;gap:20px;margin-bottom:30px}
.head h2{font-size:clamp(26px,3vw,38px);font-weight:900;margin:8px 0;color:#111827}
.head p{color:var(--muted);font-size:13px;line-height:1.8;margin:0}
.grid{display:grid;gap:18px}

/* ── BRANDS ── */
.brands{grid-template-columns:repeat(8,1fr)}
.brand{background:#fff;border:1px solid #e7eaf1;border-radius:13px;padding:24px 8px;text-align:center;font-weight:900;color:#263249;transition:.3s}
.brand:hover{transform:translateY(-7px);color:var(--purple);box-shadow:0 15px 35px #7847ff18}

/* ── CATEGORIES ── */
.cats{grid-template-columns:repeat(4,1fr)}
.cat{height:270px;position:relative;overflow:hidden;border-radius:17px;color:#fff;background:#111}
.cat img{width:100%;height:100%;object-fit:cover;transition:.7s}
.cat:hover img{transform:scale(1.12)}
.cat:after{content:"";position:absolute;inset:0;background:linear-gradient(0deg,#111827ed,transparent 75%)}
.cat div{position:absolute;z-index:1;bottom:20px;inset-inline:20px}
.cat h3{margin:0;font-size:20px}
.cat p{font-size:11px;color:#d5dbea}

/* ── PRODUCTS ── */
.products{grid-template-columns:repeat(4,1fr)}
.product{background:#fff;border:1px solid #e8ebf2;border-radius:17px;overflow:hidden;transition:.35s}
.product:hover{transform:translateY(-8px);box-shadow:0 20px 45px #101a3018}
.product img{width:100%;height:210px;object-fit:cover;transition:.5s}
.product:hover img{transform:scale(1.06)}
.productInfo{padding:17px}
.product h3{font-size:15px;margin:10px 0 5px}
.product p{font-size:11px;color:var(--muted)}
.price{color:#7044ed;font-weight:900}

/* ── PROMO ── */
.promo{border-radius:22px;min-height:310px;padding:45px;color:white;display:flex;align-items:center;background:linear-gradient(90deg,#7847ffef,#7847ff99),url('https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=1800&q=85') center/cover}
.promo h2{font-size:clamp(30px,4vw,48px);max-width:650px}

/* ── SERVICES ── */
.services{grid-template-columns:repeat(4,1fr)}
.service{background:#fff;border:1px solid #e8ebf2;border-radius:16px;padding:25px;transition:.3s}
.service:hover{transform:translateY(-8px);border-color:#bca7ff;box-shadow:0 18px 40px #101a3012}
.ico-wrap{width:52px;height:52px;border-radius:15px;background:#f0eaff;color:#7044ed;display:grid;place-items:center;font-size:22px;margin-bottom:17px}
.service h3{font-size:15px;color:#111827}
.service p{font-size:12px;color:#7b8495;line-height:1.9}

/* ── ABOUT ── */
.about{display:grid;grid-template-columns:1fr 1fr;gap:50px;align-items:center}
.about img{width:100%;height:390px;object-fit:cover;border-radius:22px}
.about p{color:#475569;line-height:2.2;font-size:14px}
.metrics{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;background:linear-gradient(110deg,#f0eaff,#e8e0ff);border-radius:20px;padding:32px;margin-top:55px}
.metrics b{font-size:30px;color:#111827}
.metrics span{display:block;color:#64748b;font-size:12px}

/* ── REVIEWS ── */
.reviews{grid-template-columns:repeat(3,1fr)}
.review{background:white;border:1px solid #e8ebf2;border-radius:17px;padding:25px}
.review p{color:#687386;font-size:13px;line-height:2}
.stars{color:#f59e0b}

/* ── FAQ ── */
.faq{max-width:850px;margin:auto}
.faqItem{padding:19px 0;border-bottom:1px solid #e6e9f0}
.faqQ{width:100%;background:none;border:0;text-align:inherit;font:inherit;font-weight:900;display:flex;justify-content:space-between;cursor:pointer;color:#111827}
.faqA{max-height:0;overflow:hidden;transition:max-height .35s;color:#778196;font-size:13px;line-height:2}
.faqItem.open .faqA{max-height:120px;padding-top:12px}

/* ── MARQUEE ── */
.marquee{overflow:hidden;background:#7847ff;color:white;padding:14px 0;white-space:nowrap}
.marquee-track{display:inline-flex;gap:45px;animation:marquee 28s linear infinite;font-weight:900;font-size:13px;letter-spacing:1px}
.marquee:hover .marquee-track{animation-play-state:paused}
@keyframes marquee{to{transform:translateX(-50%)}}

/* ── SPLIT FEATURE ── */
.split-feature{display:grid;grid-template-columns:1fr 1fr;gap:34px;align-items:center}
.feature-photo{position:relative;min-height:430px;border-radius:24px;overflow:hidden}
.feature-photo img{position:absolute;width:100%;height:100%;object-fit:cover;inset:0}
.feature-photo:after{content:"";position:absolute;inset:0;background:linear-gradient(0deg,#111827a8,transparent 55%)}
.floating-note{position:absolute;z-index:2;bottom:22px;inset-inline-start:22px;background:#fff;color:#111827;border-radius:15px;padding:17px 20px;box-shadow:0 15px 45px #0003}
.floating-note b{font-size:23px;display:block}
.feature-copy h2{font-size:clamp(30px,4vw,48px);line-height:1.25;color:#111827}
.feature-copy p{line-height:2.1;color:#475569;font-size:14px}
.checkline{display:flex;gap:12px;align-items:flex-start;margin:17px 0;color:#455166;font-size:13px}
.checkline i{color:#794cff;margin-top:4px}

/* ── TIMELINE ── */
.timeline{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;counter-reset:steps}
.step{padding:25px;border:1px solid #e7eaf1;border-radius:17px;background:#fff;counter-increment:steps;box-shadow:0 4px 12px #0000000a}
.step:before{content:"0" counter(steps);font-size:34px;font-weight:900;color:#7847ff}
.step h3{font-size:16px;color:#111827}
.step p{color:#64748b;font-size:12px;line-height:1.9}

/* ── BENEFITS ── */
.benefit-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.benefit{display:flex;gap:15px;padding:20px;border-radius:15px;background:#fff;border:1px solid #e7eaf1;box-shadow:0 4px 12px #0000000a}
.benefit .ico-wrap{flex:0 0 48px;margin:0}
.benefit p{font-size:12px;line-height:1.8;color:#64748b}
.benefit h3{margin:2px 0;font-size:14px;color:#111827}

/* ── GALLERY ── */
.gallery{display:grid;grid-template-columns:1.3fr 1fr 1fr;grid-template-rows:190px 190px;gap:13px}
.gallery a{position:relative;border-radius:16px;overflow:hidden}
.gallery a:first-child{grid-row:span 2}
.gallery img{width:100%;height:100%;object-fit:cover;transition:.6s}
.gallery a:hover img{transform:scale(1.1)}
.gallery a:after{content:"";position:absolute;inset:40% 0 0;background:linear-gradient(transparent,#111827c9)}
.gallery span{position:absolute;z-index:2;bottom:17px;inset-inline-start:18px;color:white;font-weight:900}

/* ── STORIES ── */
.brand-story{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}
.story-card{background:#fff;border:1px solid #e8ebf2;border-radius:17px;overflow:hidden}
.story-card img{height:180px;width:100%;object-fit:cover}
.story-card div{padding:20px}
.story-card h3{font-size:16px;color:#111827}
.story-card p{font-size:12px;color:#7b8495;line-height:1.9}

/* ── VIDEO COVER ── */
.video-cover{min-height:340px;border-radius:22px;overflow:hidden;position:relative;background:linear-gradient(#ffffff44,#f6f7fba8),url('https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=1800&q=90') center/cover;display:grid;place-items:center;color:#111827;text-align:center}
.play-circle{width:76px;height:76px;border:1px solid #7847ffaa;border-radius:50%;display:grid;place-items:center;background:#7847ffcc;font-size:23px;color:#fff;box-shadow:0 0 0 13px #7847ff20;animation:pulse 2s infinite}
@keyframes pulse{50%{box-shadow:0 0 0 24px #7847ff08}}
.video-cover h2{font-size:clamp(25px,4vw,42px);color:#111827}

/* ── CONTACT ── */
.contact-panel{display:grid;grid-template-columns:1fr 1fr;gap:25px}
.contact-card{background:#fff;border:1px solid #e8ebf2;border-radius:18px;padding:26px}
.contact-card h3{margin:0 0 16px;font-size:17px;color:#111827}
.contact-card input,.contact-card textarea,.contact-card select{width:100%;padding:13px 15px;border:1px solid #e4e8f0;border-radius:10px;margin:7px 0 13px;font:inherit;font-size:13px;color:#111827}
.contact-card textarea{min-height:110px;resize:vertical}
.contact-details{display:grid;gap:14px}
.contact-line{display:flex;align-items:center;gap:13px;padding:15px;background:#f6f4ff;border-radius:13px;font-size:13px;color:#111827}
.contact-line i{color:#7847ff;font-size:18px}

/* ── APP BANNER ── */
.app-banner{border-radius:22px;background:linear-gradient(105deg,#f0eaff,#e8e0ff);color:#111827;padding:35px;display:flex;align-items:center;justify-content:space-between;gap:25px}
.app-banner h2{font-size:clamp(22px,3vw,32px);margin:6px 0;color:#111827}
.app-banner p{color:#64748b;line-height:1.9;font-size:13px}
.app-badges{display:flex;gap:10px;flex-wrap:wrap}
.app-badges a{border:1px solid #7847ff55;padding:12px 17px;border-radius:12px;font-size:12px;font-weight:800;color:#111827;transition:.2s}
.app-badges a:hover{background:#7847ff;color:#fff;border-color:#7847ff}

/* ── SPARE PARTS STRIP ── */
.tag-legacy{display:inline-block;border-radius:30px;background:#eee8ff;color:#7044ed;padding:6px 11px;font-size:10px;font-weight:900}
.category-strip{display:flex;gap:10px;overflow:auto;padding:5px 0 15px}
.category-strip span{white-space:nowrap;border:1px solid #e5e8ef;background:white;border-radius:30px;padding:10px 17px;font-size:12px;font-weight:800;cursor:default;transition:.2s}
.category-strip span:hover{background:#7847ff;color:white}
.season-card{border-radius:18px;background:#f2efff;padding:27px;display:flex;justify-content:space-between;align-items:center;gap:20px}
.season-card h3{font-size:22px;margin:0 0 8px;color:#111827}
.season-card p{font-size:12px;color:#70798a;line-height:1.8}

/* ── NEWSLETTER ── */
.newsletter{background:linear-gradient(110deg,#f0eaff,#e8e0ff);border-radius:22px;padding:40px;color:#111827;display:flex;justify-content:space-between;align-items:center;gap:25px}
.newsletter h2{font-size:clamp(20px,3vw,30px);margin:6px 0;color:#111827}
.newsletter input{background:#fff;border:1px solid #e4e8f0;border-radius:40px;padding:14px 18px;color:#111827;min-width:260px;font:inherit}

/* ── PROGRESS BAR ── */
.progress-bar-top{position:fixed;z-index:100;top:0;inset-inline-start:0;height:3px;background:#9b75ff;width:0}

/* ── REVEAL ── */
.reveal{opacity:0;transform:translateY(35px);transition:opacity .8s,transform .8s cubic-bezier(.2,.7,.2,1)}
.reveal.show{opacity:1;transform:none}
.delay{transition-delay:.15s}

/* ── RESPONSIVE ── */
@media(max-width:950px){
  .brands{grid-template-columns:repeat(4,1fr)}
  .cats,.services{grid-template-columns:repeat(2,1fr)}
  .products{grid-template-columns:repeat(2,1fr)}
  .about,.split-feature,.contact-panel{grid-template-columns:1fr}
  .timeline{grid-template-columns:repeat(2,1fr)}
  .benefit-grid{grid-template-columns:repeat(2,1fr)}
  .brand-story{grid-template-columns:repeat(2,1fr)}
}
@media(max-width:600px){
  .section{padding:58px 0}
  .hero{min-height:620px}.heroText{padding:75px 0}
  .stats{gap:20px}.stats b{font-size:22px}
  .head{align-items:start;flex-direction:column}
  .cats{gap:10px}.cat{height:210px}
  .products{gap:10px}.product img{height:155px}
  .services,.reviews,.articles{grid-template-columns:1fr}
  .promo{padding:25px;min-height:330px}
  .metrics{grid-template-columns:repeat(2,1fr)}
  .gallery{grid-template-columns:1fr 1fr;grid-template-rows:170px 140px 140px}
  .gallery a:first-child{grid-column:span 2;grid-row:auto}
  .timeline,.benefit-grid,.brand-story{grid-template-columns:1fr}
  .app-banner,.season-card{display:block}.app-badges{margin-top:18px}
  .feature-photo{min-height:300px}
  .newsletter{padding:25px;display:block}.newsletter form{margin-top:20px}.newsletter input{width:100%;min-width:0}
}
@media(prefers-reduced-motion:reduce){
  *,*:before,*:after{animation:none!important;transition:none!important;scroll-behavior:auto!important}
  .reveal{opacity:1;transform:none}
}
</style>
@endpush

@section('content')
<div class="progress-bar-top" id="progress"></div>

<main>

{{-- ══ HERO ══ --}}
<section class="hero" id="home">
  <div class="wrap">
    <div class="heroText">
      <span class="eyebrow reveal">{{ __('home.hero_eyebrow_full') }}</span>
      <h1 class="reveal delay">
        {{ __('home.hero_title_a') }}<br>
        <span class="gradient">{{ __('home.hero_title_b') }}</span>
      </h1>
      <p class="reveal delay">{{ __('home.hero_desc') }}</p>
      <div class="reveal delay" style="display:flex;gap:12px;flex-wrap:wrap;margin-top:25px">
        <a class="btn-legacy" href="#motorcycles">{{ __('home.hero_cta_explore') }} ←</a>
        <a class="btn-legacy outline" href="#about">{{ __('home.hero_cta_about') }}</a>
      </div>
      <div class="stats reveal">
        <div><b data-count="10000">0</b><span>{{ __('home.stat_trusted') }}</span></div>
        <div><b data-count="25">0</b><span>{{ __('home.stat_brand_models') }}</span></div>
        <div><b data-count="12">0</b><span>{{ __('home.stat_service_centers') }}</span></div>
      </div>
    </div>
  </div>
</section>

{{-- ══ BRANDS ══ --}}
<section class="section" style="padding:35px 0;background:white">
  <div class="wrap">
    <div class="head">
      <div>
        <span class="eyebrow">{{ __('home.brands_eyebrow') }}</span>
        <h2>{{ __('home.brands_heading') }}</h2>
      </div>
    </div>
    <div class="grid brands">
      @forelse(($brands ?? collect()) as $brand)
        <a class="brand" href="{{ route('brands.show', $brand->slug) }}">{{ $brand->name }}</a>
      @empty
        @foreach(['HONDA','YAMAHA','SUZUKI','Kawasaki','BMW','Benelli','aprilia','KTM'] as $b)
          <div class="brand">{{ $b }}</div>
        @endforeach
      @endforelse
    </div>
  </div>
</section>

{{-- ══ CATEGORIES ══ --}}
<section class="section" id="categories">
  <div class="wrap">
    <div class="head reveal">
      <div>
        <span class="eyebrow">{{ __('home.cats_eyebrow') }}</span>
        <h2>{{ __('home.cats_heading') }}</h2>
        <p>{{ __('home.cats_subheading') }}</p>
      </div>
      <a class="purple" href="{{ route('motorcycles.index') }}">{{ __('home.cats_all_link') }} ←</a>
    </div>
    <div class="grid cats">
      @foreach([
        ['cats_sport',   'cats_sport_desc',   'photo-1558981806-ec527fa84c39'],
        ['cats_touring', 'cats_touring_desc',  'photo-1558981403-c5f9899a28bc'],
        ['cats_adventure','cats_adventure_desc','photo-1568772585407-9361f9bf3a87'],
        ['cats_scooter', 'cats_scooter_desc',  'photo-1558981806-ec527fa84c39'],
      ] as [$titleKey,$descKey,$photo])
      <a class="cat reveal" href="{{ route('motorcycles.index') }}">
        <img loading="lazy" src="https://images.unsplash.com/{{ $photo }}?auto=format&fit=crop&w=700&q=85" alt="{{ __('home.'.$titleKey) }}">
        <div><h3>{{ __('home.'.$titleKey) }}</h3><p>{{ __('home.'.$descKey) }}</p></div>
      </a>
      @endforeach
    </div>
  </div>
</section>

{{-- ══ MOTORCYCLES ══ --}}
<section class="section" style="background:#fff" id="motorcycles">
  <div class="wrap">
    <div class="head reveal">
      <div>
        <span class="eyebrow">{{ __('home.latest_eyebrow') }}</span>
        <h2>{{ __('home.latest_heading') }}</h2>
        <p>{{ __('home.latest_subheading') }}</p>
      </div>
      <a class="btn-legacy" href="{{ route('motorcycles.index') }}">{{ __('home.latest_view_all') }} ←</a>
    </div>
    <div class="grid products">
      @forelse(($motorcycles ?? collect())->take(8) as $m)
      <a class="product reveal" href="{{ route('motorcycles.show', $m->slug) }}">
        <img loading="lazy" src="{{ $m->image_url ?? 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=700&q=80' }}" alt="{{ $m->model_name }}">
        <div class="productInfo">
          <span class="eyebrow">{{ $m->brand->name ?? 'MOTOVEX' }}</span>
          <h3>{{ $m->model_name }}</h3>
          <p>{{ $m->year ?? '' }}</p>
          @if($m->price)
            <span class="price">{{ number_format($m->price) }} {{ $m->currency ?? 'EGP' }}</span>
          @else
            <span class="price">{{ __('home.latest_ask_price') }}</span>
          @endif
        </div>
      </a>
      @empty
      @foreach(['Street Sport','Adventure 650','Urban Scooter','Touring Pro'] as $m)
      <a class="product reveal" href="{{ route('motorcycles.index') }}">
        <img loading="lazy" src="https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=700&q=80" alt="Motorcycle">
        <div class="productInfo">
          <span class="eyebrow">MOTOVEX</span>
          <h3>{{ $m }}</h3>
          <p>{{ __('home.latest_empty') }}</p>
          <span class="price">{{ __('home.latest_learn_more') }} ←</span>
        </div>
      </a>
      @endforeach
      @endforelse
    </div>
  </div>
</section>

{{-- ══ PROMO ══ --}}
<section class="section">
  <div class="wrap">
    <div class="promo reveal">
      <div>
        <span class="eyebrow">{{ __('home.promo_eyebrow') }}</span>
        <h2>{{ __('home.promo_heading') }}</h2>
        <p style="color:#e0d6ff;font-size:13px;margin:10px 0 20px;line-height:1.8">{{ __('home.promo_subheading') }}</p>
        <a class="btn-legacy" href="{{ route('promotions') }}" style="background:#fff;color:#7847ff;box-shadow:none">{{ __('home.promo_cta') }} ←</a>
      </div>
    </div>
  </div>
</section>

{{-- ══ SERVICES ══ --}}
<section class="section" id="services" style="background:#fff">
  <div class="wrap">
    <div class="head reveal">
      <div>
        <span class="eyebrow">{{ __('home.services_eyebrow') }}</span>
        <h2>{{ __('home.services_heading') }}</h2>
        <p>{{ __('home.services_subheading') }}</p>
      </div>
      <a class="purple" href="{{ route('services') }}">{{ __('home.services_view_all') }} ←</a>
    </div>
    <div class="grid services">
      @forelse(($services ?? collect())->take(8) as $s)
      <a class="service reveal" href="{{ route('services') }}">
        <div class="ico-wrap"><i class="fa-solid {{ $s->iconClass ?? 'fa-star' }}"></i></div>
        <h3>{{ $s->title }}</h3>
        <p>{{ $s->description }}</p>
      </a>
      @empty
      @foreach([
        ['fa-screwdriver-wrench','svc_fallback_1_title','svc_fallback_1_desc'],
        ['fa-gears',             'svc_fallback_2_title','svc_fallback_2_desc'],
        ['fa-wallet',            'svc_fallback_3_title','svc_fallback_3_desc'],
        ['fa-road',              'svc_fallback_4_title','svc_fallback_4_desc'],
      ] as $s)
      <div class="service reveal">
        <div class="ico-wrap"><i class="fa-solid {{ $s[0] }}"></i></div>
        <h3>{{ __('home.'.$s[1]) }}</h3>
        <p>{{ __('home.'.$s[2]) }}</p>
      </div>
      @endforeach
      @endforelse
    </div>
  </div>
</section>

{{-- ══ ABOUT ══ --}}
<section class="section" id="about">
  <div class="wrap">
    <div class="about">
      <div class="reveal">
        <span class="eyebrow">{{ __('home.about_eyebrow') }}</span>
        <h2 style="font-size:clamp(32px,4vw,50px);line-height:1.3;color:#111827">
          {{ __('home.about_heading_a') }}<br>
          <span class="gradient">{{ __('home.about_heading_b') }}</span>
        </h2>
        <p>{{ __('home.about_text') }}</p>
        <a class="btn-legacy" href="#contact">{{ __('home.about_cta') }} ←</a>
      </div>
      <img class="reveal delay" loading="lazy" src="https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=1000&q=85" alt="Motorcycle riding">
    </div>
    <div class="metrics reveal">
      <div><b data-count="10000">0</b><span>{{ __('home.stat_trusted') }}</span></div>
      <div><b data-count="25">0</b><span>{{ __('home.stat_brand_models') }}</span></div>
      <div><b data-count="12">0</b><span>{{ __('home.stat_service_centers') }}</span></div>
      <div><b>24/7</b><span>{{ app()->getLocale() === 'ar' ? 'دعم ومساندة' : 'Support' }}</span></div>
    </div>
  </div>
</section>

{{-- ══ REVIEWS ══ --}}
<section class="section" style="background:#fff">
  <div class="wrap">
    <div class="head reveal">
      <div>
        <span class="eyebrow">{{ __('home.testimonials_eyebrow') }}</span>
        <h2>{{ __('home.testimonials_heading') }}</h2>
      </div>
    </div>
    <div class="grid reviews">
      <div class="review reveal"><div class="stars">★★★★★</div><p>{{ __('home.review_1') }}</p><b>{{ __('home.review_customer') }}</b></div>
      <div class="review reveal"><div class="stars">★★★★★</div><p>{{ __('home.review_2') }}</p><b>{{ __('home.review_customer') }}</b></div>
      <div class="review reveal"><div class="stars">★★★★★</div><p>{{ __('home.review_3') }}</p><b>{{ __('home.review_customer') }}</b></div>
    </div>
  </div>
</section>

{{-- ══ FAQ ══ --}}
<section class="section" style="background:#fff">
  <div class="wrap faq">
    <div class="head">
      <div>
        <span class="eyebrow">{{ __('home.faq_eyebrow') }}</span>
        <h2>{{ __('home.faq_heading') }}</h2>
      </div>
    </div>
    @foreach(['faq_q1'=>'faq_a1','faq_q2'=>'faq_a2','faq_q3'=>'faq_a3','faq_q4'=>'faq_a4'] as $qk=>$ak)
    <div class="faqItem">
      <button class="faqQ">{{ __('home.'.$qk) }} <i class="fa fa-plus purple"></i></button>
      <div class="faqA">{{ __('home.'.$ak) }}</div>
    </div>
    @endforeach
  </div>
</section>

{{-- ══ MARQUEE ══ --}}
<section class="marquee">
  <div class="marquee-track">
    <span>{{ __('home.marquee_text') }} </span>
    <span>{{ __('home.marquee_text') }} </span>
  </div>
</section>

{{-- ══ WHY MOTOVEX ══ --}}
<section class="section" id="why-motovex">
  <div class="wrap split-feature">
    <div class="feature-photo reveal">
      <img loading="lazy" src="https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=1100&q=90" alt="Motorcycle detail">
      <div class="floating-note"><b>360°</b><span>{{ __('home.why_floating_note') }}</span></div>
    </div>
    <div class="feature-copy reveal delay">
      <span class="eyebrow">{{ __('home.why_eyebrow') }}</span>
      <h2>{{ __('home.why_heading_a') }} <span class="purple">{{ __('home.why_heading_b') }}</span></h2>
      <p>{{ __('home.why_text') }}</p>
      <div class="checkline"><i class="fa-solid fa-circle-check"></i><span>{{ __('home.why_check_1') }}</span></div>
      <div class="checkline"><i class="fa-solid fa-circle-check"></i><span>{{ __('home.why_check_2') }}</span></div>
      <div class="checkline"><i class="fa-solid fa-circle-check"></i><span>{{ __('home.why_check_3') }}</span></div>
      <a class="btn-legacy" href="#about">{{ __('home.why_cta') }} ←</a>
    </div>
  </div>
</section>

{{-- ══ TIMELINE ══ --}}
<section class="section">
  <div class="wrap">
    <div class="head reveal">
      <div>
        <span class="eyebrow">{{ __('home.timeline_eyebrow') }}</span>
        <h2>{{ __('home.timeline_heading') }}</h2>
        <p>{{ __('home.timeline_subheading') }}</p>
      </div>
    </div>
    <div class="timeline">
      @foreach([['step1_title','step1_desc'],['step2_title','step2_desc'],['step3_title','step3_desc'],['step4_title','step4_desc']] as $s)
      <div class="step reveal">
        <h3>{{ __('home.'.$s[0]) }}</h3>
        <p>{{ __('home.'.$s[1]) }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ══ SMART CHOICE CATEGORIES ══ --}}
<section class="section">
  <div class="wrap">
    <div class="head reveal">
      <div>
        <span class="eyebrow">{{ __('home.smart_eyebrow') }}</span>
        <h2>{{ __('home.smart_heading') }}</h2>
        <p>{{ __('home.smart_subheading') }}</p>
      </div>
    </div>
    <div class="grid cats">
      @foreach([
        ['smart_tag_perf',   'smart_title_sport',   'smart_desc_sport',   'photo-1558981806-ec527fa84c39'],
        ['smart_tag_adv',    'smart_title_adv',     'smart_desc_adv',     'photo-1568772585407-9361f9bf3a87'],
        ['smart_tag_comfort','smart_title_touring', 'smart_desc_touring', 'photo-1558981403-c5f9899a28bc'],
        ['smart_tag_city',   'smart_title_urban',   'smart_desc_urban',   'photo-1558981806-ec527fa84c39'],
      ] as [$tagKey,$titleKey,$descKey,$photo])
      <a class="cat reveal" href="{{ route('motorcycles.index') }}">
        <img loading="lazy" src="https://images.unsplash.com/{{ $photo }}?auto=format&fit=crop&w=700&q=85" alt="{{ __('home.'.$titleKey) }}">
        <div>
          <span class="tag-legacy">{{ __('home.'.$tagKey) }}</span>
          <h3>{{ __('home.'.$titleKey) }}</h3>
          <p>{{ __('home.'.$descKey) }}</p>
        </div>
      </a>
      @endforeach
    </div>
  </div>
</section>

{{-- ══ SPARE PARTS ══ --}}
<section class="section" style="background:#fff">
  <div class="wrap">
    <div class="head reveal">
      <div>
        <span class="eyebrow">{{ __('home.parts_ext_eyebrow') }}</span>
        <h2>{{ __('home.parts_ext_heading') }}</h2>
        <p>{{ __('home.parts_ext_subheading') }}</p>
      </div>
      <a class="btn-legacy" href="{{ route('spare-parts.index') }}">{{ __('home.parts_shop_cta') }} ←</a>
    </div>
    <div class="category-strip reveal">
      @foreach(['parts_cat_oils','parts_cat_filters','parts_cat_brakes','parts_cat_tires','parts_cat_chains','parts_cat_batteries','parts_cat_accessories','parts_cat_protection'] as $cat)
        <span>{{ __('home.'.$cat) }}</span>
      @endforeach
    </div>
    <div class="grid products" style="margin-bottom:25px">
      @forelse(($spareParts ?? collect())->take(4) as $sp)
      <a class="product reveal" href="{{ route('spare-parts.show', $sp->slug) }}">
        <img loading="lazy" src="{{ $sp->image_url ?? 'https://images.unsplash.com/photo-1611821064430-0d40291d0f0d?auto=format&fit=crop&w=700&q=80' }}" alt="{{ $sp->name }}">
        <div class="productInfo">
          <span class="eyebrow">{{ $sp->categoryLabel() }}</span>
          <h3>{{ $sp->name }}</h3>
          <p>{{ $sp->brand->name ?? 'MOTOVEX' }}</p>
          @if($sp->price)
            <span class="price">{{ number_format($sp->price) }} {{ $sp->currency ?? 'EGP' }}</span>
          @else
            <span class="price">{{ __('home.latest_ask_price') }}</span>
          @endif
        </div>
      </a>
      @empty
      @foreach(['Brake Pad Set','Oil Filter Pro','Chain Kit Premium','Sport Battery'] as $sp)
      <a class="product reveal" href="{{ route('spare-parts.index') }}">
        <img loading="lazy" src="https://images.unsplash.com/photo-1611821064430-0d40291d0f0d?auto=format&fit=crop&w=700&q=80" alt="{{ $sp }}">
        <div class="productInfo">
          <span class="eyebrow">MOTOVEX</span>
          <h3>{{ $sp }}</h3>
          <p>{{ __('home.parts_genuine') }}</p>
          <span class="price">{{ __('home.latest_learn_more') }} ←</span>
        </div>
      </a>
      @endforeach
      @endforelse
    </div>
    <div class="season-card reveal">
      <div>
        <h3>{{ __('home.parts_season_title') }}</h3>
        <p>{{ __('home.parts_season_text') }}</p>
      </div>
      <a class="btn-legacy" href="{{ route('spare-parts.index') }}">{{ __('home.parts_browse_cta') }} ←</a>
    </div>
  </div>
</section>

{{-- ══ BENEFITS ══ --}}
<section class="section">
  <div class="wrap">
    <div class="head reveal">
      <div>
        <span class="eyebrow">{{ __('home.commit_eyebrow') }}</span>
        <h2>{{ __('home.commit_heading') }}</h2>
      </div>
    </div>
    <div class="benefit-grid">
      @foreach([
        ['fa-shield-halved','benefit_1_title','benefit_1_desc'],
        ['fa-headset',      'benefit_2_title','benefit_2_desc'],
        ['fa-screwdriver-wrench','benefit_3_title','benefit_3_desc'],
        ['fa-tags',         'benefit_4_title','benefit_4_desc'],
        ['fa-store',        'benefit_5_title','benefit_5_desc'],
        ['fa-motorcycle',   'benefit_6_title','benefit_6_desc'],
      ] as $b)
      <div class="benefit reveal">
        <div class="ico-wrap"><i class="fa-solid {{ $b[0] }}"></i></div>
        <div><h3>{{ __('home.'.$b[1]) }}</h3><p>{{ __('home.'.$b[2]) }}</p></div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ══ GALLERY ══ --}}
<section class="section">
  <div class="wrap">
    <div class="head reveal">
      <div>
        <span class="eyebrow">{{ __('home.gallery_eyebrow') }}</span>
        <h2>{{ __('home.gallery_heading') }}</h2>
        <p>{{ __('home.gallery_subheading') }}</p>
      </div>
    </div>
    <div class="gallery reveal">
      <a href="{{ route('motorcycles.index') }}"><img loading="lazy" src="https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=1000&q=85" alt="Motorcycle"><span>{{ __('home.gallery_perf') }}</span></a>
      <a href="{{ route('motorcycles.index') }}"><img loading="lazy" src="https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=700&q=85" alt="Adventure"><span>{{ __('home.gallery_adv') }}</span></a>
      <a href="{{ route('services') }}"><img loading="lazy" src="https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=700&q=85" alt="Service"><span>{{ __('home.gallery_care') }}</span></a>
      <a href="{{ route('contact') }}"><img loading="lazy" src="https://images.unsplash.com/photo-1485965120184-e220f721d03e?auto=format&fit=crop&w=700&q=85" alt="Bike"><span>{{ __('home.gallery_details') }}</span></a>
      <a href="{{ route('motorcycles.index') }}"><img loading="lazy" src="https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=700&q=85" alt="Ride"><span>{{ __('home.gallery_freedom') }}</span></a>
    </div>
  </div>
</section>

{{-- ══ STORIES/ARTICLES ══ --}}
<section class="section" style="background:#fff">
  <div class="wrap">
    <div class="head reveal">
      <div>
        <span class="eyebrow">{{ __('home.articles_eyebrow') }}</span>
        <h2>{{ __('home.articles_heading') }}</h2>
      </div>
    </div>
    <div class="brand-story">
      <article class="story-card reveal">
        <img loading="lazy" src="https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=85" alt="Article 1">
        <div>
          <span class="tag-legacy">{{ __('home.article_tag_buyer') }}</span>
          <h3>{{ __('home.article_title_1') }}</h3>
          <p>{{ __('home.article_desc_1') }}</p>
          <a class="purple" href="{{ route('contact') }}">{{ __('home.article_read_more') }} ←</a>
        </div>
      </article>
      <article class="story-card reveal">
        <img loading="lazy" src="https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=85" alt="Article 2">
        <div>
          <span class="tag-legacy">{{ __('home.article_tag_maint') }}</span>
          <h3>{{ __('home.article_title_2') }}</h3>
          <p>{{ __('home.article_desc_2') }}</p>
          <a class="purple" href="{{ route('services') }}">{{ __('home.article_read_more') }} ←</a>
        </div>
      </article>
      <article class="story-card reveal">
        <img loading="lazy" src="https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=800&q=85" alt="Article 3">
        <div>
          <span class="tag-legacy">{{ __('home.article_tag_safety') }}</span>
          <h3>{{ __('home.article_title_3') }}</h3>
          <p>{{ __('home.article_desc_3') }}</p>
          <a class="purple" href="{{ route('contact') }}">{{ __('home.article_read_more') }} ←</a>
        </div>
      </article>
    </div>
  </div>
</section>

{{-- ══ VIDEO COVER ══ --}}
<section class="section">
  <div class="wrap">
    <div class="video-cover reveal">
      <div>
        <div class="play-circle"><i class="fa-solid fa-play"></i></div>
        <h2>{{ __('home.video_heading') }}</h2>
        <p>{{ __('home.video_sub') }}</p>
      </div>
    </div>
  </div>
</section>

{{-- ══ CONTACT ══ --}}
<section class="section" id="contact">
  <div class="wrap">
    <div class="head reveal">
      <div>
        <span class="eyebrow">{{ __('home.contact_eyebrow') }}</span>
        <h2>{{ __('home.contact_heading') }}</h2>
        <p>{{ __('home.contact_subheading') }}</p>
      </div>
    </div>
    <div class="contact-panel">
      <div class="contact-card reveal">
        <h3>{{ __('home.contact_form_title') }}</h3>
        <form method="POST" action="{{ route('contact.store') }}">
          @csrf
          <input type="hidden" name="enquiry_type" value="general">
          <input name="name" required placeholder="{{ __('home.contact_name_ph') }}">
          <input type="email" name="email" required placeholder="{{ __('home.contact_email_ph') }}">
          <input name="phone" placeholder="{{ __('home.contact_phone_ph') }}">
          <select name="subject" required>
            <option value="">{{ __('home.contact_type_label') }}</option>
            <option value="{{ __('home.contact_type_buy') }}">{{ __('home.contact_type_buy') }}</option>
            <option value="{{ __('home.contact_type_parts') }}">{{ __('home.contact_type_parts') }}</option>
            <option value="{{ __('home.contact_type_service') }}">{{ __('home.contact_type_service') }}</option>
            <option value="{{ __('home.contact_type_other') }}">{{ __('home.contact_type_other') }}</option>
          </select>
          <textarea name="message" required placeholder="{{ __('home.contact_msg_ph') }}"></textarea>
          <button class="btn-legacy" type="submit">{{ __('home.contact_send_btn') }} ←</button>
        </form>
      </div>
      <div class="contact-card reveal delay">
        <h3>{{ __('home.contact_info_title') }}</h3>
        <p style="color:#7b8495;font-size:13px;line-height:1.9">{{ __('home.contact_info_desc') }}</p>
        <div class="contact-details">
          <div class="contact-line"><i class="fa-solid fa-envelope"></i><span>{{ config('motovex.contact_email','info@motovex.com') }}</span></div>
          <div class="contact-line"><i class="fa-solid fa-phone"></i><span>{{ config('motovex.phone','+20 100 000 0000') }}</span></div>
          <div class="contact-line"><i class="fa-solid fa-location-dot"></i><span>{{ config('motovex.address','Egypt') }}</span></div>
          <div class="contact-line"><i class="fa-regular fa-clock"></i><span>{{ __('home.contact_hours') }}</span></div>
        </div>
        <div style="margin-top:25px">
          <a class="btn-legacy" href="{{ route('contact') }}">{{ __('home.contact_page_cta') }} <i class="fa-solid fa-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ══ APP BANNER ══ --}}
<section class="section">
  <div class="wrap">
    <div class="app-banner reveal">
      <div>
        <span class="eyebrow">{{ __('home.app_eyebrow') }}</span>
        <h2>{{ __('home.app_heading') }}</h2>
        <p>{{ __('home.app_text') }}</p>
      </div>
      <div class="app-badges">
        <a href="{{ route('motorcycles.index') }}"><i class="fa-solid fa-motorcycle"></i> {{ __('home.app_cta_bike') }}</a>
        <a href="{{ route('spare-parts.index') }}"><i class="fa-solid fa-gears"></i> {{ __('home.app_cta_parts') }}</a>
      </div>
    </div>
  </div>
</section>

{{-- ══ NEWSLETTER ══ --}}
<section class="section">
  <div class="wrap">
    <div class="newsletter reveal">
      <div>
        <span class="eyebrow">{{ __('home.news_eyebrow') }}</span>
        <h2>{{ __('home.news_heading') }}</h2>
        <p>{{ __('home.news_text') }}</p>
      </div>
      <form method="POST" action="{{ route('contact.store') }}" style="display:flex;gap:10px;flex-wrap:wrap">
        @csrf
        <input type="hidden" name="enquiry_type" value="general">
        <input type="hidden" name="subject" value="{{ __('home.news_subject') }}">
        <input type="hidden" name="name" value="Newsletter">
        <input type="hidden" name="message" value="{{ __('home.news_subject') }}">
        <input type="email" name="email" required placeholder="{{ __('home.news_placeholder') }}">
        <button class="btn-legacy">{{ __('home.news_btn') }} ←</button>
      </form>
    </div>
  </div>
</section>

</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  // Scroll reveal
  const io = new IntersectionObserver(entries => entries.forEach(e => {
    if (e.isIntersecting) { e.target.classList.add('show'); io.unobserve(e.target); }
  }), { threshold: .12 });
  document.querySelectorAll('.reveal').forEach(el => io.observe(el));

  // Animated counters
  const co = new IntersectionObserver(entries => entries.forEach(e => {
    if (!e.isIntersecting) return;
    const el = e.target, end = +el.dataset.count, start = performance.now();
    const run = t => {
      const p = Math.min((t - start) / 1500, 1);
      el.textContent = Math.floor(end * (1 - Math.pow(1 - p, 4))).toLocaleString() + (p === 1 ? '+' : '');
      if (p < 1) requestAnimationFrame(run);
    };
    requestAnimationFrame(run);
    co.unobserve(el);
  }), { threshold: .6 });
  document.querySelectorAll('[data-count]').forEach(el => co.observe(el));

  // FAQ accordion
  document.querySelectorAll('.faqQ').forEach(btn => {
    btn.addEventListener('click', () => {
      const item = btn.parentElement;
      item.classList.toggle('open');
      btn.querySelector('i').className = item.classList.contains('open')
        ? 'fa fa-minus purple' : 'fa fa-plus purple';
    });
  });

  // Progress bar
  const bar = document.getElementById('progress');
  if (bar) {
    window.addEventListener('scroll', () => {
      const max = document.documentElement.scrollHeight - innerHeight;
      bar.style.width = (max ? scrollY / max * 100 : 0) + '%';
    }, { passive: true });
  }
});
</script>
@endpush
