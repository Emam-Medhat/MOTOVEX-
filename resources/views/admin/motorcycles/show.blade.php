@extends('layouts.admin')
@section('title', $motorcycle->model_name)

@section('styles')
<style>
    .moto-show-grid { display: grid; grid-template-columns: 340px 1fr; gap: 20px; }
    .spec-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; margin-top: 18px; }
    .spec-item dt { font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); margin-bottom: 3px; }
    .spec-item dd { font-size: 14px; font-weight: 700; color: var(--text); }
    .gallery-thumbs { display: flex; flex-wrap: wrap; gap: 7px; padding: 12px; border-top: 1px solid var(--line); }
    .gallery-thumbs img { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid var(--line); }
    @media (max-width: 900px) { .moto-show-grid { grid-template-columns: 1fr; } .spec-grid { grid-template-columns: 1fr 1fr; } }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.motorcycles.index') }}"
           style="font-size:12px;color:var(--muted);display:inline-flex;align-items:center;gap:6px;margin-bottom:6px"
           class="nav-link-back">
            <i class="fa-solid fa-arrow-left" style="font-size:10px"></i> Back to Motorcycles
        </a>
        <h1>{{ $motorcycle->model_name }}</h1>
        <p style="font-size:12px;color:var(--muted);margin-top:3px">
            {{ $motorcycle->brand->name }} · {{ ucfirst($motorcycle->category ?? '') }}
        </p>
    </div>
    <div style="display:flex;gap:10px;align-items:center">
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

<div class="moto-show-grid">

    {{-- Image panel --}}
    <div class="admin-table-wrap" style="overflow:hidden;border-radius:16px">
        @php
            $mainImg = $motorcycle->image_path
                ? asset('storage/' . $motorcycle->image_path)
                : ($motorcycle->image_url ?: null);
        @endphp
        @if($mainImg)
            <img src="{{ $mainImg }}" alt="{{ $motorcycle->model_name }}"
                 style="width:100%;aspect-ratio:4/3;object-fit:cover;display:block">
        @else
            <div style="width:100%;aspect-ratio:4/3;background:var(--purple-bg);display:grid;place-items:center;font-size:48px;color:var(--purple)">
                <i class="fa-solid fa-motorcycle"></i>
            </div>
        @endif

        @if(is_array($motorcycle->additional_images ?? null) && count($motorcycle->additional_images))
        <div class="gallery-thumbs">
            @foreach($motorcycle->additional_images as $img)
                <img src="{{ asset('storage/' . $img) }}" alt="">
            @endforeach
        </div>
        @endif
    </div>

    {{-- Details panel --}}
    <div class="admin-panel">
        {{-- Badges --}}
        <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:16px">
            <span class="badge {{ $motorcycle->is_published ? 'badge-green' : 'badge-yellow' }}">
                {{ $motorcycle->is_published ? 'Published' : 'Draft' }}
            </span>
            @if($motorcycle->is_featured)
                <span class="badge badge-purple">Featured</span>
            @endif
            <span class="badge badge-muted">{{ ucfirst($motorcycle->availability_status ?? 'available') }}</span>
            <span class="badge badge-muted">{{ ucfirst($motorcycle->condition ?? 'new') }}</span>
        </div>

        @if($motorcycle->short_description)
            <p style="font-size:14px;font-weight:600;color:var(--subtext);margin-bottom:10px">{{ $motorcycle->short_description }}</p>
        @endif

        <p style="font-size:13px;line-height:1.8;color:var(--muted)">{{ $motorcycle->description }}</p>

        <dl class="spec-grid">
            <div class="spec-item">
                <dt>Price</dt>
                <dd>{{ $motorcycle->price ? (($motorcycle->currency ?: 'USD') . ' ' . number_format($motorcycle->price)) : '—' }}</dd>
            </div>
            <div class="spec-item">
                <dt>Year</dt>
                <dd>{{ $motorcycle->year ?: '—' }}</dd>
            </div>
            <div class="spec-item">
                <dt>Category</dt>
                <dd>{{ $motorcycle->category ? ucfirst($motorcycle->category) : '—' }}</dd>
            </div>
            <div class="spec-item">
                <dt>Engine (CC)</dt>
                <dd>{{ $motorcycle->engine_capacity ? number_format($motorcycle->engine_capacity) . ' cc' : '—' }}</dd>
            </div>
            <div class="spec-item">
                <dt>Availability</dt>
                <dd>{{ ucfirst($motorcycle->availability_status ?? 'available') }}</dd>
            </div>
            <div class="spec-item">
                <dt>Condition</dt>
                <dd>{{ ucfirst($motorcycle->condition ?? 'new') }}</dd>
            </div>
        </dl>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function () {
    var btn    = document.getElementById('show-delete-btn');
    var form   = document.getElementById('show-delete-form');
    if (!btn) return;

    // Build modal once
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
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.style.display !== 'none') closeModal();
    });
})();
</script>
@endsection
