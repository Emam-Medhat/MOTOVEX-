@extends('layouts.admin')
@section('title', 'Edit ' . $motorcycle->model_name)

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.motorcycles.index') }}"
           style="font-size:12px;color:#6e6888;display:inline-flex;align-items:center;gap:6px;margin-bottom:6px;transition:color .15s"
           onmouseover="this.style.color='#d4cfea'" onmouseout="this.style.color='#6e6888'">
            <i class="fa-solid fa-arrow-left" style="font-size:10px"></i> Back to Motorcycles
        </a>
        <h1>{{ $motorcycle->model_name }}</h1>
    </div>
    @can('delete', $motorcycle)
    <form method="POST" action="{{ route('admin.motorcycles.destroy', $motorcycle) }}"
          onsubmit="return confirm('Delete {{ addslashes($motorcycle->model_name) }}?')">
        @csrf @method('DELETE')
        <button class="btn-danger"><i class="fa-solid fa-trash"></i> Delete</button>
    </form>
    @endcan
</div>

<div>
    <div class="admin-panel">
        <form method="POST" action="{{ route('admin.motorcycles.update', $motorcycle) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('admin.motorcycles._form')
            <div style="display:flex;gap:12px;margin-top:24px;padding-top:20px;border-top:1px solid var(--line)">
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-check"></i> Save Changes
                </button>
                <a href="{{ route('admin.motorcycles.index') }}" class="btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
