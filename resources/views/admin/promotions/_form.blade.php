{{-- Promotions shared form --}}
<div class="panel border-0 shadow-sm p-4">
    <div class="row g-4">

        <div class="col-md-12">
            <label class="form-label">Brand</label>
            <select name="brand_id" class="form-select">
                <option value="">Global (all brands)</option>
                @foreach($brands as $b)
                    <option value="{{ $b->id }}" {{ (string) old('brand_id', $promotion->brand_id ?? '') === (string) $b->id ? 'selected' : '' }}>
                        {{ $b->name }}
                    </option>
                @endforeach
            </select>
            @error('brand_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-12">
            <label class="form-label">Linked Motorcycle (optional)</label>
            <select name="motorcycle_id" class="form-select">
                <option value="">— None —</option>
                @foreach($motorcycles ?? [] as $m)
                    <option value="{{ $m->id }}" {{ (string) old('motorcycle_id', $promotion->motorcycle_id ?? '') === (string) $m->id ? 'selected' : '' }}>
                        {{ $m->model_name }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted d-block mt-1">Used for the public CTA link and to compute discounted prices.</small>
            @error('motorcycle_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-12">
            <label class="form-label">Title *</label>
            <input type="text" name="title" value="{{ old('title', $promotion->title ?? '') }}"
                   class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                   placeholder="Promotion title…">
            @error('title')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-12">
            <label class="form-label">Description *</label>
            <textarea name="description" rows="4"
                      class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                      placeholder="Describe the promotion…">{{ old('description', $promotion->description ?? '') }}</textarea>
            @error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Discount Type</label>
            <select name="discount_type" class="form-select">
                <option value="">No discount</option>
                <option value="percentage" {{ old('discount_type', $promotion->discount_type ?? '') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                <option value="fixed" {{ old('discount_type', $promotion->discount_type ?? '') === 'fixed' ? 'selected' : '' }}>Fixed amount</option>
            </select>
            @error('discount_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Discount Value</label>
            <input type="number" step="0.01" min="0" name="discount_value"
                   value="{{ old('discount_value', $promotion->discount_value ?? '') }}"
                   class="form-control {{ $errors->has('discount_value') ? 'is-invalid' : '' }}"
                   placeholder="e.g. 10 for 10% or 5000 off">
            @error('discount_value')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date"
                   value="{{ old('start_date', isset($promotion) && $promotion->start_date ? $promotion->start_date->format('Y-m-d') : '') }}"
                   class="form-control {{ $errors->has('start_date') ? 'is-invalid' : '' }}">
            @error('start_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">End Date</label>
            <input type="date" name="end_date"
                   value="{{ old('end_date', isset($promotion) && $promotion->end_date ? $promotion->end_date->format('Y-m-d') : '') }}"
                   class="form-control {{ $errors->has('end_date') ? 'is-invalid' : '' }}">
            @error('end_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-12">
            <label class="form-label">Image URL</label>
            <input type="url" name="image_url" value="{{ old('image_url', $promotion->image_url ?? '') }}"
                   class="form-control" placeholder="https://…">
            @error('image_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-12 mt-4">
            <div class="form-check form-switch d-flex align-items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" id="is_active" name="is_active" value="1"
                       class="form-check-input" style="width: 40px; height: 20px;"
                       {{ old('is_active', $promotion->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label fw-bold ms-2" for="is_active">
                    <i class="bi bi-megaphone me-1 text-primary"></i> Promotion is Active
                </label>
            </div>
        </div>

    </div>
</div>
