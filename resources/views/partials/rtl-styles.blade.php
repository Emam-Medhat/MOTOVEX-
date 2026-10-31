{{-- =====================================================================
     CENTRALIZED RTL STYLESHEET
     Conditionally rendered on every layout when the active locale is
     Arabic. Uses CSS logical properties for maximum compatibility and
     supplements Tailwind's own `rtl:` / `ltr:` variants.
     ===================================================================== --}}
@if (($dir ?? (app()->getLocale() === 'ar' ? 'rtl' : 'ltr')) === 'rtl')
<style>
    /* ── Fonts ─────────────────────────────────────────────────────── */
    html[dir="rtl"] body,
    html[dir="rtl"] input,
    html[dir="rtl"] textarea,
    html[dir="rtl"] select,
    html[dir="rtl"] button {
        font-family: 'Noto Kufi Arabic', 'Segoe UI', Tahoma, sans-serif;
    }

    /* ── Text alignment defaults ───────────────────────────────────── */
    html[dir="rtl"] { text-align: right; }
    html[dir="rtl"] .text-left   { text-align: right !important; }
    html[dir="rtl"] .text-right  { text-align: left !important; }

    /* ── Bootstrap-style spacing (ms-*, me-*, ps-*, pe-*) ─────────── */
    html[dir="rtl"] .ms-1 { margin-right: .25rem !important; margin-left: 0 !important; }
    html[dir="rtl"] .ms-2 { margin-right: .5rem !important;  margin-left: 0 !important; }
    html[dir="rtl"] .ms-3 { margin-right: 1rem !important;   margin-left: 0 !important; }
    html[dir="rtl"] .ms-4 { margin-right: 1.5rem !important; margin-left: 0 !important; }
    html[dir="rtl"] .me-1 { margin-left: .25rem !important;  margin-right: 0 !important; }
    html[dir="rtl"] .me-2 { margin-left: .5rem !important;   margin-right: 0 !important; }
    html[dir="rtl"] .me-3 { margin-left: 1rem !important;    margin-right: 0 !important; }
    html[dir="rtl"] .me-4 { margin-left: 1.5rem !important;  margin-right: 0 !important; }

    /* ── Icons that convey direction should flip ───────────────────── */
    html[dir="rtl"] .fa-arrow-left,
    html[dir="rtl"] .fa-arrow-right,
    html[dir="rtl"] .fa-chevron-left,
    html[dir="rtl"] .fa-chevron-right,
    html[dir="rtl"] .fa-angle-left,
    html[dir="rtl"] .fa-angle-right,
    html[dir="rtl"] .fa-angles-left,
    html[dir="rtl"] .fa-angles-right,
    html[dir="rtl"] .fa-circle-arrow-left,
    html[dir="rtl"] .fa-circle-arrow-right,
    html[dir="rtl"] .fa-long-arrow-alt-left,
    html[dir="rtl"] .fa-long-arrow-alt-right,
    html[dir="rtl"] .bi-arrow-left,
    html[dir="rtl"] .bi-arrow-right,
    html[dir="rtl"] .bi-chevron-left,
    html[dir="rtl"] .bi-chevron-right,
    html[dir="rtl"] .bi-chevron-compact-left,
    html[dir="rtl"] .bi-chevron-compact-right,
    html[dir="rtl"] .bi-box-arrow-right,
    html[dir="rtl"] .bi-box-arrow-in-right {
        transform: scaleX(-1);
    }

    /* ── Do NOT flip logos, product images, or brand icons ─────────── */
    html[dir="rtl"] img,
    html[dir="rtl"] video,
    html[dir="rtl"] canvas,
    html[dir="rtl"] svg.chart,
    html[dir="rtl"] .apexcharts-canvas,
    html[dir="rtl"] .no-flip {
        transform: none !important;
    }

    /* ── Breadcrumbs & pagination ──────────────────────────────────── */
    html[dir="rtl"] nav[aria-label="breadcrumb"] ol,
    html[dir="rtl"] .breadcrumb { padding-right: 0; padding-left: 0; }
    html[dir="rtl"] .pagination { padding-right: 0; }

    /* ── Bootstrap dropdowns: align to right edge ──────────────────── */
    html[dir="rtl"] .dropdown-menu.dropdown-menu-end,
    html[dir="rtl"] .dropdown-menu { left: auto; right: 0; }
    html[dir="rtl"] .dropdown-item { text-align: right; }

    /* ── Tables: force right text alignment, but keep numeric cells ── */
    html[dir="rtl"] .table th,
    html[dir="rtl"] .table td,
    html[dir="rtl"] .admin-table th,
    html[dir="rtl"] .admin-table td { text-align: right; }
    html[dir="rtl"] .table td.text-end,
    html[dir="rtl"] .table th.text-end,
    html[dir="rtl"] .admin-table td.text-end { text-align: left !important; }

    /* ── Admin sidebar (fixed left) ────────────────────────────────── */
    html[dir="rtl"] .sidebar {
        left: auto !important;
        right: 0 !important;
        transform: translateX(100%);
    }
    html[dir="rtl"] .sidebar.show { transform: translateX(0); }
    html[dir="rtl"] .main {
        margin-left: 0 !important;
        margin-right: 242px !important;
    }
    html[dir="rtl"] .nav-links a i { order: 2; }
    html[dir="rtl"] .nav-links .count {
        margin-left: 0 !important;
        margin-right: auto !important;
    }
    html[dir="rtl"] .search i { left: auto; right: 15px; }
    html[dir="rtl"] .search input { padding: 11px 43px 11px 15px; }
    html[dir="rtl"] .filter-bar input { padding-left: 12px; padding-right: 32px; }
    html[dir="rtl"] .search-wrap i { left: auto; right: 10px; }

    /* ── Account portal (Tailwind) ─────────────────────────────────── */
    html[dir="rtl"] .ac-nav-item { text-align: right; }
    html[dir="rtl"] .ac-nav-item.is-active::before {
        left: auto; right: 0;
        border-radius: 3px 0 0 3px;
    }
    html[dir="rtl"] .ac-activity-meta {
        margin-left: 0; margin-right: auto;
        text-align: left;
    }
    html[dir="rtl"] .ac-table th { text-align: right; }

    /* ── Public navbar overrides ───────────────────────────────────── */
    html[dir="rtl"] .fa-search + input,
    html[dir="rtl"] header input[name="search"] {
        padding-right: 2.5rem !important;
        padding-left: 1rem !important;
    }
    html[dir="rtl"] header .fa-search {
        left: auto !important;
        right: 0.875rem !important;
    }
    html[dir="rtl"] header nav a i + span { margin-right: .5rem; margin-left: 0; }

    /* ── Numbers, prices, SKUs stay Latin-aligned inside RTL text ──── */
    html[dir="rtl"] .price,
    html[dir="rtl"] .num-latin,
    html[dir="rtl"] .sku,
    html[dir="rtl"] time,
    html[dir="rtl"] .tabular-nums {
        direction: ltr;
        unicode-bidi: plaintext;
        display: inline-block;
    }

    /* ── Cards and utilities ───────────────────────────────────────── */
    html[dir="rtl"] .stat-head i { margin-right: 0; margin-left: .5rem; }
    html[dir="rtl"] .avatar + .profile-info { text-align: right; }
    html[dir="rtl"] .empty-state i,
    html[dir="rtl"] .ac-empty-ico { transform: none; }

    /* ── Bootstrap grid / flex utilities ───────────────────────────── */
    html[dir="rtl"] .ms-auto { margin-right: auto !important; margin-left: 0 !important; }
    html[dir="rtl"] .me-auto { margin-left: auto !important; margin-right: 0 !important; }
</style>
@endif
