@extends('layouts.account')
@section('title', 'My Orders')
@section('heading', 'Spare Parts Orders')

@section('content')

{{-- Stats row --}}
<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:1.5rem">
    @foreach([
        ['Total', $stats['total'], 'fa-boxes-stacked', '#7c3aed'],
        ['Pending', $stats['pending'], 'fa-clock', '#d97706'],
        ['Accepted', $stats['accepted'], 'fa-circle-check', '#2563eb'],
        ['Shipped', $stats['shipped'], 'fa-truck', '#16a34a'],
        ['Delivered', $stats['delivered'], 'fa-flag-checkered', '#15803d'],
    ] as [$label, $val, $icon, $color])
    <div class="ac-stat" style="flex-direction:column;gap:8px">
        <div class="ac-stat-ico" style="background:{{ $color }}15;color:{{ $color }}">
            <i class="fa-solid {{ $icon }}"></i>
        </div>
        <div>
            <div class="ac-stat-num" style="color:{{ $color }};font-size:1.5rem">{{ $val }}</div>
            <div class="ac-stat-label">{{ $label }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('account.merchant-orders.index') }}"
      style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:1.25rem">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Search by part or order ref…"
           class="ac-input" style="max-width:220px;padding:.6rem .9rem;font-size:.82rem">
    <select name="status" class="ac-input" style="max-width:180px;padding:.6rem .9rem;font-size:.82rem">
        <option value="">All Statuses</option>
        @foreach($statuses as $k => $v)
            <option value="{{ $k }}" {{ request('status') === $k ? 'selected' : '' }}>{{ $v }}</option>
        @endforeach
    </select>
    <button type="submit" class="ac-btn-ghost" style="padding:.62rem 1rem;font-size:.82rem">
        <i class="fa-solid fa-search"></i> Filter
    </button>
    @if(request()->hasAny(['search','status']))
        <a href="{{ route('account.merchant-orders.index') }}" class="ac-btn-ghost" style="padding:.62rem 1rem;font-size:.82rem">Clear</a>
    @endif
</form>

<div class="ac-card">
    @if($items->isEmpty())
        <div class="ac-empty" style="margin:1.5rem">
            <div class="ac-empty-ico"><i class="fa-solid fa-box-open"></i></div>
            <h3>No orders yet</h3>
            <p>When customers purchase your spare parts, orders will appear here.</p>
        </div>
    @else
        <div class="ac-table-wrap">
            <table class="ac-table">
                <thead>
                    <tr>
                        <th>Part</th>
                        <th>Order Ref</th>
                        <th>Customer</th>
                        <th>Qty × Price</th>
                        <th>Subtotal</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th style="text-align:right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    @php
                        $statusColors = [
                            'pending'            => 'pending',
                            'accepted'           => 'contacted',
                            'preparing'          => 'contacted',
                            'ready_for_shipping' => 'contacted',
                            'shipped'            => 'completed',
                            'delivered'          => 'completed',
                            'rejected'           => 'cancelled',
                            'cancelled'          => 'cancelled',
                        ];
                        $badgeClass = $statusColors[$item->status] ?? 'pending';
                    @endphp
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                @if($item->sparePart?->image_url)
                                    <img src="{{ $item->sparePart->image_url }}"
                                         style="width:40px;height:40px;object-fit:contain;border-radius:8px;background:#0f172a;padding:3px;flex-shrink:0">
                                @else
                                    <div style="width:40px;height:40px;border-radius:8px;background:var(--ac-primary-soft);color:var(--ac-primary-d);display:grid;place-items:center;flex-shrink:0">
                                        <i class="fa-solid fa-gears" style="font-size:14px"></i>
                                    </div>
                                @endif
                                <div>
                                    <p style="font-weight:700;font-size:.875rem">{{ Str::limit($item->displayName(), 35) }}</p>
                                    @if($item->sku_snapshot)
                                    <p style="font-size:.72rem;font-family:monospace;color:var(--ac-muted)">{{ $item->sku_snapshot }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td style="font-family:monospace;font-size:.78rem;font-weight:700;color:var(--ac-primary-d)">
                            {{ $item->order->order_reference }}
                        </td>
                        <td style="font-size:.82rem">
                            <p style="font-weight:700">{{ $item->order->name }}</p>
                            <p style="color:var(--ac-muted);font-size:.72rem">{{ $item->order->phone }}</p>
                        </td>
                        <td style="font-size:.875rem">
                            {{ $item->quantity }} × {{ number_format($item->unit_price, 2) }}
                        </td>
                        <td style="font-weight:800;font-size:.9rem">
                            {{ number_format($item->subtotal, 2) }}
                        </td>
                        <td>
                            <span class="ac-badge {{ $badgeClass }}">
                                {{ \App\Models\OrderItem::$merchantStatuses[$item->status] ?? ucfirst($item->status) }}
                            </span>
                        </td>
                        <td style="font-size:.78rem;color:var(--ac-muted)">
                            {{ $item->created_at->format('d M Y') }}
                        </td>
                        <td style="text-align:right">
                            <a href="{{ route('account.merchant-orders.show', $item) }}"
                               class="ac-btn-ghost" style="padding:.45rem .85rem;font-size:.78rem">
                                <i class="fa-solid fa-eye"></i> Manage
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:1rem 1.5rem">
            {{ $items->links('vendor.pagination.simple-tailwind') }}
        </div>
    @endif
</div>

@endsection
