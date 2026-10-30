@extends('layouts.admin')
@section('title', 'Motorcycles')

@section('content')
<div class="page-header">
    <div>
        <h1>Motorcycles</h1>
        <p>{{ $motorcycles->total() }} motorcycle{{ $motorcycles->total() !== 1 ? 's' : '' }} in catalogue</p>
    </div>
    <a href="{{ route('admin.motorcycles.create') }}" class="btn-primary-alt">
        <i class="bi bi-plus-lg"></i> New Motorcycle
    </a>
</div>

{{-- Filters --}}
<form method="GET" class="filter-card shadow-sm border-0">
    <div class="position-relative flex-grow-1">
        <i class="bi bi-search position-absolute text-muted" style="left: 12px; top: 9px;"></i>
        <input type="text" name="search" value="{{ request('search') }}" class="filter-input ps-5" placeholder="Search by model name…">
    </div>
    
    @if(auth()->user()->isAdmin())
    <select name="brand" class="filter-select">
        <option value="">All Brands</option>
        @foreach($brands as $b)
        <option value="{{ $b->id }}" {{ request('brand') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
        @endforeach
    </select>
    @endif

    <select name="status" class="filter-select">
        <option value="">All Status</option>
        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
        <option value="draft"     {{ request('status') === 'draft'      ? 'selected' : '' }}>Draft</option>
    </select>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-light btn-sm px-3" style="border-radius: 8px; height: 38px;">
            <i class="bi bi-filter me-1"></i> Filter
        </button>
        
        @if(request()->hasAny(['brand','status','search']))
        <a href="{{ route('admin.motorcycles.index') }}" class="btn btn-link btn-sm text-decoration-none text-muted d-flex align-items-center">Clear</a>
        @endif
    </div>
</form>

<div class="panel border-0 shadow-sm">
    @if($motorcycles->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-bicycle text-muted display-1 opacity-25"></i>
            <p class="mt-3 text-muted">No motorcycles found.</p>
        </div>
    @else
    <form id="bulk-form" method="POST" action="{{ route('admin.motorcycles.bulk-destroy') }}">
        @csrf @method('DELETE')

        <div class="bulk-bar d-flex align-items-center mb-3 gap-3">
            <div class="form-check">
                <input type="checkbox" id="select-all" class="form-check-input">
                <label class="form-check-label text-muted small" for="select-all">Select all</label>
            </div>
            <span id="selected-count" class="badge bg-primary rounded-pill" style="display:none"></span>
            <button type="submit" id="bulk-delete-btn" class="btn btn-danger btn-sm px-3" style="display:none; border-radius: 8px;">
                <i class="bi bi-trash3 me-1"></i> Delete selected
            </button>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th style="width:40px"></th>
                        <th>Model</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Year</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($motorcycles as $moto)
                    <tr>
                        <td><input type="checkbox" name="ids[]" value="{{ $moto->id }}" class="form-check-input row-checkbox"></td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div style="width: 50px; height: 40px; border-radius: 8px; overflow: hidden; background: #f8fafc; border: 1px solid #edf2f7; display: grid; place-items: center;">
                                    @if($moto->image_path)
                                        <img src="{{ asset('storage/' . $moto->image_path) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                    @elseif($moto->image_url)
                                        <img src="{{ $moto->image_url }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <i class="bi bi-bicycle text-muted"></i>
                                    @endif
                                </div>
                                <div>
                                    <strong class="d-block text-dark">{{ $moto->model_name }}</strong>
                                    <small class="text-muted text-uppercase" style="font-size: 10px;">{{ $moto->condition }} · {{ $moto->availability_status }}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="fw-medium">{{ $moto->brand->name }}</span></td>
                        <td><span class="badge bg-light text-secondary text-uppercase px-2" style="font-size: 10px; letter-spacing: 0.5px;">{{ str_replace('-', ' ', $moto->category) }}</span></td>
                        <td>
                            <div class="fw-bold text-primary">
                                {{ $moto->price ? (($moto->currency ?: 'USD') . ' ' . number_format($moto->price)) : '—' }}
                            </div>
                        </td>
                        <td><span class="text-muted">{{ $moto->year }}</span></td>
                        <td>
                            <span class="badge-alt {{ $moto->is_published ? 'badge-success' : 'badge-warning' }}">
                                {{ $moto->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.motorcycles.show', $moto) }}" class="action-btn btn-view" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.motorcycles.edit', $moto) }}" class="action-btn btn-edit" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                @can('delete', $moto)
                                <button type="button" class="action-btn btn-delete delete-single"
                                        data-url="{{ route('admin.motorcycles.destroy', $moto) }}"
                                        data-name="{{ $moto->model_name }}" title="Delete">
                                    <i class="bi bi-trash3"></i>
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
    <div class="mt-4">{{ $motorcycles->links() }}</div>
    @endif
</div>

<form id="delete-single-form" method="POST" style="display:none">@csrf @method('DELETE')</form>

{{-- Professional delete confirmation modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body p-4">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="flex-shrink-0 bg-danger-subtle border border-danger-subtle rounded-3 d-grid place-items-center" style="width: 42px; height: 42px;">
                        <i class="bi bi-exclamation-triangle text-danger fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Delete Motorcycle</h6>
                        <p class="text-muted small mb-0">
                            Are you sure you want to delete <strong id="modal-moto-name" class="text-dark"></strong>? This action cannot be undone.
                        </p>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-end mt-4">
                    <button type="button" class="btn btn-light btn-sm px-3 fw-semibold" data-bs-dismiss="modal" style="border-radius: 10px;">Cancel</button>
                    <button type="button" id="modal-confirm" class="btn btn-danger btn-sm px-3 fw-semibold d-flex align-items-center gap-2" style="border-radius: 10px;">
                        <i class="bi bi-trash3"></i> Yes, Delete
                    </button>
                </div>
            </div>
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
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const modalMotoName = document.getElementById('modal-moto-name');
    const modalConfirm = document.getElementById('modal-confirm');

    // Bulk actions
    function updateBulkState() {
        const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
        bulkBtn.style.display = checkedCount > 0 ? 'inline-flex' : 'none';
        countEl.style.display = checkedCount > 0 ? 'inline' : 'none';
        countEl.textContent = checkedCount + ' selected';
        selectAll.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
        selectAll.checked = checkedCount === checkboxes.length && checkboxes.length > 0;
    }

    if(selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulkState();
        });
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updateBulkState));

    // Single delete
    singleBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const name = this.dataset.name;
            const url = this.dataset.url;
            modalMotoName.textContent = name;
            singleForm.action = url;
            deleteModal.show();
        });
    });

    modalConfirm.addEventListener('click', function() {
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Deleting...';
        singleForm.submit();
    });
});
</script>
@endsection
