{{-- Showrooms shared form --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">

    <div style="grid-column:span 2">
        <label class="form-label">Brand Focus</label>
        <select name="brand_id" class="form-select">
            <option value="">Multi-brand Group</option>
            @foreach($brands as $b)
                <option value="{{ $b->id }}" {{ (string) old('brand_id', $showroom->brand_id ?? '') === (string) $b->id ? 'selected' : '' }}>
                    {{ $b->name }}
                </option>
            @endforeach
        </select>
        @error('brand_id')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div style="grid-column:span 2">
        <label class="form-label">Showroom Name *</label>
        <input type="text" name="name" value="{{ old('name', $showroom->name ?? '') }}"
               class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
               placeholder="e.g. MOTOVEX Cairo">
        @error('name')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div style="grid-column:span 2">
        <label class="form-label">Location *</label>
        <input type="text" name="location" value="{{ old('location', $showroom->location ?? '') }}"
               class="form-input {{ $errors->has('location') ? 'is-invalid' : '' }}"
               placeholder="City, Country">
        @error('location')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="form-label">Phone</label>
        <input type="text" name="phone" value="{{ old('phone', $showroom->phone ?? '') }}"
               class="form-input" placeholder="+20 10…">
        @error('phone')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email', $showroom->email ?? '') }}"
               class="form-input" placeholder="showroom@motovex.com">
        @error('email')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div style="grid-column:span 2">
        <label class="form-label">Opening Hours</label>
        <textarea name="opening_hours" rows="3" class="form-textarea"
                  placeholder="Sat – Thu: 9:00 AM – 9:00 PM">{{ old('opening_hours', $showroom->opening_hours ?? '') }}</textarea>
        @error('opening_hours')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div style="grid-column:span 2">
        <label class="form-label">Image URL</label>
        <input type="url" name="image_url" value="{{ old('image_url', $showroom->image_url ?? '') }}"
               class="form-input" placeholder="https://…">
        @error('image_url')<p class="form-error">{{ $message }}</p>@enderror
    </div>

</div>

<div style="display:flex;align-items:center;gap:9px;margin-top:16px">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" id="is_active" name="is_active" value="1"
           {{ old('is_active', $showroom->is_active ?? true) ? 'checked' : '' }}
           style="width:16px;height:16px;accent-color:#9b5cff;cursor:pointer">
    <label for="is_active" style="font-size:13px;color:#c8c3da;cursor:pointer">Active</label>
</div>
