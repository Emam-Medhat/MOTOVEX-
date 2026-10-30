@extends('layouts.admin')
@section('title', 'Edit ' . $user->name)

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-2 mb-2">
            <i class="bi bi-arrow-left"></i> Back to Users
        </a>
        <h1>Edit User Account</h1>
        <p>Updating account details for <strong>{{ $user->name }}</strong></p>
    </div>
</div>

<div class="panel border-0 shadow-sm p-4">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf @method('PUT')

        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}">
                @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">
                @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">New Password <small class="text-muted fw-normal">(leave blank to keep current)</small></label>
                <input type="password" name="password"
                       class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                       placeholder="••••••••">
                @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Account Role *</label>
                <select name="role_id" class="form-select {{ $errors->has('role_id') ? 'is-invalid' : '' }}">
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" @selected(old('role_id', $user->roles->first()?->id) == $role->id)>{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            @if($brands->isNotEmpty())
            <div class="col-md-12">
                <label class="form-label">Assigned Brands <small class="text-muted fw-normal">(For Data Entry roles)</small></label>
                @php $assigned = collect(old('brand_ids', $user->brands->pluck('id')->all())); @endphp
                <div class="border rounded-3 p-3 bg-light">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-2">
                        @foreach($brands as $brand)
                        <div class="col">
                            <div class="form-check">
                                <input type="checkbox" name="brand_ids[]" value="{{ $brand->id }}"
                                       id="brand_{{ $brand->id }}" class="form-check-input"
                                       @checked($assigned->contains($brand->id))>
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
                           @checked(old('is_active', $user->is_active))>
                    <label class="form-check-label fw-bold ms-2" for="is_active">
                        <i class="bi bi-person-check me-1 text-primary"></i> Account is Active
                    </label>
                </div>
                <small class="text-muted d-block mt-1 ms-5">Suspended accounts cannot sign in.</small>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn-primary-alt">
                <i class="bi bi-check-lg"></i> Save Changes
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-light" style="border-radius: 8px; font-weight: 600;">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
