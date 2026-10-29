@extends('layouts.admin')
@section('title', 'Enquiries')

@section('styles')
<style>
    /* Light page surface for this screen only (falls back to the default admin bg) */
    .admin-main:has(.enq-page) { background: #F4F6FB; }

    .enq-page {
        --p-ink: #172033;
        --p-sub: #64748B;
        --p-purple: #7C3AED;
        --p-purple-bg: #F5F3FF;
        --p-border: #E2E8F0;
    }

    /* ── Page header ── */
    .enq-head {
        display: flex; align-items: flex-end; justify-content: space-between;
        gap: 20px; flex-wrap: wrap; margin-bottom: 20px;
    }
    .enq-head h1 { font-size: 23px; font-weight: 800; letter-spacing: -.4px; color: var(--p-ink); margin: 0; }
    .enq-head p { font-size: 13px; color: var(--p-sub); margin: 5px 0 0; }
    .enq-export {
        display: inline-flex; align-items: center; gap: 8px; height: 40px; padding: 0 18px;
        border-radius: 10px; background: #fff; border: 1px solid var(--p-border); color: #334155;
        font-size: 13px; font-weight: 600;
        transition: border-color .15s, background .15s, color .15s;
    }
    .enq-export:hover { border-color: #DDD6FE; background: var(--p-purple-bg); color: var(--p-purple); }

    /* ── Summary cards ── */
    .enq-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; margin-bottom: 20px; }
    .enq-stat {
        display: flex; align-items: center; gap: 14px;
        padding: 16px 18px; background: #fff; border: 1px solid var(--p-border); border-radius: 14px;
        color: inherit; text-decoration: none;
        transition: border-color .15s, box-shadow .15s;
    }
    .enq-stat:hover { border-color: #DDD6FE; box-shadow: 0 4px 14px rgba(23, 32, 51, .06); }
    .enq-stat.is-active { border-color: #C4B5FD; box-shadow: 0 0 0 3px rgba(124, 58, 237, .10); }
    .enq-stat-icon {
        width: 40px; height: 40px; border-radius: 11px; flex-shrink: 0;
        display: grid; place-items: center; font-size: 15px;
    }
    .enq-stat-icon.i-purple { background: var(--p-purple-bg); color: var(--p-purple); border: 1px solid #DDD6FE; }
    .enq-stat-icon.i-amber  { background: #FFFBEB; color: #B45309; border: 1px solid #FDE68A; }
    .enq-stat-icon.i-blue   { background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; }
    .enq-stat-icon.i-green  { background: #F0FDF4; color: #15803D; border: 1px solid #BBF7D0; }
    .enq-stat-body { display: flex; flex-direction: column; min-width: 0; }
    .enq-stat-num { font-size: 21px; font-weight: 800; color: var(--p-ink); line-height: 1.1; }
    .enq-stat-label {
        font-size: 11.5px; font-weight: 600; letter-spacing: .3px; text-transform: uppercase;
        color: var(--p-sub); margin-top: 3px; white-space: nowrap;
    }

    /* ── Filters ── */
    .enq-filters {
        display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
        padding: 16px 18px; margin-bottom: 18px;
        background: #fff; border: 1px solid var(--p-border); border-radius: 14px;
    }
    .enq-search { position: relative; flex: 1 1 260px; min-width: 220px; }
    .enq-search i {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: #94A3B8; font-size: 12.5px; pointer-events: none;
    }
    .enq-search input {
        width: 100%; height: 40px; padding: 0 14px 0 38px;
        border: 1px solid var(--p-border); border-radius: 10px; background: #fff; color: var(--p-ink);
        font-size: 13px; font-family: inherit; outline: none;
        transition: border-color .15s, box-shadow .15s;
    }
    .enq-search input::placeholder { color: #94A3B8; }
    .enq-search input:focus { border-color: var(--p-purple); box-shadow: 0 0 0 3px rgba(124, 58, 237, .12); }

    .enq-select {
        height: 40px; padding: 0 34px 0 14px;
        border: 1px solid var(--p-border); border-radius: 10px;
        font-size: 13px; font-family: inherit; color: #334155;
        background: #fff no-repeat right 12px center;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-size: 14px;
        appearance: none; -webkit-appearance: none; outline: none; cursor: pointer;
        transition: border-color .15s, box-shadow .15s;
    }
    .enq-select:focus { border-color: var(--p-purple); box-shadow: 0 0 0 3px rgba(124, 58, 237, .12); }

    .enq-btn {
        display: inline-flex; align-items: center; gap: 7px; height: 40px; padding: 0 18px;
        border: 0; border-radius: 10px; background: var(--p-purple); color: #fff;
        font-size: 13px; font-weight: 700; font-family: inherit; cursor: pointer;
        transition: background .15s;
    }
    .enq-btn:hover { background: #6D28D9; }
    .enq-reset { display: inline-flex; align-items: center; gap: 6px; font-size: 12.5px; font-weight: 600; color: var(--p-sub); }
    .enq-reset:hover { color: #DC2626; }

    /* ── Table ── */
    .enq-table-wrap {
        background: #fff; border: 1px solid var(--p-border); border-radius: 16px;
        overflow: hidden; box-shadow: 0 1px 2px rgba(16, 24, 40, .04);
    }
    .enq-scroll { overflow-x: auto; }
    .enq-table { min-width: 820px; }
    .enq-table thead tr { background: #F8FAFC; border-bottom: 1px solid var(--p-border); }
    .enq-table th {
        padding: 13px 18px; text-align: left; white-space: nowrap;
        font-size: 11px; font-weight: 700; letter-spacing: .6px; text-transform: uppercase; color: #64748B;
    }
    .enq-table tbody tr { border-bottom: 0; }
    .enq-table tbody tr:hover { background: #FAFBFF; }
    .enq-table td { padding: 16px 18px; border-bottom: 1px solid #F1F5F9; color: #334155; }
    .enq-table tbody tr:last-child td { border-bottom: 0; }
    .enq-table .td-actions { gap: 8px; }
    .enq-table .td-actions form { display: flex; margin: 0; }

    .enq-sender { display: flex; align-items: center; gap: 11px; min-width: 0; }
    .enq-avatar {
        width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
        display: grid; place-items: center;
        background: var(--p-purple-bg); border: 1px solid #DDD6FE; color: var(--p-purple);
        font-size: 12px; font-weight: 700; letter-spacing: .3px;
    }
    .enq-sender-info { display: flex; flex-direction: column; min-width: 0; }
    .enq-name {
        font-size: 13.5px; font-weight: 600; color: #172033;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .enq-email {
        font-size: 12px; color: #64748B;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .enq-subject { max-width: 260px; }
    .enq-subject span {
        display: block; font-weight: 500; color: #334155;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .enq-date { white-space: nowrap; }
    .enq-date-main { display: block; font-size: 12.5px; font-weight: 600; color: #334155; }
    .enq-date-sub { display: block; font-size: 11.5px; color: #94A3B8; margin-top: 2px; }

    /* ── Action buttons ── */
    .enq-act {
        display: inline-flex; align-items: center; gap: 6px; height: 32px; padding: 0 12px;
        border-radius: 8px; border: 1px solid transparent;
        font-size: 12px; font-weight: 600; font-family: inherit; cursor: pointer;
        transition: background .15s, border-color .15s, color .15s;
    }
    .enq-act i { font-size: 11px; }
    .enq-act-view { color: #6D28D9; background: var(--p-purple-bg); border-color: #DDD6FE; }
    .enq-act-view:hover { background: #EDE9FE; border-color: #C4B5FD; }
    .enq-act-del { color: #B91C1C; background: #FEF2F2; border-color: #FECACA; }
    .enq-act-del:hover { background: #FEE2E2; border-color: #FCA5A5; }

    /* ── Empty state ── */
    .enq-empty { padding: 64px 24px; text-align: center; }
    .enq-empty-icon {
        width: 56px; height: 56px; border-radius: 14px; margin: 0 auto 16px;
        display: grid; place-items: center;
        background: var(--p-purple-bg); border: 1px solid #DDD6FE; color: var(--p-purple);
    }
    .enq-empty-icon i { font-size: 22px; }
    .enq-empty h4 { margin: 0; font-size: 15px; font-weight: 700; color: var(--p-ink); }
    .enq-empty p { margin: 8px 0 0; font-size: 13px; color: var(--p-sub); }
    .enq-empty a { display: inline-flex; align-items: center; gap: 7px; margin-top: 18px; font-size: 12.5px; font-weight: 600; color: var(--p-purple); }
    .enq-empty a:hover { color: #6D28D9; text-decoration: underline; }

    @media (max-width: 1100px) {
        .enq-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 640px) {
        .enq-stats { grid-template-columns: minmax(0, 1fr); }
        .enq-head { flex-direction: column; align-items: flex-start; }
        .enq-export { width: 100%; justify-content: center; }
        .enq-select, .enq-btn { flex: 1 1 auto; }
    }
</style>
@endsection

@section('content')
@php
    $typeMeta = [
        'test_drive' => ['label' => 'Test Drive', 'class' => 'badge-purple'],
        'service'    => ['label' => 'Service',    'class' => 'badge-blue'],
        'parts'      => ['label' => 'Parts',      'class' => 'badge-yellow'],
        'general'    => ['label' => 'General',    'class' => 'badge-muted'],
    ];
    $statusMeta = [
        'pending'   => ['label' => 'Pending',   'class' => 'badge-yellow'],
        'contacted' => ['label' => 'Contacted', 'class' => 'badge-blue'],
        'completed' => ['label' => 'Completed', 'class' => 'badge-green'],
        'cancelled' => ['label' => 'Cancelled', 'class' => 'badge-red'],
    ];

    $activeStatus = request('status');
    $baseFilters = array_filter([
        'search'       => request('search'),
        'enquiry_type' => request('enquiry_type'),
    ], fn ($value) => $value !== null && $value !== '');
    $hasFilters = $baseFilters !== [] || ! empty($activeStatus);

    $exportParams = $baseFilters;
    if ($activeStatus) {
        $exportParams['status'] = $activeStatus;
    }

    $cards = [
        ['key' => null,        'label' => 'Total',     'value' => $stats['total'],     'tone' => 'purple', 'icon' => 'fa-solid fa-inbox'],
        ['key' => 'pending',   'label' => 'Pending',   'value' => $stats['pending'],   'tone' => 'amber',  'icon' => 'fa-solid fa-clock'],
        ['key' => 'contacted', 'label' => 'Contacted', 'value' => $stats['contacted'], 'tone' => 'blue',   'icon' => 'fa-solid fa-headset'],
        ['key' => 'completed', 'label' => 'Completed', 'value' => $stats['completed'], 'tone' => 'green',  'icon' => 'fa-solid fa-circle-check'],
    ];

    $countLabel = fn (int $n) => $n === 1 ? 'enquiry' : 'enquiries';
@endphp

<div class="enq-page">

    {{-- Page header --}}
    <div class="enq-head">
        <div>
            <h1>Enquiries</h1>
            <p>
                @if($hasFilters)
                    {{ $enquiries->total() }} {{ $countLabel($enquiries->total()) }} matching your filters
                @else
                    {{ $enquiries->total() }} {{ $countLabel($enquiries->total()) }} in total
                @endif
            </p>
        </div>

        <a href="{{ route('admin.enquiries.export', $exportParams) }}" class="enq-export">
            <i class="fa-solid fa-file-arrow-down"></i> Export CSV
        </a>
    </div>

    {{-- Summary cards (click to filter by status) --}}
    <div class="enq-stats">
        @foreach($cards as $card)
            @php
                $isActive = $card['key'] === null ? empty($activeStatus) : $activeStatus === $card['key'];
                $href = $card['key']
                    ? route('admin.enquiries.index', array_merge($baseFilters, ['status' => $card['key']]))
                    : route('admin.enquiries.index', $baseFilters);
            @endphp
            <a href="{{ $href }}" class="enq-stat {{ $isActive ? 'is-active' : '' }}"
               @if($isActive) aria-current="true" @endif>
                <span class="enq-stat-icon i-{{ $card['tone'] }}"><i class="{{ $card['icon'] }}"></i></span>
                <span class="enq-stat-body">
                    <span class="enq-stat-num">{{ $card['value'] }}</span>
                    <span class="enq-stat-label">{{ $card['label'] }}</span>
                </span>
            </a>
        @endforeach
    </div>

    {{-- Search & filters (server-side) --}}
    <form method="GET" action="{{ route('admin.enquiries.index') }}" class="enq-filters">
        <div class="enq-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by name, email or subject…" autocomplete="off"
                   aria-label="Search enquiries">
        </div>

        <select name="status" class="enq-select" onchange="this.form.submit()" aria-label="Filter by status">
            <option value="">All statuses</option>
            @foreach($statusMeta as $value => $meta)
                <option value="{{ $value }}" @selected($activeStatus === $value)>{{ $meta['label'] }}</option>
            @endforeach
        </select>

        <select name="enquiry_type" class="enq-select" onchange="this.form.submit()" aria-label="Filter by type">
            <option value="">All types</option>
            @foreach($enquiryTypes as $type)
                <option value="{{ $type }}" @selected(request('enquiry_type') === $type)>
                    {{ ucwords(str_replace('_', ' ', $type)) }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="enq-btn"><i class="fa-solid fa-filter"></i> Filter</button>

        @if($hasFilters)
            <a href="{{ route('admin.enquiries.index') }}" class="enq-reset">
                <i class="fa-solid fa-xmark"></i> Reset
            </a>
        @endif
    </form>

    {{-- Table --}}
    <div class="enq-table-wrap">
        @if($enquiries->isEmpty())
            <div class="enq-empty">
                <div class="enq-empty-icon">
                    <i class="fa-solid {{ $hasFilters ? 'fa-magnifying-glass' : 'fa-envelope-open' }}"></i>
                </div>
                @if($hasFilters)
                    <h4>No matching enquiries</h4>
                    <p>No enquiries match your current search or filters.</p>
                    <a href="{{ route('admin.enquiries.index') }}">
                        <i class="fa-solid fa-rotate-left"></i> Clear all filters
                    </a>
                @else
                    <h4>No enquiries yet</h4>
                    <p>New enquiries submitted from the website will appear here.</p>
                @endif
            </div>
        @else
            <div class="enq-scroll">
                <table class="admin-table enq-table">
                    <thead>
                        <tr>
                            <th>Sender</th>
                            <th>Subject</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Received</th>
                            <th style="text-align:right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enquiries as $enquiry)
                            @php
                                $tm = $typeMeta[$enquiry->enquiry_type]
                                    ?? ['label' => ucwords(str_replace('_', ' ', $enquiry->enquiry_type)), 'class' => 'badge-muted'];
                                $sm = $statusMeta[$enquiry->status]
                                    ?? ['label' => ucfirst($enquiry->status), 'class' => 'badge-muted'];
                                $parts = preg_split('/\s+/', trim($enquiry->name));
                                $initials = strtoupper(
                                    substr($parts[0] ?? '', 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : '')
                                );
                            @endphp
                            <tr>
                                <td>
                                    <div class="enq-sender">
                                        <span class="enq-avatar">{{ $initials }}</span>
                                        <span class="enq-sender-info">
                                            <span class="enq-name">{{ $enquiry->name }}</span>
                                            <span class="enq-email">{{ $enquiry->email }}</span>
                                        </span>
                                    </div>
                                </td>
                                <td class="enq-subject"><span>{{ $enquiry->subject }}</span></td>
                                <td><span class="badge {{ $tm['class'] }}">{{ $tm['label'] }}</span></td>
                                <td><span class="badge {{ $sm['class'] }}">{{ $sm['label'] }}</span></td>
                                <td class="enq-date">
                                    <span class="enq-date-main">{{ $enquiry->created_at?->format('d M Y') }}</span>
                                    <span class="enq-date-sub">{{ $enquiry->created_at?->diffForHumans() }}</span>
                                </td>
                                <td>
                                    <div class="td-actions">
                                        <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="enq-act enq-act-view">
                                            <i class="fa-solid fa-eye"></i> View
                                        </a>
                                        @can('delete', $enquiry)
                                            <form method="POST" action="{{ route('admin.enquiries.destroy', $enquiry) }}"
                                                  onsubmit="return confirm('Delete this enquiry? This action cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="enq-act enq-act-del">
                                                    <i class="fa-solid fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="admin-pagination">{{ $enquiries->links() }}</div>
        @endif
    </div>
</div>
@endsection
