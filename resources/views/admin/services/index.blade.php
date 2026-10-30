@extends('layouts.admin')
@section('title', 'Services')

@section('content')
<div class="page-header">
    <div>
        <h1>Services</h1>
        <p>{{ $services->total() }} service{{ $services->total() !== 1 ? 's' : '' }} offered to customers</p>
    </div>
    <a href="{{ route('admin.services.create') }}" class="btn-primary-alt">
        <i class="bi bi-plus-lg"></i> New Service
    </a>
</div>

<div class="panel border-0 shadow-sm">
    @if($services->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-tools text-muted display-1 opacity-25"></i>
            <p class="mt-3 text-muted">No services found.</p>
        </div>
    @else
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Service Details</th>
                    <th>Sort Order</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $service)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 40px; height: 40px; border-radius: 8px; background: #f0f7ff; color: #0865df; display: grid; place-items: center; flex-shrink: 0;">
                                <i class="fa-solid {{ $service->icon_class }}"></i>
                            </div>
                            <div>
                                <strong class="d-block text-dark">{{ $service->title }}</strong>
                                <small class="text-muted">{{ Str::limit($service->description, 60) }}</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge bg-light text-dark px-3" style="border-radius: 6px;">#{{ $service->sort_order }}</span></td>
                    <td>
                        <span class="badge-alt {{ $service->is_active ? 'badge-success' : 'badge-danger' }}">
                            {{ $service->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.services.edit', $service) }}" class="action-btn btn-edit" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.services.toggle', $service) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="action-btn {{ $service->is_active ? 'btn-view' : 'btn-primary' }}" 
                                        style="{{ !$service->is_active ? 'background:#e0efff;color:#0870dc' : '' }}"
                                        title="{{ $service->is_active ? 'Unpublish' : 'Publish' }}">
                                    <i class="bi {{ $service->is_active ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.services.destroy', $service) }}"
                                  onsubmit="return confirm('Delete this service?')" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn btn-delete" title="Delete">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $services->links() }}</div>
    @endif
</div>
@endsection
