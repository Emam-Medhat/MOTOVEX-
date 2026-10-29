@extends('layouts.admin')
@section('title', 'Enquiries')

@section('content')
<div class="page-header">
    <div>
        <h1>Enquiries</h1>
        <p>{{ $enquiries->total() }} enquir{{ $enquiries->total() !== 1 ? 'ies' : 'y' }} in total</p>
    </div>
</div>

<div class="admin-table-wrap">
    @if($enquiries->isEmpty())
        <div class="empty-state">
            <i class="fa-solid fa-envelope"></i>
            <p>No enquiries yet.</p>
        </div>
    @else
    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Subject</th>
                <th>Type</th>
                <th>Status</th>
                <th>Received</th>
                <th style="text-align:right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($enquiries as $enquiry)
            <tr>
                <td>
                    <strong style="display:block;font-size:13px;color:#f0edfb">{{ $enquiry->name }}</strong>
                    <span style="font-size:11px;color:#6e6888">{{ $enquiry->email }}</span>
                </td>
                <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:12px">
                    {{ $enquiry->subject }}
                </td>
                <td>
                    <span class="badge badge-muted" style="text-transform:uppercase;letter-spacing:.5px">
                        {{ str_replace('_', ' ', $enquiry->enquiry_type) }}
                    </span>
                </td>
                <td>
                    @php
                        $sc = ['pending'=>'badge-yellow','contacted'=>'badge-blue','completed'=>'badge-green','cancelled'=>'badge-red'];
                    @endphp
                    <span class="badge {{ $sc[$enquiry->status] ?? 'badge-muted' }}">
                        {{ ucfirst($enquiry->status) }}
                    </span>
                </td>
                <td style="font-size:11px">{{ $enquiry->created_at->diffForHumans() }}</td>
                <td>
                    <div class="td-actions">
                        <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="tbl-link">View</a>
                        @can('delete', $enquiry)
                        <form method="POST" action="{{ route('admin.enquiries.destroy', $enquiry) }}"
                              onsubmit="return confirm('Delete this enquiry?')">
                            @csrf @method('DELETE')
                            <button class="tbl-link-danger">Delete</button>
                        </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="admin-pagination">{{ $enquiries->links() }}</div>
    @endif
</div>
@endsection
