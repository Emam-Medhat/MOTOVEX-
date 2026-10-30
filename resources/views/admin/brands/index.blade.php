@extends('layouts.admin')
@section('title', 'Brands')

@section('content')
<div class="page-header">
    <div>
        <h1>Brands</h1>
        <p>{{ $brands->total() }} brand{{ $brands->total() !== 1 ? 's' : '' }} in total catalog</p>
    </div>
    @can('create', App\Models\Brand::class)
    <a href="{{ route('admin.brands.create') }}" class="btn-primary-alt">
        <i class="bi bi-plus-lg"></i> New Brand
    </a>
    @endcan
</div>

<div class="panel border-0 shadow-sm">
    @if($brands->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-patch-check text-muted display-1 opacity-25"></i>
            <p class="mt-3 text-muted">No brands found.</p>
        </div>
    @else
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Brand Name</th>
                    <th>Country</th>
                    <th>Models</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($brands as $brand)
                <tr>
                    <td>
                        <strong class="d-block">{{ $brand->name }}</strong>
                        <small class="text-muted">{{ $brand->slug }}</small>
                    </td>
                    <td>{{ $brand->country ?? '—' }}</td>
                    <td>
                        <span class="fw-bold text-primary">{{ $brand->motorcycles_count }}</span>
                        <small class="text-muted ms-1">models</small>
                    </td>
                    <td>
                        <span class="badge-alt {{ $brand->is_published ? 'badge-success' : 'badge-warning' }}">
                            {{ $brand->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.brands.show', $brand) }}" class="action-btn btn-view" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('update', $brand)
                            <a href="{{ route('admin.brands.edit', $brand) }}" class="action-btn btn-edit" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            @endcan
                            @can('assignUsers', App\Models\Brand::class)
                            <a href="{{ route('admin.brands.assign-users', $brand) }}" class="action-btn btn-primary" title="Assign Users" style="background:#e0efff;color:#0870dc">
                                <i class="bi bi-person-gear"></i>
                            </a>
                            @endcan
                            @can('delete', $brand)
                            <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}"
                                  onsubmit="return confirm('Delete {{ addslashes($brand->name) }}? This will also delete all its motorcycles.')"
                                  style="display:inline">
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
    <div class="mt-4">{{ $brands->links() }}</div>
    @endif
</div>
@endsection
