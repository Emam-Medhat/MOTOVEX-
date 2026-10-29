@extends('layouts.app')

@section('title', 'Your Cart | RIDEAXIS')

@section('content')
<div class="bg-white text-[#1e1f20]">
    <div class="mx-auto max-w-7xl px-5 py-16 lg:px-8">

        <nav class="mb-8 text-xs text-gray-400">
            <a href="{{ route('home') }}" class="hover:text-[#ff9aa9]">Home</a>
            <span class="mx-2">/</span>
            <span class="text-[#1e1f20]">Cart</span>
        </nav>

        <h1 class="text-4xl font-black uppercase tracking-tighter md:text-5xl">Your Cart</h1>

        @if($cartItems->isEmpty())
            <div class="mt-12 rounded-3xl bg-[#f8f9fa] px-6 py-20 text-center">
                <p class="text-gray-500">Your cart is empty.</p>
                <a href="{{ route('motorcycles.index') }}" class="btn-primary mt-6">Browse Motorcycles</a>
            </div>
        @else
            <div class="mt-12 grid gap-10 lg:grid-cols-[1fr_360px]">
                {{-- Items --}}
                <div class="space-y-4">
                    @foreach($cartItems as $item)
                        @php($moto = $item['motorcycle'])
                        <div class="flex items-center gap-5 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                            <div class="h-24 w-28 shrink-0 overflow-hidden rounded-xl bg-[#f8f9fa]">
                                <img src="{{ $moto->image_url ?: 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=400&q=80' }}"
                                     alt="{{ $moto->model_name }}" class="h-full w-full object-cover">
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-[#ff9aa9]">{{ $moto->brand->name }}</p>
                                <h3 class="truncate font-bold">{{ $moto->model_name }}</h3>
                                <p class="mt-1 text-xs text-gray-400">
                                    {{ $moto->horsepower }} hp · {{ number_format($moto->engine_capacity) }} cc
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-black text-[#ff9aa9]">
                                    {{ $moto->currency ?: 'USD' }} {{ number_format($moto->price ?? 0) }}
                                </p>
                                <form method="POST" action="{{ route('cart.remove', $moto) }}" class="mt-2">
                                    @csrf @method('DELETE')
                                    <button class="text-xs text-gray-400 hover:text-red-500">Remove</button>
                                </form>
                            </div>
                        </div>
                    @endforeach

                    <form method="POST" action="{{ route('cart.clear') }}" class="pt-2">
                        @csrf @method('DELETE')
                        <button class="text-xs text-gray-400 hover:text-red-500">Clear cart</button>
                    </form>
                </div>

                {{-- Summary --}}
                <aside class="h-fit rounded-3xl bg-[#f8f9fa] p-8">
                    <h2 class="text-lg font-black uppercase tracking-tight">Order Summary</h2>
                    <div class="mt-6 flex items-center justify-between text-sm">
                        <span class="text-gray-500">Items</span>
                        <span class="font-bold">{{ $cartItems->count() }}</span>
                    </div>
                    <div class="mt-4 flex items-center justify-between border-t border-gray-200 pt-4">
                        <span class="text-sm text-gray-500">Total</span>
                        <span class="text-2xl font-black text-[#ff9aa9]">USD {{ number_format($total) }}</span>
                    </div>
                    <a href="{{ route('cart.checkout') }}" class="btn-primary mt-8 w-full">Proceed to Checkout</a>
                    <a href="{{ route('motorcycles.index') }}" class="mt-3 block text-center text-xs text-gray-400 hover:text-[#ff9aa9]">
                        Continue shopping
                    </a>
                </aside>
            </div>
        @endif

    </div>
</div>
@endsection
