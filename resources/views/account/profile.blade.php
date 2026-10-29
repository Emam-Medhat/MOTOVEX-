@extends('layouts.account')
@section('title', 'Profile')
@section('heading', 'Profile')

@section('content')
<form method="POST" action="{{ route('account.profile.update') }}"
      class="space-y-5 rounded-xl border border-[#292F2C] bg-[#181C1D] p-6">
    @csrf
    @method('PUT')

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label class="mb-1.5 block text-xs font-medium text-[#A1A7A2]">Full name *</label>
            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                   class="w-full rounded-lg border bg-[#0B0D0E] px-4 py-3 text-sm outline-none transition {{ $errors->has('name') ? 'border-[#EF6464]' : 'border-[#292F2C] focus:border-[#B6FF3B]' }}">
            @error('name')<p class="mt-1 text-xs text-[#EF6464]">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="mb-1.5 block text-xs font-medium text-[#A1A7A2]">Mobile number</label>
            <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                   class="w-full rounded-lg border bg-[#0B0D0E] px-4 py-3 text-sm outline-none transition {{ $errors->has('phone') ? 'border-[#EF6464]' : 'border-[#292F2C] focus:border-[#B6FF3B]' }}">
            @error('phone')<p class="mt-1 text-xs text-[#EF6464]">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="mb-1.5 block text-xs font-medium text-[#A1A7A2]">Country</label>
            <input type="text" name="country" value="{{ old('country', auth()->user()->country) }}"
                   class="w-full rounded-lg border border-[#292F2C] bg-[#0B0D0E] px-4 py-3 text-sm outline-none transition focus:border-[#B6FF3B]">
        </div>

        <div>
            <label class="mb-1.5 block text-xs font-medium text-[#A1A7A2]">City</label>
            <input type="text" name="city" value="{{ old('city', auth()->user()->city) }}"
                   class="w-full rounded-lg border border-[#292F2C] bg-[#0B0D0E] px-4 py-3 text-sm outline-none transition focus:border-[#B6FF3B]">
        </div>
    </div>

    <div>
        <label class="mb-1.5 block text-xs font-medium text-[#A1A7A2]">Favourite brand</label>
        <select name="preferred_brand_id"
                class="w-full rounded-lg border border-[#292F2C] bg-[#0B0D0E] px-4 py-3 text-sm outline-none transition focus:border-[#B6FF3B]">
            <option value="">No preference</option>
            @foreach($brands as $brand)
                <option value="{{ $brand->id }}" @selected(old('preferred_brand_id', auth()->user()->preferred_brand_id) == $brand->id)>
                    {{ $brand->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <p class="mb-1.5 text-xs font-medium text-[#A1A7A2]">Email address</p>
        <p class="rounded-lg border border-[#292F2C] bg-[#0B0D0E]/60 px-4 py-3 text-sm text-[#707873]">{{ auth()->user()->email }}</p>
        <p class="mt-1 text-[11px] text-[#707873]">Email changes require customer care verification.</p>
    </div>

    <label class="flex items-center gap-2 text-xs text-[#A1A7A2]">
        <input type="checkbox" name="newsletter" value="1" @checked(old('newsletter', auth()->user()->newsletter_opted_in))
               class="rounded border-[#292F2C] bg-[#0B0D0E] text-[#B6FF3B] focus:ring-[#B6FF3B]">
        <span>Send me news, offers and new model announcements.</span>
    </label>

    <div class="border-t border-[#292F2C] pt-5">
        <button type="submit" class="btn-primary">Save Changes</button>
    </div>
</form>
@endsection
