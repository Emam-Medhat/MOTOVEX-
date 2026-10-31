@extends('layouts.auth')
@section('title', __('auth.reset_password_title'))

@section('content')
<div class="rounded-xl border border-[#292F2C] bg-[#181C1D] p-8">
    <h1 class="text-lg font-bold">{{ __('auth.reset_password_heading') }}</h1>

    <form method="POST" action="{{ route('password.store') }}" class="mt-6 space-y-4" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label class="mb-1.5 block text-xs font-medium text-[#A1A7A2]" for="email">{{ __('auth.email_label') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email', $email) }}" autocomplete="email"
                   class="w-full rounded-lg border bg-[#0B0D0E] px-4 py-3 text-sm outline-none transition
                          {{ $errors->has('email') ? 'border-[#EF6464]' : 'border-[#292F2C] focus:border-[#B6FF3B]' }}">
            @error('email')<p class="mt-1 text-xs text-[#EF6464]">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="mb-1.5 block text-xs font-medium text-[#A1A7A2]" for="password">{{ __('auth.new_password') }}</label>
            <input id="password" name="password" type="password" autocomplete="new-password"
                   class="w-full rounded-lg border bg-[#0B0D0E] px-4 py-3 text-sm outline-none transition
                          {{ $errors->has('password') ? 'border-[#EF6464]' : 'border-[#292F2C] focus:border-[#B6FF3B]' }}">
            @error('password')<p class="mt-1 text-xs text-[#EF6464]">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="mb-1.5 block text-xs font-medium text-[#A1A7A2]" for="password_confirmation">{{ __('auth.confirm_password') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                   class="w-full rounded-lg border border-[#292F2C] bg-[#0B0D0E] px-4 py-3 text-sm outline-none transition focus:border-[#B6FF3B]">
        </div>

        <button type="submit"
                class="w-full rounded-lg bg-[#B6FF3B] py-3 text-sm font-bold text-[#0B0D0E] transition hover:bg-[#A0E92E]">
            {{ __('auth.reset_password_btn') }}
        </button>
    </form>
</div>
@endsection
