@extends('layouts.admin')
@section('title', 'Enquiries')

@section('content')
@php
    $typeMeta = [
        'test_drive' => ['label' => 'Test Drive', 'class' => 'badge-primary'],
        'service'    => ['label' => 'Service',    'class' => 'badge-info'],
        'parts'      => ['label' => 'Parts',      'class' => 'badge-warning'],
        'general'    => ['label' => 'General',    'class' => 'badge-secondary'],
    ];
    $statusMeta = [
        'pending'   => ['label' => 'Pending',   'class' => 'badge-pending'],
        'contacted' => ['label' => 'Contacted', 'class' => 'badge-info'],
        'completed' => ['label' => 'Completed', 'class' => 'badge-success'],
        'cancelled' => ['label' => 'Cancelled', 'class' => 'badge-danger'],
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
        ['key' => null,        'label' => 'Total',     'value' => $stats['total'],     'tone' => 'primary', 'icon' => 'bi-inbox'],
        ['key' => 'pending',   'label' => 'Pending',   'value' => $stats['pending'],   'tone' => 'warning', 'icon' => 'bi-clock'],
        ['key' => 'contacted', 'label' => 'Contacted', 'value' => $stats['contacted'], 'tone' => 'info',    'icon' => 'bi-headset'],
        ['key' => 'completed', 'label' => 'Completed', 'value' => $stats['completed'], 'tone' => 'success', 'icon' => 'bi-check-circle'],
    ];

    $countLabel = fn (int $n) => $n === 1 ? 'enquiry' : 'enquiries';
@endphp

<div class="page-header">
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

    <a href="{{ route('admin.enquiries.export', $exportParams) }}" class="btn-primary-alt">
        <i class="bi bi-download"></i> Export CSV
    </a>
</div>

{{-- Summary cards --}}
<div class="row g-3 mb-4">
    @foreach($cards as $card)
        @php
            $isActive = $card['key'] === null ? empty($activeStatus) : $activeStatus === $card['key'];
            $href = $card['key']
                ? route('admin.enquiries.index', array_merge($baseFilters, ['status' => $card['key']]))
                : route('admin.enquiries.index', $baseFilters);
        @endphp
        <div class="col-xl-3 col-sm-6">
            <a href="{{ $href }}" class="text-decoration-none">
                <div class="stat-card {{ $isActive ? 'border-primary' : '' }}" style="height: 120px;">
                    <div class="stat-head">
                        <div class="stat-icon bg-light text-{{ $card['tone'] }}"><i class="bi {{ $card['icon'] }}"></i></div>
                        {{ $card['label'] }}
                    </div>
                    <h3 class="mt-2">{{ $card['value'] }}</h3>
                </div>
            </a>
        </div>
    @endforeach
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.enquiries.index') }}" class="filter-card border-0 shadow-sm">
    <div class="position-relative">
        <i class="bi bi-search position-absolute text-muted" style="left: 12px; top: 9px;"></i>
        <input type="text" name="search" value="{{ request('search') }}" class="filter-input ps-5" placeholder="Search by name, email or subject…">
    </div>

    <select name="status" class="filter-select" onchange="this.form.submit()">
        <option value="">All statuses</option>
        @foreach($statusMeta as $value => $meta)
            <option value="{{ $value }}" @selected($activeStatus === $value)>{{ $meta['label'] }}</option>
        @endforeach
    </select>

    <select name="enquiry_type" class="filter-select" onchange="this.form.submit()">
        <option value="">All types</option>
        @foreach($enquiryTypes as $type)
            <option value="{{ $type }}" @selected(request('enquiry_type') === $type)>
                {{ ucwords(str_replace('_', ' ', $type)) }}
            </option>
        @endforeach
    </select>

    <button type="submit" class="btn btn-primary-alt" style="height:38px;">Filter</button>

    @if($hasFilters)
        <a href="{{ route('admin.enquiries.index') }}" class="btn btn-link btn-sm text-decoration-none text-muted">Reset</a>
    @endif
</form>

<div class="panel border-0 shadow-sm">
    @if($enquiries->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-envelope-open text-muted display-1 opacity-25"></i>
            <p class="mt-3 text-muted">No enquiries found yet.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Sender</th>
                        <th>Subject</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Received</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($enquiries as $enquiry)
                        @php
                            $tm = $typeMeta[$enquiry->enquiry_type]
                                ?? ['label' => ucwords(str_replace('_', ' ', $enquiry->enquiry_type)), 'class' => 'badge-secondary'];
                            $sm = $statusMeta[$enquiry->status]
                                ?? ['label' => ucfirst($enquiry->status), 'class' => 'badge-pending'];
                            $parts = preg_split('/\s+/', trim($enquiry->name));
                            $initials = strtoupper(
                                substr($parts[0] ?? '', 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : '')
                            );
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="customer-avatar">{{ $initials }}</div>
                                    <div>
                                        <strong class="d-block">{{ $enquiry->name }}</strong>
                                        <small class="text-muted">{{ $enquiry->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="text-truncate d-inline-block" style="max-width: 200px;">{{ $enquiry->subject }}</span></td>
                            <td><span class="badge-alt {{ $tm['class'] }}">{{ $tm['label'] }}</span></td>
                            <td><span class="badge-alt {{ $sm['class'] }}">{{ $sm['label'] }}</span></td>
                            <td>
                                <div class="small fw-semibold">{{ $enquiry->created_at?->format('d M Y') }}</div>
                                <small class="text-muted">{{ $enquiry->created_at?->diffForHumans() }}</small>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="action-btn btn-view" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @can('delete', $enquiry)
                                        <form method="POST" action="{{ route('admin.enquiries.destroy', $enquiry) }}"
                                              onsubmit="return confirm('Delete this enquiry? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn btn-delete">
                                                <i class="bi bi-trash3"></i>
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
        <div class="mt-4">{{ $enquiries->links() }}</div>
    @endif
</div>
@endsection
