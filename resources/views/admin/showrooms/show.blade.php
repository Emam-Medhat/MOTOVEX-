@extends('layouts.admin')
@section('title', $showroom->name)

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <a href="{{ route('admin.showrooms.index') }}" class="text-xs text-gray-500 hover:text-white">← Showrooms</a>
        <h1 class="mt-2 text-2xl font-black">{{ $showroom->name }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ $showroom->location }}</p>
    </div>
    <div class="flex gap-2">
        @can('update', $showroom)
        <a href="{{ route('admin.showrooms.edit', $showroom) }}" class="rounded-lg bg-[#a3e635] px-4 py-2 text-xs font-bold text-black hover:bg-[#bef264]">Edit</a>
        @endcan
        @can('delete', $showroom)
        <form method="POST" action="{{ route('admin.showrooms.destroy', $showroom) }}"
              onsubmit="return confirm('Delete this showroom?')">
            @csrf @method('DELETE')
            <button class="rounded-lg border border-red-500/40 px-4 py-2 text-xs text-red-400 hover:bg-red-500/10">Delete</button>
        </form>
        @endcan
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-3">
    <div class="overflow-hidden rounded-xl border border-gray-800 bg-[#141518] lg:col-span-1">
        <img src="{{ $showroom->image_url ?: 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=80' }}"
             alt="{{ $showroom->name }}"
             class="aspect-[4/3] w-full object-cover">
    </div>
    <div class="rounded-xl border border-gray-800 bg-[#141518] p-6 lg:col-span-2">
        <span class="rounded-full px-2.5 py-1 text-[10px] font-bold
            {{ $showroom->is_active ? 'bg-green-500/10 text-green-400' : 'bg-red-500/10 text-red-400' }}">
            {{ $showroom->is_active ? 'Active' : 'Inactive' }}
        </span>
        <dl class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach([
                'Brand Focus' => $showroom->brand ? $showroom->brand->name : 'Multi-brand Group',
                'Location' => $showroom->location,
                'Phone' => $showroom->phone ?: '—',
                'Email' => $showroom->email ?: '—',
                'Opening Hours' => $showroom->opening_hours ?: '—',
                'Slug' => $showroom->slug,
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
