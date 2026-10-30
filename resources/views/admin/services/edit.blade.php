@extends('layouts.admin')
@section('title', 'Edit — ' . $service->title)

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.services.index') }}"
           class="text-decoration-none text-muted small d-inline-flex align-items-center gap-2 mb-2">
            <i class="bi bi-arrow-left"></i> Back to Services
        </a>
        <h1>Edit Service</h1>
        <p>Updating: <strong>{{ $service->title }}</strong></p>
    </div>
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

<form method="POST" action="{{ route('admin.services.update', $service) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.services._form')
    <div class="d-flex gap-2 mt-3">
        <button type="submit" class="btn-primary-alt">
            <i class="bi bi-check-lg"></i> Save Changes
        </button>
        <a href="{{ route('admin.services.index') }}" class="btn btn-light" style="border-radius:8px;font-weight:600;">
            Cancel
        </a>
    </div>
</form>
@endsection
