<!DOCTYPE html>
<html lang="{{ app()->getLocale() === 'ar' ? 'ar' : 'en' }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $sparePart->name }} | MOTOVEX Spare Parts</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode:false,
            theme:{ extend:{ fontFamily:{ sans:['Plus Jakarta Sans','sans-serif'] }, colors:{ brand:{50:'#f5f3ff',100:'#ede9fe',200:'#ddd6fe',300:'#c4b5fd',400:'#a78bfa',500:'#8b5cf6',600:'#7c3aed',700:'#6d28d9',800:'#5b21b6',900:'#4c1d95'} } } }
        };
    </script>
    <style>
        :root {
            --brand:#7C3AED; --brand-d:#6D28D9; --brand-soft:#F5F3FF;
            --ink:#172033; --muted:#64748B; --line:#E2E8F0; --bg:#F5F7FC;
        }
        body { 
            font-family:'Plus Jakarta Sans',sans-serif; 
            background:var(--bg); 
            background-image: 
                radial-gradient(at 0% 0%, rgba(124, 58, 237, 0.03) 0, transparent 50%), 
                radial-gradient(at 50% 0%, rgba(124, 58, 237, 0.02) 0, transparent 50%);
            color:var(--ink); 
            -webkit-font-smoothing:antialiased; 
        }
        .thumb { cursor:pointer; transition:all .3s cubic-bezier(0.4, 0, 0.2, 1); border: 2px solid transparent; }
        .thumb:hover, .thumb.active { border-color:var(--brand); box-shadow:0 0 0 4px rgba(124,58,237,.1); transform: translateY(-2px); }
        
        .cinematic-badge {
            font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px;
            padding: 5px 12px; border-radius: 10px; backdrop-filter: blur(8px);
        }
        .avail-in_stock    { background:rgba(240, 253, 244, 0.85); color:#15803d; border:1px solid #bbf7d0; }
        .avail-low_stock   { background:rgba(255, 251, 235, 0.85); color:#92400e; border:1px solid #fde68a; }
        .avail-out_of_stock{ background:rgba(254, 242, 242, 0.85); color:#991b1b; border:1px solid #fecaca; }

        .btn-luxury-cart {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 14px 28px; border-radius: 16px; font-size: 14px; font-weight: 800;
            transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
            background: var(--brand); color: white; border: none;
            box-shadow: 0 8px 20px -6px rgba(124, 58, 237, 0.4);
        }
        .btn-luxury-cart:hover:not(:disabled) {
            background: var(--brand-d); transform: translateY(-2px);
            box-shadow: 0 12px 24px -6px rgba(124, 58, 237, 0.5);
        }
        .btn-luxury-cart:active:not(:disabled) { transform: translateY(0); }
        .btn-luxury-cart:disabled { background: #E2E8F0; color: #94A3B8; box-shadow: none; cursor: not-allowed; }

        .btn-luxury-outline {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 14px 24px; border-radius: 16px; font-size: 14px; font-weight: 700;
            transition: all 0.2s ease; background: white; color: #64748B; border: 1px solid #E2E8F0;
        }
        .btn-luxury-outline:hover { background: #F8FAFC; color: #172033; border-color: #CBD5E1; transform: translateY(-1px); }

        .qty-control {
            display: flex; align-items: center; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 16px; overflow: hidden;
        }
        .qty-btn {
            width: 44px; height: 48px; display: flex; align-items: center; justify-content: center;
            color: #64748B; transition: all 0.2s ease;
        }
        .qty-btn:hover { background: #F1F5F9; color: var(--brand); }
        .qty-input {
            width: 50px; height: 48px; text-align: center; font-size: 15px; font-weight: 800;
            background: transparent; border: none; outline: none; color: #172033;
        }
        
        /* Related Card */
        .related-card { transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); border: 1px solid var(--line); background: white; border-radius: 20px; overflow: hidden; }
        .related-card:hover { transform: translateY(-6px); box-shadow: 0 15px 30px -10px rgba(0,0,0,0.08); border-color: #c4b5fd; }
    </style>
@include('partials.rtl-styles')
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
            <a href="{{ route('cart.index') }}" class="text-xs font-black uppercase tracking-widest bg-green-600 text-white px-4 py-2 rounded-xl hover:bg-green-700 transition">{{ __('spare_parts.view_cart') }}</a>
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-3 px-5 py-4 rounded-2xl bg-red-50 border border-red-100 text-red-700 shadow-sm animate-in fade-in slide-in-from-top-4 duration-500">
            <i class="fa-solid fa-circle-exclamation text-red-500"></i>
            <span class="text-sm font-bold">{{ session('error') }}</span>
        </div>
    @endif
</div>

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-slate-400 mb-7">
        <a href="{{ route('home') }}" class="hover:text-brand-600 transition">{{ __('common.home') }}</a>
        <i class="fa-solid fa-chevron-right text-[9px]"></i>
        <a href="{{ route('spare-parts.index') }}" class="hover:text-brand-600 transition">{{ __('spare_parts.page_title') }}</a>
        <i class="fa-solid fa-chevron-right text-[9px]"></i>
        <span class="text-slate-700 font-semibold truncate max-w-[200px]">{{ $sparePart->name }}</span>
    </nav>

    {{-- Main grid --}}
    <div class="grid lg:grid-cols-2 gap-10 bg-white border border-slate-200 rounded-3xl p-6 sm:p-10 shadow-sm">

        {{-- Images --}}
        <div>
            {{-- Main image --}}
            <div id="mainImgWrap" class="rounded-2xl overflow-hidden border border-slate-200 bg-slate-900 flex items-center justify-center" style="aspect-ratio:4/3;">
                @if($sparePart->image_url)
                    <img id="mainImg" src="{{ $sparePart->image_url }}" alt="{{ $sparePart->name }}"
                         class="w-full h-full object-contain p-6">
                @else
                    <div class="flex flex-col items-center justify-center text-slate-600 p-10">
                        <i class="fa-solid fa-gears text-5xl"></i>
                        <span class="text-sm mt-3 font-medium">{{ __('spare_parts.no_image_available') }}</span>
                    </div>
                @endif
            </div>

            {{-- Gallery thumbs --}}
            @php
                $allImages = array_filter(array_merge(
                    $sparePart->image_url ? [$sparePart->image_url] : [],
                    $sparePart->additional_image_urls
                ));
            @endphp
            @if(count($allImages) > 1)
            <div class="mt-3 flex gap-2 flex-wrap">
                @foreach($allImages as $idx => $img)
                <button onclick="document.getElementById('mainImg').src='{{ $img }}'; document.querySelectorAll('.thumb').forEach(t=>t.classList.remove('active')); this.classList.add('active')"
                        class="thumb w-16 h-16 rounded-xl border-2 {{ $idx === 0 ? 'border-brand-500 active' : 'border-slate-200' }} overflow-hidden bg-slate-900 flex items-center justify-center">
                    <img src="{{ $img }}" class="w-full h-full object-contain p-1.5" loading="lazy">
                </button>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Details --}}
        <div class="flex flex-col gap-5">
            {{-- Brand & Category --}}
            <div class="flex flex-wrap items-center gap-2">
                @if($sparePart->brand)
                <span class="text-xs font-extrabold uppercase tracking-widest text-brand-600 bg-brand-50 border border-brand-200 px-3 py-1 rounded-full">
                    {{ $sparePart->brand->name }}
                </span>
                @endif
                @if($sparePart->category)
                <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                    {{ $sparePart->categoryLabel() }}
                </span>
                @endif
                <span class="avail-{{ $sparePart->availability_status }} text-[11px] font-bold px-3 py-1 rounded-full">
                    {{ $sparePart->availabilityLabel() }}
                </span>
            </div>

            {{-- Name --}}
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight tracking-tight">
                {{ $sparePart->name }}
            </h1>

            {{-- SKU --}}
            @if($sparePart->sku)
            <p class="text-sm font-mono text-slate-400">
                <span class="font-bold text-slate-500">{{ __('spare_parts.part_number') }}:</span> {{ $sparePart->sku }}
            </p>
            @endif

            {{-- Price --}}
            @if($sparePart->price)
            <div class="bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400">{{ __('spare_parts.price_label') }}</p>
                <p class="text-3xl font-extrabold text-slate-900 mt-1">
                    {{ number_format($sparePart->price, 2) }}
                    <span class="text-sm font-bold text-slate-500">{{ $sparePart->currency }}</span>
                </p>
            </div>
            @endif

            {{-- Description --}}
            @if($sparePart->description)
            <div>
                <h2 class="text-sm font-bold text-slate-700 mb-2">{{ __('spare_parts.description') }}</h2>
                <p class="text-sm leading-relaxed text-slate-600">{{ $sparePart->description }}</p>
            </div>
            @endif

            {{-- Compatible motorcycles --}}
            @if($sparePart->compatibleMotorcycles->isNotEmpty())
            <div>
                <h2 class="text-sm font-bold text-slate-700 mb-2">
                    <i class="fa-solid fa-motorcycle text-brand-500 mr-1"></i> {{ __('spare_parts.compatible_with') }}
                </h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($sparePart->compatibleMotorcycles as $moto)
                    <a href="{{ route('motorcycles.show', $moto) }}"
                       class="text-xs bg-white border border-slate-200 hover:border-brand-300 hover:text-brand-600 px-3 py-1.5 rounded-xl font-semibold text-slate-700 transition">
                        {{ $moto->brand->name ?? '' }} {{ $moto->model_name }} ({{ $moto->year }})
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Compatibility notes --}}
            @if($sparePart->compatibility_notes)
            <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-sm text-amber-800">
                <i class="fa-solid fa-circle-info mr-1"></i>
                {{ $sparePart->compatibility_notes }}
            </div>
            @endif

            {{-- Stock --}}
            @if($sparePart->stock_quantity > 0)
            <p class="text-sm text-slate-500">
                <i class="fa-solid fa-box text-brand-400 mr-1"></i>
                <span class="font-bold text-slate-700">{{ $sparePart->stock_quantity }}</span> {{ __('spare_parts.in_stock_label') }}
            </p>
            @endif

            {{-- CTA --}}
            <div class="mt-6 flex flex-wrap gap-4">
                @if($sparePart->isAvailable())
                <form method="POST" action="{{ route('cart.add-part', $sparePart) }}" class="flex items-center gap-4"
                      data-confirm-cart data-part-name="{{ $sparePart->name }}" data-part-price="{{ $sparePart->price }}" data-part-currency="{{ $sparePart->currency }}">
                    @csrf
                    <div class="qty-control">
                        <button type="button" onclick="var i=this.parentNode.querySelector('input');if(parseInt(i.value)>1)i.value=parseInt(i.value)-1"
                                class="qty-btn">
                            <i class="fa-solid fa-minus text-[10px]"></i>
                        </button>
                        <input type="number" name="quantity" value="1" min="1"
                               max="{{ $sparePart->stock_quantity > 0 ? $sparePart->stock_quantity : 99 }}"
                               class="qty-input">
                        <button type="button" onclick="var i=this.parentNode.querySelector('input');var m=parseInt(i.getAttribute('max')||99);if(parseInt(i.value)<m)i.value=parseInt(i.value)+1"
                                class="qty-btn">
                            <i class="fa-solid fa-plus text-[10px]"></i>
                        </button>
                    </div>
                    <button type="submit" class="btn-luxury-cart group">
                        <i class="fa-solid fa-cart-plus mr-3 group-hover:scale-110 transition"></i>
                        {{ __('spare_parts.add_to_cart') }}
                    </button>
                </form>
                @else
                <button disabled class="btn-luxury-cart opacity-70">
                    <i class="fa-solid fa-ban mr-2"></i> {{ __('spare_parts.currently_unavailable') }}
                </button>
                @endif

                <a href="{{ route('contact') }}?subject={{ urlencode('Spare Part Enquiry: '.$sparePart->name) }}&enquiry_type=parts"
                   class="btn-luxury-outline">
                    <i class="fa-solid fa-paper-plane mr-2 text-[11px]"></i> {{ __('spare_parts.direct_enquiry') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Related parts --}}
    @if($related->isNotEmpty())
    <div class="mt-20">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-black text-slate-900 tracking-tight uppercase">{{ __('spare_parts.related_inventory') }}</h2>
            <a href="{{ route('spare-parts.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 flex items-center gap-1.5 transition-all">
                {{ __('navigation.view_all') }} <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($related as $part)
            <a href="{{ route('spare-parts.show', $part) }}"
               class="related-card group">
                <div class="aspect-[4/3] bg-slate-900 flex items-center justify-center overflow-hidden relative">
                    @if($part->image_url)
                        <img src="{{ $part->image_url }}" class="w-full h-full object-contain p-4 group-hover:scale-110 transition-transform duration-500" loading="lazy">
                    @else
                        <i class="fa-solid fa-gears text-3xl text-slate-700"></i>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </div>
                <div class="p-5">
                    <p class="text-[9px] font-black uppercase tracking-[0.2em] text-brand-600 mb-1">{{ $part->brand->name ?? 'MOTOVEX' }}</p>
                    <p class="font-bold text-slate-900 text-[13px] leading-snug line-clamp-2 group-hover:text-brand-600 transition-colors">{{ $part->name }}</p>
                    @if($part->price)
                    <p class="mt-3 font-black text-slate-900 flex items-baseline gap-1">
                        {{ number_format($part->price, 0) }}
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ $part->currency }}</span>
                    </p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</div>

@include('partials.footer')
@include('partials.cart-confirm', ['theme' => 'light'])
</body>
</html>
