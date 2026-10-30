@extends('layouts.admin')
@section('title', $motorcycle->model_name)

@section('styles')
<style>
/* ── Layout ── */
.moto-hero {
    display: grid;
    grid-template-columns: 420px 1fr;
    gap: 24px;
    align-items: start;
}
@media(max-width:960px){ .moto-hero { grid-template-columns: 1fr; } }

/* ── Gallery ── */
.gallery-main {
    position: relative;
    aspect-ratio: 4/3;
    border-radius: 16px;
    overflow: hidden;
    background: #f1f5f9;
    border: 1px solid var(--line);
}
.gallery-main img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
    cursor: zoom-in;
    transition: opacity .2s;
}
.gallery-thumbs {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
    margin-top: 10px;
}
.g-thumb {
    aspect-ratio: 4/3;
    border-radius: 10px;
    overflow: hidden;
    border: 2px solid transparent;
    cursor: pointer;
    background: #f1f5f9;
    transition: border-color .15s, box-shadow .15s;
}
.g-thumb img { width:100%; height:100%; object-fit:cover; display:block; }
.g-thumb.active { border-color: var(--purple); box-shadow: 0 0 0 3px rgba(124,58,237,.18); }
.g-thumb:hover:not(.active) { border-color: var(--purple-border); }
.g-thumb-empty {
    aspect-ratio: 4/3;
    border-radius: 10px;
    border: 2px dashed var(--line);
    display: grid; place-items: center;
    color: #cbd5e1; font-size: 13px;
}

/* ── Spec grid ── */
.spec-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-top: 20px;
}
@media(max-width:640px){ .spec-grid { grid-template-columns: 1fr 1fr; } }
.spec-item {
    padding: 12px 14px;
    border-radius: 12px;
    background: #f8fafc;
    border: 1px solid var(--line);
}
.spec-item dt {
    font-size: 10px; font-weight: 600;
    text-transform: uppercase; letter-spacing: .8px;
    color: var(--muted); margin-bottom: 4px;
}
.spec-item dd {
    font-size: 14px; font-weight: 800;
    color: var(--text);
}

/* ── Info rows ── */
.ir { display:flex; align-items:flex-start; justify-content:space-between; gap:16px; padding:9px 0; border-bottom:1px solid #f1f5f9; }
.ir:last-child { border-bottom:none; }
.ir-lbl { font-size:12px; font-weight:500; color:var(--muted); flex-shrink:0; }
.ir-val { font-size:12px; font-weight:600; color:var(--text); text-align:right; }

/* ── Lightbox ── */
#lightbox {
    display:none; position:fixed; inset:0; z-index:999;
    background:rgba(0,0,0,.92); backdrop-filter:blur(6px);
    align-items:center; justify-content:center; padding:24px;
}
#lightbox.open { display:flex; }
#lightbox img {
    max-width:90vw; max-height:88vh;
    border-radius:12px; box-shadow:0 32px 80px rgba(0,0,0,.5);
    object-fit:contain;
}
#lightbox-close {
    position:absolute; top:18px; right:22px;
    width:40px; height:40px; border-radius:50%;
    background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.2);
    color:#fff; font-size:16px; cursor:pointer;
    display:grid; place-items:center;
    transition:background .15s;
}
#lightbox-close:hover { background:rgba(255,255,255,.22); }
</style>
@endsection

@section('content')

@if(session('success'))
<div class="flash-success" style="margin-bottom:18px"><i class="fa-solid fa-circle-check"></i>{{ session('success') }}</div>
@endif

{{-- ── Page header ── --}}
<div class="page-header">
    <div>
        <a href="{{ route('admin.motorcycles.index') }}"
           style="font-size:12px;color:var(--muted);display:inline-flex;align-items:center;gap:6px;margin-bottom:6px;transition:color .15s;"
           onmouseover="this.style.color='var(--purple)'" onmouseout="this.style.color='var(--muted)'">
            <i class="fa-solid fa-arrow-left" style="font-size:10px"></i> Back to Motorcycles
        </a>
        <h1>{{ $motorcycle->model_name }}</h1>
        <p style="font-size:12px;color:var(--muted);margin-top:3px">
            {{ $motorcycle->brand->name }} · {{ ucfirst($motorcycle->category ?? '') }}
        </p>
    </div>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
        <a href="{{ route('motorcycles.show', $motorcycle->slug) }}" target="_blank" class="btn-ghost">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> View on Site
        </a>
        <a href="{{ route('admin.motorcycles.edit', $motorcycle) }}" class="btn-primary">
            <i class="fa-solid fa-pen-to-square"></i> Edit Motorcycle
        </a>
        @can('delete', $motorcycle)
        <button type="button" class="btn-danger" id="show-delete-btn"
                data-name="{{ $motorcycle->model_name }}">
            <i class="fa-solid fa-trash-can"></i> Delete
        </button>
        <form id="show-delete-form" method="POST"
              action="{{ route('admin.motorcycles.destroy', $motorcycle) }}"
              style="display:none">
            @csrf @method('DELETE')
        </form>
        @endcan
    </div>
</div>

{{-- ── Badges row ── --}}
<div style="display:flex;flex-wrap:wrap;gap:7px;margin-bottom:22px;">
    <span class="badge {{ $motorcycle->is_published ? 'badge-green' : 'badge-yellow' }}">
        {{ $motorcycle->is_published ? 'Published' : 'Draft' }}
    </span>
    @if($motorcycle->is_featured)
        <span class="badge badge-purple">Featured</span>
    @endif
    <span class="badge {{ $motorcycle->availability_status === 'available' ? 'badge-green' : ($motorcycle->availability_status === 'sold' ? 'badge-red' : 'badge-muted') }}">
        {{ ucfirst($motorcycle->availability_status ?? 'available') }}
    </span>
    <span class="badge badge-muted">{{ ucfirst($motorcycle->condition ?? 'new') }}</span>
</div>

{{-- ── Main grid ── --}}
<div class="moto-hero">

    {{-- Gallery column --}}
    <div>
        @php
            // Use the accessor URL for main image
            $mainImg = $motorcycle->image_url ?: null;

            // additional_images accessor already returns full URLs
            $extras = $motorcycle->additional_images ?? [];

            // Build full gallery: main first, then extras
            $allImgs = array_values(array_filter(array_merge(
                $mainImg ? [$mainImg] : [],
                $extras
            )));
        @endphp

        {{-- Main image --}}
        <div class="gallery-main">
            @if(count($allImgs))
                <img id="galleryMain" src="{{ $allImgs[0] }}" alt="{{ $motorcycle->model_name }}"
                     onclick="openLightbox(this.src)">
                {{-- Zoom hint --}}
                <div style="position:absolute;bottom:10px;right:10px;background:rgba(0,0,0,.5);color:#fff;font-size:10px;font-weight:600;padding:4px 9px;border-radius:20px;pointer-events:none;display:flex;align-items:center;gap:5px;">
                    <i class="fa-solid fa-magnifying-glass-plus" style="font-size:9px;"></i> Click to zoom
                </div>
            @else
                <div style="width:100%;height:100%;display:grid;place-items:center;flex-direction:column;gap:10px;">
                    <i class="fa-solid fa-motorcycle" style="font-size:56px;color:#cbd5e1;"></i>
                    <p style="font-size:12px;color:#94a3b8;margin-top:8px;">No image available</p>
                </div>
            @endif
        </div>

        {{-- Thumbnails ── --}}
        @if(count($allImgs) > 1)
        <div class="gallery-thumbs">
            @foreach($allImgs as $i => $img)
            <div class="g-thumb {{ $i === 0 ? 'active' : '' }}" data-src="{{ $img }}" onclick="switchImage(this)">
                <img src="{{ $img }}" alt="Photo {{ $i+1 }}" loading="lazy">
            </div>
            @endforeach
            {{-- Fill empty slots up to 4 --}}
            @for($j = count($allImgs); $j < 4 && $j < 4; $j++)
            <div class="g-thumb-empty"><i class="fa-regular fa-image"></i></div>
            @endfor
        </div>
        @elseif(count($allImgs) === 0)
            <p style="font-size:12px;color:var(--muted);margin-top:8px;text-align:center;">No photos uploaded yet.</p>
        @endif

        {{-- Photo count ── --}}
        @if(count($allImgs) > 0)
        <p style="font-size:11px;color:var(--muted);margin-top:8px;text-align:center;">
            <i class="fa-solid fa-images" style="margin-right:4px;color:var(--purple);"></i>
            {{ count($allImgs) }} photo{{ count($allImgs) !== 1 ? 's' : '' }}
        </p>
        @endif
    </div>

    {{-- Details column --}}
    <div>
        <div class="admin-panel">

            {{-- Name + description --}}
            <div style="margin-bottom:18px;padding-bottom:18px;border-bottom:1px solid var(--line);">
                <h2 style="font-size:20px;font-weight:900;color:var(--text);letter-spacing:-.3px;margin-bottom:6px;">
                    {{ $motorcycle->model_name }}
                </h2>
                @if($motorcycle->short_description)
                <p style="font-size:13px;font-weight:600;color:var(--subtext);margin-bottom:8px;">
                    {{ $motorcycle->short_description }}
                </p>
                @endif
                @if($motorcycle->description)
                <p style="font-size:13px;line-height:1.8;color:var(--muted);">{{ $motorcycle->description }}</p>
                @endif
            </div>

            {{-- Specs grid ── --}}
            <dl class="spec-grid">
                <div class="spec-item">
                    <dt><i class="fa-solid fa-tag" style="color:var(--purple);margin-right:4px;"></i>Price</dt>
                    <dd style="color:var(--purple);">{{ $motorcycle->price ? (($motorcycle->currency ?: 'USD') . ' ' . number_format($motorcycle->price)) : '—' }}</dd>
                </div>
                <div class="spec-item">
                    <dt><i class="fa-regular fa-calendar" style="color:var(--purple);margin-right:4px;"></i>Year</dt>
                    <dd>{{ $motorcycle->year ?: '—' }}</dd>
                </div>
                <div class="spec-item">
                    <dt><i class="fa-solid fa-motorcycle" style="color:var(--purple);margin-right:4px;"></i>Category</dt>
                    <dd>{{ $motorcycle->category ? ucfirst($motorcycle->category) : '—' }}</dd>
                </div>
                <div class="spec-item">
                    <dt><i class="fa-solid fa-gauge-high" style="color:var(--purple);margin-right:4px;"></i>Engine (CC)</dt>
                    <dd>{{ $motorcycle->engine_capacity ? number_format($motorcycle->engine_capacity) . ' cc' : '—' }}</dd>
                </div>
                <div class="spec-item">
                    <dt><i class="fa-solid fa-circle-check" style="color:var(--purple);margin-right:4px;"></i>Availability</dt>
                    <dd>{{ ucfirst($motorcycle->availability_status ?? 'available') }}</dd>
                </div>
                <div class="spec-item">
                    <dt><i class="fa-solid fa-shield-halved" style="color:var(--purple);margin-right:4px;"></i>Condition</dt>
                    <dd>{{ ucfirst($motorcycle->condition ?? 'new') }}</dd>
                </div>
                @if($motorcycle->horsepower)
                <div class="spec-item">
                    <dt>Horsepower</dt>
                    <dd>{{ $motorcycle->horsepower }} HP</dd>
                </div>
                @endif
                @if($motorcycle->torque)
                <div class="spec-item">
                    <dt>Torque</dt>
                    <dd>{{ $motorcycle->torque }} Nm</dd>
                </div>
                @endif
                @if($motorcycle->transmission)
                <div class="spec-item">
                    <dt>Transmission</dt>
                    <dd>{{ ucfirst($motorcycle->transmission) }}</dd>
                </div>
                @endif
                @if($motorcycle->rental_price_per_day)
                <div class="spec-item">
                    <dt>Rental / Day</dt>
                    <dd>{{ $motorcycle->currency ?: 'USD' }} {{ number_format($motorcycle->rental_price_per_day, 2) }}</dd>
                </div>
                @endif
            </dl>

            {{-- Additional info ── --}}
            <div style="margin-top:20px;padding-top:18px;border-top:1px solid var(--line);">
                <div class="ir">
                    <span class="ir-lbl">Brand</span>
                    <span class="ir-val">
                        <a href="{{ route('admin.brands.show', $motorcycle->brand) }}" style="color:var(--purple);">{{ $motorcycle->brand->name }}</a>
                    </span>
                </div>
                <div class="ir">
                    <span class="ir-lbl">Created by</span>
                    <span class="ir-val">{{ $motorcycle->creator?->name ?? '—' }}</span>
                </div>
                @if($motorcycle->assignedManager)
                <div class="ir">
                    <span class="ir-lbl">Assigned Manager</span>
                    <span class="ir-val">{{ $motorcycle->assignedManager->name }}</span>
                </div>
                @endif
                <div class="ir">
                    <span class="ir-lbl">Added on</span>
                    <span class="ir-val">{{ $motorcycle->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="ir">
                    <span class="ir-lbl">Last updated</span>
                    <span class="ir-val">{{ $motorcycle->updated_at->format('d M Y, H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Lightbox ── --}}
<div id="lightbox" role="dialog" aria-modal="true" onclick="if(event.target===this)closeLightbox()">
    <button id="lightbox-close" onclick="closeLightbox()" aria-label="Close">
        <i class="fa-solid fa-xmark"></i>
    </button>
    <img id="lightbox-img" src="" alt="Full size photo">
</div>

@endsection

@section('scripts')
<script>
// ── Gallery switcher ──────────────────────────────────────
function switchImage(thumb) {
    document.getElementById('galleryMain').src = thumb.dataset.src;
    document.querySelectorAll('.g-thumb').forEach(function(t){ t.classList.remove('active'); });
    thumb.classList.add('active');
}

// ── Lightbox ──────────────────────────────────────────────
function openLightbox(src) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('open');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e){
    if(e.key === 'Escape') closeLightbox();
});

// ── Delete modal ──────────────────────────────────────────
(function () {
    var btn  = document.getElementById('show-delete-btn');
    var form = document.getElementById('show-delete-form');
    if (!btn) return;

    var modal = document.createElement('div');
    modal.innerHTML = `
        <div id="sdm-backdrop" style="position:fixed;inset:0;background:rgba(0,0,0,.45);backdrop-filter:blur(3px);z-index:9998"></div>
        <div style="position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;padding:16px">
            <div style="background:#fff;border-radius:18px;padding:28px 28px 24px;width:100%;max-width:400px;box-shadow:0 25px 60px rgba(0,0,0,.18)">
                <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:18px">
                    <div style="flex-shrink:0;width:42px;height:42px;border-radius:12px;background:#FEF2F2;border:1px solid #FECACA;display:grid;place-items:center">
                        <i class="fa-solid fa-triangle-exclamation" style="color:#DC2626;font-size:16px"></i>
                    </div>
                    <div>
                        <p style="font-size:15px;font-weight:700;color:#111;margin:0 0 4px">Delete Motorcycle</p>
                        <p style="font-size:13px;color:#64748b;margin:0;line-height:1.5">
                            Are you sure you want to delete <strong style="color:#111">${btn.dataset.name}</strong>?
                            This action <span style="color:#DC2626;font-weight:600">cannot be undone</span>.
                        </p>
                    </div>
                </div>
                <div style="display:flex;gap:8px;justify-content:flex-end">
                    <button id="sdm-cancel" type="button"
                            style="height:38px;padding:0 18px;font-size:13px;font-weight:600;border-radius:10px;border:1px solid #e2e8f0;background:#fff;color:#374151;cursor:pointer">
                        Cancel
                    </button>
                    <button id="sdm-confirm" type="button"
                            style="height:38px;padding:0 18px;font-size:13px;font-weight:600;border-radius:10px;border:none;background:#DC2626;color:#fff;cursor:pointer;display:inline-flex;align-items:center;gap:6px">
                        <i class="fa-solid fa-trash-can"></i> Yes, Delete
                    </button>
                </div>
            </div>
        </div>`;
    modal.style.display = 'none';
    document.body.appendChild(modal);

    function openModal()  { modal.style.display = 'block'; document.body.style.overflow = 'hidden'; }
    function closeModal() { modal.style.display = 'none';  document.body.style.overflow = ''; }

    btn.addEventListener('click', openModal);
    modal.querySelector('#sdm-cancel').addEventListener('click', closeModal);
    modal.querySelector('#sdm-backdrop').addEventListener('click', closeModal);
    modal.querySelector('#sdm-confirm').addEventListener('click', function () {
        this.disabled = true;
        this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Deleting…';
        form.submit();
    });
    document.addEventListener('keydown', function(e){
        if (e.key === 'Escape' && modal.style.display !== 'none') closeModal();
    });
})();
</script>
@endsection
