@extends('layouts.account')

@section('title', 'Profile')
@section('heading', 'Profile')

@section('content')

  <form method="POST" action="{{ route('account.profile.update') }}">
    @csrf
    @method('PUT')

    {{-- ─── Personal Information card ─── --}}
    <div class="ac-card" style="margin-bottom:1.5rem;">
      <div class="ac-card-header">
        <div class="ac-card-title">
          <i class="fa-solid fa-user"></i>
          Personal Information
          <small style="font-size:.72rem;color:#94a3b8;font-weight:400;margin-left:.25rem;">المعلومات الشخصية</small>
        </div>
      </div>
      <div class="ac-card-body">

        {{-- Name + Phone --}}
        <div class="ac-form-grid-2" style="margin-bottom:1.25rem;">
          <div>
            <label for="name" class="ac-label">
              Full Name <span style="color:#ef4444;">*</span>
            </label>
            <input
              id="name"
              type="text"
              name="name"
              value="{{ old('name', auth()->user()->name) }}"
              class="ac-input {{ $errors->has('name') ? 'is-error' : '' }}"
              placeholder="Your full name"
              required
            />
            @error('name')
              <p class="ac-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
            @enderror
          </div>
          <div>
            <label for="phone" class="ac-label">Phone Number</label>
            <input
              id="phone"
              type="tel"
              name="phone"
              value="{{ old('phone', auth()->user()->phone) }}"
              class="ac-input {{ $errors->has('phone') ? 'is-error' : '' }}"
              placeholder="+971 50 000 0000"
            />
            @error('phone')
              <p class="ac-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
            @enderror
          </div>
        </div>

        {{-- Country + City --}}
        <div class="ac-form-grid-2" style="margin-bottom:1.25rem;">
          <div>
            <label for="country" class="ac-label">Country</label>
            <input
              id="country"
              type="text"
              name="country"
              value="{{ old('country', auth()->user()->country) }}"
              class="ac-input {{ $errors->has('country') ? 'is-error' : '' }}"
              placeholder="e.g. Saudi Arabia"
            />
            @error('country')
              <p class="ac-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
            @enderror
          </div>
          <div>
            <label for="city" class="ac-label">City</label>
            <input
              id="city"
              type="text"
              name="city"
              value="{{ old('city', auth()->user()->city) }}"
              class="ac-input {{ $errors->has('city') ? 'is-error' : '' }}"
              placeholder="e.g. Riyadh"
            />
            @error('city')
              <p class="ac-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
            @enderror
          </div>
        </div>

        {{-- Email (read-only) --}}
        <div>
          <label for="email" class="ac-label">Email Address</label>
          <input
            id="email"
            type="email"
            value="{{ auth()->user()->email }}"
            class="ac-input"
            readonly
            aria-describedby="email-note"
          />
          <p class="ac-info-note" id="email-note">
            <i class="fa-solid fa-circle-info"></i>
            Your email address cannot be changed. Contact support if you need to update it.
          </p>
        </div>

      </div>
    </div>

    {{-- ─── Preferences card ─── --}}
    <div class="ac-card" style="margin-bottom:1.75rem;">
      <div class="ac-card-header">
        <div class="ac-card-title">
          <i class="fa-solid fa-sliders"></i>
          Preferences
          <small style="font-size:.72rem;color:#94a3b8;font-weight:400;margin-left:.25rem;">التفضيلات</small>
        </div>
      </div>
      <div class="ac-card-body">

        {{-- Preferred brand --}}
        <div style="margin-bottom:1.25rem;">
          <label for="preferred_brand_id" class="ac-label">Preferred Brand</label>
          <div style="position:relative;">
            <select
              id="preferred_brand_id"
              name="preferred_brand_id"
              class="ac-input {{ $errors->has('preferred_brand_id') ? 'is-error' : '' }}"
              style="appearance:none;-webkit-appearance:none;padding-right:2.5rem;cursor:pointer;"
            >
              <option value="">— No preference —</option>
              @foreach($brands as $brand)
                <option
                  value="{{ $brand->id }}"
                  {{ old('preferred_brand_id', auth()->user()->preferred_brand_id) == $brand->id ? 'selected' : '' }}
                >
                  {{ $brand->name }}
                </option>
              @endforeach
            </select>
            <i class="fa-solid fa-chevron-down" style="position:absolute;right:.95rem;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.7rem;pointer-events:none;"></i>
          </div>
          @error('preferred_brand_id')
            <p class="ac-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
          @enderror
        </div>

        {{-- Newsletter --}}
        <div>
          <label class="ac-label" style="margin-bottom:.6rem;">Email Preferences</label>
          <div class="ac-toggle-row">
            <input
              type="checkbox"
              id="newsletter"
              name="newsletter"
              value="1"
              class="ac-toggle-check"
              {{ old('newsletter', auth()->user()->newsletter_opted_in) ? 'checked' : '' }}
            />
            <div>
              <label for="newsletter" class="ac-toggle-label">Subscribe to Newsletter</label>
              <div class="ac-toggle-sub">
                Receive updates on new motorcycles, promotions, and events from MOTOVEX.
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    {{-- ─── Submit ─── --}}
    <div style="display:flex;align-items:center;gap:.85rem;flex-wrap:wrap;">
      <button type="submit" class="ac-btn-primary">
        <i class="fa-solid fa-floppy-disk"></i>
        Save Changes
      </button>
      <a href="{{ route('account.dashboard') }}" class="ac-btn-ghost">
        Cancel
      </a>
    </div>

  </form>

@endsection
