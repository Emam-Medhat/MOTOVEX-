@extends('layouts.admin')
@section('title', 'Edit — ' . $promotion->title)

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.promotions.index') }}"
           class="text-decoration-none text-muted small d-inline-flex align-items-center gap-2 mb-2">
            <i class="bi bi-arrow-left"></i> Back to Promotions
        </a>
        <h1>Edit Promotion</h1>
        <p>Updating: <strong>{{ $promotion->title }}</strong></p>
    </div>
    @can('delete', $promotion)
    <form method="POST" action="{{ route('admin.promotions.destroy', $promotion) }}"
          onsubmit="return confirm('Delete this promotion? This action cannot be undone.')">
        @csrf @method('DELETE')
        <button type="submit" class="btn-danger">
            <i class="bi bi-trash3"></i> Delete
        </button>
    </form>
    @endcan
</div>

<form method="POST" action="{{ route('admin.promotions.update', $promotion) }}">
    @csrf @method('PUT')
    @include('admin.promotions._form')
    <div class="d-flex gap-2 mt-3">
        <button type="submit" class="btn-primary-alt">
            <i class="bi bi-check-lg"></i> Save Changes
        </button>
        <a href="{{ route('admin.promotions.index') }}" class="btn btn-light" style="border-radius:8px;font-weight:600;">
            Cancel
        </a>
    </div>
</form>
@endsection
