{{-- Promotions shared form --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">

    <div style="grid-column:span 2">
        <label class="form-label">Brand</label>
        <select name="brand_id" class="form-select">
            <option value="">Global (all brands)</option>
            @foreach($brands as $b)
                <option value="{{ $b->id }}" {{ (string) old('brand_id', $promotion->brand_id ?? '') === (string) $b->id ? 'selected' : '' }}>
                    {{ $b->name }}
                </option>
            @endforeach
        </select>
        @error('brand_id')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div style="grid-column:span 2">
        <label class="form-label">Title *</label>
        <input type="text" name="title" value="{{ old('title', $promotion->title ?? '') }}"
               class="form-input {{ $errors->has('title') ? 'is-invalid' : '' }}"
               placeholder="Promotion title…">
        @error('title')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div style="grid-column:span 2">
        <label class="form-label">Description *</label>
        <textarea name="description" rows="4"
                  class="form-textarea {{ $errors->has('description') ? 'is-invalid' : '' }}"
                  placeholder="Describe the promotion…">{{ old('description', $promotion->description ?? '') }}</textarea>
        @error('description')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="form-label">Start Date</label>
        <input type="date" name="start_date"
               value="{{ old('start_date', isset($promotion) && $promotion->start_date ? $promotion->start_date->format('Y-m-d') : '') }}"
               class="form-input {{ $errors->has('start_date') ? 'is-invalid' : '' }}">
        @error('start_date')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="form-label">End Date</label>
        <input type="date" name="end_date"
               value="{{ old('end_date', isset($promotion) && $promotion->end_date ? $promotion->end_date->format('Y-m-d') : '') }}"
               class="form-input {{ $errors->has('end_date') ? 'is-invalid' : '' }}">
        @error('end_date')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div style="grid-column:span 2">
        <label class="form-label">Image URL</label>
        <input type="url" name="image_url" value="{{ old('image_url', $promotion->image_url ?? '') }}"
               class="form-input" placeholder="https://…">
        @error('image_url')<p class="form-error">{{ $message }}</p>@enderror
    </div>

</div>

<div style="display:flex;align-items:center;gap:9px;margin-top:16px">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" id="is_active" name="is_active" value="1"
           {{ old('is_active', $promotion->is_active ?? true) ? 'checked' : '' }}
           style="width:16px;height:16px;accent-color:#9b5cff;cursor:pointer">
    <label for="is_active" style="font-size:13px;color:#c8c3da;cursor:pointer">Active</label>
</div>
