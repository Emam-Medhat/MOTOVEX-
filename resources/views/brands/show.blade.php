@extends('layouts.app')
@section('title', $brand->name)

@section('content')
<div class="mx-auto max-w-7xl px-6 py-16">

    {{-- Breadcrumb --}}
    <nav class="mb-8 text-xs text-gray-500">
        <a href="{{ route('brands.index') }}" class="hover:text-white">Brands</a>
        <span class="mx-2">/</span>
        <span class="text-gray-300">{{ $brand->name }}</span>
    </nav>

    {{-- Brand header --}}
    <div class="mb-12 grid gap-8 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <p class="text-xs font-bold uppercase tracking-[0.4em] text-[#a3e635]">{{ $brand->country }}</p>
            <h1 class="mt-2 text-5xl font-black">{{ $brand->name }}</h1>
            <p class="mt-4 max-w-2xl leading-7 text-gray-400">{{ $brand->description }}</p>
            @if($brand->website_url)
                <a href="{{ $brand->website_url }}" target="_blank" rel="noopener"
                   class="mt-5 inline-flex items-center gap-2 text-sm text-[#a3e635] hover:underline">
                    Visit official website →
                </a>
            @endif
        </div>
        <div class="rounded-xl border border-gray-800 bg-[#141518] p-6">
            <p class="text-xs uppercase tracking-widest text-gray-500">Quick Stats</p>
            <div class="mt-4 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Country</span>
                    <span class="font-medium">{{ $brand->country ?? '—' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400">Models available</span>
                    <span class="font-bold text-[#a3e635]">{{ $motorcycles->total() }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Motorcycles --}}
    <div class="mb-6 flex items-center justify-between border-t border-gray-800 pt-10">
        <h2 class="text-xl font-bold">Available Models</h2>
        <a href="{{ route('motorcycles.index', ['brand' => $brand->id]) }}"
           class="text-sm text-gray-400 hover:text-white">Filter in catalogue →</a>
    </div>

    @if($motorcycles->isEmpty())
        <div class="py-16 text-center text-gray-500">No motorcycles available for this brand yet.</div>
    @else
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($motorcycles as $moto)
            <a href="{{ route('motorcycles.show', $moto->slug) }}"
               class="group overflow-hidden rounded-xl border border-gray-800 bg-[#141518] transition hover:border-[#a3e635]/50">
                <div class="overflow-hidden">
                    <img src="{{ $moto->image_url ?: 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=600&q=80' }}"
                         alt="{{ $moto->model_name }}"
                         class="h-48 w-full object-cover transition duration-500 group-hover:scale-105">
                </div>
                <div class="p-5">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-bold group-hover:text-[#a3e635] transition">{{ $moto->model_name }}</h3>
                        <span class="shrink-0 rounded bg-gray-800 px-2 py-0.5 text-[10px] uppercase text-gray-400">{{ $moto->category }}</span>
                    </div>
                    <p class="mt-2 text-xs leading-5 text-gray-500 line-clamp-2">{{ $moto->description }}</p>
                    <div class="mt-4 flex gap-3 text-xs text-gray-400">
                        <span class="rounded bg-gray-800 px-2 py-1">{{ $moto->horsepower }} hp</span>
                        <span class="rounded bg-gray-800 px-2 py-1">{{ number_format($moto->engine_capacity) }} cc</span>
                        <span class="rounded bg-gray-800 px-2 py-1">{{ $moto->year }}</span>
                    </div>
                    @if($moto->price)
                        <p class="mt-4 font-bold text-white">${{ number_format($moto->price) }}</p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
        <div class="mt-8">{{ $motorcycles->links() }}</div>
    @endif
</div>
@endsection
