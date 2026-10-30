@extends('layouts.admin')
@section('title', 'Dashboard')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('styles')
<style>
/* ══════════════════════════════════════════════
   DASHBOARD — Premium Design
══════════════════════════════════════════════ */
.welcome-bar {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 26px; gap: 16px; flex-wrap: wrap;
}
.welcome-bar h3 { font-size: 22px; font-weight: 800; margin: 0; color: var(--dark); }
.welcome-bar p  { color: var(--muted); margin: 4px 0 0; font-size: 13px; }
.date-chip {
    display: inline-flex; align-items: center; gap: 8px;
    background: #fff; border: 1px solid var(--border);
    padding: 10px 16px; border-radius: 10px;
    color: #315078; font-size: 12.5px; font-weight: 600;
    cursor: pointer; transition: border-color .15s;
}
.date-chip:hover { border-color: var(--primary); }

/* ── KPI Cards ── */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px; margin-bottom: 22px;
}
@media(max-width:1100px){ .kpi-grid{ grid-template-columns: repeat(2,1fr); } }
@media(max-width:540px){  .kpi-grid{ grid-template-columns: 1fr; } }

.kpi-card {
    background: #fff; border: 1px solid var(--border);
    border-radius: 14px; padding: 20px 20px 16px;
    position: relative; overflow: hidden;
    transition: transform .22s, box-shadow .22s;
}
.kpi-card:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(8,101,223,.1); }
.kpi-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
    border-radius: 14px 14px 0 0;
}
.kpi-card.kc-blue::before   { background: linear-gradient(90deg,#2563eb,#60a5fa); }
.kpi-card.kc-green::before  { background: linear-gradient(90deg,#16a34a,#4ade80); }
.kpi-card.kc-amber::before  { background: linear-gradient(90deg,#d97706,#fbbf24); }
.kpi-card.kc-purple::before { background: linear-gradient(90deg,#7c3aed,#a78bfa); }

.kpi-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
.kpi-icon {
    width: 44px; height: 44px; border-radius: 12px;
    display: grid; place-items: center; font-size: 18px;
}
.ki-blue   { background: #eff6ff; color: #2563eb; }
.ki-green  { background: #f0fdf4; color: #16a34a; }
.ki-amber  { background: #fffbeb; color: #d97706; }
.ki-purple { background: #f3e8ff; color: #7c3aed; }

.kpi-chip {
    font-size: 10px; font-weight: 700; padding: 3px 9px;
    border-radius: 999px; display: inline-flex; align-items: center; gap: 4px;
}
.chip-up   { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
.chip-down { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

.kpi-val { font-size: 30px; font-weight: 900; color: var(--dark); letter-spacing: -1px; line-height: 1; }
.kpi-lbl { font-size: 12px; font-weight: 600; color: var(--muted); margin-top: 4px; }
.kpi-sub { font-size: 11px; color: #94a3b8; margin-top: 8px; }
.mini-chart-wrap { position: absolute; right: 14px; bottom: 22px; width: 80px; height: 44px; }

/* ── Panels ── */
.dash-panel {
    background: #fff; border: 1px solid var(--border);
    border-radius: 12px; overflow: hidden;
    box-shadow: 0 1px 4px rgba(0,0,0,.03);
}
.dash-panel-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px; border-bottom: 1px solid var(--border);
}
.dash-panel-title { font-size: 14px; font-weight: 700; color: var(--dark); margin: 0; }
.dash-panel-link  { font-size: 12px; color: var(--primary); text-decoration: none; font-weight: 600; }
.dash-panel-link:hover { text-decoration: underline; }
.dash-panel-body { padding: 16px 20px; }

/* ── Recent orders table ── */
.ord-table { width: 100%; border-collapse: collapse; }
.ord-table th {
    background: #f8fafc; color: #64748b; font-size: 10.5px;
    font-weight: 700; padding: 10px 12px; text-align: left;
    border-bottom: 1px solid var(--border); white-space: nowrap;
    letter-spacing: .3px; text-transform: uppercase;
}
.ord-table td {
    padding: 13px 12px; border-bottom: 1px solid #f1f5f9;
    font-size: 12px; color: var(--dark); vertical-align: middle;
}
.ord-table tbody tr:last-child td { border-bottom: none; }
.ord-table tbody tr:hover { background: #fafcff; }

.ord-id { font-family: ui-monospace,monospace; font-size: 11.5px; font-weight: 700; color: var(--primary); }
.ord-cust-av {
    width: 30px; height: 30px; border-radius: 8px;
    background: linear-gradient(135deg,#e0efff,#c7d9f8);
    color: #1d4ed8; font-weight: 700; font-size: 11px;
    display: grid; place-items: center; flex-shrink: 0;
}
.ord-status {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 999px;
    font-size: 10px; font-weight: 700; white-space: nowrap;
}
.os-pending    { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
.os-confirmed  { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
.os-shipped    { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.os-processing { background: #f5f3ff; color: #6d28d9; border: 1px solid #e9d5ff; }
.os-completed  { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }

/* ── Top models ── */
.model-row {
    display: flex; align-items: center; gap: 12px;
    padding: 11px 0; border-bottom: 1px solid #f1f5f9;
}
.model-row:last-child { border-bottom: none; }
.model-rank {
    width: 24px; height: 24px; border-radius: 7px;
    background: #f8fafc; border: 1px solid var(--border);
    display: grid; place-items: center; font-weight: 700;
    font-size: 11px; color: var(--dark); flex-shrink: 0;
}
.model-info { flex: 1; min-width: 0; }
.model-info strong { font-size: 12px; display: block; font-weight: 700; color: var(--dark); }
.model-info small  { font-size: 10.5px; color: var(--muted); margin: 3px 0; display: block; }
.model-bar { height: 4px; background: #e2e8f0; border-radius: 4px; }
.model-bar-fill { height: 4px; background: linear-gradient(90deg,#2563eb,#60a5fa); border-radius: 4px; }
.model-sold { font-weight: 800; font-size: 13px; color: var(--dark); text-align: right; flex-shrink: 0; }
.model-sold small { display: block; font-size: 10px; color: var(--muted); font-weight: 400; }

/* ── Chart box ── */
.chart-box { height: 185px; position: relative; }

/* ── Quick stats ── */
.qs-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 13px 0; border-bottom: 1px solid #f1f5f9;
}
.qs-item:last-child { border-bottom: none; }
.qs-left { display: flex; align-items: center; gap: 10px; }
.qs-icon {
    width: 32px; height: 32px; border-radius: 8px;
    background: #eff6ff; color: var(--primary);
    display: grid; place-items: center; font-size: 13px; flex-shrink: 0;
}
.qs-lbl { font-size: 12px; color: var(--muted); }
.qs-val { font-size: 13px; font-weight: 700; color: var(--dark); }
.qs-up { font-size: 11px; font-weight: 700; color: #16a34a; }

/* ── Activity items ── */
.activity-item {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 12px 0; border-bottom: 1px solid #f1f5f9;
}
.activity-item:last-child { border-bottom: none; }
.activity-dot {
    width: 8px; height: 8px; border-radius: 50%;
    margin-top: 5px; flex-shrink: 0;
}

/* ── Shortcuts ── */
.shortcut-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: 10px; }
.shortcut-btn {
    display: flex; align-items: center; gap: 10px;
    padding: 13px 14px; border-radius: 10px;
    background: #f8fafc; border: 1px solid var(--border);
    text-decoration: none; transition: all .18s;
}
.shortcut-btn:hover {
    background: #eff6ff; border-color: #bfdbfe;
    transform: translateY(-1px); box-shadow: 0 4px 12px rgba(8,101,223,.1);
}
.shortcut-ic {
    width: 34px; height: 34px; border-radius: 9px;
    background: var(--primary); color: #fff;
    display: grid; place-items: center; font-size: 14px; flex-shrink: 0;
}
.shortcut-lbl { font-size: 12px; font-weight: 700; color: var(--dark); }
.shortcut-sub { font-size: 10.5px; color: var(--muted); margin-top: 1px; }
</style>
@endsection

@section('content')
@php $user = auth()->user(); @endphp

{{-- ── Welcome bar ── --}}
<div class="welcome-bar">
    <div>
        <h3>
            @php
                $hour = now()->hour;
                $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
            @endphp
            {{ $greeting }}, {{ explode(' ', $user->name)[0] }} 👋
        </h3>
        <p>Here's what's happening across your motorcycle platform today.</p>
    </div>
    <div class="date-chip">
        <i class="bi bi-calendar3"></i>
        {{ now()->subMonth()->format('d M Y') }} — {{ now()->format('d M Y') }}
        <i class="bi bi-chevron-down" style="font-size:10px;"></i>
    </div>
</div>

{{-- ── KPI Cards ── --}}
@php
    $totalSales    = \App\Models\Order::count();
    $pendingOrders = \App\Models\Order::where('status','pending')->count();
    $totalMotos    = \App\Models\Motorcycle::count();
    $totalUsers    = \App\Models\User::count();
@endphp
<div class="kpi-grid">
    <div class="kpi-card kc-blue">
        <div class="kpi-head">
            <div class="kpi-icon ki-blue"><i class="bi bi-cart3"></i></div>
            <span class="kpi-chip chip-up"><i class="bi bi-arrow-up"></i> Live</span>
        </div>
        <div class="kpi-val">{{ $totalSales }}</div>
        <div class="kpi-lbl">Total Orders</div>
        <div class="kpi-sub">All time sales orders</div>
        <div class="mini-chart-wrap"><canvas id="mini1"></canvas></div>
    </div>

    <div class="kpi-card kc-amber">
        <div class="kpi-head">
            <div class="kpi-icon ki-amber"><i class="bi bi-clock-fill"></i></div>
            @if($pendingOrders > 0)
            <span class="kpi-chip chip-down"><i class="bi bi-exclamation"></i> Action</span>
            @endif
        </div>
        <div class="kpi-val">{{ $pendingOrders }}</div>
        <div class="kpi-lbl">Pending Orders</div>
        <div class="kpi-sub">Awaiting processing</div>
        <div class="mini-chart-wrap"><canvas id="mini2"></canvas></div>
    </div>

    <div class="kpi-card kc-purple">
        <div class="kpi-head">
            <div class="kpi-icon ki-purple"><i class="bi bi-bicycle"></i></div>
            <span class="kpi-chip chip-up"><i class="bi bi-arrow-up"></i> Active</span>
        </div>
        <div class="kpi-val">{{ $totalMotos }}</div>
        <div class="kpi-lbl">Motorcycles</div>
        <div class="kpi-sub">In product catalogue</div>
        <div class="mini-chart-wrap"><canvas id="mini3"></canvas></div>
    </div>

    <div class="kpi-card kc-green">
        <div class="kpi-head">
            <div class="kpi-icon ki-green"><i class="bi bi-people-fill"></i></div>
            <span class="kpi-chip chip-up"><i class="bi bi-arrow-up"></i> Growing</span>
        </div>
        <div class="kpi-val">{{ $totalUsers }}</div>
        <div class="kpi-lbl">Total Users</div>
        <div class="kpi-sub">Registered accounts</div>
        <div class="mini-chart-wrap"><canvas id="mini4"></canvas></div>
    </div>
</div>

{{-- ── Row 2: Recent Orders + Top Models ── --}}
<div class="row g-3 mb-3">
    <div class="col-xl-8">
        <div class="dash-panel">
            <div class="dash-panel-head">
                <div class="dash-panel-title"><i class="bi bi-receipt me-2 text-primary"></i>Recent Orders</div>
                <a href="{{ route('admin.sales.index') }}" class="dash-panel-link">
                    View All <i class="bi bi-arrow-right" style="font-size:10px;"></i>
                </a>
            </div>
            <div style="overflow-x:auto;">
                @php
                    $recentOrders = \App\Models\Order::with(['items'])->latest()->take(5)->get();
                @endphp
                @if($recentOrders->isEmpty())
                <div style="padding:48px 20px;text-align:center;color:var(--muted);">
                    <i class="bi bi-cart-x" style="font-size:40px;opacity:.2;display:block;margin-bottom:10px;"></i>
                    No orders yet.
                </div>
                @else
                <table class="ord-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin.sales.show', $order) }}" class="ord-id">
                                    {{ $order->order_reference }}
                                </a>
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:9px;">
                                    <div class="ord-cust-av">{{ strtoupper(substr($order->name,0,1)) }}</div>
                                    <div>
                                        <div style="font-weight:600;font-size:12px;">{{ $order->name }}</div>
                                        <div style="font-size:10.5px;color:var(--muted);">{{ $order->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-weight:600;color:var(--dark);">{{ $order->items->count() }}</td>
                            <td style="font-weight:700;color:var(--dark);">{{ $order->currency }} {{ number_format($order->total_amount,0) }}</td>
                            <td>
                                <span class="ord-status os-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td style="font-size:11px;color:var(--muted);">{{ $order->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="dash-panel" style="height:100%;">
            <div class="dash-panel-head">
                <div class="dash-panel-title"><i class="bi bi-trophy me-2 text-warning"></i>Top Motorcycles</div>
                <a href="{{ route('admin.motorcycles.index') }}" class="dash-panel-link">View All</a>
            </div>
            <div class="dash-panel-body" style="padding-top:8px;padding-bottom:8px;">
                @php
                    $topMotos = \App\Models\Motorcycle::withCount('enquiries')
                        ->orderByDesc('enquiries_count')->take(5)->get();
                    $maxCount = $topMotos->max('enquiries_count') ?: 1;
                @endphp
                @forelse($topMotos as $i => $moto)
                <div class="model-row">
                    <div class="model-rank">{{ $i + 1 }}</div>
                    <div style="width:38px;height:30px;border-radius:6px;overflow:hidden;background:#f8fafc;border:1px solid var(--border);display:grid;place-items:center;flex-shrink:0;">
                        @if($moto->image_url)
                            <img src="{{ $moto->image_url }}" style="width:100%;height:100%;object-fit:cover;" alt="">
                        @else
                            <i class="bi bi-bicycle text-muted" style="font-size:12px;"></i>
                        @endif
                    </div>
                    <div class="model-info">
                        <strong>{{ Str::limit($moto->model_name,22) }}</strong>
                        <small>{{ $moto->brand?->name ?? '' }}</small>
                        <div class="model-bar">
                            <div class="model-bar-fill" style="width:{{ $maxCount > 0 ? round($moto->enquiries_count/$maxCount*100) : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="model-sold">{{ $moto->enquiries_count }}<small>enquiries</small></div>
                </div>
                @empty
                <div style="text-align:center;padding:30px 0;color:var(--muted);font-size:12px;">No data yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ── Row 3: Charts + Quick Stats ── --}}
<div class="row g-3 mb-3">
    <div class="col-xl-5 col-md-6">
        <div class="dash-panel">
            <div class="dash-panel-head">
                <div class="dash-panel-title"><i class="bi bi-bar-chart-line me-2 text-primary"></i>Orders Overview</div>
                <small style="color:var(--muted);font-size:11px;">Last 6 months</small>
            </div>
            <div class="dash-panel-body">
                <div class="chart-box"><canvas id="salesChart"></canvas></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="dash-panel">
            <div class="dash-panel-head">
                <div class="dash-panel-title"><i class="bi bi-pie-chart me-2" style="color:#7c3aed;"></i>By Category</div>
            </div>
            <div class="dash-panel-body">
                <div class="chart-box"><canvas id="categoryChart"></canvas></div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="dash-panel">
            <div class="dash-panel-head">
                <div class="dash-panel-title"><i class="bi bi-lightning-charge me-2 text-warning"></i>Quick Stats</div>
            </div>
            <div class="dash-panel-body" style="padding-top:6px;padding-bottom:6px;">
                @php
                    $enquiriesCount = \App\Models\Enquiry::count();
                    $testRidesCount = \App\Models\TestRideRequest::count();
                    $brandsCount    = \App\Models\Brand::where('is_published',true)->count();
                    $partsCount     = \App\Models\SparePart::count();
                @endphp
                <div class="qs-item">
                    <div class="qs-left">
                        <div class="qs-icon"><i class="bi bi-envelope-fill"></i></div>
                        <div class="qs-lbl">Enquiries</div>
                    </div>
                    <div class="qs-val">{{ $enquiriesCount }}</div>
                </div>
                <div class="qs-item">
                    <div class="qs-left">
                        <div class="qs-icon" style="background:#f3e8ff;color:#7c3aed;"><i class="bi bi-calendar-check-fill"></i></div>
                        <div class="qs-lbl">Test Rides</div>
                    </div>
                    <div class="qs-val">{{ $testRidesCount }}</div>
                </div>
                <div class="qs-item">
                    <div class="qs-left">
                        <div class="qs-icon" style="background:#fffbeb;color:#d97706;"><i class="bi bi-patch-check-fill"></i></div>
                        <div class="qs-lbl">Active Brands</div>
                    </div>
                    <div class="qs-val">{{ $brandsCount }}</div>
                </div>
                <div class="qs-item">
                    <div class="qs-left">
                        <div class="qs-icon" style="background:#f0fdf4;color:#16a34a;"><i class="bi bi-gear-fill"></i></div>
                        <div class="qs-lbl">Spare Parts</div>
                    </div>
                    <div class="qs-val">{{ $partsCount }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Row 4: Shortcuts ── --}}
<div class="row g-3">
    <div class="col-12">
        <div class="dash-panel">
            <div class="dash-panel-head">
                <div class="dash-panel-title"><i class="bi bi-grid-3x2-gap me-2" style="color:#7c3aed;"></i>Quick Access</div>
            </div>
            <div class="dash-panel-body">
                <div class="shortcut-grid" style="grid-template-columns:repeat(4,1fr);">
                    <a href="{{ route('admin.motorcycles.create') }}" class="shortcut-btn">
                        <div class="shortcut-ic"><i class="bi bi-bicycle"></i></div>
                        <div><div class="shortcut-lbl">Add Motorcycle</div><div class="shortcut-sub">New product listing</div></div>
                    </a>
                    <a href="{{ route('admin.spare-parts.create') }}" class="shortcut-btn">
                        <div class="shortcut-ic" style="background:#7c3aed;"><i class="bi bi-gear"></i></div>
                        <div><div class="shortcut-lbl">Add Spare Part</div><div class="shortcut-sub">New inventory item</div></div>
                    </a>
                    <a href="{{ route('admin.sales.index') }}" class="shortcut-btn">
                        <div class="shortcut-ic" style="background:#d97706;"><i class="bi bi-receipt"></i></div>
                        <div><div class="shortcut-lbl">Manage Orders</div><div class="shortcut-sub">Sales & fulfilment</div></div>
                    </a>
                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.users.create') }}" class="shortcut-btn">
                        <div class="shortcut-ic" style="background:#16a34a;"><i class="bi bi-person-plus-fill"></i></div>
                        <div><div class="shortcut-lbl">Add User</div><div class="shortcut-sub">Create new account</div></div>
                    </a>
                    @else
                    <a href="{{ route('admin.enquiries.index') }}" class="shortcut-btn">
                        <div class="shortcut-ic" style="background:#16a34a;"><i class="bi bi-envelope-fill"></i></div>
                        <div><div class="shortcut-lbl">View Enquiries</div><div class="shortcut-sub">Customer messages</div></div>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
const sparkOpts = {
    responsive: true, maintainAspectRatio: false,
    plugins: { legend: { display: false }, tooltip: { enabled: false } },
    scales: { x: { display: false }, y: { display: false } },
    elements: { point: { radius: 0 }, line: { borderWidth: 2, tension: 0.4 } }
};

const sparkColors = ['#2563eb','#d97706','#7c3aed','#16a34a'];
['mini1','mini2','mini3','mini4'].forEach((id, i) => {
    const el = document.getElementById(id);
    if (!el) return;
    new Chart(el, {
        type: 'line',
        data: {
            labels: [1,2,3,4,5,6,7],
            datasets: [{
                data: [2,4,3,6,5,8,10+i*2],
                borderColor: sparkColors[i],
                backgroundColor: sparkColors[i].replace('rgb','rgba').replace(')',',0.08)'),
                fill: true
            }]
        },
        options: sparkOpts
    });
});

// Orders Overview (last 6 months)
const salesEl = document.getElementById('salesChart');
if (salesEl) {
    const months = [];
    for (let i = 5; i >= 0; i--) {
        const d = new Date();
        d.setMonth(d.getMonth() - i);
        months.push(d.toLocaleString('default', { month: 'short' }));
    }
    new Chart(salesEl, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Orders',
                data: [4,7,5,9,12,8],
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37,99,235,0.1)',
                fill: true, tension: 0.4
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f0f4f9' }, ticks: { font: { size: 10 } } },
                x: { grid: { display: false }, ticks: { font: { size: 10 } } }
            }
        }
    });
}

// Category doughnut
const catEl = document.getElementById('categoryChart');
if (catEl) {
    new Chart(catEl, {
        type: 'doughnut',
        data: {
            labels: ['Sport','Street','Adventure','Scooter','Other'],
            datasets: [{
                data: [35,28,20,10,7],
                backgroundColor: ['#2563eb','#7c3aed','#16a34a','#d97706','#94a3b8'],
                borderWidth: 0, cutout: '68%'
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { font: { size: 10 }, padding: 10 } } }
        }
    });
}
</script>
@endsection
