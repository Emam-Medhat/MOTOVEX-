@extends('layouts.account')
@section('title', 'My Orders')
@section('heading', 'My Orders')

@section('content')

  {{-- Stats row --}}
  <div class="ac-stat-grid" style="grid-template-columns:repeat(2,1fr)">
    @foreach([
        ['Total',      $stats['total'],      'fa-receipt',        '#7c3aed'],
        ['Pending',    $stats['pending'],    'fa-clock',          '#d97706'],
        ['Processing', $stats['processing'], 'fa-gears',          '#2563eb'],
        ['Completed',  $stats['completed'],  'fa-flag-checkered', '#15803d'],
    ] as [$label, $val, $icon, $color])
      <div class="ac-stat">
        <div class="ac-stat-ico" style="background:{{ $color }}15;color:{{ $color }}">
          <i class="fa-solid {{ $icon }}"></i>
        </div>
        <div>
          <div class="ac-stat-num" style="color:{{ $color }}">{{ $val }}</div>
          <div class="ac-stat-label">{{ $label }}</div>
        </div>
      </div>
    @endforeach
  </div>

  {{-- Filters --}}
  <form method="GET" action="{{ route('account.orders') }}"
        style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:1.25rem">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Search by order reference…"
           class="ac-input" style="max-width:240px;padding:.6rem .9rem;font-size:.82rem">
    <select name="status" class="ac-input" style="max-width:190px;padding:.6rem .9rem;font-size:.82rem">
      <option value="">All Statuses</option>
      @foreach([
        'pending'    => 'Pending',
        'confirmed'  => 'Confirmed',
        'processing' => 'Processing',
        'completed'  => 'Completed',
        'cancelled'  => 'Cancelled',
      ] as $k => $v)
        <option value="{{ $k }}" {{ request('status') === $k ? 'selected' : '' }}>{{ $v }}</option>
      @endforeach
    </select>
    <button type="submit" class="ac-btn-ghost" style="padding:.62rem 1rem;font-size:.82rem">
      <i class="fa-solid fa-search"></i> Filter
    </button>
    @if(request()->hasAny(['search','status','payment_status']))
      <a href="{{ route('account.orders') }}" class="ac-btn-ghost" style="padding:.62rem 1rem;font-size:.82rem">Clear</a>
    @endif
  </form>

  @if($orders->isEmpty())
    <div class="ac-card">
      <div class="ac-card-body">
        <div class="ac-empty">
          <div class="ac-empty-ico"><i class="fa-solid fa-receipt"></i></div>
          <h3>No orders yet</h3>
          <p>You haven't placed any orders. Browse the catalogue and add motorcycles or spare parts to your cart.</p>
          <div style="display:flex;align-items:center;justify-content:center;gap:.85rem;flex-wrap:wrap;margin-top:1.25rem;">
            <a href="{{ route('motorcycles.index') }}" class="ac-btn-primary" style="font-size:.85rem;">
              <i class="fa-solid fa-motorcycle"></i> Browse Motorcycles
            </a>
            <a href="{{ route('spare-parts.index') }}" class="ac-btn-ghost" style="font-size:.85rem;">
              <i class="fa-solid fa-gears"></i> Browse Spare Parts
            </a>
          </div>
        </div>
      </div>
    </div>
  @else
    <div class="ac-card">
      <div class="ac-table-wrap">
        <table class="ac-table">
          <thead>
            <tr>
              <th>Reference</th>
              <th>Items</th>
              <th>Total</th>
              <th>Payment</th>
              <th>Status</th>
              <th>Date</th>
              <th style="text-align:right">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($orders as $order)
            @php
              $statusColors = [
                'pending'=>'pending','confirmed'=>'contacted','processing'=>'contacted',
                'completed'=>'completed','cancelled'=>'cancelled',
              ];
              $payColors = ['pending'=>'pending','paid'=>'completed','failed'=>'cancelled','refunded'=>'cancelled'];
              $sc = $statusColors[$order->status] ?? 'pending';
              $pc = $payColors[$order->payment_status] ?? 'pending';
            @endphp
            <tr>
              <td>
                <a href="{{ route('account.orders.show', $order) }}"
                   style="font-family:ui-monospace,monospace;font-size:.78rem;font-weight:700;color:var(--ac-primary);text-decoration:none;padding:3px 8px;border-radius:7px;background:var(--ac-primary-soft);border:1px solid #e9d5ff;display:inline-block;">
                  {{ $order->order_reference }}
                </a>
              </td>
              <td>
                <span class="ac-type-tag">
                  <i class="fa-solid fa-box"></i> {{ $order->items_count }} {{ Str::plural('item', $order->items_count) }}
                </span>
              </td>
              <td>
                <span style="font-weight:800;font-size:.9rem;color:var(--ac-primary);">
                  {{ $order->currency }} {{ number_format((float) $order->total_amount, 2) }}
                </span>
              </td>
              <td><span class="ac-badge {{ $pc }}">{{ ucfirst($order->payment_status) }}</span></td>
              <td><span class="ac-badge {{ $sc }}">{{ ucfirst($order->status) }}</span></td>
              <td style="font-size:.78rem;color:var(--ac-muted);">{{ $order->created_at?->format('d M Y') }}</td>
              <td style="text-align:right">
                <a href="{{ route('account.orders.show', $order) }}"
                   class="ac-btn-ghost" style="font-size:.78rem;padding:.45rem .9rem;">
                  View <i class="fa-solid fa-arrow-right" style="font-size:.65rem;"></i>
                </a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    @if($orders->hasPages())
      <div class="ac-pager">{{ $orders->links() }}</div>
    @endif
  @endif

@endsection
