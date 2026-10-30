@extends('layouts.admin')
@section('title', 'Customer Requests')

@section('styles')
<style>
/* ════════════════════════════════
   Customer Requests — unified UI
════════════════════════════════ */

/* ── KPI row ── */
.kpi-row {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 22px;
}
@media(max-width:1100px){ .kpi-row{ grid-template-columns: repeat(3, 1fr); } }
@media(max-width:640px){  .kpi-row{ grid-template-columns: repeat(2, 1fr); } }

.kpi-card {
    position: relative; overflow: hidden;
    background: var(--surface); border: 1px solid var(--line);
    border-radius: 16px; padding: 18px 18px 16px;
    display: flex; flex-direction: column; gap: 12px;
    transition: transform .22s, box-shadow .22s, border-color .22s;
}
.kpi-card:hover { transform: translateY(-3px); box-shadow: 0 12px 32px -10px rgba(8,101,223,.13); }
.kpi-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
    border-radius: 16px 16px 0 0; opacity: 0; transition: opacity .22s;
}
.kpi-card:hover::before { opacity: 1; }
.kc-blue::before   { background: linear-gradient(90deg,#2563eb,#60a5fa); }
.kc-amber::before  { background: linear-gradient(90deg,#d97706,#fbbf24); }
.kc-purple::before { background: linear-gradient(90deg,#7c3aed,#a78bfa); }
.kc-green::before  { background: linear-gradient(90deg,#16a34a,#4ade80); }
.kc-red::before    { background: linear-gradient(90deg,#dc2626,#f87171); }

.kpi-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; }
.kpi-icon {
    width: 42px; height: 42px; border-radius: 12px;
    display: grid; place-items: center; font-size: 16px; flex-shrink: 0;
}
.ki-blue   { background: #eff6ff; color: #2563eb; }
.ki-amber  { background: #fffbeb; color: #d97706; }
.ki-purple { background: #f3e8ff; color: #7c3aed; }
.ki-green  { background: #f0fdf4; color: #16a34a; }
.ki-red    { background: #fff1f2; color: #dc2626; }
.kpi-val { font-size: 32px; font-weight: 900; letter-spacing: -1.5px; color: var(--text); line-height: 1; }
.kpi-lbl { font-size: 11.5px; font-weight: 600; color: var(--muted); margin-top: 2px; }

/* ── Type badge ── */
.type-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 9px; border-radius: 8px;
    font-size: 10.5px; font-weight: 700; white-space: nowrap;
}
.tp-purchase  { background: #f3e8ff; color: #6d28d9; border: 1px solid #e9d5ff; }
.tp-rental    { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.tp-test_ride { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
.tp-enquiry   { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
.tp-general   { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

/* ── Motorcycle cell ── */
.moto-cell { display: flex; align-items: center; gap: 9px; }
.moto-thumb {
    width: 42px; height: 34px; flex-shrink: 0;
    border-radius: 7px; overflow: hidden;
    border: 1px solid var(--border); background: #f8fafc;
    display: grid; place-items: center;
}
.moto-thumb img { width: 100%; height: 100%; object-fit: cover; }
.moto-name { font-size: 12px; font-weight: 600; color: var(--text); }

/* ── Ref link ── */
.ref-link {
    font-family: ui-monospace, monospace;
    font-size: 11px; font-weight: 700; color: var(--primary);
    text-decoration: none; padding: 3px 8px; border-radius: 7px;
    background: #eff6ff; border: 1px solid #bfdbfe;
    transition: background .15s, color .15s; display: inline-block;
}
.ref-link:hover { background: var(--primary); color: #fff; }

/* ── Action buttons ── */
.act-wrap { display: flex; align-items: center; justify-content: flex-end; gap: 6px; }
.act-btn {
    display: inline-flex; align-items: center; gap: 5px;
    height: 32px; padding: 0 12px; border-radius: 8px;
    border: 1.5px solid transparent; font-size: 11px; font-weight: 700;
    font-family: inherit; cursor: pointer; text-decoration: none;
    white-space: nowrap; transition: all .2s;
}
.act-view { background: #eef2ff; color: #4338ca; border-color: #c7d2fe; }
.act-view:hover { background: #6366f1; color: #fff; border-color: #6366f1; transform: translateY(-1px); }
</style>
@endsection

@section('content')

{{-- ── Page Header ── --}}
<div class="page-header">
    <div>
        <h1>Customer Requests</h1>
        <p>{{ $records->total() }} request{{ $records->total() !== 1 ? 's' : '' }} matching your access and filters</p>
    </div>
</div>

{{-- ── KPI Cards ── --}}
@php
    $typeCounts = $records->getCollection()->groupBy('request_type');
    $totalAll   = $records->total();
    $pending    = $records->getCollection()->where('status','pending')->count();
    $completed  = $records->getCollection()->where('status','completed')->count();
    $cancelled  = $records->getCollection()->where('status','cancelled')->count();
@endphp
<div class="kpi-row">
    <div class="kpi-card kc-blue">
        <div class="kpi-top"><div class="kpi-icon ki-blue"><i class="bi bi-inbox-fill"></i></div></div>
        <div class="kpi-val">{{ $totalAll }}</div>
        <div class="kpi-lbl">Total Requests</div>
    </div>
    <div class="kpi-card kc-amber">
        <div class="kpi-top"><div class="kpi-icon ki-amber"><i class="bi bi-clock-fill"></i></div></div>
        <div class="kpi-val">{{ $pending }}</div>
        <div class="kpi-lbl">Pending</div>
    </div>
    <div class="kpi-card kc-purple">
        <div class="kpi-top"><div class="kpi-icon ki-purple"><i class="bi bi-motorcycle"></i></div></div>
        <div class="kpi-val">{{ $typeCounts->get('purchase', collect())->count() }}</div>
        <div class="kpi-lbl">Purchase Orders</div>
    </div>
    <div class="kpi-card kc-green">
        <div class="kpi-top"><div class="kpi-icon ki-green"><i class="bi bi-flag-fill"></i></div></div>
        <div class="kpi-val">{{ $completed }}</div>
        <div class="kpi-lbl">Completed</div>
    </div>
    <div class="kpi-card kc-red">
        <div class="kpi-top"><div class="kpi-icon ki-red"><i class="bi bi-x-circle-fill"></i></div></div>
        <div class="kpi-val">{{ $cancelled }}</div>
        <div class="kpi-lbl">Cancelled</div>
    </div>
</div>

{{-- ── Filters ── --}}
<form method="GET" class="filter-bar">
    <div class="search-wrap">
        <i class="bi bi-search"></i>
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Search reference, customer, motorcycle…">
    </div>
    <select name="type">
        <option value="">All Types</option>
        @foreach(['purchase' => 'Sales', 'rental' => 'Rentals', 'test_ride' => 'Test Rides', 'product' => 'Product Enquiries', 'general' => 'General', 'test_drive' => 'Test Drive', 'service' => 'Service', 'parts' => 'Parts'] as $value => $label)
            <option value="{{ $value }}" @selected(request('type') === $value)>{{ $label }}</option>
        @endforeach
    </select>
    <select name="status">
        <option value="">All Statuses</option>
        @foreach(['pending', 'contacted', 'confirmed', 'completed', 'cancelled'] as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select>
    @if(auth()->user()->isAdmin())
        <select name="manager_id">
            <option value="">All Managers</option>
            @foreach($managers as $manager)
                <option value="{{ $manager->id }}" @selected((string) request('manager_id') === (string) $manager->id)>{{ $manager->name }}</option>
            @endforeach
        </select>
    @endif
    <select name="motorcycle_id">
        <option value="">All Motorcycles</option>
        @foreach($motorcycles as $motorcycle)
            <option value="{{ $motorcycle->id }}" @selected((string) request('motorcycle_id') === (string) $motorcycle->id)>{{ $motorcycle->model_name }}</option>
        @endforeach
    </select>
    <input type="date" name="date_from" value="{{ request('date_from') }}" aria-label="From date">
    <input type="date" name="date_to"   value="{{ request('date_to') }}"   aria-label="To date">
    <button type="submit" class="btn-primary-alt" style="height:38px;padding:0 16px;font-size:12.5px;">
        <i class="bi bi-funnel-fill"></i> Filter
    </button>
    @if(request()->query())
        <a href="{{ route('admin.requests.index') }}" class="filter-clear">
            <i class="bi bi-x-lg" style="font-size:10px;"></i> Clear
        </a>
    @endif
</form>

{{-- ── Table ── --}}
<div class="admin-table-wrap">
    @if($records->isEmpty())
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <p>No customer requests match these filters. Try adjusting your search or clearing the filters.</p>
        </div>
    @else
        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Motorcycle</th>
                        <th>Customer</th>
                        <th>Type</th>
                        <th>Dates / Amount</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th style="text-align:right;padding-right:16px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $record)
                        @php
                            $detailRoute = match($record->request_type) {
                                'purchase'  => 'admin.orders.show',
                                'rental'    => 'admin.rentals.show',
                                'test_ride' => 'admin.test-rides.show',
                                default     => 'admin.enquiries.show',
                            };
                            $reference = $record->reference ?: 'REQ-' . $record->record_id;
                            $image = $record->image_path
                                ? asset('storage/' . $record->image_path)
                                : ($record->image_url ? asset($record->image_url) : null);

                            $typeClass = match($record->request_type) {
                                'purchase'              => 'tp-purchase',
                                'rental'                => 'tp-rental',
                                'test_ride','test_drive' => 'tp-test_ride',
                                default                 => 'tp-general',
                            };
                            $typeLabel = match($record->request_type) {
                                'purchase'   => 'Purchase',
                                'rental'     => 'Rental',
                                'test_ride'  => 'Test Ride',
                                'test_drive' => 'Test Drive',
                                'service'    => 'Service',
                                'parts'      => 'Parts',
                                'product'    => 'Enquiry',
                                default      => ucwords(str_replace('_',' ',$record->request_type)),
                            };

                            $statusClass = match($record->status) {
                                'pending'   => 'badge-yellow',
                                'contacted', 'confirmed' => 'badge-blue',
                                'completed' => 'badge-green',
                                'cancelled' => 'badge-red',
                                default     => 'badge-muted',
                            };
                        @endphp
                        <tr>
                            {{-- Reference --}}
                            <td>
                                <a href="{{ route($detailRoute, $record->record_id) }}" class="ref-link">
                                    {{ $reference }}
                                </a>
                            </td>

                            {{-- Motorcycle --}}
                            <td>
                                @if($record->motorcycle_name)
                                <div class="moto-cell">
                                    <div class="moto-thumb">
                                        @if($image)
                                            <img src="{{ $image }}" alt="">
                                        @else
                                            <i class="bi bi-bicycle text-muted" style="font-size:12px;"></i>
                                        @endif
                                    </div>
                                    <div class="moto-name">{{ $record->motorcycle_name }}</div>
                                </div>
                                @else
                                    <span style="font-size:11px;color:var(--muted);">—</span>
                                @endif
                            </td>

                            {{-- Customer --}}
                            <td>
                                <div style="font-size:12px;font-weight:700;color:var(--text);">{{ $record->customer_name }}</div>
                                <div style="font-size:11px;color:var(--primary);margin-top:1px;">
                                    <a href="mailto:{{ $record->customer_email }}" class="tbl-link" style="font-weight:500;">{{ $record->customer_email }}</a>
                                </div>
                                @if($record->customer_phone)
                                <div style="font-size:10.5px;color:var(--muted);">{{ $record->customer_phone }}</div>
                                @endif
                            </td>

                            {{-- Type --}}
                            <td>
                                <span class="type-pill {{ $typeClass }}">{{ $typeLabel }}</span>
                            </td>

                            {{-- Dates / Amount --}}
                            <td>
                                @if($record->rental_start_date)
                                    <div style="font-size:11.5px;font-weight:600;color:var(--text);">
                                        {{ $record->rental_start_date }} – {{ $record->rental_end_date }}
                                    </div>
                                    <div style="font-size:10.5px;color:var(--muted);">
                                        {{ $record->rental_days }} days ·
                                        {{ $record->currency }} {{ number_format($record->rental_price_per_day, 2) }}/day
                                    </div>
                                @elseif($record->total_amount !== null)
                                    <span style="font-size:13px;font-weight:800;color:var(--text);">
                                        {{ $record->currency }} {{ number_format($record->total_amount, 2) }}
                                    </span>
                                @elseif($record->requested_time)
                                    <span style="font-size:11.5px;color:var(--subtext);">{{ $record->requested_time }}</span>
                                @else
                                    <span style="font-size:11px;color:var(--muted);">—</span>
                                @endif
                            </td>

                            {{-- Payment --}}
                            <td>
                                @if($record->payment_status)
                                    @php
                                        $payClass = match($record->payment_status) {
                                            'paid','verified' => 'badge-green',
                                            'pending'         => 'badge-yellow',
                                            'failed'          => 'badge-red',
                                            default           => 'badge-muted',
                                        };
                                    @endphp
                                    <span class="badge {{ $payClass }}">{{ ucfirst($record->payment_status) }}</span>
                                @else
                                    <span style="font-size:11px;color:var(--muted);">—</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td>
                                <span class="badge {{ $statusClass }}">{{ ucfirst($record->status) }}</span>
                            </td>

                            {{-- Submitted ── --}}
                            <td>
                                <div style="font-size:11px;color:var(--muted);">
                                    {{ \Illuminate\Support\Carbon::parse($record->submitted_at)->format('d M Y') }}
                                </div>
                                <div style="font-size:10.5px;color:var(--muted);">
                                    {{ \Illuminate\Support\Carbon::parse($record->submitted_at)->format('H:i') }}
                                </div>
                            </td>

                            {{-- Action --}}
                            <td>
                                <div class="act-wrap">
                                    <a href="{{ route($detailRoute, $record->record_id) }}" class="act-btn act-view">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="admin-pagination">{{ $records->links() }}</div>
    @endif
</div>
@endsection
