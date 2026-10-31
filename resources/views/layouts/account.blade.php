<!DOCTYPE html>
<html lang="{{ $locale ?? app()->getLocale() }}" dir="{{ $dir ?? (app()->getLocale() === 'ar' ? 'rtl' : 'ltr') }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', __('account.title')) | MOTOVEX</title>
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Noto+Kufi+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['Plus Jakarta Sans', 'Noto Kufi Arabic', 'sans-serif'] },
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
    /* ─── MOTOVEX Account Portal — design tokens ─── */
    :root {
      --ac-bg:           #ffffff;
      --ac-lav:          #f7f6fd;
      --ac-lav-2:        #f0edfa;
      --ac-surface:      #ffffff;
      --ac-ink:          #172033;
      --ac-ink-2:        #3f4a5c;
      --ac-muted:        #64748b;
      --ac-line:         #e8e7f2;
      --ac-line-2:       #dcd9ee;
      --ac-primary:      #7c3aed;
      --ac-primary-d:    #6d28d9;
      --ac-primary-soft: #f3e8ff;
      --ac-radius:       16px;
      --ac-sidebar-w:    260px;
      --ac-shadow-sm:    0 1px 3px rgba(23,32,51,.06);
      --ac-shadow-md:    0 4px 16px -4px rgba(23,32,51,.10);
      --ac-shadow-lg:    0 20px 40px -20px rgba(124,58,237,.25);
    }

    *, *::before, *::after { box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body {
      font-family: 'Plus Jakarta Sans', 'Noto Kufi Arabic', sans-serif;
      background: var(--ac-lav);
      color: var(--ac-ink);
      -webkit-font-smoothing: antialiased;
      overflow-x: hidden;
    }

    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: var(--ac-lav); }
    ::-webkit-scrollbar-thumb { background: #cdc6ea; border-radius: 20px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--ac-primary); }

    /* ─── Layout shell ─── */
    .ac-shell {
      display: flex;
      min-height: 100vh;
      flex-direction: column;
    }

    .ac-body {
      flex: 1 1 auto;
      width: 100%;
      max-width: 1280px;
      margin-inline: auto;
      padding: 2rem 1.25rem 3rem;
      display: flex;
      gap: 1.75rem;
      align-items: flex-start;
    }
    @media (min-width: 640px)  { .ac-body { padding-inline: 1.75rem; } }
    @media (min-width: 1024px) { .ac-body { padding-inline: 2.5rem; } }

    /* ─── Sidebar ─── */
    .ac-sidebar {
      display: none;
      flex-direction: column;
      width: var(--ac-sidebar-w);
      flex-shrink: 0;
      position: sticky;
      top: 5rem;
      max-height: calc(100vh - 6rem);
      overflow-y: auto;
    }
    @media (min-width: 1024px) { .ac-sidebar { display: flex; } }

    .ac-sidebar-card {
      background: var(--ac-surface);
      border: 1px solid var(--ac-line);
      border-radius: var(--ac-radius);
      box-shadow: var(--ac-shadow-sm);
      overflow: hidden;
    }

    /* Avatar block */
    .ac-avatar-block {
      padding: 1.5rem 1.25rem 1.25rem;
      border-bottom: 1px solid var(--ac-line);
      background: linear-gradient(160deg, #faf8ff 0%, var(--ac-lav) 100%);
    }
    .ac-avatar {
      display: grid;
      place-items: center;
      width: 3rem;
      height: 3rem;
      border-radius: 12px;
      background: var(--ac-primary);
      color: #fff;
      font-size: 1.15rem;
      font-weight: 800;
      letter-spacing: .01em;
      margin-bottom: .9rem;
      box-shadow: 0 8px 20px -8px rgba(124,58,237,.55);
    }
    .ac-user-name {
      font-size: .92rem;
      font-weight: 700;
      color: var(--ac-ink);
      line-height: 1.3;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .ac-user-email {
      font-size: .76rem;
      color: var(--ac-muted);
      margin-top: .2rem;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    /* Nav items */
    .ac-nav {
      padding: .6rem;
      display: flex;
      flex-direction: column;
      gap: .2rem;
    }
    .ac-nav-item {
      display: flex;
      align-items: center;
      gap: .75rem;
      padding: .72rem .9rem;
      border-radius: 10px;
      font-size: .875rem;
      font-weight: 600;
      color: var(--ac-ink-2);
      text-decoration: none;
      transition: background .18s, color .18s;
      position: relative;
      cursor: pointer;
      border: none;
      background: transparent;
      width: 100%;
      text-align: left;
    }
    .ac-nav-item:hover {
      background: var(--ac-lav);
      color: var(--ac-ink);
    }
    .ac-nav-item.is-active {
      background: var(--ac-primary-soft);
      color: var(--ac-primary-d);
    }
    .ac-nav-item.is-active::before {
      content: '';
      position: absolute;
      left: 0;
      top: 25%;
      height: 50%;
      width: 3px;
      border-radius: 0 3px 3px 0;
      background: var(--ac-primary);
    }
    .ac-nav-item i {
      width: 1.1rem;
      text-align: center;
      font-size: .82rem;
      flex-shrink: 0;
    }
    .ac-nav-divider {
      margin: .4rem .6rem;
      border-top: 1px solid var(--ac-line);
    }
    .ac-nav-item.is-signout {
      color: #ef4444;
      font-size: .85rem;
    }
    .ac-nav-item.is-signout:hover { background: #fff1f2; color: #dc2626; }

    /* ─── Mobile tabs ─── */
    .ac-mobile-nav {
      display: flex;
      align-items: center;
      gap: .5rem;
      padding: .75rem 1.25rem;
      overflow-x: auto;
      scrollbar-width: none;
      background: var(--ac-surface);
      border-bottom: 1px solid var(--ac-line);
      box-shadow: var(--ac-shadow-sm);
    }
    .ac-mobile-nav::-webkit-scrollbar { display: none; }
    @media (min-width: 1024px) { .ac-mobile-nav { display: none; } }

    .ac-tab {
      display: inline-flex;
      align-items: center;
      gap: .45rem;
      padding: .55rem 1rem;
      border-radius: 999px;
      border: 1px solid var(--ac-line-2);
      background: #fff;
      color: var(--ac-ink-2);
      font-size: .8rem;
      font-weight: 600;
      white-space: nowrap;
      text-decoration: none;
      transition: background .18s, border-color .18s, color .18s;
      cursor: pointer;
    }
    .ac-tab:hover { background: var(--ac-lav); color: var(--ac-ink); }
    .ac-tab.is-active {
      background: var(--ac-primary-soft);
      border-color: #d9c9fb;
      color: var(--ac-primary-d);
    }
    .ac-tab i { font-size: .72rem; }

    /* Mobile sign-out tab */
    form.ac-signout-form { display: contents; }
    .ac-tab.is-signout-tab { color: #ef4444; border-color: #fecaca; }
    .ac-tab.is-signout-tab:hover { background: #fff1f2; }

    /* ─── Main content area ─── */
    .ac-main {
      flex: 1 1 0%;
      min-width: 0;
    }

    /* Page header */
    .ac-page-header {
      margin-bottom: 1.75rem;
    }
    .ac-page-header-inner {
      display: flex;
      align-items: center;
      gap: .75rem;
    }
    .ac-heading-bar {
      width: 4px;
      height: 1.8rem;
      border-radius: 4px;
      background: var(--ac-primary);
      flex-shrink: 0;
    }
    .ac-heading {
      font-size: 1.5rem;
      font-weight: 800;
      letter-spacing: -.02em;
      color: var(--ac-ink);
      line-height: 1.2;
    }
    @media (min-width: 640px) { .ac-heading { font-size: 1.75rem; } }

    /* Flash messages */
    .ac-flash {
      display: flex;
      align-items: center;
      gap: .75rem;
      padding: .9rem 1.1rem;
      border-radius: 12px;
      font-size: .88rem;
      font-weight: 600;
      margin-bottom: 1.5rem;
    }
    .ac-flash.is-success {
      background: #f0fdf4;
      border: 1px solid #bbf7d0;
      color: #166534;
    }
    .ac-flash.is-error {
      background: #fff1f2;
      border: 1px solid #fecaca;
      color: #991b1b;
    }
    .ac-flash i { font-size: .9rem; }

    /* ─── Shared card style ─── */
    .ac-card {
      background: var(--ac-surface);
      border: 1px solid var(--ac-line);
      border-radius: var(--ac-radius);
      box-shadow: var(--ac-shadow-sm);
      overflow: hidden;
    }
    .ac-card-header {
      padding: 1.25rem 1.5rem;
      border-bottom: 1px solid var(--ac-line);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
    }
    .ac-card-title {
      font-size: 1rem;
      font-weight: 700;
      color: var(--ac-ink);
      display: flex;
      align-items: center;
      gap: .6rem;
    }
    .ac-card-title i {
      color: var(--ac-primary);
      font-size: .9rem;
    }
    .ac-card-body { padding: 1.5rem; }

    /* ─── Stat cards ─── */
    .ac-stat-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 1rem;
      margin-bottom: 1.75rem;
    }
    @media (min-width: 480px) { .ac-stat-grid { grid-template-columns: repeat(3, 1fr); } }

    .ac-stat {
      background: var(--ac-surface);
      border: 1px solid var(--ac-line);
      border-radius: var(--ac-radius);
      padding: 1.35rem 1.25rem;
      display: flex;
      align-items: flex-start;
      gap: 1rem;
      box-shadow: var(--ac-shadow-sm);
      transition: box-shadow .25s, border-color .25s;
    }
    .ac-stat:hover { box-shadow: var(--ac-shadow-md); border-color: var(--ac-line-2); }
    .ac-stat-ico {
      width: 2.6rem;
      height: 2.6rem;
      border-radius: 10px;
      background: var(--ac-primary-soft);
      color: var(--ac-primary-d);
      display: grid;
      place-items: center;
      font-size: 1rem;
      flex-shrink: 0;
    }
    .ac-stat-num {
      font-size: 1.75rem;
      font-weight: 800;
      color: var(--ac-primary);
      line-height: 1;
    }
    .ac-stat-label {
      font-size: .78rem;
      font-weight: 600;
      color: var(--ac-muted);
      margin-top: .35rem;
    }

    /* ─── Status badges ─── */
    .ac-badge {
      display: inline-flex;
      align-items: center;
      gap: .35rem;
      padding: .28rem .65rem;
      border-radius: 999px;
      font-size: .72rem;
      font-weight: 700;
    }
    .ac-badge.pending  { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
    .ac-badge.contacted{ background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
    .ac-badge.completed{ background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .ac-badge.cancelled{ background: #fff1f2; color: #991b1b; border: 1px solid #fecaca; }

    /* ─── Table ─── */
    .ac-table-wrap { overflow-x: auto; }
    .ac-table {
      width: 100%;
      border-collapse: collapse;
      font-size: .875rem;
    }
    .ac-table th {
      padding: .75rem 1rem;
      background: var(--ac-lav);
      color: var(--ac-muted);
      font-size: .72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .05em;
      text-align: left;
      border-bottom: 1px solid var(--ac-line);
      white-space: nowrap;
    }
    .ac-table td {
      padding: 1rem;
      border-bottom: 1px solid var(--ac-line);
      color: var(--ac-ink-2);
      vertical-align: middle;
    }
    .ac-table tr:last-child td { border-bottom: none; }
    .ac-table tbody tr:hover { background: var(--ac-lav); }

    /* ─── Form inputs ─── */
    .ac-label {
      display: block;
      font-size: .82rem;
      font-weight: 700;
      color: var(--ac-ink-2);
      margin-bottom: .45rem;
    }
    .ac-input {
      width: 100%;
      padding: .75rem 1rem;
      border-radius: 10px;
      border: 1px solid var(--ac-line-2);
      background: #fff;
      color: var(--ac-ink);
      font-family: inherit;
      font-size: .9rem;
      transition: border-color .2s, box-shadow .2s;
      outline: none;
    }
    .ac-input::placeholder { color: #9aa3b2; }
    .ac-input:focus {
      border-color: var(--ac-primary);
      box-shadow: 0 0 0 3px rgba(124,58,237,.14);
    }
    .ac-input:read-only {
      background: var(--ac-lav);
      color: var(--ac-muted);
      cursor: not-allowed;
    }
    .ac-input.is-error { border-color: #f87171; box-shadow: 0 0 0 3px rgba(248,113,113,.15); }
    .ac-error-msg {
      margin-top: .35rem;
      font-size: .77rem;
      color: #dc2626;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: .3rem;
    }

    /* ─── Buttons ─── */
    .ac-btn-primary {
      display: inline-flex;
      align-items: center;
      gap: .55rem;
      padding: .8rem 1.6rem;
      border-radius: 11px;
      background: var(--ac-primary);
      color: #fff;
      font-family: inherit;
      font-size: .88rem;
      font-weight: 700;
      border: none;
      cursor: pointer;
      text-decoration: none;
      transition: background .22s, box-shadow .22s, transform .22s;
      box-shadow: 0 8px 20px -10px rgba(124,58,237,.7);
    }
    .ac-btn-primary:hover {
      background: var(--ac-primary-d);
      box-shadow: 0 12px 28px -10px rgba(109,40,217,.85);
      transform: translateY(-1px);
    }
    .ac-btn-ghost {
      display: inline-flex;
      align-items: center;
      gap: .5rem;
      padding: .72rem 1.3rem;
      border-radius: 10px;
      border: 1px solid var(--ac-line-2);
      background: #fff;
      color: var(--ac-ink);
      font-family: inherit;
      font-size: .85rem;
      font-weight: 700;
      text-decoration: none;
      cursor: pointer;
      transition: border-color .2s, color .2s, box-shadow .2s;
    }
    .ac-btn-ghost:hover {
      border-color: var(--ac-primary);
      color: var(--ac-primary-d);
      box-shadow: 0 6px 18px -10px rgba(124,58,237,.6);
    }

    /* ─── Empty state ─── */
    .ac-empty {
      text-align: center;
      padding: 3rem 1.5rem;
      border: 1.5px dashed var(--ac-line-2);
      border-radius: var(--ac-radius);
      background: var(--ac-lav);
    }
    .ac-empty-ico {
      display: grid;
      place-items: center;
      width: 3.2rem;
      height: 3.2rem;
      margin: 0 auto .9rem;
      border-radius: 12px;
      background: #fff;
      border: 1px solid var(--ac-line-2);
      color: var(--ac-primary-d);
      font-size: 1.2rem;
    }
    .ac-empty h3 { font-size: 1.05rem; font-weight: 800; color: var(--ac-ink); }
    .ac-empty p { margin-top: .35rem; font-size: .86rem; color: var(--ac-muted); }

    /* ─── Section dividers ─── */
    .ac-section-gap { margin-top: 1.5rem; }
    .ac-form-grid-2 {
      display: grid;
      grid-template-columns: 1fr;
      gap: 1.25rem;
    }
    @media (min-width: 600px) { .ac-form-grid-2 { grid-template-columns: 1fr 1fr; } }

    /* ─── Info note ─── */
    .ac-info-note {
      display: flex;
      align-items: center;
      gap: .5rem;
      margin-top: .4rem;
      font-size: .77rem;
      color: var(--ac-muted);
    }
    .ac-info-note i { color: var(--ac-primary); font-size: .72rem; }

    /* ─── Toggle / checkbox ─── */
    .ac-toggle-row {
      display: flex;
      align-items: flex-start;
      gap: .85rem;
      padding: 1rem 1.1rem;
      border-radius: 12px;
      border: 1px solid var(--ac-line);
      background: var(--ac-lav);
    }
    .ac-toggle-check {
      width: 1.1rem;
      height: 1.1rem;
      accent-color: var(--ac-primary);
      margin-top: .15rem;
      flex-shrink: 0;
      cursor: pointer;
    }
    .ac-toggle-label { font-size: .88rem; font-weight: 600; color: var(--ac-ink); cursor: pointer; }
    .ac-toggle-sub { font-size: .78rem; color: var(--ac-muted); margin-top: .15rem; }

    /* ─── Danger zone ─── */
    .ac-danger-zone {
      border: 1.5px solid #fca5a5;
      border-radius: var(--ac-radius);
      background: #fff8f8;
      padding: 1.5rem;
    }
    .ac-danger-title {
      font-size: .95rem;
      font-weight: 800;
      color: #dc2626;
      display: flex;
      align-items: center;
      gap: .55rem;
      margin-bottom: .65rem;
    }
    .ac-danger-text { font-size: .86rem; color: #6b7280; line-height: 1.7; }

    /* ─── Quick links ─── */
    .ac-quick-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 1rem;
    }
    .ac-quick-link {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: .55rem;
      padding: 1.1rem 1.15rem;
      background: var(--ac-surface);
      border: 1px solid var(--ac-line);
      border-radius: var(--ac-radius);
      box-shadow: var(--ac-shadow-sm);
      text-decoration: none;
      color: var(--ac-ink);
      transition: box-shadow .22s, border-color .22s, transform .22s;
    }
    .ac-quick-link:hover {
      box-shadow: var(--ac-shadow-lg);
      border-color: #d4c8f8;
      transform: translateY(-3px);
    }
    .ac-quick-link-ico {
      width: 2.2rem;
      height: 2.2rem;
      border-radius: 9px;
      background: var(--ac-primary-soft);
      color: var(--ac-primary-d);
      display: grid;
      place-items: center;
      font-size: .88rem;
    }
    .ac-quick-link-title { font-size: .88rem; font-weight: 700; color: var(--ac-ink); }
    .ac-quick-link-sub { font-size: .76rem; color: var(--ac-muted); }

    /* ─── Activity row ─── */
    .ac-activity-row {
      display: flex;
      align-items: flex-start;
      gap: .9rem;
      padding: .9rem 0;
      border-bottom: 1px solid var(--ac-line);
    }
    .ac-activity-row:last-child { border-bottom: none; }
    .ac-activity-avatar {
      width: 2.2rem;
      height: 2.2rem;
      border-radius: 9px;
      background: var(--ac-primary-soft);
      color: var(--ac-primary-d);
      display: grid;
      place-items: center;
      font-size: .88rem;
      font-weight: 800;
      flex-shrink: 0;
    }
    .ac-activity-name { font-size: .88rem; font-weight: 700; color: var(--ac-ink); }
    .ac-activity-sub  { font-size: .78rem; color: var(--ac-muted); margin-top: .15rem; }
    .ac-activity-meta { margin-left: auto; text-align: right; flex-shrink: 0; }
    .ac-activity-date { font-size: .74rem; color: var(--ac-muted); }

    /* ─── Welcome banner ─── */
    .ac-welcome {
      background: linear-gradient(135deg, #6d28d9 0%, #7c3aed 60%, #8b5cf6 100%);
      border-radius: var(--ac-radius);
      padding: 1.75rem 1.75rem;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      margin-bottom: 1.75rem;
      box-shadow: 0 16px 36px -18px rgba(109,40,217,.7);
      position: relative;
      overflow: hidden;
    }
    .ac-welcome::before {
      content: '';
      position: absolute;
      top: -40px;
      right: -40px;
      width: 160px;
      height: 160px;
      border-radius: 50%;
      background: rgba(255,255,255,.07);
      pointer-events: none;
    }
    .ac-welcome::after {
      content: '';
      position: absolute;
      bottom: -60px;
      left: 40%;
      width: 220px;
      height: 220px;
      border-radius: 50%;
      background: rgba(255,255,255,.04);
      pointer-events: none;
    }
    .ac-welcome-title { font-size: 1.2rem; font-weight: 800; }
    .ac-welcome-sub   { font-size: .84rem; opacity: .8; margin-top: .25rem; }
    .ac-welcome-ico   { font-size: 2.2rem; opacity: .5; flex-shrink: 0; }

    /* ─── Account info list ─── */
    .ac-info-list { display: flex; flex-direction: column; gap: .65rem; }
    .ac-info-row  {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      padding: .65rem .9rem;
      border-radius: 9px;
      background: var(--ac-lav);
    }
    .ac-info-row-label { font-size: .78rem; color: var(--ac-muted); font-weight: 600; }
    .ac-info-row-value { font-size: .88rem; color: var(--ac-ink); font-weight: 700; }

    /* ─── Type tag ─── */
    .ac-type-tag {
      display: inline-flex;
      align-items: center;
      gap: .3rem;
      padding: .25rem .55rem;
      border-radius: 7px;
      background: #f8f9fc;
      border: 1px solid var(--ac-line);
      color: var(--ac-ink-2);
      font-size: .72rem;
      font-weight: 700;
    }

    /* ─── Pagination ─── */
    .ac-pager { margin-top: 1.5rem; display: flex; justify-content: center; }
  </style>
  @include('partials.rtl-styles')
</head>
<body>
<div class="ac-shell">

  @include('partials.navbar')

  {{-- Mobile horizontal tab bar --}}
  <nav class="ac-mobile-nav" aria-label="{{ __('account.navigation_aria') }}">
    <a href="{{ route('account.dashboard') }}"
       class="ac-tab {{ request()->routeIs('account.dashboard') ? 'is-active' : '' }}">
      <i class="fa-solid fa-gauge"></i> {{ __('account.overview') }}
    </a>
    <a href="{{ route('account.orders') }}"
       class="ac-tab {{ request()->routeIs('account.orders*') ? 'is-active' : '' }}">
      <i class="fa-solid fa-receipt"></i> {{ __('account.orders') }}
    </a>
    <a href="{{ route('account.enquiries') }}"
       class="ac-tab {{ request()->routeIs('account.enquiries') ? 'is-active' : '' }}">
      <i class="fa-solid fa-envelope"></i> {{ __('account.enquiries') }}
    </a>
    <a href="{{ route('account.profile') }}"
       class="ac-tab {{ request()->routeIs('account.profile') ? 'is-active' : '' }}">
      <i class="fa-solid fa-user"></i> {{ __('account.profile') }}
    </a>
    <a href="{{ route('account.security') }}"
       class="ac-tab {{ request()->routeIs('account.security') ? 'is-active' : '' }}">
      <i class="fa-solid fa-shield"></i> {{ __('account.security') }}
    </a>
    @if(auth()->user()->isApprovedMerchant())
    <a href="{{ route('account.spare-parts.index') }}"
       class="ac-tab {{ request()->routeIs('account.spare-parts*') ? 'is-active' : '' }}">
      <i class="fa-solid fa-gears"></i> {{ __('account.spare_parts') }}
    </a>
    <a href="{{ route('account.merchant-orders.index') }}"
       class="ac-tab {{ request()->routeIs('account.merchant-orders*') ? 'is-active' : '' }}">
      <i class="fa-solid fa-boxes-stacked"></i> {{ __('account.spare_parts_orders') }}
    </a>
    @endif
    <form method="POST" action="{{ route('logout') }}" class="ac-signout-form">
      @csrf
      <button type="submit" class="ac-tab is-signout-tab">
        <i class="fa-solid fa-arrow-right-from-bracket"></i> {{ __('navigation.sign_out') }}
      </button>
    </form>
  </nav>

  {{-- Main body --}}
  <div class="ac-body">

    {{-- ─── Desktop Sidebar ─── --}}
    <aside class="ac-sidebar" aria-label="{{ __('account.navigation_aria') }}">
      <div class="ac-sidebar-card">

        {{-- User avatar block --}}
        <div class="ac-avatar-block">
          <div class="ac-avatar">
            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
          </div>
          <div class="ac-user-name" title="{{ auth()->user()->name }}">
            {{ auth()->user()->name }}
          </div>
          <div class="ac-user-email" title="{{ auth()->user()->email }}">
            {{ auth()->user()->email }}
          </div>
        </div>

        {{-- Navigation --}}
        <nav class="ac-nav">
          <a href="{{ route('account.dashboard') }}"
             class="ac-nav-item {{ request()->routeIs('account.dashboard') ? 'is-active' : '' }}">
            <i class="fa-solid fa-gauge"></i>
            <span>{{ __('account.overview') }}</span>
          </a>
          <a href="{{ route('account.orders') }}"
             class="ac-nav-item {{ request()->routeIs('account.orders', 'account.orders.show') ? 'is-active' : '' }}">
            <i class="fa-solid fa-receipt"></i>
            <span>{{ __('account.my_orders') }}</span>
          </a>
          <a href="{{ route('account.enquiries') }}"
             class="ac-nav-item {{ request()->routeIs('account.enquiries') ? 'is-active' : '' }}">
            <i class="fa-solid fa-envelope"></i>
            <span>{{ __('account.my_enquiries') }}</span>
          </a>
          <a href="{{ route('account.profile') }}"
             class="ac-nav-item {{ request()->routeIs('account.profile') ? 'is-active' : '' }}">
            <i class="fa-solid fa-user"></i>
            <span>{{ __('account.profile') }}</span>
          </a>
          <a href="{{ route('account.security') }}"
             class="ac-nav-item {{ request()->routeIs('account.security') ? 'is-active' : '' }}">
            <i class="fa-solid fa-shield"></i>
            <span>{{ __('account.security') }}</span>
          </a>

          @if(auth()->user()->isApprovedMerchant())
          <div class="ac-nav-divider"></div>
          <a href="{{ route('account.spare-parts.index') }}"
             class="ac-nav-item {{ request()->routeIs('account.spare-parts*') ? 'is-active' : '' }}">
            <i class="fa-solid fa-gears"></i>
            <span>{{ __('account.my_spare_parts') }}</span>
          </a>
          <a href="{{ route('account.merchant-orders.index') }}"
             class="ac-nav-item {{ request()->routeIs('account.merchant-orders*') ? 'is-active' : '' }}">
            <i class="fa-solid fa-boxes-stacked"></i>
            <span>{{ __('account.spare_parts_orders') }}</span>
          </a>
          @endif

          <div class="ac-nav-divider"></div>

          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="ac-nav-item is-signout">
              <i class="fa-solid fa-arrow-right-from-bracket"></i>
              <span>{{ __('navigation.sign_out') }}</span>
            </button>
          </form>
        </nav>

      </div>
    </aside>

    {{-- ─── Main content ─── --}}
    <main class="ac-main">

      {{-- Page heading --}}
      <div class="ac-page-header">
        <div class="ac-page-header-inner">
          <div class="ac-heading-bar" aria-hidden="true"></div>
          <h1 class="ac-heading">@yield('heading', __('account.overview'))</h1>
        </div>
      </div>

      {{-- Flash messages --}}
      @if(session('success'))
        <div class="ac-flash is-success" role="alert">
          <i class="fa-solid fa-circle-check"></i>
          {{ session('success') }}
        </div>
      @endif
      @if(session('error'))
        <div class="ac-flash is-error" role="alert">
          <i class="fa-solid fa-circle-exclamation"></i>
          {{ session('error') }}
        </div>
      @endif

      @yield('content')

    </main>

  </div>{{-- /.ac-body --}}

  @include('partials.footer')

</div>{{-- /.ac-shell --}}

@stack('scripts')
</body>
</html>
