@extends('layouts.admin')
@section('title', 'User Details')

@section('styles')
<style>
    .user-details-grid {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 24px;
        align-items: start;
    }

    .profile-panel {
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .profile-accent {
        height: 80px;
        background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
        opacity: 0.1;
    }

    .profile-avatar-wrap {
        margin-top: -40px;
        display: flex;
        justify-content: center;
        padding-bottom: 20px;
    }

    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        display: grid;
        place-items: center;
        font-size: 32px;
        font-weight: 800;
        color: #fff;
        border: 4px solid #fff;
        box-shadow: 0 10px 15px -3px rgba(109, 40, 217, 0.2);
    }

    .summary-card {
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 12px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .summary-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        font-size: 16px;
    }

    .brand-item {
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: background 0.15s;
    }

    .brand-item:last-child {
        border-bottom: 0;
    }

    .brand-item:hover {
        background: #f8fafc;
    }

    @media (max-width: 1024px) {
        .user-details-grid {
            grid-template-columns: 1fr;
        }
        .summary-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }

    @media (max-width: 640px) {
        .summary-grid {
            grid-template-columns: 1fr !important;
        }
        .page-header {
            flex-direction: column;
            align-items: flex-start !important;
        }
        .page-header > div:last-child {
            margin-top: 16px;
            width: 100%;
            display: flex;
            gap: 10px;
        }
        .page-header .btn-primary, .page-header .btn-ghost {
            flex: 1;
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
{{-- Breadcrumbs & Header --}}
<div class="page-header" style="margin-bottom: 28px;">
    <div>
        <nav style="font-size: 12px; color: var(--muted); margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
            <a href="{{ route('admin.dashboard') }}" style="color: var(--muted); text-decoration: none;">Admin</a>
            <i class="fa-solid fa-chevron-right" style="font-size: 8px; opacity: 0.5;"></i>
            <a href="{{ route('admin.users.index') }}" style="color: var(--muted); text-decoration: none;">Users</a>
            <i class="fa-solid fa-chevron-right" style="font-size: 8px; opacity: 0.5;"></i>
            <span style="color: var(--text); font-weight: 500;">{{ $user->name }}</span>
        </nav>
        <div style="display: flex; align-items: center; gap: 12px;">
            <h1 style="margin: 0;">{{ $user->name }}</h1>
            <span class="badge {{ $user->is_active ? 'badge-green' : 'badge-red' }}" style="padding: 4px 10px;">
                {{ $user->is_active ? 'Active' : 'Suspended' }}
            </span>
        </div>
        <p style="margin-top: 4px; font-size: 13px; color: var(--muted);">{{ $user->roleName() }} Account</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('admin.users.index') }}" class="btn-ghost">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
        <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary">
            <i class="fa-solid fa-user-pen"></i> Edit User
        </a>
    </div>
</div>

{{-- Summary Row --}}
<div class="summary-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px;">
    <div class="summary-card">
        <div class="summary-icon" style="background: #f5f3ff; color: #7c3aed;">
            <i class="fa-solid fa-copyright"></i>
        </div>
        <div>
            <div style="font-size: 10px; color: var(--muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Assigned Brands</div>
            <div style="font-size: 16px; font-weight: 800; color: var(--text);">{{ $user->isAdmin() ? 'Global' : $user->brands->count() }}</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon" style="background: #eff6ff; color: #2563eb;">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
        <div>
            <div style="font-size: 10px; color: var(--muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Account Role</div>
            <div style="font-size: 16px; font-weight: 800; color: var(--text);">{{ $user->roleName() }}</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon" style="background: {{ $user->is_active ? '#ecfdf5' : '#fef2f2' }}; color: {{ $user->is_active ? '#059669' : '#dc2626' }};">
            <i class="fa-solid {{ $user->is_active ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
        </div>
        <div>
            <div style="font-size: 10px; color: var(--muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Status</div>
            <div style="font-size: 16px; font-weight: 800; color: var(--text);">{{ $user->is_active ? 'Active' : 'Suspended' }}</div>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon" style="background: #fffbeb; color: #d97706;">
            <i class="fa-solid fa-calendar-day"></i>
        </div>
        <div>
            <div style="font-size: 10px; color: var(--muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Member Since</div>
            <div style="font-size: 16px; font-weight: 800; color: var(--text);">{{ $user->created_at->format('M Y') }}</div>
        </div>
    </div>
</div>

<div class="user-details-grid">
    {{-- Left: Profile Panel --}}
    <aside class="profile-panel">
        <div class="profile-accent"></div>
        <div class="profile-avatar-wrap">
            <div class="profile-avatar">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        </div>
        
        <div style="padding: 0 24px 24px; text-align: center;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 800; color: var(--text);">{{ $user->name }}</h3>
            <p style="font-size: 13px; color: var(--muted); margin-top: 4px;">{{ $user->email }}</p>
            
            <div style="margin-top: 24px; display: flex; flex-direction: column; gap: 14px; text-align: left;">
                <div style="display: flex; align-items: flex-start; gap: 12px; font-size: 13px;">
                    <i class="fa-solid fa-envelope" style="width: 16px; color: var(--purple); margin-top: 2px;"></i>
                    <div style="min-width: 0;">
                        <div style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Email Address</div>
                        <div style="color: var(--text); font-weight: 500; overflow-wrap: break-word;">{{ $user->email }}</div>
                    </div>
                </div>
                
                @if($user->phone)
                <div style="display: flex; align-items: flex-start; gap: 12px; font-size: 13px;">
                    <i class="fa-solid fa-phone" style="width: 16px; color: var(--purple); margin-top: 2px;"></i>
                    <div>
                        <div style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Phone Number</div>
                        <div style="color: var(--text); font-weight: 500;">{{ $user->phone }}</div>
                    </div>
                </div>
                @endif
                
                @if($user->city || $user->country)
                <div style="display: flex; align-items: flex-start; gap: 12px; font-size: 13px;">
                    <i class="fa-solid fa-location-dot" style="width: 16px; color: var(--purple); margin-top: 2px;"></i>
                    <div>
                        <div style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Location</div>
                        <div style="color: var(--text); font-weight: 500;">{{ collect([$user->city, $user->country])->filter()->implode(', ') }}</div>
                    </div>
                </div>
                @endif
                
                <div style="display: flex; align-items: flex-start; gap: 12px; font-size: 13px;">
                    <i class="fa-solid fa-calendar-check" style="width: 16px; color: var(--purple); margin-top: 2px;"></i>
                    <div>
                        <div style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Join Date</div>
                        <div style="color: var(--text); font-weight: 500;">{{ $user->created_at->format('d F Y') }}</div>
                    </div>
                </div>
            </div>

            <div style="margin-top: 28px; padding-top: 20px; border-top: 1px solid #f1f5f9; display: flex; flex-wrap: wrap; gap: 6px; justify-content: center;">
                @foreach($user->roles as $role)
                    @php $rc = ['admin'=>'badge-purple','data_entry'=>'badge-blue','sales_agent'=>'badge-yellow','customer'=>'badge-muted']; @endphp
                    <span class="badge {{ $rc[$role->slug] ?? 'badge-muted' }}" style="padding: 4px 10px;">{{ $role->name }}</span>
                @endforeach
            </div>
        </div>
    </aside>

    {{-- Right: Assigned Brands --}}
    <main class="admin-panel" style="padding: 0;">
        <div style="padding: 24px; border-bottom: 1px solid var(--line);">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h3 style="margin: 0; font-size: 16px; font-weight: 800; color: var(--text);">Assigned Brands</h3>
                    <p style="margin-top: 4px; font-size: 12px; color: var(--muted);">
                        {{ $user->isAdmin() ? 'Global administrative access to all brands.' : 'Specific brands this user is authorized to manage.' }}
                    </p>
                </div>
                <div style="background: var(--purple-bg); color: var(--purple); font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 20px;">
                    {{ $user->isAdmin() ? 'Full Access' : $user->brands->count() . ' Brands' }}
                </div>
            </div>
        </div>

        @if($user->brands->isEmpty())
            <div class="empty-state" style="padding: 60px 24px;">
                <div style="width: 56px; height: 56px; border-radius: 14px; background: #f8fafc; border: 1px solid #e2e8f0; display: grid; place-items: center; margin: 0 auto 20px; color: #cbd5e1;">
                    <i class="fa-regular fa-copyright" style="font-size: 28px;"></i>
                </div>
                <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: var(--text);">No Brands Assigned</h4>
                <p style="margin-top: 8px; font-size: 13px; color: var(--muted); max-w: 340px; margin-left: auto; margin-right: auto; line-height: 1.5;">
                    @if($user->isAdmin()) Administrators automatically inherit access to every brand in the catalogue.
                    @elseif($user->isCustomer() || $user->isSalesAgent()) This role type does not require brand assignment.
                    @else This user has not been linked to any brands yet. Assign them from the brand management section.
                    @endif
                </p>
                @if(!$user->isAdmin() && !$user->isCustomer() && !$user->isSalesAgent())
                    <a href="{{ route('admin.brands.index') }}" class="admin-btn-sm btn-edit-alt" style="margin-top: 20px; padding: 8px 18px;">
                        <i class="fa-solid fa-magnifying-glass"></i> Browse Brands Directory
                    </a>
                @endif
            </div>
        @else
            <div style="padding: 8px 0;">
                @foreach($user->brands as $brand)
                <div class="brand-item" style="padding: 16px 24px;">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, #f8fafc, #f1f5f9); border: 1px solid #e2e8f0; display: grid; place-items: center; font-weight: 800; color: var(--purple); font-size: 16px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">
                            {{ strtoupper(substr($brand->name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-size: 14px; font-weight: 700; color: var(--text);">{{ $brand->name }}</div>
                            <div style="font-size: 12px; color: var(--muted); display: flex; align-items: center; gap: 6px; margin-top: 2px;">
                                <i class="fa-solid fa-globe" style="font-size: 10px;"></i>
                                {{ $brand->country ?? 'Universal' }}
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.brands.assign-users', $brand) }}" class="admin-btn-sm btn-view-alt" style="padding: 7px 14px;">
                        <i class="fa-solid fa-users-gear"></i> Manage
                    </a>
                </div>
                @endforeach
            </div>
        @endif
    </main>
</div>
@endsection
