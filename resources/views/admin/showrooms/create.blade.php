@extends('layouts.admin')
@section('title', 'New Showroom')

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.showrooms.index') }}"
           class="text-decoration-none text-muted small d-inline-flex align-items-center gap-2 mb-2">
            <i class="bi bi-arrow-left"></i> Back to Showrooms
        </a>
        <h1>New Showroom</h1>
        <p>Register a new showroom location in the network.</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.showrooms.store') }}">
    @csrf
    @include('admin.showrooms._form')
    <div class="d-flex gap-2 mt-3">
        <button type="submit" class="btn-primary-alt">
            <i class="bi bi-check-lg"></i> Create Showroom
        </button>
        <a href="{{ route('admin.showrooms.index') }}" class="btn btn-light" style="border-radius:8px;font-weight:600;">
            Cancel
        </a>
    </div>
</form>
@endsection
