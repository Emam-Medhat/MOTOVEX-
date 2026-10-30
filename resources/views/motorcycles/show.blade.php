<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MOTOVEX | {{ $motorcycle->model_name }}</title>
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
                        brand: { 50:'#f5f3ff', 100:'#ede9fe', 200:'#ddd6fe', 300:'#c4b5fd', 400:'#a78bfa', 500:'#8b5cf6', 600:'#7c3aed', 700:'#6d28d9', 800:'#5b21b6', 900:'#4c1d95' }
                    }
                }
            }
        };
    </script>
    <style>
        html { scroll-behavior:smooth; background:#f8f9fa; }
        body { font-family:'Plus Jakarta Sans','Noto Kufi Arabic',sans-serif; background:#f8f9fa; color:#0f172a; }
        .hero-bg {
            background: linear-gradient(105deg,rgba(9,11,18,.85) 0%,rgba(9,11,18,.45) 100%),
                        url('{{ $motorcycle->image_url ?: "https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=1800&q=85" }}') center/cover;
        }
        .product-card img { transition:transform .5s cubic-bezier(.4,0,.2,1); }
        .product-card:hover img { transform:scale(1.07); }
        .thumb.active { border-color:#8b5cf6; box-shadow:0 0 0 2px #8b5cf6; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">

@include('partials.navbar')

{{-- Hero section with bike image background --}}
<section class="hero-bg">
    <div class="mx-auto flex min-h-[220px] max-w-7xl items-end px-4 py-12 sm:px-6 lg:px-8">
        <div>
            <p class="mb-2 text-xs font-bold uppercase tracking-[0.25em] text-brand-400">{{ strtoupper($motorcycle->brand->name) }} Official</p>
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl">{{ $motorcycle->model_name }}</h1>
            <p class="mt-2 text-slate-300">Freedom on Two Wheels</p>
            <div class="mt-5 flex flex-wrap items-center gap-2 text-xs text-slate-300">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <i class="fa-solid fa-chevron-right text-[8px]"></i>
                <a href="{{ route('motorcycles.index') }}" class="hover:text-white">Shop</a>
                <i class="fa-solid fa-chevron-right text-[8px]"></i>
                <a href="{{ route('motorcycles.index', ['brand' => $motorcycle->brand_id]) }}" class="hover:text-white">{{ $motorcycle->brand->name }}</a>
                <i class="fa-solid fa-chevron-right text-[8px]"></i>
                <span class="text-white font-semibold">{{ $motorcycle->model_name }}</span>
            </div>
        </div>
    </div>
</section>

{{-- Main product content: gallery + info --}}
<main id="product" class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 gap-10 lg:grid-cols-2">

        {{-- Gallery --}}
        <section>
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-3 shadow-sm">
                @if($motorcycle->is_featured)
                <span class="absolute left-5 top-5 z-10 rounded-full bg-brand-600 px-3 py-1 text-[10px] font-bold text-white">FEATURED</span>
                @endif
                @if($motorcycle->availability_status === 'available')
                <span class="absolute right-5 top-5 z-10 rounded-full bg-emerald-600/90 px-3 py-1 text-[10px] font-bold text-white">
                    <i class="fa-solid fa-circle-check mr-1"></i> Certified
                </span>
                @endif

                <div class="relative aspect-[4/3] overflow-hidden rounded-xl bg-slate-100">
                    @php
                        $imgs = array_values(array_filter([
                            $motorcycle->image_url,
                            ...($motorcycle->additional_images ?? [])
                        ]));
                        if (empty($imgs)) $imgs = ['https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=1400&q=90'];
                    @endphp
                    <img id="mainImage" class="h-full w-full object-cover transition-opacity duration-300"
                         src="{{ $imgs[0] }}" alt="{{ $motorcycle->model_name }}" />
                    @if(count($imgs) > 1)
                    <button id="prevImage" class="absolute left-3 top-1/2 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-slate-900/60 text-white hover:bg-brand-600 transition" aria-label="Previous">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <button id="nextImage" class="absolute right-3 top-1/2 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-slate-900/60 text-white hover:bg-brand-600 transition" aria-label="Next">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                    <span id="imageCounter" class="absolute bottom-3 right-3 rounded-full bg-slate-900/70 px-3 py-1 text-xs font-medium text-white">1 / {{ count($imgs) }}</span>
                    @endif
                </div>
            </div>

            @if(count($imgs) > 1)
            <div id="thumbnails" class="mt-4 grid grid-cols-4 gap-3">
                @foreach($imgs as $i => $img)
                <button class="thumb {{ $i === 0 ? 'active border-brand-500' : 'border-transparent hover:border-brand-400' }} overflow-hidden rounded-xl border-2 bg-white p-1 shadow-sm" data-index="{{ $i }}">
                    <img class="aspect-[4/3] w-full rounded-lg object-cover" src="{{ $img }}" alt="View {{ $i+1 }}" />
                </button>
                @endforeach
            </div>
            @endif
        </section>

        {{-- Product Info --}}
        <section class="flex flex-col">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <span class="rounded-lg bg-brand-50 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-brand-700">
                    {{ strtoupper($motorcycle->brand->name) }} Official
                </span>
                <div class="flex items-center gap-1.5 text-sm">
                    <span class="text-amber-400">★★★★☆</span>
                    <span class="text-slate-500"><strong class="text-slate-800">4.0</strong></span>
                </div>
            </div>

            <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">{{ $motorcycle->model_name }}</h2>

            @if($motorcycle->short_description || $motorcycle->description)
            <p class="mt-3 text-sm leading-relaxed text-slate-600">
                {{ $motorcycle->short_description ?: \Illuminate\Support\Str::limit($motorcycle->description, 200) }}
            </p>
            @endif

            <div class="mt-6 flex flex-wrap items-end gap-3">
                @if($motorcycle->price)
                <span class="text-4xl font-extrabold tracking-tight text-slate-950">${{ number_format($motorcycle->price) }}</span>
                <span class="pb-1 text-sm text-slate-500">{{ $motorcycle->currency ?: 'USD' }} (Tax Included)</span>
                @endif
                <span class="ml-auto rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                    ✓ {{ ucfirst($motorcycle->availability_status ?? 'Available') }}
                </span>
            </div>

            {{-- Specs --}}
            <div class="mt-7 grid grid-cols-2 gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                @if($motorcycle->engine_capacity)
                <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                        <i class="fa-solid fa-gauge-high"></i>
                    </span>
                    <div>
                        <small class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Engine</small>
                        <strong class="text-sm text-slate-900">{{ number_format($motorcycle->engine_capacity) }} CC</strong>
                    </div>
                </div>
                @endif
                @if($motorcycle->year)
                <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                        <i class="fa-regular fa-calendar"></i>
                    </span>
                    <div>
                        <small class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Year</small>
                        <strong class="text-sm text-slate-900">{{ $motorcycle->year }}</strong>
                    </div>
                </div>
                @endif
                @if($motorcycle->category)
                <div class="flex items-center gap-3 pt-1">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                        <i class="fa-solid fa-motorcycle"></i>
                    </span>
                    <div>
                        <small class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Category</small>
                        <strong class="text-sm text-slate-900">{{ ucfirst($motorcycle->category) }}</strong>
                    </div>
                </div>
                @endif
                @if($motorcycle->condition)
                <div class="flex items-center gap-3 pt-1">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                        <i class="fa-solid fa-shield-halved"></i>
                    </span>
                    <div>
                        <small class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Condition</small>
                        <strong class="text-sm text-slate-900">{{ ucfirst($motorcycle->condition) }}</strong>
                    </div>
                </div>
                @endif
            </div>

            {{-- CTAs --}}
            <div class="mt-6 space-y-3">
                @if($motorcycle->availability_status !== 'sold')
                {{-- Hidden form — submitted only after modal confirmation --}}
                <form id="addToCartForm" method="POST" action="{{ route('cart.add', $motorcycle) }}">
                    @csrf
                </form>
                {{-- Trigger button — opens confirmation modal --}}
                <button type="button" id="addToCartBtn"
                        onclick="openCartModal()"
                        class="flex w-full items-center justify-center gap-3 rounded-xl bg-slate-900 px-5 py-4 text-sm font-bold text-white transition hover:bg-brand-700">
                    <i class="fa-solid fa-cart-plus"></i> Add to Cart to Order
                </button>
                @endif
                @if($motorcycle->rental_price_per_day > 0 && $motorcycle->availability_status !== 'sold')
                <details class="rounded-xl border border-slate-200 bg-white p-4">
                    <summary class="cursor-pointer font-bold text-slate-800">
                        Rent for {{ $motorcycle->currency ?: 'USD' }} {{ number_format($motorcycle->rental_price_per_day, 2) }} per day
                    </summary>
                    <form method="POST" action="{{ route('rentals.store', $motorcycle) }}" class="mt-4 space-y-3">
                        @csrf
                        <label class="block text-xs font-semibold text-slate-600">Full name
                            <input name="name" required maxlength="100" value="{{ old('name', auth()->user()->name ?? '') }}" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2">
                        </label>
                        <label class="block text-xs font-semibold text-slate-600">Email
                            <input type="email" name="email" required maxlength="150" value="{{ old('email', auth()->user()->email ?? '') }}" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2">
                        </label>
                        <label class="block text-xs font-semibold text-slate-600">Phone
                            <input name="phone" required maxlength="30" value="{{ old('phone', auth()->user()->phone ?? '') }}" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2">
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="block text-xs font-semibold text-slate-600">Pickup date
                                <input type="date" name="rental_start_date" required min="{{ now()->toDateString() }}" value="{{ old('rental_start_date') }}" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2">
                            </label>
                            <label class="block text-xs font-semibold text-slate-600">Return date
                                <input type="date" name="rental_end_date" required min="{{ now()->toDateString() }}" value="{{ old('rental_end_date') }}" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2">
                            </label>
                        </div>
                        <p class="text-xs text-slate-500">The final rental price is {{ $motorcycle->currency ?: 'USD' }} {{ number_format($motorcycle->rental_price_per_day, 2) }} per day. This request is not a confirmed booking or payment.</p>
                        <button class="w-full rounded-lg bg-brand-600 px-4 py-3 text-sm font-bold text-white hover:bg-brand-700">Request Rental</button>
                    </form>
                </details>
                @endif
                <button id="bookButton"
                        class="flex w-full items-center justify-center gap-3 rounded-xl bg-gradient-to-r from-brand-600 to-brand-500 px-5 py-4 text-sm font-bold text-white shadow-lg shadow-brand-500/25 transition hover:-translate-y-0.5 hover:from-brand-700 hover:to-brand-600">
                    <i class="fa-solid fa-key"></i>
                    Book a Test Ride
                    <span class="text-brand-200 text-xs">(حجز تجربة قيادة)</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('motorcycles.index') }}"
                       class="flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-3.5 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition">
                        <i class="fa-solid fa-arrow-left"></i> رجوع للمتجر
                    </a>
                    <a href="{{ route('contact', ['motorcycle_id' => $motorcycle->id]) }}"
                            class="flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-3 py-3.5 text-xs font-bold text-white hover:bg-brand-700 transition">
                        <i class="fa-regular fa-comments"></i> Contact Seller
                    </a>
                </div>
            </div>

            {{-- Trust badges --}}
            <div class="mt-6 grid grid-cols-3 gap-2 border-t border-slate-100 pt-5 text-center">
                <div class="flex flex-col items-center gap-1.5">
                    <i class="fa-solid fa-truck-fast text-brand-600"></i>
                    <span class="text-[10px] font-medium text-slate-500 leading-tight">Free Delivery<br>Nationwide</span>
                </div>
                <div class="flex flex-col items-center gap-1.5">
                    <i class="fa-solid fa-shield-halved text-brand-600"></i>
                    <span class="text-[10px] font-medium text-slate-500 leading-tight">30-Day<br>Money Back</span>
                </div>
                <div class="flex flex-col items-center gap-1.5">
                    <i class="fa-solid fa-headset text-brand-600"></i>
                    <span class="text-[10px] font-medium text-slate-500 leading-tight">24/7<br>Support</span>
                </div>
            </div>
        </section>
    </div>

    {{-- Full Description --}}
    @if($motorcycle->description)
    <section class="mt-10 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="mb-3 text-base font-extrabold text-slate-900">الوصف الكامل</h3>
        <p class="text-sm leading-7 text-slate-600">{{ $motorcycle->description }}</p>
    </section>
    @endif

    {{-- Benefits section --}}
    <section id="benefits" class="mt-12 grid grid-cols-2 gap-4 rounded-2xl border border-brand-100 bg-gradient-to-r from-brand-50 via-white to-indigo-50 p-5 sm:grid-cols-4 sm:p-6">
        @foreach([
            ['fa-shield-halved','Certified & Inspected','Quality you can trust'],
            ['fa-award','Full Warranty','Peace of mind'],
            ['fa-headset','Expert Support','Real people, real help'],
            ['fa-star','Trusted Dealership','Your next ride starts here'],
        ] as [$icon,$title,$sub])
        <div class="flex items-center gap-3">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand-600 text-white">
                <i class="fa-solid {{ $icon }}"></i>
            </span>
            <div>
                <strong class="block text-xs text-slate-900">{{ $title }}</strong>
                <small class="text-[10px] text-slate-500">{{ $sub }}</small>
            </div>
        </div>
        @endforeach
    </section>

    {{-- Related motorcycles --}}
    @if($related->isNotEmpty())
    <section id="similar" class="mt-16">
        <div class="mb-7 flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-950">Similar Motorcycles from {{ $motorcycle->brand->name }}</h2>
                <p class="mt-1.5 text-sm text-slate-500">Explore high-performance models in the same category</p>
            </div>
            <a href="{{ route('motorcycles.index', ['brand' => $motorcycle->brand_id]) }}"
               class="text-sm font-semibold text-brand-600 hover:text-brand-800 transition">
                View All Similar <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($related as $r)
            <article class="product-card overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl hover:border-brand-300">
                <div class="relative overflow-hidden bg-slate-100">
                    <img class="aspect-[4/3] w-full object-cover"
                         src="{{ $r->image_url ?: 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=700&q=85' }}"
                         alt="{{ $r->model_name }}" loading="lazy" />
                    @if($r->engine_capacity)
                    <span class="absolute left-3 top-3 rounded-full bg-brand-600 px-2.5 py-1 text-[9px] font-bold text-white">
                        {{ number_format($r->engine_capacity) }} CC
                    </span>
                    @endif
                    <button class="cardHeart absolute right-3 top-3 flex h-8 w-8 items-center justify-center rounded-full bg-white/90 text-slate-600 shadow hover:bg-brand-600 hover:text-white transition">
                        <i class="fa-regular fa-heart"></i>
                    </button>
                </div>
                <div class="p-4">
                    <span class="text-[9px] font-bold uppercase tracking-wider text-brand-600">{{ $r->brand->name }}</span>
                    <h3 class="mt-1 font-bold text-slate-900">{{ $r->model_name }}</h3>
                    <div class="mt-3 flex items-center justify-between">
                        @if($r->price)
                        <strong class="text-lg text-slate-900">${{ number_format($r->price) }}</strong>
                        @else
                        <span class="text-sm text-slate-500">On request</span>
                        @endif
                        <a href="{{ route('motorcycles.show', $r->slug) }}"
                           class="rounded-lg bg-slate-100 px-3 py-2 text-[10px] font-bold text-slate-700 hover:bg-brand-100 hover:text-brand-700 transition">
                            View →
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </section>
    @endif
</main>

@include('partials.footer')

{{-- ══════════════════════════════════════════════
     ADD TO CART CONFIRMATION MODAL
══════════════════════════════════════════════ --}}
<div id="cartConfirmModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
     role="dialog" aria-modal="true" aria-labelledby="cartModalHeading">

    <div class="relative w-full max-w-md rounded-2xl border border-slate-200 bg-white shadow-2xl overflow-hidden"
         style="animation: cartModalIn .3s cubic-bezier(.16,1,.3,1) both;">

        {{-- Top accent bar --}}
        <div class="h-1 w-full bg-gradient-to-r from-brand-500 to-indigo-500"></div>

        {{-- Header --}}
        <div class="flex items-start gap-4 px-6 pt-6 pb-5 border-b border-slate-100">
            {{-- Motorcycle image --}}
            <div class="flex-shrink-0">
                @if($motorcycle->image_url)
                    <img src="{{ $motorcycle->image_url }}" alt="{{ $motorcycle->model_name }}"
                         class="h-16 w-20 rounded-xl object-cover border border-slate-200 shadow-sm">
                @else
                    <div class="h-16 w-20 rounded-xl bg-brand-50 border border-brand-100 flex items-center justify-center">
                        <i class="fa-solid fa-motorcycle text-brand-400 text-xl"></i>
                    </div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-[10px] font-bold uppercase tracking-widest text-brand-600 mb-1">{{ $motorcycle->brand->name }}</p>
                <h2 id="cartModalHeading" class="text-base font-extrabold text-slate-900 leading-tight truncate">
                    {{ $motorcycle->model_name }}
                </h2>
                @if($motorcycle->price)
                <p class="mt-1 text-sm font-bold text-slate-700">
                    {{ $motorcycle->currency ?: 'USD' }} {{ number_format($motorcycle->price) }}
                </p>
                @endif
            </div>
            <button type="button" onclick="closeCartModal()"
                    class="flex-shrink-0 h-8 w-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        {{-- Body --}}
        <div class="px-6 py-5">
            {{-- Cart icon + message --}}
            <div class="flex flex-col items-center text-center mb-6">
                <div class="w-16 h-16 rounded-full bg-brand-50 border-2 border-brand-100 flex items-center justify-content center mb-3 flex items-center justify-center">
                    <i class="fa-solid fa-cart-shopping text-2xl text-brand-600"></i>
                </div>
                <h3 class="text-lg font-extrabold text-slate-900">Add to Cart?</h3>
                <p class="mt-1.5 text-sm text-slate-500 leading-relaxed max-w-xs">
                    This motorcycle will be added to your cart. You can review it before placing your order enquiry.
                </p>
            </div>

            {{-- Info note --}}
            <div class="flex items-start gap-2.5 rounded-xl bg-amber-50 border border-amber-100 px-4 py-3 mb-5">
                <i class="fa-solid fa-circle-info text-amber-500 mt-0.5 flex-shrink-0 text-sm"></i>
                <p class="text-xs text-amber-700 leading-relaxed">
                    No payment is taken. This is a <strong>purchase enquiry</strong> — our team will contact you to confirm availability.
                </p>
            </div>

            {{-- Action buttons --}}
            <div class="flex gap-3">
                <button type="button" onclick="closeCartModal()"
                        class="flex-1 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                    Cancel
                </button>
                <button type="button" id="cartConfirmBtn" onclick="confirmAddToCart()"
                        class="flex-1 flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-3 text-sm font-bold text-white hover:bg-brand-700 transition">
                    <i class="fa-solid fa-cart-plus"></i>
                    Yes, Add to Cart
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes cartModalIn {
    from { opacity: 0; transform: scale(.92) translateY(16px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}
</style>

<script>
function openCartModal() {
    var m = document.getElementById('cartConfirmModal');
    m.classList.remove('hidden');
    m.classList.add('flex');
    document.body.style.overflow = 'hidden';
}
function closeCartModal() {
    var m = document.getElementById('cartConfirmModal');
    m.classList.add('hidden');
    m.classList.remove('flex');
    document.body.style.overflow = '';
}
function confirmAddToCart() {
    var btn = document.getElementById('cartConfirmBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Adding…';
    document.getElementById('addToCartForm').submit();
}
// Close on backdrop click
document.getElementById('cartConfirmModal').addEventListener('click', function(e) {
    if (e.target === this) closeCartModal();
});
// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeCartModal();
});
</script>

{{-- ══════════════════════════════════════════════
     TEST RIDE BOOKING MODAL
     Collects: name, email, phone, preferred_date,
     preferred_time, showroom, notes
     POSTs to test-ride.store (TestRideController)
══════════════════════════════════════════════ --}}
<div id="modal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/70 p-3 backdrop-blur-sm"
     role="dialog" aria-modal="true" aria-labelledby="modalHeading">

    {{-- Overlay backdrop click closes modal --}}
    <div id="modalBackdrop" class="absolute inset-0"></div>

    <div class="relative z-10 w-full max-w-lg max-h-[90vh] overflow-y-auto rounded-2xl border border-slate-200 bg-white shadow-2xl">

        {{-- ── Modal Header ── --}}
        <div class="flex items-start justify-between gap-4 border-b border-slate-100 px-6 py-5">
            <div class="flex items-center gap-3">
                {{-- Motorcycle thumbnail --}}
                <div class="relative flex-shrink-0">
                    @if($motorcycle->image_url)
                        <img src="{{ $motorcycle->image_url }}" alt="{{ $motorcycle->model_name }}"
                             class="h-12 w-16 rounded-xl object-cover border border-slate-200">
                    @else
                        <div class="h-12 w-16 rounded-xl bg-brand-50 border border-brand-100 flex items-center justify-center">
                            <i class="fa-solid fa-motorcycle text-brand-500"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <p class="text-[9px] font-bold uppercase tracking-[0.22em] text-brand-600">Book a Test Ride</p>
                    <h2 id="modalHeading" class="text-base font-extrabold text-slate-900 leading-tight">
                        {{ $motorcycle->model_name }}
                    </h2>
                    <p class="text-[11px] text-slate-500 mt-0.5">{{ $motorcycle->brand->name }} · {{ $motorcycle->year }}</p>
                </div>
            </div>
            <button id="closeModal" type="button" aria-label="Close"
                    class="flex-shrink-0 flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        {{-- ── Success state (shown after booking) ── --}}
        <div id="bookingSuccess" class="hidden px-6 py-10 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-50 border-2 border-emerald-200">
                <i class="fa-solid fa-circle-check text-2xl text-emerald-500"></i>
            </div>
            <h3 class="text-lg font-extrabold text-slate-900">Request Sent!</h3>
            <p class="mt-2 text-sm text-slate-500">Your test ride request has been received. We'll confirm your slot shortly.</p>
            <div id="bookingRefWrap" class="mt-4 hidden">
                <p class="text-xs font-semibold text-slate-500 mb-1">Your booking reference:</p>
                <span id="bookingRef" class="inline-block rounded-lg bg-brand-50 border border-brand-200 px-4 py-2 font-mono text-sm font-bold text-brand-700"></span>
            </div>
            <p class="mt-3 text-xs text-slate-400">Status: <strong class="text-amber-600">Pending — not yet confirmed.</strong></p>
            <button onclick="closeModal()" class="mt-6 rounded-xl border border-slate-200 px-6 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                Close
            </button>
        </div>

        {{-- ── Booking form ── --}}
        <form id="testRideForm" method="POST" action="{{ route('test-ride.store') }}"
              class="px-6 py-5 space-y-4" novalidate>
            @csrf
            <input type="hidden" name="motorcycle_id" value="{{ $motorcycle->id }}">

            {{-- Price strip --}}
            @if($motorcycle->price)
            <div class="flex items-center justify-between rounded-xl bg-slate-50 border border-slate-200 px-4 py-3">
                <span class="text-xs font-semibold text-slate-500">Motorcycle Price</span>
                <span class="text-sm font-extrabold text-slate-900">${{ number_format($motorcycle->price) }}</span>
            </div>
            @endif

            {{-- Info note --}}
            <div class="flex items-start gap-2.5 rounded-xl bg-brand-50 border border-brand-100 px-4 py-3">
                <i class="fa-solid fa-circle-info text-brand-500 mt-0.5 flex-shrink-0 text-sm"></i>
                <p class="text-xs text-brand-700 leading-relaxed">
                    This is a <strong>test ride request</strong>, not a purchase. Your booking will be reviewed and confirmed by our team.
                </p>
            </div>

            {{-- Name + Phone --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        <input type="text" name="name" id="trName"
                               value="{{ auth()->user()->name ?? '' }}"
                               required maxlength="120"
                               placeholder="Your full name"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-9 pr-3 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-500/10">
                    </div>
                    <p class="trErr mt-1 hidden text-xs text-red-500 font-medium" data-for="trName"></p>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                        Phone <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fa-solid fa-phone absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        <input type="tel" name="phone" id="trPhone"
                               value="{{ auth()->user()->phone ?? '' }}"
                               required maxlength="30"
                               placeholder="+20 1xx xxx xxxx"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-9 pr-3 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-500/10">
                    </div>
                    <p class="trErr mt-1 hidden text-xs text-red-500 font-medium" data-for="trPhone"></p>
                </div>
            </div>

            {{-- Email --}}
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                    Email Address <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <i class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    <input type="email" name="email" id="trEmail"
                           value="{{ auth()->user()->email ?? '' }}"
                           required maxlength="150"
                           placeholder="you@example.com"
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-9 pr-3 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-500/10">
                </div>
                <p class="trErr mt-1 hidden text-xs text-red-500 font-medium" data-for="trEmail"></p>
            </div>

            {{-- Date + Time --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                        Preferred Date
                    </label>
                    <div class="relative">
                        <i class="fa-regular fa-calendar absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        <input type="date" name="preferred_date" id="trDate"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-9 pr-3 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white">
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                        Preferred Time
                    </label>
                    <div class="relative">
                        <i class="fa-regular fa-clock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        <select name="preferred_time"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 pl-9 pr-3 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white appearance-none">
                            <option value="">Any time</option>
                            <option>09:00 – 10:00</option>
                            <option>10:00 – 11:00</option>
                            <option>11:00 – 12:00</option>
                            <option>12:00 – 13:00</option>
                            <option>13:00 – 14:00</option>
                            <option>14:00 – 15:00</option>
                            <option>15:00 – 16:00</option>
                            <option>16:00 – 17:00</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate-700">Notes (optional)</label>
                <textarea name="notes" rows="2" maxlength="1000" placeholder="Any specific requests or questions…"
                          class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white resize-none"></textarea>
            </div>

            {{-- Submit --}}
            <div class="border-t border-slate-100 pt-4">
                <button type="submit" id="trSubmit"
                        class="flex w-full items-center justify-center gap-2.5 rounded-xl bg-gradient-to-r from-brand-600 to-brand-500 py-3.5 text-sm font-bold text-white shadow-lg shadow-brand-500/25 transition hover:from-brand-700 hover:to-brand-600 disabled:opacity-60 disabled:cursor-not-allowed">
                    <i class="fa-solid fa-motorcycle"></i>
                    <span id="trSubmitText">Submit Test Ride Request</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
                <p class="mt-2.5 text-center text-[10px] text-slate-400">
                    <i class="fa-solid fa-lock text-[9px]"></i>
                    Secure submission · Your data is never shared · Booking status will be <strong>Pending</strong> until confirmed
                </p>
            </div>

            {{-- Inline error banner (server-side fallback) --}}
            @if($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3">
                <p class="text-xs font-semibold text-red-700 mb-1"><i class="fa-solid fa-circle-exclamation mr-1"></i> Please fix the following:</p>
                <ul class="list-disc list-inside text-xs text-red-600 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </form>

    </div>
</div>

{{-- ══════════════════════════════════════════════
     BOOKING CONFIRMATION BANNER (after redirect)
══════════════════════════════════════════════ --}}
@if(session('booking_success'))
<div id="bookingBanner"
     class="fixed bottom-5 left-1/2 z-[70] -translate-x-1/2 w-full max-w-sm rounded-2xl bg-white border border-emerald-200 shadow-2xl shadow-emerald-100/50 p-4">
    <div class="flex items-start gap-3">
        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-emerald-50 border border-emerald-200">
            <i class="fa-solid fa-circle-check text-emerald-500"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-bold text-slate-900">Test Ride Request Sent!</p>
            <p class="text-xs text-slate-500 mt-0.5">Ref: <span class="font-mono font-bold text-brand-600">{{ session('booking_success')['reference'] }}</span></p>
            <p class="text-xs text-amber-600 font-semibold mt-1">Status: Pending — awaiting confirmation.</p>
        </div>
        <button onclick="document.getElementById('bookingBanner').remove()"
                class="flex-shrink-0 text-slate-400 hover:text-slate-600 transition">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>
    </div>
</div>
<script>setTimeout(()=>{ const b=document.getElementById('bookingBanner'); if(b){ b.style.transition='opacity .4s'; b.style.opacity='0'; setTimeout(()=>b.remove(),400); }}, 7000);</script>
@endif

{{-- Toast notification --}}
<div id="toast" class="pointer-events-none fixed bottom-6 left-1/2 z-[60] -translate-x-1/2 translate-y-4 rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white opacity-0 shadow-xl transition-all duration-300" role="status"></div>

<script>
    // ── Gallery ──────────────────────────────────────────────────────────────
    const gallery = @json($imgs);
    let activeImage = 0, toastTimer;
    const mainImage = document.getElementById('mainImage');
    const modal     = document.getElementById('modal');

    function toast(msg, dur = 2600) {
        const el = document.getElementById('toast');
        el.textContent = msg;
        el.classList.remove('opacity-0', 'translate-y-4');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => el.classList.add('opacity-0', 'translate-y-4'), dur);
    }

    function setImage(idx) {
        activeImage = (idx + gallery.length) % gallery.length;
        mainImage.src = gallery[activeImage];
        const counter = document.getElementById('imageCounter');
        if (counter) counter.textContent = `${activeImage + 1} / ${gallery.length}`;
        document.querySelectorAll('.thumb').forEach((t, i) => {
            t.classList.toggle('active',          i === activeImage);
            t.classList.toggle('border-brand-500', i === activeImage);
            t.classList.toggle('border-transparent', i !== activeImage);
        });
    }

    document.querySelectorAll('.thumb').forEach(btn => btn.addEventListener('click', () => setImage(+btn.dataset.index)));
    document.getElementById('prevImage')?.addEventListener('click', () => setImage(activeImage - 1));
    document.getElementById('nextImage')?.addEventListener('click', () => setImage(activeImage + 1));

    document.querySelectorAll('.cardHeart').forEach(btn => {
        btn.addEventListener('click', () => {
            const icon = btn.querySelector('i');
            const wasActive = icon.classList.contains('fa-solid');
            icon.classList.toggle('fa-solid', !wasActive);
            icon.classList.toggle('fa-regular', wasActive);
            icon.classList.toggle('text-pink-500', !wasActive);
            toast(wasActive ? 'Removed from wishlist' : 'Added to wishlist ♥');
        });
    });

    // ── Modal open/close ─────────────────────────────────────────────────────
    function openModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        // Reset to form state if previously shown success
        document.getElementById('bookingSuccess').classList.add('hidden');
        document.getElementById('testRideForm').classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.getElementById('bookButton')?.addEventListener('click', openModal);
    document.getElementById('closeModal')?.addEventListener('click', closeModal);
    document.getElementById('modalBackdrop')?.addEventListener('click', closeModal);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

    // Set min date to today
    const dateInput = document.getElementById('trDate');
    if (dateInput) dateInput.min = new Date().toISOString().slice(0, 10);

    // ── AJAX form submission ─────────────────────────────────────────────────
    const form     = document.getElementById('testRideForm');
    const submitBtn = document.getElementById('trSubmit');
    const submitTxt = document.getElementById('trSubmitText');

    // Client-side validation helpers
    function showErr(id, msg) {
        const el = document.querySelector(`.trErr[data-for="${id}"]`);
        if (el) { el.textContent = msg; el.classList.remove('hidden'); }
        document.getElementById(id)?.classList.add('border-red-400', 'bg-red-50');
    }
    function clearErrs() {
        document.querySelectorAll('.trErr').forEach(e => e.classList.add('hidden'));
        document.querySelectorAll('#testRideForm input').forEach(i => {
            i.classList.remove('border-red-400', 'bg-red-50');
        });
    }
    function validateForm() {
        clearErrs();
        let ok = true;
        const name  = document.getElementById('trName')?.value.trim();
        const phone = document.getElementById('trPhone')?.value.trim();
        const email = document.getElementById('trEmail')?.value.trim();
        if (!name)                               { showErr('trName',  'Full name is required.'); ok = false; }
        if (!phone)                              { showErr('trPhone', 'Phone number is required.'); ok = false; }
        if (!email || !/\S+@\S+\.\S+/.test(email)) { showErr('trEmail', 'A valid email is required.'); ok = false; }
        return ok;
    }

    form?.addEventListener('submit', async function (e) {
        e.preventDefault();
        if (!validateForm()) return;

        // Prevent double-submission
        submitBtn.disabled = true;
        submitTxt.textContent = 'Sending…';

        const fd = new FormData(form);

        try {
            const res  = await fetch(form.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                body: fd,
            });
            const json = await res.json();

            if (json.ok) {
                // Show success panel inside modal
                form.classList.add('hidden');
                const successPanel = document.getElementById('bookingSuccess');
                successPanel.classList.remove('hidden');

                if (json.reference) {
                    document.getElementById('bookingRef').textContent = json.reference;
                    document.getElementById('bookingRefWrap').classList.remove('hidden');
                }
                // Reset form for next use
                form.reset();
            } else {
                // Server returned errors
                const errs = json.errors || {};
                const map  = { name: 'trName', phone: 'trPhone', email: 'trEmail' };
                Object.entries(errs).forEach(([field, msgs]) => {
                    const elId = map[field];
                    if (elId) showErr(elId, Array.isArray(msgs) ? msgs[0] : msgs);
                });
                if (!Object.keys(errs).length) toast('Something went wrong. Please try again.', 3500);
            }
        } catch (err) {
            toast('Network error. Please try again.', 3500);
        } finally {
            submitBtn.disabled = false;
            submitTxt.textContent = 'Submit Test Ride Request';
        }
    });

    // ── Auto-open modal if validation errors returned from server ────────────
    @if($errors->any())
        openModal();
    @endif

    @if(session('success'))
        toast("{{ session('success') }}");
    @endif
</script>
</body>
</html>
