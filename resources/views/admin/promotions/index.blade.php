@extends('layouts.admin')
@section('title', 'Promotions')

@section('content')
<div class="page-header">
    <div>
        <h1>Promotions</h1>
        <p>{{ $promotions->total() }} promotion{{ $promotions->total() !== 1 ? 's' : '' }} in total</p>
    </div>
    <a href="{{ route('admin.promotions.create') }}" class="btn-primary">
        <i class="fa-solid fa-plus"></i> New Promotion
    </a>
</div>

<div class="admin-table-wrap">
    @if($promotions->isEmpty())
        <div class="empty-state">
            <i class="fa-solid fa-tags"></i>
            <p>No promotions found.</p>
        </div>
    @else
    <table class="admin-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Brand</th>
                <th>Validity</th>
                <th>Status</th>
                <th style="text-align:right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($promotions as $promo)
            <tr>
                <td>
                    <strong style="display:block;font-size:13px;color:var(--text)">{{ $promo->title }}</strong>
                    <span style="font-size:11px;color:var(--muted)">{{ Str::limit($promo->description, 50) }}</span>
                </td>
                <td>{{ $promo->brand ? $promo->brand->name : 'Global' }}</td>
                <td style="font-size:11px">
                    {{ $promo->start_date?->format('d M Y') ?: '—' }} →
                    {{ $promo->end_date?->format('d M Y') ?: '—' }}
                </td>
                <td>
                    <span class="badge {{ $promo->is_active ? 'badge-green' : 'badge-red' }}">
                        {{ $promo->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td style="text-align:right">
                    <div class="td-actions" style="gap:6px;justify-content:flex-end">
                        <a href="{{ route('admin.promotions.edit', $promo) }}" class="admin-btn-sm btn-edit-alt">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                        <form method="POST" action="{{ route('admin.promotions.destroy', $promo) }}"
                              onsubmit="return confirm('Delete this promotion?')"
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
    <div class="admin-pagination">{{ $promotions->links() }}</div>
    @endif
</div>
@endsection
