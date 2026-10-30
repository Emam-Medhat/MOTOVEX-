<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — MOTOVEX Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @stack('head')
    <style>
        /* ── Reset & Base ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg:      #f5f7fc;
            --surface: #ffffff;
            --line:    #e2e8f0;
            --muted:   #64748b;
            --text:    #172033;
            --subtext: #475569;
            --purple:  #7c3aed;
            --purple-strong: #6d28d9;
            --purple-light:  #8b5cf6;
            --purple-bg:     #f3e8ff;
            --purple-border: #e9d5ff;
            --sidebar: 250px;
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
        a  { color: inherit; text-decoration: none; }
        button { font: inherit; cursor: pointer; }
        img { display: block; max-width: 100%; }

        /* ── Layout ── */
        .admin-layout {
            display: grid;
            grid-template-columns: var(--sidebar) minmax(0,1fr);
            min-height: 100vh;
        }

        /* ════════════════════════════════
           SIDEBAR
        ════════════════════════════════ */
        .admin-sidebar {
            position: sticky; top: 0; height: 100vh;
            background: var(--surface);
            border-right: 1px solid var(--line);
            display: flex; flex-direction: column;
            overflow: hidden;
        }
        .sidebar-scroll {
            flex: 1; overflow-y: auto; padding-bottom: 8px;
            scrollbar-width: thin; scrollbar-color: #e2e8f0 transparent;
        }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

        /* Logo */
        .sidebar-logo {
            display: flex; align-items: center; gap: 11px;
            padding: 17px 18px;
            border-bottom: 1px solid var(--line);
        }
        .logo-mark {
            width: 38px; height: 38px; border-radius: 11px; flex-shrink: 0;
            background: var(--purple);
            display: grid; place-items: center;
            box-shadow: 0 4px 12px rgba(124,58,237,.28);
            color: #fff; font-size: 16px;
        }
        .logo-text  { min-width: 0; }
        .logo-name  { font-size: 15px; font-weight: 800; letter-spacing: .6px; color: var(--text); line-height: 1.15; }
        .logo-sub   { font-size: 8.5px; color: var(--muted); letter-spacing: 2.2px; margin-top: 2px; text-transform: uppercase; font-weight: 600; }

        /* Nav */
        .nav-section { padding: 14px 12px 2px; }
        .nav-label {
            font-size: 9.5px; font-weight: 700; letter-spacing: 1.4px;
            text-transform: uppercase; color: #94a3b8;
            padding: 0 10px 8px;
        }
        .sidebar-nav { display: flex; flex-direction: column; gap: 2px; }
        .sidebar-nav a {
            position: relative;
            display: flex; align-items: center; gap: 11px;
            min-height: 38px; padding: 9px 11px;
            color: var(--subtext); font-size: 13px; font-weight: 500;
            border-radius: 9px;
            transition: background .16s, color .16s;
        }
        .sidebar-nav a i { width: 17px; text-align: center; font-size: 13.5px; color: #94a3b8; flex-shrink: 0; transition: color .16s; }
        .sidebar-nav a:hover { background: #f8fafc; color: var(--text); }
        .sidebar-nav a:hover i { color: var(--purple); }
        .sidebar-nav a.active {
            background: var(--purple-bg);
            color: var(--purple-strong);
            font-weight: 600;
        }
        .sidebar-nav a.active i { color: var(--purple); }
        .sidebar-nav a.active::before {
            content: ""; position: absolute; left: -12px; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 20px; border-radius: 0 3px 3px 0;
            background: var(--purple);
        }
        .sidebar-nav a:focus-visible { outline: 2px solid var(--purple); outline-offset: 2px; }

        /* Sidebar bottom */
        .sidebar-bottom { margin-top: auto; padding: 12px; border-top: 1px solid var(--line); }
        .help-card {
            border: 1px solid var(--purple-border);
            background: linear-gradient(180deg, #faf5ff, var(--purple-bg));
            border-radius: 12px; padding: 14px; margin-bottom: 12px;
        }
        .help-card .help-icon {
            width: 28px; height: 28px; border-radius: 8px;
            display: grid; place-items: center;
            background: #ede9fe; color: var(--purple);
            margin-bottom: 9px; font-size: 12px;
        }
        .help-card strong { display: block; font-size: 12.5px; font-weight: 600; color: var(--text); }
        .help-card p { font-size: 11px; color: var(--muted); margin: 4px 0 10px; line-height: 1.5; }
        .help-card a { display: inline-flex; align-items: center; gap: 6px; font-size: 11px; color: var(--purple); font-weight: 600; }
        .help-card a i { font-size: 9px; transition: transform .16s; }
        .help-card a:hover { color: var(--purple-strong); }
        .help-card a:hover i { transform: translateX(2px); }

        .sidebar-profile {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 10px; border-radius: 11px;
            background: #f8fafc; border: 1px solid var(--line);
        }
        .profile-avatar {
            width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
            background: var(--purple);
            display: grid; place-items: center;
            font-weight: 700; font-size: 13px; color: #fff;
        }
        .profile-info { flex: 1; min-width: 0; }
        .profile-info strong { display: block; font-size: 12px; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .profile-info span   { font-size: 10.5px; color: var(--muted); }
        .profile-signout { background: none; border: 0; color: #94a3b8; padding: 6px; border-radius: 7px; transition: color .15s, background .15s; }
        .profile-signout:hover { color: #ef4444; background: #fef2f2; }
        .profile-signout:focus-visible { outline: 2px solid var(--purple); outline-offset: 2px; }

        /* ════════════════════════════════
           MAIN CONTENT
        ════════════════════════════════ */
        .admin-content { min-width: 0; display: flex; flex-direction: column; }

        /* Top header bar */
        .admin-header {
            height: 60px;
            background: var(--surface);
            border-bottom: 1px solid var(--line);
            display: flex; align-items: center; justify-content: space-between;
            gap: 14px; padding: 0 clamp(16px, 2.5vw, 36px);
            position: sticky; top: 0; z-index: 20;
        }
        .topbar-crumb { display: flex; align-items: center; gap: 8px; font-size: 12.5px; color: var(--muted); min-width: 0; }
        .topbar-crumb a { color: var(--muted); transition: color .15s; }
        .topbar-crumb a:hover { color: var(--purple); }
        .topbar-crumb i { font-size: 9px; color: #cbd5e1; }
        .topbar-crumb .crumb-current { color: var(--text); font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .admin-header-actions { display: flex; align-items: center; gap: 8px; }
        .admin-btn-ghost {
            height: 36px; padding: 0 14px;
            border: 1px solid var(--line); background: var(--surface);
            border-radius: 9px; color: var(--subtext);
            display: inline-flex; align-items: center; gap: 7px;
            font-size: 12px; font-weight: 600;
            transition: border-color .15s, color .15s, background .15s;
        }
        .admin-btn-ghost:hover { border-color: var(--purple-border); background: var(--purple-bg); color: var(--purple-strong); }
        .admin-btn-ghost:focus-visible { outline: 2px solid var(--purple); outline-offset: 2px; }

        /* Page content area */
        .admin-main { flex: 1; padding: 28px clamp(16px,2.5vw,36px) 48px; }

        /* Page header */
        .page-header {
            display: flex; align-items: flex-start; justify-content: space-between;
            gap: 14px; margin-bottom: 24px; flex-wrap: wrap;
        }
        .page-header h1 { font-size: 22px; font-weight: 800; letter-spacing: -.5px; color: var(--text); }
        .page-header p  { font-size: 13px; color: var(--muted); margin-top: 3px; }

        /* ── Buttons ── */
        .btn-primary {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 16px; border-radius: 9px; border: 0;
            background: var(--purple);
            color: #fff; font-size: 12px; font-weight: 700;
            box-shadow: 0 4px 14px rgba(124,58,237,.22);
            transition: background .18s, box-shadow .18s, transform .18s; white-space: nowrap;
        }
        .btn-primary:hover { background: var(--purple-strong); box-shadow: 0 6px 18px rgba(124,58,237,.32); transform: translateY(-1px); }

        .btn-ghost {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 16px; border-radius: 9px;
            border: 1px solid var(--line); background: var(--surface);
            color: var(--subtext); font-size: 12px; font-weight: 600;
            transition: border-color .15s, color .15s, background .15s;
            white-space: nowrap;
        }
        .btn-ghost:hover { border-color: var(--purple-border); background: var(--purple-bg); color: var(--purple-strong); }

        .btn-danger {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 7px 12px; border-radius: 8px; border: 0;
            background: #fef2f2; color: #dc2626;
            font-size: 11px; font-weight: 600;
            border: 1px solid #fecaca;
            transition: background .15s;
        }
        .btn-danger:hover { background: #fee2e2; }

        /* ── Flash Messages ── */
        .flash-success, .flash-error {
            display: flex; align-items: flex-start; gap: 10px;
            border-radius: 10px; padding: 11px 14px;
            font-size: 13px; margin-bottom: 20px;
        }
        .flash-success { border: 1px solid #bbf7d0; background: #f0fdf4; color: #166534; }
        .flash-error   { border: 1px solid #fecaca; background: #fef2f2; color: #991b1b; }

        /* ── Tables ── */
        .admin-table-wrap {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 12px;
            overflow: hidden;
        }
        .admin-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .admin-table thead tr { border-bottom: 1px solid var(--line); background: #f8fafc; }
        .admin-table th {
            padding: 12px 18px;
            font-size: 11px; font-weight: 600;
            letter-spacing: .6px; text-transform: uppercase;
            color: var(--muted); text-align: left;
        }
        .admin-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background .15s;
        }
        .admin-table tbody tr:last-child { border-bottom: 0; }
        .admin-table tbody tr:hover { background: #f8fafc; }
        .admin-table td { padding: 14px 18px; color: var(--subtext); vertical-align: middle; }
        .admin-table .td-actions { display: flex; align-items: center; justify-content: flex-end; gap: 4px; }

        /* Action icon buttons in table */
        .tbl-btn {
            width: 32px; height: 32px; border-radius: 7px;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 12px; border: 0; background: transparent;
            color: #94a3b8; transition: background .15s, color .15s; cursor: pointer;
        }
        .tbl-btn:hover              { background: #f1f5f9; color: var(--text); }
        .tbl-btn.edit:hover         { background: var(--purple-bg); color: var(--purple); }
        .tbl-btn.view:hover         { background: #f0fdf4; color: #16a34a; }
        .tbl-btn.danger:hover       { background: #fef2f2; color: #dc2626; }

        /* Legacy link styles (keep for compat) */
        .tbl-link        { font-size: 11px; font-weight: 600; color: var(--purple); transition: color .15s; }
        .tbl-link:hover  { color: #6d28d9; text-decoration: underline; }
        .tbl-link-danger { font-size: 11px; font-weight: 600; color: #dc2626; background: none; border: 0; padding: 0; transition: color .15s; }
        .tbl-link-danger:hover { color: #991b1b; }
        .tbl-link-muted  { font-size: 11px; font-weight: 500; color: var(--muted); transition: color .15s; }
        .tbl-link-muted:hover { color: var(--text); }

        /* Hover actions visibility */
        .admin-table .row-hover-actions { opacity: 0; transition: opacity .15s; }
        .admin-table tr:hover .row-hover-actions { opacity: 1; }

        /* ── Badges ── */
        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 3px 9px; border-radius: 20px;
            font-size: 11px; font-weight: 600; white-space: nowrap;
        }
        .badge::before { content: ""; width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }
        .badge-green  { color: #15803d; background: #f0fdf4; border: 1px solid #bbf7d0; }
        .badge-green::before  { background: #4ade80; }
        .badge-yellow { color: #92400e; background: #fffbeb; border: 1px solid #fde68a; }
        .badge-yellow::before { background: #fbbf24; }
        .badge-blue   { color: #1d4ed8; background: #eff6ff; border: 1px solid #bfdbfe; }
        .badge-blue::before   { background: #60a5fa; }
        .badge-red    { color: #991b1b; background: #fef2f2; border: 1px solid #fecaca; }
        .badge-red::before    { background: #f87171; }
        .badge-muted  { color: var(--muted); background: #f8fafc; border: 1px solid var(--line); }
        .badge-muted::before  { background: #94a3b8; }
        .badge-purple { color: var(--purple); background: var(--purple-bg); border: 1px solid var(--purple-border); }
        .badge-purple::before { background: #a78bfa; }

        /* ── Panels / Cards ── */
        .admin-panel {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 22px;
        }
        .admin-panel-title { font-size: 15px; font-weight: 700; color: var(--text); margin-bottom: 3px; }
        .admin-panel-sub   { font-size: 12px; color: var(--muted); }

        /* ── Forms ── */
        .form-label   { display: block; font-size: 12px; font-weight: 600; color: var(--subtext); margin-bottom: 6px; }
        .form-input, .form-select, .form-textarea {
            width: 100%;
            background: var(--surface);
            border: 1px solid var(--line);
            color: var(--text);
            border-radius: 9px;
            padding: 9px 13px;
            font-size: 13px; font-family: inherit;
            transition: border-color .18s, box-shadow .18s;
            outline: none;
        }
        .form-input::placeholder, .form-textarea::placeholder { color: #94a3b8; }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--purple-light);
            box-shadow: 0 0 0 3px rgba(139,92,246,.12);
        }
        .form-input:hover:not(:focus), .form-select:hover:not(:focus), .form-textarea:hover:not(:focus) {
            border-color: var(--purple-border);
        }
        .form-textarea { resize: vertical; min-height: 100px; line-height: 1.6; }
        .form-error { margin-top: 5px; font-size: 11px; font-weight: 600; color: #dc2626; }

        /* ── Pagination ── */
        .admin-pagination { padding: 14px 18px; border-top: 1px solid var(--line); }
        .admin-pagination nav { display: flex; align-items: center; gap: 5px; flex-wrap: wrap; }
        .admin-pagination nav span, .admin-pagination nav a {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 32px; height: 32px; padding: 0 8px;
            border-radius: 7px; font-size: 12px; font-weight: 500;
            border: 1px solid var(--line);
            background: var(--surface);
            color: var(--muted);
            transition: border-color .15s, background .15s, color .15s;
        }
        .admin-pagination nav a:hover { border-color: var(--purple-border); background: var(--purple-bg); color: var(--purple); }
        .admin-pagination nav [aria-current="page"] span,
        .admin-pagination nav span[aria-current="page"] {
            border-color: var(--purple); background: var(--purple); color: #fff;
        }

        /* ── Empty state ── */
        .empty-state { padding: 60px 20px; text-align: center; }
        .empty-state i { font-size: 40px; color: #cbd5e1; margin-bottom: 14px; }
        .empty-state p { font-size: 13px; color: var(--muted); }

        /* ── Mobile topbar ── */
        .mobile-topbar { display: none; }
        .mobile-menu-btn { background: none; border: 0; color: var(--purple); font-size: 18px; padding: 6px 8px; border-radius: 8px; }
        .mobile-brand { font-size: 15px; font-weight: 800; letter-spacing: .5px; color: var(--text); }
        .mobile-site-link { font-size: 13px; color: var(--purple); padding: 6px 8px; border-radius: 8px; }
        .mobile-menu-btn:focus-visible,
        .mobile-site-link:focus-visible { outline: 2px solid var(--purple); outline-offset: 2px; }

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
                background: rgba(0,0,0,.3);
            }
            .sidebar-overlay.show { display: block; }
            .mobile-topbar {
                display: flex; align-items: center; justify-content: space-between;
                padding: 0 16px; height: 56px;
                border-bottom: 1px solid var(--line);
                background: var(--surface);
                position: sticky; top: 0; z-index: 30;
            }
            .admin-header { display: none; }
            .admin-main { padding: 16px 14px 30px; }
        }

        /* ── Admin Action Buttons (Professional Refactor) ── */
        .admin-btn-sm {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            cursor: pointer;
            line-height: 1;
            white-space: nowrap;
        }
        .admin-btn-sm i {
            font-size: 10px;
        }

        /* View - Indigo */
        .btn-view-alt {
            background-color: #EEF2FF;
            color: #4338CA;
            border-color: #E0E7FF;
        }
        .btn-view-alt:hover {
            background-color: #E0E7FF;
            border-color: #C7D2FE;
        }

        /* Edit - Purple */
        .btn-edit-alt {
            background-color: #F3E8FF;
            color: #7C3AED;
            border-color: #E9D5FF;
        }
        .btn-edit-alt:hover {
            background-color: #E9D5FF;
            border-color: #DDD6FE;
        }

        /* Suspend - Amber */
        .btn-suspend-alt {
            background-color: #FFF7ED;
            color: #C2410C;
            border-color: #FFEDD5;
        }
        .btn-suspend-alt:hover {
            background-color: #FFEDD5;
            border-color: #FED7AA;
        }

        /* Reactivate/Success - Green */
        .btn-success-alt {
            background-color: #ECFDF5;
            color: #047857;
            border-color: #D1FAE5;
        }
        .btn-success-alt:hover {
            background-color: #D1FAE5;
            border-color: #A7F3D0;
        }

        /* Delete - Red */
        .btn-delete-alt {
            background-color: #FEF2F2;
            color: #DC2626;
            border-color: #FEE2E2;
        }
        .btn-delete-alt:hover {
            background-color: #FEE2E2;
            border-color: #FECACA;
        }

        .admin-btn-sm:focus-visible {
            outline: 2px solid var(--purple);
            outline-offset: 2px;
        }
    </style>
    @yield('styles')
</head>
<body>

<div class="admin-layout">

    {{-- ═══════════ SIDEBAR ═══════════ --}}
    <aside class="admin-sidebar" id="admin-sidebar" aria-label="Admin navigation">

        {{-- Logo --}}
        <div class="sidebar-logo">
            <div class="logo-mark"><i class="fa-solid fa-motorcycle"></i></div>
            <div class="logo-text">
                <div class="logo-name">MOTOVEX</div>
                <div class="logo-sub">Control Center</div>
            </div>
        </div>

        <div class="sidebar-scroll">

            {{-- Workspace --}}
            <div class="nav-section">
                <div class="nav-label">Workspace</div>
                <nav class="sidebar-nav">
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-gauge-high"></i> Dashboard
                    </a>
                </nav>
            </div>

            {{-- Catalogue --}}
            <div class="nav-section">
                <div class="nav-label">Catalogue</div>
                <nav class="sidebar-nav">
                    <a href="{{ route('admin.brands.index') }}"     class="{{ request()->routeIs('admin.brands*')      ? 'active' : '' }}">
                        <i class="fa-regular fa-copyright"></i> Brands
                    </a>
                    <a href="{{ route('admin.motorcycles.index') }}" class="{{ request()->routeIs('admin.motorcycles*') ? 'active' : '' }}">
                        <i class="fa-solid fa-motorcycle"></i> Motorcycles
                    </a>
                    <a href="{{ route('admin.promotions.index') }}"  class="{{ request()->routeIs('admin.promotions*')  ? 'active' : '' }}">
                        <i class="fa-solid fa-tags"></i> Promotions
                    </a>
                    <a href="{{ route('admin.showrooms.index') }}"   class="{{ request()->routeIs('admin.showrooms*')   ? 'active' : '' }}">
                        <i class="fa-solid fa-shop"></i> Showrooms
                    </a>
                </nav>
            </div>

            {{-- Management (admin only) --}}
            @if(auth()->user()->isAdmin())
            <div class="nav-section">
                <div class="nav-label">Management</div>
                <nav class="sidebar-nav">
                    <a href="{{ route('admin.users.index') }}"      class="{{ request()->routeIs('admin.users*')      ? 'active' : '' }}">
                        <i class="fa-solid fa-users"></i> Users
                    </a>
                    <a href="{{ route('admin.enquiries.index') }}"  class="{{ request()->routeIs('admin.enquiries*')  ? 'active' : '' }}">
                        <i class="fa-solid fa-envelope"></i> Enquiries
                    </a>
                </nav>
            </div>
            @endif

            {{-- Orders & Sales — admin + data_entry + sales agent --}}
            @if(auth()->user()->isAdmin() || auth()->user()->isDataEntry() || auth()->user()->isSalesAgent())
            <div class="nav-section">
                <div class="nav-label">Orders & Sales</div>
                <nav class="sidebar-nav">
                    @if(auth()->user()->isAdmin() || auth()->user()->isDataEntry())
                    <a href="{{ route('admin.sales.index') }}" class="{{ request()->routeIs('admin.sales*') ? 'active' : '' }}">
                        <i class="fa-solid fa-cart-shopping"></i> Sales Orders
                        @php 
                            $pendingSales = auth()->user()->isAdmin() 
                                ? \App\Models\Order::where('status', 'pending')->count()
                                : \App\Models\OrderItem::where('merchant_id', auth()->id())->where('status', 'pending')->count();
                        @endphp
                        @if($pendingSales > 0)
                            <span style="margin-left:auto;min-width:18px;height:18px;border-radius:9px;background:#7c3aed;color:#fff;font-size:9px;font-weight:700;display:inline-flex;align-items:center;justify-content:center;padding:0 4px;">{{ $pendingSales }}</span>
                        @endif
                    </a>
                    @endif
                    
                    <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                        <i class="fa-solid fa-receipt"></i> Enquiries
                        @php 
                            $pendingEnqs = \App\Models\Enquiry::purchaseOrders()->where('status','pending');
                            if (!auth()->user()->isAdmin() && !auth()->user()->isSalesAgent()) {
                                $pendingEnqs->whereHas('motorcycle', fn($q) => $q->responsibleFor(auth()->user()));
                            }
                            $pendingEnqsCount = $pendingEnqs->count();
                        @endphp
                        @if($pendingEnqsCount > 0)
                            <span style="margin-left:auto;min-width:18px;height:18px;border-radius:9px;background:#7c3aed;color:#fff;font-size:9px;font-weight:700;display:inline-flex;align-items:center;justify-content:center;padding:0 4px;">{{ $pendingEnqsCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.test-rides.index') }}" class="{{ request()->routeIs('admin.test-rides*') ? 'active' : '' }}">
                        <i class="fa-solid fa-flag-checkered"></i> Test Rides
                        @php $pendingRides = \App\Models\TestRideRequest::where('status','pending')->count(); @endphp
                        @if($pendingRides > 0)
                            <span style="margin-left:auto;min-width:18px;height:18px;border-radius:9px;background:#d97706;color:#fff;font-size:9px;font-weight:700;display:inline-flex;align-items:center;justify-content:center;padding:0 4px;">{{ $pendingRides }}</span>
                        @endif
                    </a>
                </nav>
            </div>
            @endif

            {{-- Links --}}
            <div class="nav-section">
                <div class="nav-label">Links</div>
                <nav class="sidebar-nav">
                    <a href="{{ route('home') }}" target="_blank">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Public Website
                    </a>
                </nav>
            </div>
        </div>

        {{-- Bottom: help + user --}}
        <div class="sidebar-bottom">
            <div class="help-card">
                <div class="help-icon"><i class="fa-regular fa-circle-question"></i></div>
                <strong>Need help?</strong>
                <p>Access support and platform guides.</p>
                <a href="{{ route('contact') }}">Open help center <i class="fa-solid fa-arrow-right"></i></a>
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

    {{-- ═══════════ CONTENT ═══════════ --}}
    <div class="admin-content">

        {{-- Mobile Topbar --}}
        <div class="mobile-topbar">
            <button id="sidebar-open" class="mobile-menu-btn" type="button" aria-label="Open navigation menu" aria-controls="admin-sidebar" aria-expanded="false">
                <i class="fa-solid fa-bars"></i>
            </button>
            <span class="mobile-brand">MOTOVEX</span>
            <a href="{{ route('home') }}" class="mobile-site-link" target="_blank" aria-label="View public site">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
            </a>
        </div>

        {{-- Desktop Header --}}
        <header class="admin-header">
            <nav class="topbar-crumb" aria-label="Breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Admin</a>
                <i class="fa-solid fa-chevron-right"></i>
                <span class="crumb-current">@yield('title', 'Dashboard')</span>
            </nav>
            <div class="admin-header-actions">
                <a href="{{ route('home') }}" class="admin-btn-ghost" target="_blank">
                    <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:10px"></i> View Site
                </a>
            </div>
        </header>

        <main class="admin-main">

            {{-- Flash messages --}}
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

<div class="sidebar-overlay" id="sidebar-overlay" aria-hidden="true"></div>

<script>
(function () {
    const sidebar = document.getElementById('admin-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const openBtn = document.getElementById('sidebar-open');

    function setOpen(isOpen) {
        if (!sidebar || !overlay) return;
        sidebar.classList.toggle('open', isOpen);
        overlay.classList.toggle('show', isOpen);
        if (openBtn) openBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    }

    openBtn?.addEventListener('click', () => setOpen(true));
    overlay?.addEventListener('click', () => setOpen(false));
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') setOpen(false);
    });
})();
</script>

@yield('scripts')
</body>
</html>
