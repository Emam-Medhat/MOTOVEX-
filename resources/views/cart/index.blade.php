@extends('layouts.app')

@section('title', 'Your Cart | MOTOVEX')

{{-- Page-scoped styles — only loaded on the cart page. --}}
@push('head')
<style>
    .mvx-cart {
        background: #F5F7FC;
        color: #172033;
        min-height: 60vh;
        padding-bottom: 72px;
    }
    .mvx-shell { max-width: 80rem; margin: 0 auto; padding: 32px 20px 0; }
    @media (min-width: 1024px) { .mvx-shell { padding-left: 32px; padding-right: 32px; } }

    /* ── Header ─────────────────────────────────────────────── */
    .mvx-head {
        display: flex; flex-wrap: wrap; align-items: center; gap: 18px;
        justify-content: space-between;
        background: #fff; border: 1px solid #E2E8F0; border-radius: 16px;
        padding: 20px 22px; box-shadow: 0 1px 2px rgba(15,23,42,.04);
    }
    .mvx-head-main { display: flex; align-items: center; gap: 14px; min-width: 0; }
    .mvx-head-icon {
        width: 46px; height: 46px; flex-shrink: 0; border-radius: 13px;
        display: grid; place-items: center; font-size: 19px;
        background: #F5F3FF; color: #7C3AED; border: 1px solid #E9D5FF;
    }
    .mvx-head h1 {
        font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
        font-size: 25px; font-weight: 800; letter-spacing: -.5px; line-height: 1.15; color: #0F172A;
        display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
    }
    .mvx-head p { margin-top: 3px; font-size: 13px; color: #64748B; }
    .mvx-count-pill {
        font-size: 12px; font-weight: 700; letter-spacing: .02em;
        background: #F5F3FF; color: #6D28D9; border: 1px solid #E9D5FF;
        border-radius: 9999px; padding: 3px 11px;
    }
    .mvx-back {
        display: inline-flex; align-items: center; gap: 8px;
        font-size: 13px; font-weight: 600; color: #475569;
        border: 1px solid #E2E8F0; background: #fff; border-radius: 10px;
        padding: 9px 15px; transition: border-color .18s, color .18s, background .18s;
        white-space: nowrap;
    }
    .mvx-back:hover { border-color: #C4B5FD; color: #6D28D9; background: #F5F3FF; }
    .mvx-back i { transition: transform .18s; }
    .mvx-back:hover i { transform: translateX(-3px); }

    /* ── Product card ───────────────────────────────────────── */
    .mvx-item {
        background: #fff; border: 1px solid #E2E8F0; border-radius: 16px;
        padding: 16px; box-shadow: 0 1px 2px rgba(15,23,42,.04);
        transition: border-color .2s, box-shadow .2s;
        display: grid; grid-template-columns: 132px minmax(0, 1fr);
        gap: 16px; align-items: center;
    }
    .mvx-item:hover { border-color: #DDD6FE; box-shadow: 0 8px 22px -14px rgba(76,29,149,.30); }
    @media (min-width: 1024px) {
        .mvx-item { grid-template-columns: 148px minmax(0, 1fr) 232px; gap: 20px; padding: 18px; }
    }

    .mvx-thumb {
        position: relative; display: block; overflow: hidden;
        border-radius: 12px; background: #F1F5F9; border: 1px solid #E2E8F0;
        aspect-ratio: 4 / 3;
    }
    .mvx-thumb img { width: 100%; height: 100%; object-fit: cover; object-position: center; transition: transform .4s cubic-bezier(.16,1,.3,1); }
    .mvx-item:hover .mvx-thumb img { transform: scale(1.04); }
    .mvx-thumb-fallback {
        width: 100%; height: 100%; display: grid; place-items: center;
        background: linear-gradient(140deg, #F8FAFC 0%, #EEF2FF 100%);
        color: #C7C9DC; font-size: 34px;
    }

    .mvx-brand {
        font-size: 10px; font-weight: 800; letter-spacing: .18em; text-transform: uppercase;
        color: #7C3AED;
    }
    .mvx-name {
        margin-top: 4px; font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
        font-size: 17px; font-weight: 700; letter-spacing: -.2px; color: #0F172A; line-height: 1.3;
    }
    .mvx-name a { transition: color .18s; }
    .mvx-name a:hover { color: #6D28D9; }

    .mvx-chips { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 10px; }
    .mvx-chip {
        font-size: 11px; font-weight: 600; color: #475569;
        background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 7px; padding: 3px 8px;
        white-space: nowrap;
    }
    .mvx-stock {
        display: inline-flex; align-items: center; gap: 6px; margin-top: 10px;
        font-size: 11px; font-weight: 700;
        border-radius: 7px; padding: 3px 9px; text-transform: capitalize;
    }
    .mvx-stock i { font-size: 7px; }
    .mvx-stock.ok      { color: #047857; background: #ECFDF5; border: 1px solid #A7F3D0; }
    .mvx-stock.warn    { color: #B45309; background: #FFFBEB; border: 1px solid #FDE68A; }
    .mvx-stock.bad     { color: #B91C1C; background: #FEF2F2; border: 1px solid #FECACA; }
    .mvx-stock.neutral { color: #475569; background: #F8FAFC; border: 1px solid #E2E8F0; }

    /* Buy column */
    .mvx-buy {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 12px;
        grid-column: 1 / -1;
        border-top: 1px dashed #E2E8F0; padding-top: 14px;
    }
    @media (min-width: 1024px) {
        .mvx-buy {
            grid-column: auto; flex-direction: column; align-items: flex-end;
            justify-content: center; gap: 12px; border-top: 0; border-left: 1px solid #F1F5F9;
            padding-top: 0; padding-left: 20px; align-self: stretch;
        }
    }
    .mvx-price { text-align: left; }
    @media (min-width: 1024px) { .mvx-price { text-align: right; } }
    .mvx-price .mvx-unit { display: block; font-size: 17px; font-weight: 800; color: #0F172A; letter-spacing: -.3px; }
    .mvx-price .mvx-unit-label { display: block; font-size: 10.5px; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: #94A3B8; margin-bottom: 2px; }
    .mvx-price .mvx-line { display: block; font-size: 11.5px; color: #64748B; margin-top: 3px; }

    .mvx-controls { display: flex; align-items: center; gap: 10px; }

    /* Quantity stepper (one unit per motorcycle — see CartController::add) */
    .mvx-qty {
        display: inline-flex; align-items: center;
        border: 1px solid #E2E8F0; border-radius: 10px; background: #fff; overflow: hidden;
    }
    .mvx-qty button {
        width: 34px; height: 34px; display: grid; place-items: center;
        background: #fff; color: #334155; border: 0; font-size: 12px;
        transition: background .18s, color .18s;
    }
    .mvx-qty button:hover:not(:disabled) { background: #F5F3FF; color: #6D28D9; }
    .mvx-qty button:disabled { color: #CBD5E1; cursor: not-allowed; }
    .mvx-qty .mvx-qty-val {
        min-width: 36px; height: 34px; line-height: 34px; text-align: center;
        font-size: 13px; font-weight: 700; color: #0F172A;
        border-left: 1px solid #E2E8F0; border-right: 1px solid #E2E8F0;
    }
    /* Page-scoped focus ring (overrides the global green outline). */
    .mvx-cart :focus-visible {
        outline: 2px solid #7C3AED; outline-offset: 2px; border-radius: 4px;
    }
    .mvx-qty-label {
        font-size: 10.5px; color: #94A3B8; margin-top: 5px; text-align: right; line-height: 1.3;
    }

    .mvx-remove {
        width: 34px; height: 34px; display: grid; place-items: center;
        border-radius: 10px; border: 1px solid #E2E8F0; background: #fff;
        color: #94A3B8; font-size: 13px; transition: all .18s;
    }
    .mvx-remove:hover { background: #FEF2F2; border-color: #FECACA; color: #DC2626; }

    /* ── Order summary ──────────────────────────────────────── */
    .mvx-summary {
        background: #0F172A; border-radius: 18px; padding: 24px;
        box-shadow: 0 18px 40px -22px rgba(15,23,42,.55);
        position: relative; overflow: hidden;
    }
    .mvx-summary::after {
        content: ""; position: absolute; top: -70px; right: -70px;
        width: 190px; height: 190px; border-radius: 9999px;
        background: radial-gradient(circle, rgba(124,58,237,.42), transparent 70%);
        pointer-events: none;
    }
    .mvx-summary h2 {
        font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
        font-size: 15px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase;
        color: #fff; position: relative;
    }
    .mvx-summary .mvx-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; font-size: 13.5px; position: relative; }
    .mvx-summary .mvx-row + .mvx-row { margin-top: 12px; }
    .mvx-summary .mvx-k { color: #94A3B8; }
    .mvx-summary .mvx-v { color: #E2E8F0; font-weight: 700; }
    .mvx-summary .mvx-divide { height: 1px; background: rgba(148,163,184,.20); margin: 18px 0; position: relative; }
    .mvx-total-label { font-size: 12.5px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: #CBD5E1; }
    .mvx-total-value { font-size: 27px; font-weight: 800; letter-spacing: -.8px; color: #fff; line-height: 1; }
    .mvx-total-value small { font-size: 12px; font-weight: 600; color: #A78BFA; margin-right: 5px; letter-spacing: 0; }

    .mvx-cta {
        display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%;
        background: #7C3AED; color: #fff; border: 0; border-radius: 12px;
        padding: 14px 20px; font-size: 13.5px; font-weight: 700; letter-spacing: .01em;
        transition: background .18s, transform .18s, box-shadow .18s; position: relative;
    }
    .mvx-cta:hover { background: #6D28D9; transform: translateY(-1px); box-shadow: 0 12px 26px -12px rgba(124,58,237,.85); }
    .mvx-cta i { transition: transform .18s; }
    .mvx-cta:hover i { transform: translateX(3px); }

    .mvx-cta-ghost {
        display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%;
        margin-top: 10px; background: transparent; color: #CBD5E1;
        border: 1px solid rgba(148,163,184,.30); border-radius: 12px; padding: 12px 18px;
        font-size: 13px; font-weight: 600; transition: all .18s; position: relative;
    }
    .mvx-cta-ghost:hover { border-color: #A78BFA; color: #fff; background: rgba(124,58,237,.14); }

    .mvx-note {
        display: flex; gap: 9px; margin-top: 16px; padding-top: 16px;
        border-top: 1px solid rgba(148,163,184,.16);
        font-size: 11.5px; line-height: 1.55; color: #94A3B8; position: relative;
    }
    .mvx-note i { color: #A78BFA; margin-top: 2px; flex-shrink: 0; }

    .mvx-clear {
        display: inline-flex; align-items: center; gap: 7px;
        font-size: 12px; font-weight: 600; color: #94A3B8;
        background: transparent; border: 1px solid #E2E8F0; border-radius: 9px;
        padding: 8px 13px; transition: all .18s;
    }
    .mvx-clear:hover { border-color: #FECACA; background: #FEF2F2; color: #DC2626; }

    /* ── Empty state ────────────────────────────────────────── */
    .mvx-empty {
        margin-top: 24px;
        background: #fff; border: 1px solid #E2E8F0; border-radius: 18px;
        padding: 56px 28px; text-align: center;
        display: flex; flex-direction: column; align-items: center;
    }
    .mvx-empty-art {
        position: relative; width: 108px; height: 108px; border-radius: 9999px;
        display: grid; place-items: center; margin-bottom: 22px;
        background: radial-gradient(circle at 50% 40%, #F5F3FF, #EEF2FF);
        border: 1px solid #E9D5FF; font-size: 42px; color: #7C3AED;
    }
    .mvx-empty-art .mvx-dot { position: absolute; border-radius: 9999px; background: #C4B5FD; }
    .mvx-empty-art .mvx-dot.a { width: 8px; height: 8px; top: 12px; right: 16px; opacity: .8; }
    .mvx-empty-art .mvx-dot.b { width: 5px; height: 5px; bottom: 20px; left: 10px; opacity: .6; }
    .mvx-empty-art .mvx-dot.c { width: 6px; height: 6px; bottom: 8px; right: 26px; opacity: .5; }
    .mvx-empty h2 {
        font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
        font-size: 23px; font-weight: 800; letter-spacing: -.5px; color: #0F172A; max-width: 30ch;
    }
    .mvx-empty p { margin-top: 10px; max-width: 46ch; font-size: 14px; line-height: 1.6; color: #64748B; }
    .mvx-empty-actions { margin-top: 26px; display: flex; flex-wrap: wrap; gap: 12px; justify-content: center; }
    .mvx-empty-cta {
        display: inline-flex; align-items: center; gap: 9px;
        background: #7C3AED; color: #fff; border-radius: 12px;
        padding: 13px 24px; font-size: 13.5px; font-weight: 700;
        transition: background .18s, transform .18s, box-shadow .18s;
    }
    .mvx-empty-cta:hover { background: #6D28D9; transform: translateY(-1px); box-shadow: 0 12px 26px -12px rgba(124,58,237,.85); }
    .mvx-empty-actions .mvx-link {
        display: inline-flex; align-items: center; gap: 8px;
        border: 1px solid #E2E8F0; background: #fff; border-radius: 12px;
        padding: 13px 22px; font-size: 13.5px; font-weight: 600; color: #475569;
        transition: all .18s;
    }
    .mvx-empty-actions .mvx-link:hover { border-color: #C4B5FD; color: #6D28D9; background: #F5F3FF; }

    .mvx-crumbs { display: flex; align-items: center; gap: 8px; font-size: 12px; color: #94A3B8; margin-bottom: 18px; }
    .mvx-crumbs a { transition: color .18s; }
    .mvx-crumbs a:hover { color: #7C3AED; }
    .mvx-crumbs i { font-size: 8px; }
    .mvx-crumbs .is-current { color: #475569; font-weight: 600; }
</style>
@endpush

@section('content')
@php
    $itemCount   = $cartItems->count();
    $cartCurrency = $cartItems->isNotEmpty()
        ? ($cartItems->first()['motorcycle']->currency ?: 'USD')
        : 'USD';
@endphp

<div class="mvx-cart">
    <div class="mvx-shell">

        {{-- Breadcrumb --}}
        <nav class="mvx-crumbs" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
            <a href="{{ route('motorcycles.index') }}">Shop</a>
            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
            <span class="is-current" aria-current="page">Cart</span>
        </nav>

        {{-- ── Header ─────────────────────────────────────────── --}}
        <header class="mvx-head">
            <div class="mvx-head-main">
                <span class="mvx-head-icon" aria-hidden="true"><i class="fa-solid fa-motorcycle"></i></span>
                <div>
                    <h1>
                        Your Cart
                        <span class="mvx-count-pill">{{ $itemCount }} {{ $itemCount === 1 ? 'item' : 'items' }}</span>
                    </h1>
                    <p>Review your selected machines before requesting an order.</p>
                </div>
            </div>
            <a href="{{ route('motorcycles.index') }}" class="mvx-back">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                Continue Shopping
            </a>
        </header>

        @if($cartItems->isEmpty())

            {{-- ── Empty state ────────────────────────────────── --}}
            <div class="mvx-empty">
                <div class="mvx-empty-art" aria-hidden="true">
                    <i class="fa-solid fa-motorcycle"></i>
                    <span class="mvx-dot a"></span>
                    <span class="mvx-dot b"></span>
                    <span class="mvx-dot c"></span>
                </div>
                <h2>Your cart is waiting for your next ride</h2>
                <p>
                    You haven&rsquo;t added any motorcycles yet. Explore our certified lineup and
                    add a machine to your cart to request an order — our team will confirm
                    availability and finalise everything with you.
                </p>
                <div class="mvx-empty-actions">
                    <a href="{{ route('motorcycles.index') }}" class="mvx-empty-cta">
                        <i class="fa-solid fa-motorcycle" aria-hidden="true"></i>
                        Browse Motorcycles
                    </a>
                    <a href="{{ route('brands.index') }}" class="mvx-link">
                        <i class="fa-solid fa-tags" aria-hidden="true"></i>
                        View Brands
                    </a>
                </div>
            </div>

        @else

            <div class="mt-6 grid items-start gap-6 lg:grid-cols-[minmax(0,1fr)_356px] lg:gap-7">

                {{-- ── Items ──────────────────────────────────── --}}
                <section aria-label="Cart items" class="min-w-0">
                    <div class="mb-3 flex items-center justify-between gap-4">
                        <h2 class="text-[13px] font-bold uppercase tracking-[.1em] text-slate-500">Selected Machines</h2>
                        <form method="POST" action="{{ route('cart.clear') }}" data-guard>
                            @csrf @method('DELETE')
                            <button type="submit" class="mvx-clear">
                                <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
                                Clear Cart
                            </button>
                        </form>
                    </div>

                    <div class="space-y-4">
                        @foreach($cartItems as $item)
                            @php
                                $moto   = $item['motorcycle'];
                                $specs  = array_values(array_filter([
                                    $moto->engine_capacity ? number_format((float) $moto->engine_capacity) . ' cc' : null,
                                    $moto->horsepower ? $moto->horsepower . ' hp' : null,
                                    $moto->torque ? $moto->torque : null,
                                    $moto->year ? (string) $moto->year : null,
                                    $moto->category ? ucfirst($moto->category) : null,
                                ]));
                                $brandName = $moto->brand?->name ?? 'MOTOVEX';
                                $unitPrice = (float) ($moto->price ?? 0);
                                $productUrl = route('motorcycles.show', $moto->slug);
                                $stockTone = match ($moto->availability_status) {
                                    'available' => 'ok',
                                    'reserved'  => 'warn',
                                    'sold'      => 'bad',
                                    default     => 'neutral',
                                };
                            @endphp

                            <article class="mvx-item">
                                <a href="{{ $productUrl }}" class="mvx-thumb" aria-label="View {{ $brandName }} {{ $moto->model_name }}">
                                    @if($moto->image_url)
                                        <img src="{{ $moto->image_url }}"
                                             alt="{{ $brandName }} {{ $moto->model_name }}"
                                             loading="lazy" decoding="async">
                                    @else
                                        <span class="mvx-thumb-fallback" role="img" aria-label="No image available for {{ $brandName }} {{ $moto->model_name }}">
                                            <i class="fa-solid fa-motorcycle" aria-hidden="true"></i>
                                        </span>
                                    @endif
                                </a>

                                <div class="min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <p class="mvx-brand">{{ $brandName }}</p>
                                            <h3 class="mvx-name">
                                                <a href="{{ $productUrl }}">{{ $moto->model_name }}</a>
                                            </h3>
                                        </div>
                                        <span class="text-[10px] font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded border border-slate-100 uppercase tracking-tight">
                                            {{ $moto->year }}
                                        </span>
                                    </div>

                                    @if(!empty($specs))
                                        <div class="mvx-chips">
                                            @foreach($specs as $spec)
                                                <span class="mvx-chip">
                                                    @if(str_contains($spec, 'cc')) <i class="fa-solid fa-gauge-high mr-1 opacity-60"></i> @endif
                                                    @if(str_contains($spec, 'hp')) <i class="fa-solid fa-bolt mr-1 opacity-60"></i> @endif
                                                    {{ $spec }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="flex items-center gap-3 mt-3">
                                        @if($moto->availability_status)
                                            <p class="mvx-stock {{ $stockTone }} m-0">
                                                <i class="fa-solid fa-circle" aria-hidden="true"></i>
                                                {{ ucfirst($moto->availability_status) }}
                                            </p>
                                        @endif
                                        <span class="text-[11px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded">
                                            {{ ucfirst($moto->category ?? 'Standard') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mvx-buy">
                                    <div class="mvx-price">
                                        <span class="mvx-unit-label">Unit Price</span>
                                        <span class="mvx-unit">{{ $moto->currency ?: $cartCurrency }} {{ number_format($unitPrice) }}</span>
                                        <span class="mvx-line">Line total: {{ $moto->currency ?: $cartCurrency }} {{ number_format($unitPrice) }}</span>
                                    </div>

                                    <div class="flex flex-col items-start gap-2 lg:items-end">
                                        <div class="mvx-controls">
                                            {{-- Quantity: one unit per motorcycle (CartController::add) --}}
                                            <div class="mvx-qty" role="group" aria-label="Quantity">
                                                <button type="submit"
                                                        form="mvx-remove-{{ $moto->id }}"
                                                        title="Remove from cart"
                                                        aria-label="Remove {{ $moto->model_name }} from cart (decreases quantity to zero)">
                                                    <i class="fa-solid fa-minus" aria-hidden="true"></i>
                                                </button>
                                                <span class="mvx-qty-val" aria-live="polite" aria-label="Quantity: 1">1</span>
                                                <button type="button" disabled aria-disabled="true"
                                                        title="Only one unit per motorcycle"
                                                        aria-label="Increase quantity (maximum one unit per motorcycle)">
                                                    <i class="fa-solid fa-plus" aria-hidden="true"></i>
                                                </button>
                                            </div>

                                            <form id="mvx-remove-{{ $moto->id }}" method="POST"
                                                  action="{{ route('cart.remove', $moto) }}"
                                                  data-guard>
                                                @csrf @method('DELETE')
                                                <button type="submit" class="mvx-remove"
                                                        aria-label="Remove {{ $brandName }} {{ $moto->model_name }} from cart"
                                                        title="Remove from cart">
                                                    <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <span class="mvx-qty-label lg:text-right">1 unit per model</span>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>

                {{-- ── Order summary ──────────────────────────── --}}
                <aside class="mvx-summary lg:sticky lg:top-24" aria-label="Order summary">
                    <h2>Order Summary</h2>

                    <div class="mt-6">
                        <div class="mvx-row">
                            <span class="mvx-k">Items</span>
                            <span class="mvx-v">{{ $itemCount }}</span>
                        </div>
                        <div class="mvx-row">
                            <span class="mvx-k">Subtotal</span>
                            <span class="mvx-v">{{ $cartCurrency }} {{ number_format($total) }}</span>
                        </div>
                    </div>

                    <div class="mvx-divide"></div>

                    <div class="flex items-end justify-between gap-3">
                        <span class="mvx-total-label">Total</span>
                        <span class="mvx-total-value"><small>{{ $cartCurrency }}</small>{{ number_format($total) }}</span>
                    </div>

                    <a href="{{ route('cart.checkout') }}" class="mvx-cta mt-6">
                        Proceed to Checkout
                        <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </a>
                    <a href="{{ route('motorcycles.index') }}" class="mvx-cta-ghost">
                        <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                        Continue Shopping
                    </a>

                    <p class="mvx-note">
                        <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
                        <span>No online payment is taken. Checkout submits an order enquiry and our team
                        contacts you to confirm availability, delivery and final pricing.</span>
                    </p>
                </aside>
            </div>

        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
    // Guard against accidental duplicate submissions on single-click removals.
    document.querySelectorAll('.mvx-cart form[data-guard]').forEach(function (form) {
        form.addEventListener('submit', function () {
            var btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.setAttribute('aria-busy', 'true');
            }
        });
    });
</script>
@endpush
