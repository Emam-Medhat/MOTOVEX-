@extends('layouts.admin')
@section('title', 'Showrooms')

@section('content')
<div class="page-header">
    <div>
        <h1>Showrooms</h1>
        <p>{{ $showrooms->total() }} showroom{{ $showrooms->total() !== 1 ? 's' : '' }} total in network</p>
    </div>
    <a href="{{ route('admin.showrooms.create') }}" class="btn-primary-alt">
        <i class="bi bi-plus-lg"></i> New Showroom
    </a>
</div>

<div class="panel border-0 shadow-sm">
    @if($showrooms->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-shop text-muted display-1 opacity-25"></i>
            <p class="mt-3 text-muted">No showrooms found.</p>
        </div>
    @else
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Showroom Name</th>
                    <th>Location</th>
                    <th>Brand</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($showrooms as $showroom)
                <tr>
                    <td>
                        <strong class="d-block text-dark">{{ $showroom->name }}</strong>
                        <small class="text-muted">{{ $showroom->email }}</small>
                    </td>
                    <td><small class="text-muted">{{ $showroom->location }}</small></td>
                    <td>
                        @if($showroom->brand)
                            <span class="badge-alt badge-primary" style="font-size: 8px; padding: 3px 8px;">{{ $showroom->brand->name }}</span>
                        @else
                            <span class="badge-alt bg-light text-muted" style="font-size: 8px; padding: 3px 8px;">Multi-brand</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge-alt {{ $showroom->is_active ? 'badge-success' : 'badge-danger' }}">
                            {{ $showroom->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.showrooms.edit', $showroom) }}" class="action-btn btn-edit" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.showrooms.destroy', $showroom) }}"
                                  onsubmit="return confirm('Delete this showroom?')"
                                  style="display:inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn btn-delete" title="Delete">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $showrooms->links() }}</div>
    @endif
</div>
@endsection
