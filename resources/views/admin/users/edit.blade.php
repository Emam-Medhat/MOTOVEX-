@extends('layouts.admin')
@section('title', 'Edit ' . $user->name)

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.users.index') }}"
           style="font-size:12px;color:#6e6888;display:inline-flex;align-items:center;gap:6px;margin-bottom:6px"
           onmouseover="this.style.color='#d4cfea'" onmouseout="this.style.color='#6e6888'">
            <i class="fa-solid fa-arrow-left" style="font-size:10px"></i> Back to Users
        </a>
        <h1>{{ $user->name }}</h1>
    </div>
</div>

<div>
    <div class="admin-panel">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" style="display:flex;flex-direction:column;gap:16px">
            @csrf @method('PUT')

            <div>
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}">
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}">
                @error('email')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">
                    New Password
                    <span style="font-weight:400;color:#6e6888">(leave blank to keep current)</span>
                </label>
                <input type="password" name="password"
                       class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                       placeholder="••••••••">
                @error('password')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">Role *</label>
                <select name="role_id" class="form-select {{ $errors->has('role_id') ? 'is-invalid' : '' }}">
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" @selected(old('role_id', $user->roles->first()?->id) == $role->id)>{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            @if($brands->isNotEmpty())
            <div>
                <label class="form-label">Assigned Brands <span style="color:#6e6888;font-weight:400">(Data Entry only)</span></label>
                @php $assigned = collect(old('brand_ids', $user->brands->pluck('id')->all())); @endphp
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:4px">
                    @foreach($brands as $brand)
                    <label style="display:flex;align-items:center;gap:9px;padding:10px 13px;border:1px solid var(--line);border-radius:10px;cursor:pointer;transition:border-color .15s"
                           onmouseover="this.style.borderColor='rgba(155,92,255,.35)'" onmouseout="this.style.borderColor='rgba(255,255,255,.08)'">
                        <input type="checkbox" name="brand_ids[]" value="{{ $brand->id }}"
                               @checked($assigned->contains($brand->id))
                               style="accent-color:#9b5cff;width:15px;height:15px;cursor:pointer">
                        <span style="font-size:13px">{{ $brand->name }}</span>
                    </label>
                    @endforeach
                </div>
                @error('brand_ids')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            @endif

            <label style="display:flex;align-items:center;gap:9px;padding:12px 14px;border:1px solid var(--line);border-radius:10px;cursor:pointer">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active))
                       style="accent-color:#9b5cff;width:15px;height:15px;cursor:pointer">
                <span style="font-size:13px;color:#c8c3da">Account is active</span>
                <span style="font-size:11px;color:#6e6888">— suspended accounts can't sign in</span>
            </label>

            <div style="display:flex;gap:12px;padding-top:16px;border-top:1px solid var(--line)">
                <button type="submit" class="btn-primary"><i class="fa-solid fa-check"></i> Save Changes</button>
                <a href="{{ route('admin.users.index') }}" class="btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
