@extends('layouts.admin')
@section('title', $user->name)

@section('styles')
<style>
    .user-show-grid { display: grid; grid-template-columns: 280px 1fr; gap: 20px; }
    @media (max-width: 860px) { .user-show-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.users.index') }}"
           style="font-size:12px;color:#6e6888;display:inline-flex;align-items:center;gap:6px;margin-bottom:6px"
           onmouseover="this.style.color='#d4cfea'" onmouseout="this.style.color='#6e6888'">
            <i class="fa-solid fa-arrow-left" style="font-size:10px"></i> Back to Users
        </a>
        <h1>{{ $user->name }}</h1>
    </div>
    <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary">
        <i class="fa-solid fa-pen"></i> Edit User
    </a>
</div>

<div class="user-show-grid">

    {{-- Profile card --}}
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="admin-panel" style="text-align:center">
            <div style="width:60px;height:60px;border-radius:18px;background:linear-gradient(135deg,#9b5cff,#ed5bb7);display:grid;place-items:center;font-size:22px;font-weight:800;margin:0 auto 14px">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <p style="font-size:15px;font-weight:700;color:#f0edfb">{{ $user->name }}</p>
            <a href="mailto:{{ $user->email }}" class="tbl-link" style="font-size:12px;display:block;margin-top:4px">{{ $user->email }}</a>
            @if($user->phone)
                <p style="font-size:12px;color:#6e6888;margin-top:3px">{{ $user->phone }}</p>
            @endif
            @if($user->city || $user->country)
                <p style="font-size:11px;color:#6e6888;margin-top:3px">
                    {{ collect([$user->city, $user->country])->filter()->implode(', ') }}
                </p>
            @endif

            <div style="display:flex;flex-wrap:wrap;gap:6px;justify-content:center;margin-top:12px">
                @foreach($user->roles as $role)
                @php
                    $rc = ['admin'=>'badge-purple','data_entry'=>'badge-blue','sales_agent'=>'badge-yellow','customer'=>'badge-muted'];
                @endphp
                <span class="badge {{ $rc[$role->slug] ?? 'badge-muted' }}">{{ $role->name }}</span>
                @endforeach
                <span class="badge {{ $user->is_active ? 'badge-green' : 'badge-red' }}">
                    {{ $user->is_active ? 'Active' : 'Suspended' }}
                </span>
            </div>

            <p style="font-size:11px;color:#6e6888;margin-top:12px">
                Joined {{ $user->created_at->format('d M Y') }}
            </p>
        </div>
    </div>

    {{-- Brands --}}
    <div class="admin-table-wrap">
        <div style="padding:16px 18px;border-bottom:1px solid var(--line)">
            <strong style="font-size:13px">Assigned Brands</strong>
        </div>
        @if($user->brands->isEmpty())
            <div class="empty-state">
                <i class="fa-regular fa-copyright"></i>
                <p>
                    @if($user->isAdmin()) Admin has access to all brands.
                    @elseif($user->isCustomer() || $user->isSalesAgent()) Not applicable for this role.
                    @else No brands assigned yet.
                    @endif
                </p>
                @if(!$user->isAdmin() && !$user->isCustomer() && !$user->isSalesAgent())
                    <a href="{{ route('admin.brands.index') }}" class="tbl-link" style="margin-top:8px">Assign from a brand →</a>
                @endif
            </div>
        @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Brand</th>
                    <th>Country</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user->brands as $brand)
                <tr>
                    <td style="font-weight:600;color:#f0edfb">{{ $brand->name }}</td>
                    <td style="color:#6e6888">{{ $brand->country ?? '—' }}</td>
                    <td>
                        <div class="td-actions">
                            <a href="{{ route('admin.brands.assign-users', $brand) }}" class="tbl-link">Manage</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

</div>
@endsection
