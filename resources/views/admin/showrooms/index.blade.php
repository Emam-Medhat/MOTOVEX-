@extends('layouts.admin')
@section('title', 'Showrooms')

@section('content')
<div class="page-header">
    <div>
        <h1>Showrooms</h1>
        <p>{{ $showrooms->total() }} showroom{{ $showrooms->total() !== 1 ? 's' : '' }} in total</p>
    </div>
    <a href="{{ route('admin.showrooms.create') }}" class="btn-primary">
        <i class="fa-solid fa-plus"></i> New Showroom
    </a>
</div>

<div class="admin-table-wrap">
    @if($showrooms->isEmpty())
        <div class="empty-state">
            <i class="fa-solid fa-shop"></i>
            <p>No showrooms found.</p>
        </div>
    @else
    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Location</th>
                <th>Brand</th>
                <th>Status</th>
                <th style="text-align:right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($showrooms as $showroom)
            <tr>
                <td>
                    <strong style="display:block;font-size:13px;color:var(--text)">{{ $showroom->name }}</strong>
                    <span style="font-size:11px;color:var(--muted)">{{ $showroom->email }}</span>
                </td>
                <td style="font-size:12px">{{ $showroom->location }}</td>
                <td>{{ $showroom->brand ? $showroom->brand->name : 'Multi-brand' }}</td>
                <td>
                    <span class="badge {{ $showroom->is_active ? 'badge-green' : 'badge-red' }}">
                        {{ $showroom->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td style="text-align:right">
                    <div class="td-actions" style="gap:6px;justify-content:flex-end">
                        <a href="{{ route('admin.showrooms.edit', $showroom) }}" class="admin-btn-sm btn-edit-alt">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                        <form method="POST" action="{{ route('admin.showrooms.destroy', $showroom) }}"
                              onsubmit="return confirm('Delete this showroom?')"
                              style="display:inline">
                            @csrf @method('DELETE')
                            <button class="admin-btn-sm btn-delete-alt">
                                <i class="fa-solid fa-trash-can"></i> Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="admin-pagination">{{ $showrooms->links() }}</div>
    @endif
</div>
@endsection
