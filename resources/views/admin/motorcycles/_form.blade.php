{{-- Shared form partial for create & edit --}}

<div class="panel border-0 shadow-sm p-4">
    <div class="row g-4">
        {{-- Brand --}}
        <div class="col-md-12">
            <label class="form-label">Brand *</label>
            <select name="brand_id" class="form-select {{ $errors->has('brand_id') ? 'is-invalid' : '' }}">
                <option value="">Select brand…</option>
                @foreach($brands as $b)
                    <option value="{{ $b->id }}" {{ old('brand_id', $motorcycle->brand_id ?? '') == $b->id ? 'selected' : '' }}>
                        {{ $b->name }}
                    </option>
                @endforeach
            </select>
            @error('brand_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        {{-- Model Name --}}
        <div class="col-md-12">
            <label class="form-label">Model Name *</label>
            <input type="text" name="model_name"
                   value="{{ old('model_name', $motorcycle->model_name ?? '') }}"
                   class="form-control {{ $errors->has('model_name') ? 'is-invalid' : '' }}"
                   placeholder="e.g. Kawasaki Ninja H2">
            @error('model_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        {{-- Price --}}
        <div class="col-md-6">
            <label class="form-label">Price *</label>
            <input type="number" step="0.01" name="price"
                   value="{{ old('price', $motorcycle->price ?? '') }}"
                   class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}"
                   placeholder="e.g. 15000">
            @error('price')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Rental Price per Day</label>
            <input type="number" step="0.01" min="0.01" name="rental_price_per_day"
                   value="{{ old('rental_price_per_day', $motorcycle->rental_price_per_day ?? '') }}"
                   class="form-control {{ $errors->has('rental_price_per_day') ? 'is-invalid' : '' }}"
                   placeholder="Leave blank to disable rentals">
            @error('rental_price_per_day')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        @if(auth()->user()->isAdmin())
        <div class="col-md-12">
            <label class="form-label">Assigned Manager</label>
            <select name="assigned_manager_id" class="form-select {{ $errors->has('assigned_manager_id') ? 'is-invalid' : '' }}">
                <option value="">Use original creator</option>
                @foreach($managers as $manager)
                    <option value="{{ $manager->id }}" {{ (string) old('assigned_manager_id', $motorcycle->assigned_manager_id ?? '') === (string) $manager->id ? 'selected' : '' }}>
                        {{ $manager->name }} ({{ $manager->roleName() }})
                    </option>
                @endforeach
            </select>
            <small class="text-muted mt-2 d-block">The assigned manager handles new requests. The creator remains recorded for auditing.</small>
            @error('assigned_manager_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        @endif

        {{-- Year --}}
        <div class="col-md-4">
            <label class="form-label">Model Year *</label>
            <input type="number" name="year"
                   value="{{ old('year', $motorcycle->year ?? date('Y')) }}"
                   class="form-control {{ $errors->has('year') ? 'is-invalid' : '' }}"
                   placeholder="{{ date('Y') }}">
            @error('year')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        {{-- Category --}}
        <div class="col-md-4">
            <label class="form-label">Category *</label>
            <select name="category" class="form-select {{ $errors->has('category') ? 'is-invalid' : '' }}">
                <option value="">Select…</option>
                @foreach(['sport'=>'Sport','naked'=>'Naked','adventure'=>'Adventure','cruiser'=>'Cruiser','scooter'=>'Scooter','off-road'=>'Off-Road'] as $val => $lbl)
                    <option value="{{ $val }}" {{ old('category', $motorcycle->category ?? '') === $val ? 'selected' : '' }}>
                        {{ $lbl }}
                    </option>
                @endforeach
            </select>
            @error('category')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        {{-- Engine CC --}}
        <div class="col-md-4">
            <label class="form-label">Engine Capacity (CC)</label>
            <input type="number" step="0.01" name="engine_capacity"
                   value="{{ old('engine_capacity', $motorcycle->engine_capacity ?? '') }}"
                   class="form-control"
                   placeholder="e.g. 998">
            @error('engine_capacity')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        {{-- Availability --}}
        <div class="col-md-6">
            <label class="form-label">Availability *</label>
            <select name="availability_status" class="form-select {{ $errors->has('availability_status') ? 'is-invalid' : '' }}">
                @foreach(['available'=>'Available','reserved'=>'Reserved','sold'=>'Sold'] as $val => $lbl)
                    <option value="{{ $val }}" {{ old('availability_status', $motorcycle->availability_status ?? 'available') === $val ? 'selected' : '' }}>
                        {{ $lbl }}
                    </option>
                @endforeach
            </select>
            @error('availability_status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        {{-- Condition --}}
        <div class="col-md-6">
            <label class="form-label">Condition *</label>
            <select name="condition" class="form-select {{ $errors->has('condition') ? 'is-invalid' : '' }}">
                @foreach(['new'=>'New','used'=>'Used'] as $val => $lbl)
                    <option value="{{ $val }}" {{ old('condition', $motorcycle->condition ?? 'new') === $val ? 'selected' : '' }}>
                        {{ $lbl }}
                    </option>
                @endforeach
            </select>
            @error('condition')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        {{-- Short Description --}}
        <div class="col-md-12">
            <label class="form-label">Short Description</label>
            <input type="text" name="short_description"
                   value="{{ old('short_description', $motorcycle->short_description ?? '') }}"
                   maxlength="300"
                   class="form-control"
                   placeholder="A brief one-line summary…">
            @error('short_description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        {{-- Description --}}
        <div class="col-md-12">
            <label class="form-label">Description *</label>
            <textarea name="description" rows="5"
                      class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                      placeholder="Full motorcycle description…">{{ old('description', $motorcycle->description ?? '') }}</textarea>
            @error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

{{-- Images --}}
<div class="panel border-0 shadow-sm p-4 mt-4">
    <div class="form-section-title border-0 mb-3"><i class="bi bi-images text-primary"></i> Images</div>

    <div class="row g-4">
        <div class="col-md-6">
            <label class="form-label">Main Image</label>
            @if(!empty($motorcycle->image_path ?? null))
                <div class="mb-3" style="width: 200px; height: 150px; border-radius: 12px; overflow: hidden; border: 1px solid var(--border);">
                    <img src="{{ asset('storage/' . $motorcycle->image_path) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            @elseif(!empty($motorcycle->image_url ?? null))
                <div class="mb-3" style="width: 200px; height: 150px; border-radius: 12px; overflow: hidden; border: 1px solid var(--border);">
                    <img src="{{ $motorcycle->image_url }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            @endif
            <input type="file" name="image" accept="image/*" class="form-control">
            <small class="text-muted mt-2 d-block">Max 4MB. Leave empty to keep the existing image.</small>
            @error('image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Additional Images</label>
            @if(is_array($motorcycle->additional_images ?? null) && count($motorcycle->additional_images))
                <div class="d-flex flex-wrap gap-2 mb-3">
                    @foreach($motorcycle->additional_images as $img)
                        <div style="width: 60px; height: 60px; border-radius: 8px; overflow: hidden; border: 1px solid var(--border);">
                            <img src="{{ asset('storage/' . $img) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    @endforeach
                </div>
            @endif
            <input type="file" name="additional_images[]" accept="image/*" multiple class="form-control">
            <small class="text-muted mt-2 d-block">Multiple files allowed. New uploads replace existing ones.</small>
            @error('additional_images')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            @error('additional_images.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

{{-- Flags --}}
<div class="d-flex flex-wrap gap-4 mt-4 ps-2">
    <div class="form-check">
        <input type="hidden" name="is_published" value="0">
        <input type="checkbox" id="is_published" name="is_published" class="form-check-input" value="1"
               {{ old('is_published', $motorcycle->is_published ?? false) ? 'checked' : '' }}>
        <label class="form-check-label fw-semibold" for="is_published">Published</label>
    </div>
    <div class="form-check">
        <input type="hidden" name="is_featured" value="0">
        <input type="checkbox" id="is_featured" name="is_featured" class="form-check-input" value="1"
               {{ old('is_featured', $motorcycle->is_featured ?? false) ? 'checked' : '' }}>
        <label class="form-check-label fw-semibold" for="is_featured">Featured on homepage</label>
    </div>
</div>
