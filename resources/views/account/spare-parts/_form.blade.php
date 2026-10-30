{{-- Shared form for merchant spare part create/edit --}}
@php $part = $part ?? null; @endphp

<div class="ac-form-grid-2">

    <div style="grid-column:span 2">
        <label class="ac-label">Part Name *</label>
        <input type="text" name="name" value="{{ old('name', $part->name ?? '') }}"
               class="ac-input {{ $errors->has('name') ? 'is-error' : '' }}"
               placeholder="e.g. Front Brake Pad Set">
        @error('name')<p class="ac-error-msg"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="ac-label">SKU / Part Number</label>
        <input type="text" name="sku" value="{{ old('sku', $part->sku ?? '') }}"
               class="ac-input" placeholder="e.g. BP-YMH-001">
        @error('sku')<p class="ac-error-msg"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="ac-label">Category</label>
        <select name="category" class="ac-input">
            <option value="">— Select category —</option>
            @foreach(\App\Models\SparePart::$categories as $k => $v)
                <option value="{{ $k }}" {{ old('category', $part->category ?? '') === $k ? 'selected' : '' }}>{{ $v }}</option>
            @endforeach
        </select>
        @error('category')<p class="ac-error-msg"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="ac-label">Brand (optional)</label>
        <select name="brand_id" class="ac-input">
            <option value="">— Universal / No specific brand —</option>
            @foreach($brands as $brand)
                <option value="{{ $brand->id }}" {{ old('brand_id', $part->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                    {{ $brand->name }}
                </option>
            @endforeach
        </select>
        @error('brand_id')<p class="ac-error-msg"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="ac-label">Availability</label>
        <select name="availability_status" class="ac-input">
            @foreach(\App\Models\SparePart::$availabilityStatuses as $k => $v)
                <option value="{{ $k }}" {{ old('availability_status', $part->availability_status ?? 'in_stock') === $k ? 'selected' : '' }}>{{ $v }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="ac-label">Price</label>
        <input type="number" name="price" step="0.01" min="0"
               value="{{ old('price', $part->price ?? '') }}"
               class="ac-input" placeholder="0.00">
        @error('price')<p class="ac-error-msg"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="ac-label">Currency</label>
        <input type="text" name="currency" value="{{ old('currency', $part->currency ?? 'USD') }}"
               class="ac-input" maxlength="10">
    </div>

    <div>
        <label class="ac-label">Stock Quantity</label>
        <input type="number" name="stock_quantity" min="0"
               value="{{ old('stock_quantity', $part->stock_quantity ?? 0) }}"
               class="ac-input">
    </div>

    <div style="grid-column:span 2">
        <label class="ac-label">Description</label>
        <textarea name="description" rows="4" class="ac-input"
                  placeholder="Describe this spare part, materials, specifications…"
                  style="resize:vertical">{{ old('description', $part->description ?? '') }}</textarea>
        @error('description')<p class="ac-error-msg"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>@enderror
    </div>

    <div style="grid-column:span 2">
        <label class="ac-label">Compatibility Notes</label>
        <textarea name="compatibility_notes" rows="2" class="ac-input"
                  placeholder="e.g. Fits 2020–2024 YZF-R1. Not compatible with R1M."
                  style="resize:vertical">{{ old('compatibility_notes', $part->compatibility_notes ?? '') }}</textarea>
    </div>

    {{-- Compatible Motorcycles --}}
    <div style="grid-column:span 2">
        <label class="ac-label">Compatible Motorcycles</label>
        <div style="max-height:200px;overflow-y:auto;border:1px solid var(--ac-line-2);border-radius:10px;padding:10px;background:var(--ac-lav)">
            @if($motorcycles->isEmpty())
                <p style="font-size:.8rem;color:var(--ac-muted)">No motorcycles available.</p>
            @else
                @foreach($motorcycles as $moto)
                <label style="display:flex;align-items:center;gap:8px;padding:4px 6px;border-radius:7px;cursor:pointer;font-size:.82rem;color:var(--ac-ink-2)">
                    <input type="checkbox" name="compatible_motorcycles[]" value="{{ $moto->id }}"
                           {{ in_array($moto->id, $selected ?? []) ? 'checked' : '' }}
                           style="width:14px;height:14px;accent-color:var(--ac-primary)">
                    {{ $moto->brand->name ?? '' }} {{ $moto->model_name }} ({{ $moto->year }})
                </label>
                @endforeach
            @endif
        </div>
    </div>

    {{-- Main image --}}
    <div style="grid-column:span 2">
        <label class="ac-label">Main Image</label>
        @if(isset($part) && $part->image_url)
            <div style="margin-bottom:10px">
                <img src="{{ $part->image_url }}" alt="{{ $part->name }}"
                     style="width:140px;height:105px;object-fit:contain;border-radius:9px;border:1px solid var(--ac-line);background:#0f172a;padding:8px">
            </div>
        @endif
        <input type="file" name="image" accept="image/*" class="ac-input">
        <p style="margin-top:5px;font-size:.75rem;color:var(--ac-muted)">
            JPG, PNG, WEBP — max 3MB.{{ isset($part) && $part->image_path ? ' Upload to replace current.' : '' }}
        </p>
        @error('image')<p class="ac-error-msg"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>@enderror
    </div>

</div>

<div class="ac-toggle-row" style="margin-top:1.25rem">
    <i class="fa-solid fa-clock-rotate-left" style="color:var(--ac-primary);margin-top:2px"></i>
    <div>
        <p class="ac-toggle-label">Pending Admin Review</p>
        <p class="ac-toggle-sub">Your spare part will be saved as a draft and published by an Admin after review.</p>
    </div>
</div>
