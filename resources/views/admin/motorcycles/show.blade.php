@extends('layouts.admin')
@section('title', $motorcycle->model_name)

@section('styles')
<style>
    .moto-show-grid { display: grid; grid-template-columns: 340px 1fr; gap: 20px; }
    .spec-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; margin-top: 18px; }
    .spec-item dt { font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #6e6888; margin-bottom: 3px; }
    .spec-item dd { font-size: 14px; font-weight: 700; color: #f0edfb; }
    .gallery-thumbs { display: flex; flex-wrap: wrap; gap: 7px; padding: 12px; border-top: 1px solid var(--line); }
    .gallery-thumbs img { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(255,255,255,.08); }
    @media (max-width: 900px) { .moto-show-grid { grid-template-columns: 1fr; } .spec-grid { grid-template-columns: 1fr 1fr; } }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.motorcycles.index') }}"
           style="font-size:12px;color:#6e6888;display:inline-flex;align-items:center;gap:6px;margin-bottom:6px"
           onmouseover="this.style.color='#d4cfea'" onmouseout="this.style.color='#6e6888'">
            <i class="fa-solid fa-arrow-left" style="font-size:10px"></i> Back to Motorcycles
        </a>
        <h1>{{ $motorcycle->model_name }}</h1>
        <p style="font-size:12px;color:#6e6888;margin-top:3px">
            {{ $motorcycle->brand->name }} · {{ ucfirst($motorcycle->category ?? '') }}
        </p>
    </div>
    <div style="display:flex;gap:10px;align-items:center">
        <a href="{{ route('admin.motorcycles.edit', $motorcycle) }}" class="btn-primary">
            <i class="fa-solid fa-pen"></i> Edit
        </a>
        @can('delete', $motorcycle)
        <form method="POST" action="{{ route('admin.motorcycles.destroy', $motorcycle) }}"
              onsubmit="return confirm('Delete this motorcycle?')">
            @csrf @method('DELETE')
            <button class="btn-danger"><i class="fa-solid fa-trash"></i> Delete</button>
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
            <div style="width:100%;aspect-ratio:4/3;background:rgba(155,92,255,.08);display:grid;place-items:center;font-size:48px;color:#3d3655">
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
            <p style="font-size:14px;font-weight:600;color:#e0daf0;margin-bottom:10px">{{ $motorcycle->short_description }}</p>
        @endif

        <p style="font-size:13px;line-height:1.8;color:#a09aaf">{{ $motorcycle->description }}</p>

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
