<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — MotoTrade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @stack('head')
    <style>
        :root {
            /* ── Core palette ── */
            --primary: #0865df;
            --sidebar: #084b9c;
            --dark: #102344;
            --muted: #7185a4;
            --border: #e4ebf5;
            --bg: #f5f8fd;

            /* ── Extended tokens (used by module pages) ── */
            --text: #102344;
            --subtext: #375477;
            --line: #e4ebf5;
            --surface: #ffffff;

            /* ── Purple accent (KPI cards, badges, refs) ── */
            --purple: #6d28d9;
            --purple-light: #8b5cf6;
            --purple-bg: #f3e8ff;
            --purple-border: #e9d5ff;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--dark);
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            min-height: 100vh;
        }

        .sidebar {
            width: 242px;
            background: linear-gradient(180deg,#0752a8,#06438c);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            color: white;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: .3s;
        }

        .logo {
            height: 83px;
            display: flex;
            align-items: center;
            padding: 20px 25px;
            border-bottom: 1px solid #ffffff18;
            gap: 12px;
        }

        .logo i { font-size: 37px; }

        .logo h4 {
            font-size: 20px;
            font-weight: 800;
            margin: 0;
        }

        .logo small { font-size: 10px; color: #c4dcff; }

        .nav-links { padding: 16px 12px; flex: 1; overflow-y: hidden; }

        .nav-section-label {
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: rgba(255,255,255,.35);
            padding: 14px 14px 6px;
            margin-top: 4px;
        }

        .nav-links a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,.75);
            text-decoration: none;
            padding: 10px 14px;
            border-radius: 9px;
            margin-bottom: 2px;
            font-size: 13px;
            font-weight: 500;
            transition: background .18s, color .18s;
        }

        .nav-links a i { font-size: 16px; flex-shrink: 0; }

        .nav-links a:hover {
            background: rgba(255,255,255,.1);
            color: #fff;
        }

        .nav-links a.active {
            background: rgba(255,255,255,.18);
            color: #fff;
            font-weight: 700;
        }

        .nav-links .count {
            margin-left: auto;
            background: #ef4444;
            color: #fff;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            line-height: 1.5;
        }

        .main {
            margin-left: 242px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: white;
            height: 68px;
            border-bottom: 1px solid var(--border);
            padding: 0 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .search {
            width: 42%;
            position: relative;
        }

        .search input {
            width: 100%;
            border: 1px solid #dce5f2;
            border-radius: 8px;
            padding: 11px 15px 11px 43px;
            outline: none;
            font-size: 13px;
        }

        .search i {
            position: absolute;
            left: 15px;
            top: 12px;
            color: var(--muted);
            font-size: 16px;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar {
            width: 37px;
            height: 37px;
            border-radius: 50%;
            background: #e2efff;
            display: grid;
            place-items: center;
            color: var(--primary);
            font-size: 18px;
            overflow: hidden;
        }
        .avatar img { width: 100%; height: 100%; object-fit: cover; }

        .content { padding: 25px; flex: 1; }

        /* Global Admin Styles */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 23px;
        }
        .page-header h1 {
            font-size: 21px;
            font-weight: 800;
            margin-bottom: 5px;
            color: var(--dark);
        }
        .page-header p { color: var(--muted); margin: 0; }

        .panel {
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 18px;
            height: 100%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .table-responsive { overflow-x: auto; }

        .table thead th {
            background: #f3f7fc !important;
            color: #375477 !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            padding: 13px 8px !important;
            border: 0 !important;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 14px 8px !important;
            vertical-align: middle !important;
            border-bottom: 1px solid #edf1f7 !important;
            font-size: 12px !important;
            white-space: nowrap;
            color: var(--dark);
        }

        .btn-primary-alt {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: 0.2s;
            text-decoration: none;
        }
        .btn-primary-alt:hover {
            background: #0654ba;
            color: white;
            transform: translateY(-1px);
        }

        .filter-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
        }

        .filter-input {
            border: 1px solid #dce5f2;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 13px;
            outline: none;
            min-width: 200px;
        }
        .filter-select {
            border: 1px solid #dce5f2;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 13px;
            outline: none;
            background: white;
            min-width: 150px;
        }

        .badge-alt {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .badge-pending { background: #fff2d7; color: #d58b00; }
        .badge-success { background: #d9f6e8; color: #008c55; }
        .badge-primary { background: #e0efff; color: #0870dc; }
        .badge-info { background: #e5edff; color: #2869e7; }
        .badge-danger { background: #fee2e2; color: #dc2626; }
        .badge-warning { background: #fef3c7; color: #d97706; }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: inline-grid;
            place-items: center;
            font-size: 14px;
            transition: 0.2s;
            border: none;
            background: #f8fafc;
            color: #64748b;
            text-decoration: none;
        }
        .action-btn:hover {
            background: #e2e8f0;
            color: var(--dark);
        }
        .btn-view { color: #6366f1; background: #eef2ff; }
        .btn-edit { color: #8b5cf6; background: #f5f3ff; }
        .btn-delete { color: #ef4444; background: #fef2f2; }

        /* Global Form Styles */
        .form-label {
            font-size: 13px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
            display: block;
        }

        .form-control, .form-select, .form-input, .form-textarea {
            border: 1px solid #dce5f2 !important;
            border-radius: 8px !important;
            padding: 10px 14px !important;
            font-size: 13px !important;
            color: var(--dark) !important;
            background: white !important;
            outline: none !important;
            transition: 0.2s !important;
            width: 100% !important;
        }
        .form-control:focus, .form-select:focus, .form-input:focus, .form-textarea:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px rgba(8, 101, 223, 0.1) !important;
        }

        .form-section {
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .form-section-title {
            font-size: 15px;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .invalid-feedback {
            font-size: 11px;
            font-weight: 600;
            color: #dc2626;
            margin-top: 5px;
        }

        .mobile-toggle { display: none; }

        @media(max-width: 1100px) {
            .sidebar { width: 210px; }
            .main { margin-left: 210px; }
            .content { padding: 18px; }
        }

        @media(max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 242px;
            }
            .sidebar.show { transform: translateX(0); }
            .main { margin-left: 0; }
            .mobile-toggle { display: inline-block; }
            .search { width: 55%; }
            .profile-info { display: none; }
            .content { padding: 14px; }
        }

        /* ════════════════════════════════════════════════
           SHARED MODULE UTILITIES
           (used by orders, test-rides, requests, etc.)
        ════════════════════════════════════════════════ */

        /* ── Primary / Danger action buttons ── */
        .btn-primary {
            display: inline-flex; align-items: center; gap: 7px;
            background: var(--primary); color: #fff;
            border: none; padding: 9px 17px;
            border-radius: 8px; font-weight: 600; font-size: 12.5px;
            font-family: inherit; cursor: pointer; text-decoration: none;
            transition: background .18s, transform .18s, box-shadow .18s;
        }
        .btn-primary:hover {
            background: #0654ba; color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px -6px rgba(8,101,223,.45);
        }
        .btn-danger {
            display: inline-flex; align-items: center; gap: 7px;
            background: #fef2f2; color: #dc2626;
            border: 1.5px solid #fecaca; padding: 9px 17px;
            border-radius: 8px; font-weight: 600; font-size: 12.5px;
            font-family: inherit; cursor: pointer; text-decoration: none;
            transition: all .18s;
        }
        .btn-danger:hover {
            background: #ef4444; color: #fff; border-color: #ef4444;
            box-shadow: 0 6px 18px -6px rgba(239,68,68,.5);
            transform: translateY(-1px);
        }

        /* ── Small admin button variants ── */
        .admin-btn-sm {
            display: inline-flex; align-items: center; gap: 5px;
            height: 30px; padding: 0 10px;
            border-radius: 7px; border: 1px solid var(--border);
            font-size: 11px; font-weight: 600; font-family: inherit;
            cursor: pointer; text-decoration: none; background: #f8fafc;
            color: var(--subtext); transition: all .15s;
        }
        .admin-btn-sm:hover { border-color: var(--primary); color: var(--primary); background: #eff6ff; }
        .btn-view-alt { background: #eef2ff; color: #4338ca; border-color: #c7d2fe; }
        .btn-view-alt:hover { background: #6366f1; color: #fff; border-color: #6366f1; }

        /* ── Table wrapper & table ── */
        .admin-table-wrap {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,.03);
        }
        .admin-table {
            width: 100%; border-collapse: collapse;
        }
        .admin-table thead th {
            background: #f3f7fc;
            color: #375477;
            font-size: 11px;
            font-weight: 700;
            padding: 13px 14px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
            text-align: left;
        }
        .admin-table tbody tr { transition: background .12s; }
        .admin-table tbody tr:hover { background: #f8fbff; }
        .admin-table tbody td {
            padding: 13px 14px;
            border-bottom: 1px solid #f0f4f9;
            font-size: 12px;
            color: var(--dark);
            vertical-align: middle;
        }
        .admin-table tbody tr:last-child td { border-bottom: none; }

        /* ── Pagination wrapper ── */
        .admin-pagination {
            padding: 14px 16px;
            border-top: 1px solid var(--border);
            display: flex; align-items: center; justify-content: flex-end;
        }

        /* ── Empty state ── */
        .empty-state {
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; padding: 60px 20px; gap: 12px;
            color: var(--muted); text-align: center;
        }
        .empty-state i { font-size: 48px; opacity: .22; }
        .empty-state p { font-size: 13px; margin: 0; max-width: 300px; line-height: 1.6; }

        /* ── Status badges ── */
        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 4px 10px; border-radius: 999px;
            font-size: 10.5px; font-weight: 700; letter-spacing: .02em;
            white-space: nowrap;
        }
        .badge-yellow  { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
        .badge-blue    { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .badge-green   { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-red     { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .badge-purple  { background: var(--purple-bg); color: var(--purple); border: 1px solid var(--purple-border); }
        .badge-muted   { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

        /* ── Table link ── */
        .tbl-link {
            color: var(--primary); text-decoration: none; font-weight: 600;
            transition: color .14s;
        }
        .tbl-link:hover { color: var(--purple); text-decoration: underline; }

        /* ── Flash messages ── */
        .flash-success, .flash-error {
            display: flex; align-items: center; gap: 9px;
            padding: 12px 16px; border-radius: 10px;
            font-size: 13px; font-weight: 600; margin-bottom: 18px;
        }
        .flash-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .flash-error   { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

        /* ── Filter bar (used by module pages) ── */
        .filter-bar {
            display: flex; flex-wrap: wrap; align-items: center; gap: 9px;
            margin-bottom: 18px; padding: 12px 14px;
            border: 1px solid var(--border); border-radius: 12px;
            background: #fff;
        }
        .filter-bar input,
        .filter-bar select {
            height: 38px; padding: 0 12px;
            background: #fff; border: 1px solid #dce5f2;
            border-radius: 8px; color: var(--dark); font-size: 12.5px;
            font-family: inherit; outline: none;
            transition: border-color .15s, box-shadow .15s;
        }
        .filter-bar input { min-width: 220px; padding-left: 32px; }
        .filter-bar select { min-width: 130px; cursor: pointer; }
        .filter-bar input:focus,
        .filter-bar select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(8,101,223,.1);
        }
        .filter-bar input::placeholder { color: #94a3b8; }
        .filter-clear {
            font-size: 11.5px; font-weight: 600; color: var(--muted);
            border: 1px solid var(--border); background: none;
            border-radius: 8px; padding: 0 13px; height: 38px;
            cursor: pointer; transition: all .15s; text-decoration: none;
            display: inline-flex; align-items: center; gap: 5px;
        }
        .filter-clear:hover { border-color: #fecaca; color: #dc2626; background: #fff1f2; }
        .search-wrap { position: relative; }
        .search-wrap i {
            position: absolute; left: 10px; top: 50%;
            transform: translateY(-50%); color: #94a3b8;
            font-size: 12px; pointer-events: none;
        }
        /* ── Stat card (used by dashboard and enquiries) ── */
        .stat-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 17px;
            position: relative;
            overflow: hidden;
            transition: box-shadow .2s, transform .2s;
        }
        .stat-card:hover { box-shadow: 0 6px 20px rgba(8,101,223,.09); transform: translateY(-2px); }
        .stat-head {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            color: var(--dark);
            font-size: 13px;
        }
        .stat-icon {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: #e6f1ff;
            color: var(--primary);
            display: grid; place-items: center;
            font-size: 18px; flex-shrink: 0;
        }
        .stat-card h3 {
            font-size: 26px;
            font-weight: 800;
            margin-top: 12px;
            margin-bottom: 4px;
            color: var(--dark);
        }
        .stat-card .growth { color: #00a66c; font-size: 12px; font-weight: 600; }
        .stat-card small { color: var(--muted); font-size: 11px; }

        /* ── Admin panel card (used by enquiries/show) ── */
        .admin-panel {
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
        }

        /* ── Customer avatar ── */
        .customer-avatar {
            width: 32px; height: 32px;
            border-radius: 8px; background: #e0efff;
            display: grid; place-items: center;
            color: var(--primary); font-weight: 700; font-size: 12px;
            flex-shrink: 0;
        }

        /* ── Ghost button (used in forms/show pages) ── */
        .btn-ghost {
            display: inline-flex; align-items: center; gap: 7px;
            background: #f8fafc; color: var(--dark);
            border: 1px solid var(--border); padding: 9px 17px;
            border-radius: 8px; font-weight: 600; font-size: 12.5px;
            font-family: inherit; cursor: pointer; text-decoration: none;
            transition: all .18s;
        }
        .btn-ghost:hover {
            background: #f1f5f9; border-color: #cbd5e1; color: var(--dark);
        }

        /* ── Edit alt button (brands/show, users/show) ── */
        .btn-edit-alt {
            background: #f5f3ff; color: #7c3aed; border-color: #e9d5ff;
        }
        .btn-edit-alt:hover { background: #7c3aed; color: #fff; border-color: #7c3aed; }
    </style>
    @yield('styles')
</head>
<body>

<div class="admin-layout">
    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="logo">
            <i class="bi bi-bicycle"></i>
            <div>
                <h4>MotoTrade</h4>
                <small>Ride Your Business</small>
            </div>
        </div>

        <nav class="nav-links">

            {{-- ── Overview ── --}}
            <div class="nav-section-label">Overview</div>

            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>

            <a href="{{ route('admin.requests.index') }}" class="{{ request()->routeIs('admin.requests*') ? 'active' : '' }}">
                <i class="bi bi-inboxes-fill"></i> All Requests
            </a>

            {{-- ── Customer Activity ── --}}
            <div class="nav-section-label">Customer Activity</div>

            <a href="{{ route('admin.enquiries.index') }}" class="{{ request()->routeIs('admin.enquiries*') ? 'active' : '' }}">
                <i class="bi bi-envelope-fill"></i> Enquiries
            </a>

            <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Purchase Orders
            </a>

            <a href="{{ route('admin.test-rides.index') }}" class="{{ request()->routeIs('admin.test-rides*') ? 'active' : '' }}">
                <i class="bi bi-calendar2-check-fill"></i> Test Rides
            </a>

            <a href="{{ route('admin.rentals.index') }}" class="{{ request()->routeIs('admin.rentals*') ? 'active' : '' }}">
                <i class="bi bi-key-fill"></i> Rentals
            </a>

            @if(auth()->user()->isAdmin() || auth()->user()->isDataEntry())
                @php
                    $pendingSalesCount = \App\Models\Order::where('status', 'pending')->count();
                @endphp

                <a href="{{ route('admin.sales.index') }}" class="{{ request()->routeIs('admin.sales*') ? 'active' : '' }}">
                    <i class="bi bi-cart3"></i> Sales
                    @if($pendingSalesCount > 0)
                        <span class="count">{{ $pendingSalesCount }}</span>
                    @endif
                </a>

                {{-- ── Catalogue ── --}}
                <div class="nav-section-label">Catalogue</div>

                <a href="{{ route('admin.motorcycles.index') }}" class="{{ request()->routeIs('admin.motorcycles*') ? 'active' : '' }}">
                    <i class="bi bi-bicycle"></i> Motorcycles
                </a>

                <a href="{{ route('admin.brands.index') }}" class="{{ request()->routeIs('admin.brands*') ? 'active' : '' }}">
                    <i class="bi bi-patch-check-fill"></i> Brands
                </a>

                <a href="{{ route('admin.spare-parts.index') }}" class="{{ request()->routeIs('admin.spare-parts*') ? 'active' : '' }}">
                    <i class="bi bi-gear-fill"></i> Spare Parts
                </a>

                <a href="{{ route('admin.promotions.index') }}" class="{{ request()->routeIs('admin.promotions*') ? 'active' : '' }}">
                    <i class="bi bi-megaphone-fill"></i> Promotions
                </a>

                {{-- ── Business ── --}}
                <div class="nav-section-label">Business</div>

                <a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services*') ? 'active' : '' }}">
                    <i class="bi bi-tools"></i> Services
                </a>

                <a href="{{ route('admin.showrooms.index') }}" class="{{ request()->routeIs('admin.showrooms*') ? 'active' : '' }}">
                    <i class="bi bi-shop-window"></i> Showrooms
                </a>
            @endif

            @if(auth()->user()->isAdmin())
                {{-- ── Administration ── --}}
                <div class="nav-section-label">Administration</div>

                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> Users & Merchants
                </a>
            @endif

        </nav>

    </aside>

    <main class="main">
        <header class="topbar">
            <div class="d-flex align-items-center gap-3 w-100">
                <button class="btn btn-light mobile-toggle" id="sidebar-toggle">
                    <i class="bi bi-list"></i>
                </button>

                <div class="search">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Search for orders, products, customers...">
                </div>
            </div>

            <div class="profile">
                <i class="bi bi-bell fs-5" style="cursor:pointer"></i>
                <div class="avatar">
                    @if(auth()->user()->avatar_url)
                        <img src="{{ auth()->user()->avatar_url }}" alt="">
                    @else
                        <i class="bi bi-person-fill"></i>
                    @endif
                </div>
                <div class="profile-info">
                    <strong>{{ auth()->user()->name }}</strong>
                    <small class="d-block text-secondary">{{ auth()->user()->roleName() }}</small>
                </div>
                
                <div class="dropdown">
                    <i class="bi bi-chevron-down ms-2" style="cursor:pointer" data-bs-toggle="dropdown"></i>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <div class="content">
            {{-- Flash messages --}}
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('sidebar-toggle')?.addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('show');
    });
</script>
@yield('scripts')
</body>
</html>
