<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — MOTOVEX Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        /* ── Reset & Base ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg:      #0b0913;
            --surface: #131020;
            --line:    rgba(255,255,255,.08);
            --muted:   #9b96ad;
            --text:    #f0edfb;
            --purple:  #9b5cff;
            --pink:    #ed5bb7;
            --lime:    #b7f36b;
            --sidebar: 248px;
        }
        html, body { height: 100%; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            overflow-x: hidden;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }
        body::before {
            content: "";
            position: fixed; inset: 0; z-index: 0;
            pointer-events: none;
            background:
                radial-gradient(ellipse at 75% 0%,   rgba(130,65,220,.14),  transparent 32%),
                radial-gradient(ellipse at 5%  90%,  rgba(237,91,183,.07),  transparent 28%);
        }
        a  { color: inherit; text-decoration: none; }
        button { font: inherit; cursor: pointer; }
        img { display: block; max-width: 100%; }

        /* ── Layout ── */
        .admin-layout {
            position: relative; z-index: 1;
            display: grid;
            grid-template-columns: var(--sidebar) minmax(0,1fr);
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .admin-sidebar {
            position: sticky; top: 0; height: 100vh;
            padding: 22px 14px 16px;
            background: rgba(10,8,20,.94);
            border-right: 1px solid var(--line);
            backdrop-filter: blur(20px);
            display: flex; flex-direction: column;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #2e2647 transparent;
        }
        .admin-sidebar::-webkit-scrollbar { width: 4px; }
        .admin-sidebar::-webkit-scrollbar-thumb { background: #2e2647; border-radius: 4px; }

        .sidebar-logo { display: flex; align-items: center; gap: 10px; padding: 2px 6px 26px; }
        .logo-mark {
            width: 38px; height: 38px; border-radius: 12px; flex-shrink: 0;
            background: linear-gradient(145deg, #b16aff, #7439dc 65%, #ec5dbb);
            display: grid; place-items: center;
            box-shadow: 0 5px 20px rgba(140,72,232,.3);
            color: #fff; font-size: 16px;
        }
        .logo-name  { font-size: 16px; letter-spacing: 1.4px; font-weight: 800; line-height: 1.2; }
        .logo-name span { color: #bb8bff; }
        .logo-sub   { font-size: 8px; color: #6e6888; letter-spacing: 2px; margin-top: 2px; text-transform: uppercase; }

        .nav-section { margin-bottom: 2px; }
        .nav-label {
            font-size: 9.5px; font-weight: 700; letter-spacing: 1.6px;
            text-transform: uppercase; color: #4f4966;
            padding: 14px 10px 7px;
        }
        .sidebar-nav { display: flex; flex-direction: column; gap: 2px; }
        .sidebar-nav a {
            display: flex; align-items: center; gap: 11px;
            padding: 9px 11px;
            color: #9692b0; font-size: 13px; font-weight: 500;
            border: 1px solid transparent; border-radius: 9px;
            transition: color .15s, background .15s, border-color .15s;
        }
        .sidebar-nav a i { width: 16px; text-align: center; font-size: 13px; color: #6e6888; flex-shrink: 0; }
        .sidebar-nav a:hover { background: rgba(255,255,255,.05); color: #fff; }
        .sidebar-nav a:hover i { color: #c18bff; }
        .sidebar-nav a.active {
            color: #fff; font-weight: 600;
            background: linear-gradient(100deg, rgba(142,75,229,.2), rgba(182,90,243,.06));
            border-color: rgba(161,106,255,.22);
            box-shadow: inset 3px 0 0 #b77aff;
        }
        .sidebar-nav a.active i { color: #c18bff; }

        /* Sidebar bottom */
        .sidebar-bottom { margin-top: auto; padding-top: 10px; }
        .help-card {
            border: 1px solid rgba(168,106,255,.18);
            background: linear-gradient(135deg, rgba(162,92,255,.09), rgba(236,91,183,.04));
            border-radius: 13px; padding: 13px; margin-bottom: 14px;
        }
        .help-icon {
            width: 26px; height: 26px; display: grid; place-items: center;
            background: rgba(163,93,245,.18); color: #cdaaff;
            border-radius: 7px; margin-bottom: 8px; font-size: 11px;
        }
        .help-card strong { font-size: 11.5px; font-weight: 600; }
        .help-card p { font-size: 10.5px; line-height: 1.5; color: var(--muted); margin: 4px 0 9px; }
        .help-card a { font-size: 11px; color: #c59aff; font-weight: 600; }
        .sidebar-profile {
            display: flex; align-items: center; gap: 9px;
            padding: 13px 3px 0;
            border-top: 1px solid var(--line);
        }
        .profile-avatar {
            width: 34px; height: 34px; border-radius: 10px; flex-shrink: 0;
            background: linear-gradient(135deg, #ef6fb7, #7648dc);
            display: grid; place-items: center;
            font-weight: 700; font-size: 13px;
        }
        .profile-info { flex: 1; min-width: 0; overflow: hidden; }
        .profile-info strong { display: block; font-size: 11.5px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .profile-info span   { font-size: 10px; color: var(--muted); }
        .profile-signout { background: none; border: 0; color: #5e5876; padding: 5px; transition: color .15s; }
        .profile-signout:hover { color: #d4cfea; }

        /* ── Main Content ── */
        .admin-content { min-width: 0; display: flex; flex-direction: column; }
        .admin-main { flex: 1; padding: 26px clamp(16px,3vw,46px) 40px; }

        /* ── Top bar ── */
        .admin-topbar {
            display: flex; justify-content: space-between; align-items: center;
            gap: 14px; margin-bottom: 26px;
        }
        .topbar-crumb { font-size: 11px; color: #635e7a; margin-bottom: 3px; }
        .topbar-crumb span { color: #c59aff; }
        .topbar-title { font-size: 22px; letter-spacing: -.5px; font-weight: 800; }
        .top-actions { display: flex; align-items: center; gap: 8px; }
        .admin-btn-ghost {
            height: 38px; padding: 0 13px;
            border: 1px solid var(--line); background: rgba(255,255,255,.04);
            border-radius: 10px; color: #ccc7de;
            display: inline-flex; align-items: center; gap: 7px;
            font-size: 11px; font-weight: 600;
            transition: border-color .15s, background .15s;
        }
        .admin-btn-ghost i { color: #c49aff; }
        .admin-btn-ghost:hover { border-color: rgba(161,106,255,.45); background: rgba(161,106,255,.07); color: #fff; }

        /* ── Flash messages ── */
        .flash-success, .flash-error {
            display: flex; align-items: flex-start; gap: 10px;
            border-radius: 11px; padding: 11px 14px;
            font-size: 13px; margin-bottom: 18px;
        }
        .flash-success { border: 1px solid rgba(183,243,107,.2); background: rgba(183,243,107,.07); color: var(--lime); }
        .flash-error   { border: 1px solid rgba(248,113,113,.2); background: rgba(248,113,113,.07); color: #fca5a5; }

        /* ── Shared page elements ── */
        .page-header {
            display: flex; align-items: flex-start; justify-content: space-between;
            gap: 14px; margin-bottom: 22px; flex-wrap: wrap;
        }
        .page-header h1 { font-size: 20px; font-weight: 800; letter-spacing: -.4px; }
        .page-header p  { font-size: 12px; color: var(--muted); margin-top: 3px; }

        /* Primary / ghost action buttons (used in all index pages) */
        .btn-primary {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 16px; border-radius: 10px; border: 0;
            background: linear-gradient(105deg, #9b5cff, #dc5dbd);
            color: #fff; font-size: 12px; font-weight: 700;
            box-shadow: 0 6px 18px rgba(155,92,255,.25);
            transition: filter .18s, transform .18s;
            white-space: nowrap;
        }
        .btn-primary:hover { filter: brightness(1.1); transform: translateY(-1px); }

        .btn-ghost {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 16px; border-radius: 10px;
            border: 1px solid var(--line); background: rgba(255,255,255,.04);
            color: #ccc7de; font-size: 12px; font-weight: 600;
            transition: border-color .15s, background .15s, color .15s;
            white-space: nowrap;
        }
        .btn-ghost:hover { border-color: rgba(161,106,255,.4); background: rgba(161,106,255,.07); color: #fff; }

        .btn-danger {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 12px; border-radius: 8px; border: 0;
            background: rgba(248,113,113,.12); color: #fca5a5;
            font-size: 11px; font-weight: 600;
            border: 1px solid rgba(248,113,113,.2);
            transition: background .15s;
        }
        .btn-danger:hover { background: rgba(248,113,113,.22); }

        /* ── Tables ── */
        .admin-table-wrap {
            border: 1px solid var(--line);
            border-radius: 16px;
            overflow: hidden;
            background: linear-gradient(145deg, #131020, #0e0c1a);
        }
        .admin-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .admin-table thead tr {
            border-bottom: 1px solid var(--line);
        }
        .admin-table th {
            padding: 13px 18px;
            font-size: 10px; font-weight: 700;
            letter-spacing: 1.2px; text-transform: uppercase;
            color: #6e6888; text-align: left;
        }
        .admin-table tbody tr {
            border-bottom: 1px solid rgba(255,255,255,.04);
            transition: background .15s;
        }
        .admin-table tbody tr:last-child { border-bottom: 0; }
        .admin-table tbody tr:hover { background: rgba(255,255,255,.03); }
        .admin-table td { padding: 14px 18px; color: #c8c3da; vertical-align: middle; }
        .admin-table .td-actions { display: flex; align-items: center; justify-content: flex-end; gap: 8px; }
        .tbl-link         { font-size: 11px; font-weight: 600; color: #c49aff; transition: color .15s; }
        .tbl-link:hover   { color: #e0c8ff; text-decoration: underline; }
        .tbl-link-danger  { font-size: 11px; font-weight: 600; color: #f87171; transition: color .15s; background: none; border: 0; padding: 0; }
        .tbl-link-danger:hover { color: #fca5a5; text-decoration: underline; }
        .tbl-link-muted   { font-size: 11px; font-weight: 500; color: #7a748f; transition: color .15s; }
        .tbl-link-muted:hover  { color: #d4cfea; }

        /* ── Badges ── */
        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 3px 9px; border-radius: 20px;
            font-size: 10px; font-weight: 700; white-space: nowrap;
        }
        .badge::before { content: ""; width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }
        .badge-green  { color: #86efac; background: rgba(134,239,172,.1); border: 1px solid rgba(134,239,172,.15); }
        .badge-green::before  { background: #4ade80; }
        .badge-yellow { color: #fde68a; background: rgba(253,230,138,.1); border: 1px solid rgba(253,230,138,.15); }
        .badge-yellow::before { background: #fbbf24; }
        .badge-blue   { color: #93c5fd; background: rgba(147,197,253,.1); border: 1px solid rgba(147,197,253,.15); }
        .badge-blue::before   { background: #60a5fa; }
        .badge-red    { color: #fca5a5; background: rgba(252,165,165,.1); border: 1px solid rgba(252,165,165,.15); }
        .badge-red::before    { background: #f87171; }
        .badge-muted  { color: #9b96ad; background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.08); }
        .badge-muted::before  { background: #6b6480; }
        .badge-purple { color: #c4b5fd; background: rgba(196,181,253,.1); border: 1px solid rgba(196,181,253,.15); }
        .badge-purple::before { background: #a78bfa; }

        /* ── Panels / Cards ── */
        .admin-panel {
            border: 1px solid var(--line);
            border-radius: 16px;
            background: linear-gradient(145deg, #131020, #0e0c1a);
            padding: 22px;
        }
        .admin-panel-title { font-size: 14px; font-weight: 700; margin-bottom: 3px; }
        .admin-panel-sub   { font-size: 11px; color: var(--muted); }

        /* ── Forms ── */
        .form-label   { display: block; font-size: 11px; font-weight: 600; color: #a09aaf; margin-bottom: 6px; }
        .form-input, .form-select, .form-textarea {
            width: 100%;
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.1);
            color: var(--text);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13px; font-family: inherit;
            transition: border-color .18s, box-shadow .18s;
            outline: none;
        }
        .form-input::placeholder, .form-textarea::placeholder { color: rgba(196,181,253,.3); }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: rgba(155,92,255,.7);
            box-shadow: 0 0 0 3px rgba(155,92,255,.15);
        }
        .form-input:hover, .form-select:hover, .form-textarea:hover {
            border-color: rgba(196,181,253,.2);
        }
        .form-select option { background: #1a1528; }
        .form-textarea { resize: vertical; min-height: 100px; line-height: 1.6; }
        .form-error { margin-top: 5px; font-size: 11px; font-weight: 600; color: #f87171; }

        /* ── Pagination ── */
        .admin-pagination { padding: 14px 18px; border-top: 1px solid var(--line); }
        .admin-pagination nav { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
        .admin-pagination nav span, .admin-pagination nav a {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 32px; height: 32px; padding: 0 8px;
            border-radius: 8px; font-size: 12px; font-weight: 500;
            border: 1px solid var(--line);
            background: rgba(255,255,255,.03);
            color: var(--muted);
            transition: border-color .15s, background .15s, color .15s;
        }
        .admin-pagination nav a:hover { border-color: rgba(161,106,255,.4); background: rgba(161,106,255,.08); color: #fff; }
        .admin-pagination nav [aria-current="page"] span,
        .admin-pagination nav span[aria-current="page"] {
            border-color: rgba(155,92,255,.5); background: rgba(155,92,255,.18); color: #e0c8ff;
        }

        /* ── Empty state ── */
        .empty-state { padding: 60px 20px; text-align: center; }
        .empty-state i { font-size: 36px; color: #3d3655; margin-bottom: 14px; }
        .empty-state p { font-size: 13px; color: var(--muted); }

        /* ── Mobile topbar ── */
        .mobile-topbar { display: none; }

        /* ── Responsive ── */
        @media (max-width: 1024px) { :root { --sidebar: 215px; } }
        @media (max-width: 768px) {
            .admin-layout { grid-template-columns: 1fr; }
            .admin-sidebar {
                position: fixed; left: 0; top: 0; z-index: 50;
                transform: translateX(-100%); transition: transform .26s ease;
            }
            .admin-sidebar.open { transform: translateX(0); }
            .sidebar-overlay {
                display: none; position: fixed; inset: 0; z-index: 40;
                background: rgba(0,0,0,.65);
            }
            .sidebar-overlay.show { display: block; }
            .mobile-topbar {
                display: flex; align-items: center; justify-content: space-between;
                padding: 12px 15px;
                border-bottom: 1px solid var(--line);
                background: rgba(10,8,20,.94);
                position: sticky; top: 0; z-index: 30;
            }
            .admin-topbar { display: none; }
            .admin-main { padding: 16px 14px 30px; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { transition: none !important; animation: none !important; }
        }
    </style>
    @yield('styles')
</head>
<body>

<div class="admin-layout">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="admin-sidebar" id="admin-sidebar">
        <div>
            <div class="sidebar-logo">
                <div class="logo-mark"><i class="fa-solid fa-motorcycle"></i></div>
                <div>
                    <div class="logo-name">MOTO<span>VEX</span></div>
                    <div class="logo-sub">Control Center</div>
                </div>
            </div>

            <div class="nav-section">
                <div class="nav-label">Workspace</div>
                <nav class="sidebar-nav">
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-grid-2"></i> Dashboard
                    </a>
                </nav>
            </div>

            <div class="nav-section">
                <div class="nav-label">Catalogue</div>
                <nav class="sidebar-nav">
                    <a href="{{ route('admin.brands.index') }}" class="{{ request()->routeIs('admin.brands*') ? 'active' : '' }}">
                        <i class="fa-regular fa-copyright"></i> Brands
                    </a>
                    <a href="{{ route('admin.motorcycles.index') }}" class="{{ request()->routeIs('admin.motorcycles*') ? 'active' : '' }}">
                        <i class="fa-solid fa-motorcycle"></i> Motorcycles
                    </a>
                    <a href="{{ route('admin.promotions.index') }}" class="{{ request()->routeIs('admin.promotions*') ? 'active' : '' }}">
                        <i class="fa-solid fa-tags"></i> Promotions
                    </a>
                    <a href="{{ route('admin.showrooms.index') }}" class="{{ request()->routeIs('admin.showrooms*') ? 'active' : '' }}">
                        <i class="fa-solid fa-shop"></i> Showrooms
                    </a>
                </nav>
            </div>

            @if(auth()->user()->isAdmin())
            <div class="nav-section">
                <div class="nav-label">Management</div>
                <nav class="sidebar-nav">
                    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users"></i> Users
                    </a>
                    <a href="{{ route('admin.enquiries.index') }}" class="{{ request()->routeIs('admin.enquiries*') ? 'active' : '' }}">
                        <i class="fa-solid fa-envelope"></i> Enquiries
                    </a>
                </nav>
            </div>
            @endif

            <div class="nav-section">
                <div class="nav-label">Links</div>
                <nav class="sidebar-nav">
                    <a href="{{ route('home') }}" target="_blank">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Public Website
                    </a>
                </nav>
            </div>
        </div>

        <div class="sidebar-bottom">
            <div class="help-card">
                <div class="help-icon"><i class="fa-regular fa-circle-question"></i></div>
                <strong>Need help?</strong>
                <p>Access support and platform guides.</p>
                <a href="{{ route('contact') }}">Open help center <i class="fa-solid fa-arrow-right" style="font-size:9px"></i></a>
            </div>
            <div class="sidebar-profile">
                <div class="profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="profile-info">
                    <strong>{{ auth()->user()->name }}</strong>
                    <span>{{ auth()->user()->roleName() }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="profile-signout" title="Sign out"><i class="fa-solid fa-arrow-right-from-bracket"></i></button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ===== CONTENT ===== --}}
    <div class="admin-content">

        {{-- Mobile Topbar --}}
        <div class="mobile-topbar">
            <button id="sidebar-open" style="background:none;border:0;color:#c49aff;font-size:17px;">
                <i class="fa-solid fa-bars"></i>
            </button>
            <span style="font-size:14px;font-weight:800;letter-spacing:1px">MOTO<span style="color:#bb8bff">VEX</span></span>
            <a href="{{ route('home') }}" style="font-size:11px;color:#c49aff;"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
        </div>

        <main class="admin-main">

            {{-- Desktop Top bar --}}
            <header class="admin-topbar">
                <div>
                    <div class="topbar-crumb">Admin / <span>@yield('title', 'Dashboard')</span></div>
                    <div class="topbar-title">@yield('title', 'Dashboard')</div>
                </div>
                <div class="top-actions">
                    <a href="{{ route('home') }}" class="admin-btn-ghost" target="_blank">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> View Site
                    </a>
                </div>
            </header>

            {{-- Flash --}}
            @if(session('success'))
                <div class="flash-success">
                    <i class="fa-solid fa-circle-check" style="margin-top:1px;flex-shrink:0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="flash-error">
                    <i class="fa-solid fa-circle-xmark" style="margin-top:1px;flex-shrink:0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<div class="sidebar-overlay" id="sidebar-overlay"></div>

<script>
(function () {
    const sidebar = document.getElementById('admin-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const openBtn = document.getElementById('sidebar-open');

    function open()  { sidebar.classList.add('open');  overlay.classList.add('show'); }
    function close() { sidebar.classList.remove('open'); overlay.classList.remove('show'); }

    openBtn?.addEventListener('click', open);
    overlay.addEventListener('click', close);
})();
</script>

@yield('scripts')
</body>
</html>
