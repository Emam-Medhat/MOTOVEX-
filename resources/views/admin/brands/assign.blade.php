@extends('layouts.admin')
@section('title', 'Assign Users — ' . $brand->name)

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.brands.show', $brand) }}"
           style="font-size:12px;color:#6e6888;display:inline-flex;align-items:center;gap:6px;margin-bottom:6px"
           onmouseover="this.style.color='#d4cfea'" onmouseout="this.style.color='#6e6888'">
            <i class="fa-solid fa-arrow-left" style="font-size:10px"></i> {{ $brand->name }}
        </a>
        <h1>Assign Users</h1>
        <p style="font-size:12px;color:#6e6888;margin-top:3px">
            Select Data Entry users who can manage motorcycles for <strong style="color:#c8c3da">{{ $brand->name }}</strong>.
        </p>
    </div>
</div>

<div>
    <div class="admin-panel">
        <form method="POST" action="{{ route('admin.brands.sync-users', $brand) }}" style="display:flex;flex-direction:column;gap:16px">
            @csrf

            @if($users->isEmpty())
                <div class="empty-state" style="padding:30px 0">
                    <i class="fa-solid fa-users"></i>
                    <p>No Data Entry users found.</p>
                    <a href="{{ route('admin.users.create') }}" class="tbl-link" style="margin-top:8px">Create one →</a>
                </div>
            @else
                <p style="font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#6e6888">
                    Data Entry Users
                </p>
                <div style="display:flex;flex-direction:column;gap:8px">
                    @foreach($users as $user)
                    <label style="display:flex;align-items:center;gap:12px;padding:12px 15px;border:1px solid var(--line);border-radius:12px;cursor:pointer;transition:border-color .15s"
                           onmouseover="this.style.borderColor='rgba(155,92,255,.35)'" onmouseout="this.style.borderColor='rgba(255,255,255,.08)'">
                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                               {{ $assignedUserIds->contains($user->id) ? 'checked' : '' }}
                               style="accent-color:#9b5cff;width:16px;height:16px;cursor:pointer;flex-shrink:0">
                        <div style="width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,#9b5cff,#ed5bb7);display:grid;place-items:center;font-weight:700;font-size:12px;flex-shrink:0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div style="min-width:0">
                            <p style="font-size:13px;font-weight:600;color:#f0edfb">{{ $user->name }}</p>
                            <p style="font-size:11px;color:#6e6888">{{ $user->email }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
            @endif

            <div style="display:flex;gap:12px;padding-top:16px;border-top:1px solid var(--line)">
                <button type="submit" class="btn-primary"><i class="fa-solid fa-check"></i> Save Assignments</button>
                <a href="{{ route('admin.brands.show', $brand) }}" class="btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
