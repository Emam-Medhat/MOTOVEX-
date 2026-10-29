@extends('layouts.admin')
@section('title', 'Edit ' . $showroom->name)

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.showrooms.index') }}"
           style="font-size:12px;color:#6e6888;display:inline-flex;align-items:center;gap:6px;margin-bottom:6px"
           onmouseover="this.style.color='#d4cfea'" onmouseout="this.style.color='#6e6888'">
            <i class="fa-solid fa-arrow-left" style="font-size:10px"></i> Back to Showrooms
        </a>
        <h1>{{ $showroom->name }}</h1>
    </div>
    @can('delete', $showroom)
    <form method="POST" action="{{ route('admin.showrooms.destroy', $showroom) }}"
          onsubmit="return confirm('Delete this showroom?')">
        @csrf @method('DELETE')
        <button class="btn-danger"><i class="fa-solid fa-trash"></i> Delete</button>
    </form>
    @endcan
</div>

<div>
    <div class="admin-panel">
        <form method="POST" action="{{ route('admin.showrooms.update', $showroom) }}">
            @csrf @method('PUT')
            @include('admin.showrooms._form')
            <div style="display:flex;gap:12px;margin-top:20px;padding-top:18px;border-top:1px solid var(--line)">
                <button type="submit" class="btn-primary"><i class="fa-solid fa-check"></i> Save Changes</button>
                <a href="{{ route('admin.showrooms.index') }}" class="btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
