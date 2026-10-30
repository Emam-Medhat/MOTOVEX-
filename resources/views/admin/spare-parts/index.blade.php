@extends('layouts.admin')
@section('title', 'Spare Parts')

@section('content')
<div class="page-header">
    <div>
        <h1>Spare Parts</h1>
        <p>{{ $spareParts->total() }} part{{ $spareParts->total() !== 1 ? 's' : '' }} total in inventory</p>
    </div>
    @can('create', \App\Models\SparePart::class)
    <a href="{{ route('admin.spare-parts.create') }}" class="btn-primary-alt">
        <i class="bi bi-plus-lg"></i> New Spare Part
    </a>
    @endcan
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.spare-parts.index') }}" class="filter-card border-0 shadow-sm">
    <div class="position-relative">
        <i class="bi bi-search position-absolute text-muted" style="left: 12px; top: 9px;"></i>
        <input type="text" name="search" value="{{ request('search') }}" class="filter-input ps-5" placeholder="Search by name, SKU…">
    </div>

    <select name="category" class="filter-select">
        <option value="">All Categories</option>
        @foreach(\App\Models\SparePart::$categories as $k => $v)
            <option value="{{ $k }}" {{ request('category') === $k ? 'selected' : '' }}>{{ $v }}</option>
        @endforeach
    </select>

    <select name="brand" class="filter-select">
        <option value="">All Brands</option>
        @foreach($brands as $brand)
            <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
        @endforeach
    </select>

    <select name="status" class="filter-select">
        <option value="">All Status</option>
        <option value="published"   {{ request('status') === 'published'   ? 'selected' : '' }}>Published</option>
        <option value="unpublished" {{ request('status') === 'unpublished' ? 'selected' : '' }}>Draft</option>
    </select>

    <button type="submit" class="btn btn-light btn-sm px-3" style="border-radius: 8px; height: 38px;">
        <i class="bi bi-filter me-1"></i> Filter
    </button>
    
    @if(request()->hasAny(['search','category','brand','status']))
        <a href="{{ route('admin.spare-parts.index') }}" class="btn btn-link btn-sm text-decoration-none text-muted">Clear</a>
    @endif
</form>

<div class="panel border-0 shadow-sm">
    @if($spareParts->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-gear-wide-connected text-muted display-1 opacity-25"></i>
            <p class="mt-3 text-muted">No spare parts found.</p>
        </div>
    @else
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Part Details</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Owner</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($spareParts as $part)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 45px; height: 45px; border-radius: 8px; overflow: hidden; background: #f8fafc; border: 1px solid #edf1f7; display: grid; place-items: center;">
                                @if($part->image_url)
                                    <img src="{{ $part->image_url }}" alt="" style="width: 100%; height: 100%; object-fit: contain; padding: 2px;">
                                @else
                                    <i class="bi bi-gear-fill text-muted opacity-50"></i>
                                @endif
                            </div>
                            <div>
                                <strong class="d-block">{{ Str::limit($part->name, 45) }}</strong>
                                @if($part->sku)
                                    <small class="text-muted font-monospace" style="font-size: 10px;">{{ $part->sku }}</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td><span class="badge bg-light text-secondary text-uppercase" style="font-size: 9px; letter-spacing: 0.5px;">{{ $part->categoryLabel() }}</span></td>
                    <td>{{ $part->brand->name ?? '—' }}</td>
                    <td class="fw-bold text-dark">
                        {{ $part->price ? (number_format($part->price, 0) . ' ' . $part->currency) : '—' }}
                    </td>
                    <td><small class="text-muted">{{ $part->creator->name ?? '—' }}</small></td>
                    <td>
                        <span class="badge-alt {{ $part->is_published ? 'badge-success' : 'badge-warning' }}">
                            {{ $part->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            @can('update', $part)
                            <a href="{{ route('admin.spare-parts.edit', $part) }}" class="action-btn btn-edit" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            @endcan
                            
                            @can('publish', $part)
                            <form method="POST" action="{{ route('admin.spare-parts.toggle-publish', $part) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="action-btn {{ $part->is_published ? 'btn-view' : 'btn-primary' }}" 
                                        style="{{ !$part->is_published ? 'background:#e0efff;color:#0870dc' : '' }}"
                                        title="{{ $part->is_published ? 'Unpublish' : 'Publish' }}">
                                    <i class="bi {{ $part->is_published ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                </button>
                            </form>
                            @endcan

                            @can('delete', $part)
                            <form method="POST" action="{{ route('admin.spare-parts.destroy', $part) }}"
                                  onsubmit="return confirm('Delete this spare part?')" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn btn-delete" title="Delete">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $spareParts->links() }}</div>
    @endif
</div>
@endsection
