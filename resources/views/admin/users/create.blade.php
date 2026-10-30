@extends('layouts.admin')
@section('title', request('preset') === 'admin' ? 'New Admin Account' : 'New User')

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-2 mb-2">
            <i class="bi bi-arrow-left"></i> Back to Users
        </a>
        @if(request('preset') === 'admin')
            <h1>New Admin Account</h1>
            <p>Admin accounts have full platform access. Use with caution.</p>
        @else
            <h1>New User Account</h1>
            <p>Create a new account with specific role and permissions.</p>
        @endif
    </div>
</div>

@if(request('preset') === 'admin')
<div class="alert alert-danger border-0 shadow-sm d-flex align-items-center gap-3 mb-4 p-3">
    <div class="bg-white rounded-3 p-2 text-danger shadow-sm">
        <i class="bi bi-shield-lock fs-4"></i>
    </div>
    <div>
        <h6 class="mb-0 fw-bold">Administrator Account Creation</h6>
        <p class="mb-0 small opacity-75">This account will have unrestricted access to all platform functions.</p>
    </div>
</div>
@endif

<div class="panel border-0 shadow-sm p-4">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        @if(request('preset') === 'admin')
            <input type="hidden" name="preset" value="admin">
        @endif

        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                       placeholder="e.g. Ahmed Mohamed">
                @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                       placeholder="email@example.com">
                @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Password *</label>
                <input type="password" name="password"
                       class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                       placeholder="Min 8 characters">
                @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Confirm Password *</label>
                <input type="password" name="password_confirmation"
                       class="form-control"
                       placeholder="Re-enter password">
            </div>

            <div class="col-md-12">
                <label class="form-label">Account Role *</label>
                <select name="role_id" class="form-select {{ $errors->has('role_id') ? 'is-invalid' : '' }}" id="role-select">
                    <option value="">Select role…</option>
                    @foreach($roles as $role)
                        @php
                            $isPresetAdmin = request('preset') === 'admin' && $role->slug === 'admin';
                            $isSelected    = old('role_id') == $role->id || $isPresetAdmin;
                        @endphp
                        <option value="{{ $role->id }}" @selected($isSelected)>{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                @if(request('preset') === 'admin')
                    <small class="text-danger d-block mt-2 fw-bold">
                        <i class="bi bi-info-circle me-1"></i> Admin role is pre-selected for this account.
                    </small>
                @endif
            </div>

            @if($brands->isNotEmpty())
            <div class="col-md-12" id="brand-section">
                <label class="form-label">Assigned Brands <small class="text-muted fw-normal">(For Data Entry roles)</small></label>
                <div class="border rounded-3 p-3 bg-light">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-2">
                        @foreach($brands as $brand)
                        <div class="col">
                            <div class="form-check">
                                <input type="checkbox" name="brand_ids[]" value="{{ $brand->id }}"
                                       id="brand_{{ $brand->id }}" class="form-check-input"
                                       @checked(collect(old('brand_ids', []))->contains($brand->id))>
                                <label class="form-check-label small" for="brand_{{ $brand->id }}">
                                    {{ $brand->name }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @error('brand_ids')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            @endif

            <div class="col-md-12 mt-4">
                <div class="form-check form-switch d-flex align-items-center gap-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                           class="form-check-input" style="width: 40px; height: 20px;"
                           @checked(old('is_active', true))>
                    <label class="form-check-label fw-bold ms-2" for="is_active">
                        <i class="bi bi-person-check me-1 text-primary"></i> Account is Active
                    </label>
                </div>
                <small class="text-muted d-block mt-1 ms-5">Suspended accounts cannot sign in to the platform.</small>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            @if(request('preset') === 'admin')
                <button type="submit" class="btn btn-danger btn-sm px-4" style="border-radius: 8px; font-weight: 600; height: 38px;">
                    <i class="bi bi-shield-check me-2"></i> Create Admin Account
                </button>
            @else
                <button type="submit" class="btn-primary-alt">
                    <i class="bi bi-person-plus me-2"></i> Create User
                </button>
            @endif
            <a href="{{ route('admin.users.index') }}" class="btn btn-light" style="border-radius: 8px; font-weight: 600;">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
(function () {
    const roleSelect   = document.getElementById('role-select');
    const brandSection = document.getElementById('brand-section');

    function toggleBrands() {
        if (!roleSelect || !brandSection) return;
        const selected = roleSelect.options[roleSelect.selectedIndex];
        const text     = selected ? selected.text.toLowerCase() : '';
        brandSection.style.display = text.includes('data') ? '' : 'none';
    }

    if (roleSelect) {
        roleSelect.addEventListener('change', toggleBrands);
        toggleBrands();
    }
})();
</script>
@endsection
