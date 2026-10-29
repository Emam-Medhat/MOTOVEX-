@extends('layouts.admin')
@section('title', 'Users')

@section('content')
<div class="page-header">
    <div>
        <h1>Users</h1>
        <p>
            {{ $users->total() }} account{{ $users->total() !== 1 ? 's' : '' }}
            @if($role) &nbsp;·&nbsp; filtered by {{ \Illuminate\Support\Str::headline($role) }} @endif
        </p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn-primary">
        <i class="fa-solid fa-user-plus"></i> New User
    </a>
</div>

{{-- Role filter pills --}}
<div style="display:flex;flex-wrap:wrap;gap:7px;margin-bottom:18px">
    <a href="{{ route('admin.users.index') }}"
       class="badge {{ !$role ? 'badge-purple' : 'badge-muted' }}"
       style="padding:5px 12px;font-size:11px;cursor:pointer;text-decoration:none">
        All ({{ $roleCounts->sum() }})
    </a>
    @foreach($roles as $roleOption)
    <a href="{{ route('admin.users.index', ['role' => $roleOption->slug]) }}"
       class="badge {{ $role === $roleOption->slug ? 'badge-purple' : 'badge-muted' }}"
       style="padding:5px 12px;font-size:11px;cursor:pointer;text-decoration:none">
        {{ $roleOption->name }} ({{ $roleCounts[$roleOption->slug] ?? 0 }})
    </a>
    @endforeach
</div>

<div class="admin-table-wrap">
    @if($users->isEmpty())
        <div class="empty-state">
            <i class="fa-solid fa-users"></i>
            <p>No users found.</p>
        </div>
    @else
    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Joined</th>
                <th style="text-align:right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px">
                        <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#8b5cf6,#6d28d9);display:grid;place-items:center;font-weight:700;font-size:12px;flex-shrink:0;color:#fff">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <strong style="font-size:13px;color:var(--text)">{{ $user->name }}</strong>
                            @if($user->id === auth()->id())
                                <span class="badge badge-muted" style="margin-left:5px;padding:2px 7px;font-size:9px">You</span>
                            @endif
                        </div>
                    </div>
                </td>
                <td style="font-size:12px">{{ $user->email }}</td>
                <td>
                    @foreach($user->roles as $userRole)
                    @php
                        $rc = ['admin'=>'badge-purple','data_entry'=>'badge-blue','sales_agent'=>'badge-yellow','customer'=>'badge-muted'];
                    @endphp
                    <span class="badge {{ $rc[$userRole->slug] ?? 'badge-muted' }}">{{ $userRole->name }}</span>
                    @endforeach
                </td>
                <td>
                    <span class="badge {{ $user->is_active ? 'badge-green' : 'badge-red' }}">
                        {{ $user->is_active ? 'Active' : 'Suspended' }}
                    </span>
                </td>
                <td style="font-size:11px">{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    <div class="td-actions" style="gap:6px;justify-content:flex-end">
                        <a href="{{ route('admin.users.show', $user) }}" class="admin-btn-sm btn-view-alt">
                            <i class="fa-solid fa-eye"></i> View
                        </a>
                        <a href="{{ route('admin.users.edit', $user) }}" class="admin-btn-sm btn-edit-alt">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                        
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}" style="display:inline">
                            @csrf @method('PATCH')
                            <button class="admin-btn-sm {{ $user->is_active ? 'btn-suspend-alt' : 'btn-success-alt' }}">
                                @if($user->is_active)
                                    <i class="fa-solid fa-user-slash"></i> Suspend
                                @else
                                    <i class="fa-solid fa-user-check"></i> Reactivate
                                @endif
                            </button>
                        </form>
                        
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                              onsubmit="return confirm('Delete {{ addslashes($user->name) }}?')" style="display:inline">
                            @csrf @method('DELETE')
                            <button class="admin-btn-sm btn-delete-alt">
                                <i class="fa-solid fa-trash-can"></i> Delete
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="admin-pagination">{{ $users->links() }}</div>
    @endif
</div>
@endsection
