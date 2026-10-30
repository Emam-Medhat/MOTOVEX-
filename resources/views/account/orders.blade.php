@extends('layouts.account')
@section('title', 'My Orders')
@section('heading', 'My Orders')

@section('content')

  @if($orders->isEmpty())
    <div class="ac-card">
      <div class="ac-card-body">
        <div class="ac-empty">
          <div class="ac-empty-ico"><i class="fa-solid fa-receipt"></i></div>
          <h3>No orders yet</h3>
          <p>You haven't placed any purchase orders. Browse our catalogue and request a motorcycle.</p>
          <div style="display:flex;align-items:center;justify-content:center;gap:.85rem;flex-wrap:wrap;margin-top:1.25rem;">
            <a href="{{ route('motorcycles.index') }}" class="ac-btn-primary" style="font-size:.85rem;">
              <i class="fa-solid fa-motorcycle"></i> Browse Motorcycles
            </a>
          </div>
        </div>
      </div>
    </div>
  @else

    <div class="ac-card" style="margin-bottom:1.5rem;">
      <div class="ac-card-header">
        <div class="ac-card-title">
          <i class="fa-solid fa-receipt"></i>
          Purchase Orders
          <small style="font-size:.72rem;color:#94a3b8;font-weight:400;margin-left:.25rem;">{{ $orders->total() }} total</small>
        </div>
      </div>

      <div class="ac-table-wrap">
        <table class="ac-table">
          <thead>
            <tr>
              <th>Reference</th>
              <th>Motorcycle</th>
              <th>Total</th>
              <th>Payment</th>
              <th>Status</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($orders as $order)
            @php
              $statusColors = ['pending'=>'pending','contacted'=>'contacted','completed'=>'completed','cancelled'=>'cancelled'];
              $payColors    = ['pending'=>'pending','verified'=>'completed','failed'=>'cancelled','refunded'=>'cancelled'];
              $sc = $statusColors[$order->status] ?? 'pending';
              $pc = $payColors[$order->payment_status ?? 'pending'] ?? 'pending';
            @endphp
            <tr>
              <td>
                <a href="{{ route('account.orders.show', $order) }}"
                   style="font-family:ui-monospace,monospace;font-size:.78rem;font-weight:700;color:var(--ac-primary);text-decoration:none;padding:3px 8px;border-radius:7px;background:var(--ac-primary-soft);border:1px solid #e9d5ff;display:inline-block;">
                  {{ $order->order_reference ?? '#'.$order->id }}
                </a>
              </td>
              <td>
                <div style="font-weight:700;font-size:.85rem;color:var(--ac-ink);">{{ $order->motorcycle_snapshot ?? $order->motorcycle?->model_name ?? '—' }}</div>
                <div style="font-size:.72rem;color:#a78bfa;font-weight:600;margin-top:.1rem;">{{ $order->motorcycle?->brand?->name }}</div>
              </td>
              <td>
                @if($order->total_amount)
                  <span style="font-weight:800;font-size:.9rem;color:var(--ac-primary);">{{ $order->currency ?? 'USD' }} {{ number_format($order->total_amount) }}</span>
                @else
                  <span style="color:var(--ac-muted);font-size:.8rem;">—</span>
                @endif
              </td>
              <td><span class="ac-badge {{ $pc }}">{{ ucfirst($order->payment_status ?? 'pending') }}</span></td>
              <td><span class="ac-badge {{ $sc }}">{{ ucfirst($order->status) }}</span></td>
              <td style="font-size:.78rem;color:var(--ac-muted);">{{ $order->created_at?->format('d M Y') }}</td>
              <td>
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
