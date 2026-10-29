@extends('layouts.admin')
@section('title', 'Test Ride Bookings')

@section('styles')
<style>
/* ══════════════════════════════════════════════════════
   TEST RIDE BOOKINGS — Premium Admin UI
   Consistent with layouts/admin.blade.php design tokens
══════════════════════════════════════════════════════ */

/* ── KPI Cards ── */
.kpi-row {
    display: grid;
    grid-template-columns: repeat(5, minmax(0,1fr));
    gap: 14px;
    margin-bottom: 22px;
}
@media(max-width:1100px){ .kpi-row{ grid-template-columns:repeat(3,1fr); } }
@media(max-width:640px){  .kpi-row{ grid-template-columns:repeat(2,1fr); } }

.kpi-card {
    position: relative; overflow: hidden;
    background: var(--surface); border: 1px solid var(--line);
    border-radius: 16px; padding: 18px 18px 16px;
    display: flex; flex-direction: column; gap: 12px;
    transition: transform .22s, box-shadow .22s, border-color .22s;
    cursor: default;
}
.kpi-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 32px -10px rgba(124,58,237,.14);
    border-color: #ddd6fe;
}
/* Glow accent bar at top */
.kpi-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 3px;
    border-radius: 16px 16px 0 0;
    opacity: 0; transition: opacity .22s;
}
.kpi-card:hover::before { opacity: 1; }
.kpi-card.kc-purple::before { background: linear-gradient(90deg,#7c3aed,#a78bfa); }
.kpi-card.kc-amber::before  { background: linear-gradient(90deg,#d97706,#fbbf24); }
.kpi-card.kc-blue::before   { background: linear-gradient(90deg,#2563eb,#60a5fa); }
.kpi-card.kc-green::before  { background: linear-gradient(90deg,#16a34a,#4ade80); }
.kpi-card.kc-red::before    { background: linear-gradient(90deg,#dc2626,#f87171); }

.kpi-top {
    display: flex; align-items: flex-start;
    justify-content: space-between; gap: 8px;
}
.kpi-icon {
    width: 42px; height: 42px; border-radius: 12px;
    display: grid; place-items: center; font-size: 16px; flex-shrink: 0;
    transition: transform .22s;
}
.kpi-card:hover .kpi-icon { transform: scale(1.1) rotate(-4deg); }
.kpi-purple { background: #f3e8ff; color: #7c3aed; }
.kpi-amber  { background: #fffbeb; color: #d97706; }
.kpi-blue   { background: #eff6ff; color: #2563eb; }
.kpi-green  { background: #f0fdf4; color: #16a34a; }
.kpi-red    { background: #fff1f2; color: #dc2626; }

.kpi-badge {
    font-size: 9.5px; font-weight: 700; padding: 3px 9px;
    border-radius: 999px; letter-spacing: .02em; white-space: nowrap;
}
.kpi-badge.kb-purple { color: #7c3aed; background: #f3e8ff; border: 1px solid #e9d5ff; }
.kpi-badge.kb-amber  { color: #92400e; background: #fffbeb; border: 1px solid #fde68a; }
.kpi-badge.kb-blue   { color: #1d4ed8; background: #eff6ff; border: 1px solid #bfdbfe; }
.kpi-badge.kb-green  { color: #15803d; background: #f0fdf4; border: 1px solid #bbf7d0; }
.kpi-badge.kb-red    { color: #991b1b; background: #fff1f2; border: 1px solid #fecaca; }

.kpi-val {
    font-size: 32px; font-weight: 900; letter-spacing: -1.5px;
    color: var(--text); line-height: 1;
}
.kpi-lbl { font-size: 11.5px; font-weight: 600; color: var(--muted); margin-top: 2px; }
.kpi-foot {
    display: flex; align-items: center; gap: 5px;
    font-size: 10.5px; color: var(--muted);
    padding-top: 8px; border-top: 1px solid var(--line);
    margin-top: auto;
}
.kpi-foot i { font-size: 9px; }

/* ── Filter bar ── */
.filter-bar {
    display: flex; flex-wrap: wrap; align-items: center; gap: 9px;
    margin-bottom: 18px; padding: 12px 14px;
    border: 1px solid var(--line); border-radius: 13px;
    background: var(--surface);
}
.filter-bar input,
.filter-bar select {
    height: 38px; padding: 0 13px;
    background: var(--surface); border: 1px solid var(--line);
    border-radius: 9px; color: var(--text); font-size: 12px;
    font-family: inherit; outline: none; transition: border-color .15s, box-shadow .15s;
}
.filter-bar input { min-width: 230px; padding-left: 34px; }
.filter-bar select { min-width: 130px; cursor: pointer; }
.filter-bar input:focus,
.filter-bar select:focus { border-color: var(--purple-light); box-shadow: 0 0 0 3px rgba(139,92,246,.1); }
.filter-bar input::placeholder { color: #94a3b8; }
.search-wrap { position: relative; }
.search-wrap i { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 12px; pointer-events: none; }
.filter-clear {
    font-size: 11px; font-weight: 600; color: var(--muted);
    border: 1px solid var(--line); background: none; border-radius: 9px;
    padding: 0 12px; height: 38px; cursor: pointer; transition: all .15s;
}
.filter-clear:hover { border-color: #fecaca; color: #dc2626; background: #fff1f2; }

/* ── Quick-status select (styled) ── */
.qs-wrap { position: relative; display: inline-flex; align-items: center; }
.quick-status {
    appearance: none; -webkit-appearance: none;
    border-radius: 999px; padding: 5px 26px 5px 11px;
    font-size: 11px; font-weight: 700; cursor: pointer;
    border: 1.5px solid; outline: none; font-family: inherit;
    transition: box-shadow .15s;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%2394a3b8'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 8px center;
}
.quick-status:focus { box-shadow: 0 0 0 3px rgba(124,58,237,.15); }
/* status-specific colours applied via data-status attr */
.quick-status[data-status="pending"]   { color:#92400e; background-color:#fffbeb; border-color:#fde68a; }
.quick-status[data-status="confirmed"] { color:#1d4ed8; background-color:#eff6ff; border-color:#bfdbfe; }
.quick-status[data-status="completed"] { color:#15803d; background-color:#f0fdf4; border-color:#bbf7d0; }
.quick-status[data-status="cancelled"] { color:#991b1b; background-color:#fff1f2; border-color:#fecaca; }

/* ── Action buttons — premium style ── */
.act-wrap { display: flex; align-items: center; justify-content: flex-end; gap: 6px; }

.act-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    height: 34px; padding: 0 13px;
    border-radius: 9px; border: 1.5px solid transparent;
    font-size: 11.5px; font-weight: 700; font-family: inherit;
    cursor: pointer; text-decoration: none; white-space: nowrap;
    transition: all .2s cubic-bezier(.16,1,.3,1);
}
.act-btn i { font-size: 11px; }

/* View — indigo filled */
.act-view {
    background: linear-gradient(135deg,#eef2ff,#e0e7ff);
    color: #4338ca; border-color: #c7d2fe;
}
.act-view:hover {
    background: linear-gradient(135deg,#6366f1,#4f46e5);
    color: #fff; border-color: #6366f1;
    box-shadow: 0 6px 18px -6px rgba(99,102,241,.55);
    transform: translateY(-1px);
}

/* Delete — red ghost → filled on hover */
.act-delete {
    background: #fff1f2; color: #dc2626; border-color: #fecaca;
}
.act-delete:hover {
    background: linear-gradient(135deg,#ef4444,#dc2626);
    color: #fff; border-color: #ef4444;
    box-shadow: 0 6px 18px -6px rgba(239,68,68,.5);
    transform: translateY(-1px);
}

/* ── Reference link ── */
.ref-link {
    font-family: 'JetBrains Mono', ui-monospace, monospace;
    font-size: 11px; font-weight: 700; color: var(--purple);
    text-decoration: none; letter-spacing: .03em;
    padding: 3px 8px; border-radius: 7px;
    background: var(--purple-bg); border: 1px solid var(--purple-border);
    transition: background .15s, color .15s;
    display: inline-block;
}
.ref-link:hover { background: var(--purple); color: #fff; }

/* ── Moto thumb ── */
.moto-cell { display: flex; align-items: center; gap: 10px; }
.moto-thumb {
    width: 40px; height: 40px; flex-shrink: 0;
    border-radius: 10px; overflow: hidden;
    border: 1px solid var(--purple-border);
    background: var(--purple-bg);
    display: grid; place-items: center;
}
.moto-thumb img { width:100%;height:100%;object-fit:cover; }
.moto-name { font-size: 12px; font-weight: 600; color: var(--text); }
.moto-brand { font-size: 10px; color: var(--muted); margin-top: 1px; }

/* ── Date cell ── */
.date-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 9px; border-radius: 8px;
    background: #f8fafc; border: 1px solid var(--line);
    font-size: 11.5px; font-weight: 600; color: var(--subtext);
}
.date-pill i { color: var(--purple); font-size: 10px; }
.time-sub { font-size: 10.5px; color: var(--muted); margin-top: 3px; }
</style>
@endsection

@section('content')

{{-- ── Page Header ── --}}
<div class="page-header">
    <div>
        <h1>Test Ride Bookings</h1>
        <p>{{ $bookings->total() }} booking{{ $bookings->total() !== 1 ? 's' : '' }} total</p>
    </div>
</div>

{{-- ── KPI Cards ── --}}
<div class="kpi-row">

    <div class="kpi-card kc-purple">
        <div class="kpi-top">
            <div class="kpi-icon kpi-purple"><i class="fa-solid fa-calendar-check"></i></div>
            <span class="kpi-badge kb-purple">All Time</span>
        </div>
        <div>
            <div class="kpi-val">{{ $stats['total'] }}</div>
            <div class="kpi-lbl">Total Bookings</div>
        </div>
        <div class="kpi-foot"><i class="fa-solid fa-chart-simple"></i> All submitted requests</div>
    </div>

    <div class="kpi-card kc-amber">
        <div class="kpi-top">
            <div class="kpi-icon kpi-amber"><i class="fa-regular fa-clock"></i></div>
            <span class="kpi-badge kb-amber">Action Needed</span>
        </div>
        <div>
            <div class="kpi-val">{{ $stats['pending'] }}</div>
            <div class="kpi-lbl">Pending</div>
        </div>
        <div class="kpi-foot"><i class="fa-solid fa-triangle-exclamation"></i> Awaiting your response</div>
    </div>

    <div class="kpi-card kc-blue">
        <div class="kpi-top">
            <div class="kpi-icon kpi-blue"><i class="fa-solid fa-circle-check"></i></div>
            <span class="kpi-badge kb-blue">Scheduled</span>
        </div>
        <div>
            <div class="kpi-val">{{ $stats['confirmed'] }}</div>
            <div class="kpi-lbl">Confirmed</div>
        </div>
        <div class="kpi-foot"><i class="fa-solid fa-calendar"></i> Slots confirmed</div>
    </div>

    <div class="kpi-card kc-green">
        <div class="kpi-top">
            <div class="kpi-icon kpi-green"><i class="fa-solid fa-flag-checkered"></i></div>
            <span class="kpi-badge kb-green">Done</span>
        </div>
        <div>
            <div class="kpi-val">{{ $stats['completed'] }}</div>
            <div class="kpi-lbl">Completed</div>
        </div>
        <div class="kpi-foot"><i class="fa-solid fa-check"></i> Test rides done</div>
    </div>

    <div class="kpi-card kc-red">
        <div class="kpi-top">
            <div class="kpi-icon kpi-red"><i class="fa-solid fa-ban"></i></div>
            <span class="kpi-badge kb-red">Cancelled</span>
        </div>
        <div>
            <div class="kpi-val">{{ $stats['cancelled'] }}</div>
            <div class="kpi-lbl">Cancelled</div>
        </div>
        <div class="kpi-foot"><i class="fa-solid fa-xmark"></i> Not proceeding</div>
    </div>

</div>

{{-- ── Filters ── --}}
<form method="GET" class="filter-bar">
    <div class="search-wrap">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, reference…">
    </div>
    <select name="status">
        <option value="">All Statuses</option>
        @foreach(\App\Models\TestRideRequest::$statuses as $val => $label)
            <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    <input type="date" name="date" value="{{ request('date') }}" title="Filter by preferred date">
    <button type="submit" class="btn-primary" style="height:38px;padding:0 16px;font-size:12px;">
        <i class="fa-solid fa-filter"></i> Filter
    </button>
    @if(request()->hasAny(['search','status','date']))
        <a href="{{ route('admin.test-rides.index') }}" class="filter-clear">
            <i class="fa-solid fa-xmark" style="font-size:10px;margin-right:4px;"></i>Clear
        </a>
    @endif
</form>

{{-- ── Table ── --}}
<div class="admin-table-wrap">
    @if($bookings->isEmpty())
        <div class="empty-state">
            <i class="fa-solid fa-calendar-xmark"></i>
            <p>No bookings found. Adjust your filters or wait for customers to submit requests.</p>
        </div>
    @else
    <table class="admin-table">
        <thead>
            <tr>
                <th>Reference</th>
                <th>Motorcycle</th>
                <th>Customer</th>
                <th>Preferred Date</th>
                <th>Showroom</th>
                <th>Status</th>
                <th>Received</th>
                <th style="text-align:right;padding-right:18px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
            <tr>

                {{-- Reference --}}
                <td>
                    <a href="{{ route('admin.test-rides.show', $booking) }}" class="ref-link">
                        {{ $booking->booking_reference }}
                    </a>
                </td>

                {{-- Motorcycle --}}
                <td>
                    @if($booking->motorcycle)
                        <div class="moto-cell">
                            <div class="moto-thumb">
                                @if($booking->motorcycle->image_url)
                                    <img src="{{ $booking->motorcycle->image_url }}" alt="{{ $booking->motorcycle->model_name }}">
                                @else
                                    <i class="fa-solid fa-motorcycle" style="color:var(--purple);font-size:14px;"></i>
                                @endif
                            </div>
                            <div>
                                <div class="moto-name">{{ $booking->motorcycle->model_name }}</div>
                                <div class="moto-brand">{{ $booking->motorcycle->brand->name ?? '' }}</div>
                            </div>
                        </div>
                    @else
                        <span style="font-size:11px;color:var(--muted);">—</span>
                    @endif
                </td>

                {{-- Customer --}}
                <td>
                    <div style="font-size:12px;font-weight:700;color:var(--text);">{{ $booking->name }}</div>
                    <div style="font-size:10.5px;color:var(--purple);margin-top:1px;">
                        <a href="mailto:{{ $booking->email }}" style="color:var(--purple);text-decoration:none;">{{ $booking->email }}</a>
                    </div>
                    <div style="font-size:10.5px;color:var(--muted);">{{ $booking->phone }}</div>
                </td>

                {{-- Date/Time --}}
                <td>
                    @if($booking->preferred_date)
                        <div class="date-pill">
                            <i class="fa-regular fa-calendar"></i>
                            {{ $booking->preferred_date->format('d M Y') }}
                        </div>
                        @if($booking->preferred_time)
                            <div class="time-sub"><i class="fa-regular fa-clock" style="font-size:9px;color:var(--purple);margin-right:3px;"></i>{{ $booking->preferred_time }}</div>
                        @endif
                    @else
                        <span style="font-size:11px;color:var(--muted);font-style:italic;">Not specified</span>
                    @endif
                </td>

                {{-- Showroom --}}
                <td>
                    <span style="font-size:11.5px;color:var(--subtext);">
                        {{ $booking->showroom?->name ?? $booking->preferred_showroom ?? '—' }}
                    </span>
                </td>

                {{-- Status quick-change ── --}}
                <td>
                    <form method="POST" action="{{ route('admin.test-rides.update-status', $booking) }}">
                        @csrf @method('PATCH')
                        <select name="status"
                                class="quick-status"
                                data-status="{{ $booking->status }}"
                                onchange="this.form.submit()"
                                title="Change status">
                            @foreach(\App\Models\TestRideRequest::$statuses as $val => $label)
                                <option value="{{ $val }}" {{ $booking->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </form>
                </td>

                {{-- Received --}}
                <td>
                    <span style="font-size:11px;color:var(--muted);" title="{{ $booking->created_at->format('d M Y H:i') }}">
                        {{ $booking->created_at->diffForHumans() }}
                    </span>
                </td>

                {{-- Actions ── --}}
                <td>
                    <div class="act-wrap">
                        <a href="{{ route('admin.test-rides.show', $booking) }}"
                           class="act-btn act-view" title="View full details">
                            <i class="fa-solid fa-eye"></i>
                            View
                        </a>
                        @if(auth()->user()->isAdmin())
                        <form method="POST"
                              action="{{ route('admin.test-rides.destroy', $booking) }}"
                              onsubmit="return confirm('Delete booking {{ $booking->booking_reference }}?\n\nThis action cannot be undone.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="act-btn act-delete" title="Delete booking">
                                <i class="fa-solid fa-trash-can"></i>
                                Delete
                            </button>
                        </form>
                        @endif
                    </div>
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>

    @if($bookings->hasPages())
        <div class="admin-pagination">{{ $bookings->links() }}</div>
    @endif
    @endif
</div>

@endsection
