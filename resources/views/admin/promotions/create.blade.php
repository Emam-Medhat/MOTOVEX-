@extends('layouts.admin')
@section('title', 'New Promotion')

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.promotions.index') }}"
           style="font-size:12px;color:#6e6888;display:inline-flex;align-items:center;gap:6px;margin-bottom:6px"
           onmouseover="this.style.color='#d4cfea'" onmouseout="this.style.color='#6e6888'">
            <i class="fa-solid fa-arrow-left" style="font-size:10px"></i> Back to Promotions
        </a>
        <h1>New Promotion</h1>
    </div>
</div>

<div>
    <div class="admin-panel">
        <form method="POST" action="{{ route('admin.promotions.store') }}">
            @csrf
            @include('admin.promotions._form')
            <div style="display:flex;gap:12px;margin-top:20px;padding-top:18px;border-top:1px solid var(--line)">
                <button type="submit" class="btn-primary"><i class="fa-solid fa-check"></i> Create Promotion</button>
                <a href="{{ route('admin.promotions.index') }}" class="btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
