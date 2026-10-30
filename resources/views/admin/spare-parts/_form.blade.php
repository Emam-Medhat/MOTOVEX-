{{-- Shared form for create/edit spare parts --}}
@php $part = $part ?? null; @endphp

<div class="panel border-0 shadow-sm p-4">
    <div class="row g-4">

        {{-- Part Name --}}
        <div class="col-md-12">
            <label class="form-label">Part Name *</label>
            <input type="text" name="name" value="{{ old('name', $part->name ?? '') }}"
                   class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                   placeholder="e.g. Front Brake Pad Set">
            @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        {{-- SKU & Category --}}
        <div class="col-md-6">
            <label class="form-label">SKU / Part Number</label>
            <input type="text" name="sku" value="{{ old('sku', $part->sku ?? '') }}"
                   class="form-control" placeholder="e.g. BP-YMH-001">
            @error('sku')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Category</label>
            <select name="category" class="form-select">
                <option value="">— Select category —</option>
                @foreach(\App\Models\SparePart::$categories as $k => $v)
                    <option value="{{ $k }}" {{ old('category', $part->category ?? '') === $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>
            @error('category')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        {{-- Brand & Availability --}}
        <div class="col-md-6">
            <label class="form-label">Brand</label>
            <select name="brand_id" class="form-select">
                <option value="">— Universal / No specific brand —</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ old('brand_id', $part->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
            @error('brand_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Availability Status</label>
            <select name="availability_status" class="form-select">
                @foreach(\App\Models\SparePart::$availabilityStatuses as $k => $v)
                    <option value="{{ $k }}" {{ old('availability_status', $part->availability_status ?? 'in_stock') === $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>
            @error('availability_status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        {{-- Price, Currency & Stock --}}
        <div class="col-md-4">
            <label class="form-label">Price</label>
            <input type="number" name="price" step="0.01" min="0"
                   value="{{ old('price', $part->price ?? '') }}"
                   class="form-control" placeholder="0.00">
            @error('price')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Currency</label>
            <input type="text" name="currency" value="{{ old('currency', $part->currency ?? 'USD') }}"
                   class="form-control" maxlength="10" placeholder="USD">
            @error('currency')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Stock Quantity</label>
            <input type="number" name="stock_quantity" min="0"
                   value="{{ old('stock_quantity', $part->stock_quantity ?? 0) }}"
                   class="form-control">
            @error('stock_quantity')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        {{-- Description & Compatibility --}}
        <div class="col-md-12">
            <label class="form-label">Description</label>
            <textarea name="description" rows="4" class="form-control"
                      placeholder="Describe this spare part…">{{ old('description', $part->description ?? '') }}</textarea>
            @error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-12">
            <label class="form-label">Compatibility Notes</label>
            <textarea name="compatibility_notes" rows="2" class="form-control"
                      placeholder="e.g. Fits all 2020–2024 YZF-R1 models. Not compatible with R1M.">{{ old('compatibility_notes', $part->compatibility_notes ?? '') }}</textarea>
            @error('compatibility_notes')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        {{-- Compatible Motorcycles --}}
        <div class="col-md-12">
            <label class="form-label">Compatible Motorcycles</label>
            <div class="border rounded-3 p-3 bg-light" style="max-height:200px; overflow-y:auto;">
                @if($motorcycles->isEmpty())
                    <p class="text-muted small mb-0">No published motorcycles available.</p>
                @else
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-2">
                        @foreach($motorcycles as $moto)
                        <div class="col">
                            <div class="form-check">
                                <input type="checkbox" name="compatible_motorcycles[]" value="{{ $moto->id }}"
                                       id="moto_{{ $moto->id }}"
                                       class="form-check-input"
                                       {{ in_array($moto->id, $selected ?? []) ? 'checked' : '' }}>
                                <label class="form-check-label small" for="moto_{{ $moto->id }}">
                                    {{ $moto->brand->name ?? '' }} {{ $moto->model_name }} ({{ $moto->year }})
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <small class="text-muted d-block mt-2">Select all motorcycle models this part is compatible with.</small>
        </div>

        {{-- Main image --}}
        <div class="col-md-12">
            <label class="form-label">Main Image</label>
            @if(isset($part) && $part->image_url)
                <div class="mb-3">
                    <img src="{{ $part->image_url }}" alt="{{ $part->name }}"
                         class="img-thumbnail" style="height: 120px; width: 160px; object-fit: contain; background: #f8fafc;">
                </div>
            @endif
            <input type="file" name="image" accept="image/*" class="form-control">
            <small class="text-muted d-block mt-1">JPG, PNG, WEBP or GIF — max 3MB.{{ isset($part) && $part->image_path ? ' Upload to replace.' : '' }}</small>
            @error('image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        {{-- Additional images --}}
        <div class="col-md-12">
            <label class="form-label">Additional Images (Gallery)</label>
            @if(isset($part) && !empty($part->additional_image_urls))
                <div class="d-flex gap-2 flex-wrap mb-3">
                    @foreach($part->additional_image_urls as $img)
                        <img src="{{ $img }}" class="img-thumbnail" style="height: 60px; width: 80px; object-fit: contain; background: #f8fafc;">
                    @endforeach
                </div>
            @endif
            <input type="file" name="additional_images[]" accept="image/*" multiple class="form-control">
            <small class="text-muted d-block mt-1">Select multiple files. These replace existing gallery images.</small>
            @error('additional_images.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

{{-- Published toggle (admin only) --}}
<div class="panel border-0 shadow-sm mt-4 p-3">
    @if(auth()->user()->isAdmin())
        <div class="form-check form-switch d-flex align-items-center gap-2">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" id="is_published" name="is_published" value="1"
                   class="form-check-input" style="width: 40px; height: 20px;"
                   {{ old('is_published', $part->is_published ?? false) ? 'checked' : '' }}>
            <label class="form-check-label fw-bold ms-2" for="is_published">
                <i class="bi bi-eye me-1 text-primary"></i> Published (visible on public site)
            </label>
        </div>
    @else
        <div class="d-flex align-items-center gap-2 text-warning">
            <i class="bi bi-clock-history"></i>
            <span class="small fw-bold">Saved as Draft — an Admin must approve and publish this part.</span>
        </div>
    @endif
</div>
