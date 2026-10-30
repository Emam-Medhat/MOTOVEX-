@extends('layouts.account')
@section('title', 'Order ' . $order->order_reference)
@section('heading', 'Order Details')

@section('content')

  @php
    $statusColors = [
      'pending'=>'pending','confirmed'=>'contacted','processing'=>'contacted',
      'completed'=>'completed','cancelled'=>'cancelled',
    ];
    $payColors = ['pending'=>'pending','paid'=>'completed','failed'=>'cancelled','refunded'=>'cancelled'];
    $itemStatusColors = [
      'pending'=>'#d97706','accepted'=>'#2563eb','preparing'=>'#7c3aed','ready_for_shipping'=>'#0891b2',
      'shipped'=>'#16a34a','delivered'=>'#15803d','rejected'=>'#dc2626','cancelled'=>'#6b7280',
    ];
  @endphp

  {{-- Back + reference --}}
  <div style="display:flex;align-items:center;gap:.85rem;flex-wrap:wrap;margin-bottom:1.5rem;">
    <a href="{{ route('account.orders') }}" class="ac-btn-ghost" style="font-size:.82rem;">
      <i class="fa-solid fa-arrow-left" style="font-size:.7rem;"></i> Back to Orders
    </a>
    <span style="font-family:ui-monospace,monospace;font-size:.85rem;font-weight:700;color:var(--ac-primary);padding:4px 12px;border-radius:8px;background:var(--ac-primary-soft);border:1px solid #e9d5ff;">
      {{ $order->order_reference }}
    </span>
    <span class="ac-badge {{ $statusColors[$order->status] ?? 'pending' }}">{{ ucfirst($order->status) }}</span>
    <span class="ac-badge {{ $payColors[$order->payment_status] ?? 'pending' }}">
      <i class="fa-solid fa-credit-card" style="font-size:.65rem;"></i> Payment: {{ ucfirst($order->payment_status) }}
    </span>
  </div>

  {{-- Status notice — never claim payment success that did not happen --}}
  @if($order->status === 'pending')
    <div style="display:flex;align-items:flex-start;gap:.75rem;padding:.9rem 1.1rem;border-radius:13px;background:#fffbeb;border:1px solid #fde68a;margin-bottom:1.5rem;">
      <i class="fa-solid fa-circle-info" style="color:#d97706;margin-top:.1rem;flex-shrink:0;"></i>
      <div>
        <p style="font-size:.84rem;font-weight:700;color:#92400e;">Order Pending Review</p>
        <p style="font-size:.76rem;color:#b45309;margin-top:.2rem;line-height:1.55;">
          Our team and the responsible merchants are reviewing your order. Payment is only confirmed after our team verifies it — no payment has been taken yet.
        </p>
      </div>
    </div>
  @elseif($order->status === 'completed')
    <div style="display:flex;align-items:flex-start;gap:.75rem;padding:.9rem 1.1rem;border-radius:13px;background:#f0fdf4;border:1px solid #bbf7d0;margin-bottom:1.5rem;">
      <i class="fa-solid fa-circle-check" style="color:#16a34a;margin-top:.1rem;flex-shrink:0;"></i>
      <p style="font-size:.84rem;font-weight:600;color:#15803d;">This order has been completed. Thank you for your purchase!</p>
    </div>
  @elseif($order->status === 'cancelled')
    <div style="display:flex;align-items:flex-start;gap:.75rem;padding:.9rem 1.1rem;border-radius:13px;background:#fff1f2;border:1px solid #fecaca;margin-bottom:1.5rem;">
      <i class="fa-solid fa-circle-xmark" style="color:#dc2626;margin-top:.1rem;flex-shrink:0;"></i>
      <p style="font-size:.84rem;font-weight:600;color:#991b1b;">This order has been cancelled. Contact us if you have questions.</p>
    </div>
  @endif

  {{-- Items --}}
  <div class="ac-card" style="margin-bottom:1.5rem;">
    <div class="ac-card-header">
      <div class="ac-card-title">
        <i class="fa-solid fa-boxes-stacked"></i>
        Order Items
        <small style="font-size:.72rem;color:#94a3b8;font-weight:400;margin-left:.25rem;">{{ $order->items->count() }} total</small>
      </div>
    </div>
    <div class="ac-card-body" style="padding:0;">
      @foreach($order->items as $item)
        @php $c = $itemStatusColors[$item->status] ?? '#64748b'; @endphp
        <div style="display:flex;gap:1rem;padding:1.1rem 1.5rem;border-bottom:1px solid var(--ac-line);align-items:flex-start;">
          {{-- Image --}}
          <div style="width:72px;height:60px;border-radius:10px;overflow:hidden;border:1px solid var(--ac-line-2);flex-shrink:0;background:var(--ac-lav);display:flex;align-items:center;justify-content:center;">
            @if($item->productImageUrl())
              <img src="{{ $item->productImageUrl() }}" alt="" style="width:100%;height:100%;object-fit:cover;">
            @elseif($item->isSparePartItem())
              <i class="fa-solid fa-gears" style="color:var(--ac-primary);font-size:1.4rem;"></i>
            @else
              <i class="fa-solid fa-motorcycle" style="color:var(--ac-primary);font-size:1.4rem;"></i>
            @endif
          </div>

          {{-- Details --}}
          <div style="flex:1;min-width:0;">
            <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;">
              <span style="font-weight:800;font-size:.92rem;color:var(--ac-ink);">{{ $item->displayName() }}</span>
              <span class="ac-type-tag">
                <i class="fa-solid {{ $item->isSparePartItem() ? 'fa-gears' : 'fa-motorcycle' }}"></i>
                {{ $item->isSparePartItem() ? 'Spare Part' : 'Motorcycle' }}
              </span>
            </div>
            <div style="display:flex;gap:1.25rem;flex-wrap:wrap;margin-top:.4rem;font-size:.8rem;color:var(--ac-muted);">
              @if($item->sku_snapshot)
                <span>SKU: <strong style="font-family:monospace;color:var(--ac-ink-2);">{{ $item->sku_snapshot }}</strong></span>
              @endif
              <span>Qty: <strong style="color:var(--ac-ink-2);">{{ $item->quantity }}</strong></span>
              <span>Unit: <strong style="color:var(--ac-ink-2);">{{ $order->currency }} {{ number_format((float) $item->unit_price, 2) }}</strong></span>
            </div>

            {{-- Merchant + tracking --}}
            <div style="display:flex;gap:.75rem;flex-wrap:wrap;margin-top:.55rem;">
              @if($item->merchant)
                <span class="ac-badge contacted"><i class="fa-solid fa-store" style="font-size:.62rem;"></i> Seller: {{ $item->merchant->name }}</span>
              @endif
              @if($item->tracking_number)
                <span class="ac-badge completed"><i class="fa-solid fa-truck" style="font-size:.62rem;"></i> Tracking: {{ $item->tracking_number }}</span>
              @endif
            </div>

            @if($item->rejection_reason)
              <p style="margin-top:.5rem;font-size:.78rem;color:#dc2626;"><strong>Rejected:</strong> {{ $item->rejection_reason }}</p>
            @endif
          </div>

          {{-- Status + subtotal --}}
          <div style="text-align:right;flex-shrink:0;">
            <span style="display:inline-block;padding:.28rem .7rem;border-radius:999px;font-size:.72rem;font-weight:700;background:{{ $c }}18;color:{{ $c }};border:1px solid {{ $c }}30;">
              {{ \App\Models\OrderItem::$merchantStatuses[$item->status] ?? ucfirst($item->status) }}
            </span>
            <div style="font-weight:800;font-size:.95rem;color:var(--ac-ink);margin-top:.5rem;">
              {{ $order->currency }} {{ number_format((float) $item->subtotal, 2) }}
            </div>
          </div>
        </div>
      @endforeach

      {{-- Total --}}
      <div style="display:flex;justify-content:flex-end;align-items:center;gap:1rem;padding:1.1rem 1.5rem;background:var(--ac-lav);">
        <span style="font-weight:700;color:var(--ac-ink-2);">Order Total</span>
        <span style="font-size:1.25rem;font-weight:900;color:var(--ac-primary);">
          {{ $order->currency }} {{ number_format((float) $order->total_amount, 2) }}
        </span>
      </div>
    </div>
  </div>

  {{-- Delivery & Payment --}}
  <div class="ac-card">
    <div class="ac-card-header">
      <div class="ac-card-title"><i class="fa-solid fa-truck-fast"></i> Delivery & Payment</div>
    </div>
    <div class="ac-card-body">
      <div class="ac-info-list">
        <div class="ac-info-row">
          <span class="ac-info-row-label">Recipient</span>
          <span class="ac-info-row-value">{{ $order->name }} · {{ $order->phone }}</span>
        </div>
        <div class="ac-info-row">
          <span class="ac-info-row-label">Delivery Method</span>
          <span class="ac-info-row-value">{{ ucwords(str_replace('_',' ', $order->delivery_method ?? 'Not specified')) }}</span>
        </div>
        @if($order->delivery_address)
        <div class="ac-info-row" style="flex-direction:column;align-items:flex-start;gap:4px">
          <span class="ac-info-row-label">Address</span>
          <span class="ac-info-row-value" style="font-size:.85rem">{{ $order->delivery_address }}</span>
        </div>
        @endif
        @if($order->city || $order->governorate)
        <div class="ac-info-row">
          <span class="ac-info-row-label">City / Governorate</span>
          <span class="ac-info-row-value">{{ implode(', ', array_filter([$order->city, $order->governorate])) }}</span>
        </div>
        @endif
        <div class="ac-info-row">
          <span class="ac-info-row-label">Payment Method</span>
          <span class="ac-info-row-value">{{ ucwords(str_replace('_',' ', $order->payment_method ?? 'Not specified')) }}</span>
        </div>
        <div class="ac-info-row">
          <span class="ac-info-row-label">Payment Status</span>
          <span class="ac-info-row-value">
            <span class="ac-badge {{ $payColors[$order->payment_status] ?? 'pending' }}">{{ ucfirst($order->payment_status) }}</span>
          </span>
        </div>
        <div class="ac-info-row">
          <span class="ac-info-row-label">Placed On</span>
          <span class="ac-info-row-value">{{ $order->created_at?->format('d M Y, H:i') }}</span>
        </div>
      </div>
    </div>
  </div>

  {{-- Need help --}}
  <div style="margin-top:1.5rem;padding:1rem 1.25rem;border-radius:13px;background:var(--ac-lav);border:1px solid var(--ac-line);display:flex;align-items:center;gap:.85rem;">
    <i class="fa-solid fa-headset" style="color:var(--ac-primary);font-size:1rem;flex-shrink:0;"></i>
    <div>
      <p style="font-size:.84rem;font-weight:700;color:var(--ac-ink);">Need help with this order?</p>
      <p style="font-size:.77rem;color:var(--ac-muted);margin-top:.15rem;">Contact our team and quote reference <strong>{{ $order->order_reference }}</strong>.</p>
    </div>
    <a href="{{ route('contact') }}" class="ac-btn-ghost" style="margin-left:auto;font-size:.8rem;flex-shrink:0;">Contact Us</a>
  </div>

@endsection
