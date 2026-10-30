@extends('layouts.admin')
@section('title', 'Purchase Orders')

@section('styles')
<style>
.kpi-row { display:grid; grid-template-columns:repeat(6,minmax(0,1fr)); gap:12px; margin-bottom:20px; }
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
.kc-teal::before   { background:linear-gradient(90deg,#0891b2,#67e8f9); }

.kpi-top { display:flex; align-items:flex-start; justify-content:space-between; }
.kpi-icon { width:38px; height:38px; border-radius:10px; display:grid; place-items:center; font-size:14px; flex-shrink:0; }
.ki-purple { background:#f3e8ff; color:#7c3aed; }
.ki-amber  { background:#fffbeb; color:#d97706; }
.ki-blue   { background:#eff6ff; color:#2563eb; }
.ki-green  { background:#f0fdf4; color:#16a34a; }
.ki-red    { background:#fff1f2; color:#dc2626; }
.ki-teal   { background:#ecfeff; color:#0891b2; }
.kpi-badge { font-size:9px; font-weight:700; padding:2px 8px; border-radius:999px; white-space:nowrap; }
.kb-p { color:#7c3aed; background:#f3e8ff; border:1px solid #e9d5ff; }
.kb-a { color:#92400e; background:#fffbeb; border:1px solid #fde68a; }
.kb-b { color:#1d4ed8; background:#eff6ff; border:1px solid #bfdbfe; }
.kb-g { color:#15803d; background:#f0fdf4; border:1px solid #bbf7d0; }
.kb-r { color:#991b1b; background:#fff1f2; border:1px solid #fecaca; }
.kb-t { color:#164e63; background:#ecfeff; border:1px solid #a5f3fc; }
.kpi-val { font-size:28px; font-weight:900; letter-spacing:-1.2px; color:var(--text); line-height:1; }
.kpi-lbl { font-size:11px; font-weight:600; color:var(--muted); }

.filter-bar { display:flex; flex-wrap:wrap; align-items:center; gap:9px; margin-bottom:18px; padding:12px 14px; border:1px solid var(--line); border-radius:13px; background:var(--surface); }
.filter-bar input, .filter-bar select { height:38px; padding:0 13px; background:var(--surface); border:1px solid var(--line); border-radius:9px; color:var(--text); font-size:12px; font-family:inherit; outline:none; transition:border-color .15s; }
.filter-bar input { min-width:220px; padding-left:34px; }
.filter-bar select { min-width:130px; cursor:pointer; }
.filter-bar input:focus, .filter-bar select:focus { border-color:var(--purple-light); box-shadow:0 0 0 3px rgba(139,92,246,.1); }
.search-wrap { position:relative; }
.search-wrap i { position:absolute; left:11px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:12px; pointer-events:none; }
.filter-clear { font-size:11px; font-weight:600; color:var(--muted); border:1px solid var(--line); background:none; border-radius:9px; padding:0 12px; height:38px; cursor:pointer; transition:all .15s; }
.filter-clear:hover { border-color:#fecaca; color:#dc2626; background:#fff1f2; }

.ref-link { font-family:ui-monospace,monospace; font-size:11px; font-weight:700; color:var(--purple); text-decoration:none; padding:3px 8px; border-radius:7px; background:var(--purple-bg); border:1px solid var(--purple-border); transition:all .15s; display:inline-block; }
.ref-link:hover { background:var(--purple); color:#fff; }

.moto-cell { display:flex; align-items:center; gap:9px; }
.moto-thumb { width:38px; height:38px; flex-shrink:0; border-radius:9px; overflow:hidden; border:1px solid var(--purple-border); background:var(--purple-bg); display:grid; place-items:center; }
.moto-thumb img { width:100%; height:100%; object-fit:cover; }

.act-wrap { display:flex; align-items:center; justify-content:flex-end; gap:6px; }
.act-btn { display:inline-flex; align-items:center; justify-content:center; gap:5px; height:32px; padding:0 12px; border-radius:9px; border:1.5px solid transparent; font-size:11px; font-weight:700; font-family:inherit; cursor:pointer; text-decoration:none; white-space:nowrap; transition:all .2s; }
.act-btn i { font-size:10px; }
.act-view { background:#eef2ff; color:#4338ca; border-color:#c7d2fe; }
.act-view:hover { background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; border-color:#6366f1; box-shadow:0 4px 14px -6px rgba(99,102,241,.55); transform:translateY(-1px); }
.act-del { background:#fff1f2; color:#dc2626; border-color:#fecaca; }
.act-del:hover { background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff; border-color:#ef4444; box-shadow:0 4px 14px -6px rgba(239,68,68,.5); transform:translateY(-1px); }

.pay-pill { display:inline-flex; align-items:center; gap:4px; padding:3px 9px; border-radius:999px; font-size:10px; font-weight:700; }
.pay-pending  { color:#92400e; background:#fffbeb; border:1px solid #fde68a; }
.pay-verified { color:#15803d; background:#f0fdf4; border:1px solid #bbf7d0; }
.pay-failed   { color:#991b1b; background:#fff1f2; border:1px solid #fecaca; }
.pay-refunded { color:#64748b; background:#f8fafc; border:1px solid #e2e8f0; }
</style>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1>Purchase Orders</h1>
        <p>{{ $orders->total() }} order{{ $orders->total() !== 1 ? 's' : '' }} total</p>
    </div>
</div>

{{-- KPI Cards --}}
<div class="kpi-row">
    <div class="kpi-card kc-purple">
        <div class="kpi-top"><div class="kpi-icon ki-purple"><i class="fa-solid fa-receipt"></i></div><span class="kpi-badge kb-p">All</span></div>
        <div class="kpi-val">{{ $stats['total'] }}</div>
        <div class="kpi-lbl">Total Orders</div>
    </div>
    <div class="kpi-card kc-amber">
        <div class="kpi-top"><div class="kpi-icon ki-amber"><i class="fa-regular fa-clock"></i></div><span class="kpi-badge kb-a">Needs Action</span></div>
        <div class="kpi-val">{{ $stats['pending'] }}</div>
        <div class="kpi-lbl">Pending Review</div>
    </div>
    <div class="kpi-card kc-blue">
        <div class="kpi-top"><div class="kpi-icon ki-blue"><i class="fa-solid fa-phone"></i></div><span class="kpi-badge kb-b">In Progress</span></div>
        <div class="kpi-val">{{ $stats['confirmed'] }}</div>
        <div class="kpi-lbl">Contacted</div>
    </div>
    <div class="kpi-card kc-green">
        <div class="kpi-top"><div class="kpi-icon ki-green"><i class="fa-solid fa-circle-check"></i></div><span class="kpi-badge kb-g">Done</span></div>
        <div class="kpi-val">{{ $stats['completed'] }}</div>
        <div class="kpi-lbl">Completed</div>
    </div>
    <div class="kpi-card kc-amber">
        <div class="kpi-top"><div class="kpi-icon ki-amber"><i class="fa-solid fa-hourglass-half"></i></div><span class="kpi-badge kb-a">Unverified</span></div>
        <div class="kpi-val">{{ $stats['pay_pending'] }}</div>
        <div class="kpi-lbl">Payment Pending</div>
    </div>
    <div class="kpi-card kc-teal">
        <div class="kpi-top"><div class="kpi-icon ki-teal"><i class="fa-solid fa-shield-halved"></i></div><span class="kpi-badge kb-t">Verified</span></div>
        <div class="kpi-val">{{ $stats['pay_verified'] }}</div>
        <div class="kpi-lbl">Payment Verified</div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" class="filter-bar">
    <div class="search-wrap">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, reference, motorcycle…">
    </div>
    <select name="status">
        <option value="">All Statuses</option>
        @foreach(\App\Models\Enquiry::$statuses as $v => $l)
            <option value="{{ $v }}" {{ request('status')===$v?'selected':'' }}>{{ $l }}</option>
        @endforeach
    </select>
    <select name="payment">
        <option value="">All Payments</option>
        @foreach(\App\Models\Enquiry::$paymentStatuses as $v => $l)
            <option value="{{ $v }}" {{ request('payment')===$v?'selected':'' }}>{{ $l }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn-primary" style="height:38px;padding:0 16px;font-size:12px;"><i class="fa-solid fa-filter"></i> Filter</button>
    @if(request()->hasAny(['search','status','payment']))
        <a href="{{ route('admin.orders.index') }}" class="filter-clear"><i class="fa-solid fa-xmark" style="font-size:10px;margin-right:3px;"></i>Clear</a>
    @endif
</form>

{{-- Table --}}
<div class="admin-table-wrap">
    @if($orders->isEmpty())
        <div class="empty-state">
            <i class="fa-solid fa-receipt"></i>
            <p>No purchase orders found. Adjust your filters or wait for customers to submit orders.</p>
        </div>
    @else
    <table class="admin-table">
        <thead>
            <tr>
                <th>Reference</th>
                <th>Motorcycle</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Date</th>
                <th style="text-align:right;padding-right:18px;">Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>
                    <a href="{{ route('admin.orders.show', $order) }}" class="ref-link">
                        {{ $order->order_reference ?? '#' . $order->id }}
                    </a>
                </td>
                <td>
                    <div class="moto-cell">
                        <div class="moto-thumb">
                            @if($order->motorcycle?->image_url)
                                <img src="{{ $order->motorcycle->image_url }}" alt="">
                            @else
                                <i class="fa-solid fa-motorcycle" style="color:var(--purple);font-size:13px;"></i>
                            @endif
                        </div>
                        <div>
                            <div style="font-size:12px;font-weight:600;color:var(--text);">{{ $order->motorcycle_snapshot ?? $order->motorcycle?->model_name ?? '—' }}</div>
                            <div style="font-size:10px;color:var(--muted);">{{ $order->motorcycle?->brand?->name }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div style="font-size:12px;font-weight:700;color:var(--text);">{{ $order->name }}</div>
                    <div style="font-size:10.5px;color:var(--purple);"><a href="mailto:{{ $order->email }}" style="color:var(--purple);text-decoration:none;">{{ $order->email }}</a></div>
                    <div style="font-size:10.5px;color:var(--muted);">{{ $order->phone }}</div>
                </td>
                <td>
                    @if($order->total_amount)
                        <div style="font-size:13px;font-weight:800;color:var(--text);">{{ $order->currency ?? 'USD' }} {{ number_format($order->total_amount) }}</div>
                    @else
                        <span style="font-size:11px;color:var(--muted);">—</span>
                    @endif
                </td>
                <td>
                    <span class="pay-pill pay-{{ $order->payment_status ?? 'pending' }}">
                        {{ \App\Models\Enquiry::$paymentStatuses[$order->payment_status] ?? ucfirst($order->payment_status ?? 'pending') }}
                    </span>
                </td>
                <td>
                    <span class="badge {{ match($order->status) { 'pending'=>'badge-yellow','contacted'=>'badge-blue','completed'=>'badge-green','cancelled'=>'badge-red', default=>'badge-muted' } }}">
                        {{ \App\Models\Enquiry::$statuses[$order->status] ?? ucfirst($order->status) }}
                    </span>
                </td>
                <td>
                    <span style="font-size:11px;color:var(--muted);" title="{{ $order->created_at->format('d M Y H:i') }}">{{ $order->created_at->diffForHumans() }}</span>
                </td>
                <td>
                    <div class="act-wrap">
                        <a href="{{ route('admin.orders.show', $order) }}" class="act-btn act-view">
                            <i class="fa-solid fa-eye"></i> View
                        </a>
                        @if(auth()->user()->isAdmin())
                        <form method="POST" action="{{ route('admin.orders.destroy', $order) }}"
                              onsubmit="return confirm('Delete order {{ $order->order_reference }}? This cannot be undone.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="act-btn act-del"><i class="fa-solid fa-trash-can"></i> Delete</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @if($orders->hasPages())
        <div class="admin-pagination">{{ $orders->links() }}</div>
    @endif
    @endif
</div>

@endsection
