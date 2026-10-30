@extends('layouts.admin')
@section('title', 'New Brand')

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.brands.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-2 mb-2">
            <i class="bi bi-arrow-left"></i> Back to Brands
        </a>
        <h1>New Brand</h1>
        <p>Register a new motorcycle brand in the system.</p>
    </div>
</div>

<div class="panel border-0 shadow-sm p-4">
    <form method="POST" action="{{ route('admin.brands.store') }}">
        @csrf

        <div class="row g-4">
            <div class="col-md-12">
                <label class="form-label">Brand Name *</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                       placeholder="e.g. Kawasaki">
                @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-12">
                <label class="form-label">Description *</label>
                <textarea name="description" rows="4"
                          class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                          placeholder="Brief description of the brand…">{{ old('description') }}</textarea>
                @error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Country</label>
                <input type="text" name="country" value="{{ old('country') }}"
                       class="form-control" placeholder="e.g. Japan">
            </div>

            <div class="col-md-6">
                <label class="form-label">Website URL</label>
                <input type="url" name="website_url" value="{{ old('website_url') }}"
                       class="form-control {{ $errors->has('website_url') ? 'is-invalid' : '' }}"
                       placeholder="https://…">
                @error('website_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-12">
                <label class="form-label">Logo URL</label>
                <input type="url" name="logo_url" value="{{ old('logo_url') }}"
                       class="form-control {{ $errors->has('logo_url') ? 'is-invalid' : '' }}"
                       placeholder="https://…">
                @error('logo_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-12 mt-4">
                <div class="form-check form-switch d-flex align-items-center gap-2">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" id="is_published" name="is_published" value="1"
                           class="form-check-input" style="width: 40px; height: 20px;"
                           {{ old('is_published') ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold ms-2" for="is_published">
                        <i class="bi bi-eye me-1 text-primary"></i> Publish immediately
                    </label>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn-primary-alt">
                <i class="bi bi-check-lg"></i> Create Brand
            </button>
            <a href="{{ route('admin.brands.index') }}" class="btn btn-light" style="border-radius: 8px; font-weight: 600;">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
