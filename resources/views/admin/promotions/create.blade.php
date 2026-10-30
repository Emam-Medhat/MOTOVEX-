@extends('layouts.admin')
@section('title', 'New Promotion')

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.promotions.index') }}"
           class="text-decoration-none text-muted small d-inline-flex align-items-center gap-2 mb-2">
            <i class="bi bi-arrow-left"></i> Back to Promotions
        </a>
        <h1>New Promotion</h1>
        <p>Create a new promotion or offer campaign.</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.promotions.store') }}">
    @csrf
    @include('admin.promotions._form')
    <div class="d-flex gap-2 mt-3">
        <button type="submit" class="btn-primary-alt">
            <i class="bi bi-check-lg"></i> Create Promotion
        </button>
        <a href="{{ route('admin.promotions.index') }}" class="btn btn-light" style="border-radius:8px;font-weight:600;">
            Cancel
        </a>
    </div>
</form>
@endsection
