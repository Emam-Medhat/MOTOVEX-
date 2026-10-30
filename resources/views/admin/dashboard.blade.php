@extends('layouts.admin')
@section('title', 'Dashboard')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')
@php
    $user = auth()->user();
    $canManageCatalogue = $user->isAdmin() || $user->isDataEntry();
    $canSeeEnquiries    = $user->isAdmin() || $user->isSalesAgent() || $user->isDataEntry();

    $statMeta = [
        'Brands'          => ['fa-regular fa-copyright',  'kpi-purple', 'admin.brands.index',      'الماركات',      'كل الماركات المسجلة'],
        'My Brands'       => ['fa-regular fa-copyright',  'kpi-purple', 'admin.brands.index',      'ماركاتي',       'الماركات المخصصة لك'],
        'Motorcycles'     => ['fa-solid fa-motorcycle',   'kpi-blue',   'admin.motorcycles.index', 'الموتوسيكلات',  'موديلات في الكتالوج'],
        'My Motorcycles'  => ['fa-solid fa-motorcycle',   'kpi-blue',   'admin.motorcycles.index', 'موتوسيكلاتي', 'الموتوسيكلات المسؤولة عنها'],
        'Promotions'      => ['fa-solid fa-tags',         'kpi-amber',  'admin.promotions.index',  'العروض',        'عروض ترويجية نشطة'],
        'Showrooms'       => ['fa-solid fa-shop',         'kpi-green',  'admin.showrooms.index',   'المعارض',       'فروع المبيعات'],
        'Enquiries'       => ['fa-solid fa-envelope',     'kpi-purple', 'admin.enquiries.index',   'الاستفسارات',   'طلبات من الموقع'],
        'Total Enquiries' => ['fa-solid fa-envelope',     'kpi-purple', 'admin.enquiries.index',   'إجمالي الطلبات','كل الاستفسارات'],
        'Pending'         => ['fa-regular fa-clock',      'kpi-amber',  'admin.enquiries.index',   'معلّقة',        'تنتظر المتابعة'],
        'Sales Orders'    => ['fa-solid fa-cart-shopping', 'kpi-purple', 'admin.sales.index',       'طلبات المبيعات', 'إجمالي طلبات البيع'],
        'My Sales'        => ['fa-solid fa-cart-shopping', 'kpi-purple', 'admin.sales.index',       'مبيعاتي',       'طلبات البيع الخاصة بك'],
        'New Orders'      => ['fa-solid fa-bell',          'kpi-amber',  'admin.sales.index',       'طلبات جديدة',    'طلبات شراء تنتظر مراجعتك'],
        'Pending Sales'   => ['fa-regular fa-clock',       'kpi-amber',  'admin.sales.index',       'مبيعات معلقة',    'طلبات شراء تنتظر التأكيد'],
        'Confirmed Sales' => ['fa-solid fa-circle-check',  'kpi-green',  'admin.sales.index',       'مبيعات مؤكدة',    'طلبات تم تأكيد دفعها'],
        'Test Rides'      => ['fa-solid fa-flag-checkered','kpi-blue',  'admin.test-rides.index',   'تجارب القيادة', 'طلبات حجز جديدة'],
        'Test-Ride Bookings' => ['fa-solid fa-flag-checkered','kpi-blue','admin.test-rides.index',  'حجوزات التجربة','طلبات حجز موتوسيكلاتك'],
        'Sales Requests'  => ['fa-solid fa-receipt',       'kpi-purple', 'admin.orders.index',       'طلبات الشراء', 'طلبات شراء موتوسيكلاتك'],
        'Rental Requests' => ['fa-solid fa-key',            'kpi-amber',  'admin.rentals.index',      'طلبات الإيجار', 'طلبات إيجار موتوسيكلاتك'],
        'Product Enquiries' => ['fa-solid fa-comments',    'kpi-purple', 'admin.enquiries.index',    'استفسارات المنتجات','استفسارات حول موتوسيكلاتك'],
        'Pending Actions' => ['fa-regular fa-clock',       'kpi-amber',  'admin.requests.index',     'إجراءات معلّقة','طلبات تنتظر مراجعتك'],
        'Completed'       => ['fa-solid fa-circle-check', 'kpi-green',  'admin.enquiries.index',   'منجزة',         'طلبات مكتملة'],
    ];

    $statusBadge = [
        'pending'   => 'badge-amber',
        'contacted' => 'badge-blue',
        'completed' => 'badge-green',
        'closed'    => 'badge-muted',
    ];
@endphp

<style>
/* ════════════════════════════════════════════════════════
   MOTOVEX ADMIN — Dashboard v3
   Clean, modern, data-focused. LTR layout, Arabic labels.
   ════════════════════════════════════════════════════════ */

/* ── tokens ── */
:root {
    --d-bg:      #f4f6fb;
    --d-surface: #ffffff;
    --d-line:    #e8ecf2;
    --d-line2:   #f1f4f9;
    --d-text:    #111827;
    --d-sub:     #4b5563;
    --d-muted:   #9ca3af;
    --d-purple:  #7c3aed;
    --d-purpled: #6d28d9;
    --d-purplel: #f3e8ff;
    --d-radius:  14px;
    --d-shadow:  0 1px 3px rgba(17,24,39,.05), 0 1px 2px rgba(17,24,39,.04);
    --d-shadow-md: 0 4px 16px rgba(17,24,39,.07), 0 2px 6px rgba(17,24,39,.04);
}
.admin-main { background: var(--d-bg); }

/* ── Utilities ── */
.d-row   { display: flex; align-items: center; gap: 10px; }
.d-gap4  { gap: 4px; }
.d-gap6  { gap: 6px; }
.d-gap8  { gap: 8px; }
.d-gap12 { gap: 12px; }
.d-gap16 { gap: 16px; }
.d-grow  { flex: 1; min-width: 0; }
.d-muted { color: var(--d-muted); font-size: 11px; }
.d-trunc { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

/* ── Page intro ── */
.d-intro { display: flex; align-items: flex-end; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 22px; }
.d-intro-text h1 { font-size: clamp(18px,2.2vw,23px); font-weight: 800; letter-spacing: -.4px; color: var(--d-text); line-height: 1.2; }
.d-intro-text h1 em { font-style: normal; color: var(--d-purple); }
.d-intro-text p  { font-size: 12.5px; color: var(--d-sub); margin-top: 3px; }
.d-time-chip {
    display: flex; align-items: center; gap: 7px;
    border: 1px solid var(--d-line); background: var(--d-surface);
    border-radius: 10px; padding: 8px 13px;
    font-size: 11.5px; color: var(--d-sub); white-space: nowrap;
    box-shadow: var(--d-shadow);
}
.d-time-chip i { color: var(--d-purple); }

/* ── Hero banner ── */
.d-hero {
    position: relative; overflow: hidden;
    display: flex; align-items: center; justify-content: space-between; gap: 20px;
    border-radius: var(--d-radius); margin-bottom: 20px;
    padding: 22px 28px;
    background: linear-gradient(115deg, #6d28d9 0%, #7c3aed 45%, #8b5cf6 100%);
    box-shadow: 0 8px 28px rgba(109,40,217,.22);
}
.d-hero::before {
    content: ""; position: absolute; width: 500px; height: 500px;
    top: -300px; right: 10%; border-radius: 50%;
    background: rgba(255,255,255,.055); filter: blur(2px);
    pointer-events: none;
}
.d-hero::after {
    content: ""; position: absolute; width: 200px; height: 200px;
    bottom: -120px; left: 8%; border-radius: 50%;
    background: rgba(255,255,255,.04);
    pointer-events: none;
}
.d-hero-copy { position: relative; z-index: 1; }
.d-hero-eyebrow {
    display: inline-flex; align-items: center; gap: 7px;
    font-size: 9.5px; font-weight: 700; letter-spacing: 1.8px;
    text-transform: uppercase; color: #c4b5fd; margin-bottom: 8px;
}
.d-hero-eyebrow .dot { width: 5px; height: 5px; border-radius: 50%; background: #a78bfa; animation: pulse-dot 2s ease-in-out infinite; }
@keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.6;transform:scale(1.4)} }
.d-hero h2 { font-size: 19px; font-weight: 800; color: #fff; letter-spacing: -.3px; margin-bottom: 5px; }
.d-hero-sub { font-size: 12px; color: #ddd6fe; }
.d-hero-actions { position: relative; z-index: 1; display: flex; flex-wrap: wrap; gap: 8px; }
.d-hero-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 18px; border-radius: 11px;
    font-size: 12px; font-weight: 700;
    transition: transform .18s, box-shadow .18s; white-space: nowrap; text-decoration: none; border: 0;
}
.d-hero-btn-white { background: #fff; color: var(--d-purpled); box-shadow: 0 4px 14px rgba(0,0,0,.14); }
.d-hero-btn-white:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(0,0,0,.2); }
.d-hero-btn-ghost { background: rgba(255,255,255,.12); color: #fff; border: 1px solid rgba(255,255,255,.22); }
.d-hero-btn-ghost:hover { background: rgba(255,255,255,.2); transform: translateY(-1px); }
.d-hero-visual { position: absolute; right: 22px; top: 50%; transform: translateY(-50%); opacity: .07; pointer-events: none; user-select: none; }
.d-hero-visual i { font-size: 130px; color: #fff; }

/* ── KPI grid ── */
.d-kpi-grid { display: grid; grid-template-columns: repeat(4,minmax(0,1fr)); gap: 14px; margin-bottom: 20px; }
.d-kpi {
    background: var(--d-surface); border: 1px solid var(--d-line);
    border-radius: var(--d-radius); padding: 18px 20px;
    display: flex; flex-direction: column; gap: 12px;
    box-shadow: var(--d-shadow);
    transition: border-color .2s, box-shadow .2s, transform .2s;
    text-decoration: none; color: inherit;
}
.d-kpi:hover { border-color: #ddd6fe; box-shadow: var(--d-shadow-md); transform: translateY(-2px); }
.d-kpi:focus-visible { outline: 2px solid var(--d-purple); outline-offset: 2px; }
.d-kpi-top { display: flex; align-items: center; justify-content: space-between; }
.d-kpi-icon { width: 40px; height: 40px; border-radius: 11px; display: grid; place-items: center; font-size: 15px; flex-shrink: 0; }
.kpi-purple { background: #f3e8ff; color: #7c3aed; }
.kpi-blue   { background: #eff6ff; color: #2563eb; }
.kpi-amber  { background: #fffbeb; color: #d97706; }
.kpi-green  { background: #f0fdf4; color: #16a34a; }
.d-kpi-trend {
    font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 6px;
}
.trend-ok   { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
.trend-zero { background: #f8fafc; color: #9ca3af; border: 1px solid #e5e7eb; }
.d-kpi-val  { font-size: 32px; font-weight: 900; letter-spacing: -1.5px; color: var(--d-text); line-height: 1; }
.d-kpi-label{ font-size: 11.5px; font-weight: 600; color: var(--d-sub); margin-bottom: 2px; }
.d-kpi-hint { font-size: 10px; color: var(--d-muted); }

/* ── Main content 2-col ── */
.d-content { display: grid; grid-template-columns: minmax(0,1.65fr) minmax(260px,.9fr); gap: 16px; margin-bottom: 20px; align-items: start; }

/* ── Panel ── */
.d-panel {
    background: var(--d-surface); border: 1px solid var(--d-line);
    border-radius: var(--d-radius); padding: 22px;
    box-shadow: var(--d-shadow); min-width: 0;
}
.d-panel + .d-panel { margin-top: 16px; }
.d-panel-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; margin-bottom: 20px; }
.d-panel-head-left { min-width: 0; }
.d-panel-title { font-size: 13.5px; font-weight: 700; color: var(--d-text); }
.d-panel-sub   { font-size: 11px; color: var(--d-muted); margin-top: 2px; }
.d-panel-icon  { width: 34px; height: 34px; border-radius: 10px; display: grid; place-items: center; font-size: 13px; background: var(--d-purplel); color: var(--d-purple); flex-shrink: 0; }
.d-text-link {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 11px; font-weight: 700; color: var(--d-purple); white-space: nowrap;
    text-decoration: none; transition: color .15s;
}
.d-text-link:hover { color: var(--d-purpled); }
.d-text-link i { font-size: 9px; }

/* ── Chart tabs ── */
.chart-tab {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 14px; border-radius: 9px;
    font-size: 11.5px; font-weight: 600;
    border: 1px solid var(--d-line); background: var(--d-surface);
    color: var(--d-muted); cursor: pointer;
    transition: all .18s;
}
.chart-tab.active { background: var(--d-purple); color: #fff; border-color: var(--d-purple); }
.chart-tab:hover:not(.active) { background: var(--d-purplel); color: var(--d-purple); border-color: #ddd6fe; }

/* ── Chart ── */
.d-chart-wrap { position: relative; height: 210px; }

/* ── Divider ── */
.d-divider { border: 0; border-top: 1px solid var(--d-line2); margin: 20px 0 16px; }

/* ── Moto list ── */
.d-moto-head {
    display: grid; grid-template-columns: minmax(0,1fr) 60px 80px;
    gap: 12px; align-items: center;
    padding: 0 10px 10px; border-bottom: 1px solid var(--d-line2);
    font-size: 9.5px; font-weight: 700; letter-spacing: .8px; text-transform: uppercase; color: var(--d-muted);
}
.d-moto-row {
    display: grid; grid-template-columns: minmax(0,1fr) 60px 80px;
    gap: 12px; align-items: center;
    padding: 11px 10px; border-radius: 10px;
    transition: background .14s;
}
.d-moto-row:hover { background: #f8fafc; }
.d-moto-thumb {
    width: 38px; height: 38px; flex-shrink: 0; border-radius: 9px;
    background: var(--d-purplel); display: grid; place-items: center;
    color: var(--d-purple); border: 1px solid #e9d5ff; font-size: 13px;
}
.d-moto-name strong { font-size: 12px; font-weight: 600; color: var(--d-text); }
.d-moto-name span   { font-size: 10.5px; color: var(--d-muted); display: block; margin-top: 1px; }
.d-moto-year { font-size: 11.5px; color: var(--d-sub); font-weight: 500; }

/* Status pill */
.d-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 9px; border-radius: 20px;
    font-size: 9.5px; font-weight: 700; white-space: nowrap;
}
.d-pill::before { content: ""; width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }
.pill-green  { color: #15803d; background: #f0fdf4; border: 1px solid #bbf7d0; }
.pill-green::before  { background: #4ade80; }
.pill-amber  { color: #92400e; background: #fffbeb; border: 1px solid #fde68a; }
.pill-amber::before  { background: #fbbf24; }
.pill-blue   { color: #1d4ed8; background: #eff6ff; border: 1px solid #bfdbfe; }
.pill-blue::before   { background: #60a5fa; }
.pill-muted  { color: #64748b; background: #f8fafc; border: 1px solid #e2e8f0; }
.pill-muted::before  { background: #94a3b8; }

/* Badge aliases */
.badge-green  { color: #15803d; background: #f0fdf4; border: 1px solid #bbf7d0; }
.badge-green::before  { background: #4ade80; }
.badge-amber  { color: #92400e; background: #fffbeb; border: 1px solid #fde68a; }
.badge-amber::before  { background: #fbbf24; }
.badge-blue   { color: #1d4ed8; background: #eff6ff; border: 1px solid #bfdbfe; }
.badge-blue::before   { background: #60a5fa; }
.badge-muted  { color: #64748b; background: #f8fafc; border: 1px solid #e2e8f0; }
.badge-muted::before  { background: #94a3b8; }

/* Panel footer */
.d-panel-footer {
    display: flex; align-items: center; justify-content: space-between; gap: 10px;
    margin-top: 18px; padding-top: 16px; border-top: 1px solid var(--d-line2);
}

/* ── Enquiry list ── */
.d-enq-list { display: flex; flex-direction: column; }
.d-enq-row  { display: flex; gap: 11px; padding: 12px 0; border-bottom: 1px solid var(--d-line2); }
.d-enq-row:last-child { border-bottom: 0; padding-bottom: 0; }
.d-enq-avatar {
    width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
    background: var(--d-purplel); color: var(--d-purple);
    display: grid; place-items: center; font-weight: 800; font-size: 12.5px;
    border: 1px solid #e9d5ff;
}
.d-enq-body   { flex: 1; min-width: 0; }
.d-enq-top    { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
.d-enq-name   { font-size: 12px; font-weight: 600; color: var(--d-text); }
.d-enq-time   { font-size: 10px; color: var(--d-muted); white-space: nowrap; flex-shrink: 0; }
.d-enq-subject{ font-size: 11px; color: var(--d-sub); margin-top: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

/* ── Quick actions ── */
.d-qa-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 9px; }
.d-qa {
    display: flex; flex-direction: column; align-items: flex-start; gap: 9px;
    padding: 14px 15px; border-radius: 12px; border: 1px solid var(--d-line);
    background: var(--d-surface); text-decoration: none; color: inherit;
    transition: border-color .2s, background .2s, transform .18s;
}
.d-qa:hover { border-color: #ddd6fe; background: #faf8ff; transform: translateY(-1px); }
.d-qa:focus-visible { outline: 2px solid var(--d-purple); outline-offset: 2px; }
.d-qa-ico { width: 32px; height: 32px; border-radius: 9px; display: grid; place-items: center; font-size: 13px; }
.d-qa span { font-size: 11.5px; font-weight: 600; color: var(--d-text); line-height: 1.3; }
.d-qa small { font-size: 10px; color: var(--d-muted); }

/* ── Snapshot ── */
.d-snap-list { display: flex; flex-direction: column; gap: 8px; }
.d-snap-row  {
    display: flex; align-items: center; gap: 11px;
    padding: 10px 12px; border: 1px solid var(--d-line2);
    border-radius: 11px; background: #f8fafc;
    transition: background .15s;
}
.d-snap-row:hover { background: #f1f4f9; }
.d-snap-ico  { width: 30px; height: 30px; border-radius: 8px; display: grid; place-items: center; font-size: 11.5px; flex-shrink: 0; }
.d-snap-lbl  { flex: 1; font-size: 12px; color: var(--d-sub); font-weight: 500; }
.d-snap-val  { font-size: 16px; font-weight: 800; color: var(--d-text); letter-spacing: -.4px; }

/* ── Add btn ── */
.d-add-btn {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--d-purple); color: #fff; border: 0;
    border-radius: 10px; padding: 9px 16px; font-size: 11.5px; font-weight: 700;
    cursor: pointer; text-decoration: none;
    box-shadow: 0 4px 14px rgba(124,58,237,.22);
    transition: background .18s, box-shadow .18s, transform .18s;
}
.d-add-btn:hover { background: var(--d-purpled); box-shadow: 0 6px 20px rgba(124,58,237,.3); transform: translateY(-1px); }
.d-add-btn:focus-visible { outline: 2px solid var(--d-purple); outline-offset: 2px; }

/* ── Footer ── */
.d-foot {
    display: flex; justify-content: space-between; gap: 12px;
    padding: 16px 0 0; color: var(--d-muted);
    font-size: 10.5px; border-top: 1px solid var(--d-line);
}

/* ── Responsive ── */
@media (max-width:1100px) {
    .d-kpi-grid  { grid-template-columns: repeat(2,minmax(0,1fr)); }
    .d-content   { grid-template-columns: 1fr; }
}
@media (max-width:640px) {
    .d-intro     { flex-direction: column; align-items: flex-start; gap: 10px; }
    .d-hero      { flex-direction: column; align-items: flex-start; gap: 14px; padding: 18px 20px; }
    .d-hero-visual { display: none; }
    .d-hero-btn  { flex: 1; justify-content: center; }
    .d-hero-actions { width: 100%; }
    .d-moto-head,
    .d-moto-row  { grid-template-columns: minmax(0,1fr) auto; }
    .d-moto-head > span:nth-child(2),
    .d-moto-year { display: none; }
    .d-qa-grid   { grid-template-columns: 1fr 1fr; }
}
</style>


{{-- ── Page intro ── --}}
<div class="d-intro">
    <div class="d-intro-text">
        <h1>مرحباً، <em>{{ explode(' ', $user->name)[0] }}</em> 👋</h1>
        <p>لقطة سريعة على الكتالوج والنشاط الأخير.</p>
    </div>
    <div class="d-time-chip">
        <i class="fa-regular fa-calendar"></i>
        {{ now()->translatedFormat('D، d M Y') }}
    </div>
</div>


{{-- ── Hero ── --}}
<div class="d-hero">
    <div class="d-hero-copy">
        <div class="d-hero-eyebrow"><span class="dot"></span> MOTOVEX — لوحة التحكم</div>
        <h2>كتالوجك في متناول يدك.</h2>
        <p class="d-hero-sub">أضف موتوسيكلات، راجع الاستفسارات، وابقِ كل شيء محدّثاً.</p>
    </div>
    <div class="d-hero-actions">
        @if($canManageCatalogue)
        <a href="{{ route('admin.motorcycles.create') }}" class="d-hero-btn d-hero-btn-white">
            <i class="fa-solid fa-plus"></i> إضافة موتوسيكل
        </a>
        @endif
        <a href="{{ route('home') }}" target="_blank" class="d-hero-btn d-hero-btn-ghost">
            <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:10px"></i> الموقع
        </a>
    </div>
    <div class="d-hero-visual" aria-hidden="true"><i class="fa-solid fa-motorcycle"></i></div>
</div>


{{-- ── KPI cards ── --}}
<div class="d-kpi-grid">
    @foreach($statCards as [$label, $value])
    @php
        $meta = $statMeta[$label] ?? ['fa-solid fa-chart-simple', 'kpi-purple', null, $label, 'سجلات'];
        $href = !empty($meta[2]) && \Illuminate\Support\Facades\Route::has($meta[2])
            ? route($meta[2], $label === 'Pending Actions' ? ['status' => 'pending'] : [])
            : null;
        if ($label === 'Product Enquiries' && $href) {
            $href = route('admin.enquiries.index', ['enquiry_type' => 'product']);
        }
        $ar   = $meta[3] ?? $label;
        $hint = $meta[4] ?? 'إجمالي السجلات';
    @endphp
    @if($href)
    <a href="{{ $href }}" class="d-kpi">
    @else
    <div class="d-kpi">
    @endif
        <div class="d-kpi-top">
            <div class="d-kpi-icon {{ $meta[1] }}"><i class="{{ $meta[0] }}"></i></div>
            <span class="d-kpi-trend {{ $value > 0 ? 'trend-ok' : 'trend-zero' }}">
                {{ $value > 0 ? '● مسجّل' : '— فارغ' }}
            </span>
        </div>
        <div>
            <div class="d-kpi-label">{{ $ar }}</div>
            <div class="d-kpi-val">{{ number_format($value) }}</div>
            <div class="d-kpi-hint">{{ $hint }}</div>
        </div>
    @if($href) </a> @else </div> @endif
    @endforeach
</div>


{{-- ── Main content ── --}}
@if($canSeeEnquiries)
<div class="d-panel" style="margin-bottom:20px">
    <div class="d-panel-head">
        <div class="d-panel-head-left">
            <div class="d-panel-title">Recent Activity</div>
            <div class="d-panel-sub">The latest customer requests for your motorcycles</div>
        </div>
        <a href="{{ route('admin.requests.index') }}" class="d-text-link">View all requests <i class="fa-solid fa-arrow-left"></i></a>
    </div>
    @forelse($recentActivity as $activity)
        <a href="{{ $activity['url'] }}" class="d-enq-row" style="text-decoration:none;color:inherit">
            <div class="d-enq-avatar">{{ strtoupper(substr($activity['customer'], 0, 1)) }}</div>
            <div class="d-enq-body">
                <div class="d-enq-top">
                    <span class="d-enq-name">{{ $activity['reference'] }} · {{ $activity['customer'] }}</span>
                    <span class="d-enq-time">{{ $activity['created_at']->diffForHumans() }}</span>
                </div>
                <div class="d-row" style="gap:7px;margin-top:5px">
                    <span class="d-enq-subject d-grow">{{ $activity['type'] }} · {{ $activity['motorcycle'] ?: 'General enquiry' }}</span>
                    <span class="d-pill {{ $statusBadge[$activity['status']] ?? 'badge-muted' }}">{{ ucfirst($activity['status']) }}</span>
                </div>
            </div>
        </a>
    @empty
        <p class="d-muted" style="padding:16px">No customer activity yet.</p>
    @endforelse
</div>
@endif

<div class="d-content">

    {{-- Left: chart + motorcycles ── --}}
    <div>
        <div class="d-panel">
            <div class="d-panel-head">
                <div class="d-panel-head-left">
                    <div class="d-panel-title">نظرة عامة على الكتالوج</div>
                    <div class="d-panel-sub">توزيع السجلات في مساحة عملك</div>
                </div>
                <div class="d-panel-icon"><i class="fa-solid fa-chart-pie"></i></div>
            </div>

            {{-- Chart tabs --}}
            <div style="display:flex;gap:6px;margin-bottom:18px;">
                <button class="chart-tab active" data-chart="donut" onclick="switchChart('donut',this)">
                    <i class="fa-solid fa-chart-pie"></i> توزيع
                </button>
                <button class="chart-tab" data-chart="line" onclick="switchChart('line',this)">
                    <i class="fa-solid fa-chart-line"></i> مقارنة
                </button>
            </div>

            {{-- Donut chart --}}
            <div id="chartDonut" class="chart-pane" style="display:flex;align-items:center;gap:20px;">
                <div style="position:relative;width:200px;height:200px;flex-shrink:0;margin:0 auto;">
                    <canvas id="donutChart" style="width:200px;height:200px;"></canvas>
                    <div id="donutCenter" style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none;">
                        <span style="font-size:26px;font-weight:900;color:#111827;letter-spacing:-1px;line-height:1;" id="donutTotal">—</span>
                        <span style="font-size:10px;color:#9ca3af;font-weight:600;margin-top:2px;">إجمالي</span>
                    </div>
                </div>
                <div id="donutLegend" style="flex:1;display:flex;flex-direction:column;gap:9px;min-width:0;"></div>
            </div>

            {{-- Line chart --}}
            <div id="chartLine" class="chart-pane" style="display:none;height:220px;">
                <canvas id="lineChart"></canvas>
            </div>
        </div>

        @if($canManageCatalogue && $recentMotorcycles->isNotEmpty())
        <div class="d-panel" style="margin-top:14px">
            <div class="d-panel-head">
                <div class="d-panel-head-left">
                    <div class="d-panel-title">آخر الموتوسيكلات</div>
                    <div class="d-panel-sub">أحدث الإضافات للكتالوج</div>
                </div>
                <a href="{{ route('admin.motorcycles.index') }}" class="d-text-link">
                    عرض الكل <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>

            <div class="d-moto-head">
                <span>الموديل</span>
                <span>السنة</span>
                <span>الحالة</span>
            </div>

            @foreach($recentMotorcycles as $m)
            <a href="{{ route('admin.motorcycles.edit', $m) }}" class="d-moto-row" style="text-decoration:none;color:inherit;display:grid;">
                <div class="d-row d-gap10" style="gap:10px">
                    <div class="d-moto-thumb"><i class="fa-solid fa-motorcycle"></i></div>
                    <div class="d-moto-name">
                        <strong class="d-trunc">{{ $m->model_name }}</strong>
                        <span class="d-trunc">{{ $m->brand->name }}</span>
                    </div>
                </div>
                <div class="d-moto-year">{{ $m->year ?: '—' }}</div>
                <div>
                    <span class="d-pill {{ $m->is_published ? 'pill-green' : 'pill-amber' }}">
                        {{ $m->is_published ? 'Published' : 'Draft' }}
                    </span>
                </div>
            </a>
            @endforeach

            <div class="d-panel-footer">
                <span class="d-muted">{{ $recentMotorcycles->count() }} موديل حديث</span>
                <a href="{{ route('admin.motorcycles.create') }}" class="d-add-btn">
                    <i class="fa-solid fa-plus"></i> إضافة موتوسيكل
                </a>
            </div>
        </div>
        @endif
    </div>

    {{-- Right: enquiries + quick actions ── --}}
    <div>

        {{-- Quick actions ── --}}
        @if($canManageCatalogue)
        <div class="d-panel" style="margin-bottom:14px">
            <div class="d-panel-head" style="margin-bottom:14px">
                <div class="d-panel-head-left">
                    <div class="d-panel-title">إجراءات سريعة</div>
                </div>
                <div class="d-panel-icon"><i class="fa-solid fa-bolt"></i></div>
            </div>
            <div class="d-qa-grid">
                <a href="{{ route('admin.motorcycles.create') }}" class="d-qa">
                    <div class="d-qa-ico kpi-blue"><i class="fa-solid fa-motorcycle"></i></div>
                    <span>موتوسيكل جديد</span>
                    <small>أضف للكتالوج</small>
                </a>
                <a href="{{ route('admin.brands.create') }}" class="d-qa">
                    <div class="d-qa-ico kpi-purple"><i class="fa-regular fa-copyright"></i></div>
                    <span>ماركة جديدة</span>
                    <small>أضف براند</small>
                </a>
                <a href="{{ route('admin.promotions.create') }}" class="d-qa">
                    <div class="d-qa-ico kpi-amber"><i class="fa-solid fa-tags"></i></div>
                    <span>عرض ترويجي</span>
                    <small>أنشئ عرضاً</small>
                </a>
                <a href="{{ route('admin.showrooms.create') }}" class="d-qa">
                    <div class="d-qa-ico kpi-green"><i class="fa-solid fa-shop"></i></div>
                    <span>معرض جديد</span>
                    <small>أضف فرعاً</small>
                </a>
            </div>
        </div>
        @endif

        {{-- Enquiries or snapshot ── --}}
        <div class="d-panel">
            @if($canSeeEnquiries && $recentEnquiries->isNotEmpty())
            <div class="d-panel-head">
                <div class="d-panel-head-left">
                    <div class="d-panel-title">أحدث الاستفسارات</div>
                    <div class="d-panel-sub">آخر الرسائل الواردة</div>
                </div>
                <div class="d-panel-icon"><i class="fa-solid fa-envelope"></i></div>
            </div>
            <div class="d-enq-list">
                @foreach($recentEnquiries->take(5) as $e)
                <div class="d-enq-row">
                    <div class="d-enq-avatar">{{ strtoupper(substr($e->name, 0, 1)) }}</div>
                    <div class="d-enq-body">
                        <div class="d-enq-top">
                            <span class="d-enq-name d-trunc">{{ $e->name }}</span>
                            <span class="d-enq-time">{{ $e->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="d-row" style="gap:7px;margin-top:5px">
                            <span class="d-enq-subject d-grow">{{ $e->subject ?: 'بدون موضوع' }}</span>
                            @php $bCls = $statusBadge[$e->status] ?? 'badge-muted'; @endphp
                            <span class="d-pill {{ $bCls }}" style="flex-shrink:0;">{{ ucfirst($e->status) }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="d-panel-footer">
                <span class="d-muted">{{ $recentEnquiries->count() }} استفسار حديث</span>
                <a href="{{ route('admin.enquiries.index') }}" class="d-text-link">
                    عرض الكل <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>

            @else
            {{-- Snapshot fallback ── --}}
            <div class="d-panel-head">
                <div class="d-panel-head-left">
                    <div class="d-panel-title">ملخص المساحة</div>
                    <div class="d-panel-sub">أرقام حية من قاعدة البيانات</div>
                </div>
                <div class="d-panel-icon"><i class="fa-solid fa-chart-simple"></i></div>
            </div>
            <div class="d-snap-list">
                @foreach($statCards as [$label,$value])
                @php $meta = $statMeta[$label] ?? ['fa-solid fa-chart-simple','kpi-purple',null,$label,'']; @endphp
                <div class="d-snap-row">
                    <div class="d-snap-ico {{ $meta[1] }}"><i class="{{ $meta[0] }}"></i></div>
                    <span class="d-snap-lbl">{{ $meta[3] ?? $label }}</span>
                    <span class="d-snap-val">{{ number_format($value) }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Admin users shortcut ── --}}
        @if($user->isAdmin())
        <div class="d-panel" style="margin-top:14px">
            <div class="d-panel-head" style="margin-bottom:12px">
                <div class="d-panel-title">إدارة المستخدمين</div>
                <a href="{{ route('admin.users.index') }}" class="d-text-link">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
            </div>
            <div class="d-qa-grid">
                <a href="{{ route('admin.users.index') }}" class="d-qa">
                    <div class="d-qa-ico kpi-blue"><i class="fa-solid fa-users"></i></div>
                    <span>المستخدمون</span>
                    <small>إدارة الحسابات</small>
                </a>
                <a href="{{ route('admin.users.create') }}" class="d-qa">
                    <div class="d-qa-ico kpi-green"><i class="fa-solid fa-user-plus"></i></div>
                    <span>مستخدم جديد</span>
                    <small>أضف حساباً</small>
                </a>
            </div>
        </div>
        @endif

    </div>
</div>


{{-- ── Footer ── --}}
<div class="d-foot">
    <span>© {{ date('Y') }} MOTOVEX — Control Center</span>
    <span id="d-clock"></span>
</div>


<script>
/* ── Live clock ── */
(function () {
    const el = document.getElementById('d-clock');
    if (!el) return;
    function tick() {
        const now = new Date();
        el.textContent = now.toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    }
    tick(); setInterval(tick, 1000);
})();

/* ── Chart ── */
(function () {
    const rawLabels = @json(array_column($statCards, 0));
    const values    = @json(array_column($statCards, 1));
    const metaMap   = @json($statMeta);

    const arLabels = rawLabels.map(k => (metaMap[k] && metaMap[k][3]) ? metaMap[k][3] : k);
    const total    = values.reduce((a, b) => a + b, 0);

    const palette = [
        '#7c3aed','#6d28d9','#8b5cf6','#a78bfa',
        '#2563eb','#60a5fa','#0d9488','#f59e0b',
        '#dc2626','#ec4899',
    ];
    const colors = values.map((_, i) => palette[i % palette.length]);

    // ── Update center total
    const centerEl = document.getElementById('donutTotal');
    if (centerEl) centerEl.textContent = total.toLocaleString('ar-EG');

    // ── Donut chart ──────────────────────────────────────
    const donutCtx = document.getElementById('donutChart');
    let donutChart = null;
    if (donutCtx) {
        donutChart = new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: arLabels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverBorderColor: '#ffffff',
                    hoverOffset: 8,
                }]
            },
            options: {
                responsive: false,
                cutout: '68%',
                animation: { animateRotate: true, duration: 800, easing: 'easeOutQuart' },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e1b4b',
                        titleColor: '#fff',
                        bodyColor: '#c4b5fd',
                        padding: 12, cornerRadius: 10, borderWidth: 0,
                        titleFont: { size: 12, weight: '700', family: 'Inter' },
                        bodyFont:  { size: 11, family: 'Inter' },
                        callbacks: {
                            label: ctx => {
                                const pct = total > 0 ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
                                return `  ${ctx.parsed.toLocaleString()} سجل  (${pct}%)`;
                            }
                        }
                    }
                },
                onHover: (event, elements) => {
                    donutCtx.style.cursor = elements.length ? 'pointer' : 'default';
                    if (elements.length) {
                        const i = elements[0].index;
                        centerEl.textContent = values[i].toLocaleString('ar-EG');
                    } else {
                        centerEl.textContent = total.toLocaleString('ar-EG');
                    }
                }
            }
        });
    }

    // ── Build donut legend ────────────────────────────────
    const legend = document.getElementById('donutLegend');
    if (legend) {
        arLabels.forEach((lbl, i) => {
            const pct = total > 0 ? ((values[i] / total) * 100).toFixed(0) : 0;
            legend.insertAdjacentHTML('beforeend', `
                <div style="display:flex;align-items:center;gap:9px;cursor:default;"
                     onmouseover="highlightSlice(${i})" onmouseout="resetSlice()">
                    <span style="width:10px;height:10px;border-radius:3px;flex-shrink:0;background:${colors[i]};"></span>
                    <span style="font-size:11.5px;color:#4b5563;flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${lbl}</span>
                    <span style="font-size:11px;font-weight:700;color:#111827;margin-left:auto;">${values[i]}</span>
                    <span style="font-size:10px;color:#9ca3af;min-width:32px;text-align:right;">${pct}%</span>
                </div>
            `);
        });
    }

    window.highlightSlice = function(i) {
        if (!donutChart) return;
        donutChart.setDatasetVisibility(0, true);
        centerEl.textContent = values[i].toLocaleString('ar-EG');
    };
    window.resetSlice = function() {
        if (!donutChart) return;
        centerEl.textContent = total.toLocaleString('ar-EG');
    };

    // ── Line chart ────────────────────────────────────────
    const lineCtx = document.getElementById('lineChart');
    let lineChart = null;
    if (lineCtx) {
        const gradient = lineCtx.getContext('2d').createLinearGradient(0, 0, 0, 220);
        gradient.addColorStop(0,   'rgba(124,58,237,.22)');
        gradient.addColorStop(1,   'rgba(124,58,237,.0)');

        lineChart = new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: arLabels,
                datasets: [{
                    label: 'العدد',
                    data: values,
                    borderColor: '#7c3aed',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#7c3aed',
                    pointBorderWidth: 2.5,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: '#7c3aed',
                    fill: true,
                    backgroundColor: gradient,
                    tension: 0.42,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 700, easing: 'easeOutQuart' },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e1b4b',
                        titleColor: '#fff', bodyColor: '#c4b5fd',
                        padding: 12, cornerRadius: 10,
                        titleFont: { size: 12, weight: '700', family: 'Inter' },
                        bodyFont:  { size: 11, family: 'Inter' },
                        callbacks: { label: c => `  ${c.parsed.y} سجل` }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { color: '#6b7280', font: { size: 11, family: 'Inter', weight: '500' }, padding: 6 }
                    },
                    y: {
                        beginAtZero: true, min: 0,
                        grid: { color: '#f1f5f9', drawBorder: false },
                        border: { display: false },
                        ticks: { color: '#9ca3af', font: { size: 10, family: 'Inter' }, stepSize: 1, precision: 0, padding: 8 }
                    }
                }
            }
        });
    }

    // ── Tab switch ────────────────────────────────────────
    window.switchChart = function(type, btn) {
        document.querySelectorAll('.chart-tab').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('chartDonut').style.display = type === 'donut' ? 'flex' : 'none';
        document.getElementById('chartLine').style.display  = type === 'line'  ? 'block' : 'none';
        if (type === 'line' && lineChart) lineChart.resize();
    };
})();
</script>
@endsection
