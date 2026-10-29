@extends('layouts.admin')
@section('title', 'Edit ' . $promotion->title)

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.promotions.index') }}"
           style="font-size:12px;color:#6e6888;display:inline-flex;align-items:center;gap:6px;margin-bottom:6px"
           onmouseover="this.style.color='#d4cfea'" onmouseout="this.style.color='#6e6888'">
            <i class="fa-solid fa-arrow-left" style="font-size:10px"></i> Back to Promotions
        </a>
        <h1>{{ $promotion->title }}</h1>
    </div>
    @can('delete', $promotion)
    <form method="POST" action="{{ route('admin.promotions.destroy', $promotion) }}"
          onsubmit="return confirm('Delete this promotion?')">
        @csrf @method('DELETE')
        <button class="btn-danger"><i class="fa-solid fa-trash"></i> Delete</button>
    </form>
    @endcan
</div>

<div>
    <div class="admin-panel">
        <form method="POST" action="{{ route('admin.promotions.update', $promotion) }}">
            @csrf @method('PUT')
            @include('admin.promotions._form')
            <div style="display:flex;gap:12px;margin-top:20px;padding-top:18px;border-top:1px solid var(--line)">
                <button type="submit" class="btn-primary"><i class="fa-solid fa-check"></i> Save Changes</button>
                <a href="{{ route('admin.promotions.index') }}" class="btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
