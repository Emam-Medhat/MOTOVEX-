@extends('layouts.admin')
@section('title', 'Assign Users — ' . $brand->name)

@section('styles')
<style>
    /* Light page surface for this screen only (falls back to the default admin bg) */
    .admin-main:has(.assign-page) { background: #F4F6FB; }

    .assign-page {
        --p-ink:    #172033;
        --p-sub:    #64748B;
        --p-purple: #7C3AED;
        --p-purple-dark: #6D28D9;
        --p-soft:   #F5F3FF;
        --p-soft-border: #C4B5FD;
        --p-border: #E2E8F0;
        display: block;
    }

    /* ── Breadcrumb ── */
    .assign-crumbs {
        display: flex; align-items: center; flex-wrap: wrap; gap: 7px;
        font-size: 12.5px; color: var(--p-sub); margin-bottom: 14px;
    }
    .assign-crumbs a { color: var(--p-sub); transition: color .15s; }
    .assign-crumbs a:hover { color: var(--p-purple); }
    .assign-crumbs i { font-size: 8px; color: #CBD5E1; }
    .assign-crumbs .is-current { color: var(--p-ink); font-weight: 600; }

    /* ── Page header ── */
    .assign-header {
        display: flex; align-items: flex-start; justify-content: space-between;
        gap: 24px; flex-wrap: wrap; margin-bottom: 22px;
    }
    .assign-header-main { min-width: 0; }
    .assign-brand-badge {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 5px 12px; border-radius: 20px;
        background: var(--p-soft); border: 1px solid #DDD6FE; color: var(--p-purple);
        font-size: 11px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase;
        margin-bottom: 10px;
    }
    .assign-title { font-size: 23px; font-weight: 800; letter-spacing: -.4px; color: var(--p-ink); margin: 0; }
    .assign-subtitle { font-size: 13.5px; color: var(--p-sub); margin: 6px 0 0; max-width: 620px; line-height: 1.55; }
    .assign-subtitle strong { color: #334155; font-weight: 600; }

    .assign-stat {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        min-width: 108px; padding: 12px 20px; flex-shrink: 0;
        background: #fff; border: 1px solid var(--p-border); border-radius: 14px;
    }
    .assign-stat-num { font-size: 22px; font-weight: 800; color: var(--p-purple); line-height: 1; }
    .assign-stat-label {
        font-size: 10.5px; font-weight: 600; letter-spacing: .5px; text-transform: uppercase;
        color: var(--p-sub); margin-top: 5px;
    }

    /* ── Validation ── */
    .assign-error {
        display: flex; align-items: flex-start; gap: 9px;
        padding: 11px 14px; border-radius: 10px; margin-bottom: 16px;
        background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; font-size: 12.5px;
    }
    .assign-error i { margin-top: 2px; flex-shrink: 0; }

    /* ── Content card ── */
    .assign-card {
        background: #fff; border: 1px solid var(--p-border); border-radius: 16px;
        overflow: hidden; box-shadow: 0 1px 2px rgba(16, 24, 40, .04);
    }
    .assign-card-head {
        display: flex; align-items: center; justify-content: space-between;
        gap: 16px; flex-wrap: wrap;
        padding: 20px 24px; border-bottom: 1px solid var(--p-border);
    }
    .assign-card-title { font-size: 15px; font-weight: 700; color: var(--p-ink); margin: 0; }
    .assign-card-sub { font-size: 12.5px; color: var(--p-sub); margin: 3px 0 0; }
    .assign-card-sub strong { color: #334155; font-weight: 600; }

    .assign-tools { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }

    .assign-search { position: relative; }
    .assign-search i {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: #94A3B8; font-size: 12.5px; pointer-events: none;
    }
    .assign-search input {
        width: 268px; max-width: 100%; height: 40px;
        padding: 0 14px 0 38px;
        border: 1px solid var(--p-border); border-radius: 10px;
        background: #fff; color: var(--p-ink);
        font-size: 13px; font-family: inherit; outline: none;
        transition: border-color .15s, box-shadow .15s;
    }
    .assign-search input::placeholder { color: #94A3B8; }
    .assign-search input:focus { border-color: var(--p-purple); box-shadow: 0 0 0 3px rgba(124, 58, 237, .12); }

    .assign-selectall {
        display: inline-flex; align-items: center; gap: 9px; height: 40px; padding: 0 14px;
        border: 1px solid var(--p-border); border-radius: 10px; background: #fff;
        font-size: 12.5px; font-weight: 600; color: #334155;
        cursor: pointer; user-select: none;
        transition: border-color .15s, background .15s;
    }
    .assign-selectall:hover { border-color: var(--p-soft-border); background: var(--p-soft); }
    .assign-selectall input { width: 16px; height: 16px; margin: 0; accent-color: var(--p-purple); cursor: pointer; }

    /* ── User list ── */
    .assign-list {
        display: flex; flex-direction: column; gap: 10px;
        padding: 16px 24px 16px;
        max-height: 620px; overflow-y: auto;
    }
    .assign-list::-webkit-scrollbar { width: 8px; }
    .assign-list::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 8px; }

    .user-row {
        display: flex; align-items: center; gap: 14px;
        min-height: 64px; padding: 12px 16px;
        background: #fff; border: 1px solid var(--p-border); border-radius: 12px;
        cursor: pointer;
        transition: border-color .15s, background .15s;
    }
    .user-row:hover { background: #FBFAFF; border-color: #DDD6FE; }
    .user-row.selected { background: var(--p-soft); border-color: var(--p-soft-border); }
    .user-row.is-hidden { display: none; }

    .row-check {
        width: 18px; height: 18px; margin: 0; flex-shrink: 0;
        accent-color: var(--p-purple); cursor: pointer;
    }
    .row-avatar {
        width: 40px; height: 40px; border-radius: 11px; flex-shrink: 0;
        display: grid; place-items: center;
        background: var(--p-soft); border: 1px solid #DDD6FE; color: var(--p-purple);
        font-size: 13px; font-weight: 700; letter-spacing: .3px;
    }
    .row-info { flex: 1; min-width: 0; }
    .row-name {
        font-size: 14px; font-weight: 600; color: #172033;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    .row-email {
        font-size: 12.5px; color: #64748B; margin-top: 2px;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    .row-flag {
        display: none; align-items: center; gap: 5px; flex-shrink: 0;
        padding: 4px 10px; border-radius: 20px;
        background: #EDE9FE; border: 1px solid #DDD6FE; color: var(--p-purple);
        font-size: 11.5px; font-weight: 700;
    }
    .user-row.selected .row-flag { display: inline-flex; }

    .assign-noresults {
        display: none; padding: 28px 16px; text-align: center;
        font-size: 13px; color: var(--p-sub);
    }
    .assign-noresults.show { display: block; }

    /* ── Empty state ── */
    .assign-empty { padding: 64px 24px; text-align: center; }
    .assign-empty-icon {
        width: 56px; height: 56px; border-radius: 14px; margin: 0 auto 18px;
        display: grid; place-items: center;
        background: var(--p-soft); border: 1px solid #DDD6FE; color: var(--p-purple);
    }
    .assign-empty-icon i { font-size: 22px; }
    .assign-empty h4 { margin: 0; font-size: 16px; font-weight: 700; color: var(--p-ink); }
    .assign-empty p { margin: 8px 0 20px; font-size: 13px; color: var(--p-sub); }

    /* ── Footer actions ── */
    .assign-foot {
        display: flex; align-items: center; justify-content: space-between;
        gap: 16px; flex-wrap: wrap;
        padding: 18px 24px; border-top: 1px solid var(--p-border); background: #FBFCFF;
    }
    .assign-foot-count { font-size: 13px; color: var(--p-sub); }
    .assign-foot-count strong { color: var(--p-ink); font-weight: 700; }
    .assign-foot-actions { display: flex; align-items: center; gap: 10px; }

    .btn-assign-cancel {
        display: inline-flex; align-items: center; justify-content: center;
        height: 40px; padding: 0 18px; border-radius: 10px;
        border: 1px solid var(--p-border); background: #fff; color: #475569;
        font-size: 13px; font-weight: 600;
        transition: border-color .15s, background .15s, color .15s;
    }
    .btn-assign-cancel:hover { border-color: #CBD5E1; background: #F8FAFC; color: var(--p-ink); }

    .btn-assign-save {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        height: 40px; padding: 0 20px; border: 0; border-radius: 10px;
        background: var(--p-purple); color: #fff;
        font-size: 13px; font-weight: 700;
        box-shadow: 0 1px 2px rgba(124, 58, 237, .25);
        transition: background .15s, box-shadow .15s;
    }
    .btn-assign-save:hover { background: var(--p-purple-dark); box-shadow: 0 4px 12px rgba(124, 58, 237, .3); }

    @media (max-width: 720px) {
        .assign-card-head { padding: 16px; }
        .assign-tools { width: 100%; }
        .assign-search { flex: 1 1 200px; }
        .assign-search input { width: 100%; }
        .assign-list { padding: 12px 16px; }
        .assign-foot { padding: 16px; }
        .assign-foot-actions { width: 100%; }
        .btn-assign-cancel, .btn-assign-save { flex: 1; }
    }
</style>
@endsection

@section('content')
<div class="assign-page">

    {{-- Breadcrumb --}}
    <nav class="assign-crumbs" aria-label="Breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Admin</a>
        <i class="fa-solid fa-chevron-right"></i>
        <a href="{{ route('admin.brands.index') }}">Catalogue</a>
        <i class="fa-solid fa-chevron-right"></i>
        <a href="{{ route('admin.brands.index') }}">Brands</a>
        <i class="fa-solid fa-chevron-right"></i>
        <a href="{{ route('admin.brands.show', $brand) }}">{{ $brand->name }}</a>
        <i class="fa-solid fa-chevron-right"></i>
        <span class="is-current">Assign Users</span>
    </nav>

    {{-- Page header --}}
    <header class="assign-header">
        <div class="assign-header-main">
            <span class="assign-brand-badge">
                <i class="fa-regular fa-copyright"></i> {{ $brand->name }}
            </span>
            <h1 class="assign-title">Assign Users</h1>
            <p class="assign-subtitle">
                Choose which Data Entry team members are authorized to manage inventory for
                <strong>{{ $brand->name }}</strong>.
            </p>
        </div>

        <div class="assign-stat" title="Currently selected team members">
            <span class="assign-stat-num" id="headerSelectedCount">0</span>
            <span class="assign-stat-label">Selected</span>
        </div>
    </header>

    @if($errors->any())
        <div class="assign-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.brands.sync-users', $brand) }}" id="assignForm">
        @csrf

        <section class="assign-card">

            @if($users->isEmpty())
                <div class="assign-empty">
                    <div class="assign-empty-icon"><i class="fa-solid fa-users"></i></div>
                    <h4>No Data Entry users</h4>
                    <p>Create users with the “Data Entry” role before assigning them to this brand.</p>
                    <a href="{{ route('admin.users.create') }}" class="btn-assign-save">
                        <i class="fa-solid fa-user-plus"></i> Create New User
                    </a>
                </div>
            @else
                {{-- Card header: title + count, search, select all --}}
                <div class="assign-card-head">
                    <div>
                        <h2 class="assign-card-title">Available Team Members</h2>
                        <p class="assign-card-sub">
                            Showing <strong id="visibleCount">{{ $users->count() }}</strong> of
                            {{ $users->count() }} users
                        </p>
                    </div>

                    <div class="assign-tools">
                        <div class="assign-search">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" id="userSearch" placeholder="Search by name or email…"
                                   autocomplete="off" aria-label="Search team members">
                        </div>

                        <label class="assign-selectall">
                            <input type="checkbox" id="selectAll">
                            <span>Select all</span>
                        </label>
                    </div>
                </div>

                {{-- User list --}}
                <div class="assign-list" id="userList">
                    @foreach($users as $user)
                        @php
                            $isSelected = $assignedUserIds->contains($user->id);
                            $parts = preg_split('/\s+/', trim($user->name));
                            $initials = strtoupper(
                                substr($parts[0] ?? '', 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : '')
                            );
                        @endphp
                        <div class="user-row {{ $isSelected ? 'selected' : '' }}"
                             data-name="{{ strtolower($user->name) }}"
                             data-email="{{ strtolower($user->email) }}">
                            <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                   class="row-check" {{ $isSelected ? 'checked' : '' }}>

                            <span class="row-avatar">{{ $initials }}</span>

                            <div class="row-info">
                                <div class="row-name">{{ $user->name }}</div>
                                <div class="row-email">{{ $user->email }}</div>
                            </div>

                            <span class="row-flag">
                                <i class="fa-solid fa-circle-check"></i> Assigned
                            </span>
                        </div>
                    @endforeach
                </div>

                <div class="assign-noresults" id="noResults">No team members match your search.</div>
            @endif

            {{-- Footer actions --}}
            <div class="assign-foot">
                <div class="assign-foot-count">
                    <strong id="selectedCount">0</strong> users selected
                </div>
                <div class="assign-foot-actions">
                    <a href="{{ route('admin.brands.show', $brand) }}" class="btn-assign-cancel">Cancel</a>
                    <button type="submit" class="btn-assign-save">
                        <i class="fa-solid fa-check"></i> Save Assignments
                    </button>
                </div>
            </div>
        </section>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('assignForm');
    if (!form) return;

    const rows            = Array.from(document.querySelectorAll('.user-row'));
    const searchInput     = document.getElementById('userSearch');
    const selectAll       = document.getElementById('selectAll');
    const visibleCountEl  = document.getElementById('visibleCount');
    const selectedCountEl = document.getElementById('selectedCount');
    const headerSelEl     = document.getElementById('headerSelectedCount');
    const noResults       = document.getElementById('noResults');

    const checkboxOf = (row) => row.querySelector('.row-check');
    const visibleRows = () => rows.filter((row) => !row.classList.contains('is-hidden'));

    function refresh() {
        const selectedCount = rows.filter((row) => checkboxOf(row)?.checked).length;
        if (selectedCountEl) selectedCountEl.textContent = selectedCount;
        if (headerSelEl) headerSelEl.textContent = selectedCount;

        const visible = visibleRows();
        if (visibleCountEl) visibleCountEl.textContent = visible.length;
        if (noResults) noResults.classList.toggle('show', rows.length > 0 && visible.length === 0);

        if (selectAll) {
            const checked = visible.filter((row) => checkboxOf(row)?.checked).length;
            selectAll.checked = visible.length > 0 && checked === visible.length;
            selectAll.indeterminate = checked > 0 && checked < visible.length;
        }
    }

    // Toggle a row when clicking anywhere other than the checkbox itself
    rows.forEach((row) => {
        row.addEventListener('click', function (e) {
            if (e.target.closest('input')) return;
            const cb = checkboxOf(row);
            if (!cb) return;
            cb.checked = !cb.checked;
            cb.dispatchEvent(new Event('change', { bubbles: true }));
        });

        checkboxOf(row)?.addEventListener('change', function () {
            row.classList.toggle('selected', this.checked);
            refresh();
        });
    });

    // Select all — applies only to the users currently visible after filtering
    selectAll?.addEventListener('change', function () {
        const shouldCheck = this.checked;
        visibleRows().forEach((row) => {
            const cb = checkboxOf(row);
            if (!cb) return;
            cb.checked = shouldCheck;
            row.classList.toggle('selected', shouldCheck);
        });
        refresh();
    });

    // Search — filters the visible list only; never changes selections
    searchInput?.addEventListener('input', function () {
        const query = this.value.toLowerCase().trim();
        rows.forEach((row) => {
            const name = row.dataset.name || '';
            const email = row.dataset.email || '';
            const matches = query === '' || name.includes(query) || email.includes(query);
            row.classList.toggle('is-hidden', !matches);
        });
        refresh();
    });

    refresh();
});
</script>
@endsection
