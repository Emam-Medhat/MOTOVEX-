@extends('layouts.admin')
@section('title', 'Edit — ' . $showroom->name)

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.showrooms.index') }}"
           class="text-decoration-none text-muted small d-inline-flex align-items-center gap-2 mb-2">
            <i class="bi bi-arrow-left"></i> Back to Showrooms
        </a>
        <h1>Edit Showroom</h1>
        <p>Updating: <strong>{{ $showroom->name }}</strong></p>
    </div>
    @can('delete', $showroom)
    <form method="POST" action="{{ route('admin.showrooms.destroy', $showroom) }}"
          onsubmit="return confirm('Delete this showroom? This action cannot be undone.')">
        @csrf @method('DELETE')
        <button type="submit" class="btn-danger">
            <i class="bi bi-trash3"></i> Delete
        </button>
    </form>
    @endcan
</div>

<form method="POST" action="{{ route('admin.showrooms.update', $showroom) }}">
    @csrf @method('PUT')
    @include('admin.showrooms._form')
    <div class="d-flex gap-2 mt-3">
        <button type="submit" class="btn-primary-alt">
            <i class="bi bi-check-lg"></i> Save Changes
        </button>
        <a href="{{ route('admin.showrooms.index') }}" class="btn btn-light" style="border-radius:8px;font-weight:600;">
            Cancel
        </a>
    </div>
</form>
@endsection
