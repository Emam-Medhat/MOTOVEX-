@extends('layouts.auth')
@section('title', 'Forgot Password')

@section('content')
<div class="rounded-xl border border-[#292F2C] bg-[#181C1D] p-8">
    <h1 class="text-lg font-bold">Reset your password</h1>
    <p class="mt-1 text-xs text-[#707873]">
        Enter your email and we will send you a reset link. The link stays valid for one hour.
    </p>

    <form method="POST" action="{{ route('password.email') }}" class="mt-6" novalidate>
        @csrf

        <div class="mb-5">
            <label class="mb-1.5 block text-xs font-medium text-[#A1A7A2]" for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" autofocus
                   class="w-full rounded-lg border bg-[#0B0D0E] px-4 py-3 text-sm outline-none transition
                          {{ $errors->has('email') ? 'border-[#EF6464]' : 'border-[#292F2C] focus:border-[#B6FF3B]' }}">
            @error('email')<p class="mt-1 text-xs text-[#EF6464]">{{ $message }}</p>@enderror
        </div>

        <button type="submit"
                class="w-full rounded-lg bg-[#B6FF3B] py-3 text-sm font-bold text-[#0B0D0E] transition hover:bg-[#A0E92E]">
            Send Reset Link
        </button>
    </form>

    <p class="mt-6 border-t border-[#292F2C] pt-5 text-center text-xs text-[#707873]">
        <a href="{{ route('login') }}" class="font-semibold text-[#B6FF3B] hover:underline">← Back to sign in</a>
    </p>
</div>
@endsection
