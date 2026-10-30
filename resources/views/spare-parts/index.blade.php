<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spare Parts | MOTOVEX — Genuine Motorcycle Parts & Accessories</title>
    <meta name="description" content="Browse genuine motorcycle spare parts and accessories at MOTOVEX. Filter by category, brand, and compatibility.">
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
                        brand: { 50:'#f5f3ff',100:'#ede9fe',200:'#ddd6fe',300:'#c4b5fd',400:'#a78bfa',500:'#8b5cf6',600:'#7c3aed',700:'#6d28d9',800:'#5b21b6',900:'#4c1d95' }
                    }
                }
            }
        };
    </script>
    <style>
        :root {
            --brand:#7C3AED; --brand-d:#6D28D9; --brand-soft:#F5F3FF;
            --ink:#172033; --muted:#64748B; --line:#E2E8F0; --bg:#F5F7FC;
        }
        body { 
            font-family:'Plus Jakarta Sans',sans-serif; 
            background: var(--bg);
            background-image: 
                radial-gradient(at 0% 0%, rgba(124, 58, 237, 0.03) 0, transparent 50%), 
                radial-gradient(at 50% 0%, rgba(124, 58, 237, 0.02) 0, transparent 50%);
            color:var(--ink); 
            overflow-x:hidden; 
            -webkit-font-smoothing:antialiased; 
        }
        ::-webkit-scrollbar { width:7px; } ::-webkit-scrollbar-track { background:#f1f5f9; } ::-webkit-scrollbar-thumb { background:#7c3aed; border-radius:10px; }

        .part-card {
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            background: #ffffff;
            border: 1px solid var(--line);
        }
        .part-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -12px rgba(124, 58, 237, 0.12);
            border-color: #c4b5fd;
        }
        .part-card:hover .part-img { transform: scale(1.08) rotate(1deg); }
        .part-img { transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1); }

        /* Cinematic Badge */
        .cinematic-badge {
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 4px 10px;
            border-radius: 8px;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .avail-in_stock     { background: rgba(240, 253, 244, 0.85); color: #15803d; border-color: #bbf7d0; }
        .avail-low_stock    { background: rgba(255, 251, 235, 0.85); color: #92400e; border-color: #fde68a; }
        .avail-out_of_stock { background: rgba(254, 242, 242, 0.85); color: #991b1b; border-color: #fecaca; }

        /* High-end Buttons */
        .btn-luxury-view {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 16px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
            transition: all 0.2s ease;
            background: #F8FAFC;
            color: #64748B;
            border: 1px solid #E2E8F0;
        }
        .btn-luxury-view:hover {
            background: #F1F5F9;
            color: #172033;
            border-color: #CBD5E1;
        }

        .btn-luxury-cart {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 16px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
            transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
            background: var(--brand);
            color: white;
            border: none;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.2);
        }
        .btn-luxury-cart:hover:not(:disabled) {
            background: var(--brand-d);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(124, 58, 237, 0.3);
        }
        .btn-luxury-cart:active:not(:disabled) { transform: translateY(0); }
        .btn-luxury-cart:disabled {
            background: #E2E8F0;
            color: #94A3B8;
            box-shadow: none;
            cursor: not-allowed;
        }

        .reveal { opacity:0; transform:translateY(24px); transition:all .65s cubic-bezier(.16,1,.3,1); }
        .reveal.active { opacity:1; transform:translateY(0); }
    </style>
</head>
<body>
@include('partials.navbar')

{{-- Success/Error Alerts --}}
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-6">
    @if(session('success'))
        <div class="flex items-center justify-between gap-3 px-5 py-4 rounded-2xl bg-green-50 border border-green-100 text-green-700 shadow-sm animate-in fade-in slide-in-from-top-4 duration-500">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <span class="text-sm font-bold">{{ session('success') }}</span>
            </div>
            <a href="{{ route('cart.index') }}" class="text-xs font-black uppercase tracking-widest bg-green-600 text-white px-4 py-2 rounded-xl hover:bg-green-700 transition">View Cart</a>
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-red-50 border border-red-100 text-red-700 shadow-sm animate-in fade-in slide-in-from-top-4 duration-500">
            <i class="fa-solid fa-circle-exclamation text-red-500"></i>
            <span class="text-sm font-bold">{{ session('error') }}</span>
        </div>
    @endif
</div>

{{-- Hero --}}
<section class="bg-white border-b border-slate-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-14">
        <nav class="flex items-center gap-2 text-xs text-slate-400 mb-5">
            <a href="{{ route('home') }}" class="hover:text-brand-600 transition">Home</a>
            <i class="fa-solid fa-chevron-right text-[9px]"></i>
            <span class="text-slate-700 font-semibold">Spare Parts</span>
        </nav>
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full bg-brand-50 border border-brand-200 px-3 py-1 text-xs font-bold text-brand-700 mb-4">
                    <i class="fa-solid fa-gears text-brand-500"></i> Genuine Parts & Accessories
                </div>
                <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight">Spare Parts</h1>
                <p class="mt-3 text-slate-500 max-w-xl">
                    Genuine motorcycle parts, accessories, and components — sourced from trusted suppliers across our brand portfolio.
                </p>
            </div>
            <div class="flex items-center gap-3 text-sm text-slate-500">
                <span class="font-bold text-slate-800">{{ $spareParts->total() }}</span> parts found
            </div>
        </div>
    </div>
</section>

{{-- Main content --}}
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col lg:flex-row gap-8">

        {{-- ── Sidebar filters ── --}}
        <aside class="w-full lg:w-72 shrink-0">
            <form method="GET" action="{{ route('spare-parts.index') }}" id="filterForm">
                <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden sticky top-24">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <span class="text-[13px] font-extrabold text-slate-900 flex items-center gap-2.5">
                            <i class="fa-solid fa-sliders text-brand-600"></i> PREFERENCES
                        </span>
                        @if(request()->hasAny(['search','category','brand','motorcycle','sort']))
                            <a href="{{ route('spare-parts.index') }}" class="text-[11px] font-bold text-brand-600 hover:text-brand-700 uppercase tracking-wider">Reset</a>
                        @endif
                    </div>

                    <div class="p-6 space-y-6">
                        {{-- Search --}}
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2.5">Keyword Search</label>
                            <div class="relative group">
                                <i class="fa-solid fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs transition-colors group-focus-within:text-brand-500"></i>
                                <input type="text" name="search" value="{{ request('search') }}"
                                       placeholder="Part name, SKU..."
                                       class="w-full pl-10 pr-4 py-3 text-[13px] border border-slate-200 rounded-2xl bg-slate-50/50 focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 outline-none transition-all">
                            </div>
                        </div>

                        {{-- Category --}}
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2.5">Department</label>
                            <select name="category" onchange="this.form.submit()"
                                    class="w-full py-3 px-4 text-[13px] border border-slate-200 rounded-2xl bg-slate-50/50 focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 outline-none appearance-none cursor-pointer transition-all">
                                <option value="">All Categories</option>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Brand --}}
                        @if($brands->isNotEmpty())
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2.5">Manufacturer</label>
                            <select name="brand" onchange="this.form.submit()"
                                    class="w-full py-3 px-4 text-[13px] border border-slate-200 rounded-2xl bg-slate-50/50 focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 outline-none appearance-none cursor-pointer transition-all">
                                <option value="">All Brands</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        {{-- Motorcycle compatibility --}}
                        @if($motorcycles->isNotEmpty())
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2.5">Machine Compatibility</label>
                            <select name="motorcycle" onchange="this.form.submit()"
                                    class="w-full py-3 px-4 text-[13px] border border-slate-200 rounded-2xl bg-slate-50/50 focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 outline-none appearance-none cursor-pointer transition-all">
                                <option value="">Any Motorcycle</option>
                                @foreach($motorcycles as $moto)
                                    <option value="{{ $moto->id }}" {{ request('motorcycle') == $moto->id ? 'selected' : '' }}>
                                        {{ $moto->brand->name ?? '' }} {{ $moto->model_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        {{-- Sort --}}
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2.5">Order By</label>
                            <select name="sort" onchange="this.form.submit()"
                                    class="w-full py-3 px-4 text-[13px] border border-slate-200 rounded-2xl bg-slate-50/50 focus:bg-white focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 outline-none appearance-none cursor-pointer transition-all">
                                <option value="newest"    {{ request('sort','newest') === 'newest'     ? 'selected' : '' }}>Newest Inventory</option>
                                <option value="price_asc" {{ request('sort') === 'price_asc'           ? 'selected' : '' }}>Price: Lowest First</option>
                                <option value="price_desc"{{ request('sort') === 'price_desc'          ? 'selected' : '' }}>Price: Highest First</option>
                                <option value="name_asc"  {{ request('sort') === 'name_asc'            ? 'selected' : '' }}>Alphabetical A-Z</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full bg-slate-900 text-white rounded-2xl py-4 text-[13px] font-black uppercase tracking-widest hover:bg-brand-600 hover:shadow-lg hover:shadow-brand-500/20 transition-all duration-300">
                            Apply Changes
                        </button>
                    </div>
                </div>
            </form>
        </aside>

        {{-- ── Parts grid ── --}}
        <div class="flex-1 min-w-0">
            @if($spareParts->isEmpty())
                <div class="bg-white border border-dashed border-slate-300 rounded-2xl p-16 text-center">
                    <div class="w-20 h-20 rounded-full bg-brand-50 border border-brand-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-gears text-3xl text-brand-400"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">No parts found</h2>
                    <p class="mt-2 text-slate-500 text-sm max-w-sm mx-auto">Try adjusting your filters or search term.</p>
                    <a href="{{ route('spare-parts.index') }}" class="mt-6 inline-flex items-center gap-2 bg-brand-600 text-white px-6 py-3 rounded-xl text-sm font-bold hover:bg-brand-700 transition">
                        <i class="fa-solid fa-rotate-left"></i> Clear Filters
                    </a>
                </div>
            @else
                <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($spareParts as $i => $part)
                    <article class="part-card bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm reveal" style="transition-delay:{{ ($i % 6) * 80 }}ms">
                        {{-- Image --}}
                        <a href="{{ route('spare-parts.show', $part) }}" class="block relative overflow-hidden" style="background:radial-gradient(ellipse at 50% 40%, #1e293b,#0f172a); aspect-ratio:4/3;">
                            @if($part->image_url)
                                <img src="{{ $part->image_url }}" alt="{{ $part->name }}"
                                     class="part-img w-full h-full object-contain p-4" loading="lazy">
                            @else
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-600">
                                    <i class="fa-solid fa-gears text-4xl"></i>
                                    <span class="text-xs mt-2 font-medium">No Image</span>
                                </div>
                            @endif
                            {{-- Availability badge --}}
                            <div class="absolute top-3 left-3">
                                <span class="avail-{{ $part->availability_status }} cinematic-badge inline-flex items-center gap-1.5 backdrop-blur-md">
                                    <i class="fa-solid fa-circle text-[6px]"></i>
                                    {{ $part->availabilityLabel() }}
                                </span>
                            </div>
                        </a>

                        {{-- Body --}}
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <div>
                                    @if($part->brand)
                                    <p class="text-[10px] font-extrabold uppercase tracking-[0.15em] text-brand-600 mb-0.5">{{ $part->brand->name }}</p>
                                    @endif
                                    <h3 class="font-bold text-slate-900 leading-snug text-[15px]">
                                        <a href="{{ route('spare-parts.show', $part) }}" class="hover:text-brand-600 transition">{{ $part->name }}</a>
                                    </h3>
                                </div>
                                @if($part->sku)
                                <span class="shrink-0 text-[10px] font-mono font-medium text-slate-400 bg-slate-50 px-2 py-0.5 rounded border border-slate-100">
                                    {{ $part->sku }}
                                </span>
                                @endif
                            </div>

                            @if($part->category)
                            <p class="text-[11px] font-semibold text-slate-500 flex items-center gap-1.5">
                                <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                {{ $part->categoryLabel() }}
                            </p>
                            @endif

                            {{-- Compatible motorcycles --}}
                            @if($part->compatibleMotorcycles->isNotEmpty())
                            <div class="mt-3 flex flex-wrap gap-1.5">
                                @foreach($part->compatibleMotorcycles->take(2) as $moto)
                                <span class="text-[9px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md font-bold uppercase tracking-wider">
                                    {{ $moto->model_name }}
                                </span>
                                @endforeach
                                @if($part->compatibleMotorcycles->count() > 2)
                                <span class="text-[9px] bg-brand-50 text-brand-600 px-2 py-0.5 rounded-md font-bold uppercase tracking-wider">
                                    +{{ $part->compatibleMotorcycles->count() - 2 }}
                                </span>
                                @endif
                            </div>
                            @endif

                            {{-- Price + CTA --}}
                            <div class="mt-5 pt-4 border-t border-slate-100">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        @if($part->price)
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Price</p>
                                            <p class="text-xl font-black text-slate-900 flex items-baseline gap-1">
                                                {{ number_format($part->price, 0) }}
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $part->currency }}</span>
                                            </p>
                                        @else
                                            <p class="text-xs font-bold text-slate-500 italic">Quote on request</p>
                                        @endif
                                    </div>
                                    @if($part->isAvailable())
                                        <div class="text-right">
                                            <p class="text-[9px] font-bold text-green-600 flex items-center justify-end gap-1">
                                                <i class="fa-solid fa-check-double"></i> Ready to Ship
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <div class="grid grid-cols-2 gap-2.5">
                                    <a href="{{ route('spare-parts.show', $part) }}" class="btn-luxury-view">
                                        Details
                                    </a>
                                    
                                    @if($part->isAvailable())
                                        <form action="{{ route('cart.add-part', $part) }}" method="POST" class="w-full">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn-luxury-cart w-full group">
                                                <i class="fa-solid fa-cart-plus mr-2 text-[10px] group-hover:scale-110 transition"></i>
                                                Add
                                            </button>
                                        </form>
                                    @else
                                        <button disabled class="btn-luxury-cart w-full">
                                            Sold Out
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($spareParts->hasPages())
                <div class="mt-10 flex justify-center">
                    {{ $spareParts->links('vendor.pagination.tailwind') }}
                </div>
                @endif
            @endif
        </div>
    </div>
</div>

@include('partials.footer')

<script>
    const reveals = document.querySelectorAll('.reveal');
    const revealOnScroll = () => reveals.forEach(el => {
        if (el.getBoundingClientRect().top < window.innerHeight - 80) el.classList.add('active');
    });
    window.addEventListener('scroll', revealOnScroll);
    revealOnScroll();
</script>
</body>
</html>
