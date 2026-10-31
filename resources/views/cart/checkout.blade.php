<!DOCTYPE html>
<html lang="{{ app()->getLocale() === 'ar' ? 'ar' : 'en' }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ __('cart.checkout_title') }} | MOTOVEX</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['Plus Jakarta Sans','sans-serif'] },
          colors: {
            brand: {
              50:'#f5f3ff',100:'#ede9fe',200:'#ddd6fe',
              300:'#c4b5fd',400:'#a78bfa',500:'#8b5cf6',
              600:'#7c3aed',700:'#6d28d9',800:'#5b21b6',900:'#4c1d95'
            }
          }
        }
      }
    }
  </script>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: #f4f3ff;
      color: #0f172a;
      -webkit-font-smoothing: antialiased;
      overflow-x: hidden;
      min-height: 100vh;
    }

    /* ── Animated gradient background ── */
    .co-bg {
      position: fixed; inset: 0; z-index: 0; pointer-events: none;
      background: linear-gradient(135deg, #f0edff 0%, #faf9ff 35%, #ede9fe 65%, #f5f0ff 100%);
      animation: bgShift 12s ease-in-out infinite alternate;
    }
    @keyframes bgShift {
      0%   { background-position: 0% 50%; }
      100% { background-position: 100% 50%; }
    }

    /* Floating orbs */
    .orb {
      position: fixed; border-radius: 50%; pointer-events: none; z-index: 0;
      filter: blur(80px); opacity: .35;
    }
    .orb-1 { width: 500px; height: 500px; background: radial-gradient(circle, #a78bfa, transparent 70%); top: -120px; left: -100px; animation: float1 14s ease-in-out infinite; }
    .orb-2 { width: 400px; height: 400px; background: radial-gradient(circle, #818cf8, transparent 70%); bottom: -80px; right: -80px; animation: float2 16s ease-in-out infinite; }
    .orb-3 { width: 280px; height: 280px; background: radial-gradient(circle, #c4b5fd, transparent 70%); top: 40%; left: 55%; animation: float3 11s ease-in-out infinite; }
    @keyframes float1 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(40px,30px)} }
    @keyframes float2 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(-30px,-40px)} }
    @keyframes float3 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(20px,-25px)} }

    /* ── Layout ── */
    .co-wrap {
      position: relative; z-index: 1;
      max-width: 1100px; margin: 0 auto;
      padding: 0 1.25rem 4rem;
    }
    @media(min-width:640px)  { .co-wrap { padding-inline: 1.75rem; } }
    @media(min-width:1024px) { .co-wrap { padding-inline: 2.25rem; } }

    /* ── Page heading ── */
    .co-hero { padding: 2.5rem 0 2rem; text-align: center; }
    .co-hero-badge {
      display: inline-flex; align-items: center; gap: .5rem;
      background: #f3e8ff; border: 1px solid #e9d5ff; color: #7c3aed;
      border-radius: 999px; padding: .38rem .9rem; font-size: .72rem; font-weight: 700; letter-spacing: .04em;
      margin-bottom: 1rem;
    }
    .co-hero h1 {
      font-size: clamp(2rem, 5vw, 3rem); font-weight: 900; letter-spacing: -.04em;
      background: linear-gradient(135deg, #4c1d95, #7c3aed, #8b5cf6);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      line-height: 1.1;
    }
    .co-hero p { font-size: .95rem; color: #64748b; margin-top: .65rem; }

    /* ── Progress steps ── */
    .co-steps {
      display: flex; align-items: center; justify-content: center; gap: 0;
      margin: 0 auto 2.5rem; max-width: 420px;
    }
    .co-step { display: flex; flex-direction: column; align-items: center; gap: .4rem; flex: 1; position: relative; }
    .co-step::after {
      content: ''; position: absolute; top: 17px; left: 50%; width: 100%; height: 2px;
      background: #e2e8f0; z-index: 0;
    }
    .co-step:last-child::after { display: none; }
    .co-step-dot {
      width: 34px; height: 34px; border-radius: 50%; display: grid; place-items: center;
      font-size: 12px; font-weight: 800; position: relative; z-index: 1;
      border: 2px solid #e2e8f0; background: #fff; color: #94a3b8;
      transition: all .25s;
    }
    .co-step.done .co-step-dot  { background: #f3e8ff; border-color: #a78bfa; color: #7c3aed; }
    .co-step.done::after        { background: linear-gradient(90deg, #a78bfa, #e2e8f0); }
    .co-step.active .co-step-dot { background: linear-gradient(135deg,#7c3aed,#6d28d9); border-color: #7c3aed; color: #fff; box-shadow: 0 6px 18px rgba(124,58,237,.35); }
    .co-step-lbl { font-size: .68rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .04em; }
    .co-step.active .co-step-lbl { color: #7c3aed; }
    .co-step.done .co-step-lbl   { color: #a78bfa; }

    /* ── Main grid ── */
    .co-grid { display: grid; gap: 1.75rem; align-items: start; }
    @media(min-width:900px) { .co-grid { grid-template-columns: 1fr 360px; } }

    /* ── Glass card ── */
    .co-card {
      background: rgba(255,255,255,.92); backdrop-filter: blur(24px);
      border: 1px solid rgba(139,92,246,.12);
      border-radius: 22px;
      box-shadow: 0 4px 24px rgba(124,58,237,.06), 0 1px 3px rgba(0,0,0,.04);
    }
    .co-card-head {
      padding: 1.35rem 1.6rem;
      border-bottom: 1px solid rgba(139,92,246,.08);
      display: flex; align-items: center; gap: .75rem;
    }
    .co-card-ico {
      width: 38px; height: 38px; border-radius: 12px; flex-shrink: 0;
      display: grid; place-items: center; font-size: 14px;
      background: linear-gradient(135deg, #f3e8ff, #ede9fe);
      color: #7c3aed; border: 1px solid #e9d5ff;
    }
    .co-card-title { font-size: 1rem; font-weight: 800; color: #0f172a; letter-spacing: -.01em; }
    .co-card-sub   { font-size: .78rem; color: #94a3b8; margin-top: .15rem; }
    .co-card-body  { padding: 1.6rem; }

    /* ── Form fields ── */
    .co-field { margin-bottom: 1.15rem; }
    .co-label {
      display: block; font-size: .78rem; font-weight: 700; color: #475569;
      margin-bottom: .45rem; letter-spacing: .01em;
    }
    .co-label .req { color: #a78bfa; margin-left: .2rem; }
    .co-input-wrap { position: relative; }
    .co-input-wrap i {
      position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
      color: #a78bfa; font-size: 13px; pointer-events: none;
    }
    textarea.co-input-wrap i { top: 16px; transform: none; }
    .co-input {
      width: 100%; background: #faf9ff; border: 1.5px solid #e8e4f8;
      border-radius: 13px; padding: .82rem 1rem .82rem 2.65rem;
      font-size: .9rem; font-family: inherit; color: #0f172a;
      transition: all .22s; outline: none;
    }
    .co-input::placeholder { color: #c4b5fd; }
    .co-input:focus { border-color: #8b5cf6; background: #fff; box-shadow: 0 0 0 4px rgba(139,92,246,.12); }
    .co-input.is-err { border-color: #f87171; box-shadow: 0 0 0 3px rgba(248,113,113,.15); }
    .co-err { font-size: .74rem; color: #ef4444; font-weight: 600; margin-top: .35rem; display: flex; align-items: center; gap: .3rem; }
    textarea.co-input { padding-top: .9rem; padding-bottom: .9rem; resize: vertical; min-height: 100px; }

    .co-grid-2 { display: grid; gap: 1rem; }
    @media(min-width:580px) { .co-grid-2 { grid-template-columns: 1fr 1fr; } }

    /* ── Submit button ── */
    .co-submit {
      width: 100%; display: flex; align-items: center; justify-content: center; gap: .75rem;
      padding: 1.05rem 2rem; border-radius: 15px; border: none; cursor: pointer;
      font-family: inherit; font-size: .95rem; font-weight: 800; letter-spacing: -.01em;
      color: #fff; position: relative; overflow: hidden;
      background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 50%, #5b21b6 100%);
      box-shadow: 0 12px 32px rgba(124,58,237,.35), 0 4px 12px rgba(0,0,0,.1);
      transition: all .3s cubic-bezier(.16,1,.3,1);
    }
    .co-submit::before {
      content: ''; position: absolute; inset: 0;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,.18), transparent);
      transform: translateX(-100%); transition: transform .55s;
    }
    .co-submit:hover::before { transform: translateX(100%); }
    .co-submit:hover { transform: translateY(-2px); box-shadow: 0 18px 42px rgba(124,58,237,.45); }
    .co-submit:active { transform: translateY(0); }
    .co-submit:disabled { opacity: .65; cursor: not-allowed; transform: none; }
    .co-submit .arrow { transition: transform .25s; }
    .co-submit:hover .arrow { transform: translateX(4px); }

    /* ── Security strip ── */
    .co-secure {
      display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 1.25rem;
      padding: .9rem 1rem; border-radius: 12px;
      background: rgba(139,92,246,.05); border: 1px solid rgba(139,92,246,.08);
      margin-top: 1rem;
    }
    .co-secure-item { display: flex; align-items: center; gap: .45rem; font-size: .73rem; font-weight: 600; color: #7c3aed; }
    .co-secure-item i { font-size: .72rem; }

    /* ── Order summary card ── */
    .co-summary-sticky { position: sticky; top: 5.5rem; }

    .co-moto-item {
      display: flex; align-items: center; gap: .9rem; padding: .9rem 0;
      border-bottom: 1px solid rgba(139,92,246,.06);
    }
    .co-moto-item:last-child { border-bottom: 0; padding-bottom: 0; }
    .co-moto-img {
      width: 56px; height: 44px; flex-shrink: 0; border-radius: 10px; overflow: hidden;
      border: 1px solid #e8e4f8; background: #f5f3ff;
      display: flex; align-items: center; justify-content: center;
    }
    .co-moto-img img { width: 100%; height: 100%; object-fit: cover; }
    .co-moto-name { font-size: .85rem; font-weight: 700; color: #0f172a; line-height: 1.3; }
    .co-moto-brand { font-size: .7rem; font-weight: 600; color: #a78bfa; text-transform: uppercase; letter-spacing: .1em; margin-top: .1rem; }
    .co-moto-price { margin-left: auto; font-size: .9rem; font-weight: 800; color: #0f172a; flex-shrink: 0; }

    .co-total-row {
      display: flex; align-items: center; justify-content: space-between; gap: 1rem;
      padding: 1.1rem 0 .5rem; border-top: 1.5px solid rgba(139,92,246,.1); margin-top: .75rem;
    }
    .co-total-lbl { font-size: .8rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .06em; }
    .co-total-val {
      font-size: 1.75rem; font-weight: 900; letter-spacing: -.04em;
      background: linear-gradient(135deg, #7c3aed, #a78bfa);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }
    .co-total-cur { font-size: .85rem; font-weight: 700; color: #a78bfa; margin-right: .3rem; }

    /* ── Promise cards ── */
    .co-promises { display: grid; grid-template-columns: 1fr 1fr; gap: .65rem; margin-top: 1.25rem; }
    .co-promise {
      display: flex; flex-direction: column; align-items: flex-start; gap: .45rem;
      padding: .85rem; border-radius: 12px;
      background: rgba(139,92,246,.04); border: 1px solid rgba(139,92,246,.08);
    }
    .co-promise-ico { color: #7c3aed; font-size: .9rem; }
    .co-promise-txt { font-size: .72rem; font-weight: 700; color: #475569; line-height: 1.4; }

    /* ── Note box ── */
    .co-note {
      display: flex; align-items: flex-start; gap: .65rem;
      padding: .9rem 1rem; border-radius: 12px; margin-top: 1.1rem;
      background: rgba(139,92,246,.04); border: 1px solid rgba(139,92,246,.1);
      font-size: .76rem; color: #64748b; line-height: 1.6;
    }
    .co-note i { color: #a78bfa; margin-top: .1rem; flex-shrink: 0; }

    /* ── Flash ── */
    .co-flash-ok {
      display: flex; align-items: center; gap: .75rem;
      padding: .9rem 1.1rem; border-radius: 13px; margin-bottom: 1.5rem;
      background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534;
      font-size: .88rem; font-weight: 600;
    }

    /* ── Reveal animation ── */
    .reveal { opacity: 0; transform: translateY(28px); transition: all .75s cubic-bezier(.16,1,.3,1); }
    .reveal.active { opacity: 1; transform: translateY(0); }
  </style>
@include('partials.rtl-styles')
</head>
<body>

{{-- Floating background --}}
<div class="co-bg" aria-hidden="true"></div>
<div class="orb orb-1" aria-hidden="true"></div>
<div class="orb orb-2" aria-hidden="true"></div>
<div class="orb orb-3" aria-hidden="true"></div>

{{-- ── Navbar ── --}}
@include('partials.navbar')

<div class="co-wrap">

  {{-- ── Page hero ── --}}
  <div class="co-hero reveal">
    <div class="co-hero-badge">
      <i class="fa-solid fa-lock text-[10px]"></i>
      {{ __('cart.secure_enquiry') }}
    </div>
    <h1>{{ __('cart.checkout_heading') }}</h1>
    <p>{{ __('cart.checkout_sub') }}</p>
  </div>

  {{-- ── Progress steps ── --}}
  <div class="co-steps reveal" style="transition-delay:.08s">
    <div class="co-step done">
      <div class="co-step-dot"><i class="fa-solid fa-check text-xs"></i></div>
      <span class="co-step-lbl">{{ __('cart.step_browse') }}</span>
    </div>
    <div class="co-step done">
      <div class="co-step-dot"><i class="fa-solid fa-check text-xs"></i></div>
      <span class="co-step-lbl">{{ __('cart.step_cart') }}</span>
    </div>
    <div class="co-step active">
      <div class="co-step-dot">3</div>
      <span class="co-step-lbl">{{ __('cart.step_checkout') }}</span>
    </div>
    <div class="co-step">
      <div class="co-step-dot">4</div>
      <span class="co-step-lbl">{{ __('cart.step_confirm') }}</span>
    </div>
  </div>

  {{-- Flash --}}
  @if(session('success'))
  <div class="co-flash-ok reveal">
    <i class="fa-solid fa-circle-check"></i>
    {{ session('success') }}
  </div>
  @endif

  @if(session('error'))
  <div class="reveal" style="display:flex;align-items:center;gap:.75rem;padding:.9rem 1.1rem;border-radius:13px;margin-bottom:1.5rem;background:#fef2f2;border:1px solid #fecaca;color:#991b1b;font-size:.88rem;font-weight:600;">
    <i class="fa-solid fa-circle-exclamation"></i>
    {{ session('error') }}
  </div>
  @endif

  {{-- ── Main Grid ── --}}
  <div class="co-grid">

    {{-- ── Left: Order form ── --}}
    <div class="reveal" style="transition-delay:.12s">
      <div class="co-card">
        <div class="co-card-head">
          <div class="co-card-ico"><i class="fa-solid fa-user"></i></div>
          <div>
            <div class="co-card-title">{{ __('cart.your_information') }}</div>
            <div class="co-card-sub">{{ __('cart.info_sub') }}</div>
          </div>
        </div>
        <div class="co-card-body">

          <form method="POST" action="{{ route('cart.place-order') }}" id="checkoutForm" novalidate>
            @csrf
            <input type="hidden" name="idempotency_key" id="idempotencyKey" value="{{ session('cart_submission_token', '') }}">

            {{-- Name (full width) --}}
            <div class="co-field">
              <label class="co-label">{{ __('cart.full_name') }} <span class="req">*</span></label>
              <div class="co-input-wrap">
                <i class="fa-solid fa-user"></i>
                <input type="text" name="name"
                       value="{{ old('name', auth()->user()->name ?? '') }}"
                       required placeholder="John Smith"
                       class="co-input {{ $errors->has('name') ? 'is-err' : '' }}">
              </div>
              @error('name')
                <p class="co-err"><i class="fa-solid fa-circle-exclamation text-xs"></i>{{ $message }}</p>
              @enderror
            </div>

            {{-- Email + Phone --}}
            <div class="co-grid-2">
              <div class="co-field">
                <label class="co-label">{{ __('cart.email_address') }} <span class="req">*</span></label>
                <div class="co-input-wrap">
                  <i class="fa-solid fa-envelope"></i>
                  <input type="email" name="email"
                         value="{{ old('email', auth()->user()->email ?? '') }}"
                         required placeholder="you@example.com"
                         class="co-input {{ $errors->has('email') ? 'is-err' : '' }}">
                </div>
                @error('email')
                  <p class="co-err"><i class="fa-solid fa-circle-exclamation text-xs"></i>{{ $message }}</p>
                @enderror
              </div>

              <div class="co-field">
                <label class="co-label">{{ __('cart.phone_number') }} <span class="req">*</span></label>
                <div class="co-input-wrap">
                  <i class="fa-solid fa-phone"></i>
                  <input type="tel" name="phone"
                         value="{{ old('phone', auth()->user()->phone ?? '') }}"
                         required placeholder="+20 1xx xxx xxxx"
                         class="co-input {{ $errors->has('phone') ? 'is-err' : '' }}">
                </div>
                @error('phone')
                  <p class="co-err"><i class="fa-solid fa-circle-exclamation text-xs"></i>{{ $message }}</p>
                @enderror
              </div>
            </div>

            {{-- Message --}}
            <div class="co-field">
              <label class="co-label">{{ __('cart.additional_notes') }} <span style="color:#94a3b8;font-weight:500">{{ __('cart.optional_label') }}</span></label>
              <div class="co-input-wrap" style="position:relative">
                <i class="fa-solid fa-comment-dots" style="position:absolute;left:14px;top:14px;color:#a78bfa;font-size:13px;pointer-events:none;"></i>
                <textarea name="message" rows="3"
                          placeholder="{{ __('cart.notes_placeholder_co') }}"
                          class="co-input"
                          style="padding-top:.85rem;padding-bottom:.85rem;padding-left:2.65rem;">{{ old('message') }}</textarea>
              </div>
              @error('message')
                <p class="co-err"><i class="fa-solid fa-circle-exclamation text-xs"></i>{{ $message }}</p>
              @enderror
            </div>

            {{-- Submit --}}
            <div style="margin-top:1.5rem">
              <button type="submit" id="submitBtn" class="co-submit">
                <i class="fa-solid fa-motorcycle"></i>
                <span id="submitTxt">{{ __('cart.place_order_enquiry') }}</span>
                <i class="fa-solid fa-arrow-right text-xs arrow"></i>
              </button>

              {{-- Security strip --}}
              <div class="co-secure">
                <div class="co-secure-item"><i class="fa-solid fa-lock"></i> {{ __('cart.secure_submission') }}</div>
                <div class="co-secure-item"><i class="fa-solid fa-shield-halved"></i> {{ __('cart.no_card_required') }}</div>
                <div class="co-secure-item"><i class="fa-solid fa-headset"></i> {{ __('cart.team_24h') }}</div>
              </div>
            </div>

          </form>

        </div>
      </div>
    </div>

    {{-- ── Right: Order summary ── --}}
    <div class="co-summary-sticky reveal" style="transition-delay:.2s">

      {{-- Summary card --}}
      @php
        $motoCount    = $motoItems->count();
        $spCount      = $spItems->count();
        $itemCount    = $motoCount + $spCount;
        $cartCurrency = ($motoItems->isNotEmpty() ? ($motoItems->first()['motorcycle']->currency ?: null) : null)
            ?? ($spItems->isNotEmpty() ? ($spItems->first()['spare_part']->currency ?: null) : null)
            ?? 'USD';
      @endphp
      <div class="co-card" style="margin-bottom:1rem;">
        <div class="co-card-head">
          <div class="co-card-ico"><i class="fa-solid fa-receipt"></i></div>
          <div>
            <div class="co-card-title">{{ __('cart.order_summary') }}</div>
            <div class="co-card-sub">{{ str_replace(':count', $itemCount, __('cart.items_selected')) }}</div>
          </div>
        </div>
        <div class="co-card-body" style="padding-top:1rem;padding-bottom:1rem;">

          {{-- Motorcycles --}}
          @foreach($motoItems as $item)
            @php $moto = $item['motorcycle']; @endphp
            <div class="co-moto-item">
              <div class="co-moto-img">
                @if($moto->image_url)
                  <img src="{{ $moto->image_url }}" alt="{{ $moto->model_name }}">
                @else
                  <i class="fa-solid fa-motorcycle" style="color:#c4b5fd;font-size:18px;"></i>
                @endif
              </div>
              <div style="flex:1;min-width:0;">
                <div class="co-moto-name">{{ $moto->model_name }}</div>
                <div class="co-moto-brand">{{ $moto->brand?->name }}</div>
              </div>
              <div class="co-moto-price">
                @if($moto->price)
                  {{ $moto->currency ?: 'USD' }} {{ number_format($moto->price) }}
                @else
                  <span style="font-size:.75rem;color:#94a3b8;">{{ __('cart.on_request_text') }}</span>
                @endif
              </div>
            </div>
          @endforeach

          {{-- Spare parts --}}
          @foreach($spItems as $item)
            @php
              $sp  = $item['spare_part'];
              $qty = max(1, (int) $item['quantity']);
            @endphp
            <div class="co-moto-item">
              <div class="co-moto-img">
                @if($sp->image_url)
                  <img src="{{ $sp->image_url }}" alt="{{ $sp->name }}">
                @else
                  <i class="fa-solid fa-gears" style="color:#c4b5fd;font-size:18px;"></i>
                @endif
              </div>
              <div style="flex:1;min-width:0;">
                <div class="co-moto-name">{{ $sp->name }}</div>
                <div class="co-moto-brand">{{ $sp->brand?->name ?? 'MOTOVEX' }} &middot; {{ __('cart.qty_prefix') }} {{ $qty }}</div>
              </div>
              <div class="co-moto-price">
                @if($sp->price)
                  {{ $sp->currency ?: 'USD' }} {{ number_format((float) $sp->price * $qty) }}
                @else
                  <span style="font-size:.75rem;color:#94a3b8;">{{ __('cart.on_request_text') }}</span>
                @endif
              </div>
            </div>
          @endforeach

          {{-- Total --}}
          @if($total > 0)
          <div class="co-total-row">
            <span class="co-total-lbl">{{ __('cart.total_label') }}</span>
            <span class="co-total-val"><span class="co-total-cur">{{ $cartCurrency }}</span>{{ number_format($total) }}</span>
          </div>
          @endif

          {{-- Note --}}
          <div class="co-note">
            <i class="fa-solid fa-circle-info"></i>
            <span>{{ __('cart.checkout_no_payment') }}</span>
          </div>

        </div>
      </div>

      {{-- Promises --}}
      <div class="co-card">
        <div class="co-card-body" style="padding:1.25rem 1.4rem;">
          <p style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#a78bfa;margin-bottom:.85rem;">{{ __('cart.our_promise') }}</p>
          <div class="co-promises">
            <div class="co-promise">
              <i class="fa-solid fa-shield-halved co-promise-ico"></i>
              <span class="co-promise-txt">{{ __('cart.promise_certified') }}</span>
            </div>
            <div class="co-promise">
              <i class="fa-solid fa-truck-fast co-promise-ico"></i>
              <span class="co-promise-txt">{{ __('cart.promise_delivery') }}</span>
            </div>
            <div class="co-promise">
              <i class="fa-solid fa-rotate-left co-promise-ico"></i>
              <span class="co-promise-txt">{{ __('cart.promise_warranty') }}</span>
            </div>
            <div class="co-promise">
              <i class="fa-solid fa-headset co-promise-ico"></i>
              <span class="co-promise-txt">{{ __('cart.promise_support') }}</span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

</div>{{-- /.co-wrap --}}

@include('partials.footer')

<script>
  // ── Reveal on scroll ──────────────────────────────────────
  var reveals = document.querySelectorAll('.reveal');
  var obs = new IntersectionObserver(function(entries){
    entries.forEach(function(e){
      if(e.isIntersecting){ e.target.classList.add('active'); obs.unobserve(e.target); }
    });
  }, { threshold: .06 });
  reveals.forEach(function(el){ obs.observe(el); });

  // ── Submit guard ──────────────────────────────────────────
  var form = document.getElementById('checkoutForm');
  var btn  = document.getElementById('submitBtn');
  var txt  = document.getElementById('submitTxt');
  var submitted = false;

  form && form.addEventListener('submit', function(e) {
    // Validate required visible fields manually
    var name  = form.querySelector('[name="name"]');
    var email = form.querySelector('[name="email"]');
    var phone = form.querySelector('[name="phone"]');
    var valid = true;

    [name, email, phone].forEach(function(field) {
      if (!field || !field.value.trim()) {
        if (field) field.classList.add('is-err');
        valid = false;
      } else {
        if (field) field.classList.remove('is-err');
      }
    });

    if (!valid) {
      e.preventDefault();
      return;
    }

    // Prevent double submission
    if (submitted) {
      e.preventDefault();
      return;
    }
    submitted = true;

    btn.disabled = true;
    txt.textContent = @json(__('cart.submitting_text'));
    btn.style.opacity = '.75';
  });

  // Restore button if page is shown from bfcache (back button)
  window.addEventListener('pageshow', function(e) {
    if (e.persisted) {
      submitted = false;
      if (btn) { btn.disabled = false; btn.style.opacity = '1'; }
      if (txt) { txt.textContent = @json(__('cart.place_order_enquiry')); }
    }
  });
</script>

</body>
</html>
