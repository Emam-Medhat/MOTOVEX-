@extends('layouts.app')

@section('title', 'Checkout | RIDEAXIS')

@section('content')
<div class="bg-white text-[#1e1f20]">
    <div class="mx-auto max-w-7xl px-5 py-16 lg:px-8">

        <nav class="mb-8 text-xs text-gray-400">
            <a href="{{ route('home') }}" class="hover:text-[#ff9aa9]">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('cart.index') }}" class="hover:text-[#ff9aa9]">Cart</a>
            <span class="mx-2">/</span>
            <span class="text-[#1e1f20]">Checkout</span>
        </nav>

        <h1 class="text-4xl font-black uppercase tracking-tighter md:text-5xl">Checkout</h1>
        <p class="mt-3 max-w-xl text-sm text-gray-500">
            Submit your details and our team will contact you to finalise this order.
        </p>

        <div class="mt-12 grid gap-10 lg:grid-cols-[1fr_360px]">
            {{-- Form --}}
            <form method="POST" action="{{ route('cart.place-order') }}"
                  class="rounded-3xl border border-gray-100 p-8 shadow-sm">
                @csrf
                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-gray-400">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required
                               class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition
                                      {{ $errors->has('name') ? 'border-red-500' : 'border-gray-200 focus:border-[#ff9aa9]' }}">
                        @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-gray-400">Email *</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required
                               class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition
                                      {{ $errors->has('email') ? 'border-red-500' : 'border-gray-200 focus:border-[#ff9aa9]' }}">
                        @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-gray-400">Phone *</label>
                        <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}" required
                               class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition
                                      {{ $errors->has('phone') ? 'border-red-500' : 'border-gray-200 focus:border-[#ff9aa9]' }}">
                        @error('phone')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-widest text-gray-400">Message</label>
                        <textarea name="message" rows="4" placeholder="Anything we should know about your order?"
                                  class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-[#ff9aa9]">{{ old('message') }}</textarea>
                        @error('message')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                <button type="submit" class="btn-primary mt-8 w-full sm:w-auto">Place Order Enquiry</button>
            </form>

            {{-- Summary --}}
            <aside class="h-fit rounded-3xl bg-[#f8f9fa] p-8">
                <h2 class="text-lg font-black uppercase tracking-tight">Your Order</h2>
                <ul class="mt-6 space-y-4">
                    @foreach($cartItems as $item)
                        @php($moto = $item['motorcycle'])
                        <li class="flex items-center justify-between gap-4 text-sm">
                            <span class="min-w-0 truncate text-gray-600">{{ $moto->model_name }}</span>
                            <span class="shrink-0 font-bold">{{ $moto->currency ?: 'USD' }} {{ number_format($moto->price ?? 0) }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-6 flex items-center justify-between border-t border-gray-200 pt-4">
                    <span class="text-sm text-gray-500">Total</span>
                    <span class="text-2xl font-black text-[#ff9aa9]">USD {{ number_format($total) }}</span>
                </div>
            </aside>
        </div>

    </div>
</div>
@endsection
