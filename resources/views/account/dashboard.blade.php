@extends('layouts.account')
@section('title', 'Overview')
@section('heading', 'Overview')

@section('content')
<div class="grid gap-4 sm:grid-cols-3">
    @foreach([
        ['Total enquiries', $stats['total']],
        ['Awaiting contact', $stats['open']],
        ['Test rides', $stats['test_rides']],
    ] as [$label, $value])
        <div class="rounded-xl border border-[#292F2C] bg-[#181C1D] p-5">
            <p class="text-[10px] font-bold uppercase tracking-widest text-[#707873]">{{ $label }}</p>
            <p class="mt-2 text-3xl font-black tabular-nums text-[#B6FF3B]">{{ $value }}</p>
        </div>
    @endforeach
</div>

<div class="mt-6 rounded-xl border border-[#292F2C] bg-[#181C1D]">
    <div class="flex items-center justify-between border-b border-[#292F2C] px-5 py-4">
        <h2 class="text-sm font-bold uppercase tracking-wider">Recent activity</h2>
        <a href="{{ route('account.enquiries') }}" class="text-xs text-[#A1A7A2] transition hover:text-[#B6FF3B]">View all →</a>
    </div>

    @if($enquiries->isEmpty())
        <div class="px-5 py-12 text-center">
            <p class="text-sm text-[#707873]">You haven't sent any enquiries yet.</p>
            <a href="{{ route('motorcycles.index') }}" class="btn-primary mt-4">Browse Motorcycles</a>
        </div>
    @else
        <ul class="divide-y divide-[#292F2C]">
            @foreach($enquiries as $enquiry)
                <li class="flex items-start justify-between gap-4 px-5 py-4">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold">{{ $enquiry->subject }}</p>
                        <p class="mt-0.5 text-xs text-[#707873]">
                            {{ ucfirst(str_replace('_', ' ', $enquiry->enquiry_type)) }} ·
                            {{ $enquiry->created_at->format('d M Y') }}
                        </p>
                    </div>
                    <span @class([
                        'status-badge shrink-0',
                        'bg-[#F4B740]/10 text-[#F4B740]' => $enquiry->status === 'pending',
                        'bg-[#B6FF3B]/10 text-[#B6FF3B]' => $enquiry->status === 'contacted',
                        'bg-[#45C486]/10 text-[#45C486]' => $enquiry->status === 'completed',
                        'bg-[#EF6464]/10 text-[#EF6464]' => $enquiry->status === 'cancelled',
                    ])>
                        {{ $enquiry->status }}
                    </span>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
