@extends('layouts.admin')
@section('title', $showroom->name)

@section('styles')
<style>
.showroom-grid { display: grid; grid-template-columns: 340px 1fr; gap: 22px; align-items: start; }
@media(max-width:900px){ .showroom-grid{ grid-template-columns: 1fr; } }

.detail-card {
    background: #fff; border: 1px solid var(--border);
    border-radius: 14px; overflow: hidden;
}
.detail-card-head {
    padding: 14px 20px; border-bottom: 1px solid var(--border);
    background: #f8fafc; display: flex; align-items: center; gap: 9px;
}
.detail-card-head i { color: var(--primary); font-size: 13px; }
.detail-card-head span { font-size: 13px; font-weight: 700; color: var(--dark); }
.detail-card-body { padding: 20px; }

.info-row {
    display: flex; align-items: flex-start; justify-content: space-between;
    gap: 12px; padding: 9px 0; border-bottom: 1px solid #f1f5f9;
}
.info-row:last-child { border-bottom: none; padding-bottom: 0; }
.info-lbl { font-size: 11px; font-weight: 600; color: var(--muted); flex-shrink: 0; min-width: 120px; }
.info-val { font-size: 12.5px; color: var(--dark); font-weight: 500; text-align: right; }

.back-btn {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 600; color: var(--muted);
    text-decoration: none; margin-bottom: 16px; transition: color .15s;
}
.back-btn:hover { color: var(--primary); }

.contact-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px;
}
.contact-item:last-child { border-bottom: none; }
.contact-item i { width: 16px; color: var(--primary); flex-shrink: 0; }
</style>
@endsection

@section('content')

<a href="{{ route('admin.showrooms.index') }}" class="back-btn">
    <i class="bi bi-arrow-left" style="font-size:11px;"></i> Back to Showrooms
</a>

<div class="page-header">
    <div>
        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
            <h1>{{ $showroom->name }}</h1>
            <span class="badge {{ $showroom->is_active ? 'badge-green' : 'badge-red' }}">
                {{ $showroom->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
        <p><i class="bi bi-geo-alt me-1"></i>{{ $showroom->location }}</p>
    </div>
    <div style="display:flex;gap:10px;">
        @can('update', $showroom)
        <a href="{{ route('admin.showrooms.edit', $showroom) }}" class="btn-primary-alt">
            <i class="bi bi-pencil-square"></i> Edit
        </a>
        @endcan
        @can('delete', $showroom)
        <form method="POST" action="{{ route('admin.showrooms.destroy', $showroom) }}"
              onsubmit="return confirm('Delete this showroom? This action cannot be undone.')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-danger">
                <i class="bi bi-trash3"></i> Delete
            </button>
        </form>
        @endcan
    </div>
</div>

<div class="showroom-grid">

    {{-- ── Image ── --}}
    <div>
        <div class="detail-card">
            @if($showroom->image_url)
                <img src="{{ $showroom->image_url }}" alt="{{ $showroom->name }}"
                     style="width:100%;aspect-ratio:4/3;object-fit:cover;display:block;">
            @else
                <div style="width:100%;aspect-ratio:4/3;background:linear-gradient(135deg,#f0f7ff,#e0efff);display:grid;place-items:center;">
                    <i class="bi bi-shop" style="font-size:56px;color:#bfdbfe;opacity:.7;"></i>
                </div>
            @endif
        </div>

        {{-- Contact info --}}
        <div class="detail-card" style="margin-top:16px;">
            <div class="detail-card-head">
                <i class="bi bi-telephone"></i><span>Contact Information</span>
            </div>
            <div class="detail-card-body" style="padding-top:4px;padding-bottom:4px;">
                @if($showroom->phone)
                <div class="contact-item">
                    <i class="bi bi-telephone-fill"></i>
                    <a href="tel:{{ $showroom->phone }}" class="tbl-link">{{ $showroom->phone }}</a>
                </div>
                @endif
                @if($showroom->email)
                <div class="contact-item">
                    <i class="bi bi-envelope-fill"></i>
                    <a href="mailto:{{ $showroom->email }}" class="tbl-link">{{ $showroom->email }}</a>
                </div>
                @endif
                @if(!$showroom->phone && !$showroom->email)
                <p style="font-size:12px;color:var(--muted);margin:0;padding:8px 0;">No contact info provided.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Details ── --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Overview --}}
        <div class="detail-card">
            <div class="detail-card-head">
                <i class="bi bi-info-circle"></i><span>Showroom Details</span>
            </div>
            <div class="detail-card-body" style="padding-top:8px;padding-bottom:8px;">
                <div class="info-row">
                    <span class="info-lbl">Brand Focus</span>
                    <span class="info-val">
                        @if($showroom->brand)
                            <span class="badge badge-blue">{{ $showroom->brand->name }}</span>
                        @else
                            Multi-brand
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-lbl">Location</span>
                    <span class="info-val">{{ $showroom->location }}</span>
                </div>
                <div class="info-row">
                    <span class="info-lbl">Status</span>
                    <span class="info-val">
                        <span class="badge {{ $showroom->is_active ? 'badge-green' : 'badge-red' }}">
                            {{ $showroom->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-lbl">Slug</span>
                    <span class="info-val" style="font-family:ui-monospace,monospace;font-size:11px;">{{ $showroom->slug }}</span>
                </div>
                <div class="info-row">
                    <span class="info-lbl">Added On</span>
                    <span class="info-val">{{ $showroom->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        {{-- Opening hours --}}
        @if($showroom->opening_hours)
        <div class="detail-card">
            <div class="detail-card-head">
                <i class="bi bi-clock"></i><span>Opening Hours</span>
            </div>
            <div class="detail-card-body">
                <p style="font-size:13px;line-height:1.75;color:var(--dark);margin:0;white-space:pre-line;">{{ $showroom->opening_hours }}</p>
            </div>
        </div>
        @endif

        {{-- Linked test rides --}}
        @if(isset($testRidesCount))
        <div class="detail-card">
            <div class="detail-card-head">
                <i class="bi bi-calendar-check"></i><span>Activity</span>
            </div>
            <div class="detail-card-body">
                <div style="display:flex;align-items:center;gap:12px;padding:10px;background:#f8fafc;border-radius:10px;border:1px solid var(--border);">
                    <div style="width:40px;height:40px;border-radius:10px;background:#eff6ff;color:#2563eb;display:grid;place-items:center;font-size:16px;">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <div>
                        <div style="font-size:20px;font-weight:800;color:var(--dark);">{{ $testRidesCount }}</div>
                        <div style="font-size:11px;color:var(--muted);">Test ride bookings</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
