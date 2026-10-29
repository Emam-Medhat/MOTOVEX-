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
                    <button id="contactButton"
                            class="flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-3 py-3.5 text-xs font-bold text-white hover:bg-brand-700 transition">
                        <i class="fa-regular fa-comments"></i> Contact Seller
                    </button>
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

{{-- Test ride modal --}}
<div id="modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm" role="dialog" aria-modal="true">
    <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl sm:p-8">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-brand-600">MOTOVEX Concierge</p>
                <h2 id="modalTitle" class="mt-2 text-2xl font-extrabold text-slate-900">Book a Test Ride</h2>
                <p id="modalDescription" class="mt-2 text-sm text-slate-500">Leave your details and preferred date.</p>
            </div>
            <button id="closeModal" class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 hover:bg-slate-200 transition text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('test-ride.store') }}" class="mt-6 space-y-4">
            @csrf
            <input type="hidden" name="motorcycle_id" value="{{ $motorcycle->id }}">
            <input type="hidden" name="enquiry_type" value="test_drive">
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate-700">Full name</label>
                <input required name="name" value="{{ auth()->user()->name ?? '' }}"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-brand-600" placeholder="Your name" />
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate-700">Email address</label>
                <input required type="email" name="email" value="{{ auth()->user()->email ?? '' }}"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-brand-600" placeholder="you@example.com" />
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate-700">Phone number</label>
                <input required type="tel" name="phone"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-brand-600" placeholder="+20 ..." />
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-slate-700">Preferred date</label>
                <input type="date" name="preferred_date" id="preferredDate"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none focus:border-brand-600" />
            </div>
            <button type="submit" class="w-full rounded-xl bg-brand-600 py-3.5 text-sm font-bold text-white hover:bg-brand-700 transition">
                <i class="fa-solid fa-paper-plane mr-2"></i> Submit Request
            </button>
        </form>
    </div>
</div>

{{-- Toast notification --}}
<div id="toast" class="pointer-events-none fixed bottom-6 left-1/2 z-[60] -translate-x-1/2 translate-y-4 rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white opacity-0 shadow-xl transition-all" role="status"></div>

<script>
    const gallery = @json($imgs);
    let activeImage = 0, toastTimer;
    const mainImage = document.getElementById('mainImage');
    const modal = document.getElementById('modal');

    function toast(message) {
        const el = document.getElementById('toast');
        el.textContent = message;
        el.classList.remove('opacity-0','translate-y-4');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => el.classList.add('opacity-0','translate-y-4'), 2400);
    }

    function setImage(index) {
        activeImage = (index + gallery.length) % gallery.length;
        mainImage.src = gallery[activeImage];
        const counter = document.getElementById('imageCounter');
        if (counter) counter.textContent = `${activeImage + 1} / ${gallery.length}`;
        document.querySelectorAll('.thumb').forEach((t, i) => {
            t.classList.toggle('active', i === activeImage);
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
            const active = icon.classList.contains('fa-solid');
            icon.classList.toggle('fa-solid', !active);
            icon.classList.toggle('fa-regular', active);
            icon.classList.toggle('text-pink-500', !active);
            toast(active ? 'Removed from wishlist' : 'Added to wishlist');
        });
    });

    function openModal(type) {
        document.getElementById('modalTitle').textContent = type === 'Contact' ? 'Contact the Seller' : 'Book a Test Ride';
        document.getElementById('modalDescription').textContent =
            type === 'Contact' ? 'Leave your details and question about this motorcycle.' : 'Choose your preferred date and leave your contact details.';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.getElementById('bookButton').addEventListener('click', () => openModal('Test Ride'));
    document.getElementById('contactButton').addEventListener('click', () => openModal('Contact'));
    document.getElementById('closeModal').addEventListener('click', closeModal);
    modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
    document.getElementById('preferredDate').min = new Date().toISOString().slice(0, 10);

    @if(session('success'))
        toast("{{ session('success') }}");
    @endif
</script>
</body>
</html>
