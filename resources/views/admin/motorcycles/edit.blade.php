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
    <button type="button" class="btn-danger" id="edit-delete-btn"
            data-name="{{ $motorcycle->model_name }}">
        <i class="fa-solid fa-trash"></i> Delete
    </button>
    <form id="edit-delete-form" method="POST"
          action="{{ route('admin.motorcycles.destroy', $motorcycle) }}"
          style="display:none">
        @csrf @method('DELETE')
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

@section('scripts')
<script>
(function () {
    var btn    = document.getElementById('edit-delete-btn');
    var form   = document.getElementById('edit-delete-form');
    if (!btn) return;

    var modal = document.createElement('div');
    modal.innerHTML = `
        <div id="edm-backdrop" style="position:fixed;inset:0;background:rgba(0,0,0,.45);backdrop-filter:blur(3px);z-index:9998"></div>
        <div style="position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;padding:16px">
            <div style="background:#fff;border-radius:18px;padding:28px 28px 24px;width:100%;max-width:400px;box-shadow:0 25px 60px rgba(0,0,0,.18)">
                <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:18px">
                    <div style="flex-shrink:0;width:42px;height:42px;border-radius:12px;background:#FEF2F2;border:1px solid #FECACA;display:grid;place-items:center">
                        <i class="fa-solid fa-triangle-exclamation" style="color:#DC2626;font-size:16px"></i>
                    </div>
                    <div>
                        <p style="font-size:15px;font-weight:700;color:#111;margin:0 0 4px">Delete Motorcycle</p>
                        <p style="font-size:13px;color:#64748b;margin:0;line-height:1.5">
                            Are you sure you want to delete <strong style="color:#111">${btn.dataset.name}</strong>?
                            This action <span style="color:#DC2626;font-weight:600">cannot be undone</span>.
                        </p>
                    </div>
                </div>
                <div style="display:flex;gap:8px;justify-content:flex-end">
                    <button id="edm-cancel" type="button"
                            style="height:38px;padding:0 18px;font-size:13px;font-weight:600;border-radius:10px;border:1px solid #e2e8f0;background:#fff;color:#374151;cursor:pointer">
                        Cancel
                    </button>
                    <button id="edm-confirm" type="button"
                            style="height:38px;padding:0 18px;font-size:13px;font-weight:600;border-radius:10px;border:none;background:#DC2626;color:#fff;cursor:pointer;display:inline-flex;align-items:center;gap:6px">
                        <i class="fa-solid fa-trash-can"></i> Yes, Delete
                    </button>
                </div>
            </div>
        </div>`;
    modal.style.display = 'none';
    document.body.appendChild(modal);

    function openModal()  { modal.style.display = 'block'; document.body.style.overflow = 'hidden'; }
    function closeModal() { modal.style.display = 'none';  document.body.style.overflow = ''; }

    btn.addEventListener('click', openModal);
    modal.querySelector('#edm-cancel').addEventListener('click', closeModal);
    modal.querySelector('#edm-backdrop').addEventListener('click', closeModal);
    modal.querySelector('#edm-confirm').addEventListener('click', function () {
        this.disabled = true;
        this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Deleting…';
        form.submit();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.style.display !== 'none') closeModal();
    });
})();
</script>
@endsection
