@extends('layouts.admin')
@section('title', 'New Service')

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.services.index') }}"
           class="text-decoration-none text-muted small d-inline-flex align-items-center gap-2 mb-2">
            <i class="bi bi-arrow-left"></i> Back to Services
        </a>
        <h1>New Service</h1>
        <p>Add a new service offering visible on the public site.</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data">
    @csrf
    @include('admin.services._form')
    <div class="d-flex gap-2 mt-3">
        <button type="submit" class="btn-primary-alt">
            <i class="bi bi-check-lg"></i> Create Service
        </button>
        <a href="{{ route('admin.services.index') }}" class="btn btn-light" style="border-radius:8px;font-weight:600;">
            Cancel
        </a>
    </div>
</form>
@endsection
