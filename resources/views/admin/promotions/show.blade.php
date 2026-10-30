@extends('layouts.admin')
@section('title', $promotion->title)

@section('styles')
<style>
.promo-grid { display: grid; grid-template-columns: 340px 1fr; gap: 22px; align-items: start; }
@media(max-width:900px){ .promo-grid{ grid-template-columns: 1fr; } }

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

<a href="{{ route('admin.promotions.index') }}" class="back-btn">
    <i class="bi bi-arrow-left" style="font-size:11px;"></i> Back to Promotions
</a>

<div class="page-header">
    <div>
        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
            <h1>{{ $promotion->title }}</h1>
            <span class="badge {{ $promotion->is_active ? 'badge-green' : 'badge-red' }}">
                {{ $promotion->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
        <p>{{ $promotion->brand ? $promotion->brand->name : 'Global Promotion' }}</p>
    </div>
    <div style="display:flex;gap:10px;align-items:center;">
        @can('update', $promotion)
        <a href="{{ route('admin.promotions.edit', $promotion) }}" class="btn-primary-alt">
            <i class="bi bi-pencil-square"></i> Edit
        </a>
        @endcan
        @can('delete', $promotion)
        <form method="POST" action="{{ route('admin.promotions.destroy', $promotion) }}"
              onsubmit="return confirm('Delete this promotion? This action cannot be undone.')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-danger">
                <i class="bi bi-trash3"></i> Delete
            </button>
        </form>
        @endcan
    </div>
</div>

<div class="promo-grid">

    {{-- ── Image card ── --}}
    <div>
        <div class="detail-card" style="overflow:hidden;">
            @if($promotion->image_url)
                <img src="{{ $promotion->image_url }}" alt="{{ $promotion->title }}"
                     style="width:100%;aspect-ratio:4/3;object-fit:cover;display:block;">
            @else
                <div style="width:100%;aspect-ratio:4/3;background:linear-gradient(135deg,#eff6ff,#e0e7ff);display:grid;place-items:center;">
                    <i class="bi bi-megaphone" style="font-size:56px;color:#bfdbfe;opacity:.7;"></i>
                </div>
            @endif
        </div>

        {{-- Discount badge if exists --}}
        @if($promotion->discount_value)
        <div style="margin-top:14px;padding:14px 18px;background:linear-gradient(135deg,#fef9c3,#fef3c7);border:1px solid #fde68a;border-radius:12px;display:flex;align-items:center;gap:12px;">
            <div style="width:42px;height:42px;border-radius:10px;background:#f59e0b;display:grid;place-items:center;color:#fff;font-size:18px;flex-shrink:0;">
                <i class="bi bi-tags-fill"></i>
            </div>
            <div>
                <div style="font-size:13px;font-weight:800;color:#92400e;">
                    {{ $promotion->discount_value }}{{ $promotion->discount_type === 'percentage' ? '%' : ' ' . 'OFF' }}
                </div>
                <div style="font-size:11px;color:#b45309;">
                    {{ $promotion->discount_type === 'percentage' ? 'Percentage discount' : 'Fixed amount off' }}
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- ── Info cards ── --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Description --}}
        <div class="detail-card">
            <div class="detail-card-head">
                <i class="bi bi-file-text"></i><span>Description</span>
            </div>
            <div class="detail-card-body">
                <p style="font-size:13.5px;line-height:1.75;color:var(--dark);margin:0;">
                    {{ $promotion->description }}
                </p>
            </div>
        </div>

        {{-- Details --}}
        <div class="detail-card">
            <div class="detail-card-head">
                <i class="bi bi-info-circle"></i><span>Promotion Details</span>
            </div>
            <div class="detail-card-body" style="padding-top:8px;padding-bottom:8px;">
                <div class="info-row">
                    <span class="info-lbl">Brand</span>
                    <span class="info-val">{{ $promotion->brand ? $promotion->brand->name : 'Global (all brands)' }}</span>
                </div>
                @if($promotion->motorcycle)
                <div class="info-row">
                    <span class="info-lbl">Motorcycle</span>
                    <span class="info-val">
                        <a href="{{ route('admin.motorcycles.show', $promotion->motorcycle) }}" class="tbl-link">
                            {{ $promotion->motorcycle->model_name }}
                        </a>
                    </span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-lbl">Start Date</span>
                    <span class="info-val">{{ $promotion->start_date?->format('d M Y') ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-lbl">End Date</span>
                    <span class="info-val">{{ $promotion->end_date?->format('d M Y') ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-lbl">Status</span>
                    <span class="info-val">
                        <span class="badge {{ $promotion->is_active ? 'badge-green' : 'badge-red' }}">
                            {{ $promotion->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-lbl">Slug</span>
                    <span class="info-val" style="font-family:ui-monospace,monospace;font-size:11px;">{{ $promotion->slug }}</span>
                </div>
                <div class="info-row">
                    <span class="info-lbl">Created</span>
                    <span class="info-val">{{ $promotion->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
