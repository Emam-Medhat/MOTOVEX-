@extends('layouts.admin')
@section('title', $promotion->title)

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <a href="{{ route('admin.promotions.index') }}" class="text-xs text-gray-500 hover:text-white">← Promotions</a>
        <h1 class="mt-2 text-2xl font-black">{{ $promotion->title }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ $promotion->brand ? $promotion->brand->name : 'Global Group' }}</p>
    </div>
    <div class="flex gap-2">
        @can('update', $promotion)
        <a href="{{ route('admin.promotions.edit', $promotion) }}" class="rounded-lg bg-[#a3e635] px-4 py-2 text-xs font-bold text-black hover:bg-[#bef264]">Edit</a>
        @endcan
        @can('delete', $promotion)
        <form method="POST" action="{{ route('admin.promotions.destroy', $promotion) }}"
              onsubmit="return confirm('Delete this promotion?')">
            @csrf @method('DELETE')
            <button class="rounded-lg border border-red-500/40 px-4 py-2 text-xs text-red-400 hover:bg-red-500/10">Delete</button>
        </form>
        @endcan
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-3">
    <div class="overflow-hidden rounded-xl border border-gray-800 bg-[#141518] lg:col-span-1">
        <img src="{{ $promotion->image_url ?: 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=80' }}"
             alt="{{ $promotion->title }}"
             class="aspect-[4/3] w-full object-cover">
    </div>
    <div class="rounded-xl border border-gray-800 bg-[#141518] p-6 lg:col-span-2">
        <span class="rounded-full px-2.5 py-1 text-[10px] font-bold
            {{ $promotion->is_active ? 'bg-green-500/10 text-green-400' : 'bg-red-500/10 text-red-400' }}">
            {{ $promotion->is_active ? 'Active' : 'Inactive' }}
        </span>
        <p class="mt-4 text-sm leading-6 text-gray-400">{{ $promotion->description }}</p>
        <dl class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach([
                'Brand' => $promotion->brand ? $promotion->brand->name : 'Global Group',
                'Starts' => $promotion->start_date?->format('d M Y') ?: '—',
                'Ends' => $promotion->end_date?->format('d M Y') ?: '—',
                'Slug' => $promotion->slug,
            ] as $label => $value)
                <div>
                    <dt class="text-[10px] uppercase tracking-widest text-gray-500">{{ $label }}</dt>
                    <dd class="mt-1 font-bold">{{ $value }}</dd>
                </div>
            @endforeach
        </dl>
    </div>
</div>
@endsection
