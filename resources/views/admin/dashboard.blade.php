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
@endphp

<style>
    .welcome-section{display:flex;align-items:flex-end;justify-content:space-between;gap:20px;margin-bottom:26px}
    .welcome-section h1{font-size:clamp(22px,3vw,30px);letter-spacing:-1px;margin-bottom:6px}
    .welcome-section h1 span{background:linear-gradient(90deg,#d8b9ff,#f29cdb);background-clip:text;-webkit-background-clip:text;color:transparent}
    .welcome-section p{font-size:12px;color:#9b96ad}
    .date-chip{display:flex;align-items:center;gap:9px;border:1px solid rgba(255,255,255,.08);background:#ffffff04;border-radius:11px;padding:10px 13px;color:#bdb6ce;font-size:11px;white-space:nowrap}
    .date-chip i{color:#c39aff}

    /* Hero strip */
    .hero-strip{position:relative;overflow:hidden;display:flex;align-items:center;justify-content:space-between;gap:25px;min-height:140px;padding:24px 28px;margin-bottom:22px;border:1px solid #ad7aff30;border-radius:20px;background:linear-gradient(105deg,#211534 0%,#191326 52%,#29163a 100%);box-shadow:0 16px 50px #0002}
    .hero-strip:before{content:"";position:absolute;width:300px;height:300px;right:25%;top:-190px;border-radius:50%;background:#a653ff29;filter:blur(20px)}
    .hero-copy{position:relative;z-index:1}
    .hero-eyebrow{display:inline-flex;align-items:center;gap:7px;color:#d1adff;font-size:9px;font-weight:800;letter-spacing:1.7px;text-transform:uppercase;margin-bottom:10px}
    .hero-eyebrow:before{content:"";width:18px;height:2px;background:#ed5bb7;border-radius:2px}
    .hero-strip h2{font-size:20px;margin-bottom:6px;letter-spacing:-.4px}
    .hero-strip p{font-size:11px;color:#b5adc5}
    .hero-cta{position:relative;z-index:1;display:inline-flex;align-items:center;gap:10px;padding:12px 17px;border-radius:11px;background:linear-gradient(100deg,#a25cf3,#c35dd5);font-size:11px;font-weight:800;box-shadow:0 9px 25px #a25cf333;white-space:nowrap;color:#fff;border:0;cursor:pointer;text-decoration:none;transition:.2s}
    .hero-cta:hover{filter:brightness(1.1);transform:translateY(-1px)}
    .hero-visual{position:absolute;right:55%;top:-55px;width:300px;height:250px;opacity:.1;transform:rotate(-14deg);pointer-events:none}
    .hero-visual i{font-size:175px;color:#e4c8ff}

    /* Stats */
    .stats-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:15px;margin-bottom:24px}
    .stat-card{position:relative;overflow:hidden;background:linear-gradient(145deg,#191526ef,#12101c);border:1px solid rgba(255,255,255,.08);border-radius:17px;padding:19px;transition:transform .22s,border-color .22s}
    .stat-card:hover{transform:translateY(-4px);border-color:#a86aff5c}
    .stat-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:17px}
    .stat-icon{width:39px;height:39px;display:grid;place-items:center;border-radius:12px;font-size:15px}
    .si-purple{color:#c6a0ff;background:#9b5cff1c}
    .si-pink{color:#ff9edb;background:#ed5bb71c}
    .si-green{color:#b7f36b;background:#b7f36b16}
    .si-blue{color:#8dd7ff;background:#4ab9f31a}
    .stat-badge{font-size:9px;padding:5px 7px;border-radius:7px}
    .badge-green{color:#b7f36b;background:#b7f36b12}
    .badge-muted{color:#b8a8d5;background:#ffffff08}
    .stat-card h3{font-size:11px;color:#a49db5;font-weight:600;margin-bottom:4px}
    .stat-value{font-size:28px;font-weight:800;letter-spacing:-1px}
    .stat-foot{font-size:9px;color:#746d86;margin-top:5px}

    /* Content grid */
    .content-grid{display:grid;grid-template-columns:minmax(0,1.55fr) minmax(260px,.85fr);gap:20px;margin-bottom:24px}
    .panel{background:linear-gradient(145deg,#171321ef,#110f1a);border:1px solid rgba(255,255,255,.08);border-radius:18px;padding:22px;min-width:0}
    .panel-head{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:20px}
    .panel-title{font-size:14px;font-weight:800}
    .panel-sub{font-size:10px;color:#817a91;margin-top:3px}
    .text-link{color:#c49aff;font-size:10px;font-weight:700;white-space:nowrap}

    /* Chart panel */
    .chart-wrap { position: relative; height: 280px; margin-bottom: 4px; }

    /* Table */
    .tbl-head,.bike-row{display:grid;grid-template-columns:minmax(0,1.4fr) minmax(75px,.6fr) minmax(80px,.6fr);align-items:center;gap:12px}
    .tbl-head{font-size:9px;color:#777087;padding:0 12px 10px;border-bottom:1px solid rgba(255,255,255,.08)}
    .bike-row{padding:13px 12px;border-bottom:1px solid #ffffff07;transition:background .2s}
    .bike-row:last-child{border-bottom:0}
    .bike-row:hover{background:#ffffff04;border-radius:10px}
    .bike-name{display:flex;align-items:center;gap:11px;min-width:0}
    .bike-thumb{width:42px;height:42px;flex-shrink:0;border-radius:12px;background:linear-gradient(135deg,#35204d,#17101f);display:grid;place-items:center;color:#c48aff;border:1px solid #a86aff20}
    .bike-name strong{font-size:11px;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
    .bike-name span{display:block;font-size:9px;color:#847c95;margin-top:3px}
    .bike-year{font-size:10px;color:#bcb5ca}
    .badge-status{display:inline-flex;align-items:center;gap:6px;border-radius:20px;padding:5px 9px;font-size:9px;white-space:nowrap}
    .badge-published{color:#b7f36b;background:#b7f36b12;border:1px solid #b7f36b20}
    .badge-published:before{content:"";width:5px;height:5px;border-radius:50%;background:#b7f36b}
    .badge-draft{color:#fbbf24;background:#fbbf2412;border:1px solid #fbbf2420}
    .badge-draft:before{content:"";width:5px;height:5px;border-radius:50%;background:#fbbf24}
    .panel-footer{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-top:16px}
    .muted-note{font-size:9px;color:#746d86}
    .add-btn{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(105deg,#9b5cff,#dc5dbd);color:#fff;border:0;border-radius:10px;padding:11px 14px;font-size:10px;font-weight:800;cursor:pointer;box-shadow:0 8px 22px #a653ff24;transition:.2s;text-decoration:none}
    .add-btn:hover{transform:translateY(-2px);filter:brightness(1.08)}

    /* Activity */
    .activity-list{display:grid;gap:18px}
    .activity{display:flex;align-items:flex-start;gap:11px}
    .activity-icon{width:33px;height:33px;border-radius:11px;display:grid;place-items:center;flex-shrink:0;font-size:12px}
    .activity strong{display:block;font-size:10px;margin:2px 0 4px}
    .activity p{font-size:9px;color:#8a839b;line-height:1.6}
    .activity time{display:block;color:#625b74;font-size:9px;margin-top:4px}
    .activity-divider{height:1px;background:rgba(255,255,255,.08)}

    /* Quick actions */
    .quick-actions{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:24px}
    .qa-btn{display:inline-flex;align-items:center;gap:8px;padding:11px 16px;border-radius:12px;font-size:11px;font-weight:700;cursor:pointer;transition:.2s;text-decoration:none}
    .qa-primary{background:linear-gradient(105deg,#9b5cff,#dc5dbd);color:#fff;border:0;box-shadow:0 8px 22px #a653ff24}
    .qa-primary:hover{transform:translateY(-2px);filter:brightness(1.08)}
    .qa-ghost{background:#ffffff06;border:1px solid rgba(255,255,255,.1);color:#d4cfea}
    .qa-ghost:hover{border-color:#a16aff66;background:#a16aff10;color:#fff}

    /* Footer */
    .dash-footer{display:flex;justify-content:space-between;gap:12px;padding:20px 3px 0;color:#5f586f;font-size:9px;border-top:1px solid rgba(255,255,255,.06);margin-top:8px}

    @media(max-width:1100px){.stats-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.content-grid{grid-template-columns:1fr}}
    @media(max-width:600px){.stats-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.welcome-section{flex-direction:column;align-items:flex-start}.date-chip{display:none}.hero-strip{flex-direction:column;align-items:flex-start}.hero-visual{opacity:.06}.tbl-head,.bike-row{grid-template-columns:minmax(0,1.4fr) auto}}
</style>

{{-- Welcome --}}
<section class="welcome-section">
    <div>
        <h1>مرحباً، <span>{{ explode(' ', $user->name)[0] }}</span> 👋</h1>
        <p>لقطة سريعة عن كتالوج الموتوسيكلات والنشاط الأخير.</p>
    </div>
    <div class="date-chip">
        <i class="fa-regular fa-calendar"></i> {{ now()->format('D, d M Y') }}
    </div>
</section>

{{-- Hero Strip --}}
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
    <div class="hero-visual"><i class="fa-solid fa-motorcycle"></i></div>
</section>

{{-- Stats --}}
<section class="stats-grid">
    @foreach($statCards as [$label, $value, $color])
    @php
        $icons  = ['fa-regular fa-copyright','fa-solid fa-motorcycle','fa-solid fa-tags','fa-solid fa-shop','fa-solid fa-envelope','fa-solid fa-users'];
        $iclass = ['si-purple','si-pink','si-green','si-blue','si-purple','si-pink'];
        $i = $loop->index % 4;
    @endphp
    <article class="stat-card">
        <div class="stat-top">
            <div class="stat-icon {{ $iclass[$i] }}"><i class="{{ $icons[$i] }}"></i></div>
            <span class="stat-badge {{ $value > 0 ? 'badge-green' : 'badge-muted' }}">
                {{ $value > 0 ? 'مسجّل' : 'فارغ' }}
            </span>
        </div>
        <h3>{{ $label }}</h3>
        <div class="stat-value">{{ str_pad($value, 2, '0', STR_PAD_LEFT) }}</div>
        <div class="stat-foot">إجمالي السجلات</div>
    </article>
    @endforeach
</section>

{{-- Chart + Motorcycles --}}
<section class="content-grid">

    {{-- Chart Panel --}}
    <article class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">نظرة عامة على الكتالوج</div>
                <div class="panel-sub">توزيع السجلات في مساحة عملك</div>
            </div>
            <span class="stat-icon si-purple" style="width:33px;height:33px;font-size:13px"><i class="fa-solid fa-chart-bar"></i></span>
        </div>
        <div class="chart-wrap">
            <canvas id="catalogueChart"></canvas>
        </div>

        @if($canManageCatalogue && $recentMotorcycles->isNotEmpty())
        <div style="margin-top:22px;border-top:1px solid rgba(255,255,255,.07);padding-top:18px">
            <div class="panel-head" style="margin-bottom:14px">
                <div class="panel-title" style="font-size:12px">آخر الموتوسيكلات</div>
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
        </div>
        @endif

        <div class="panel-footer">
            <span class="muted-note">آخر الإضافات</span>
            @if($canManageCatalogue)
            <a href="{{ route('admin.motorcycles.create') }}" class="add-btn">
                <i class="fa-solid fa-plus"></i> إضافة موتوسيكل
            </a>
            @endif
        </div>
    </article>

    {{-- Snapshot + Enquiries --}}
    <article class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">ملخص المساحة</div>
                <div class="panel-sub">حالة الكتالوج والنشاط الأخير</div>
            </div>
            <span class="stat-icon si-purple" style="width:33px;height:33px;font-size:13px"><i class="fa-solid fa-chart-simple"></i></span>
        </div>

        <div class="activity-list">
            @foreach($statCards as [$label, $value, $color])
            @php
                $actIcons = ['fa-regular fa-copyright','fa-solid fa-motorcycle','fa-solid fa-tags','fa-solid fa-shop','fa-solid fa-envelope','fa-solid fa-users'];
                $actClass = ['si-purple','si-pink','si-green','si-blue','si-purple','si-pink'];
                $j = $loop->index % 4;
            @endphp
            @if(!$loop->first)<div class="activity-divider"></div>@endif
            <div class="activity">
                <div class="activity-icon {{ $actClass[$j] }}"><i class="{{ $actIcons[$j] }}"></i></div>
                <div>
                    <strong>{{ $label }}</strong>
                    <p>{{ $value }} سجل {{ $value === 1 ? 'مضاف' : 'مضافة' }} حتى الآن.</p>
                    <time>الكتالوج</time>
                </div>
            </div>
            @endforeach

            @if($canSeeEnquiries && $recentEnquiries->isNotEmpty())
            <div class="activity-divider"></div>
            @foreach($recentEnquiries->take(3) as $e)
            <div class="activity">
                <div class="activity-icon si-pink"><i class="fa-solid fa-envelope"></i></div>
                <div>
                    <strong>{{ $e->name }}</strong>
                    <p>{{ $e->subject }}</p>
                    <time>{{ $e->created_at->diffForHumans() }}</time>
                </div>
            </div>
            @if(!$loop->last)<div class="activity-divider"></div>@endif
            @endforeach
            @endif
        </div>
    </article>
</section>

{{-- Quick Actions --}}
<div class="quick-actions">
    @if($canManageCatalogue)
    <a href="{{ route('admin.motorcycles.create') }}" class="qa-btn qa-primary">
        <i class="fa-solid fa-plus"></i> إضافة موتوسيكل
    </a>
    @endif
    @if($user->isAdmin())
    <a href="{{ route('admin.brands.create') }}" class="qa-btn qa-ghost">
        <i class="fa-regular fa-copyright"></i> إضافة براند
    </a>
    <a href="{{ route('admin.users.create') }}" class="qa-btn qa-ghost">
        <i class="fa-solid fa-user-plus"></i> إضافة مستخدم
    </a>
    @endif
    @if($canSeeEnquiries)
    <a href="{{ route('admin.enquiries.index') }}" class="qa-btn qa-ghost">
        <i class="fa-solid fa-envelope"></i> الاستفسارات
    </a>
    @endif
    <a href="{{ route('home') }}" target="_blank" class="qa-btn qa-ghost">
        <i class="fa-solid fa-arrow-up-right-from-square"></i> الموقع العام
    </a>
</div>

{{-- Footer --}}
<div class="dash-footer">
    <span>© {{ date('Y') }} MOTOVEX AURA</span>
    <span>لوحة التحكم</span>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function(){
    const labels = @json(array_column($statCards, 0));
    const values = @json(array_column($statCards, 1));

    const barColors = [
        'rgba(155,92,255,.9)',
        'rgba(237,91,183,.9)',
        'rgba(183,243,107,.85)',
        'rgba(141,215,255,.85)',
        'rgba(200,140,255,.9)',
        'rgba(255,160,220,.85)',
    ];
    const glowColors = [
        '#9b5cff', '#ed5bb7', '#b7f36b', '#8dd7ff', '#c88cff', '#ffa0dc'
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
                borderColor: glowColors.slice(0, labels.length),
                borderWidth: 0,
                borderRadius: 12,
                borderSkipped: false,
                barThickness: 44,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 700, easing: 'easeOutQuart' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e1630',
                    borderColor: 'rgba(155,92,255,.4)',
                    borderWidth: 1,
                    titleColor: '#e9d5ff',
                    titleFont: { family: 'Inter', size: 12, weight: '600' },
                    bodyColor: '#a89cc0',
                    bodyFont: { family: 'Inter', size: 11 },
                    padding: 12,
                    cornerRadius: 10,
                    displayColors: true,
                    boxWidth: 8, boxHeight: 8,
                    callbacks: {
                        label: (ctx) => `  ${ctx.parsed.y} سجل`
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: {
                        color: '#9b96ad',
                        font: { size: 12, family: 'Inter', weight: '500' },
                        padding: 8,
                    }
                },
                y: {
                    beginAtZero: true,
                    min: 0,
                    grid: {
                        color: 'rgba(255,255,255,.06)',
                        drawBorder: false,
                    },
                    border: { display: false, dash: [4,4] },
                    ticks: {
                        color: '#6b6480',
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
