@extends('layouts.account')

@section('title', 'Security')
@section('heading', 'Security')

@section('content')

  {{-- ─── Account details card ─── --}}
  <div class="ac-card" style="margin-bottom:1.5rem;">
    <div class="ac-card-header">
      <div class="ac-card-title">
        <i class="fa-solid fa-circle-info"></i>
        Account Details
        <small style="font-size:.72rem;color:#94a3b8;font-weight:400;margin-left:.25rem;">تفاصيل الحساب</small>
      </div>
    </div>
    <div class="ac-card-body">
      <div class="ac-info-list">
        <div class="ac-info-row">
          <span class="ac-info-row-label"><i class="fa-solid fa-envelope" style="margin-left:.4rem;color:#94a3b8;font-size:.8rem;"></i> Email Address</span>
          <span class="ac-info-row-value">{{ auth()->user()->email }}</span>
        </div>
        <div class="ac-info-row">
          <span class="ac-info-row-label"><i class="fa-solid fa-circle-dot" style="margin-left:.4rem;color:#94a3b8;font-size:.8rem;"></i> Account Status</span>
          <span>
            <span class="ac-badge completed">
              <i class="fa-solid fa-circle-check"></i>
              Active
            </span>
          </span>
        </div>
        <div class="ac-info-row">
          <span class="ac-info-row-label"><i class="fa-solid fa-calendar" style="margin-left:.4rem;color:#94a3b8;font-size:.8rem;"></i> Member Since</span>
          <span class="ac-info-row-value">{{ auth()->user()->created_at?->format('d F Y') ?? 'N/A' }}</span>
        </div>
      </div>
    </div>
  </div>

  {{-- ─── Change password card ─── --}}
  <div class="ac-card" style="margin-bottom:1.5rem;">
    <div class="ac-card-header">
      <div class="ac-card-title">
        <i class="fa-solid fa-lock"></i>
        Change Password
        <small style="font-size:.72rem;color:#94a3b8;font-weight:400;margin-left:.25rem;">تغيير كلمة المرور</small>
      </div>
    </div>
    <div class="ac-card-body">

      <form method="POST" action="{{ route('account.security.update') }}">
        @csrf
        @method('PUT')

        {{-- Current password --}}
        <div style="margin-bottom:1.2rem;">
          <label for="current_password" class="ac-label">
            Current Password <span style="color:#ef4444;">*</span>
          </label>
          <input
            id="current_password"
            type="password"
            name="current_password"
            autocomplete="current-password"
            class="ac-input {{ $errors->has('current_password') ? 'is-error' : '' }}"
            placeholder="Enter your current password"
          />
          @error('current_password')
            <p class="ac-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
          @enderror
        </div>

        {{-- New password + confirm --}}
        <div class="ac-form-grid-2" style="margin-bottom:1.5rem;">
          <div>
            <label for="password" class="ac-label">
              New Password <span style="color:#ef4444;">*</span>
            </label>
            <input
              id="password"
              type="password"
              name="password"
              autocomplete="new-password"
              class="ac-input {{ $errors->has('password') ? 'is-error' : '' }}"
              placeholder="Min. 8 characters"
            />
            @error('password')
              <p class="ac-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
            @enderror
          </div>
          <div>
            <label for="password_confirmation" class="ac-label">
              Confirm New Password <span style="color:#ef4444;">*</span>
            </label>
            <input
              id="password_confirmation"
              type="password"
              name="password_confirmation"
              autocomplete="new-password"
              class="ac-input"
              placeholder="Repeat your new password"
            />
          </div>
        </div>

        {{-- Password requirements hint --}}
        <div style="display:flex;align-items:flex-start;gap:.65rem;padding:.9rem 1rem;border-radius:10px;background:#f7f6fd;border:1px solid #e8e7f2;margin-bottom:1.5rem;">
          <i class="fa-solid fa-shield-halved" style="color:#7c3aed;margin-top:.1rem;font-size:.9rem;flex-shrink:0;"></i>
          <div>
            <div style="font-size:.82rem;font-weight:700;color:#172033;margin-bottom:.3rem;">Password requirements</div>
            <ul style="font-size:.78rem;color:#64748b;margin:0;padding-left:1.1rem;display:flex;flex-direction:column;gap:.2rem;">
              <li>At least 8 characters long</li>
              <li>Contains at least one letter</li>
              <li>Contains at least one number</li>
            </ul>
          </div>
        </div>

        <button type="submit" class="ac-btn-primary">
          <i class="fa-solid fa-key"></i>
          Update Password
        </button>

      </form>

    </div>
  </div>

  {{-- ─── Danger zone ─── --}}
  <div class="ac-danger-zone">
    <div class="ac-danger-title">
      <i class="fa-solid fa-triangle-exclamation"></i>
      Danger Zone
    </div>
    <p class="ac-danger-text">
      Deleting your account is permanent and cannot be undone. All your enquiries, profile data, and preferences will be removed. If you wish to delete your account, please contact our support team directly at
      <a href="{{ route('contact') }}" style="color:#dc2626;font-weight:700;text-decoration:underline;">support</a>.
    </p>
  </div>

@endsection
