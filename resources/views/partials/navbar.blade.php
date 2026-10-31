{{-- =====================================================================
     UNIFIED NAVBAR — MOTOVEX | Dark themed, bilingual, RTL-aware.
     ===================================================================== --}}
@php
    $locale   = app()->getLocale();
    $isAr     = $locale === 'ar';
    $cartData = session('cart', ['motorcycles' => [], 'spare_parts' => []]);
    $cartCount = count($cartData['motorcycles'] ?? [])
               + array_sum(array_column($cartData['spare_parts'] ?? [], 'quantity'));
@endphp

<style>
/* ── Wheel spin animation ───────────────────────── */
@keyframes wheel-spin {
    from { transform: rotate(0deg); }
    to   { transform: rotate(360deg); }
}
.mv-wheel {
    display: inline-block;
    animation: wheel-spin 2.4s linear infinite;
    transform-origin: center center;
}
/* ── Navbar base ────────────────────────────────── */
.mvnav {
    position: sticky;
    top: 0;
    z-index: 9999;
    width: 100%;
    background: rgba(3, 9, 22, 0.97);
    border-bottom: 1px solid #17365b;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.45);
}
.mvnav-inner {
    height: 68px;
    max-width: 1360px;
    margin: 0 auto;
    padding: 0 20px;
    display: flex;
    align-items: center;
    gap: 18px;
}
/* ── Logo ───────────────────────────────────────── */
.mvnav-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    flex-shrink: 0;
}
.mvnav-logo-mark {
    width: 42px;
    height: 42px;
    border-radius: 13px;
    background: linear-gradient(135deg, #a64bff, #4b39ff 50%, #11b9ff);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 20px rgba(115, 60, 255, 0.55);
    flex-shrink: 0;
    font-size: 18px;
    color: #fff;
    gap: 0;
    position: relative;
    overflow: visible;
}
.mvnav-logo-text {
    display: flex;
    flex-direction: column;
    line-height: 1;
}
.mvnav-logo-text strong {
    font-size: 19px;
    font-weight: 900;
    color: #fff;
    letter-spacing: .4px;
}
.mvnav-logo-text small {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 2px;
    color: #7eaacc;
    text-transform: uppercase;
    margin-top: 2px;
}
/* ── Search ─────────────────────────────────────── */
.mvnav-search {
    flex: 1;
    min-width: 0;
    max-width: 380px;
    position: relative;
    display: none;
}
@media(min-width: 900px) { .mvnav-search { display: block; } }
.mvnav-search input {
    width: 100%;
    background: #061224;
    border: 1px solid #1f4369;
    border-radius: 10px;
    padding: 10px 14px 10px 38px;
    color: #dceaff;
    font-size: 12.5px;
    outline: none;
    transition: border-color .2s, box-shadow .2s;
}
.mvnav-search input:focus {
    border-color: #4d85cc;
    box-shadow: 0 0 0 3px rgba(77,133,204,.15);
}
.mvnav-search input::placeholder { color: #526d8a; }
.mvnav-search-icon {
    position: absolute;
    inset-inline-start: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #526d8a;
    font-size: 12px;
    pointer-events: none;
}
/* ── Nav links ──────────────────────────────────── */
.mvnav-links {
    display: none;
    align-items: center;
    gap: 4px;
    list-style: none;
    margin: 0;
    padding: 0;
}
@media(min-width: 1060px) { .mvnav-links { display: flex; } }
.mvnav-links a {
    display: inline-block;
    padding: 7px 12px;
    border-radius: 9px;
    font-size: 12.5px;
    font-weight: 700;
    color: #b8cfe6;
    text-decoration: none;
    transition: background .2s, color .2s;
    white-space: nowrap;
}
.mvnav-links a:hover,
.mvnav-links a.nav-active {
    background: rgba(100, 160, 255, .1);
    color: #a78bff;
}
/* ── Right tools ────────────────────────────────── */
.mvnav-tools {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-inline-start: auto;
}
.mvnav-icon {
    width: 38px;
    height: 38px;
    border-radius: 11px;
    border: 1px solid #1c3e64;
    background: #060f1e;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #c8daee;
    font-size: 14px;
    text-decoration: none;
    position: relative;
    transition: border-color .2s, background .2s, color .2s;
    flex-shrink: 0;
}
.mvnav-icon:hover {
    border-color: #a67dff;
    background: rgba(130, 70, 255, .12);
    color: #c5a4ff;
}
.mvnav-cart-badge {
    position: absolute;
    top: -6px;
    right: -6px;
    min-width: 18px;
    height: 18px;
    background: #e8305e;
    border-radius: 20px;
    font-size: 9px;
    font-weight: 900;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 4px;
    border: 2px solid #030916;
}
.mvnav-account-btn {
    display: none;
    align-items: center;
    gap: 7px;
    padding: 0 14px;
    height: 38px;
    border-radius: 11px;
    border: 1px solid #1c3e64;
    background: #060f1e;
    color: #c8daee;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    transition: border-color .2s, background .2s, color .2s;
    white-space: nowrap;
}
@media(min-width: 680px) { .mvnav-account-btn { display: flex; } }
.mvnav-account-btn:hover {
    border-color: #a67dff;
    background: rgba(130, 70, 255, .12);
    color: #c5a4ff;
}
/* ── Dashboard button ──────────────────────────── */
.mvnav-dash-btn {
    display: none;
    align-items: center;
    gap: 7px;
    padding: 0 14px;
    height: 38px;
    border-radius: 11px;
    border: 1px solid rgba(166, 125, 255, .45);
    background: linear-gradient(135deg, #8b5cf6, #4b39ff);
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
    box-shadow: 0 4px 14px rgba(115, 60, 255, .35);
    transition: filter .2s, box-shadow .2s, transform .2s;
}
@media(min-width: 680px) { .mvnav-dash-btn { display: flex; } }
.mvnav-dash-btn:hover {
    filter: brightness(1.12);
    box-shadow: 0 6px 20px rgba(115, 60, 255, .55);
    transform: translateY(-1px);
    color: #fff;
}
/* ── Language pill ──────────────────────────────── */
.mvnav-lang {
    display: none;
    align-items: center;
    gap: 5px;
    padding: 0 11px;
    height: 34px;
    border-radius: 9px;
    border: 1px solid #1c3e64;
    background: transparent;
    color: #8dafc9;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
    transition: color .2s, border-color .2s;
}
@media(min-width: 780px) { .mvnav-lang { display: flex; } }
.mvnav-lang:hover { color: #a78bff; border-color: #a67dff; }
/* ── Mobile toggle ──────────────────────────────── */
.mvnav-hamburger {
    width: 38px;
    height: 38px;
    border-radius: 11px;
    border: 1px solid #1c3e64;
    background: #060f1e;
    color: #c8daee;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: .2s;
}
@media(min-width: 1060px) { .mvnav-hamburger { display: none; } }
.mvnav-hamburger:hover { border-color: #a67dff; color: #c5a4ff; }
/* ── Mobile drawer ──────────────────────────────── */
.mvnav-drawer {
    display: none;
    flex-direction: column;
    border-top: 1px solid #17365b;
    background: #030a18;
    padding: 12px 20px 18px;
    gap: 4px;
}
.mvnav-drawer.open { display: flex; }
.mvnav-drawer a {
    padding: 11px 14px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    color: #b8cfe6;
    text-decoration: none;
    transition: background .2s, color .2s;
}
.mvnav-drawer a:hover,
.mvnav-drawer a.nav-active {
    background: rgba(100,160,255,.08);
    color: #a78bff;
}
.mvnav-drawer-divider {
    height: 1px;
    background: #17365b;
    margin: 8px 0;
}
/* ── Flash alert ───────────────────────────────── */
.mvnav-flash {
    background: rgba(30, 90, 30, .95);
    border-bottom: 1px solid #2a7a2a;
    color: #6aee6a;
    font-size: 13px;
    font-weight: 600;
    text-align: center;
    padding: 10px 16px;
}
.mvnav-flash.is-error {
    background: rgba(100, 20, 30, .95);
    border-bottom-color: #7a2a2a;
    color: #ff8a8a;
}
</style>

<div class="mvnav">
  <div class="mvnav-inner">

    {{-- ── Logo ── --}}
    <a href="{{ route('home') }}" class="mvnav-logo" aria-label="MOTOVEX Home">
      <div class="mvnav-logo-mark">
        {{-- The wheel icon spins, body stays still --}}
        <span style="position:relative;display:inline-flex;align-items:center;justify-content:center;">
          {{-- Motorcycle body (static) --}}
          <i class="fa-solid fa-motorcycle" style="font-size:18px;position:relative;z-index:2;"></i>
          {{-- Spinning wheel overlay --}}
          <span class="mv-wheel" style="
            position:absolute;
            inset-inline-start:-3px;
            bottom:-3px;
            width:14px;
            height:14px;
            border-radius:50%;
            border:2.5px solid rgba(255,255,255,0.9);
            border-top-color: transparent;
            z-index:3;
          "></span>
        </span>
      </div>
      <div class="mvnav-logo-text">
        <strong>MOTOVEX</strong>
        <small>{{ $isAr ? 'عش حلمك' : 'RIDE YOUR DREAM' }}</small>
      </div>
    </a>

    {{-- ── Search ── --}}
    <div class="mvnav-search">
      <form action="{{ route('motorcycles.index') }}" method="GET" role="search">
        <span class="mvnav-search-icon"><i class="fas fa-search"></i></span>
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="{{ $isAr ? 'ابحث عن موتوسيكل أو قطعة غيار…' : 'Search motorcycles, parts…' }}"
               aria-label="{{ $isAr ? 'بحث' : 'Search' }}">
      </form>
    </div>

    {{-- ── Nav links ── --}}
    @php
      $navLinks = $isAr ? [
          ['route' => 'motorcycles.index','label' => 'الموتوسيكلات'],
          ['route' => 'spare-parts.index','label' => 'قطع الغيار'],
          ['route' => 'brands.index',     'label' => 'العلامات التجارية'],
          ['route' => 'promotions',       'label' => 'العروض'],
          ['route' => 'services',         'label' => 'خدماتنا'],
          ['route' => 'contact',          'label' => 'تواصل معنا'],
      ] : [
          ['route' => 'motorcycles.index','label' => 'Motorcycles'],
          ['route' => 'spare-parts.index','label' => 'Spare Parts'],
          ['route' => 'brands.index',     'label' => 'Brands'],
          ['route' => 'promotions',       'label' => 'Promotions'],
          ['route' => 'services',         'label' => 'Services'],
          ['route' => 'contact',          'label' => 'Contact'],
      ];
    @endphp
    <nav aria-label="Main navigation">
      <ul class="mvnav-links">
        @foreach($navLinks as $link)
          <li>
            <a href="{{ route($link['route']) }}"
               class="{{ request()->routeIs($link['route']) ? 'nav-active' : '' }}">
              {{ $link['label'] }}
            </a>
          </li>
        @endforeach
      </ul>
    </nav>

    {{-- ── Tools ── --}}
    <div class="mvnav-tools">

      {{-- Language --}}
      @php
        $switchLocale = $isAr ? 'en' : 'ar';
        $switchLabel  = $isAr ? '🇬🇧 EN' : '🇪🇬 AR';
      @endphp
      <a href="{{ route('lang.switch', ['locale' => $switchLocale]) }}"
         class="mvnav-lang"
         aria-label="{{ $isAr ? 'Switch to English' : 'التبديل للعربية' }}">
        {{ $switchLabel }}
      </a>

      {{-- Cart --}}
      <a href="{{ route('cart.index') }}" class="mvnav-icon" aria-label="{{ $isAr ? 'السلة' : 'Cart' }}">
        <i class="fa-solid fa-cart-shopping"></i>
        @if($cartCount > 0)
          <span class="mvnav-cart-badge">{{ $cartCount > 9 ? '9+' : $cartCount }}</span>
        @endif
      </a>

      {{-- Dashboard --}}
      @auth
        <a href="{{ auth()->user()->isStaff() ? route('admin.dashboard') : route('dashboard') }}"
           class="mvnav-dash-btn"
           aria-label="{{ $isAr ? 'لوحة التحكم' : 'Dashboard' }}">
          <i class="fa-solid fa-gauge-high"></i>
          <span>{{ $isAr ? 'لوحة التحكم' : 'Dashboard' }}</span>
        </a>
      @endauth

      {{-- Account --}}
      @auth
        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('account.dashboard') }}"
           class="mvnav-account-btn"
           aria-label="{{ $isAr ? 'حسابي' : 'My Account' }}">
          <i class="fa-solid fa-user-circle"></i>
          <span>{{ $isAr ? 'حسابي' : 'Account' }}</span>
        </a>
      @else
        <a href="{{ route('login') }}" class="mvnav-account-btn"
           aria-label="{{ $isAr ? 'تسجيل الدخول' : 'Login' }}">
          <i class="fa-regular fa-user-circle"></i>
          <span>{{ $isAr ? 'دخول' : 'Login' }}</span>
        </a>
      @endauth

      {{-- Hamburger --}}
      <button class="mvnav-hamburger" id="mvHamburger"
              aria-expanded="false" aria-controls="mvDrawer"
              aria-label="{{ $isAr ? 'القائمة' : 'Menu' }}">
        <i class="fa-solid fa-bars" id="mvHamburgerIcon"></i>
      </button>
    </div>

  </div>

  {{-- ── Mobile drawer ── --}}
  <div class="mvnav-drawer" id="mvDrawer" aria-hidden="true">
    @foreach($navLinks as $link)
      <a href="{{ route($link['route']) }}"
         class="{{ request()->routeIs($link['route']) ? 'nav-active' : '' }}">
        {{ $link['label'] }}
      </a>
    @endforeach
    <div class="mvnav-drawer-divider"></div>
    <div style="display:flex;gap:10px;align-items:center;padding:4px 14px">
      @auth
        <a href="{{ auth()->user()->isStaff() ? route('admin.dashboard') : route('dashboard') }}"
           style="font-size:12px;color:#c5a4ff;text-decoration:none">
          <i class="fa-solid fa-gauge-high" style="margin-inline-end:5px"></i>
          {{ $isAr ? 'لوحة التحكم' : 'Dashboard' }}
        </a>
        <span style="color:#2a4a6a">·</span>
        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('account.dashboard') }}"
           style="font-size:12px;color:#b8cfe6;text-decoration:none">
          <i class="fa-solid fa-user-circle" style="margin-inline-end:5px"></i>
          {{ $isAr ? 'حسابي' : 'My Account' }}
        </a>
      @else
        <a href="{{ route('login') }}" style="font-size:12px;color:#b8cfe6;text-decoration:none">
          <i class="fa-regular fa-user-circle" style="margin-inline-end:5px"></i>
          {{ $isAr ? 'تسجيل الدخول' : 'Login' }}
        </a>
        <span style="color:#2a4a6a">·</span>
        <a href="{{ route('register') }}" style="font-size:12px;color:#a78bff;text-decoration:none">
          {{ $isAr ? 'إنشاء حساب' : 'Register' }}
        </a>
      @endauth
      <span style="color:#2a4a6a;margin-inline-start:auto">·</span>
      <a href="{{ route('lang.switch', ['locale' => $switchLocale]) }}"
         style="font-size:11px;color:#7eaacc;text-decoration:none">
        {{ $switchLabel }}
      </a>
    </div>
  </div>
</div>

{{-- Flash messages (success / error) --}}
@if(session('success'))
  <div class="mvnav-flash" role="alert">
    <i class="fa-solid fa-circle-check" style="margin-inline-end:7px"></i>{{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div class="mvnav-flash is-error" role="alert">
    <i class="fa-solid fa-circle-exclamation" style="margin-inline-end:7px"></i>{{ session('error') }}
  </div>
@endif
@if(session('info'))
  <div class="mvnav-flash" style="background:rgba(10,40,80,.95);border-bottom-color:#1a4a8a;color:#7dd3fc" role="alert">
    <i class="fa-solid fa-circle-info" style="margin-inline-end:7px"></i>{{ session('info') }}
  </div>
@endif

<script>
(function () {
    var btn  = document.getElementById('mvHamburger');
    var icon = document.getElementById('mvHamburgerIcon');
    var drawer = document.getElementById('mvDrawer');
    if (!btn || !drawer) return;
    btn.addEventListener('click', function () {
        var isOpen = drawer.classList.toggle('open');
        drawer.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
        btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        icon.className = isOpen ? 'fa-solid fa-xmark' : 'fa-solid fa-bars';
    });
})();
</script>
