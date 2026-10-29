@extends('layouts.account')
@section('title', 'My Enquiries')
@section('heading', 'My Enquiries')

@section('content')
<div class="overflow-hidden rounded-xl border border-[#292F2C] bg-[#181C1D]">
    @if($enquiries->isEmpty())
        <div class="px-5 py-16 text-center">
            <p class="text-sm text-[#707873]">No enquiries yet.</p>
            <a href="{{ route('contact') }}" class="btn-primary mt-4">Send an Enquiry</a>
        </div>
    @else
        <div class="divide-y divide-[#292F2C]">
            @foreach($enquiries as $enquiry)
                <article class="px-5 py-5">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h2 class="text-sm font-bold">{{ $enquiry->subject }}</h2>
                        <span @class([
                            'status-badge',
                            'bg-[#F4B740]/10 text-[#F4B740]' => $enquiry->status === 'pending',
                            'bg-[#B6FF3B]/10 text-[#B6FF3B]' => $enquiry->status === 'contacted',
                            'bg-[#45C486]/10 text-[#45C486]' => $enquiry->status === 'completed',
                            'bg-[#EF6464]/10 text-[#EF6464]' => $enquiry->status === 'cancelled',
                        ])>
                            {{ $enquiry->status }}
                        </span>
                    </div>
                    <p class="mt-2 text-sm leading-6 text-[#A1A7A2]">{{ $enquiry->message }}</p>
                    <p class="mt-3 text-[11px] uppercase tracking-wider text-[#707873]">
                        {{ ucfirst(str_replace('_', ' ', $enquiry->enquiry_type)) }} ·
                        Ref #{{ str_pad($enquiry->id, 5, '0', STR_PAD_LEFT) }} ·
                        {{ $enquiry->created_at->format('d M Y, H:i') }}
                    </p>
                </article>
            @endforeach
        </div>
        <div class="border-t border-[#292F2C] px-5 py-4">{{ $enquiries->links() }}</div>
    @endif
</div>
@endsection
