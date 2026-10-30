@extends('layouts.admin')
@section('title', 'User — ' . $user->name)

@section('styles')
<style>
.user-grid {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 22px;
    align-items: start;
}
@media(max-width:1024px){ .user-grid{ grid-template-columns:1fr; } }
@media(max-width:640px){  .summary-grid-4{ grid-template-columns: repeat(2,1fr) !important; } }

.profile-panel {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
}
.profile-accent {
    height: 72px;
    background: linear-gradient(135deg,#8b5cf6,#6d28d9);
    opacity: .12;
}
.profile-avatar-wrap {
    margin-top: -38px;
    display: flex;
    justify-content: center;
    padding-bottom: 18px;
}
.profile-avatar {
    width: 76px; height: 76px;
    border-radius: 18px;
    background: linear-gradient(135deg,#8b5cf6,#6d28d9);
    display: grid; place-items: center;
    font-size: 30px; font-weight: 800; color: #fff;
    border: 4px solid #fff;
    box-shadow: 0 8px 20px rgba(109,40,217,.22);
}
.summary-card {
    background: #fff; border: 1px solid var(--border);
    border-radius: 12px; padding: 14px 16px;
    display: flex; align-items: center; gap: 12px;
}
.summary-icon {
    width: 38px; height: 38px; border-radius: 10px;
    display: grid; place-items: center; font-size: 15px; flex-shrink: 0;
}
.brand-item {
    padding: 14px 20px;
    border-bottom: 1px solid #f1f5f9;
    display: flex; align-items: center; justify-content: space-between;
    transition: background .12s;
}
.brand-item:last-child { border-bottom: none; }
.brand-item:hover { background: #f8fafc; }

.info-row {
    display: flex; align-items: flex-start; gap: 12px;
    font-size: 13px; margin-bottom: 14px;
}
.info-row:last-child { margin-bottom: 0; }
.info-row-icon { width: 16px; color: var(--primary); margin-top: 1px; flex-shrink: 0; }
</style>
@endsection

@section('content')

{{-- ── Page Header ── --}}
<div class="page-header" style="margin-bottom:26px;">
    <div>
        <nav style="font-size:12px;color:var(--muted);margin-bottom:8px;display:flex;align-items:center;gap:6px;">
            <a href="{{ route('admin.dashboard') }}" style="color:var(--muted);text-decoration:none;">Admin</a>
            <i class="bi bi-chevron-right" style="font-size:8px;opacity:.5;"></i>
            <a href="{{ route('admin.users.index') }}" style="color:var(--muted);text-decoration:none;">Users</a>
            <i class="bi bi-chevron-right" style="font-size:8px;opacity:.5;"></i>
            <span style="color:var(--dark);font-weight:500;">{{ $user->name }}</span>
        </nav>
        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
            <h1 style="margin:0;">{{ $user->name }}</h1>
            @if($user->account_status === 'pending')
                <span class="badge badge-yellow"><i class="bi bi-clock" style="font-size:9px;"></i> Pending</span>
            @elseif($user->account_status === 'rejected')
                <span class="badge badge-red"><i class="bi bi-ban" style="font-size:9px;"></i> Rejected</span>
            @elseif($user->is_active)
                <span class="badge badge-green">Active</span>
            @else
                <span class="badge badge-red">Suspended</span>
            @endif
        </div>
        <p style="margin-top:4px;font-size:13px;color:var(--muted);">{{ $user->roleName() }} Account</p>

        @if($user->account_status === 'pending' && $user->isMerchant())
        <div style="margin-top:12px;display:flex;align-items:center;gap:10px;padding:12px 16px;background:#fffbeb;border:1px solid #fde68a;border-radius:12px;flex-wrap:wrap;">
            <span style="font-size:12px;color:#92400e;font-weight:600;flex:1;min-width:200px;">
                <i class="bi bi-clock-history me-1"></i> Merchant application awaiting review
            </span>
            <div style="display:flex;gap:8px;">
                <form method="POST" action="{{ route('admin.users.approve-merchant', $user) }}"
                      onsubmit="return confirm('Approve merchant for {{ addslashes($user->name) }}?')">
                    @csrf @method('PATCH')
                    <button type="submit" style="padding:6px 14px;background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d;border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;">
                        <i class="bi bi-check-circle-fill"></i> Approve
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.users.reject-merchant', $user) }}"
                      onsubmit="return confirm('Reject this merchant application?')">
                    @csrf @method('PATCH')
                    <button type="submit" style="padding:6px 14px;background:#fef2f2;border:1px solid #fecaca;color:#dc2626;border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;">
                        <i class="bi bi-ban"></i> Reject
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
    <div style="display:flex;gap:10px;align-items:flex-start;">
        <a href="{{ route('admin.users.index') }}" class="btn-ghost">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary-alt">
            <i class="bi bi-person-gear"></i> Edit User
        </a>
    </div>
</div>

{{-- ── Summary Cards ── --}}
<div class="summary-grid-4" style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px;">
    <div class="summary-card">
        <div class="summary-icon" style="background:#f5f3ff;color:#7c3aed;"><i class="bi bi-patch-check-fill"></i></div>
        <div>
            <div style="font-size:10px;color:var(--muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Brands</div>
            <div style="font-size:16px;font-weight:800;color:var(--dark);">{{ $user->isAdmin() ? 'All' : $user->brands->count() }}</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon" style="background:#eff6ff;color:#2563eb;"><i class="bi bi-shield-fill"></i></div>
        <div>
            <div style="font-size:10px;color:var(--muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Role</div>
            <div style="font-size:14px;font-weight:800;color:var(--dark);">{{ $user->roleName() }}</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon" style="background:{{ $user->is_active ? '#f0fdf4' : '#fef2f2' }};color:{{ $user->is_active ? '#16a34a' : '#dc2626' }};">
            <i class="bi bi-{{ $user->is_active ? 'check-circle-fill' : 'x-circle-fill' }}"></i>
        </div>
        <div>
            <div style="font-size:10px;color:var(--muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Status</div>
            <div style="font-size:14px;font-weight:800;color:var(--dark);">
                @if($user->account_status === 'pending') Pending
                @elseif($user->account_status === 'rejected') Rejected
                @elseif($user->is_active) Active
                @else Suspended @endif
            </div>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon" style="background:#fffbeb;color:#d97706;"><i class="bi bi-calendar2-fill"></i></div>
        <div>
            <div style="font-size:10px;color:var(--muted);font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Joined</div>
            <div style="font-size:14px;font-weight:800;color:var(--dark);">{{ $user->created_at->format('M Y') }}</div>
        </div>
    </div>
</div>

{{-- ── Main grid ── --}}
<div class="user-grid">

    {{-- Profile Panel --}}
    <aside class="profile-panel">
        <div class="profile-accent"></div>
        <div class="profile-avatar-wrap">
            <div class="profile-avatar">{{ strtoupper(substr($user->name,0,1)) }}</div>
        </div>
        <div style="padding:0 22px 22px;text-align:center;">
            <h3 style="margin:0;font-size:17px;font-weight:800;color:var(--dark);">{{ $user->name }}</h3>
            <p style="font-size:12.5px;color:var(--muted);margin-top:3px;">{{ $user->email }}</p>

            <div style="margin-top:22px;display:flex;flex-direction:column;gap:0;text-align:left;border-top:1px solid var(--border);padding-top:16px;">
                <div class="info-row">
                    <i class="bi bi-envelope-fill info-row-icon"></i>
                    <div>
                        <div style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;">Email</div>
                        <div style="color:var(--dark);font-weight:500;font-size:12.5px;word-break:break-all;">{{ $user->email }}</div>
                    </div>
                </div>
                @if($user->phone)
                <div class="info-row">
                    <i class="bi bi-telephone-fill info-row-icon"></i>
                    <div>
                        <div style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;">Phone</div>
                        <div style="color:var(--dark);font-weight:500;font-size:12.5px;">{{ $user->phone }}</div>
                    </div>
                </div>
                @endif
                @if($user->city || $user->country)
                <div class="info-row">
                    <i class="bi bi-geo-alt-fill info-row-icon"></i>
                    <div>
                        <div style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;">Location</div>
                        <div style="color:var(--dark);font-weight:500;font-size:12.5px;">{{ collect([$user->city,$user->country])->filter()->implode(', ') }}</div>
                    </div>
                </div>
                @endif
                <div class="info-row">
                    <i class="bi bi-calendar-check-fill info-row-icon"></i>
                    <div>
                        <div style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;">Member Since</div>
                        <div style="color:var(--dark);font-weight:500;font-size:12.5px;">{{ $user->created_at->format('d F Y') }}</div>
                    </div>
                </div>
            </div>

            <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border);display:flex;flex-wrap:wrap;gap:6px;justify-content:center;">
                @foreach($user->roles as $role)
                    @php $rc = ['admin'=>'badge-red','data_entry'=>'badge-blue','sales_agent'=>'badge-yellow','customer'=>'badge-muted','merchant'=>'badge-green']; @endphp
                    <span class="badge {{ $rc[$role->slug] ?? 'badge-muted' }}">{{ $role->name }}</span>
                @endforeach
            </div>
        </div>
    </aside>

    {{-- Right: Brands --}}
    <main class="admin-panel" style="padding:0;">
        <div style="padding:20px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
            <div>
                <h3 style="margin:0;font-size:15px;font-weight:800;color:var(--dark);">Assigned Brands</h3>
                <p style="margin-top:3px;font-size:12px;color:var(--muted);">
                    {{ $user->isAdmin() ? 'Global admin — access to all brands.' : 'Brands this user can manage.' }}
                </p>
            </div>
            <span class="badge badge-purple">
                {{ $user->isAdmin() ? 'Full Access' : $user->brands->count() . ' Brand' . ($user->brands->count() !== 1 ? 's' : '') }}
            </span>
        </div>

        @if($user->brands->isEmpty())
            <div class="empty-state" style="padding:52px 24px;">
                <i class="bi bi-award"></i>
                <p>
                    @if($user->isAdmin()) Administrators have full access to all brands automatically.
                    @elseif(method_exists($user,'isCustomer') && ($user->isCustomer() || $user->isSalesAgent())) This role does not require brand assignment.
                    @else No brands assigned yet. Use the brand page to assign this user.
                    @endif
                </p>
                @if(!$user->isAdmin() && !(method_exists($user,'isCustomer') && $user->isCustomer()))
                <a href="{{ route('admin.brands.index') }}" class="admin-btn-sm btn-view-alt" style="margin-top:16px;padding:8px 18px;">
                    <i class="bi bi-collection"></i> Browse Brands
                </a>
                @endif
            </div>
        @else
            <div>
                @foreach($user->brands as $brand)
                <div class="brand-item">
                    <div style="display:flex;align-items:center;gap:14px;">
                        <div style="width:42px;height:42px;border-radius:11px;background:#f3e8ff;border:1px solid #e9d5ff;display:grid;place-items:center;font-weight:800;color:var(--purple);font-size:15px;flex-shrink:0;">
                            {{ strtoupper(substr($brand->name,0,1)) }}
                        </div>
                        <div>
                            <div style="font-size:14px;font-weight:700;color:var(--dark);">{{ $brand->name }}</div>
                            <div style="font-size:12px;color:var(--muted);margin-top:1px;">
                                <i class="bi bi-globe" style="font-size:10px;margin-right:3px;"></i>{{ $brand->country ?? 'Universal' }}
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.brands.assign-users', $brand) }}" class="admin-btn-sm btn-view-alt">
                        <i class="bi bi-people-fill"></i> Manage
                    </a>
                </div>
                @endforeach
            </div>
        @endif
    </main>
</div>
@endsection
