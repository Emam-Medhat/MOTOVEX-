@extends('layouts.account')
@section('title', 'Order Item #' . $item->id)
@section('heading', 'Order Item Detail')

@section('content')

<div style="margin-bottom:1rem">
    <a href="{{ route('account.merchant-orders.index') }}" class="ac-btn-ghost" style="padding:.5rem .9rem;font-size:.8rem">
        <i class="fa-solid fa-arrow-left" style="font-size:.7rem"></i> Back to Orders
    </a>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem">
    {{-- Part details --}}
    <div class="ac-card">
        <div class="ac-card-header">
            <div class="ac-card-title"><i class="fa-solid fa-gears"></i> Part Details</div>
        </div>
        <div class="ac-card-body">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:1rem">
                @if($item->sparePart?->image_url)
                    <img src="{{ $item->sparePart->image_url }}"
                         style="width:80px;height:64px;object-fit:contain;border-radius:10px;background:#0f172a;padding:6px;border:1px solid var(--ac-line)">
                @endif
                <div>
                    <p style="font-weight:800;font-size:1rem;color:var(--ac-ink)">{{ $item->displayName() }}</p>
                    @if($item->sku_snapshot)
                    <p style="font-size:.78rem;font-family:monospace;color:var(--ac-muted)">SKU: {{ $item->sku_snapshot }}</p>
                    @endif
                    @if($item->sparePart?->brand)
                    <p style="font-size:.78rem;color:var(--ac-primary-d);font-weight:600">{{ $item->sparePart->brand->name }}</p>
                    @endif
                </div>
            </div>
            <div class="ac-info-list">
                <div class="ac-info-row">
                    <span class="ac-info-row-label">Quantity</span>
                    <span class="ac-info-row-value">{{ $item->quantity }}</span>
                </div>
                <div class="ac-info-row">
                    <span class="ac-info-row-label">Unit Price</span>
                    <span class="ac-info-row-value">{{ number_format($item->unit_price, 2) }}</span>
                </div>
                <div class="ac-info-row">
                    <span class="ac-info-row-label">Subtotal</span>
                    <span class="ac-info-row-value" style="font-weight:800;color:var(--ac-primary-d)">{{ number_format($item->subtotal, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Customer details --}}
    <div class="ac-card">
        <div class="ac-card-header">
            <div class="ac-card-title"><i class="fa-solid fa-user"></i> Customer & Delivery</div>
        </div>
        <div class="ac-card-body">
            <div class="ac-info-list">
                <div class="ac-info-row">
                    <span class="ac-info-row-label">Name</span>
                    <span class="ac-info-row-value">{{ $item->order->name }}</span>
                </div>
                <div class="ac-info-row">
                    <span class="ac-info-row-label">Phone</span>
                    <span class="ac-info-row-value">{{ $item->order->phone }}</span>
                </div>
                <div class="ac-info-row">
                    <span class="ac-info-row-label">Email</span>
                    <span class="ac-info-row-value" style="font-size:.78rem">{{ $item->order->email }}</span>
                </div>
                @if($item->order->delivery_address)
                <div class="ac-info-row" style="flex-direction:column;align-items:flex-start;gap:4px">
                    <span class="ac-info-row-label">Delivery Address</span>
                    <span class="ac-info-row-value" style="font-size:.85rem">{{ $item->order->delivery_address }}</span>
                </div>
                @endif
                @if($item->order->city)
                <div class="ac-info-row">
                    <span class="ac-info-row-label">City</span>
                    <span class="ac-info-row-value">{{ $item->order->city }}</span>
                </div>
                @endif
                @if($item->order->admin_notes)
                <div class="ac-info-row" style="flex-direction:column;align-items:flex-start;gap:4px">
                    <span class="ac-info-row-label">Customer Notes</span>
                    <span class="ac-info-row-value" style="font-size:.85rem">{{ $item->order->admin_notes }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Order reference + payment status --}}
<div class="ac-card" style="margin-bottom:1.25rem">
    <div class="ac-card-body" style="display:flex;align-items:center;gap:2rem;flex-wrap:wrap">
        <div>
            <p style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--ac-muted)">Order Reference</p>
            <p style="font-family:monospace;font-weight:800;color:var(--ac-primary-d);font-size:1rem">{{ $item->order->order_reference }}</p>
        </div>
        <div>
            <p style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--ac-muted)">Order Date</p>
            <p style="font-weight:700;font-size:.9rem">{{ $item->order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <div>
            <p style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--ac-muted)">Payment</p>
            <p style="font-weight:700;font-size:.9rem">{{ ucfirst($item->order->payment_status) }}</p>
        </div>
        <div>
            <p style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--ac-muted)">Delivery Method</p>
            <p style="font-weight:700;font-size:.9rem">{{ str_replace('_',' ', ucfirst($item->order->delivery_method)) }}</p>
        </div>
    </div>
</div>

{{-- Current status + Update form --}}
<div class="ac-card">
    <div class="ac-card-header">
        <div class="ac-card-title"><i class="fa-solid fa-truck"></i> Fulfillment Status</div>
        @php
            $statusColors = ['pending'=>'#d97706','accepted'=>'#2563eb','preparing'=>'#7c3aed','ready_for_shipping'=>'#0891b2','shipped'=>'#16a34a','delivered'=>'#15803d','rejected'=>'#dc2626','cancelled'=>'#6b7280'];
            $color = $statusColors[$item->status] ?? '#64748b';
        @endphp
        <span style="padding:.3rem .75rem;border-radius:20px;font-size:.75rem;font-weight:700;background:{{ $color }}18;color:{{ $color }};border:1px solid {{ $color }}30">
            {{ \App\Models\OrderItem::$merchantStatuses[$item->status] ?? ucfirst($item->status) }}
        </span>
    </div>
    <div class="ac-card-body">

        @if($item->rejection_reason)
        <div style="margin-bottom:1rem;padding:.85rem 1rem;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;font-size:.85rem;color:#dc2626">
            <strong>Rejection Reason:</strong> {{ $item->rejection_reason }}
        </div>
        @endif

        @if($item->fulfillment_notes)
        <div style="margin-bottom:1rem;padding:.85rem 1rem;background:var(--ac-lav);border:1px solid var(--ac-line);border-radius:10px;font-size:.85rem">
            <strong>Notes:</strong> {{ $item->fulfillment_notes }}
        </div>
        @endif

        @if($item->tracking_number)
        <div style="margin-bottom:1rem;padding:.85rem 1rem;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;font-size:.85rem">
            <strong>Tracking:</strong> {{ $item->tracking_number }}
        </div>
        @endif

        @if(!empty($allowedTransitions))
        <form method="POST" action="{{ route('account.merchant-orders.update-status', $item) }}">
            @csrf @method('PATCH')

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem">
                <div>
                    <label class="ac-label">Update Status</label>
                    <select name="status" id="statusSelect" class="ac-input"
                            onchange="document.getElementById('rejectBlock').style.display = this.value === 'rejected' ? 'block' : 'none'">
                        <option value="">— Select new status —</option>
                        @foreach($allowedTransitions as $s)
                        <option value="{{ $s }}">{{ \App\Models\OrderItem::$merchantStatuses[$s] ?? $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="ac-label">Tracking Number (optional)</label>
                    <input type="text" name="tracking_number" class="ac-input" placeholder="e.g. TRACK123456" value="{{ $item->tracking_number }}">
                </div>
            </div>

            <div id="rejectBlock" style="display:none;margin-bottom:1rem">
                <label class="ac-label">Rejection Reason <span style="color:#ef4444">*</span></label>
                <textarea name="rejection_reason" rows="2" class="ac-input"
                          placeholder="Explain why you cannot fulfill this order item…"></textarea>
            </div>

            <div style="margin-bottom:1rem">
                <label class="ac-label">Fulfillment Notes (optional)</label>
                <textarea name="fulfillment_notes" rows="2" class="ac-input"
                          placeholder="Any notes for the customer or admin…">{{ $item->fulfillment_notes }}</textarea>
            </div>

            <button type="submit" class="ac-btn-primary">
                <i class="fa-solid fa-check"></i> Update Status
            </button>
        </form>
        @else
        <div style="padding:1rem;background:var(--ac-lav);border-radius:10px;font-size:.85rem;color:var(--ac-muted)">
            <i class="fa-solid fa-lock mr-1"></i>
            No further status transitions available for this item.
        </div>
        @endif
    </div>
</div>

@endsection
