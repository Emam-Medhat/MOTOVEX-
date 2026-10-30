@extends('layouts.admin')
@section('title', 'Order ' . $order->order_reference)

@section('styles')
<style>
/* ── Grid layout ── */
.order-grid {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 22px;
    align-items: start;
}
@media(max-width:1100px){ .order-grid{ grid-template-columns:1fr; } }

/* ── Hero header ── */
.order-hero {
    background: linear-gradient(135deg, #4c1d95 0%, #6d28d9 50%, #7c3aed 100%);
    border-radius: 16px;
    padding: 28px 32px;
    margin-bottom: 22px;
    position: relative;
    overflow: hidden;
}
.order-hero::before {
    content: '';
    position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    pointer-events: none;
}
.order-hero-content { position: relative; z-index: 1; display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap; }
.order-ref { font-size: 26px; font-weight: 900; color: #fff; letter-spacing: -.5px; }
.order-meta { font-size: 13px; color: rgba(255,255,255,.65); margin-top: 5px; display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
.order-meta span { display:flex; align-items:center; gap:5px; }
.hero-status { display:inline-flex; align-items:center; gap:6px; padding:6px 14px; border-radius:20px; font-size:12px; font-weight:700; }
.hs-pending   { background:rgba(251,191,36,.2); color:#fde68a; border:1px solid rgba(251,191,36,.3); }
.hs-confirmed { background:rgba(96,165,250,.2); color:#bfdbfe; border:1px solid rgba(96,165,250,.3); }
.hs-completed { background:rgba(74,222,128,.2); color:#bbf7d0; border:1px solid rgba(74,222,128,.3); }
.hs-cancelled { background:rgba(248,113,113,.2); color:#fecaca; border:1px solid rgba(248,113,113,.3); }

/* ── Section cards ── */
.os-card {
    background: var(--surface);
    border: 1px solid var(--line);
    border-radius: 14px;
    overflow: hidden;
}
.os-card + .os-card { margin-top: 18px; }
.os-card-head {
    padding: 16px 22px;
    border-bottom: 1px solid var(--line);
    display: flex; align-items: center; justify-content: space-between;
    background: #fafbfd;
}
.os-card-title {
    font-size: 13px; font-weight: 700; color: var(--text);
    display: flex; align-items: center; gap: 9px;
}
.os-card-title i { width: 28px; height: 28px; border-radius: 8px; display:grid; place-items:center; font-size:12px; background: var(--purple-bg); color: var(--purple); }
.os-card-body { padding: 22px; }

/* ── Item rows ── */
.item-card {
    display: flex; align-items: center; gap: 16px;
    padding: 16px;
    border: 1px solid var(--line);
    border-radius: 12px;
    transition: border-color .15s, box-shadow .15s;
}
.item-card:hover { border-color: var(--purple-border); box-shadow: 0 2px 12px rgba(124,58,237,.06); }
.item-card + .item-card { margin-top: 10px; }
.item-thumb {
    width: 88px; height: 66px; flex-shrink: 0;
    border-radius: 10px; overflow: hidden;
    border: 1px solid var(--line);
    background: #f8fafc;
    display: grid; place-items: center;
}
.item-thumb img { width:100%; height:100%; object-fit:cover; }
.item-thumb-ico { font-size:20px; color:#cbd5e1; }
.item-name { font-size: 14px; font-weight: 700; color: var(--text); }
.item-price-line { font-size: 12px; color: var(--muted); margin-top: 4px; }
.item-price-line strong { color: var(--purple); font-weight: 700; }
.item-status-sel {
    font-size: 11px; height: 30px; padding: 0 8px;
    border-radius: 7px; border: 1px solid var(--line);
    background: var(--surface); color: var(--text);
    cursor: pointer; outline: none;
    transition: border-color .15s;
}
.item-status-sel:focus { border-color: var(--purple-light); box-shadow: 0 0 0 2px rgba(139,92,246,.12); }

/* ── Total row ── */
.order-total-bar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 22px;
    border-top: 1px solid var(--line);
    background: linear-gradient(90deg, #faf5ff, #f5f3ff);
}
.total-lbl { font-size: 13px; font-weight: 700; color: var(--subtext); }
.total-val { font-size: 22px; font-weight: 900; letter-spacing: -.5px; color: var(--purple); }

/* ── Info rows ── */
.ir { display:flex; align-items:flex-start; justify-content:space-between; gap:16px; padding:10px 0; border-bottom:1px solid #f1f5f9; }
.ir:last-child { border-bottom: none; padding-bottom: 0; }
.ir-lbl { font-size:12px; font-weight:500; color:var(--muted); flex-shrink:0; }
.ir-val { font-size:12px; font-weight:600; color:var(--text); text-align:right; }
.ir-val a { color: var(--purple); }
.ir-val a:hover { text-decoration: underline; }

/* ── Customer avatar ── */
.cust-avatar {
    width: 44px; height: 44px; border-radius: 12px; flex-shrink:0;
    background: linear-gradient(135deg, var(--purple), #818cf8);
    display: grid; place-items: center;
    font-weight: 800; font-size: 16px; color: #fff;
    box-shadow: 0 4px 12px rgba(124,58,237,.22);
}

/* ── Payment status badges ── */
.pay-badge { display:inline-flex; align-items:center; gap:6px; padding:5px 11px; border-radius:20px; font-size:12px; font-weight:700; }
.pay-pending  { background:#fffbeb; color:#b45309; border:1px solid #fde68a; }
.pay-paid     { background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; }
.pay-failed   { background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; }
.pay-refunded { background:#f5f3ff; color:var(--purple); border:1px solid var(--purple-border); }

/* ── Timeline ── */
.timeline { display:flex; flex-direction:column; gap:0; }
.tl-item { display:flex; gap:12px; padding-bottom:16px; position:relative; }
.tl-item:last-child { padding-bottom:0; }
.tl-item:not(:last-child) .tl-line { position:absolute; left:11px; top:24px; bottom:0; width:1px; background:var(--line); z-index:0; }
.tl-dot { width:22px; height:22px; border-radius:50%; flex-shrink:0; display:grid; place-items:center; font-size:9px; position:relative; z-index:1; }
.tl-dot-purple { background:var(--purple-bg); border:2px solid var(--purple); color:var(--purple); }
.tl-dot-muted  { background:#f8fafc; border:2px solid var(--line); color:#94a3b8; }
.tl-body { padding-top: 1px; }
.tl-title { font-size:12px; font-weight:700; color:var(--text); }
.tl-time  { font-size:11px; color:var(--muted); margin-top:2px; }

/* ── Stat mini ── */
.mini-stat { display:flex; flex-direction:column; gap:3px; padding:14px 16px; border-radius:10px; background:#f8fafc; border:1px solid var(--line); }
.ms-label  { font-size:10px; font-weight:600; text-transform:uppercase; letter-spacing:.6px; color:var(--muted); }
.ms-value  { font-size:16px; font-weight:800; color:var(--text); }
</style>
@endsection

@section('content')

{{-- Flash --}}
@if(session('success'))
<div class="flash-success" style="margin-bottom:18px"><i class="fa-solid fa-circle-check"></i>{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="flash-error" style="margin-bottom:18px"><i class="fa-solid fa-circle-exclamation"></i>{{ session('error') }}</div>
@endif

{{-- ── Hero header ── --}}
<div class="order-hero">
    <div class="order-hero-content">
        <div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                <a href="{{ route('admin.sales.index') }}" style="width:32px;height:32px;border-radius:9px;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);display:grid;place-items:center;color:#fff;font-size:12px;transition:background .15s;" onmouseover="this.style.background='rgba(255,255,255,.25)'" onmouseout="this.style.background='rgba(255,255,255,.15)'">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <span style="font-size:12px;color:rgba(255,255,255,.5);font-weight:600;">Sales Orders</span>
            </div>
            <div class="order-ref">{{ $order->order_reference }}</div>
            <div class="order-meta">
                <span><i class="fa-solid fa-calendar-days"></i> Placed on {{ $order->created_at->format('d M Y, H:i') }}</span>
                <span><i class="fa-solid fa-user"></i> {{ $order->name }}</span>
                <span><i class="fa-solid fa-box"></i> {{ $order->items->count() }} item{{ $order->items->count() !== 1 ? 's' : '' }}</span>
            </div>
        </div>
        <span class="hero-status hs-{{ $order->status }}">
            <span style="width:6px;height:6px;border-radius:50%;background:currentColor;opacity:.8;"></span>
            {{ ucfirst($order->status) }}
        </span>
    </div>
</div>

{{-- ── Mini stats ── --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:22px;">
    <div class="mini-stat">
        <span class="ms-label">Order Total</span>
        <span class="ms-value" style="color:var(--purple)">{{ $order->currency }} {{ number_format($order->total_amount, 2) }}</span>
    </div>
    <div class="mini-stat">
        <span class="ms-label">Payment</span>
        <span class="ms-value" style="font-size:13px;">
            <span class="pay-badge pay-{{ $order->payment_status }}">{{ ucfirst($order->payment_status) }}</span>
        </span>
    </div>
    <div class="mini-stat">
        <span class="ms-label">Items</span>
        <span class="ms-value">{{ $order->items->count() }}</span>
    </div>
</div>

{{-- ── Main grid ── --}}
<div class="order-grid">

    {{-- ═══ LEFT COLUMN ═══ --}}
    <div>

        {{-- Order Items --}}
        <div class="os-card">
            <div class="os-card-head">
                <div class="os-card-title">
                    <i class="fa-solid fa-box"></i>
                    Order Items
                </div>
                <span style="font-size:11px;color:var(--muted);font-weight:500;">{{ $order->items->count() }} item{{ $order->items->count() !== 1 ? 's' : '' }}</span>
            </div>
            <div class="os-card-body">

                @forelse($order->items as $item)
                @php
                    $itemStatusLabels = ['pending' => 'Pending'] + \App\Models\OrderItem::$merchantStatuses;
                @endphp
                <div class="item-card">
                    <div class="item-thumb">
                        @if($item->productImageUrl())
                            <img src="{{ $item->productImageUrl() }}" alt="{{ $item->displayName() }}">
                        @else
                            <i class="fa-solid {{ $item->isSparePartItem() ? 'fa-gears' : 'fa-motorcycle' }} item-thumb-ico"></i>
                        @endif
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div class="item-name">{{ $item->displayName() }}</div>
                        <div style="display:flex;flex-wrap:wrap;gap:6px;margin-top:4px;">
                            <span style="font-size:10px;font-weight:700;letter-spacing:.4px;text-transform:uppercase;padding:2px 8px;border-radius:6px;background:{{ $item->isSparePartItem() ? '#eef2ff' : '#f3e8ff' }};color:{{ $item->isSparePartItem() ? '#4f46e5' : '#7c3aed' }};">
                                {{ $item->isSparePartItem() ? 'Spare Part' : 'Motorcycle' }}
                            </span>
                            @if($item->sku_snapshot)
                            <span style="font-size:10px;font-family:ui-monospace,monospace;color:var(--muted);padding:2px 8px;border-radius:6px;background:#f8fafc;border:1px solid var(--line);">SKU {{ $item->sku_snapshot }}</span>
                            @endif
                            @if($item->merchant)
                            <span style="font-size:10px;color:var(--subtext);padding:2px 8px;border-radius:6px;background:#f8fafc;border:1px solid var(--line);">
                                <i class="fa-solid fa-store" style="font-size:8px;"></i> {{ $item->merchant->name }}
                            </span>
                            @endif
                        </div>
                        <div class="item-price-line" style="margin-top:6px;">
                            {{ $order->currency }} {{ number_format($item->unit_price, 2) }} × {{ $item->quantity }}
                            &nbsp;=&nbsp;
                            <strong>{{ $order->currency }} {{ number_format($item->subtotal, 2) }}</strong>
                        </div>
                        @if($item->isSparePartItem() && $item->sparePart)
                        <a href="{{ route('admin.spare-parts.show', $item->sparePart) }}" style="font-size:11px;color:var(--purple);margin-top:4px;display:inline-flex;align-items:center;gap:4px;">
                            <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:9px;"></i> View part
                        </a>
                        @elseif($item->motorcycle)
                        <a href="{{ route('admin.motorcycles.show', $item->motorcycle) }}" style="font-size:11px;color:var(--purple);margin-top:4px;display:inline-flex;align-items:center;gap:4px;">
                            <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:9px;"></i> View motorcycle
                        </a>
                        @endif
                    </div>
                    <div style="flex-shrink:0;">
                        <form action="{{ route('admin.sales.items.update-status', $item) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="this.form.submit()" class="item-status-sel">
                                @foreach($itemStatusLabels as $val => $lbl)
                                <option value="{{ $val }}" {{ $item->status == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
                @empty
                <div style="text-align:center;padding:40px;color:var(--muted);">
                    <i class="fa-solid fa-box-open" style="font-size:32px;margin-bottom:10px;opacity:.4;display:block;"></i>
                    No items in this order.
                </div>
                @endforelse

            </div>
            <div class="order-total-bar">
                <span class="total-lbl">Order Total</span>
                <span class="total-val">{{ $order->currency }} {{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        {{-- Additional Info --}}
        <div class="os-card">
            <div class="os-card-head">
                <div class="os-card-title">
                    <i class="fa-solid fa-circle-info"></i>
                    Additional Information
                </div>
            </div>
            <div class="os-card-body" style="padding-top:8px;padding-bottom:8px;">
                <div class="ir">
                    <span class="ir-lbl">Payment Method</span>
                    <span class="ir-val">{{ str_replace('_', ' ', ucfirst($order->payment_method ?? '—')) }}</span>
                </div>
                <div class="ir">
                    <span class="ir-lbl">Delivery Method</span>
                    <span class="ir-val">{{ str_replace('_', ' ', ucfirst($order->delivery_method ?? '—')) }}</span>
                </div>
                @if($order->city || $order->governorate)
                <div class="ir">
                    <span class="ir-lbl">Location</span>
                    <span class="ir-val">{{ trim(($order->city ?? '') . ', ' . ($order->governorate ?? ''), ', ') }}</span>
                </div>
                @endif
                @if($order->delivery_address)
                <div class="ir" style="flex-direction:column;gap:4px;">
                    <span class="ir-lbl">Delivery Address</span>
                    <span class="ir-val" style="text-align:left;color:var(--subtext);">{{ $order->delivery_address }}</span>
                </div>
                @endif
                @if($order->admin_notes)
                <div class="ir" style="flex-direction:column;gap:4px;">
                    <span class="ir-lbl">Order Notes</span>
                    <span class="ir-val" style="text-align:left;color:var(--subtext);">{{ $order->admin_notes }}</span>
                </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ═══ RIGHT COLUMN ═══ --}}
    <div>

        {{-- Customer Details --}}
        <div class="os-card">
            <div class="os-card-head">
                <div class="os-card-title">
                    <i class="fa-solid fa-user"></i>
                    Customer Details
                </div>
                @if($order->user)
                <a href="{{ route('admin.users.show', $order->user) }}" class="admin-btn-sm btn-view-alt" style="font-size:10px;">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Profile
                </a>
                @endif
            </div>
            <div class="os-card-body">
                <div style="display:flex;align-items:center;gap:14px;margin-bottom:18px;padding:14px;border-radius:12px;background:#f8fafc;border:1px solid var(--line);">
                    <div class="cust-avatar">{{ strtoupper(substr($order->name, 0, 1)) }}</div>
                    <div>
                        <div style="font-size:15px;font-weight:800;color:var(--text);">{{ $order->name }}</div>
                        <div style="font-size:11px;color:var(--muted);margin-top:2px;">
                            @if($order->user)
                                <span style="display:inline-flex;align-items:center;gap:4px;background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;border-radius:20px;padding:2px 8px;font-weight:600;">
                                    <i class="fa-solid fa-circle-check" style="font-size:9px;"></i> Registered Customer
                                </span>
                            @else
                                <span style="color:var(--muted);">Guest</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="ir">
                    <span class="ir-lbl"><i class="fa-solid fa-envelope" style="margin-right:5px;color:#a78bfa;"></i>Email</span>
                    <span class="ir-val"><a href="mailto:{{ $order->email }}">{{ $order->email }}</a></span>
                </div>
                <div class="ir">
                    <span class="ir-lbl"><i class="fa-solid fa-phone" style="margin-right:5px;color:#a78bfa;"></i>Phone</span>
                    <span class="ir-val"><a href="tel:{{ $order->phone }}">{{ $order->phone }}</a></span>
                </div>
                @if($order->city || $order->governorate)
                <div class="ir">
                    <span class="ir-lbl"><i class="fa-solid fa-location-dot" style="margin-right:5px;color:#a78bfa;"></i>Location</span>
                    <span class="ir-val">{{ trim(($order->city ?? '') . ', ' . ($order->governorate ?? ''), ', ') }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Payment Status --}}
        <div class="os-card" style="margin-top:18px;">
            <div class="os-card-head">
                <div class="os-card-title">
                    <i class="fa-solid fa-credit-card"></i>
                    Payment Status
                </div>
                <span class="pay-badge pay-{{ $order->payment_status }}">{{ ucfirst($order->payment_status) }}</span>
            </div>
            <div class="os-card-body">

                @if(auth()->user()->isAdmin())
                <form action="{{ route('admin.sales.verify-payment', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div style="margin-bottom:14px;">
                        <label class="form-label">Update Payment Status</label>
                        <select name="payment_status" class="form-select">
                            @foreach(['pending'=>'Pending','paid'=>'Mark as Paid','failed'=>'Failed','refunded'=>'Refunded'] as $val=>$lbl)
                            <option value="{{ $val }}" {{ $order->payment_status == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="margin-bottom:16px;">
                        <label class="form-label">Payment Reference (optional)</label>
                        <input type="text" name="payment_reference" value="{{ $order->payment_reference }}" class="form-input" placeholder="e.g. TRX-123456">
                    </div>
                    <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">
                        <i class="fa-solid fa-check"></i> Update Payment
                    </button>
                </form>
                @else
                    @if($order->payment_reference)
                    <div class="ir">
                        <span class="ir-lbl">Reference</span>
                        <span class="ir-val">{{ $order->payment_reference }}</span>
                    </div>
                    @endif
                    @if($order->payment_verified_at)
                    <div class="ir">
                        <span class="ir-lbl">Verified at</span>
                        <span class="ir-val">{{ $order->payment_verified_at->format('d M Y, H:i') }}</span>
                    </div>
                    @endif
                    <div style="margin-top:14px;padding:12px;border-radius:10px;background:#f8fafc;border:1px solid var(--line);font-size:11px;color:var(--muted);display:flex;align-items:center;gap:8px;">
                        <i class="fa-solid fa-lock" style="color:#cbd5e1;"></i>
                        Payment verification is handled by system administrators.
                    </div>
                @endif

            </div>
        </div>

        {{-- Order Timeline --}}
        <div class="os-card" style="margin-top:18px;">
            <div class="os-card-head">
                <div class="os-card-title">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Timeline
                </div>
            </div>
            <div class="os-card-body">
                <div class="timeline">
                    <div class="tl-item">
                        <div class="tl-line"></div>
                        <div class="tl-dot tl-dot-purple"><i class="fa-solid fa-plus"></i></div>
                        <div class="tl-body">
                            <div class="tl-title">Order Created</div>
                            <div class="tl-time">{{ $order->created_at->format('d M Y, H:i') }}</div>
                        </div>
                    </div>
                    @if($order->payment_verified_at)
                    <div class="tl-item">
                        <div class="tl-line"></div>
                        <div class="tl-dot tl-dot-purple"><i class="fa-solid fa-check"></i></div>
                        <div class="tl-body">
                            <div class="tl-title">Payment Verified</div>
                            <div class="tl-time">{{ $order->payment_verified_at->format('d M Y, H:i') }}</div>
                        </div>
                    </div>
                    @endif
                    @if($order->status === 'completed')
                    <div class="tl-item">
                        <div class="tl-dot tl-dot-purple"><i class="fa-solid fa-flag-checkered"></i></div>
                        <div class="tl-body">
                            <div class="tl-title">Order Completed</div>
                            <div class="tl-time">{{ $order->updated_at->format('d M Y, H:i') }}</div>
                        </div>
                    </div>
                    @elseif($order->status === 'cancelled')
                    <div class="tl-item">
                        <div class="tl-dot" style="background:#fef2f2;border:2px solid #fecaca;color:#dc2626;"><i class="fa-solid fa-xmark"></i></div>
                        <div class="tl-body">
                            <div class="tl-title" style="color:#dc2626;">Order Cancelled</div>
                            <div class="tl-time">{{ $order->updated_at->format('d M Y, H:i') }}</div>
                        </div>
                    </div>
                    @else
                    <div class="tl-item">
                        <div class="tl-dot tl-dot-muted"><i class="fa-solid fa-ellipsis"></i></div>
                        <div class="tl-body">
                            <div class="tl-title" style="color:var(--muted);">Awaiting completion</div>
                            <div class="tl-time">Status: {{ ucfirst($order->status) }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
