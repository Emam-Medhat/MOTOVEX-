<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop | MOTOVEX — Premium Motorcycle Group</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: false,
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans','sans-serif'] },
                    colors: {
                        brand: { 50:'#f5f3ff', 100:'#ede9fe', 200:'#ddd6fe', 400:'#a78bfa', 500:'#8b5cf6', 600:'#7c3aed', 700:'#6d28d9', 800:'#5b21b6' }
                    }
                }
            }
        };
    </script>
    <style>
        html,body { background:#f8f9fa!important; color:#0f172a; }
        body { font-family:'Plus Jakarta Sans',sans-serif; overflow-x:hidden; }
        ::-webkit-scrollbar { width:8px; height:8px; }
        ::-webkit-scrollbar-track { background:#f1f5f9; }
        ::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:4px; }
        ::-webkit-scrollbar-thumb:hover { background:#7c3aed; }
        .light-card { background:#ffffff; border:1px solid #e2e8f0; box-shadow:0 4px 6px -1px rgba(0,0,0,.03),0 2px 4px -1px rgba(0,0,0,.02); transition:all .3s cubic-bezier(.4,0,.2,1); }
        .light-card:hover { border-color:#c084fc; transform:translateY(-4px); box-shadow:0 20px 25px -5px rgba(124,58,237,.1),0 10px 10px -5px rgba(124,58,237,.04); }
        input[type=range] { -webkit-appearance:none; background:transparent; }
        input[type=range]::-webkit-slider-thumb { -webkit-appearance:none; height:18px; width:18px; border-radius:50%; background:#7c3aed; cursor:pointer; box-shadow:0 0 8px rgba(124,58,237,.4); margin-top:-6px; }
        input[type=range]::-webkit-slider-runnable-track { width:100%; height:6px; cursor:pointer; background:#e2e8f0; border-radius:3px; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">

{{-- TOP ANNOUNCEMENT BAR --}}
<div class="bg-slate-900 py-2 text-center text-xs font-semibold text-white">
    <i class="fa-solid fa-motorcycle mr-2 text-brand-400"></i>
    2026 Models In Stock · Free Test Rides · Certified Inspections · Nationwide Delivery
</div>

{{-- MAIN HEADER NAV --}}
<header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur-xl shadow-sm">
    <div class="mx-auto flex h-16 max-w-7xl items-center gap-4 px-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-3 group">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-lg text-white shadow-md shadow-brand-500/25 group-hover:scale-105 transition-transform duration-300">
                <i class="fa-solid fa-motorcycle"></i>
            </span>
            <div>
                <strong class="block text-xl font-extrabold tracking-tight text-slate-900">MOTOVEX</strong>
                <small class="text-[9px] font-bold uppercase tracking-[0.2em] text-brand-600">Premium Motorcycles</small>
            </div>
        </a>

        {{-- Search bar --}}
        <form method="GET" action="{{ route('motorcycles.index') }}" class="mx-4 hidden flex-1 md:flex">
            <div class="relative w-full max-w-lg">
                <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search motorcycles, brands, categories..."
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-sm text-slate-800 outline-none focus:border-brand-500 focus:bg-white transition">
            </div>
        </form>

        <nav class="ml-auto hidden items-center gap-7 text-sm font-medium text-slate-500 lg:flex">
            <a href="{{ route('home') }}" class="hover:text-slate-900 transition">Home</a>
            <a href="{{ route('motorcycles.index') }}" class="text-brand-600 font-semibold">Shop</a>
            <a href="{{ route('brands.index') }}" class="hover:text-slate-900 transition">Brands</a>
            <a href="{{ route('services') }}" class="hover:text-slate-900 transition">Services</a>
            <a href="{{ route('contact') }}" class="hover:text-slate-900 transition">Contact</a>
        </nav>

        <div class="ml-auto lg:ml-4 flex items-center gap-3">
            @auth
                <a href="{{ route('account.dashboard') }}" class="hidden sm:inline-flex items-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-700 hover:border-brand-300 transition">
                    <i class="fa-solid fa-user"></i> Account
                </a>
            @else
                <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-2 rounded-full bg-brand-600 px-5 py-2.5 text-xs font-bold text-white hover:bg-brand-500 transition shadow-md shadow-brand-500/20">
                    Sign In
                </a>
            @endauth
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

{{-- CATEGORIES SUB-NAV --}}
<div class="border-b border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-6 overflow-x-auto py-3 text-xs font-semibold text-slate-500 scrollbar-none">
            <a href="{{ route('motorcycles.index') }}" class="shrink-0 transition hover:text-brand-600 {{ !request('category') ? 'text-brand-600 border-b-2 border-brand-600 pb-1' : '' }}">All</a>
            @foreach($categories as $cat)
            <a href="{{ route('motorcycles.index', ['category' => $cat]) }}"
               class="shrink-0 transition hover:text-brand-600 {{ request('category') === $cat ? 'text-brand-600 border-b-2 border-brand-600 pb-1' : '' }}">
                {{ ucfirst($cat) }}
            </a>
            @endforeach
        </div>
    </div>
</div>

{{-- HERO / BREADCRUMB SECTION --}}
<section class="relative bg-gradient-to-b from-slate-100 via-white to-slate-50 py-10 border-b border-slate-200 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center text-xs text-slate-500 mb-2 gap-2">
                    <a href="{{ route('home') }}" class="hover:text-brand-600 transition-colors">
                        <i class="fa-solid fa-house text-xs mr-1"></i> Home
                    </a>
                    <i class="fa-solid fa-chevron-right text-[9px] text-slate-400"></i>
                    <span class="text-slate-800 font-semibold">Motorcycle Showroom</span>
                </nav>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight flex items-center gap-3 flex-wrap">
                    Premium Motorcycle Shop
                    <span class="text-xs font-bold bg-brand-100 text-brand-700 border border-brand-200 px-3 py-1 rounded-full">
                        {{ $motorcycles->total() }} Models
                    </span>
                </h1>
                <p class="text-slate-600 text-xs sm:text-sm mt-1 max-w-xl">
                    Explore engineered perfection. Certified inspection, custom test ride bookings, and official warranty on all models.
                </p>
            </div>
            @if(request()->hasAny(['search','brand','category','min_cc','max_cc']))
            <div class="flex flex-wrap items-center gap-2 text-xs">
                @if(request('search'))
                    <span class="bg-brand-100 border border-brand-200 text-brand-700 px-2.5 py-1 rounded-full flex items-center gap-1 font-semibold">
                        Search: {{ request('search') }}
                        <a href="{{ route('motorcycles.index', request()->except('search','page')) }}" class="ml-1 text-brand-600">×</a>
                    </span>
                @endif
                @if(request('category'))
                    <span class="bg-brand-100 border border-brand-200 text-brand-700 px-2.5 py-1 rounded-full flex items-center gap-1 font-semibold">
                        {{ ucfirst(request('category')) }}
                        <a href="{{ route('motorcycles.index', request()->except('category','page')) }}" class="ml-1 text-brand-600">×</a>
                    </span>
                @endif
                <a href="{{ route('motorcycles.index') }}" class="text-slate-500 hover:text-red-500 transition-colors font-medium">Clear all</a>
            </div>
            @endif
        </div>
    </div>
</section>

{{-- VALUE PROPOSITION BADGES BAR --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-20">
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
        @foreach([
            ['fa-rotate-left','Free Return','30 days money back'],
            ['fa-gauge-high','Test Rides','Book online today'],
            ['fa-headset','Support 24/7','Dedicated bike experts'],
            ['fa-shield-halved','Secure Payment','100% Encrypted checkouts'],
            ['fa-certificate','100% Certified','Rigorous 150-point check'],
            ['fa-crown','Premium Brands','AURUM, Honda & more'],
        ] as [$icon,$title,$sub])
        <div class="light-card p-3.5 rounded-xl flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center shrink-0 text-base font-bold">
                <i class="fa-solid {{ $icon }}"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-slate-900">{{ $title }}</h4>
                <p class="text-[10px] text-slate-500">{{ $sub }}</p>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- MAIN CONTENT: Filter Sidebar + Product Grid --}}
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- LEFT FILTER SIDEBAR --}}
        <aside class="lg:col-span-1 space-y-4">
            <div class="light-card p-4 rounded-2xl border border-slate-200 sticky top-28">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                    <h3 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-sliders text-brand-600"></i> Filter Vehicles
                    </h3>
                    <a href="{{ route('motorcycles.index') }}" class="text-xs text-brand-600 hover:text-brand-800 font-semibold underline">Reset All</a>
                </div>

                <form method="GET" action="{{ route('motorcycles.index') }}" id="filter-form">

                    {{-- Category --}}
                    <div class="py-3 border-b border-slate-200">
                        <label class="block text-[10px] font-extrabold text-slate-900 uppercase tracking-wider mb-2">Category</label>
                        <div class="space-y-1.5">
                            <label class="flex items-center justify-between text-xs text-slate-700 hover:text-brand-600 cursor-pointer font-medium">
                                <span class="flex items-center gap-2">
                                    <input type="radio" name="category" value="" class="accent-brand-600"
                                           {{ !request('category') ? 'checked' : '' }} onchange="this.form.submit()">
                                    All Categories
                                </span>
                                <span class="text-slate-500 text-[10px] bg-slate-100 px-2 py-0.5 rounded-full font-semibold">{{ $motorcycles->total() }}</span>
                            </label>
                            @foreach($categories as $cat)
                            <label class="flex items-center justify-between text-xs text-slate-700 hover:text-brand-600 cursor-pointer font-medium">
                                <span class="flex items-center gap-2">
                                    <input type="radio" name="category" value="{{ $cat }}" class="accent-brand-600"
                                           {{ request('category') === $cat ? 'checked' : '' }} onchange="this.form.submit()">
                                    {{ ucfirst($cat) }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Engine CC --}}
                    <div class="py-3 border-b border-slate-200">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[10px] font-extrabold text-slate-900 uppercase tracking-wider">Engine Capacity</label>
                        </div>
                        <div class="flex gap-1.5">
                            <input type="number" name="min_cc" value="{{ request('min_cc') }}"
                                   placeholder="Min CC" class="flex-1 bg-slate-50 border border-slate-200 text-slate-800 text-[11px] rounded-lg px-2 py-1.5 focus:outline-none focus:border-brand-600">
                            <input type="number" name="max_cc" value="{{ request('max_cc') }}"
                                   placeholder="Max CC" class="flex-1 bg-slate-50 border border-slate-200 text-slate-800 text-[11px] rounded-lg px-2 py-1.5 focus:outline-none focus:border-brand-600">
                        </div>
                    </div>

                    {{-- Brands --}}
                    <div class="py-3 border-b border-slate-200">
                        <label class="block text-[10px] font-extrabold text-slate-900 uppercase tracking-wider mb-2">Brands</label>
                        <div class="space-y-1.5">
                            @foreach($brands as $b)
                            <label class="flex items-center justify-between text-xs text-slate-700 cursor-pointer font-medium hover:text-brand-600">
                                <span class="flex items-center gap-2">
                                    <input type="radio" name="brand" value="{{ $b->id }}" class="accent-brand-600"
                                           {{ request('brand') == $b->id ? 'checked' : '' }} onchange="this.form.submit()">
                                    {{ $b->name }}
                                </span>
                                <span class="text-slate-400 text-[10px]">{{ $b->motorcycles_count }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Sort --}}
                    <div class="pt-3">
                        <label class="block text-[10px] font-extrabold text-slate-900 uppercase tracking-wider mb-2">Sort By</label>
                        <div class="grid grid-cols-2 gap-1.5">
                            @foreach(['name' => 'Default', 'price_asc' => 'Price ↑', 'price_desc' => 'Price ↓', 'power' => 'Power'] as $val => $label)
                            <button type="submit" name="sort" value="{{ $val }}"
                                    class="text-[11px] py-1.5 px-2 rounded-lg border font-semibold transition-all
                                           {{ request('sort', 'name') === $val
                                              ? 'border-brand-600 bg-brand-600 text-white shadow-sm'
                                              : 'border-slate-200 bg-slate-50 text-slate-600 hover:text-slate-900' }}">
                                {{ $label }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif

                    <button type="submit" class="w-full mt-4 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs py-2.5 rounded-xl shadow-md shadow-brand-600/20 transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-filter"></i> Apply Filters
                    </button>
                </form>
            </div>

            {{-- Featured Sidebar Widget --}}
            @if($motorcycles->isNotEmpty())
            @php $feat = $motorcycles->first(fn($m) => $m->is_featured) ?? $motorcycles->first(); @endphp
            @if($feat)
            <div class="light-card p-4 rounded-2xl hidden lg:block">
                <h4 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-star text-amber-500"></i> Featured Offer
                </h4>
                <div class="rounded-xl overflow-hidden relative group">
                    <img src="{{ $feat->image_url ?: 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=600&q=80' }}"
                         alt="{{ $feat->model_name }}"
                         class="w-full h-36 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent"></div>
                    <div class="absolute bottom-2 left-3 right-3 flex justify-between items-end">
                        <div>
                            <span class="text-[10px] text-brand-200 font-bold uppercase">{{ $feat->brand->name }}</span>
                            <h5 class="text-xs font-bold text-white">{{ \Illuminate\Support\Str::limit($feat->model_name, 22) }}</h5>
                        </div>
                        @if($feat->price)
                            <span class="text-xs font-black text-white">${{ number_format($feat->price) }}</span>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            @endif
        </aside>

        {{-- RIGHT PRODUCTS DISPLAY AREA --}}
        <section class="lg:col-span-4 space-y-4">

            {{-- SORT BAR --}}
            <div class="light-card p-4 rounded-2xl flex flex-wrap items-center justify-between gap-4">
                <div class="text-xs text-slate-500 font-medium">
                    Showing <span class="font-bold text-slate-900">{{ $motorcycles->firstItem() ?? 0 }}</span>–<span class="font-bold text-slate-900">{{ $motorcycles->lastItem() ?? 0 }}</span>
                    of <span class="font-bold text-slate-900">{{ $motorcycles->total() }}</span> premium motorcycles
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2 text-xs">
                        <span class="text-slate-500 font-semibold">Sort By:</span>
                        <form method="GET" action="{{ route('motorcycles.index') }}">
                            @if(request('search'))   <input type="hidden" name="search"   value="{{ request('search') }}"> @endif
                            @if(request('brand'))    <input type="hidden" name="brand"    value="{{ request('brand') }}"> @endif
                            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                            @if(request('min_cc'))   <input type="hidden" name="min_cc"   value="{{ request('min_cc') }}"> @endif
                            @if(request('max_cc'))   <input type="hidden" name="max_cc"   value="{{ request('max_cc') }}"> @endif
                            <select name="sort" onchange="this.form.submit()"
                                    class="bg-slate-50 border border-slate-200 text-slate-800 text-xs py-1.5 px-3 rounded-xl focus:outline-none focus:border-brand-600 font-medium cursor-pointer">
                                <option value="name"       @selected(request('sort','name')==='name')>Default Featured</option>
                                <option value="price_asc"  @selected(request('sort')==='price_asc')>Price: Low to High</option>
                                <option value="price_desc" @selected(request('sort')==='price_desc')>Price: High to Low</option>
                                <option value="power"      @selected(request('sort')==='power')>Engine Power (CC)</option>
                            </select>
                        </form>
                    </div>
                    <div class="flex items-center bg-slate-100 border border-slate-200 rounded-xl p-1 gap-1">
                        <button id="btnGridView" onclick="setGridMode(true)" class="p-1.5 rounded-lg bg-brand-600 text-white shadow-sm transition-all">
                            <i class="fa-solid fa-border-all text-xs"></i>
                        </button>
                        <button id="btnListView" onclick="setGridMode(false)" class="p-1.5 rounded-lg text-slate-500 hover:text-slate-900 transition-all">
                            <i class="fa-solid fa-list text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>

            @if($motorcycles->isEmpty())
            <div class="light-card p-12 rounded-2xl text-center">
                <i class="fa-solid fa-motorcycle text-5xl text-slate-300 mb-4 block"></i>
                <h3 class="text-lg font-bold text-slate-900">No Motorcycles Found</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Try adjusting your filters or browse all models.</p>
                <a href="{{ route('motorcycles.index') }}" class="mt-4 inline-block bg-brand-600 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-md">Reset Filters</a>
            </div>
            @else

            {{-- GRID VIEW --}}
            <div id="grid-view">
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($motorcycles as $moto)
                    <div class="light-card rounded-2xl overflow-hidden border border-slate-200 flex flex-col justify-between group">
                        <div class="relative overflow-hidden bg-slate-100" style="aspect-ratio:4/3">
                            <img src="{{ $moto->image_url ?: 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=600&q=80' }}"
                                 alt="{{ $moto->model_name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                 loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>
                            <div class="absolute top-2 left-2 flex flex-wrap gap-1">
                                @if($moto->is_featured)
                                <span class="bg-brand-600 text-white text-[9px] font-extrabold px-2 py-0.5 rounded-md tracking-wider shadow-sm">FEATURED</span>
                                @endif
                                @if($moto->availability_status === 'available')
                                <span class="bg-emerald-600 text-white text-[9px] font-extrabold px-2 py-0.5 rounded-md flex items-center gap-0.5 shadow-sm">
                                    <i class="fa-solid fa-check text-[8px]"></i> CERTIFIED
                                </span>
                                @endif
                            </div>
                            <a href="{{ route('motorcycles.show', $moto->slug) }}"
                               class="absolute top-2 right-2 w-7 h-7 rounded-full bg-white/90 border border-slate-200 text-slate-600 hover:text-brand-600 flex items-center justify-center transition-colors shadow-sm">
                                <i class="fa-regular fa-eye text-[11px]"></i>
                            </a>
                            <div class="absolute bottom-2 left-2 right-2 flex items-center gap-1 flex-wrap">
                                @if($moto->engine_capacity)
                                <span class="bg-white/95 text-slate-800 text-[9px] font-bold px-1.5 py-0.5 rounded-lg shadow-sm">{{ number_format($moto->engine_capacity) }}CC</span>
                                @endif
                                @if($moto->year)
                                <span class="bg-white/95 text-slate-800 text-[9px] font-bold px-1.5 py-0.5 rounded-lg shadow-sm">{{ $moto->year }}</span>
                                @endif
                                @if($moto->category)
                                <span class="bg-white/95 text-slate-800 text-[9px] font-bold px-1.5 py-0.5 rounded-lg shadow-sm">{{ ucfirst($moto->category) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-4 flex-1 flex flex-col justify-between bg-white">
                            <div>
                                <div class="flex justify-between items-start">
                                    <span class="text-[9px] font-extrabold tracking-widest text-brand-600 uppercase">{{ $moto->brand->name }}</span>
                                </div>
                                <h3 class="text-sm font-bold text-slate-900 mt-0.5 group-hover:text-brand-600 transition-colors leading-tight">{{ $moto->model_name }}</h3>
                            </div>
                            <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between">
                                <div>
                                    <span class="text-[9px] text-slate-400 font-medium block">Starting Price</span>
                                    @if($moto->price)
                                        <span class="text-sm font-black text-slate-900">{{ $moto->currency ?: 'USD' }} {{ number_format($moto->price) }}</span>
                                    @else
                                        <span class="text-xs font-semibold text-slate-500">On request</span>
                                    @endif
                                </div>
                                <a href="{{ route('motorcycles.show', $moto->slug) }}"
                                   class="px-3 py-1.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-[11px] transition-all shadow-md shadow-brand-600/20 flex items-center gap-1">
                                    <i class="fa-solid fa-motorcycle text-[10px]"></i>
                                    <span>Order</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- LIST VIEW --}}
            <div id="list-view" style="display:none">
                @foreach($motorcycles as $moto)
                <div class="light-card rounded-2xl overflow-hidden border border-slate-200 p-4 flex flex-col sm:flex-row gap-5 items-center mb-4 bg-white">
                    <div class="relative w-full sm:w-56 h-36 rounded-xl overflow-hidden shrink-0 bg-slate-100">
                        <img src="{{ $moto->image_url ?: 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=400&q=80' }}"
                             alt="{{ $moto->model_name }}" class="w-full h-full object-cover" loading="lazy">
                        <span class="absolute top-2 left-2 bg-brand-600 text-white text-[9px] font-bold px-2 py-0.5 rounded">{{ $moto->brand->name }}</span>
                    </div>
                    <div class="flex-1 w-full flex flex-col justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">{{ $moto->model_name }}</h3>
                            @if($moto->short_description)
                            <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $moto->short_description }}</p>
                            @endif
                            <div class="flex gap-2 mt-3 flex-wrap">
                                @if($moto->engine_capacity) <span class="bg-slate-100 border border-slate-200 text-slate-700 text-[10px] font-semibold px-2 py-0.5 rounded">{{ number_format($moto->engine_capacity) }} CC</span> @endif
                                @if($moto->category) <span class="bg-slate-100 border border-slate-200 text-slate-700 text-[10px] font-semibold px-2 py-0.5 rounded">{{ ucfirst($moto->category) }}</span> @endif
                                @if($moto->year) <span class="bg-slate-100 border border-slate-200 text-slate-700 text-[10px] font-semibold px-2 py-0.5 rounded">{{ $moto->year }}</span> @endif
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-4 pt-3 border-t border-slate-100">
                            @if($moto->price)
                                <span class="text-lg font-black text-slate-900">{{ $moto->currency ?: 'USD' }} {{ number_format($moto->price) }}</span>
                            @else
                                <span class="text-sm text-slate-500">Price on request</span>
                            @endif
                            <div class="flex gap-2">
                                <a href="{{ route('motorcycles.show', $moto->slug) }}"
                                   class="px-3 py-1.5 bg-slate-100 border border-slate-200 text-xs font-semibold rounded-lg text-slate-700 hover:text-brand-600">Quick View</a>
                                <a href="{{ route('motorcycles.show', $moto->slug) }}"
                                   class="px-4 py-1.5 bg-brand-600 text-white text-xs font-bold rounded-lg hover:bg-brand-700 transition-colors">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- PAGINATION --}}
            @if($motorcycles->hasPages())
            <div class="flex justify-center mt-8 gap-2 flex-wrap">
                @if($motorcycles->onFirstPage())
                    <span class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-slate-400 text-xs shadow-sm">&laquo;</span>
                @else
                    <a href="{{ $motorcycles->previousPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-slate-700 hover:border-brand-600 hover:text-brand-600 text-xs transition-all shadow-sm">&laquo;</a>
                @endif

                @foreach($motorcycles->getUrlRange(max(1,$motorcycles->currentPage()-3), min($motorcycles->lastPage(),$motorcycles->currentPage()+3)) as $page => $url)
                    @if($page == $motorcycles->currentPage())
                        <span class="px-3.5 py-2 bg-brand-600 text-white rounded-xl text-xs font-bold shadow-md shadow-brand-600/20">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-slate-700 hover:border-brand-600 hover:text-brand-600 text-xs transition-all shadow-sm">{{ $page }}</a>
                    @endif
                @endforeach

                @if($motorcycles->hasMorePages())
                    <a href="{{ $motorcycles->nextPageUrl() }}" class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-slate-700 hover:border-brand-600 hover:text-brand-600 text-xs transition-all shadow-sm">&raquo;</a>
                @else
                    <span class="px-3 py-2 bg-white border border-slate-200 rounded-xl text-slate-400 text-xs shadow-sm">&raquo;</span>
                @endif
            </div>
            @endif
            @endif

        </section>
    </div>
</main>

{{-- FOOTER --}}
<footer class="border-t border-slate-200 bg-white text-slate-600 pt-16 pb-8 text-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Contact Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 pb-12 border-b border-slate-200">
            @foreach([
                ['fa-location-dot','Our Showroom','123 Motorway Street, Cairo, Egypt'],
                ['fa-envelope','Mail Us','info@motovex-group.com'],
                ['fa-phone','Direct Line','+20 (012) 3456 7890'],
                ['fa-motorcycle','MOTOVEX Group','Official Authorized Distributor'],
            ] as [$icon,$title,$text])
            <div class="light-card p-5 rounded-2xl flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center text-xl shrink-0 font-bold">
                    <i class="fa-solid {{ $icon }}"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 text-sm">{{ $title }}</h4>
                    <p class="text-slate-500 mt-0.5">{{ $text }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Links Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 py-12 border-b border-slate-200">
            <div>
                <a href="{{ route('home') }}" class="flex items-center gap-2 mb-4">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-600 text-white text-sm">
                        <i class="fa-solid fa-motorcycle"></i>
                    </span>
                    <strong class="text-base font-extrabold text-slate-900">MOTOVEX</strong>
                </a>
                <p class="text-slate-500 leading-relaxed">Premium motorcycle dealership with certified inspections and expert support.</p>
            </div>
            <div>
                <h4 class="font-bold text-slate-900 mb-4 text-xs uppercase tracking-wider">Explore</h4>
                <ul class="space-y-2.5 text-slate-500">
                    <li><a href="{{ route('motorcycles.index') }}" class="hover:text-brand-600 transition">All Motorcycles</a></li>
                    <li><a href="{{ route('brands.index') }}" class="hover:text-brand-600 transition">Our Brands</a></li>
                    <li><a href="{{ route('services') }}" class="hover:text-brand-600 transition">Services</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-brand-600 transition">Contact</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-slate-900 mb-4 text-xs uppercase tracking-wider">Account</h4>
                <ul class="space-y-2.5 text-slate-500">
                    <li><a href="{{ route('login') }}" class="hover:text-brand-600 transition">Login</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-brand-600 transition">Register</a></li>
                    <li><a href="{{ route('account.dashboard') }}" class="hover:text-brand-600 transition">My Account</a></li>
                    <li><a href="{{ route('account.enquiries') }}" class="hover:text-brand-600 transition">My Enquiries</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-slate-900 mb-4 text-xs uppercase tracking-wider">Top Brands</h4>
                <ul class="space-y-2.5 text-slate-500">
                    @foreach($footerBrands ?? [] as $fb)
                    <li><a href="{{ route('brands.show', $fb->slug) }}" class="hover:text-brand-600 transition">{{ $fb->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-6 text-slate-400">
            <span>© {{ date('Y') }} MOTOVEX Premium Motorcycle Group. All rights reserved.</span>
            <div class="flex gap-5">
                <a href="#" class="hover:text-slate-700 transition">Privacy</a>
                <a href="#" class="hover:text-slate-700 transition">Terms</a>
                <a href="#" class="hover:text-slate-700 transition">Cookies</a>
            </div>
        </div>
    </div>
</footer>

{{-- Toast notification --}}
<div id="toast" class="fixed bottom-6 left-1/2 z-50 -translate-x-1/2 hidden rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-xl" role="status">
    <span id="toastMsg"></span>
</div>

<script>
    function setGridMode(isGrid) {
        document.getElementById('grid-view').style.display = isGrid ? 'block' : 'none';
        document.getElementById('list-view').style.display = isGrid ? 'none' : 'block';
        document.getElementById('btnGridView').className = isGrid
            ? 'p-1.5 rounded-lg bg-brand-600 text-white shadow-sm transition-all'
            : 'p-1.5 rounded-lg text-slate-500 hover:text-slate-900 transition-all';
        document.getElementById('btnListView').className = !isGrid
            ? 'p-1.5 rounded-lg bg-brand-600 text-white shadow-sm transition-all'
            : 'p-1.5 rounded-lg text-slate-500 hover:text-slate-900 transition-all';
        localStorage.setItem('moto-view', isGrid ? 'grid' : 'list');
    }
    if (localStorage.getItem('moto-view') === 'list') setGridMode(false);

    function showToast(msg) {
        const t = document.getElementById('toast');
        document.getElementById('toastMsg').textContent = msg;
        t.classList.remove('hidden');
        setTimeout(() => t.classList.add('hidden'), 3000);
    }

    document.getElementById('menuButton').addEventListener('click', () => {
        document.getElementById('mobileMenu').classList.toggle('hidden');
    });
</script>
</body>
</html>
