@extends('layouts.account')
@section('title', 'Order ' . ($enquiry->order_reference ?? '#'.$enquiry->id))
@section('heading', 'Order Details')

@section('content')

  {{-- Back + reference --}}
  <div style="display:flex;align-items:center;gap:.85rem;flex-wrap:wrap;margin-bottom:1.5rem;">
    <a href="{{ route('account.orders') }}" class="ac-btn-ghost" style="font-size:.82rem;">
      <i class="fa-solid fa-arrow-left text-xs"></i> Back to Orders
    </a>
    <span style="font-family:ui-monospace,monospace;font-size:.85rem;font-weight:700;color:var(--ac-primary);padding:4px 12px;border-radius:8px;background:var(--ac-primary-soft);border:1px solid #e9d5ff;">
      {{ $enquiry->order_reference ?? '#'.$enquiry->id }}
    </span>
    @php
      $statusMap = ['pending'=>'pending','contacted'=>'contacted','completed'=>'completed','cancelled'=>'cancelled'];
      $payMap    = ['pending'=>'pending','verified'=>'completed','failed'=>'cancelled','refunded'=>'cancelled'];
    @endphp
    <span class="ac-badge {{ $statusMap[$enquiry->status] ?? 'pending' }}">
      {{ ucfirst($enquiry->status) }}
    </span>
    <span class="ac-badge {{ $payMap[$enquiry->payment_status ?? 'pending'] ?? 'pending' }}">
      <i class="fa-solid fa-credit-card" style="font-size:.65rem;"></i>
      Payment: {{ ucfirst($enquiry->payment_status ?? 'Pending') }}
    </span>
  </div>

  {{-- Pending notice --}}
  @if($enquiry->status === 'pending')
  <div style="display:flex;align-items:flex-start;gap:.75rem;padding:.9rem 1.1rem;border-radius:13px;background:#fffbeb;border:1px solid #fde68a;margin-bottom:1.5rem;">
    <i class="fa-solid fa-circle-info" style="color:#d97706;margin-top:.1rem;flex-shrink:0;"></i>
    <div>
      <p style="font-size:.84rem;font-weight:700;color:#92400e;">Order Pending Review</p>
      <p style="font-size:.76rem;color:#b45309;margin-top:.2rem;line-height:1.55;">Our team will contact you within 24 hours to confirm availability and payment details. No payment has been taken yet.</p>
    </div>
  </div>
  @elseif($enquiry->status === 'completed')
  <div style="display:flex;align-items:flex-start;gap:.75rem;padding:.9rem 1.1rem;border-radius:13px;background:#f0fdf4;border:1px solid #bbf7d0;margin-bottom:1.5rem;">
    <i class="fa-solid fa-circle-check" style="color:#16a34a;margin-top:.1rem;flex-shrink:0;"></i>
    <p style="font-size:.84rem;font-weight:600;color:#15803d;">This order has been completed. Thank you for your purchase!</p>
  </div>
  @elseif($enquiry->status === 'cancelled')
  <div style="display:flex;align-items:flex-start;gap:.75rem;padding:.9rem 1.1rem;border-radius:13px;background:#fff1f2;border:1px solid #fecaca;margin-bottom:1.5rem;">
    <i class="fa-solid fa-circle-xmark" style="color:#dc2626;margin-top:.1rem;flex-shrink:0;"></i>
    <p style="font-size:.84rem;font-weight:600;color:#991b1b;">This order has been cancelled. Contact us if you have questions.</p>
  </div>
  @endif

  <div style="display:grid;grid-template-columns:1fr;gap:1.25rem;">
    @media (min-width:768px) { .od-2col { grid-template-columns:1fr 1fr !important; } }

    {{-- Motorcycle --}}
    <div class="ac-card">
      <div class="ac-card-header">
        <div class="ac-card-title"><i class="fa-solid fa-motorcycle"></i> Motorcycle</div>
      </div>
      <div class="ac-card-body">
        @if($enquiry->motorcycle)
          <div style="display:flex;align-items:center;gap:1rem;padding:1rem;border-radius:12px;background:var(--ac-lav);border:1px solid var(--ac-line);margin-bottom:1rem;">
            <div style="width:72px;height:56px;border-radius:10px;overflow:hidden;border:1px solid var(--ac-line-2);flex-shrink:0;background:#fff;display:flex;align-items:center;justify-content:center;">
              @if($enquiry->motorcycle->image_url)
                <img src="{{ $enquiry->motorcycle->image_url }}" alt="" style="width:100%;height:100%;object-fit:cover;">
              @else
                <i class="fa-solid fa-motorcycle" style="color:var(--ac-primary);font-size:1.5rem;"></i>
              @endif
            </div>
            <div>
              <div style="font-weight:800;font-size:.95rem;color:var(--ac-ink);">{{ $enquiry->motorcycle_snapshot ?? $enquiry->motorcycle->model_name }}</div>
              <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:var(--ac-primary);margin-top:.15rem;">{{ $enquiry->motorcycle->brand?->name }}</div>
              @if($enquiry->motorcycle->engine_capacity)
                <div style="font-size:.76rem;color:var(--ac-muted);margin-top:.15rem;">{{ number_format($enquiry->motorcycle->engine_capacity) }} cc · {{ $enquiry->motorcycle->year }}</div>
              @endif
            </div>
          </div>
        @else
          <p style="font-size:.88rem;font-weight:600;color:var(--ac-ink);">{{ $enquiry->motorcycle_snapshot ?? 'Motorcycle details not available' }}</p>
        @endif

        {{-- Price breakdown --}}
        <div style="display:flex;flex-direction:column;gap:.45rem;padding:.9rem;border-radius:10px;background:var(--ac-lav);border:1px solid var(--ac-line);">
          <div style="display:flex;justify-content:space-between;font-size:.82rem;">
            <span style="color:var(--ac-muted);">Unit Price</span>
            <span style="font-weight:600;color:var(--ac-ink);">{{ $enquiry->currency ?? 'USD' }} {{ number_format($enquiry->unit_price ?? 0) }}</span>
          </div>
          <div style="display:flex;justify-content:space-between;font-size:.82rem;">
            <span style="color:var(--ac-muted);">Quantity</span>
            <span style="font-weight:600;color:var(--ac-ink);">{{ $enquiry->quantity ?? 1 }}</span>
          </div>
          <div style="display:flex;justify-content:space-between;font-size:.82rem;">
            <span style="color:var(--ac-muted);">Delivery Fee</span>
            <span style="font-weight:600;color:var(--ac-ink);">{{ $enquiry->currency ?? 'USD' }} {{ number_format($enquiry->delivery_fee ?? 0) }}</span>
          </div>
          <div style="display:flex;justify-content:space-between;padding-top:.5rem;border-top:1px solid var(--ac-line-2);margin-top:.3rem;">
            <span style="font-weight:700;color:var(--ac-ink);">Total</span>
            <span style="font-size:1.1rem;font-weight:900;color:var(--ac-primary);">{{ $enquiry->currency ?? 'USD' }} {{ number_format($enquiry->total_amount ?? 0) }}</span>
          </div>
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
            <span class="ac-info-row-label">Delivery Method</span>
            <span class="ac-info-row-value">{{ ucwords(str_replace('_',' ',$enquiry->delivery_method ?? 'Not specified')) }}</span>
          </div>
          @if($enquiry->delivery_address)
          <div class="ac-info-row">
            <span class="ac-info-row-label">Address</span>
            <span class="ac-info-row-value">{{ $enquiry->delivery_address }}</span>
          </div>
          @endif
          @if($enquiry->city || $enquiry->governorate)
          <div class="ac-info-row">
            <span class="ac-info-row-label">City / Governorate</span>
            <span class="ac-info-row-value">{{ implode(', ', array_filter([$enquiry->city, $enquiry->governorate])) }}</span>
          </div>
          @endif
          <div class="ac-info-row">
            <span class="ac-info-row-label">Payment Method</span>
            <span class="ac-info-row-value">{{ ucwords(str_replace('_',' ',$enquiry->payment_method ?? 'Not specified')) }}</span>
          </div>
          <div class="ac-info-row">
            <span class="ac-info-row-label">Payment Status</span>
            <span class="ac-info-row-value">
              <span class="ac-badge {{ $payMap[$enquiry->payment_status ?? 'pending'] ?? 'pending' }}">{{ ucfirst($enquiry->payment_status ?? 'pending') }}</span>
            </span>
          </div>
          <div class="ac-info-row">
            <span class="ac-info-row-label">Submitted</span>
            <span class="ac-info-row-value">{{ $enquiry->created_at?->format('d M Y, H:i') }}</span>
          </div>
        </div>
      </div>
    </div>

    {{-- Notes --}}
    @if($enquiry->message)
    <div class="ac-card">
      <div class="ac-card-header">
        <div class="ac-card-title"><i class="fa-solid fa-comment-dots"></i> Your Notes</div>
      </div>
      <div class="ac-card-body">
        <p style="font-size:.9rem;color:var(--ac-ink-2);line-height:1.7;">{{ $enquiry->message }}</p>
      </div>
    </div>
    @endif

  </div>

  {{-- Need help --}}
  <div style="margin-top:1.5rem;padding:1rem 1.25rem;border-radius:13px;background:var(--ac-lav);border:1px solid var(--ac-line);display:flex;align-items:center;gap:.85rem;">
    <i class="fa-solid fa-headset" style="color:var(--ac-primary);font-size:1rem;flex-shrink:0;"></i>
    <div>
      <p style="font-size:.84rem;font-weight:700;color:var(--ac-ink);">Need help with this order?</p>
      <p style="font-size:.77rem;color:var(--ac-muted);margin-top:.15rem;">Contact our team and quote reference <strong>{{ $enquiry->order_reference }}</strong>.</p>
    </div>
    <a href="{{ route('contact') }}" class="ac-btn-ghost" style="margin-left:auto;font-size:.8rem;flex-shrink:0;">Contact Us</a>
  </div>

@endsection
