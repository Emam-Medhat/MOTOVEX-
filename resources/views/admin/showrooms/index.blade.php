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
                    <strong style="display:block;font-size:13px;color:#f0edfb">{{ $showroom->name }}</strong>
                    <span style="font-size:11px;color:#6e6888">{{ $showroom->email }}</span>
                </td>
                <td style="font-size:12px">{{ $showroom->location }}</td>
                <td>{{ $showroom->brand ? $showroom->brand->name : 'Multi-brand' }}</td>
                <td>
                    <span class="badge {{ $showroom->is_active ? 'badge-green' : 'badge-red' }}">
                        {{ $showroom->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <div class="td-actions">
                        <a href="{{ route('admin.showrooms.edit', $showroom) }}" class="tbl-link">Edit</a>
                        <form method="POST" action="{{ route('admin.showrooms.destroy', $showroom) }}"
                              onsubmit="return confirm('Delete this showroom?')">
                            @csrf @method('DELETE')
                            <button class="tbl-link-danger">Delete</button>
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
