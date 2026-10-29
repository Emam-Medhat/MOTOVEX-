<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MOTOVEX | Premium Motorcycles</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Noto+Kufi+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['Plus Jakarta Sans','Noto Kufi Arabic','sans-serif'] },
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
    *   { box-sizing:border-box; }
    html{ scroll-behavior:smooth; }
    body{
      font-family:'Plus Jakarta Sans','Noto Kufi Arabic',sans-serif;
      background:#ffffff; color:#0f172a; overflow-x:hidden;
    }

    ::-webkit-scrollbar       { width:6px; }
    ::-webkit-scrollbar-track { background:#f1f5f9; }
    ::-webkit-scrollbar-thumb { background:linear-gradient(180deg,#8b5cf6,#6d28d9); border-radius:20px; }

    /* ── Animations ── */
    @keyframes fadeUp   { from{opacity:0;transform:translateY(40px)} to{opacity:1;transform:translateY(0)} }
    @keyframes scaleIn  { from{opacity:0;transform:scale(.88)}       to{opacity:1;transform:scale(1)}      }
    @keyframes slideR   { from{opacity:0;transform:translateX(-40px)}to{opacity:1;transform:translateX(0)} }
    @keyframes float    { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-14px)} }
    @keyframes shimmer  { 0%{background-position:-200% center} 100%{background-position:200% center} }
    @keyframes spin-slow{ from{transform:rotate(0)} to{transform:rotate(360deg)} }
    @keyframes marquee  { 0%{transform:translateX(0)} 100%{transform:translateX(-50%)} }
    @keyframes countUp  {}

    .fade-up { animation:fadeUp .9s cubic-bezier(.16,1,.3,1) forwards; opacity:0; }
    .scale-in{ animation:scaleIn 1s cubic-bezier(.16,1,.3,1) forwards; opacity:0; }
    .slide-r { animation:slideR .9s cubic-bezier(.16,1,.3,1) forwards; opacity:0; }
    .float   { animation:float 5s ease-in-out infinite; }
    .spin-slow{ animation:spin-slow 22s linear infinite; }

    .reveal{
      opacity:0; transform:translateY(45px);
      transition:all .85s cubic-bezier(.16,1,.3,1);
    }
    .reveal.active{ opacity:1; transform:translateY(0); }

    /* ── Gradient text ── */
    .text-gradient{
      background:linear-gradient(135deg,#6d28d9 0%,#8b5cf6 50%,#a78bfa 100%);
      -webkit-background-clip:text; -webkit-text-fill-color:transparent;
    }
    .text-shimmer{
      background:linear-gradient(90deg,#6d28d9,#8b5cf6,#a78bfa,#8b5cf6,#6d28d9);
      background-size:200% auto;
      -webkit-background-clip:text; -webkit-text-fill-color:transparent;
      animation:shimmer 4s linear infinite;
    }

    /* ── Buttons ── */
    .btn-primary{
      background:linear-gradient(135deg,#8b5cf6,#6d28d9);
      box-shadow:0 8px 28px rgba(139,92,246,.35);
      color:#fff; transition:all .4s cubic-bezier(.16,1,.3,1);
      position:relative; overflow:hidden;
    }
    .btn-primary::after{
      content:''; position:absolute; inset:0;
      background:linear-gradient(90deg,transparent,rgba(255,255,255,.2),transparent);
      transform:translateX(-100%); transition:transform .5s;
    }
    .btn-primary:hover::after{ transform:translateX(100%); }
    .btn-primary:hover{ transform:translateY(-3px); box-shadow:0 14px 40px rgba(139,92,246,.5); }

    .btn-outline{
      border:1.5px solid #ddd6fe; color:#7c3aed;
      background:transparent; transition:all .3s;
    }
    .btn-outline:hover{ background:#f5f3ff; border-color:#8b5cf6; }

    /* ── Glass cards ── */
    .glass{
      background:rgba(255,255,255,.85);
      backdrop-filter:blur(16px);
      border:1px solid rgba(139,92,246,.12);
    }

    /* ── Card hover ── */
    .card-hover{
      transition:all .5s cubic-bezier(.16,1,.3,1);
      border:1px solid #e9eaf5;
    }
    .card-hover:hover{
      transform:translateY(-10px);
      border-color:rgba(139,92,246,.3);
      box-shadow:0 24px 48px -12px rgba(139,92,246,.18);
    }

    /* ── Hero background ── */
    .hero-bg{
      background:
        radial-gradient(ellipse 90% 60% at 50% -10%, rgba(139,92,246,.13), transparent 55%),
        radial-gradient(ellipse 60% 40% at 95% 50%,  rgba(99,102,241,.07), transparent),
        radial-gradient(ellipse 40% 30% at 5%  80%,  rgba(167,139,250,.06), transparent),
        #f8faff;
    }

    /* ── Stats bar ── */
    .stat-item{ border-right:1px solid #e9eaf5; }
    .stat-item:last-child{ border-right:none; }

    /* ── Marquee ── */
    .marquee-wrap{ overflow:hidden; }
    .marquee-track{ display:flex; width:max-content; animation:marquee 30s linear infinite; }

    /* ── Orbit decoration ── */
    .orbit-ring{
      position:absolute; border-radius:50%;
      border:1.5px dashed rgba(139,92,246,.15);
    }

    /* ── Motorcycle card image zoom ── */
    .moto-card{ overflow:hidden; }
    .moto-card img{ transition:transform .6s cubic-bezier(.16,1,.3,1); }
    .moto-card:hover img{ transform:scale(1.08); }

    /* ── Why-us icon ── */
    .why-icon{ transition:transform .4s cubic-bezier(.16,1,.3,1); }
    .why-card:hover .why-icon{ transform:scale(1.15) rotate(-5deg); }

    /* ── Section label ── */
    .section-label{
      display:inline-flex; align-items:center; gap:.5rem;
      background:#f5f3ff; border:1px solid #ddd6fe;
      color:#7c3aed; border-radius:9999px;
      padding:.35rem 1rem; font-size:.72rem; font-weight:700;
      letter-spacing:.08em; text-transform:uppercase;
    }

    /* ── Testimonial card ── */
    .stars{ color:#f59e0b; }

    /* ── Number counter ── */
    .num-counter{ font-variant-numeric:tabular-nums; }
  </style>
</head>
<body>

{{-- ── NAVBAR ── --}}
@include('partials.navbar')


{{-- ════════════════════════════════════════════
     1. HERO
════════════════════════════════════════════ --}}
<section class="hero-bg relative overflow-hidden pt-4 pb-0 min-h-[92vh] flex items-center">

  {{-- Decoration rings --}}
  <div class="orbit-ring w-[700px] h-[700px] absolute -top-40 -left-40 opacity-50"></div>
  <div class="orbit-ring w-[450px] h-[450px] absolute top-1/2 right-0 translate-x-1/3 -translate-y-1/2 opacity-40"></div>
  <div class="absolute top-32 right-1/3 w-3 h-3 rounded-full bg-brand-400 opacity-60 float" style="animation-delay:.5s"></div>
  <div class="absolute bottom-40 left-1/4 w-2 h-2 rounded-full bg-brand-300 opacity-50 float" style="animation-delay:1.2s"></div>
  <div class="absolute top-1/2 left-16 w-2 h-2 rounded-full bg-violet-400 opacity-40 float" style="animation-delay:2s"></div>

  <div class="relative z-10 mx-auto max-w-7xl px-5 sm:px-8 w-full py-16 lg:py-24">
    <div class="grid lg:grid-cols-2 gap-10 xl:gap-16 items-center">

      {{-- TEXT --}}
      <div>
        <div class="section-label fade-up mb-6">
          <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
          متاح الآن · تجربة قيادة مجانية
        </div>

        <h1 class="text-5xl sm:text-6xl xl:text-7xl font-black leading-[1.05] tracking-tight text-slate-900 fade-up" style="animation-delay:.1s">
          عيش السرعة<br>
          <span class="text-shimmer">بإحساس مختلف</span>
        </h1>

        <p class="mt-6 text-lg text-slate-500 max-w-lg leading-relaxed fade-up" style="animation-delay:.22s">
          أفضل الماركات العالمية · فحص معتمد لكل دراجة · توصيل لكل محافظات مصر · ضمان حقيقي
        </p>

        <div class="mt-8 flex flex-wrap gap-4 fade-up" style="animation-delay:.34s">
          <a href="{{ route('motorcycles.index') }}"
             class="btn-primary inline-flex items-center gap-2.5 rounded-2xl px-8 py-4 text-sm font-bold">
            <i class="fa-solid fa-bolt"></i> ادخل المتجر
          </a>
          <a href="{{ route('brands.index') }}"
             class="btn-outline inline-flex items-center gap-2 rounded-2xl px-7 py-4 text-sm font-bold">
            الماركات <i class="fa-solid fa-arrow-left text-xs"></i>
          </a>
        </div>

        {{-- Trust badges --}}
        <div class="mt-10 flex flex-wrap items-center gap-4 fade-up" style="animation-delay:.46s">
          @foreach([['fa-shield-halved','فحص معتمد'],['fa-truck-fast','توصيل سريع'],['fa-headset','دعم 24/7'],['fa-rotate-left','ضمان حقيقي']] as [$ico,$lbl])
          <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-500">
            <i class="fa-solid {{ $ico }} text-brand-500"></i> {{ $lbl }}
          </div>
          @endforeach
        </div>
      </div>

      {{-- IMAGE --}}
      <div class="relative scale-in" style="animation-delay:.2s">
        <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-brand-200/50 border border-brand-100">
          @if($featuredMotorcycle?->image_url)
            <img src="{{ $featuredMotorcycle->image_url }}"
                 class="w-full aspect-[4/3] object-cover"
                 alt="{{ $featuredMotorcycle->model_name }}" />
          @else
            <img src="https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=1000&q=90"
                 class="w-full aspect-[4/3] object-cover" alt="Motorcycle" />
          @endif
          {{-- Bottom overlay badge --}}
          <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-white/95 to-transparent pt-16 pb-5 px-5">
            @if($featuredMotorcycle)
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs font-bold uppercase tracking-widest text-brand-600">{{ $featuredMotorcycle->brand->name }}</p>
                <p class="text-xl font-extrabold text-slate-900">{{ $featuredMotorcycle->model_name }}</p>
                @if($featuredMotorcycle->engine_capacity)
                  <p class="text-sm text-slate-500 mt-0.5">{{ $featuredMotorcycle->engine_capacity }} CC
                    @if($featuredMotorcycle->horsepower) · {{ $featuredMotorcycle->horsepower }} HP @endif
                  </p>
                @endif
              </div>
              <a href="{{ route('motorcycles.show', $featuredMotorcycle->slug) }}"
                 class="btn-primary rounded-xl px-5 py-2.5 text-sm font-bold shrink-0">
                عرض
              </a>
            </div>
            @else
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center text-white shrink-0">
                <i class="fa-solid fa-shield-halved"></i>
              </div>
              <div>
                <p class="font-bold text-sm text-slate-900">100% معتمدة</p>
                <p class="text-xs text-slate-500">فحص + ضمان شامل</p>
              </div>
            </div>
            @endif
          </div>
        </div>

        {{-- Floating cards --}}
        <div class="absolute -top-6 -right-6 glass rounded-2xl px-4 py-3 shadow-lg float">
          <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-lg bg-brand-100 flex items-center justify-center text-brand-600">
              <i class="fa-solid fa-motorcycle"></i>
            </div>
            <div>
              <p class="text-xs font-bold text-slate-900">+150 موديل</p>
              <p class="text-[10px] text-slate-500">في المخزون</p>
            </div>
          </div>
        </div>
        <div class="absolute -bottom-5 -left-5 glass rounded-2xl px-4 py-3 shadow-lg float" style="animation-delay:.8s">
          <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
              <i class="fa-solid fa-star"></i>
            </div>
            <div>
              <p class="text-xs font-bold text-slate-900">98% رضا</p>
              <p class="text-[10px] text-slate-500">12,000+ عميل</p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  {{-- Wave divider --}}
  <div class="absolute bottom-0 inset-x-0">
    <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
      <path d="M0 60 C360 0 1080 0 1440 60 L1440 60 L0 60Z" fill="#ffffff"/>
    </svg>
  </div>
</section>


{{-- ════════════════════════════════════════════
     2. STATS BAR
════════════════════════════════════════════ --}}
<section class="bg-white border-b border-slate-100">
  <div class="mx-auto max-w-7xl px-5 sm:px-8">
    <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-slate-100 rtl:divide-x-reverse">
      @foreach([
        ['150+','دراجة في المخزون',      'fa-motorcycle'],
        ['12K+','عميل سعيد',             'fa-users'],
        ['98%', 'نسبة رضا العملاء',      'fa-heart'],
        ['6',   'ماركات عالمية',         'fa-gem'],
      ] as [$num,$lbl,$ico])
      <div class="py-8 px-6 text-center reveal">
        <div class="flex items-center justify-center gap-2 mb-1">
          <i class="fa-solid {{ $ico }} text-brand-500 text-lg"></i>
          <span class="text-3xl font-black text-slate-900 num-counter">{{ $num }}</span>
        </div>
        <p class="text-sm text-slate-500 font-medium">{{ $lbl }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>


{{-- ════════════════════════════════════════════
     3. MARQUEE BRANDS
════════════════════════════════════════════ --}}
<div class="bg-slate-50 border-y border-slate-100 py-4 marquee-wrap overflow-hidden">
  <div class="marquee-track gap-10 items-center text-sm font-black uppercase tracking-[0.25em] text-slate-400 select-none">
    @php $marqueeItems = $brands->count() ? $brands->pluck('name')->toArray() : ['AURUM','HOUGAN','VELOCE','APEX','NITRO','ZENITH']; @endphp
    @foreach(array_merge($marqueeItems,$marqueeItems,$marqueeItems,$marqueeItems) as $bn)
      <span class="hover:text-brand-600 transition cursor-default shrink-0">{{ $bn }}</span>
      <span class="text-brand-300 shrink-0">✦</span>
    @endforeach
  </div>
</div>


{{-- ════════════════════════════════════════════
     4. FEATURED MOTORCYCLE (full-width spotlight)
════════════════════════════════════════════ --}}
@if($featuredMotorcycle)
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-5 sm:px-8">
    <div class="reveal mb-12 text-center">
      <span class="section-label"><i class="fa-solid fa-fire-flame-curved"></i> موديل مميز</span>
      <h2 class="mt-4 text-4xl sm:text-5xl font-black text-slate-900">
        الاختيار <span class="text-gradient">الأول</span>
      </h2>
    </div>

    <div class="grid lg:grid-cols-2 gap-10 items-center">
      {{-- Big image --}}
      <div class="reveal rounded-3xl overflow-hidden bg-slate-50 border border-slate-100 shadow-xl shadow-slate-100/80">
        @if($featuredMotorcycle->image_url)
          <img src="{{ $featuredMotorcycle->image_url }}"
               class="w-full aspect-[4/3] object-cover hover:scale-105 transition duration-700"
               alt="{{ $featuredMotorcycle->model_name }}" />
        @else
          <div class="w-full aspect-[4/3] flex items-center justify-center text-slate-300">
            <i class="fa-solid fa-motorcycle text-8xl"></i>
          </div>
        @endif
      </div>

      {{-- Details --}}
      <div class="reveal" style="transition-delay:.1s">
        <p class="text-xs font-bold uppercase tracking-[.25em] text-brand-600 mb-2">{{ $featuredMotorcycle->brand->name }}</p>
        <h3 class="text-4xl font-black text-slate-900 mb-4">{{ $featuredMotorcycle->model_name }}</h3>
        @if($featuredMotorcycle->description)
          <p class="text-slate-500 leading-relaxed mb-6">{{ Str::limit($featuredMotorcycle->description, 180) }}</p>
        @endif

        {{-- Specs grid --}}
        <div class="grid grid-cols-2 gap-3 mb-8">
          @foreach([
            ['fa-tachometer-alt', 'المحرك',        $featuredMotorcycle->engine_capacity ? $featuredMotorcycle->engine_capacity.' CC' : null],
            ['fa-bolt',           'القوة',          $featuredMotorcycle->horsepower     ? $featuredMotorcycle->horsepower.' HP'     : null],
            ['fa-gauge-high',     'العزم',          $featuredMotorcycle->torque         ? $featuredMotorcycle->torque.' NM'         : null],
            ['fa-gears',          'ناقل الحركة',    $featuredMotorcycle->transmission   ?: null],
            ['fa-tag',            'الفئة',          $featuredMotorcycle->category       ?: null],
            ['fa-check-circle',   'الحالة',         $featuredMotorcycle->availability_status === 'available' ? 'متاح' : ($featuredMotorcycle->availability_status ?? 'غير محدد')],
          ] as [$ico,$label,$val])
            @if($val)
            <div class="flex items-center gap-3 rounded-xl bg-slate-50 border border-slate-100 px-4 py-3">
              <div class="w-8 h-8 rounded-lg bg-brand-50 flex items-center justify-center text-brand-600 shrink-0">
                <i class="fa-solid {{ $ico }} text-sm"></i>
              </div>
              <div>
                <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wide">{{ $label }}</p>
                <p class="text-sm font-bold text-slate-900">{{ $val }}</p>
              </div>
            </div>
            @endif
          @endforeach
        </div>

        {{-- Price + CTA --}}
        <div class="flex flex-wrap items-center gap-4">
          @if($featuredMotorcycle->price)
          <div>
            <p class="text-xs text-slate-400 font-semibold">السعر</p>
            <p class="text-3xl font-black text-brand-700">${{ number_format($featuredMotorcycle->price) }}</p>
          </div>
          @endif
          <a href="{{ route('motorcycles.show', $featuredMotorcycle->slug) }}"
             class="btn-primary flex-1 sm:flex-none inline-flex items-center justify-center gap-2 rounded-2xl px-8 py-4 text-sm font-bold">
            عرض التفاصيل <i class="fa-solid fa-arrow-left text-xs"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
@endif


{{-- ════════════════════════════════════════════
     5. LATEST MOTORCYCLES GRID
════════════════════════════════════════════ --}}
<section class="py-20 bg-slate-50">
  <div class="mx-auto max-w-7xl px-5 sm:px-8">

    <div class="flex items-end justify-between mb-12 reveal">
      <div>
        <span class="section-label"><i class="fa-solid fa-motorcycle"></i> الموديلات</span>
        <h2 class="mt-4 text-4xl sm:text-5xl font-black text-slate-900">
          أحدث <span class="text-gradient">الموديلات</span>
        </h2>
      </div>
      <a href="{{ route('motorcycles.index') }}"
         class="btn-outline hidden sm:inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-bold">
        عرض الكل <i class="fa-solid fa-arrow-left text-xs"></i>
      </a>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
      @forelse($latestMotorcycles as $index => $m)
      <a href="{{ route('motorcycles.show', $m->slug) }}"
         class="moto-card card-hover bg-white rounded-2xl overflow-hidden block reveal"
         style="transition-delay:{{ $index * 0.07 }}s">
        <div class="relative h-48 overflow-hidden bg-slate-100">
          @if($m->image_url)
            <img src="{{ $m->image_url }}" alt="{{ $m->model_name }}" class="w-full h-full object-cover" />
          @else
            <div class="w-full h-full flex items-center justify-center text-slate-300">
              <i class="fa-solid fa-motorcycle text-5xl"></i>
            </div>
          @endif
          @if($m->is_featured)
            <span class="absolute top-3 right-3 rounded-full bg-brand-600 text-white px-2.5 py-0.5 text-[10px] font-bold">مميز</span>
          @endif
          @if($m->availability_status === 'available')
            <span class="absolute top-3 left-3 rounded-full bg-emerald-500 text-white px-2.5 py-0.5 text-[10px] font-bold">متاح</span>
          @endif
        </div>
        <div class="p-5">
          <p class="text-[10px] font-bold uppercase tracking-[.2em] text-brand-600 mb-1">{{ $m->brand->name }}</p>
          <h3 class="text-base font-bold text-slate-900 mb-1">{{ $m->model_name }}</h3>
          <div class="flex flex-wrap gap-1.5 mb-4">
            @if($m->category)
              <span class="text-[10px] rounded-full bg-slate-100 px-2.5 py-0.5 text-slate-500">{{ $m->category }}</span>
            @endif
            @if($m->engine_capacity)
              <span class="text-[10px] rounded-full bg-slate-100 px-2.5 py-0.5 text-slate-500">{{ $m->engine_capacity }}cc</span>
            @endif
            @if($m->horsepower)
              <span class="text-[10px] rounded-full bg-slate-100 px-2.5 py-0.5 text-slate-500">{{ $m->horsepower }}hp</span>
            @endif
          </div>
          <div class="flex items-center justify-between">
            @if($m->price)
              <span class="text-lg font-black text-brand-700">${{ number_format($m->price) }}</span>
            @else
              <span class="text-sm font-semibold text-slate-400">اتصل للسعر</span>
            @endif
            <span class="text-brand-600 text-xs font-bold flex items-center gap-1 group-hover:gap-2 transition-all">
              التفاصيل <i class="fa-solid fa-arrow-left text-[9px]"></i>
            </span>
          </div>
        </div>
      </a>
      @empty
      {{-- Static fallback --}}
      @foreach([
        ['Sport RS','AURUM','Cruiser','650','photo-1558981806-ec527fa84c39'],
        ['Naked 400','NITRO','Naked','400','photo-1568772585407-9361f9bf3a87'],
        ['Adventure X','APEX','Adventure','800','photo-1558981359-219d6364c9c8'],
        ['Classic GT','VELOCE','Classic','500','photo-1558981403-c5f9899a28bc'],
      ] as $i => [$name,$brand,$cat,$cc,$photo])
      <div class="moto-card card-hover bg-white rounded-2xl overflow-hidden reveal" style="transition-delay:{{ $i * 0.07 }}s">
        <div class="relative h-48 overflow-hidden">
          <img src="https://images.unsplash.com/{{ $photo }}?auto=format&fit=crop&w=600&q=80"
               class="w-full h-full object-cover" alt="{{ $name }}" />
        </div>
        <div class="p-5">
          <p class="text-[10px] font-bold uppercase tracking-[.2em] text-brand-600 mb-1">{{ $brand }}</p>
          <h3 class="text-base font-bold text-slate-900 mb-3">{{ $name }}</h3>
          <div class="flex gap-1.5 mb-4">
            <span class="text-[10px] rounded-full bg-slate-100 px-2.5 py-0.5 text-slate-500">{{ $cat }}</span>
            <span class="text-[10px] rounded-full bg-slate-100 px-2.5 py-0.5 text-slate-500">{{ $cc }}cc</span>
          </div>
          <a href="{{ route('motorcycles.index') }}" class="text-brand-600 text-xs font-bold flex items-center gap-1">
            التفاصيل <i class="fa-solid fa-arrow-left text-[9px]"></i>
          </a>
        </div>
      </div>
      @endforeach
      @endforelse
    </div>

    <div class="mt-10 text-center reveal sm:hidden">
      <a href="{{ route('motorcycles.index') }}"
         class="btn-primary inline-flex items-center gap-2 rounded-2xl px-8 py-3.5 text-sm font-bold">
        عرض كل الموديلات <i class="fa-solid fa-arrow-left text-xs"></i>
      </a>
    </div>

  </div>
</section>


{{-- ════════════════════════════════════════════
     6. BRANDS SHOWCASE
════════════════════════════════════════════ --}}
@if($brands->count())
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-5 sm:px-8">

    <div class="text-center mb-12 reveal">
      <span class="section-label"><i class="fa-solid fa-gem"></i> الماركات</span>
      <h2 class="mt-4 text-4xl sm:text-5xl font-black text-slate-900">
        ماركات <span class="text-gradient">عالمية</span>
      </h2>
      <p class="mt-3 text-slate-500 max-w-md mx-auto">مختارة بعناية لتقديم أفضل تجربة قيادة</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
      @foreach($brands as $i => $brand)
      <a href="{{ route('brands.show', $brand->slug) }}"
         class="card-hover bg-white rounded-2xl p-5 text-center reveal group"
         style="transition-delay:{{ $i * 0.06 }}s">
        @if($brand->logo_url)
          <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}"
               class="h-12 w-auto mx-auto object-contain mb-3 grayscale group-hover:grayscale-0 transition" />
        @else
          <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-brand-100 to-brand-200 mx-auto flex items-center justify-center mb-3">
            <span class="text-xl font-black text-brand-700">{{ strtoupper(substr($brand->name,0,1)) }}</span>
          </div>
        @endif
        <p class="text-xs font-bold text-slate-700 group-hover:text-brand-600 transition">{{ $brand->name }}</p>
        @if(isset($brand->motorcycles_count))
          <p class="text-[10px] text-slate-400 mt-0.5">{{ $brand->motorcycles_count }} موديل</p>
        @endif
      </a>
      @endforeach
    </div>

    <div class="mt-10 text-center reveal">
      <a href="{{ route('brands.index') }}"
         class="btn-outline inline-flex items-center gap-2 rounded-full px-7 py-3 text-sm font-bold">
        عرض كل الماركات <i class="fa-solid fa-arrow-left text-xs"></i>
      </a>
    </div>

  </div>
</section>
@endif


{{-- ════════════════════════════════════════════
     7. WHY US
════════════════════════════════════════════ --}}
<section class="py-20 bg-slate-50">
  <div class="mx-auto max-w-7xl px-5 sm:px-8">

    <div class="text-center mb-14 reveal">
      <span class="section-label"><i class="fa-solid fa-award"></i> لماذا نحن؟</span>
      <h2 class="mt-4 text-4xl sm:text-5xl font-black text-slate-900">
        مش مجرد <span class="text-gradient">وكالة</span>
      </h2>
      <p class="mt-3 text-slate-500 max-w-lg mx-auto">نقدم تجربة شراء كاملة من الفحص حتى التوصيل</p>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
      @foreach([
        ['fa-shield-halved','فحص معتمد','كل دراجة تعدي على فحص شامل من فريق متخصص قبل التسليم','from-brand-500 to-brand-700','brand'],
        ['fa-key','تجربة مجانية','جرب الدراجة قبل ما تشتريها واتأكد إنها المناسبة ليك','from-violet-500 to-violet-700','violet'],
        ['fa-truck-fast','توصيل سريع','توصيل آمن لكل محافظات مصر مع تأمين كامل على الشحنة','from-indigo-500 to-indigo-700','indigo'],
        ['fa-headset','دعم 24/7','فريق دعم فني متخصص جاهز يرد على أي استفسار في أي وقت','from-purple-500 to-purple-700','purple'],
      ] as $i => [$ico,$title,$desc,$grad,$color])
      <div class="why-card card-hover bg-white rounded-2xl p-7 reveal" style="transition-delay:{{ $i * 0.09 }}s">
        <div class="why-icon w-14 h-14 rounded-2xl bg-gradient-to-br {{ $grad }} flex items-center justify-center text-white text-2xl mb-6 shadow-lg shadow-{{ $color }}-200">
          <i class="fa-solid {{ $ico }}"></i>
        </div>
        <h3 class="text-lg font-extrabold text-slate-900 mb-2">{{ $title }}</h3>
        <p class="text-sm text-slate-500 leading-relaxed">{{ $desc }}</p>
      </div>
      @endforeach
    </div>

  </div>
</section>


{{-- ════════════════════════════════════════════
     8. PROCESS STEPS
════════════════════════════════════════════ --}}
<section class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-5 sm:px-8">

    <div class="text-center mb-14 reveal">
      <span class="section-label"><i class="fa-solid fa-list-check"></i> كيف تشتري؟</span>
      <h2 class="mt-4 text-4xl sm:text-5xl font-black text-slate-900">
        خطوات <span class="text-gradient">بسيطة</span>
      </h2>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 relative">
      {{-- Connecting line --}}
      <div class="hidden lg:block absolute top-10 right-[12.5%] left-[12.5%] h-px bg-gradient-to-l from-brand-200 via-brand-400 to-brand-200"></div>

      @foreach([
        ['01','اختر موديلك','تصفح مئات الموديلات وفلتر حسب الفئة والماركة'],
        ['02','احجز تجربة','تواصل معنا واحجز تجربة قيادة مجانية'],
        ['03','افحص واطمن','فريقنا يعمل فحصاً شاملاً قدامك'],
        ['04','استلم وطر','توصيل لبابك مع ضمان كامل'],
      ] as $i => [$step,$title,$desc])
      <div class="text-center reveal" style="transition-delay:{{ $i * 0.1 }}s">
        <div class="relative inline-flex mb-5">
          <div class="w-20 h-20 rounded-full bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center text-white text-2xl font-black shadow-lg shadow-brand-200 mx-auto">
            {{ $step }}
          </div>
          @if($i < 3)
          <div class="absolute top-1/2 -left-full w-full h-px bg-brand-200 hidden lg:block"></div>
          @endif
        </div>
        <h3 class="text-base font-extrabold text-slate-900 mb-2">{{ $title }}</h3>
        <p class="text-sm text-slate-500 leading-relaxed max-w-[180px] mx-auto">{{ $desc }}</p>
      </div>
      @endforeach
    </div>

  </div>
</section>


{{-- ════════════════════════════════════════════
     9. BIG CTA BANNER
════════════════════════════════════════════ --}}
<section class="py-20 bg-slate-50">
  <div class="mx-auto max-w-7xl px-5 sm:px-8">
    <div class="reveal relative overflow-hidden rounded-3xl bg-gradient-to-br from-brand-600 to-brand-800 p-12 sm:p-16 text-center">
      {{-- Decoration --}}
      <div class="absolute inset-0 opacity-10">
        <div class="orbit-ring w-96 h-96 absolute -top-20 -right-20 border-white"></div>
        <div class="orbit-ring w-64 h-64 absolute -bottom-10 -left-10 border-white"></div>
      </div>
      <div class="relative z-10">
        <span class="inline-flex items-center gap-2 rounded-full bg-white/20 px-4 py-1.5 text-xs font-bold text-white mb-6">
          <i class="fa-solid fa-calendar-check"></i> تجربة قيادة مجانية
        </span>
        <h2 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white mb-5">
          جاهز تبدأ رحلتك؟
        </h2>
        <p class="text-brand-200 text-lg max-w-md mx-auto mb-10">
          احجز تجربة قيادة مجانية دلوقتي وخد قرارك وأنت مطمن تماماً
        </p>
        <div class="flex flex-wrap items-center justify-center gap-4">
          <a href="{{ route('contact') }}"
             class="inline-flex items-center gap-2 rounded-2xl bg-white px-8 py-4 text-sm font-bold text-brand-700 shadow-xl hover:bg-brand-50 transition">
            <i class="fa-solid fa-calendar-check"></i> احجز تجربة قيادة
          </a>
          <a href="{{ route('motorcycles.index') }}"
             class="inline-flex items-center gap-2 rounded-2xl bg-white/10 border border-white/30 px-8 py-4 text-sm font-bold text-white hover:bg-white/20 transition">
            تصفح الموديلات <i class="fa-solid fa-arrow-left text-xs"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>


{{-- ════════════════════════════════════════════
     10. CONTACT SECTION
════════════════════════════════════════════ --}}
<section id="contact" class="py-20 bg-white">
  <div class="mx-auto max-w-7xl px-5 sm:px-8">

    <div class="grid lg:grid-cols-2 gap-14 items-start">

      {{-- Info --}}
      <div class="reveal">
        <span class="section-label mb-6"><i class="fa-solid fa-comments"></i> تواصل معنا</span>
        <h2 class="mt-4 text-4xl sm:text-5xl font-black text-slate-900 mb-5">
          كلمنا <span class="text-gradient">دلوقتي</span>
        </h2>
        <p class="text-slate-500 mb-10 leading-relaxed">فريقنا جاهز يرد عليك في أسرع وقت ويساعدك في اختيار الدراجة المناسبة</p>

        <div class="space-y-4">
          @foreach([
            ['fa-phone',        'الهاتف',   '(+012) 3456 7890', 'from-brand-500 to-brand-700'],
            ['fa-envelope',     'الإيميل',  'info@motovex.com',  'from-violet-500 to-violet-700'],
            ['fa-location-dot', 'العنوان',  '123 Motorway St, Cairo, Egypt', 'from-indigo-500 to-indigo-700'],
          ] as [$ico,$label,$val,$grad])
          <div class="flex items-center gap-4 p-4 rounded-2xl border border-slate-100 bg-slate-50 hover:border-brand-200 transition">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $grad }} flex items-center justify-center text-white shrink-0 shadow-md">
              <i class="fa-solid {{ $ico }}"></i>
            </div>
            <div>
              <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">{{ $label }}</p>
              <p class="font-semibold text-slate-800">{{ $val }}</p>
            </div>
          </div>
          @endforeach
        </div>
      </div>

      {{-- CTA card --}}
      <div class="reveal" style="transition-delay:.1s">
        <div class="rounded-3xl border border-brand-100 bg-gradient-to-br from-brand-50 to-white p-8 shadow-xl shadow-brand-100/50 text-center flex flex-col items-center gap-6">
          <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center text-white text-3xl shadow-xl shadow-brand-300/40 float">
            <i class="fa-solid fa-motorcycle"></i>
          </div>
          <div>
            <h3 class="text-2xl font-extrabold text-slate-900 mb-2">احجز تجربتك</h3>
            <p class="text-slate-500 leading-relaxed">وملي النموذج الكامل من صفحة التواصل وهنرد عليك خلال 24 ساعة</p>
          </div>
          <div class="w-full space-y-3">
            <a href="{{ route('contact') }}"
               class="btn-primary w-full inline-flex items-center justify-center gap-2 rounded-2xl px-6 py-4 font-bold text-sm">
              <i class="fa-solid fa-arrow-left text-xs"></i> اذهب لصفحة التواصل
            </a>
            <a href="{{ route('motorcycles.index') }}"
               class="btn-outline w-full inline-flex items-center justify-center gap-2 rounded-2xl px-6 py-4 font-bold text-sm">
              تصفح الموديلات أولاً
            </a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>


{{-- ── FOOTER ── --}}
@include('partials.footer')


<script>
  // Reveal on scroll
  const reveals = document.querySelectorAll('.reveal');
  const io = new IntersectionObserver(
    (entries) => entries.forEach(e => { if(e.isIntersecting) e.target.classList.add('active'); }),
    { threshold: 0.1 }
  );
  reveals.forEach(el => io.observe(el));
</script>

</body>
</html>
