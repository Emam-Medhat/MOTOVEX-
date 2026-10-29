<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MOTOVEX | الماركات</title>
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
    * { box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body {
      font-family: 'Plus Jakarta Sans', 'Noto Kufi Arabic', sans-serif;
      background: #ffffff;
      color: #0f172a;
      overflow-x: hidden;
    }

    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: #f1f5f9; }
    ::-webkit-scrollbar-thumb { background: linear-gradient(180deg, #8b5cf6, #6d28d9); border-radius: 20px; }

    .hero-bg {
      background:
        radial-gradient(ellipse 80% 50% at 50% -20%, rgba(139, 92, 246, 0.12), transparent 55%),
        radial-gradient(ellipse 50% 40% at 90% 60%, rgba(99, 102, 241, 0.05), transparent),
        #f8faff;
    }

    .glass {
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(16px);
      border: 1px solid rgba(139, 92, 246, 0.12);
    }

    .glass-strong {
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(139, 92, 246, 0.15);
    }

    .text-gradient {
      background: linear-gradient(135deg, #6d28d9 0%, #8b5cf6 50%, #a78bfa 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .btn-primary {
      background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
      box-shadow: 0 8px 28px rgba(139, 92, 246, 0.35);
      color: #ffffff;
      transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .btn-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 14px 40px rgba(139, 92, 246, 0.5);
    }

    .brand-card {
      transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
      position: relative;
      overflow: hidden;
      background: #ffffff;
      border: 1px solid #e9eaf5;
    }
    .brand-card::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(139,92,246,0.06), transparent 60%);
      opacity: 0;
      transition: opacity 0.5s ease;
    }
    .brand-card:hover::before {
      opacity: 1;
    }
    .brand-card:hover {
      transform: translateY(-12px) scale(1.02);
      border-color: rgba(139, 92, 246, 0.35);
      box-shadow: 0 25px 50px -12px rgba(139, 92, 246, 0.18);
    }

    .brand-card .logo-area {
      transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .brand-card:hover .logo-area {
      transform: scale(1.08);
    }

    .reveal {
      opacity: 0;
      transform: translateY(40px);
      transition: all 0.85s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .reveal.active {
      opacity: 1;
      transform: translateY(0);
    }

    .fade-up {
      animation: fadeUp 0.9s cubic-bezier(0.16, 1, 0.3, 1) forwards;
      opacity: 0;
    }
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(35px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .filter-btn {
      color: #64748b;
      border: 1px solid #e2e8f0;
    }
    .filter-btn:hover {
      color: #0f172a;
      border-color: #cbd5e1;
    }
    .filter-btn.active {
      background: linear-gradient(135deg, #8b5cf6, #6d28d9);
      color: white;
      border-color: transparent;
    }

    .nav-link { color: #64748b; }
    .nav-link:hover { color: #0f172a; }
    .nav-link.active-nav { color: #7c3aed; font-weight: 700; }

    .header-wrap {
      background: rgba(255,255,255,0.92);
      backdrop-filter: blur(16px);
      border-bottom: 1px solid #e9eaf5;
      box-shadow: 0 1px 12px rgba(139,92,246,0.07);
    }

    .tag-pill {
      background: #f5f3ff;
      color: #6d28d9;
    }

    .sort-select {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      color: #0f172a;
    }
    .sort-select:focus { border-color: #8b5cf6; outline: none; }

    .search-input {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      color: #0f172a;
    }
    .search-input::placeholder { color: #94a3b8; }
    .search-input:focus { border-color: #8b5cf6; outline: none; }

    .cta-section {
      background: linear-gradient(135deg, #faf5ff 0%, #f0ebff 100%);
      border: 1px solid rgba(139, 92, 246, 0.2);
    }
  </style>
</head>
<body>

  @include('partials.navbar')

  <!-- Hero -->
  <section class="hero-bg relative pt-28 pb-16 overflow-hidden">
    <div class="absolute top-20 left-1/4 w-72 h-72 bg-brand-500/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="relative mx-auto max-w-7xl px-5 sm:px-8 text-center">
      <div class="inline-flex items-center gap-2 rounded-full bg-brand-50 border border-brand-200 px-4 py-1.5 text-xs font-semibold text-brand-700 fade-up mb-6">
        <i class="fa-solid fa-gem"></i>
        ماركات عالمية مختارة بعناية
      </div>

      <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 fade-up" style="animation-delay: 0.1s">
        اكتشف <span class="text-gradient">الماركات</span>
      </h1>
      <p class="mt-5 text-slate-500 text-lg max-w-xl mx-auto fade-up" style="animation-delay: 0.2s">
        مجموعة من أفضل الماركات العالمية في عالم الدراجات النارية الفاخرة
      </p>

      <!-- Search -->
      <div class="mt-10 max-w-md mx-auto fade-up" style="animation-delay: 0.3s">
        <div class="relative">
          <input id="brandSearch" type="text" placeholder="ابحث عن ماركة..."
                 class="search-input w-full rounded-2xl py-4 pr-5 pl-12 text-sm transition" />
          <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
        </div>
      </div>
    </div>
  </section>

  <!-- Filters -->
  <section class="sticky top-[70px] z-40 bg-white/95 backdrop-blur-xl border-b border-slate-100 shadow-sm">
    <div class="mx-auto max-w-7xl px-5 sm:px-8 py-4">
      <div class="flex flex-wrap items-center justify-center gap-3" id="filters">
        <button class="filter-btn active rounded-full px-5 py-2 text-sm font-semibold transition" data-filter="all">
          الكل
        </button>
        <button class="filter-btn rounded-full px-5 py-2 text-sm font-semibold transition" data-filter="premium">
          بريميوم
        </button>
        <button class="filter-btn rounded-full px-5 py-2 text-sm font-semibold transition" data-filter="sport">
          رياضي
        </button>
        <button class="filter-btn rounded-full px-5 py-2 text-sm font-semibold transition" data-filter="classic">
          كلاسيك
        </button>
        <button class="filter-btn rounded-full px-5 py-2 text-sm font-semibold transition" data-filter="adventure">
          مغامرات
        </button>
      </div>
    </div>
  </section>

  <!-- Brands Grid -->
  <section class="py-16 bg-white">
    <div class="mx-auto max-w-7xl px-5 sm:px-8">

      <div class="flex items-center justify-between mb-10">
        <p class="text-sm text-slate-500">
          عرض <span id="brandCount" class="text-slate-900 font-semibold">{{ $brands->count() }}</span> ماركات
        </p>
        <select class="sort-select rounded-xl px-4 py-2 text-sm">
          <option>الأحدث</option>
          <option>الأكثر شهرة</option>
          <option>أبجدي</option>
        </select>
      </div>

      <div id="brandsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse($brands as $index => $brand)
        <article
          class="brand-card reveal rounded-3xl p-1 group"
          data-category="{{ strtolower($brand->category ?? 'all') }}"
          data-name="{{ strtolower($brand->name) }}"
          style="transition-delay: {{ ($index % 3) * 0.08 }}s"
        >
          <div class="rounded-[1.35rem] overflow-hidden">

            <!-- Logo Area -->
            <div class="logo-area relative h-48 flex items-center justify-center"
                 style="background: linear-gradient(135deg, {{ $brand->bg_from ?? '#f5f3ff' }}, {{ $brand->bg_to ?? '#ede9fe' }})">

              <div class="absolute inset-0 opacity-30"
                   style="background: radial-gradient(circle at 50% 50%, rgba(139,92,246,0.15), transparent 70%)"></div>

              @if($brand->logo_url)
                <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}"
                     class="relative max-h-24 max-w-[75%] object-contain drop-shadow-md" />
              @else
                <div class="relative text-center">
                  <div class="text-5xl font-black tracking-tight text-brand-700">
                    {{ strtoupper(substr($brand->name, 0, 1)) }}
                  </div>
                  <div class="mt-1 text-xl font-extrabold tracking-[0.3em] text-brand-600">
                    {{ strtoupper($brand->name) }}
                  </div>
                </div>
              @endif

              @if($brand->badge)
                <span class="absolute top-4 right-4 rounded-full bg-brand-600 text-white px-3 py-1 text-[10px] font-bold shadow">
                  {{ $brand->badge }}
                </span>
              @endif
            </div>

            <!-- Card Body -->
            <div class="p-6 bg-white">
              <div class="flex items-start justify-between gap-3">
                <div>
                  <h3 class="text-xl font-bold text-slate-900">{{ $brand->name }}</h3>
                  <p class="mt-1 text-sm text-slate-500">{{ $brand->description ?? 'ماركة متميزة في عالم الدراجات' }}</p>
                </div>
                <span class="tag-pill rounded-lg px-2.5 py-1 text-xs font-semibold whitespace-nowrap">
                  {{ $brand->motorcycles_count ?? $brand->motorcycles()->count() }} موديل
                </span>
              </div>

              @if($brand->tags)
              <div class="mt-5 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                @foreach(explode(',', $brand->tags) as $tag)
                  <span class="rounded-full bg-slate-100 px-2.5 py-1">{{ trim($tag) }}</span>
                @endforeach
              </div>
              @endif

              <a href="{{ route('brands.show', $brand->slug) }}"
                 class="mt-6 flex w-full items-center justify-center gap-2 rounded-xl bg-slate-50 border border-slate-200 py-3 text-sm font-semibold text-slate-700 hover:bg-brand-600 hover:text-white hover:border-transparent transition group-hover:bg-brand-600 group-hover:text-white group-hover:border-transparent">
                استكشف الموديلات
                <i class="fa-solid fa-arrow-left text-xs"></i>
              </a>
            </div>

          </div>
        </article>
        @empty
        <div class="col-span-3 text-center py-20">
          <div class="text-5xl mb-4 opacity-30">🏍️</div>
          <h3 class="text-xl font-bold text-slate-700">لا توجد ماركات حتى الآن</h3>
          <p class="mt-2 text-slate-400">تابعنا قريباً لإضافة ماركات جديدة</p>
        </div>
        @endforelse

      </div>

      <!-- Empty search state -->
      <div id="emptyState" class="hidden text-center py-20">
        <div class="text-5xl mb-4 opacity-30">🔍</div>
        <h3 class="text-xl font-bold text-slate-700">مفيش ماركات مطابقة</h3>
        <p class="mt-2 text-slate-400">جرب تبحث بكلمات مختلفة</p>
      </div>

    </div>
  </section>

  <!-- CTA -->
  <section class="py-20 bg-slate-50">
    <div class="mx-auto max-w-7xl px-5 sm:px-8">
      <div class="reveal cta-section rounded-3xl p-12 text-center relative overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-96 h-32 bg-brand-300/20 blur-[80px] pointer-events-none"></div>
        <div class="relative">
          <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">مش لاقي الماركة اللي بتدور عليها؟</h2>
          <p class="mt-4 text-slate-500 max-w-md mx-auto">كلمنا وقولنا إيه اللي محتاجه، وهنساعدك نلاقيه.</p>
          <a href="{{ route('contact') }}" class="btn-primary mt-8 inline-flex items-center gap-2 rounded-2xl px-8 py-4 text-sm font-bold">
            تواصل معنا
            <i class="fa-solid fa-arrow-left text-xs"></i>
          </a>
        </div>
      </div>
    </div>
  </section>

  @include('partials.footer')

  <script>
    // Reveal on scroll
    const reveals = document.querySelectorAll('.reveal');
    const revealOnScroll = () => {
      reveals.forEach(el => {
        if (el.getBoundingClientRect().top < window.innerHeight - 80) {
          el.classList.add('active');
        }
      });
    };
    window.addEventListener('scroll', revealOnScroll);
    revealOnScroll();

    // Filter
    const filterBtns = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.brand-card');
    const brandCountEl = document.getElementById('brandCount');
    const emptyState = document.getElementById('emptyState');
    const brandsGrid = document.getElementById('brandsGrid');

    function updateCount() {
      const visible = [...cards].filter(c => !c.classList.contains('hidden')).length;
      brandCountEl.textContent = visible;
      emptyState.classList.toggle('hidden', visible > 0);
      brandsGrid.classList.toggle('hidden', visible === 0);
    }

    filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const filter = btn.dataset.filter;
        cards.forEach(card => {
          if (filter === 'all' || card.dataset.category.includes(filter)) {
            card.classList.remove('hidden');
          } else {
            card.classList.add('hidden');
          }
        });
        updateCount();
      });
    });

    // Search
    document.getElementById('brandSearch').addEventListener('input', (e) => {
      const q = e.target.value.trim().toLowerCase();
      cards.forEach(card => {
        const name = card.dataset.name;
        const text = card.textContent.toLowerCase();
        if (q === '' || name.includes(q) || text.includes(q)) {
          card.classList.remove('hidden');
        } else {
          card.classList.add('hidden');
        }
      });
      if (q) {
        filterBtns.forEach(b => b.classList.remove('active'));
      }
      updateCount();
    });
  </script>

</body>
</html>
