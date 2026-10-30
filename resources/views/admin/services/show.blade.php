@extends('layouts.admin')
@section('title', $service->title)

@section('styles')
<style>
.service-grid { display: grid; grid-template-columns: 320px 1fr; gap: 22px; align-items: start; }
@media(max-width:900px){ .service-grid{ grid-template-columns: 1fr; } }

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
.info-lbl { font-size: 11px; font-weight: 600; color: var(--muted); flex-shrink: 0; min-width: 110px; }
.info-val { font-size: 12.5px; color: var(--dark); font-weight: 500; text-align: right; }

.back-btn {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 600; color: var(--muted);
    text-decoration: none; margin-bottom: 16px; transition: color .15s;
}
.back-btn:hover { color: var(--primary); }
</style>
@endsection

@section('content')

<a href="{{ route('admin.services.index') }}" class="back-btn">
    <i class="bi bi-arrow-left" style="font-size:11px;"></i> Back to Services
</a>

<div class="page-header">
    <div>
        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
            <h1>{{ $service->title }}</h1>
            <span class="badge {{ $service->is_active ? 'badge-green' : 'badge-red' }}">
                {{ $service->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
        <p style="font-family:ui-monospace,monospace;font-size:11.5px;">{{ $service->slug }}</p>
    </div>
    <div style="display:flex;gap:10px;">
        @can('update', $service)
        <a href="{{ route('admin.services.edit', $service) }}" class="btn-primary-alt">
            <i class="bi bi-pencil-square"></i> Edit
        </a>
        @endcan
        @can('delete', $service)
        <form method="POST" action="{{ route('admin.services.destroy', $service) }}"
              onsubmit="return confirm('Delete this service? This action cannot be undone.')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-danger">
                <i class="bi bi-trash3"></i> Delete
            </button>
        </form>
        @endcan
    </div>
</div>

<div class="service-grid">

    {{-- ── Image / Icon ── --}}
    <div>
        <div class="detail-card">
            @if($service->image_url)
                <img src="{{ $service->image_url }}" alt="{{ $service->title }}"
                     style="width:100%;aspect-ratio:4/3;object-fit:cover;display:block;">
            @else
                <div style="width:100%;aspect-ratio:4/3;background:linear-gradient(135deg,#eff6ff,#e0e7ff);display:grid;place-items:center;">
                    <i class="bi bi-tools" style="font-size:56px;color:#bfdbfe;opacity:.7;"></i>
                </div>
            @endif
        </div>

        {{-- CTA info --}}
        @if($service->cta_label || $service->cta_route)
        <div class="detail-card" style="margin-top:16px;">
            <div class="detail-card-head">
                <i class="bi bi-cursor-fill"></i><span>Call to Action</span>
            </div>
            <div class="detail-card-body">
                @if($service->cta_label)
                <div style="font-size:13px;font-weight:700;color:var(--dark);margin-bottom:6px;">
                    {{ $service->cta_label }}
                </div>
                @endif
                @if($service->cta_route)
                <a href="{{ $service->cta_route }}" target="_blank" class="tbl-link"
                   style="font-size:12px;word-break:break-all;">
                    {{ $service->cta_route }} <i class="bi bi-arrow-up-right-square" style="font-size:10px;"></i>
                </a>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- ── Info ── --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Description --}}
        <div class="detail-card">
            <div class="detail-card-head">
                <i class="bi bi-file-text"></i><span>Description</span>
            </div>
            <div class="detail-card-body">
                <p style="font-size:13.5px;line-height:1.75;color:var(--dark);margin:0;">{{ $service->description }}</p>
                @if($service->full_description)
                <p style="font-size:13px;line-height:1.75;color:var(--muted);margin:14px 0 0;">{{ $service->full_description }}</p>
                @endif
            </div>
        </div>

        {{-- Meta --}}
        <div class="detail-card">
            <div class="detail-card-head">
                <i class="bi bi-sliders"></i><span>Service Details</span>
            </div>
            <div class="detail-card-body" style="padding-top:8px;padding-bottom:8px;">
                <div class="info-row">
                    <span class="info-lbl">Status</span>
                    <span class="info-val">
                        <span class="badge {{ $service->is_active ? 'badge-green' : 'badge-red' }}">
                            {{ $service->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-lbl">Display Order</span>
                    <span class="info-val">{{ $service->sort_order }}</span>
                </div>
                @if($service->icon)
                <div class="info-row">
                    <span class="info-lbl">Icon Class</span>
                    <span class="info-val" style="font-family:ui-monospace,monospace;font-size:11px;">{{ $service->icon }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-lbl">Slug</span>
                    <span class="info-val" style="font-family:ui-monospace,monospace;font-size:11px;">{{ $service->slug }}</span>
                </div>
                <div class="info-row">
                    <span class="info-lbl">Created</span>
                    <span class="info-val">{{ $service->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
