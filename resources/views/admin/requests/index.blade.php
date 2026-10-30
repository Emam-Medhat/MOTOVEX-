@extends('layouts.admin')
@section('title', 'Customer Requests')

@section('content')
<div class="page-header">
    <div>
        <h1>Customer Requests</h1>
        <p>{{ $records->total() }} requests matching your access and filters</p>
    </div>
</div>

<form method="GET" class="filter-bar">
    <input type="search" name="search" value="{{ request('search') }}" placeholder="Search reference, customer, motorcycle">
    <select name="type">
        <option value="">All request types</option>
        @foreach(['purchase' => 'Sales', 'rental' => 'Rentals', 'test_ride' => 'Test rides', 'product' => 'Product enquiries', 'general' => 'General', 'test_drive' => 'Test drive enquiries', 'service' => 'Service', 'parts' => 'Parts'] as $value => $label)
            <option value="{{ $value }}" @selected(request('type') === $value)>{{ $label }}</option>
        @endforeach
    </select>
    <select name="status">
        <option value="">All statuses</option>
        @foreach(['pending', 'contacted', 'confirmed', 'completed', 'cancelled'] as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select>
    @if(auth()->user()->isAdmin())
        <select name="manager_id">
            <option value="">All managers</option>
            @foreach($managers as $manager)
                <option value="{{ $manager->id }}" @selected((string) request('manager_id') === (string) $manager->id)>{{ $manager->name }}</option>
            @endforeach
        </select>
    @endif
    <select name="motorcycle_id">
        <option value="">All motorcycles</option>
        @foreach($motorcycles as $motorcycle)
            <option value="{{ $motorcycle->id }}" @selected((string) request('motorcycle_id') === (string) $motorcycle->id)>{{ $motorcycle->model_name }}</option>
        @endforeach
    </select>
    <input type="date" name="date_from" value="{{ request('date_from') }}" aria-label="Submitted from">
    <input type="date" name="date_to" value="{{ request('date_to') }}" aria-label="Submitted to">
    <button type="submit" class="btn-primary">Filter</button>
    @if(request()->query())
        <a href="{{ route('admin.requests.index') }}" class="filter-clear">Clear</a>
    @endif
</form>

<div class="admin-table-wrap">
    @if($records->isEmpty())
        <div class="empty-state"><i class="fa-solid fa-inbox"></i><p>No customer requests match these filters.</p></div>
    @else
        <table class="admin-table">
            <thead>
                <tr><th>Reference</th><th>Motorcycle</th><th>Customer</th><th>Request</th><th>Dates / Price</th><th>Payment</th><th>Status</th><th>Submitted</th></tr>
            </thead>
            <tbody>
                @foreach($records as $record)
                    @php
                        $detailRoute = match($record->request_type) {
                            'purchase' => 'admin.orders.show',
                            'rental' => 'admin.rentals.show',
                            'test_ride' => 'admin.test-rides.show',
                            default => 'admin.enquiries.show',
                        };
                        $reference = $record->reference ?: 'REQ-' . $record->record_id;
                        $image = $record->image_path ? asset('storage/' . $record->image_path) : ($record->image_url ? asset($record->image_url) : null);
                    @endphp
                    <tr>
                        <td><a href="{{ route($detailRoute, $record->record_id) }}">{{ $reference }}</a></td>
                        <td>
                            @if($image)<img src="{{ $image }}" alt="" style="width:48px;height:36px;object-fit:cover;border-radius:6px">@endif
                            {{ $record->motorcycle_name ?: '—' }}
                        </td>
                        <td>{{ $record->customer_name }}<br><small>{{ $record->customer_email }} · {{ $record->customer_phone }}</small></td>
                        <td>{{ ucwords(str_replace('_', ' ', $record->request_type)) }}</td>
                        <td>
                            @if($record->rental_start_date)
                                {{ $record->rental_start_date }} – {{ $record->rental_end_date }}<br>
                                {{ $record->rental_days }} days · {{ $record->currency }} {{ number_format($record->rental_price_per_day, 2) }}/day
                            @elseif($record->total_amount !== null)
                                {{ $record->currency }} {{ number_format($record->total_amount, 2) }}
                            @elseif($record->requested_time)
                                {{ $record->requested_time }}
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ $record->payment_status ? ucfirst($record->payment_status) : '—' }}</td>
                        <td>{{ ucfirst($record->status) }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($record->submitted_at)->format('d M Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="admin-pagination">{{ $records->links() }}</div>
    @endif
</div>
@endsection
