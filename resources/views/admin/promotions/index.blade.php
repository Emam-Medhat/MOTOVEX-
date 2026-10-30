@extends('layouts.admin')
@section('title', 'Promotions')

@section('content')
<div class="page-header">
    <div>
        <h1>Promotions & Offers</h1>
        <p>{{ $promotions->total() }} active campaign{{ $promotions->total() !== 1 ? 's' : '' }} running</p>
    </div>
    <a href="{{ route('admin.promotions.create') }}" class="btn-primary-alt">
        <i class="bi bi-plus-lg"></i> New Promotion
    </a>
</div>

<div class="panel border-0 shadow-sm">
    @if($promotions->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-tags text-muted display-1 opacity-25"></i>
            <p class="mt-3 text-muted">No promotions found.</p>
        </div>
    @else
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Promotion Details</th>
                    <th>Brand</th>
                    <th>Validity Period</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($promotions as $promo)
                <tr>
                    <td>
                        <strong class="d-block text-dark">{{ $promo->title }}</strong>
                        <small class="text-muted">{{ Str::limit($promo->description, 50) }}</small>
                    </td>
                    <td>
                        @if($promo->brand)
                            <span class="badge-alt badge-primary" style="font-size: 8px; padding: 3px 8px;">{{ $promo->brand->name }}</span>
                        @else
                            <span class="badge-alt bg-light text-muted" style="font-size: 8px; padding: 3px 8px;">Global</span>
                        @endif
                    </td>
                    <td>
                        <div class="small">
                            <i class="bi bi-calendar-event text-muted me-1"></i>
                            {{ $promo->start_date?->format('d M Y') ?: '—' }}
                            <i class="bi bi-arrow-right mx-1 text-muted"></i>
                            {{ $promo->end_date?->format('d M Y') ?: '—' }}
                        </div>
                    </td>
                    <td>
                        <span class="badge-alt {{ $promo->is_active ? 'badge-success' : 'badge-danger' }}">
                            {{ $promo->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.promotions.edit', $promo) }}" class="action-btn btn-edit" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.promotions.destroy', $promo) }}"
                                  onsubmit="return confirm('Delete this promotion?')"
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
    <div class="mt-4">{{ $promotions->links() }}</div>
    @endif
</div>
@endsection
