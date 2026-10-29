{{-- Shared form partial for create & edit --}}

<style>
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
    .form-grid .col-span-2 { grid-column: span 2; }
    .form-section { border: 1px solid var(--line); border-radius: 14px; background: rgba(255,255,255,.025); padding: 20px; margin-top: 4px; }
    .form-section-title { font-size: 13px; font-weight: 700; color: #d4cfea; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
    .form-section-title i { color: #9b5cff; font-size: 13px; }
    .form-check-row { display: flex; align-items: center; gap: 9px; }
    .form-check-row input[type="checkbox"] { width: 16px; height: 16px; accent-color: #9b5cff; cursor: pointer; border-radius: 4px; }
    .form-check-row label { font-size: 13px; color: #c8c3da; cursor: pointer; user-select: none; }
    .form-hint { font-size: 11px; color: #6e6888; margin-top: 5px; line-height: 1.5; }
    .img-preview { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 10px; }
    .img-preview img { width: 72px; height: 72px; object-fit: cover; border-radius: 10px; border: 1px solid rgba(255,255,255,.1); }
    .img-preview-main img { height: 110px; width: auto; max-width: 220px; border-radius: 10px; border: 1px solid rgba(155,92,255,.25); margin-bottom: 10px; }
    .error-msg { font-size: 11px; color: #f87171; margin-top: 5px; font-weight: 600; }
    @media (max-width: 640px) { .form-grid { grid-template-columns: 1fr; } .form-grid .col-span-2 { grid-column: span 1; } }
</style>

<div class="form-grid">

    {{-- Brand --}}
    <div class="col-span-2">
        <label class="form-label">Brand *</label>
        <select name="brand_id" class="form-select {{ $errors->has('brand_id') ? 'is-invalid' : '' }}">
            <option value="">Select brand…</option>
            @foreach($brands as $b)
                <option value="{{ $b->id }}" {{ old('brand_id', $motorcycle->brand_id ?? '') == $b->id ? 'selected' : '' }}>
                    {{ $b->name }}
                </option>
            @endforeach
        </select>
        @error('brand_id')<p class="error-msg">{{ $message }}</p>@enderror
    </div>

    {{-- Model Name --}}
    <div class="col-span-2">
        <label class="form-label">Model Name *</label>
        <input type="text" name="model_name"
               value="{{ old('model_name', $motorcycle->model_name ?? '') }}"
               class="form-input {{ $errors->has('model_name') ? 'is-invalid' : '' }}"
               placeholder="e.g. Kawasaki Ninja H2">
        @error('model_name')<p class="error-msg">{{ $message }}</p>@enderror
    </div>

    {{-- Price --}}
    <div>
        <label class="form-label">Price *</label>
        <input type="number" step="0.01" name="price"
               value="{{ old('price', $motorcycle->price ?? '') }}"
               class="form-input {{ $errors->has('price') ? 'is-invalid' : '' }}"
               placeholder="e.g. 15000">
        @error('price')<p class="error-msg">{{ $message }}</p>@enderror
    </div>

    {{-- Year --}}
    <div>
        <label class="form-label">Model Year *</label>
        <input type="number" name="year"
               value="{{ old('year', $motorcycle->year ?? date('Y')) }}"
               class="form-input {{ $errors->has('year') ? 'is-invalid' : '' }}"
               placeholder="{{ date('Y') }}">
        @error('year')<p class="error-msg">{{ $message }}</p>@enderror
    </div>

    {{-- Category --}}
    <div>
        <label class="form-label">Category *</label>
        <select name="category" class="form-select {{ $errors->has('category') ? 'is-invalid' : '' }}">
            <option value="">Select…</option>
            @foreach(['sport'=>'Sport','naked'=>'Naked','adventure'=>'Adventure','cruiser'=>'Cruiser','scooter'=>'Scooter','off-road'=>'Off-Road'] as $val => $lbl)
                <option value="{{ $val }}" {{ old('category', $motorcycle->category ?? '') === $val ? 'selected' : '' }}>
                    {{ $lbl }}
                </option>
            @endforeach
        </select>
        @error('category')<p class="error-msg">{{ $message }}</p>@enderror
    </div>

    {{-- Engine CC --}}
    <div>
        <label class="form-label">Engine Capacity (CC)</label>
        <input type="number" step="0.01" name="engine_capacity"
               value="{{ old('engine_capacity', $motorcycle->engine_capacity ?? '') }}"
               class="form-input"
               placeholder="e.g. 998">
        @error('engine_capacity')<p class="error-msg">{{ $message }}</p>@enderror
    </div>

    {{-- Availability --}}
    <div>
        <label class="form-label">Availability *</label>
        <select name="availability_status" class="form-select {{ $errors->has('availability_status') ? 'is-invalid' : '' }}">
            @foreach(['available'=>'Available','reserved'=>'Reserved','sold'=>'Sold'] as $val => $lbl)
                <option value="{{ $val }}" {{ old('availability_status', $motorcycle->availability_status ?? 'available') === $val ? 'selected' : '' }}>
                    {{ $lbl }}
                </option>
            @endforeach
        </select>
        @error('availability_status')<p class="error-msg">{{ $message }}</p>@enderror
    </div>

    {{-- Condition --}}
    <div>
        <label class="form-label">Condition *</label>
        <select name="condition" class="form-select {{ $errors->has('condition') ? 'is-invalid' : '' }}">
            @foreach(['new'=>'New','used'=>'Used'] as $val => $lbl)
                <option value="{{ $val }}" {{ old('condition', $motorcycle->condition ?? 'new') === $val ? 'selected' : '' }}>
                    {{ $lbl }}
                </option>
            @endforeach
        </select>
        @error('condition')<p class="error-msg">{{ $message }}</p>@enderror
    </div>

    {{-- Short Description --}}
    <div class="col-span-2">
        <label class="form-label">Short Description</label>
        <input type="text" name="short_description"
               value="{{ old('short_description', $motorcycle->short_description ?? '') }}"
               maxlength="300"
               class="form-input"
               placeholder="A brief one-line summary…">
        @error('short_description')<p class="error-msg">{{ $message }}</p>@enderror
    </div>

    {{-- Description --}}
    <div class="col-span-2">
        <label class="form-label">Description *</label>
        <textarea name="description" rows="5"
                  class="form-textarea {{ $errors->has('description') ? 'is-invalid' : '' }}"
                  placeholder="Full motorcycle description…">{{ old('description', $motorcycle->description ?? '') }}</textarea>
        @error('description')<p class="error-msg">{{ $message }}</p>@enderror
    </div>

</div>

{{-- Images --}}
<div class="form-section" style="margin-top:22px">
    <div class="form-section-title"><i class="fa-solid fa-images"></i> Images</div>

    <div class="form-grid">
        <div>
            <label class="form-label">Main Image</label>
            @if(!empty($motorcycle->image_path ?? null))
                <div class="img-preview-main"><img src="{{ asset('storage/' . $motorcycle->image_path) }}" alt=""></div>
            @elseif(!empty($motorcycle->image_url ?? null))
                <div class="img-preview-main"><img src="{{ $motorcycle->image_url }}" alt=""></div>
            @endif
            <input type="file" name="image" accept="image/*" class="form-input" style="padding:8px 12px;cursor:pointer">
            <p class="form-hint">Max 4MB. Leave empty to keep the existing image.</p>
            @error('image')<p class="error-msg">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="form-label">Additional Images</label>
            @if(is_array($motorcycle->additional_images ?? null) && count($motorcycle->additional_images))
                <div class="img-preview">
                    @foreach($motorcycle->additional_images as $img)
                        <img src="{{ asset('storage/' . $img) }}" alt="">
                    @endforeach
                </div>
            @endif
            <input type="file" name="additional_images[]" accept="image/*" multiple
                   class="form-input" style="padding:8px 12px;cursor:pointer">
            <p class="form-hint">Multiple files allowed (max 4MB each). New uploads replace existing ones.</p>
            @error('additional_images')<p class="error-msg">{{ $message }}</p>@enderror
            @error('additional_images.*')<p class="error-msg">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

{{-- Flags --}}
<div style="display:flex;flex-wrap:wrap;gap:20px;margin-top:20px">
    <div class="form-check-row">
        <input type="hidden" name="is_published" value="0">
        <input type="checkbox" id="is_published" name="is_published" value="1"
               {{ old('is_published', $motorcycle->is_published ?? false) ? 'checked' : '' }}>
        <label for="is_published">Published</label>
    </div>
    <div class="form-check-row">
        <input type="hidden" name="is_featured" value="0">
        <input type="checkbox" id="is_featured" name="is_featured" value="1"
               {{ old('is_featured', $motorcycle->is_featured ?? false) ? 'checked' : '' }}>
        <label for="is_featured">Featured on homepage</label>
    </div>
</div>
