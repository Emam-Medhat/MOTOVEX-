@extends('layouts.admin')
@section('title', 'Users')

@section('content')
<div class="page-header">
    <div>
        <h1>Users & Accounts</h1>
        <p>
            {{ $users->total() }} account{{ $users->total() !== 1 ? 's' : '' }} total
            @if($role) · {{ \Illuminate\Support\Str::headline($role) }}s @endif
            @if($status) · {{ ucfirst($status) }} @endif
        </p>
    </div>
    <div class="d-flex gap-2 align-items-center flex-wrap">
        <a href="{{ route('admin.users.create', ['preset' => 'admin']) }}" class="btn btn-danger btn-sm px-3" style="border-radius: 8px; font-weight: 600; height: 38px; display: inline-flex; align-items: center; gap: 8px;">
            <i class="bi bi-shield-lock"></i> Add Admin
        </a>
        <a href="{{ route('admin.users.create') }}" class="btn-primary-alt">
            <i class="bi bi-person-plus"></i> New User
        </a>
    </div>
</div>

{{-- Tab Navigation --}}
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="{{ route('admin.users.index') }}"
       class="badge-alt text-decoration-none {{ (!$role && !$status) ? 'badge-primary' : 'bg-light text-muted' }}">
        All Users ({{ $roleCounts->sum() }})
    </a>

    @foreach($roles as $roleOption)
    <a href="{{ route('admin.users.index', ['role' => $roleOption->slug]) }}"
       class="badge-alt text-decoration-none {{ ($role === $roleOption->slug && !$status) ? 'badge-primary' : 'bg-light text-muted' }}">
        {{ $roleOption->name }} ({{ $roleCounts[$roleOption->slug] ?? 0 }})
    </a>
    @endforeach

    <a href="{{ route('admin.users.index', ['status' => 'pending']) }}"
       class="badge-alt text-decoration-none {{ $status === 'pending' ? 'badge-warning' : 'bg-light text-muted' }}">
        <i class="bi bi-clock me-1"></i>
        Pending @if($pendingCount > 0)<span class="ms-1 px-2 py-0 bg-white rounded-pill text-warning">{{ $pendingCount }}</span>@endif
    </a>

    <a href="{{ route('admin.users.index', ['status' => 'rejected']) }}"
       class="badge-alt text-decoration-none {{ $status === 'rejected' ? 'badge-danger' : 'bg-light text-muted' }}">
        <i class="bi bi-ban me-1"></i>
        Rejected @if($rejectedCount > 0)<span class="ms-1 px-2 py-0 bg-white rounded-pill text-danger">{{ $rejectedCount }}</span>@endif
    </a>
</div>

{{-- Pending Banner --}}
@if($status === 'pending' && $pendingCount > 0)
<div class="alert alert-warning border-0 shadow-sm d-flex align-items-center gap-3 mb-4 p-3">
    <div class="bg-white rounded-3 p-2 text-warning shadow-sm">
        <i class="bi bi-clock-history fs-4"></i>
    </div>
    <div>
        <h6 class="mb-0 fw-bold">{{ $pendingCount }} Merchant Application{{ $pendingCount !== 1 ? 's' : '' }} Awaiting Review</h6>
        <p class="mb-0 small opacity-75">Review each application to approve or reject merchant access.</p>
    </div>
</div>
@endif

<div class="panel border-0 shadow-sm">
    @if($users->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-people text-muted display-1 opacity-25"></i>
            <p class="mt-3 text-muted">
                @if($status === 'pending') No pending merchant applications.
                @elseif($status === 'rejected') No rejected accounts found.
                @else No users found.
                @endif
            </p>
        </div>
    @else
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Account Status</th>
                    <th>Joined</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: #e0efff; color: #0870dc; display: grid; place-items: center; font-weight: 700; font-size: 12px;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <strong class="d-block text-dark">{{ $user->name }}</strong>
                                @if($user->id === auth()->id())
                                    <span class="badge bg-light text-primary rounded-pill" style="font-size: 8px; padding: 2px 6px;">YOU</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @foreach($user->roles as $userRole)
                        @php
                            $rc = [
                                'admin'        => 'badge-danger',
                                'data_entry'   => 'badge-primary',
                                'sales_agent'  => 'badge-info',
                                'customer'     => 'badge-muted',
                                'merchant'     => 'badge-success',
                            ];
                        @endphp
                        <span class="badge-alt {{ $rc[$userRole->slug] ?? 'bg-light text-muted' }}" style="font-size: 8px; padding: 3px 8px;">{{ $userRole->name }}</span>
                        @endforeach
                    </td>
                    <td>
                        @if($user->account_status === 'pending')
                            <span class="badge-alt badge-warning">Pending</span>
                        @elseif($user->account_status === 'rejected')
                            <span class="badge-alt badge-danger">Rejected</span>
                        @elseif($user->is_active)
                            <span class="badge-alt badge-success">Active</span>
                        @else
                            <span class="badge-alt bg-secondary text-white">Suspended</span>
                        @endif
                    </td>
                    <td class="text-muted small">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.users.show', $user) }}" class="action-btn btn-view" title="View">
                                <i class="bi bi-eye"></i>
                            </a>

                            @if($user->account_status === 'pending' && $user->isMerchant())
                                <form method="POST" action="{{ route('admin.users.approve-merchant', $user) }}" style="display:inline"
                                      onsubmit="return confirm('Approve merchant account for {{ addslashes($user->name) }}?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="action-btn btn-view" title="Approve" style="background:#d9f6e8;color:#008c55">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.users.reject-merchant', $user) }}" style="display:inline"
                                      onsubmit="return confirm('Reject merchant application from {{ addslashes($user->name) }}?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="action-btn btn-delete" title="Reject">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('admin.users.edit', $user) }}" class="action-btn btn-edit" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}" style="display:inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="action-btn {{ $user->is_active ? 'btn-view' : 'btn-primary' }}" 
                                                title="{{ $user->is_active ? 'Suspend' : 'Reactivate' }}"
                                                style="{{ !$user->is_active ? 'background:#e0efff;color:#0870dc' : '' }}">
                                            <i class="bi {{ $user->is_active ? 'bi-person-x' : 'bi-person-check' }}"></i>
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                          onsubmit="return confirm('Delete {{ addslashes($user->name) }}?')" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="action-btn btn-delete" title="Delete">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
    @endif
</div>
@endsection
