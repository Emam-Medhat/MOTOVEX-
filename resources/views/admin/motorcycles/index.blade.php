@extends('layouts.admin')
@section('title', 'Motorcycles')

@section('styles')
<style>
    .filter-bar {
        display: flex; flex-wrap: wrap; align-items: center; gap: 10px;
        margin-bottom: 18px;
        padding: 14px 16px;
        border: 1px solid var(--line);
        border-radius: 14px;
        background: linear-gradient(145deg, #131020, #0e0c1a);
    }
    .filter-bar input, .filter-bar select {
        height: 38px; padding: 0 13px;
        background: rgba(255,255,255,.05);
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 9px; color: var(--text);
        font-size: 12px; font-family: inherit;
        outline: none; transition: border-color .15s;
    }
    .filter-bar input { min-width: 220px; padding-left: 34px; }
    .filter-bar select { min-width: 120px; }
    .filter-bar input:focus, .filter-bar select:focus { border-color: rgba(155,92,255,.6); }
    .filter-bar input::placeholder { color: #6e6888; }
    .filter-bar select option { background: #1a1528; }
    .search-wrap { position: relative; }
    .search-wrap i { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #6e6888; font-size: 12px; pointer-events: none; }
    .filter-clear { font-size: 11px; font-weight: 600; color: #7a748f; border: 1px solid var(--line); background: none; border-radius: 9px; padding: 0 12px; height: 38px; transition: color .15s; }
    .filter-clear:hover { color: #d4cfea; }
    .bulk-bar { display: flex; align-items: center; gap: 10px; padding: 10px 18px; border-bottom: 1px solid var(--line); min-height: 46px; }
    .moto-thumb { width: 40px; height: 40px; border-radius: 10px; object-fit: cover; flex-shrink: 0; border: 1px solid rgba(255,255,255,.08); }
    .moto-thumb-placeholder { width: 40px; height: 40px; border-radius: 10px; background: rgba(155,92,255,.1); border: 1px solid rgba(155,92,255,.15); display: grid; place-items: center; flex-shrink: 0; color: #7a748f; font-size: 14px; }
    .admin-table .row-hover-actions { opacity: 0; transition: opacity .15s; }
    .admin-table tr:hover .row-hover-actions { opacity: 1; }
    .row-check { accent-color: #9b5cff; width: 15px; height: 15px; cursor: pointer; }
    .category-badge {
        display: inline-flex; padding: 3px 9px; border-radius: 6px;
        font-size: 10px; font-weight: 600; letter-spacing: .4px; text-transform: uppercase;
        background: rgba(255,255,255,.07); color: #b8b3c8;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1>Motorcycles</h1>
        <p>{{ $motorcycles->total() }} motorcycle{{ $motorcycles->total() !== 1 ? 's' : '' }} in catalogue</p>
    </div>
    <a href="{{ route('admin.motorcycles.create') }}" class="btn-primary">
        <i class="fa-solid fa-plus"></i> New Motorcycle
    </a>
</div>

{{-- Filters --}}
<form method="GET" class="filter-bar">
    <div class="search-wrap">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by model name…">
    </div>
    @if(auth()->user()->isAdmin())
    <select name="brand">
        <option value="">All Brands</option>
        @foreach($brands as $b)
        <option value="{{ $b->id }}" {{ request('brand') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
        @endforeach
    </select>
    @endif
    <select name="status">
        <option value="">All Status</option>
        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
        <option value="draft"     {{ request('status') === 'draft'      ? 'selected' : '' }}>Draft</option>
    </select>
    <button type="submit" class="btn-ghost" style="height:38px;padding:0 14px;font-size:11px">
        <i class="fa-solid fa-filter"></i> Filter
    </button>
    @if(request()->hasAny(['brand','status','search']))
    <a href="{{ route('admin.motorcycles.index') }}" class="filter-clear">Clear</a>
    @endif
</form>

<div class="admin-table-wrap">
    @if($motorcycles->isEmpty())
        <div class="empty-state">
            <i class="fa-solid fa-motorcycle"></i>
            <p>No motorcycles found.</p>
        </div>
    @else
    <form id="bulk-form" method="POST" action="{{ route('admin.motorcycles.bulk-destroy') }}">
        @csrf @method('DELETE')

        {{-- Bulk bar --}}
        <div class="bulk-bar">
            <label style="display:flex;align-items:center;gap:8px;font-size:12px;color:#9b96ad;cursor:pointer;user-select:none">
                <input type="checkbox" id="select-all" class="row-check">
                Select all
            </label>
            <span id="selected-count" style="display:none;font-size:11px;font-weight:600;color:#c4b5fd"></span>
            <button type="submit" id="bulk-delete-btn" class="btn-danger" style="display:none;margin-left:auto">
                <i class="fa-solid fa-trash"></i> Delete selected
            </button>
        </div>

        <div style="overflow-x:auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:40px"></th>
                        <th>Model</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Year</th>
                        <th>Status</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($motorcycles as $moto)
                    <tr>
                        <td><input type="checkbox" name="ids[]" value="{{ $moto->id }}" class="row-check row-checkbox"></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:11px">
                                @if($moto->image_path)
                                    <img src="{{ asset('storage/' . $moto->image_path) }}" alt="" class="moto-thumb">
                                @elseif($moto->image_url)
                                    <img src="{{ $moto->image_url }}" alt="" class="moto-thumb">
                                @else
                                    <div class="moto-thumb-placeholder"><i class="fa-solid fa-motorcycle"></i></div>
                                @endif
                                <div>
                                    <strong style="display:block;font-size:13px;color:#f0edfb">{{ $moto->model_name }}</strong>
                                    <span style="font-size:11px;color:#6e6888">{{ ucfirst($moto->condition ?? 'new') }} · {{ ucfirst($moto->availability_status ?? 'available') }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ $moto->brand->name }}</td>
                        <td><span class="category-badge">{{ str_replace('-', ' ', $moto->category) }}</span></td>
                        <td style="font-weight:600;color:#d4cfea">
                            {{ $moto->price ? (($moto->currency ?: 'USD') . ' ' . number_format($moto->price)) : '—' }}
                        </td>
                        <td>{{ $moto->year }}</td>
                        <td>
                            <span class="badge {{ $moto->is_published ? 'badge-green' : 'badge-yellow' }}">
                                {{ $moto->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td>
                            <div class="td-actions row-hover-actions">
                                <a href="{{ route('admin.motorcycles.show', $moto) }}" class="tbl-link-muted" title="View">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.motorcycles.edit', $moto) }}" class="tbl-link" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                @can('delete', $moto)
                                <button type="button" class="tbl-link-danger delete-single"
                                        data-url="{{ route('admin.motorcycles.destroy', $moto) }}"
                                        data-name="{{ $moto->model_name }}" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>
    <div class="admin-pagination">{{ $motorcycles->links() }}</div>
    @endif
</div>

<form id="delete-single-form" method="POST" style="display:none">@csrf @method('DELETE')</form>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll   = document.getElementById('select-all');
    const checkboxes  = document.querySelectorAll('.row-checkbox');
    const bulkBtn     = document.getElementById('bulk-delete-btn');
    const countEl     = document.getElementById('selected-count');
    const bulkForm    = document.getElementById('bulk-form');
    const singleBtns  = document.querySelectorAll('.delete-single');
    const singleForm  = document.getElementById('delete-single-form');

    function update() {
        const n = document.querySelectorAll('.row-checkbox:checked').length;
        bulkBtn.style.display = n > 0 ? 'inline-flex' : 'none';
        countEl.style.display = n > 0 ? 'inline' : 'none';
        countEl.textContent   = n + ' selected';
        selectAll.indeterminate = n > 0 && n < checkboxes.length;
        selectAll.checked = n === checkboxes.length && n > 0;
    }

    selectAll.addEventListener('change', function () {
        checkboxes.forEach(cb => cb.checked = this.checked);
        update();
    });
    checkboxes.forEach(cb => cb.addEventListener('change', update));

    bulkForm.addEventListener('submit', function (e) {
        const n = document.querySelectorAll('.row-checkbox:checked').length;
        if (n === 0) { e.preventDefault(); return; }
        if (!confirm('Delete ' + n + ' motorcycle(s)? This cannot be undone.')) e.preventDefault();
    });

    singleBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            if (confirm('Delete ' + this.dataset.name + '?')) {
                singleForm.action = this.dataset.url;
                singleForm.submit();
            }
        });
    });
});
</script>
@endsection
