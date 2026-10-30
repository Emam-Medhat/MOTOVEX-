@extends('layouts.admin')
@section('title', 'Edit ' . $brand->name)

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.brands.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-2 mb-2">
            <i class="bi bi-arrow-left"></i> Back to Brands
        </a>
        <h1>Edit Brand</h1>
        <p>Updating details for <strong>{{ $brand->name }}</strong></p>
    </div>
</div>

<div class="panel border-0 shadow-sm p-4">
    <form method="POST" action="{{ route('admin.brands.update', $brand) }}">
        @csrf @method('PUT')

        <div class="row g-4">
            <div class="col-md-12">
                <label class="form-label">Brand Name *</label>
                <input type="text" name="name" value="{{ old('name', $brand->name) }}"
                       class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}">
                @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-12">
                <label class="form-label">Description *</label>
                <textarea name="description" rows="4"
                          class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}">{{ old('description', $brand->description) }}</textarea>
                @error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Country</label>
                <input type="text" name="country" value="{{ old('country', $brand->country) }}"
                       class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Website URL</label>
                <input type="url" name="website_url" value="{{ old('website_url', $brand->website_url) }}"
                       class="form-control">
            </div>

            <div class="col-md-12">
                <label class="form-label">Logo URL</label>
                <input type="url" name="logo_url" value="{{ old('logo_url', $brand->logo_url) }}"
                       class="form-control">
            </div>

            <div class="col-md-12 mt-4">
                <div class="form-check form-switch d-flex align-items-center gap-2">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" id="is_published" name="is_published" value="1"
                           class="form-check-input" style="width: 40px; height: 20px;"
                           {{ old('is_published', $brand->is_published) ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold ms-2" for="is_published">
                        <i class="bi bi-eye me-1 text-primary"></i> Published (visible on site)
                    </label>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn-primary-alt">
                <i class="bi bi-check-lg"></i> Save Changes
            </button>
            <a href="{{ route('admin.brands.index') }}" class="btn btn-light" style="border-radius: 8px; font-weight: 600;">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
