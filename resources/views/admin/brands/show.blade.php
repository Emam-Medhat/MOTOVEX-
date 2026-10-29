@extends('layouts.admin')
@section('title', $brand->name)

@section('styles')
<style>
    .show-grid { display: grid; grid-template-columns: 1fr 280px; gap: 20px; }
    @media (max-width: 900px) { .show-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.brands.index') }}"
           style="font-size:12px;color:#6e6888;display:inline-flex;align-items:center;gap:6px;margin-bottom:6px"
           onmouseover="this.style.color='#d4cfea'" onmouseout="this.style.color='#6e6888'">
            <i class="fa-solid fa-arrow-left" style="font-size:10px"></i> Back to Brands
        </a>
        <h1>{{ $brand->name }}</h1>
        @if($brand->country)<p style="font-size:12px;color:#6e6888;margin-top:3px">{{ $brand->country }}</p>@endif
    </div>
    <div style="display:flex;gap:10px;align-items:center">
        @can('assignUsers', App\Models\Brand::class)
        <a href="{{ route('admin.brands.assign-users', $brand) }}" class="btn-ghost">
            <i class="fa-solid fa-users"></i> Assign Users
        </a>
        @endcan
        @can('update', $brand)
        <a href="{{ route('admin.brands.edit', $brand) }}" class="btn-primary">
            <i class="fa-solid fa-pen"></i> Edit Brand
        </a>
        @endcan
    </div>
</div>

<div class="show-grid">

    {{-- Motorcycles table --}}
    <div class="admin-table-wrap">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 18px;border-bottom:1px solid var(--line)">
            <strong style="font-size:13px">Motorcycles ({{ $motorcycles->total() }})</strong>
            <a href="{{ route('admin.motorcycles.create') }}" class="tbl-link">
                <i class="fa-solid fa-plus" style="font-size:10px"></i> Add motorcycle
            </a>
        </div>
        @if($motorcycles->isEmpty())
            <div class="empty-state">
                <i class="fa-solid fa-motorcycle"></i>
                <p>No motorcycles yet.</p>
            </div>
        @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Model</th>
                    <th>Year</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($motorcycles as $m)
                <tr>
                    <td style="font-weight:600;color:#f0edfb">{{ $m->model_name }}</td>
                    <td>{{ $m->year }}</td>
                    <td>
                        <span class="badge {{ $m->is_published ? 'badge-green' : 'badge-yellow' }}">
                            {{ $m->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td>
                        <div class="td-actions">
                            <a href="{{ route('admin.motorcycles.edit', $m) }}" class="tbl-link">Edit</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="admin-pagination">{{ $motorcycles->links() }}</div>
        @endif
    </div>

    {{-- Brand info --}}
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="admin-panel">
            <p style="font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#6e6888;margin-bottom:14px">Brand Info</p>
            <div style="display:flex;flex-direction:column;gap:12px;font-size:13px">
                <div style="display:flex;justify-content:space-between;align-items:center">
                    <span style="color:#6e6888">Status</span>
                    <span class="badge {{ $brand->is_published ? 'badge-green' : 'badge-yellow' }}">
                        {{ $brand->is_published ? 'Published' : 'Draft' }}
                    </span>
                </div>
                <div style="display:flex;justify-content:space-between">
                    <span style="color:#6e6888">Country</span>
                    <span>{{ $brand->country ?? '—' }}</span>
                </div>
                @if($brand->website_url)
                <div>
                    <span style="color:#6e6888;font-size:11px">Website</span>
                    <a href="{{ $brand->website_url }}" target="_blank"
                       class="tbl-link" style="display:block;margin-top:3px;font-size:11px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                        {{ $brand->website_url }}
                    </a>
                </div>
                @endif
                <div style="display:flex;justify-content:space-between">
                    <span style="color:#6e6888">Created</span>
                    <span>{{ $brand->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        @if($brand->description)
        <div class="admin-panel">
            <p style="font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#6e6888;margin-bottom:10px">About</p>
            <p style="font-size:13px;line-height:1.7;color:#a09aaf">{{ $brand->description }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
