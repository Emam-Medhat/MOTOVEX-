@extends('layouts.admin')
@section('title', 'Enquiry — ' . $enquiry->name)

@section('styles')
<style>
    .detail-grid { display: grid; grid-template-columns: 1fr 320px; gap: 20px; }
    .detail-label { font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #6e6888; margin-bottom: 4px; }
    .detail-val { font-size: 13px; color: #d4cfea; }
    @media (max-width: 900px) { .detail-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.enquiries.index') }}"
           style="font-size:12px;color:#6e6888;display:inline-flex;align-items:center;gap:6px;margin-bottom:6px"
           onmouseover="this.style.color='#d4cfea'" onmouseout="this.style.color='#6e6888'">
            <i class="fa-solid fa-arrow-left" style="font-size:10px"></i> Back to Enquiries
        </a>
        <h1 style="font-size:18px">{{ $enquiry->subject }}</h1>
        <p style="font-size:12px;color:#6e6888;margin-top:3px">
            Received {{ $enquiry->created_at->format('d M Y, H:i') }}
        </p>
    </div>
</div>

<div class="detail-grid">

    {{-- Message --}}
    <div>
        <div class="admin-panel">
            <p style="font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#6e6888;margin-bottom:14px">Message</p>
            <p style="font-size:14px;line-height:1.8;color:#c8c3da">{{ $enquiry->message }}</p>
        </div>
    </div>

    {{-- Sidebar --}}
    <div style="display:flex;flex-direction:column;gap:16px">

        {{-- Contact details --}}
        <div class="admin-panel">
            <p style="font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#6e6888;margin-bottom:14px">Contact Details</p>
            <div style="display:flex;flex-direction:column;gap:12px">
                <div>
                    <p class="detail-label">Name</p>
                    <p class="detail-val">{{ $enquiry->name }}</p>
                </div>
                <div>
                    <p class="detail-label">Email</p>
                    <a href="mailto:{{ $enquiry->email }}" class="tbl-link" style="font-size:13px">{{ $enquiry->email }}</a>
                </div>
                @if($enquiry->phone)
                <div>
                    <p class="detail-label">Phone</p>
                    <p class="detail-val">{{ $enquiry->phone }}</p>
                </div>
                @endif
                <div>
                    <p class="detail-label">Type</p>
                    <span class="badge badge-muted" style="text-transform:uppercase;letter-spacing:.5px">
                        {{ str_replace('_', ' ', $enquiry->enquiry_type) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="admin-panel">
            <p style="font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#6e6888;margin-bottom:14px">Update Status</p>
            <form method="POST" action="{{ route('admin.enquiries.update', $enquiry) }}">
                @csrf @method('PUT')
                <select name="status" class="form-select" style="margin-bottom:10px">
                    @foreach(['pending','contacted','completed','cancelled'] as $s)
                        <option value="{{ $s }}" {{ $enquiry->status === $s ? 'selected' : '' }}>
                            {{ ucfirst($s) }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn-primary" style="width:100%;justify-content:center">
                    <i class="fa-solid fa-check"></i> Update Status
                </button>
            </form>

            @can('delete', $enquiry)
            <form method="POST" action="{{ route('admin.enquiries.destroy', $enquiry) }}"
                  onsubmit="return confirm('Permanently delete this enquiry?')" style="margin-top:10px">
                @csrf @method('DELETE')
                <button class="btn-danger" style="width:100%;justify-content:center">
                    <i class="fa-solid fa-trash"></i> Delete Enquiry
                </button>
            </form>
            @endcan
        </div>

    </div>
</div>
@endsection
