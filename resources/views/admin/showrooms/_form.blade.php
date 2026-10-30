{{-- Showrooms shared form --}}
<div class="panel border-0 shadow-sm p-4">
    <div class="row g-4">

        <div class="col-md-12">
            <label class="form-label">Brand Focus</label>
            <select name="brand_id" class="form-select">
                <option value="">Multi-brand Group</option>
                @foreach($brands as $b)
                    <option value="{{ $b->id }}" {{ (string) old('brand_id', $showroom->brand_id ?? '') === (string) $b->id ? 'selected' : '' }}>
                        {{ $b->name }}
                    </option>
                @endforeach
            </select>
            @error('brand_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-12">
            <label class="form-label">Showroom Name *</label>
            <input type="text" name="name" value="{{ old('name', $showroom->name ?? '') }}"
                   class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                   placeholder="e.g. MOTOVEX Cairo">
            @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-12">
            <label class="form-label">Location *</label>
            <input type="text" name="location" value="{{ old('location', $showroom->location ?? '') }}"
                   class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}"
                   placeholder="City, Country">
            @error('location')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $showroom->phone ?? '') }}"
                   class="form-control" placeholder="+20 10…">
            @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email', $showroom->email ?? '') }}"
                   class="form-control" placeholder="showroom@motovex.com">
            @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-12">
            <label class="form-label">Opening Hours</label>
            <textarea name="opening_hours" rows="3" class="form-control"
                      placeholder="Sat – Thu: 9:00 AM – 9:00 PM">{{ old('opening_hours', $showroom->opening_hours ?? '') }}</textarea>
            @error('opening_hours')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-12">
            <label class="form-label">Image URL</label>
            <input type="url" name="image_url" value="{{ old('image_url', $showroom->image_url ?? '') }}"
                   class="form-control" placeholder="https://…">
            @error('image_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-12 mt-4">
            <div class="form-check form-switch d-flex align-items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" id="is_active" name="is_active" value="1"
                       class="form-check-input" style="width: 40px; height: 20px;"
                       {{ old('is_active', $showroom->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label fw-bold ms-2" for="is_active">
                    <i class="bi bi-shop me-1 text-primary"></i> Showroom is Active
                </label>
            </div>
        </div>
    </div>
</div>
