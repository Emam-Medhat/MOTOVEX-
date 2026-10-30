@extends('layouts.admin')
@section('title', 'Sales Orders')

@section('styles')
<style>
.kpi-row { display:grid; grid-template-columns:repeat(5,minmax(0,1fr)); gap:12px; margin-bottom:20px; }
@media(max-width:1100px){ .kpi-row{ grid-template-columns:repeat(3,1fr); } }
@media(max-width:640px){  .kpi-row{ grid-template-columns:repeat(2,1fr); } }

.kpi-card {
    background:var(--surface); border:1px solid var(--line); border-radius:14px;
    padding:16px; display:flex; flex-direction:column; gap:10px; position:relative; overflow:hidden;
    transition:transform .2s, box-shadow .2s;
}
.kpi-card:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(124,58,237,.1); }
.kpi-card::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; border-radius:14px 14px 0 0; }
.kc-purple::before { background:linear-gradient(90deg,#7c3aed,#a78bfa); }
.kc-amber::before  { background:linear-gradient(90deg,#d97706,#fbbf24); }
.kc-blue::before   { background:linear-gradient(90deg,#2563eb,#60a5fa); }
.kc-green::before  { background:linear-gradient(90deg,#16a34a,#4ade80); }
.kc-red::before    { background:linear-gradient(90deg,#dc2626,#f87171); }

.kpi-top { display:flex; align-items:flex-start; justify-content:space-between; }
.kpi-icon { width:38px; height:38px; border-radius:10px; display:grid; place-items:center; font-size:14px; flex-shrink:0; }
.ki-purple { background:#f3e8ff; color:#7c3aed; }
.ki-amber  { background:#fffbeb; color:#d97706; }
.ki-blue   { background:#eff6ff; color:#2563eb; }
.ki-green  { background:#f0fdf4; color:#16a34a; }
.ki-red    { background:#fff1f2; color:#dc2626; }

.kpi-val { font-size:28px; font-weight:900; letter-spacing:-1.2px; color:var(--text); line-height:1; }
.kpi-lbl { font-size:11px; font-weight:600; color:var(--muted); }

.filter-bar { display:flex; flex-wrap:wrap; align-items:center; gap:9px; margin-bottom:18px; padding:12px 14px; border:1px solid var(--line); border-radius:13px; background:var(--surface); }
.filter-bar input, .filter-bar select { height:38px; padding:0 13px; background:var(--surface); border:1px solid var(--line); border-radius:9px; color:var(--text); font-size:12px; font-family:inherit; outline:none; transition:border-color .15s; }
.filter-bar input { min-width:220px; padding-left:34px; }
.filter-bar select { min-width:130px; cursor:pointer; }
.filter-bar input:focus, .filter-bar select:focus { border-color:var(--purple-light); box-shadow:0 0 0 3px rgba(139,92,246,.1); }
.search-wrap { position:relative; }
.search-wrap i { position:absolute; left:11px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:12px; pointer-events:none; }

.ref-link { font-family:ui-monospace,monospace; font-size:11px; font-weight:700; color:var(--purple); text-decoration:none; padding:3px 8px; border-radius:7px; background:var(--purple-bg); border:1px solid var(--purple-border); transition:all .15s; display:inline-block; }
.ref-link:hover { background:var(--purple); color:#fff; }

.moto-item { display:flex; align-items:center; gap:8px; padding:4px 0; }
.moto-thumb { width:32px; height:32px; border-radius:6px; overflow:hidden; border:1px solid var(--line); flex-shrink:0; }
.moto-thumb img { width:100%; height:100%; object-fit:cover; }

.status-pill { font-size:10px; font-weight:700; padding:2px 8px; border-radius:999px; }
.sp-pending   { background:#fff7ed; color:#c2410c; border:1px solid #ffedd5; }
.sp-confirmed { background:#eff6ff; color:#1d4ed8; border:1px solid #dbeafe; }
.sp-paid      { background:#f0fdf4; color:#15803d; border:1px solid #dcfce7; }
.sp-completed { background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; }
.sp-cancelled { background:#fef2f2; color:#b91c1c; border:1px solid #fee2e2; }

.act-btn { display:inline-flex; align-items:center; gap:5px; height:32px; padding:0 12px; border-radius:9px; background:#eef2ff; color:#4338ca; border:1.5px solid #c7d2fe; font-size:11px; font-weight:700; text-decoration:none; transition:all .2s; }
.act-btn:hover { background:#6366f1; color:#fff; border-color:#6366f1; }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1>Sales Orders</h1>
        <p>{{ auth()->user()->isAdmin() ? 'Global sales overview' : 'Your product sales' }}</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl col-md-4 col-6">
        <div class="stat-card" style="height: 140px;">
            <div class="stat-head">
                <div class="stat-icon" style="background:#f3e8ff; color:#7c3aed;"><i class="bi bi-cart3"></i></div>
                Total Sales
            </div>
            <h3>{{ $stats['total'] }}</h3>
        </div>
    </div>
    <div class="col-xl col-md-4 col-6">
        <div class="stat-card" style="height: 140px;">
            <div class="stat-head">
                <div class="stat-icon" style="background:#fffbeb; color:#d97706;"><i class="bi bi-bell"></i></div>
                New / Pending
            </div>
            <h3>{{ $stats['pending'] }}</h3>
        </div>
    </div>
    <div class="col-xl col-md-4 col-6">
        <div class="stat-card" style="height: 140px;">
            <div class="stat-head">
                <div class="stat-icon" style="background:#eff6ff; color:#2563eb;"><i class="bi bi-check2-circle"></i></div>
                Confirmed
            </div>
            <h3>{{ $stats['confirmed'] }}</h3>
        </div>
    </div>
    <div class="col-xl col-md-4 col-6">
        <div class="stat-card" style="height: 140px;">
            <div class="stat-head">
                <div class="stat-icon" style="background:#f0fdf4; color:#16a34a;"><i class="bi bi-cash-stack"></i></div>
                Paid Orders
            </div>
            <h3>{{ $stats['paid'] }}</h3>
        </div>
    </div>
    <div class="col-xl col-md-4 col-6">
        <div class="stat-card" style="height: 140px;">
            <div class="stat-head">
                <div class="stat-icon" style="background:#f0fdf4; color:#16a34a;"><i class="bi bi-box-seam"></i></div>
                Completed
            </div>
            <h3>{{ $stats['completed'] }}</h3>
        </div>
    </div>
</div>

<form method="GET" class="filter-card border-0 shadow-sm">
    <div class="position-relative">
        <i class="bi bi-search position-absolute text-muted" style="left: 12px; top: 9px;"></i>
        <input type="text" name="search" value="{{ request('search') }}" class="filter-input ps-5" placeholder="Search reference, customer, email...">
    </div>
    <select name="status" class="filter-select">
        <option value="">All Statuses</option>
        <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
        <option value="confirmed" {{ request('status')=='confirmed'?'selected':'' }}>Confirmed</option>
        <option value="processing" {{ request('status')=='processing'?'selected':'' }}>Processing</option>
        <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Completed</option>
        <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Cancelled</option>
    </select>
    <select name="payment_status" class="filter-select">
        <option value="">All Payments</option>
        <option value="pending" {{ request('payment_status')=='pending'?'selected':'' }}>Pending</option>
        <option value="paid" {{ request('payment_status')=='paid'?'selected':'' }}>Paid</option>
        <option value="failed" {{ request('payment_status')=='failed'?'selected':'' }}>Failed</option>
    </select>
    <button type="submit" class="btn btn-primary-alt" style="height:38px;">Filter</button>
</form>

<div class="panel border-0 shadow-sm">
    @if($orders->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-cart-x text-muted display-1 opacity-25"></i>
            <p class="mt-3 text-muted">No sales orders found yet.</p>
        </div>
    @else
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Products</th>
                    <th>Customer</th>
                    <th>Subtotal</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>
                        <a href="{{ route('admin.sales.show', $order) }}" class="fw-bold text-primary text-decoration-none" style="font-family: monospace;">{{ $order->order_reference }}</a>
                    </td>
                    <td>
                        @foreach($order->items as $item)
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <div style="width: 32px; height: 32px; border-radius: 6px; overflow: hidden; background: #f8fafc; display: grid; place-items: center;">
                                    @if($item->motorcycle?->image_url)
                                        <img src="{{ $item->motorcycle->image_url }}" alt="" style="width: 100%; height: 100%; object-fit: contain;">
                                    @else
                                        <i class="bi bi-bicycle text-muted" style="font-size: 12px;"></i>
                                    @endif
                                </div>
                                <span class="small fw-semibold text-truncate" style="max-width: 150px;">{{ $item->motorcycle_snapshot }}</span>
                            </div>
                        @endforeach
                    </td>
                    <td>
                        <div class="fw-bold">{{ $order->name }}</div>
                        <small class="text-muted d-block">{{ $order->email }}</small>
                    </td>
                    <td class="fw-bold text-dark">
                        {{ $order->currency }} {{ number_format($order->items->sum('subtotal'), 2) }}
                    </td>
                    <td>
                        <span class="badge-alt {{ $order->payment_status === 'paid' ? 'badge-success' : ($order->payment_status === 'failed' ? 'badge-danger' : 'badge-warning') }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-alt {{ $order->status === 'completed' ? 'badge-success' : ($order->status === 'cancelled' ? 'badge-danger' : 'badge-info') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="small">{{ $order->created_at->format('d M Y') }}</div>
                        <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.sales.show', $order) }}" class="action-btn btn-view" title="Details">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
