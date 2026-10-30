@extends('layouts.admin')
@section('title', 'New Motorcycle')

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.motorcycles.index') }}"
           style="font-size:12px;color:#6e6888;display:inline-flex;align-items:center;gap:6px;margin-bottom:6px;transition:color .15s"
           onmouseover="this.style.color='#d4cfea'" onmouseout="this.style.color='#6e6888'">
            <i class="bi bi-arrow-left" style="font-size:10px"></i> Back to Motorcycles
        </a>
        <h1>New Motorcycle</h1>
    </div>
</div>

<div>
    <div class="admin-panel">
        <form method="POST" action="{{ route('admin.motorcycles.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.motorcycles._form')

            <div style="display:flex;gap:12px;margin-top:24px;padding-top:20px;border-top:1px solid var(--line)">
                <button type="submit" class="btn-primary">
                    <i class="bi bi-check-lg"></i> Create Motorcycle
                </button>
                <a href="{{ route('admin.motorcycles.index') }}" class="btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

