{{-- Services shared form --}}
@php $service = $service ?? null; @endphp

<div class="panel border-0 shadow-sm p-4">
    <div class="row g-4">

        <div class="col-md-12">
            <label class="form-label">Title *</label>
            <input type="text" name="title" value="{{ old('title', $service->title ?? '') }}"
                   class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                   placeholder="e.g. Scheduled Maintenance">
            @error('title')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-12">
            <label class="form-label">Short Description *</label>
            <textarea name="description" rows="2"
                      class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                      placeholder="One or two lines shown on the service card…">{{ old('description', $service->description ?? '') }}</textarea>
            @error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-12">
            <label class="form-label">Full Description</label>
            <textarea name="full_description" rows="4" class="form-control"
                      placeholder="Additional detail shown under the short description…">{{ old('full_description', $service->full_description ?? '') }}</textarea>
            @error('full_description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Icon</label>
            <input type="text" name="icon" value="{{ old('icon', $service->icon ?? '') }}"
                   class="form-control" placeholder="e.g. fa-gears or wrench">
            <small class="text-muted d-block mt-1">Font Awesome class (e.g. <code>fa-gears</code>).</small>
            @error('icon')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Display Order</label>
            <input type="number" name="sort_order" min="0" max="9999"
                   value="{{ old('sort_order', $service->sort_order ?? 0) }}"
                   class="form-control {{ $errors->has('sort_order') ? 'is-invalid' : '' }}">
            @error('sort_order')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">CTA Label</label>
            <input type="text" name="cta_label" value="{{ old('cta_label', $service->cta_label ?? '') }}"
                   class="form-control" placeholder="e.g. Book now">
            @error('cta_label')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">CTA Link</label>
            <input type="text" name="cta_route" value="{{ old('cta_route', $service->cta_route ?? '') }}"
                   class="form-control" placeholder="https://… or route name">
            @error('cta_route')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-12">
            <label class="form-label">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $service->slug ?? '') }}"
                   class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}"
                   placeholder="auto-generated from the title if left empty">
            @error('slug')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-12">
            <label class="form-label">Service Image</label>
            @if($service && $service->image_path)
                <div class="mb-3">
                    <img src="{{ $service->image_url }}" alt="{{ $service->title }}"
                         class="img-thumbnail" style="height: 100px; width: 160px; object-fit: cover;">
                </div>
            @endif
            <input type="file" name="image" accept="image/*" class="form-control">
            <small class="text-muted d-block mt-1">JPG, PNG, WEBP or GIF — max 2MB.</small>
            @error('image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-12 mt-4">
            <div class="form-check form-switch d-flex align-items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" id="is_active" name="is_active" value="1"
                       class="form-check-input" style="width: 40px; height: 20px;"
                       {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label fw-bold ms-2" for="is_active">
                    <i class="bi bi-eye me-1 text-primary"></i> Published (active)
                </label>
            </div>
        </div>
    </div>
</div>
