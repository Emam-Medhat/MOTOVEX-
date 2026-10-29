@extends('layouts.admin')
@section('title', 'Brands')

@section('content')
<div class="page-header">
    <div>
        <h1>Brands</h1>
        <p>{{ $brands->total() }} brand{{ $brands->total() !== 1 ? 's' : '' }} in total</p>
    </div>
    @can('create', App\Models\Brand::class)
    <a href="{{ route('admin.brands.create') }}" class="btn-primary">
        <i class="fa-solid fa-plus"></i> New Brand
    </a>
    @endcan
</div>

<div class="admin-table-wrap">
    @if($brands->isEmpty())
        <div class="empty-state">
            <i class="fa-regular fa-copyright"></i>
            <p>No brands found.</p>
        </div>
    @else
    <table class="admin-table">
        <thead>
            <tr>
                <th>Brand</th>
                <th>Country</th>
                <th>Models</th>
                <th>Status</th>
                <th style="text-align:right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($brands as $brand)
            <tr>
                <td>
                    <strong style="display:block;font-size:13px;color:#f0edfb">{{ $brand->name }}</strong>
                    <span style="font-size:11px;color:#6e6888">{{ $brand->slug }}</span>
                </td>
                <td>{{ $brand->country ?? '—' }}</td>
                <td>
                    <span style="font-weight:700;color:#c4b5fd">{{ $brand->motorcycles_count }}</span>
                </td>
                <td>
                    <span class="badge {{ $brand->is_published ? 'badge-green' : 'badge-yellow' }}">
                        {{ $brand->is_published ? 'Published' : 'Draft' }}
                    </span>
                </td>
                <td>
                    <div class="td-actions">
                        <a href="{{ route('admin.brands.show', $brand) }}" class="tbl-link-muted">View</a>
                        @can('update', $brand)
                        <a href="{{ route('admin.brands.edit', $brand) }}" class="tbl-link">Edit</a>
                        @endcan
                        @can('assignUsers', App\Models\Brand::class)
                        <a href="{{ route('admin.brands.assign-users', $brand) }}" class="tbl-link" style="color:#93c5fd">Assign</a>
                        @endcan
                        @can('delete', $brand)
                        <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}"
                              onsubmit="return confirm('Delete {{ addslashes($brand->name) }}? This will also delete all its motorcycles.')">
                            @csrf @method('DELETE')
                            <button class="tbl-link-danger">Delete</button>
                        </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="admin-pagination">{{ $brands->links() }}</div>
    @endif
</div>
@endsection
