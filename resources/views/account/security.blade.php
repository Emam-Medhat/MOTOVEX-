@extends('layouts.account')
@section('title', 'Security')
@section('heading', 'Security')

@section('content')
<div class="grid gap-6 lg:grid-cols-2">
    <form method="POST" action="{{ route('account.security.update') }}"
          class="space-y-5 rounded-xl border border-[#292F2C] bg-[#181C1D] p-6">
        @csrf
        @method('PUT')

        <h2 class="text-sm font-bold uppercase tracking-wider">Change password</h2>

        <div>
            <label class="mb-1.5 block text-xs font-medium text-[#A1A7A2]">Current password</label>
            <input type="password" name="current_password" autocomplete="current-password"
                   class="w-full rounded-lg border bg-[#0B0D0E] px-4 py-3 text-sm outline-none transition {{ $errors->has('current_password') ? 'border-[#EF6464]' : 'border-[#292F2C] focus:border-[#B6FF3B]' }}">
            @error('current_password')<p class="mt-1 text-xs text-[#EF6464]">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="mb-1.5 block text-xs font-medium text-[#A1A7A2]">New password</label>
            <input type="password" name="password" autocomplete="new-password"
                   class="w-full rounded-lg border bg-[#0B0D0E] px-4 py-3 text-sm outline-none transition {{ $errors->has('password') ? 'border-[#EF6464]' : 'border-[#292F2C] focus:border-[#B6FF3B]' }}">
            <p class="mt-1 text-[11px] text-[#707873]">Minimum 8 characters with letters and numbers.</p>
            @error('password')<p class="mt-1 text-xs text-[#EF6464]">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="mb-1.5 block text-xs font-medium text-[#A1A7A2]">Confirm new password</label>
            <input type="password" name="password_confirmation" autocomplete="new-password"
                   class="w-full rounded-lg border border-[#292F2C] bg-[#0B0D0E] px-4 py-3 text-sm outline-none transition focus:border-[#B6FF3B]">
        </div>

        <div class="border-t border-[#292F2C] pt-5">
            <button type="submit" class="btn-primary">Update Password</button>
        </div>
    </form>

    <div class="space-y-4 rounded-xl border border-[#292F2C] bg-[#181C1D] p-6">
        <h2 class="text-sm font-bold uppercase tracking-wider">Account details</h2>
        <dl class="space-y-3 text-sm">
            <div class="flex items-center justify-between gap-4">
                <dt class="text-[#707873]">Email</dt>
                <dd class="truncate font-medium">{{ auth()->user()->email }}</dd>
            </div>
            <div class="flex items-center justify-between gap-4">
                <dt class="text-[#707873]">Account status</dt>
                <dd>
                    <span class="status-badge bg-[#45C486]/10 text-[#45C486]">Active</span>
                </dd>
            </div>
            <div class="flex items-center justify-between gap-4">
                <dt class="text-[#707873]">Member since</dt>
                <dd class="font-medium">{{ auth()->user()->created_at->format('d M Y') }}</dd>
            </div>
        </dl>

        <div class="rounded-lg border border-[#EF6464]/30 bg-[#EF6464]/5 p-4">
            <p class="text-xs font-bold text-[#EF6464]">Delete account</p>
            <p class="mt-1 text-[11px] leading-5 text-[#A1A7A2]">
                Account deletion is handled by customer care so that active enquiries and test rides are not lost.
                Contact us to request removal.
            </p>
        </div>
    </div>
</div>
@endsection
