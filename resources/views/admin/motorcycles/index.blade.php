@extends('layouts.admin')
@section('title', 'Motorcycles')

@section('styles')
<style>
    .filter-bar {
        display: flex; flex-wrap: wrap; align-items: center; gap: 10px;
        margin-bottom: 18px;
        padding: 14px 16px;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: var(--surface);
    }
    .filter-bar input, .filter-bar select {
        height: 38px; padding: 0 13px;
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 9px; color: var(--text);
        font-size: 12px; font-family: inherit;
        outline: none; transition: border-color .15s;
    }
    .filter-bar input { min-width: 220px; padding-left: 34px; }
    .filter-bar select { min-width: 120px; }
    .filter-bar input:focus, .filter-bar select:focus { border-color: var(--purple-light); }
    .filter-bar input::placeholder { color: #94a3b8; }
    .filter-bar select option { background: var(--surface); }
    .search-wrap { position: relative; }
    .search-wrap i { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 12px; pointer-events: none; }
    .filter-clear { font-size: 11px; font-weight: 600; color: var(--muted); border: 1px solid var(--line); background: none; border-radius: 9px; padding: 0 12px; height: 38px; transition: color .15s; }
    .filter-clear:hover { color: var(--purple); }
    .bulk-bar { display: flex; align-items: center; gap: 10px; padding: 10px 18px; border-bottom: 1px solid var(--line); min-height: 46px; }
    .moto-thumb { width: 40px; height: 40px; border-radius: 10px; object-fit: cover; flex-shrink: 0; border: 1px solid var(--line); }
    .moto-thumb-placeholder { width: 40px; height: 40px; border-radius: 10px; background: var(--purple-bg); border: 1px solid var(--purple-border); display: grid; place-items: center; flex-shrink: 0; color: var(--purple); font-size: 14px; }
    .admin-table .row-hover-actions { opacity: 1; transition: opacity .15s; }
    .row-check { accent-color: var(--purple); width: 15px; height: 15px; cursor: pointer; }
    .category-badge {
        display: inline-flex; padding: 3px 9px; border-radius: 6px;
        font-size: 10px; font-weight: 600; letter-spacing: .4px; text-transform: uppercase;
        background: #f1f5f9; color: var(--subtext);
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
            <label style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--muted);cursor:pointer;user-select:none">
                <input type="checkbox" id="select-all" class="row-check">
                Select all
            </label>
            <span id="selected-count" style="display:none;font-size:11px;font-weight:600;color:var(--purple)"></span>
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
                                    <strong style="display:block;font-size:13px;color:var(--text)">{{ $moto->model_name }}</strong>
                                    <span style="font-size:11px;color:var(--muted)">{{ ucfirst($moto->condition ?? 'new') }} · {{ ucfirst($moto->availability_status ?? 'available') }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ $moto->brand->name }}</td>
                        <td><span class="category-badge">{{ str_replace('-', ' ', $moto->category) }}</span></td>
                        <td style="font-weight:600;color:var(--subtext)">
                            {{ $moto->price ? (($moto->currency ?: 'USD') . ' ' . number_format($moto->price)) : '—' }}
                        </td>
                        <td>{{ $moto->year }}</td>
                        <td>
                            <span class="badge {{ $moto->is_published ? 'badge-green' : 'badge-yellow' }}">
                                {{ $moto->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td style="text-align:right">
                            <div class="td-actions row-hover-actions" style="justify-content:flex-end">
                                <a href="{{ route('admin.motorcycles.show', $moto) }}" class="admin-btn-sm btn-view-alt" title="View">
                                    <i class="fa-solid fa-eye"></i> View
                                </a>
                                <a href="{{ route('admin.motorcycles.edit', $moto) }}" class="admin-btn-sm btn-edit-alt" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                @can('delete', $moto)
                                <button type="button" class="admin-btn-sm btn-delete-alt delete-single"
                                        data-url="{{ route('admin.motorcycles.destroy', $moto) }}"
                                        data-name="{{ $moto->model_name }}" title="Delete">
                                    <i class="fa-solid fa-trash-can"></i> Delete
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

{{-- Professional delete confirmation modal --}}
<div id="delete-modal" style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center">
    {{-- Backdrop --}}
    <div id="delete-modal-backdrop" style="position:absolute;inset:0;background:rgba(0,0,0,.45);backdrop-filter:blur(3px)"></div>
    {{-- Dialog --}}
    <div style="position:relative;background:#fff;border-radius:18px;padding:28px 28px 24px;width:100%;max-width:400px;margin:0 16px;box-shadow:0 25px 60px rgba(0,0,0,.18)">
        <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:18px">
            <div style="flex-shrink:0;width:42px;height:42px;border-radius:12px;background:#FEF2F2;border:1px solid #FECACA;display:grid;place-items:center">
                <i class="fa-solid fa-triangle-exclamation" style="color:#DC2626;font-size:16px"></i>
            </div>
            <div>
                <p style="font-size:15px;font-weight:700;color:#111;margin:0 0 4px">Delete Motorcycle</p>
                <p style="font-size:13px;color:#64748b;margin:0;line-height:1.5">
                    Are you sure you want to delete <strong id="modal-moto-name" style="color:#111"></strong>?
                    This action <span style="color:#DC2626;font-weight:600">cannot be undone</span>.
                </p>
            </div>
        </div>
        <div style="display:flex;gap:8px;justify-content:flex-end">
            <button id="modal-cancel" type="button"
                    style="height:38px;padding:0 18px;font-size:13px;font-weight:600;border-radius:10px;border:1px solid #e2e8f0;background:#fff;color:#374151;cursor:pointer;transition:background .15s"
                    onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
                Cancel
            </button>
            <button id="modal-confirm" type="button"
                    style="height:38px;padding:0 18px;font-size:13px;font-weight:600;border-radius:10px;border:none;background:#DC2626;color:#fff;cursor:pointer;display:inline-flex;align-items:center;gap:6px;transition:background .15s"
                    onmouseover="this.style.background='#B91C1C'" onmouseout="this.style.background='#DC2626'">
                <i class="fa-solid fa-trash-can"></i> Yes, Delete
            </button>
        </div>
    </div>
</div>
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

    // ── Bulk checkbox state ──────────────────────────────────────
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

    // ── Single-delete professional modal ────────────────────────
    const modal      = document.getElementById('delete-modal');
    const backdrop   = document.getElementById('delete-modal-backdrop');
    const nameEl     = document.getElementById('modal-moto-name');
    const cancelBtn  = document.getElementById('modal-cancel');
    const confirmBtn = document.getElementById('modal-confirm');

    function openModal(name, url) {
        nameEl.textContent   = name;
        singleForm.action    = url;
        modal.style.display  = 'flex';
        document.body.style.overflow = 'hidden';
        cancelBtn.focus();
    }

    function closeModal() {
        modal.style.display  = 'none';
        document.body.style.overflow = '';
    }

    singleBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            openModal(this.dataset.name, this.dataset.url);
        });
    });

    cancelBtn.addEventListener('click', closeModal);
    backdrop.addEventListener('click', closeModal);
    confirmBtn.addEventListener('click', function () {
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Deleting…';
        singleForm.submit();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.style.display === 'flex') closeModal();
    });
});
</script>
@endsection
