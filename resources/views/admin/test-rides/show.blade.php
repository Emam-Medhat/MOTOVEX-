@extends('layouts.admin')
@section('title', 'Booking ' . $testRide->booking_reference)

@section('styles')
<style>
    .detail-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
    @media(max-width:800px){ .detail-grid{ grid-template-columns:1fr; } }

    .detail-section { background:var(--surface); border:1px solid var(--line); border-radius:12px; overflow:hidden; }
    .detail-section-head { padding:14px 18px; border-bottom:1px solid var(--line); background:#f8fafc; display:flex; align-items:center; gap:10px; }
    .detail-section-head i { color:var(--purple); font-size:13px; }
    .detail-section-head span { font-size:13px; font-weight:700; color:var(--text); }
    .detail-section-body { padding:18px; }
    .detail-row { display:flex; align-items:flex-start; justify-content:space-between; gap:12px; padding:9px 0; border-bottom:1px solid var(--line); }
    .detail-row:last-child { border-bottom:0; padding-bottom:0; }
    .detail-row .lbl { font-size:11px; font-weight:600; color:var(--muted); flex-shrink:0; min-width:120px; }
    .detail-row .val { font-size:12.5px; color:var(--text); font-weight:500; text-align:right; word-break:break-word; }

    .moto-card { display:flex; align-items:center; gap:14px; padding:14px; background:#f8fafc; border-radius:10px; border:1px solid var(--line); margin-bottom:14px; }
    .moto-card img { width:72px; height:56px; border-radius:8px; object-fit:cover; flex-shrink:0; }
    .moto-card-placeholder { width:72px; height:56px; border-radius:8px; background:var(--purple-bg); display:grid; place-items:center; flex-shrink:0; border:1px solid var(--purple-border); }
    .moto-card .moto-name { font-size:14px; font-weight:700; color:var(--text); }
    .moto-card .moto-brand { font-size:11px; color:var(--muted); margin-top:2px; }
    .moto-card .moto-specs { font-size:11px; color:var(--subtext); margin-top:4px; }

    .status-form-card { background:var(--surface); border:1px solid var(--line); border-radius:12px; overflow:hidden; }
    .status-form-head { padding:14px 18px; border-bottom:1px solid var(--line); background:#f8fafc; display:flex; align-items:center; gap:10px; }
    .status-form-body { padding:18px; }

    .form-label-sm { display:block; font-size:11.5px; font-weight:600; color:var(--subtext); margin-bottom:6px; }
    .form-input-sm { width:100%; background:var(--surface); border:1px solid var(--line); color:var(--text); border-radius:9px; padding:8px 12px; font-size:13px; font-family:inherit; transition:border-color .18s; outline:none; }
    .form-input-sm:focus { border-color:var(--purple-light); box-shadow:0 0 0 3px rgba(139,92,246,.1); }
    .form-select-sm { width:100%; background:var(--surface); border:1px solid var(--line); color:var(--text); border-radius:9px; padding:8px 12px; font-size:13px; font-family:inherit; outline:none; cursor:pointer; }
    .form-select-sm:focus { border-color:var(--purple-light); }
    .form-textarea-sm { width:100%; background:var(--surface); border:1px solid var(--line); color:var(--text); border-radius:9px; padding:8px 12px; font-size:13px; font-family:inherit; resize:vertical; min-height:90px; outline:none; }
    .form-textarea-sm:focus { border-color:var(--purple-light); box-shadow:0 0 0 3px rgba(139,92,246,.1); }

    .ref-badge { display:inline-flex; align-items:center; gap:7px; background:#f3e8ff; border:1px solid #e9d5ff; color:#6d28d9; border-radius:8px; padding:5px 12px; font-size:12px; font-weight:700; font-family:monospace; letter-spacing:.05em; }
    .back-btn { display:inline-flex; align-items:center; gap:7px; font-size:12px; font-weight:600; color:var(--muted); text-decoration:none; margin-bottom:18px; transition:color .15s; }
    .back-btn:hover { color:var(--purple); }
</style>
@endsection

@section('content')

<a href="{{ route('admin.test-rides.index') }}" class="back-btn">
    <i class="fa-solid fa-arrow-left"></i> Back to Bookings
</a>

{{-- Page header --}}
<div class="page-header" style="margin-bottom:20px;">
    <div>
        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
            <h1>Booking Details</h1>
            <span class="ref-badge"><i class="fa-solid fa-hashtag" style="font-size:10px;"></i> {{ $testRide->booking_reference }}</span>
        </div>
        <p>Received {{ $testRide->created_at->format('d M Y') }} at {{ $testRide->created_at->format('H:i') }}</p>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <span class="badge {{ $testRide->status_color }}">{{ $testRide->status_label }}</span>
    </div>
</div>

<div class="detail-grid">

    {{-- Left column --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        {{-- Motorcycle --}}
        <div class="detail-section">
            <div class="detail-section-head">
                <i class="fa-solid fa-motorcycle"></i>
                <span>Motorcycle</span>
            </div>
            <div class="detail-section-body">
                @if($testRide->motorcycle)
                    <div class="moto-card">
                        @if($testRide->motorcycle->image_url)
                            <img src="{{ $testRide->motorcycle->image_url }}" alt="{{ $testRide->motorcycle->model_name }}">
                        @else
                            <div class="moto-card-placeholder">
                                <i class="fa-solid fa-motorcycle" style="color:var(--purple);font-size:18px;"></i>
                            </div>
                        @endif
                        <div>
                            <div class="moto-name">{{ $testRide->motorcycle->model_name }}</div>
                            <div class="moto-brand">{{ $testRide->motorcycle->brand->name ?? '—' }}</div>
                            <div class="moto-specs">
                                @if($testRide->motorcycle->engine_capacity) {{ number_format($testRide->motorcycle->engine_capacity) }} cc · @endif
                                @if($testRide->motorcycle->year) {{ $testRide->motorcycle->year }} @endif
                            </div>
                        </div>
                    </div>
                    <div class="detail-row">
                        <span class="lbl">Price</span>
                        <span class="val">${{ $testRide->motorcycle->price ? number_format($testRide->motorcycle->price) : 'On request' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="lbl">Availability</span>
                        <span class="val">{{ ucfirst($testRide->motorcycle->availability_status ?? '—') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="lbl">View Page</span>
                        <span class="val">
                            <a href="{{ route('motorcycles.show', $testRide->motorcycle->slug) }}" target="_blank" class="tbl-link">
                                Open <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:9px;"></i>
                            </a>
                        </span>
                    </div>
                @else
                    <p style="font-size:12px;color:var(--muted);">No motorcycle linked to this booking.</p>
                @endif
            </div>
        </div>

        {{-- Customer info --}}
        <div class="detail-section">
            <div class="detail-section-head">
                <i class="fa-solid fa-user"></i>
                <span>Customer</span>
            </div>
            <div class="detail-section-body">
                <div class="detail-row">
                    <span class="lbl">Full Name</span>
                    <span class="val">{{ $testRide->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="lbl">Email</span>
                    <span class="val"><a href="mailto:{{ $testRide->email }}" class="tbl-link">{{ $testRide->email }}</a></span>
                </div>
                <div class="detail-row">
                    <span class="lbl">Phone</span>
                    <span class="val"><a href="tel:{{ $testRide->phone }}" class="tbl-link">{{ $testRide->phone }}</a></span>
                </div>
                @if($testRide->user)
                <div class="detail-row">
                    <span class="lbl">Account</span>
                    <span class="val">
                        <a href="{{ route('admin.users.show', $testRide->user) }}" class="tbl-link">
                            {{ $testRide->user->name }}
                        </a>
                    </span>
                </div>
                @endif
            </div>
        </div>

        {{-- Booking preferences --}}
        <div class="detail-section">
            <div class="detail-section-head">
                <i class="fa-regular fa-calendar"></i>
                <span>Booking Preferences</span>
            </div>
            <div class="detail-section-body">
                <div class="detail-row">
                    <span class="lbl">Preferred Date</span>
                    <span class="val">{{ $testRide->preferred_date ? $testRide->preferred_date->format('d F Y') : 'Not specified' }}</span>
                </div>
                <div class="detail-row">
                    <span class="lbl">Preferred Time</span>
                    <span class="val">{{ $testRide->preferred_time ?? 'Not specified' }}</span>
                </div>
                <div class="detail-row">
                    <span class="lbl">Showroom</span>
                    <span class="val">{{ $testRide->showroom?->name ?? $testRide->preferred_showroom ?? 'Not specified' }}</span>
                </div>
                @if($testRide->notes)
                <div class="detail-row" style="flex-direction:column;align-items:flex-start;gap:6px;">
                    <span class="lbl">Customer Notes</span>
                    <span class="val" style="text-align:left;">{{ $testRide->notes }}</span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="lbl">Submitted IP</span>
                    <span class="val" style="font-family:monospace;font-size:11px;">{{ $testRide->ip_address ?? '—' }}</span>
                </div>
            </div>
        </div>

    </div>

    {{-- Right column: status management --}}
    <div style="display:flex;flex-direction:column;gap:16px;">

        <div class="status-form-card">
            <div class="status-form-head">
                <i class="fa-solid fa-sliders" style="color:var(--purple);font-size:13px;"></i>
                <span style="font-size:13px;font-weight:700;color:var(--text);">Manage Booking</span>
            </div>
            <div class="status-form-body">
                <form method="POST" action="{{ route('admin.test-rides.update', $testRide) }}">
                    @csrf @method('PUT')

                    <div style="margin-bottom:14px;">
                        <label class="form-label-sm">Booking Status *</label>
                        <select name="status" class="form-select-sm" required>
                            @foreach(\App\Models\TestRideRequest::$statuses as $val => $label)
                                <option value="{{ $val }}" {{ $testRide->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom:16px;">
                        <label class="form-label-sm">Admin Notes (internal, not visible to customer)</label>
                        <textarea name="admin_notes" class="form-textarea-sm" placeholder="Notes for the team…">{{ old('admin_notes', $testRide->admin_notes) }}</textarea>
                    </div>

                    <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">
                        <i class="fa-solid fa-floppy-disk"></i> Save Changes
                    </button>
                </form>

                @if(auth()->user()->isAdmin())
                <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--line);">
                    <form method="POST" action="{{ route('admin.test-rides.destroy', $testRide) }}"
                          onsubmit="return confirm('Permanently delete booking {{ $testRide->booking_reference }}? This action cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger" style="width:100%;justify-content:center;">
                            <i class="fa-solid fa-trash-can"></i> Delete Booking
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>

        {{-- Booking timeline --}}
        <div class="detail-section">
            <div class="detail-section-head">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>Timeline</span>
            </div>
            <div class="detail-section-body" style="display:flex;flex-direction:column;gap:10px;">
                <div style="display:flex;align-items:flex-start;gap:10px;">
                    <div style="width:28px;height:28px;border-radius:50%;background:#f0fdf4;border:1px solid #bbf7d0;display:grid;place-items:center;flex-shrink:0;">
                        <i class="fa-solid fa-plus" style="color:#16a34a;font-size:10px;"></i>
                    </div>
                    <div>
                        <div style="font-size:12px;font-weight:600;color:var(--text);">Booking Created</div>
                        <div style="font-size:11px;color:var(--muted);">{{ $testRide->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
                @if($testRide->updated_at && $testRide->updated_at != $testRide->created_at)
                <div style="display:flex;align-items:flex-start;gap:10px;">
                    <div style="width:28px;height:28px;border-radius:50%;background:#eff6ff;border:1px solid #bfdbfe;display:grid;place-items:center;flex-shrink:0;">
                        <i class="fa-solid fa-pen" style="color:#2563eb;font-size:10px;"></i>
                    </div>
                    <div>
                        <div style="font-size:12px;font-weight:600;color:var(--text);">Last Updated</div>
                        <div style="font-size:11px;color:var(--muted);">{{ $testRide->updated_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
                @endif
                <div style="display:flex;align-items:flex-start;gap:10px;">
                    @php
                        $dot = match($testRide->status) {
                            'confirmed' => ['bg:#eff6ff;border:1px solid #bfdbfe','#2563eb','fa-circle-check'],
                            'completed' => ['bg:#f0fdf4;border:1px solid #bbf7d0','#16a34a','fa-flag-checkered'],
                            'cancelled' => ['bg:#fff1f2;border:1px solid #fecaca','#dc2626','fa-xmark'],
                            default     => ['bg:#fffbeb;border:1px solid #fde68a','#d97706','fa-clock'],
                        };
                    @endphp
                    <div style="width:28px;height:28px;border-radius:50%;{{ $dot[0] }};display:grid;place-items:center;flex-shrink:0;">
                        <i class="fa-solid {{ $dot[2] }}" style="color:{{ $dot[1] }};font-size:10px;"></i>
                    </div>
                    <div>
                        <div style="font-size:12px;font-weight:600;color:var(--text);">Current Status</div>
                        <div style="font-size:11px;color:var(--muted);">{{ $testRide->status_label }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
