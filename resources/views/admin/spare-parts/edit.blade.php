@extends('layouts.admin')
@section('title', 'Edit ' . $sparePart->name)

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.spare-parts.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-2 mb-2">
            <i class="bi bi-arrow-left"></i> Back to Inventory
        </a>
        <h1>Edit Spare Part</h1>
        <p>Updating: <strong>{{ $sparePart->name }}</strong> · Owner: {{ $sparePart->creator->name ?? '—' }}</p>
    </div>
    <div class="d-flex gap-2">
        @can('publish', $sparePart)
        <form method="POST" action="{{ route('admin.spare-parts.toggle-publish', $sparePart) }}">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-light btn-sm px-3" style="border-radius: 8px; height: 38px; font-weight: 600;">
                <i class="bi {{ $sparePart->is_published ? 'bi-eye-slash' : 'bi-eye' }} me-1"></i>
                {{ $sparePart->is_published ? 'Unpublish' : 'Publish' }}
            </button>
        </form>
        @endcan
        @can('delete', $sparePart)
        <form method="POST" action="{{ route('admin.spare-parts.destroy', $sparePart) }}"
              onsubmit="return confirm('Delete this spare part permanently?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm px-3" style="border-radius: 8px; height: 38px; font-weight: 600;">
                <i class="bi bi-trash3 me-1"></i> Delete
            </button>
        </form>
        @endcan
    </div>
</div>

<form method="POST" action="{{ route('admin.spare-parts.update', $sparePart) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    @php $part = $sparePart; @endphp
    @include('admin.spare-parts._form')

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn-primary-alt">
            <i class="bi bi-check-lg"></i> Save Changes
        </button>
        <a href="{{ route('admin.spare-parts.index') }}" class="btn btn-light" style="border-radius: 8px; font-weight: 600;">
            Cancel
        </a>
    </div>
</form>
@endsection
