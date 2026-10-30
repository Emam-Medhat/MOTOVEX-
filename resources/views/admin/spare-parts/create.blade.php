@extends('layouts.admin')
@section('title', 'New Spare Part')

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.spare-parts.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center gap-2 mb-2">
            <i class="bi bi-arrow-left"></i> Back to Inventory
        </a>
        <h1>New Spare Part</h1>
        <p>Add a new item to the spare parts inventory.</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.spare-parts.store') }}" enctype="multipart/form-data">
    @csrf
    @php $selected = []; @endphp
    @include('admin.spare-parts._form')

    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn-primary-alt">
            <i class="bi bi-check-lg"></i> Create Spare Part
        </button>
        <a href="{{ route('admin.spare-parts.index') }}" class="btn btn-light" style="border-radius: 8px; font-weight: 600;">
            Cancel
        </a>
    </div>
</form>
@endsection
