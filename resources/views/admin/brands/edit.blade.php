@extends('layouts.admin')
@section('title', 'Edit ' . $brand->name)

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.brands.index') }}"
           style="font-size:12px;color:#6e6888;display:inline-flex;align-items:center;gap:6px;margin-bottom:6px"
           onmouseover="this.style.color='#d4cfea'" onmouseout="this.style.color='#6e6888'">
            <i class="fa-solid fa-arrow-left" style="font-size:10px"></i> Back to Brands
        </a>
        <h1>{{ $brand->name }}</h1>
    </div>
</div>

<div>
    <div class="admin-panel">
        <form method="POST" action="{{ route('admin.brands.update', $brand) }}" style="display:flex;flex-direction:column;gap:16px">
            @csrf @method('PUT')

            <div>
                <label class="form-label">Brand Name *</label>
                <input type="text" name="name" value="{{ old('name', $brand->name) }}"
                       class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}">
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">Description *</label>
                <textarea name="description" rows="4"
                          class="form-textarea {{ $errors->has('description') ? 'is-invalid' : '' }}">{{ old('description', $brand->description) }}</textarea>
                @error('description')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                <div>
                    <label class="form-label">Country</label>
                    <input type="text" name="country" value="{{ old('country', $brand->country) }}"
                           class="form-input">
                </div>
                <div>
                    <label class="form-label">Website URL</label>
                    <input type="url" name="website_url" value="{{ old('website_url', $brand->website_url) }}"
                           class="form-input">
                </div>
            </div>

            <div>
                <label class="form-label">Logo URL</label>
                <input type="url" name="logo_url" value="{{ old('logo_url', $brand->logo_url) }}"
                       class="form-input">
            </div>

            <div style="display:flex;align-items:center;gap:9px">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" id="is_published" name="is_published" value="1"
                       {{ old('is_published', $brand->is_published) ? 'checked' : '' }}
                       style="width:16px;height:16px;accent-color:#9b5cff;cursor:pointer">
                <label for="is_published" style="font-size:13px;color:#c8c3da;cursor:pointer">Published</label>
            </div>

            <div style="display:flex;gap:12px;padding-top:16px;border-top:1px solid var(--line)">
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-check"></i> Save Changes
                </button>
                <a href="{{ route('admin.brands.index') }}" class="btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
