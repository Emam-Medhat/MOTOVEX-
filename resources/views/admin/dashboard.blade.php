@extends('layouts.admin')
@section('title', 'Dashboard')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')
@php
    $user = auth()->user();
    $canManageCatalogue = $user->isAdmin() || $user->isDataEntry();
    $canSeeEnquiries    = $user->isAdmin() || $user->isSalesAgent();

    // Visual metadata keyed by the (dynamic) stat label, so each role keeps its
    // own real counts while sharing one consistent icon + tint language.
    // Third value = the existing destination the count links to (null = not linked).
    $statMeta = [
        'Brands'          => ['fa-regular fa-copyright',  'si-purple', 'admin.brands.index'],
        'My Brands'       => ['fa-regular fa-copyright',  'si-purple', 'admin.brands.index'],
        'Motorcycles'     => ['fa-solid fa-motorcycle',   'si-blue',   'admin.motorcycles.index'],
        'Promotions'      => ['fa-solid fa-tags',         'si-amber',  'admin.promotions.index'],
        'Showrooms'       => ['fa-solid fa-shop',         'si-green',  'admin.showrooms.index'],
        'Enquiries'       => ['fa-solid fa-envelope',     'si-purple', 'admin.enquiries.index'],
        'Total Enquiries' => ['fa-solid fa-envelope',     'si-purple', 'admin.enquiries.index'],
        'Pending'         => ['fa-regular fa-clock',      'si-amber',  'admin.enquiries.index'],
        'Test Rides'      => ['fa-solid fa-motorcycle',   'si-blue',   'admin.enquiries.index'],
        'Completed'       => ['fa-solid fa-circle-check', 'si-green',  'admin.enquiries.index'],
    ];

    $statusBadge = [
        'pending'   => 'badge-yellow',
        'contacted' => 'badge-blue',
        'completed' => 'badge-green',
    ];
@endphp

<style>
    /* ── Page intro ── */
    .dash-intro{display:flex;align-items:flex-end;justify-content:space-between;gap:20px;margin-bottom:20px;flex-wrap:wrap}
    .dash-intro h1{font-size:clamp(19px,2.4vw,25px);letter-spacing:-.5px;margin-bottom:4px;color:#172033;font-weight:800;line-height:1.2}
    .dash-intro h1 span{color:#7c3aed}
    .dash-intro p{font-size:12.5px;color:#64748b}
    .date-chip{display:flex;align-items:center;gap:8px;border:1px solid #e2e8f0;background:#fff;border-radius:10px;padding:8px 13px;color:#64748b;font-size:11px;white-space:nowrap}
    .date-chip i{color:#7c3aed}

    /* ── Hero ── */
    .hero-strip{position:relative;overflow:hidden;display:flex;align-items:center;justify-content:space-between;gap:22px;padding:20px 26px;margin-bottom:20px;border:1px solid #ddd6fe;border-radius:16px;background:linear-gradient(110deg,#7c3aed 0%,#6d28d9 62%,#5b21b6 100%)}
    .hero-strip::before{content:"";position:absolute;width:320px;height:320px;right:20%;top:-215px;border-radius:50%;background:rgba(255,255,255,.07);filter:blur(26px);pointer-events:none}
    .hero-copy{position:relative;z-index:1;min-width:0}
    .hero-eyebrow{display:inline-flex;align-items:center;gap:7px;color:#ddd6fe;font-size:9px;font-weight:800;letter-spacing:1.7px;text-transform:uppercase;margin-bottom:9px}
    .hero-eyebrow::before{content:"";width:18px;height:2px;background:#c4b5fd;border-radius:2px}
    .hero-strip h2{font-size:20px;margin-bottom:5px;letter-spacing:-.4px;color:#fff;font-weight:800}
    .hero-strip p{font-size:12px;color:#ddd6fe}
    .hero-cta{position:relative;z-index:1;display:inline-flex;align-items:center;gap:9px;padding:11px 18px;border-radius:11px;background:#fff;color:#6d28d9;font-size:12px;font-weight:700;box-shadow:0 4px 14px rgba(0,0,0,.14);white-space:nowrap;border:0;cursor:pointer;text-decoration:none;transition:transform .18s,box-shadow .18s}
    .hero-cta:hover{transform:translateY(-1px);box-shadow:0 8px 22px rgba(0,0,0,.2)}
    .hero-cta:focus-visible{outline:2px solid #fff;outline-offset:2px}
    .hero-visual{position:absolute;right:3.5%;top:50%;transform:translateY(-50%);opacity:.10;pointer-events:none}
    .hero-visual i{font-size:118px;color:#fff}

    /* ── KPI cards ── */
    .stats-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px;margin-bottom:20px}
    .stat-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:18px;display:flex;flex-direction:column;gap:14px;color:inherit;transition:border-color .2s,box-shadow .2s,transform .2s}
    a.stat-card{text-decoration:none}
    .stat-card:hover{border-color:#ddd6fe;box-shadow:0 8px 24px rgba(124,58,237,.08);transform:translateY(-2px)}
    .stat-card:focus-visible{outline:2px solid #7c3aed;outline-offset:2px}
    .stat-top{display:flex;align-items:center;justify-content:space-between;gap:10px}
    .stat-icon{width:40px;height:40px;display:grid;place-items:center;border-radius:11px;font-size:16px;flex-shrink:0}
    .si-purple{color:#7c3aed;background:#f3e8ff}
    .si-blue  {color:#1d4ed8;background:#eff6ff}
    .si-amber {color:#b45309;background:#fffbeb}
    .si-green {color:#15803d;background:#f0fdf4}
    .stat-badge{font-size:9px;padding:4px 8px;border-radius:6px;font-weight:700;white-space:nowrap}
    .stat-badge.ok  {color:#15803d;background:#f0fdf4;border:1px solid #bbf7d0}
    .stat-badge.nil {color:#64748b;background:#f8fafc;border:1px solid #e2e8f0}
    .stat-body{min-width:0}
    .stat-card h3{font-size:11.5px;color:#64748b;font-weight:600;margin-bottom:5px;letter-spacing:.2px}
    .stat-value{font-size:30px;font-weight:800;letter-spacing:-1.2px;color:#172033;line-height:1}
    .stat-foot{font-size:10.5px;color:#94a3b8;margin-top:6px}

    /* ── Panels ── */
    .panel{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:20px;min-width:0}
    .panel-head{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:18px}
    .panel-title{font-size:14px;font-weight:700;color:#172033}
    .panel-sub{font-size:10.5px;color:#64748b;margin-top:3px}
    .panel-icon{width:33px;height:33px;display:grid;place-items:center;border-radius:10px;font-size:13px;background:#f3e8ff;color:#7c3aed;flex-shrink:0}
    .text-link{color:#7c3aed;font-size:11px;font-weight:700;white-space:nowrap;display:inline-flex;align-items:center;gap:5px}
    .text-link:hover{color:#6d28d9;text-decoration:underline}

    /* ── Content grid ── */
    .content-grid{display:grid;grid-template-columns:minmax(0,1.6fr) minmax(280px,.9fr);gap:16px;margin-bottom:20px;align-items:start}

    /* Chart */
    .chart-wrap{position:relative;height:204px}

    /* Motorcycle mini table */
    .tbl-head,.bike-row{display:grid;grid-template-columns:minmax(0,1.4fr) minmax(70px,.55fr) minmax(84px,.6fr);align-items:center;gap:12px}
    .tbl-head{font-size:9px;color:#94a3b8;padding:0 8px 10px;border-bottom:1px solid #f1f5f9;font-weight:700;letter-spacing:.8px;text-transform:uppercase}
    .bike-row{padding:11px 8px;border-bottom:1px solid #f1f5f9;border-radius:8px;transition:background .15s}
    .bike-row:last-child{border-bottom:0}
    .bike-row:hover{background:#f8fafc}
    .bike-name{display:flex;align-items:center;gap:10px;min-width:0}
    .bike-thumb{width:38px;height:38px;flex-shrink:0;border-radius:9px;background:#f3e8ff;display:grid;place-items:center;color:#7c3aed;border:1px solid #e9d5ff}
    .bike-name strong{font-size:12px;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#172033;font-weight:600}
    .bike-name span{display:block;font-size:10px;color:#94a3b8;margin-top:2px}
    .bike-year{font-size:11px;color:#64748b}
    .badge-status{display:inline-flex;align-items:center;gap:5px;border-radius:20px;padding:4px 9px;font-size:9.5px;font-weight:700;white-space:nowrap}
    .badge-published{color:#15803d;background:#f0fdf4;border:1px solid #bbf7d0}
    .badge-published::before{content:"";width:5px;height:5px;border-radius:50%;background:#4ade80}
    .badge-draft{color:#92400e;background:#fffbeb;border:1px solid #fde68a}
    .badge-draft::before{content:"";width:5px;height:5px;border-radius:50%;background:#fbbf24}
    .list-divider{margin:20px 0 16px;border:0;border-top:1px solid #f1f5f9}
    .panel-footer{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-top:16px;padding-top:14px;border-top:1px solid #f1f5f9}
    .muted-note{font-size:10.5px;color:#94a3b8}
    .add-btn{display:inline-flex;align-items:center;gap:7px;background:#7c3aed;color:#fff;border:0;border-radius:9px;padding:9px 14px;font-size:11px;font-weight:700;cursor:pointer;box-shadow:0 4px 12px rgba(124,58,237,.22);transition:background .18s,box-shadow .18s;text-decoration:none}
    .add-btn:hover{background:#6d28d9;box-shadow:0 6px 18px rgba(124,58,237,.3)}
    .add-btn:focus-visible{outline:2px solid #7c3aed;outline-offset:2px}

    /* Recent enquiries */
    .enq-list{display:flex;flex-direction:column}
    .enq-row{display:flex;gap:11px;padding:12px 0;border-bottom:1px solid #f1f5f9}
    .enq-row:last-child{border-bottom:0;padding-bottom:0}
    .enq-avatar{width:34px;height:34px;border-radius:9px;flex-shrink:0;background:#f3e8ff;color:#7c3aed;display:grid;place-items:center;font-weight:700;font-size:12px}
    .enq-body{flex:1;min-width:0}
    .enq-line{display:flex;align-items:center;justify-content:space-between;gap:8px}
    .enq-name{font-size:12px;font-weight:600;color:#172033;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
    .enq-subject{font-size:10.5px;color:#64748b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
    .enq-time{font-size:10px;color:#94a3b8;white-space:nowrap}
    .badge{display:inline-flex;align-items:center;gap:5px;padding:3px 9px;border-radius:20px;font-size:10px;font-weight:600;white-space:nowrap}
    .badge::before{content:"";width:5px;height:5px;border-radius:50%;flex-shrink:0}
    .badge-yellow{color:#92400e;background:#fffbeb;border:1px solid #fde68a}
    .badge-yellow::before{background:#fbbf24}
    .badge-blue{color:#1d4ed8;background:#eff6ff;border:1px solid #bfdbfe}
    .badge-blue::before{background:#60a5fa}
    .badge-green{color:#15803d;background:#f0fdf4;border:1px solid #bbf7d0}
    .badge-green::before{background:#4ade80}
    .badge-muted{color:#64748b;background:#f8fafc;border:1px solid #e2e8f0}
    .badge-muted::before{background:#94a3b8}

    /* Snapshot (fallback panel) */
    .snap-list{display:flex;flex-direction:column;gap:10px}
    .snap-row{display:flex;align-items:center;gap:11px;padding:10px 11px;border:1px solid #f1f5f9;border-radius:10px}
    .snap-icon{width:32px;height:32px;border-radius:9px;display:grid;place-items:center;font-size:12px;flex-shrink:0}
    .snap-row span{flex:1;font-size:12px;color:#475569;font-weight:500}
    .snap-row b{font-size:15px;font-weight:800;color:#172033;letter-spacing:-.4px}

    .dash-footer{display:flex;justify-content:space-between;gap:12px;padding:18px 0 0;color:#94a3b8;font-size:10.5px;border-top:1px solid #e2e8f0}

    @media(max-width:1100px){
        .stats-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
        .content-grid{grid-template-columns:1fr}
    }
    @media(max-width:640px){
        .dash-intro{flex-direction:column;align-items:flex-start;gap:12px}
        .hero-strip{flex-direction:column;align-items:flex-start;gap:16px;padding:18px 20px}
        .hero-visual{display:none}
        .hero-cta{width:100%;justify-content:center}
        .tbl-head,.bike-row{grid-template-columns:minmax(0,1.4fr) auto}
        .tbl-head span:nth-child(2),.bike-year{display:none}
    }
</style>

{{-- Intro --}}
<section class="dash-intro">
    <div>
        <h1>مرحباً، <span>{{ explode(' ', $user->name)[0] }}</span> 👋</h1>
        <p>لقطة سريعة عن كتالوج الموتوسيكلات والنشاط الأخير.</p>
    </div>
    <div class="date-chip">
        <i class="fa-regular fa-calendar"></i> {{ now()->format('D, d M Y') }}
    </div>
</section>

{{-- Hero banner --}}
<section class="hero-strip">
    <div class="hero-copy">
        <div class="hero-eyebrow">MOTOVEX · لوحة التحكم</div>
        <h2>كتالوجك في متناول يدك.</h2>
        <p>أضف موتوسيكلات، راجع الاستفسارات، وابقِ كل شيء محدّثاً.</p>
    </div>
    @if($canManageCatalogue)
    <a href="{{ route('admin.motorcycles.create') }}" class="hero-cta">
        <i class="fa-solid fa-plus"></i> إضافة موتوسيكل
    </a>
    @endif
    <div class="hero-visual" aria-hidden="true"><i class="fa-solid fa-motorcycle"></i></div>
</section>

{{-- KPI cards --}}
<section class="stats-grid">
    @foreach($statCards as [$label, $value, $color])
    @php
        $meta = $statMeta[$label] ?? ['fa-solid fa-chart-simple', 'si-purple', null];
        $href = !empty($meta[2]) && \Illuminate\Support\Facades\Route::has($meta[2]) ? route($meta[2]) : null;
    @endphp
    @if($href)
    <a href="{{ $href }}" class="stat-card">
    @else
    <article class="stat-card">
    @endif
        <div class="stat-top">
            <div class="stat-icon {{ $meta[1] }}"><i class="{{ $meta[0] }}"></i></div>
            <span class="stat-badge {{ $value > 0 ? 'ok' : 'nil' }}">{{ $value > 0 ? 'مسجّل' : 'فارغ' }}</span>
        </div>
        <div class="stat-body">
            <h3>{{ $label }}</h3>
            <div class="stat-value">{{ number_format($value) }}</div>
            <div class="stat-foot">إجمالي السجلات</div>
        </div>
    @if($href)
    </a>
    @else
    </article>
    @endif
    @endforeach
</section>

{{-- Analytics + side panel --}}
<section class="content-grid">

    {{-- Chart + recent motorcycles --}}
    <article class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">نظرة عامة على الكتالوج</div>
                <div class="panel-sub">توزيع السجلات في مساحة عملك</div>
            </div>
            <span class="panel-icon"><i class="fa-solid fa-chart-bar"></i></span>
        </div>
        <div class="chart-wrap">
            <canvas id="catalogueChart"></canvas>
        </div>

        @if($canManageCatalogue && $recentMotorcycles->isNotEmpty())
        <hr class="list-divider">
        <div class="panel-head" style="margin-bottom:14px">
            <div class="panel-title" style="font-size:12.5px">آخر الموتوسيكلات</div>
            <a class="text-link" href="{{ route('admin.motorcycles.index') }}">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
        </div>
        <div class="tbl-head"><span>الموديل</span><span>السنة</span><span>الحالة</span></div>
        @foreach($recentMotorcycles as $m)
        <div class="bike-row">
            <div class="bike-name">
                <div class="bike-thumb"><i class="fa-solid fa-motorcycle"></i></div>
                <div>
                    <strong>{{ $m->model_name }}</strong>
                    <span>{{ $m->brand->name }}</span>
                </div>
            </div>
            <div class="bike-year">{{ $m->year }}</div>
            <div>
                <span class="badge-status {{ $m->is_published ? 'badge-published' : 'badge-draft' }}">
                    {{ $m->is_published ? 'Published' : 'Draft' }}
                </span>
            </div>
        </div>
        @endforeach
        @endif

        @if($canManageCatalogue)
        <div class="panel-footer">
            <span class="muted-note">آخر الإضافات إلى الكتالوج</span>
            <a href="{{ route('admin.motorcycles.create') }}" class="add-btn">
                <i class="fa-solid fa-plus"></i> إضافة موتوسيكل
            </a>
        </div>
        @endif
    </article>

    {{-- Recent enquiries (or workspace snapshot fallback) --}}
    <article class="panel">
        @if($canSeeEnquiries && $recentEnquiries->isNotEmpty())
        <div class="panel-head">
            <div>
                <div class="panel-title">أحدث الاستفسارات</div>
                <div class="panel-sub">آخر الرسائل الواردة من الموقع</div>
            </div>
            <span class="panel-icon"><i class="fa-solid fa-envelope"></i></span>
        </div>
        <div class="enq-list">
            @foreach($recentEnquiries->take(5) as $e)
            <div class="enq-row">
                <div class="enq-avatar">{{ strtoupper(substr($e->name, 0, 1)) }}</div>
                <div class="enq-body">
                    <div class="enq-line">
                        <span class="enq-name">{{ $e->name }}</span>
                        <span class="enq-time">{{ $e->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="enq-line" style="margin-top:5px">
                        <span class="enq-subject">{{ $e->subject ?: 'بدون موضوع' }}</span>
                        <span class="badge {{ $statusBadge[$e->status] ?? 'badge-muted' }}">{{ ucfirst($e->status) }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="panel-footer">
            <span class="muted-note">{{ $recentEnquiries->count() }} استفسار حديث</span>
            <a href="{{ route('admin.enquiries.index') }}" class="text-link">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
        </div>
        @else
        <div class="panel-head">
            <div>
                <div class="panel-title">ملخص المساحة</div>
                <div class="panel-sub">أرقام حية من قاعدة البيانات</div>
            </div>
            <span class="panel-icon"><i class="fa-solid fa-chart-simple"></i></span>
        </div>
        <div class="snap-list">
            @foreach($statCards as [$label, $value, $color])
            @php $meta = $statMeta[$label] ?? ['fa-solid fa-chart-simple', 'si-purple', null]; @endphp
            <div class="snap-row">
                <div class="snap-icon {{ $meta[1] }}"><i class="{{ $meta[0] }}"></i></div>
                <span>{{ $label }}</span>
                <b>{{ number_format($value) }}</b>
            </div>
            @endforeach
        </div>
        @endif
    </article>
</section>

{{-- Footer --}}
<div class="dash-footer">
    <span>© {{ date('Y') }} MOTOVEX — Control Center</span>
    <span>{{ now()->format('H:i') }}</span>
</div>

<script>
(function(){
    const labels = @json(array_column($statCards, 0));
    const values = @json(array_column($statCards, 1));

    const barColors = [
        'rgba(124,58,237,.9)',
        'rgba(139,92,246,.9)',
        'rgba(167,139,250,.9)',
        'rgba(196,181,253,.9)',
        'rgba(221,214,254,.9)',
        'rgba(233,213,255,.9)',
    ];

    const ctx = document.getElementById('catalogueChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'العدد',
                data: values,
                backgroundColor: barColors.slice(0, labels.length),
                borderWidth: 0,
                borderRadius: 8,
                borderSkipped: false,
                barThickness: 40,
                maxBarThickness: 46,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 600, easing: 'easeOutQuart' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#ffffff',
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                    titleColor: '#172033',
                    titleFont: { family: 'Inter', size: 12, weight: '600' },
                    bodyColor: '#64748b',
                    bodyFont: { family: 'Inter', size: 11 },
                    padding: 12,
                    cornerRadius: 10,
                    displayColors: true,
                    boxWidth: 8, boxHeight: 8,
                    callbacks: {
                        label: (c) => `  ${c.parsed.y} سجل`
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: {
                        color: '#64748b',
                        font: { size: 11.5, family: 'Inter', weight: '500' },
                        padding: 8,
                    }
                },
                y: {
                    beginAtZero: true,
                    min: 0,
                    grid: { color: '#f1f5f9', drawBorder: false },
                    border: { display: false },
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 11, family: 'Inter' },
                        stepSize: 1,
                        precision: 0,
                        padding: 10,
                    }
                }
            }
        }
    });
})();
</script>
@endsection
