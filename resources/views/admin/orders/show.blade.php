@extends('layouts.admin')
@section('title', 'Order ' . ($enquiry->order_reference ?? '#' . $enquiry->id))

@section('styles')
<style>
.od-grid { display:grid; grid-template-columns:1fr 320px; gap:20px; align-items:start; }
@media(max-width:900px){ .od-grid { grid-template-columns:1fr; } }
.od-section { background:var(--surface); border:1px solid var(--line); border-radius:13px; overflow:hidden; margin-bottom:16px; }
.od-section-head { padding:13px 18px; border-bottom:1px solid var(--line); background:#f8fafc; display:flex; align-items:center; gap:9px; }
.od-section-head i { color:var(--purple); font-size:13px; }
.od-section-head span { font-size:13px; font-weight:700; color:var(--text); }
.od-body { padding:18px; }
.od-row { display:flex; align-items:flex-start; justify-content:space-between; gap:12px; padding:8px 0; border-bottom:1px solid #f1f5f9; }
.od-row:last-child { border-bottom:0; padding-bottom:0; }
.od-lbl { font-size:11px; font-weight:600; color:var(--muted); min-width:120px; flex-shrink:0; }
.od-val { font-size:12.5px; color:var(--text); font-weight:500; text-align:right; word-break:break-word; }
.od-ref { display:inline-flex; align-items:center; gap:6px; background:#f3e8ff; border:1px solid #e9d5ff; color:#6d28d9; border-radius:8px; padding:5px 12px; font-size:13px; font-weight:700; font-family:ui-monospace,monospace; }
.back-btn { display:inline-flex; align-items:center; gap:6px; font-size:12px; font-weight:600; color:var(--muted); text-decoration:none; margin-bottom:18px; transition:color .15s; }
.back-btn:hover { color:var(--purple); }
.pay-pill { display:inline-flex; align-items:center; gap:4px; padding:3px 9px; border-radius:999px; font-size:10px; font-weight:700; }
.pay-pending  { color:#92400e; background:#fffbeb; border:1px solid #fde68a; }
.pay-verified { color:#15803d; background:#f0fdf4; border:1px solid #bbf7d0; }
.pay-failed   { color:#991b1b; background:#fff1f2; border:1px solid #fecaca; }
.pay-refunded { color:#64748b; background:#f8fafc; border:1px solid #e2e8f0; }
.od-moto { display:flex; align-items:center; gap:12px; padding:12px; background:#f8fafc; border-radius:10px; border:1px solid var(--line); margin-bottom:12px; }
.od-moto img { width:70px; height:54px; border-radius:8px; object-fit:cover; flex-shrink:0; }
.od-moto-ph { width:70px; height:54px; border-radius:8px; background:var(--purple-bg); display:grid; place-items:center; flex-shrink:0; border:1px solid var(--purple-border); }
.form-label-sm { display:block; font-size:11.5px; font-weight:600; color:var(--subtext); margin-bottom:5px; }
.form-input-sm { width:100%; background:var(--surface); border:1px solid var(--line); color:var(--text); border-radius:9px; padding:8px 12px; font-size:13px; font-family:inherit; outline:none; transition:border-color .18s; }
.form-input-sm:focus { border-color:var(--purple-light); box-shadow:0 0 0 3px rgba(139,92,246,.1); }
.form-select-sm { width:100%; background:var(--surface); border:1px solid var(--line); color:var(--text); border-radius:9px; padding:8px 12px; font-size:13px; font-family:inherit; outline:none; cursor:pointer; }
.form-select-sm:focus { border-color:var(--purple-light); }
.form-textarea-sm { width:100%; background:var(--surface); border:1px solid var(--line); color:var(--text); border-radius:9px; padding:8px 12px; font-size:13px; font-family:inherit; resize:vertical; min-height:80px; outline:none; }
.form-textarea-sm:focus { border-color:var(--purple-light); box-shadow:0 0 0 3px rgba(139,92,246,.1); }
</style>
@endsection

@section('content')

<a href="{{ route('admin.orders.index') }}" class="back-btn">
    <i class="fa-solid fa-arrow-left"></i> Back to Orders
</a>

<div class="page-header" style="margin-bottom:20px;">
    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
        <h1>Order Details</h1>
        <span class="od-ref"><i class="fa-solid fa-hashtag" style="font-size:10px;"></i> {{ $enquiry->order_reference ?? '#'.$enquiry->id }}</span>
        <span class="badge {{ match($enquiry->status){ 'pending'=>'badge-yellow','contacted'=>'badge-blue','completed'=>'badge-green','cancelled'=>'badge-red',default=>'badge-muted' } }}">
            {{ \App\Models\Enquiry::$statuses[$enquiry->status] ?? ucfirst($enquiry->status) }}
        </span>
        <span class="pay-pill pay-{{ $enquiry->payment_status ?? 'pending' }}">
            <i class="fa-solid fa-credit-card" style="font-size:9px;"></i>
            {{ \App\Models\Enquiry::$paymentStatuses[$enquiry->payment_status] ?? 'Pending' }}
        </span>
    </div>
    <p>Received {{ $enquiry->created_at->format('d M Y') }} at {{ $enquiry->created_at->format('H:i') }}</p>
</div>

<div class="od-grid">

    {{-- Left column --}}
    <div>

        {{-- Motorcycle --}}
        <div class="od-section">
            <div class="od-section-head"><i class="fa-solid fa-motorcycle"></i><span>Motorcycle</span></div>
            <div class="od-body">
                @if($enquiry->motorcycle)
                    <div class="od-moto">
                        @if($enquiry->motorcycle->image_url)
                            <img src="{{ $enquiry->motorcycle->image_url }}" alt="">
                        @else
                            <div class="od-moto-ph"><i class="fa-solid fa-motorcycle" style="color:var(--purple);font-size:18px;"></i></div>
                        @endif
                        <div>
                            <div style="font-size:14px;font-weight:700;color:var(--text);">{{ $enquiry->motorcycle_snapshot ?? $enquiry->motorcycle->model_name }}</div>
                            <div style="font-size:11px;color:var(--muted);margin-top:2px;">{{ $enquiry->motorcycle->brand?->name }}</div>
                            @if($enquiry->motorcycle->engine_capacity)
                                <div style="font-size:11px;color:var(--muted);">{{ number_format($enquiry->motorcycle->engine_capacity) }} cc · {{ $enquiry->motorcycle->year }}</div>
                            @endif
                        </div>
                    </div>
                @else
                    <div style="font-size:13px;font-weight:600;color:var(--text);">{{ $enquiry->motorcycle_snapshot ?? 'Motorcycle not linked' }}</div>
                @endif
                <div class="od-row"><span class="od-lbl">Unit Price</span><span class="od-val">{{ $enquiry->currency ?? 'USD' }} {{ number_format($enquiry->unit_price ?? 0) }}</span></div>
                <div class="od-row"><span class="od-lbl">Quantity</span><span class="od-val">{{ $enquiry->quantity ?? 1 }}</span></div>
                <div class="od-row"><span class="od-lbl">Delivery Fee</span><span class="od-val">{{ $enquiry->currency ?? 'USD' }} {{ number_format($enquiry->delivery_fee ?? 0) }}</span></div>
                <div class="od-row">
                    <span class="od-lbl">Total</span>
                    <span class="od-val" style="font-size:16px;font-weight:900;color:var(--purple);">{{ $enquiry->currency ?? 'USD' }} {{ number_format($enquiry->total_amount ?? 0) }}</span>
                </div>
            </div>
        </div>

        {{-- Customer --}}
        <div class="od-section">
            <div class="od-section-head"><i class="fa-solid fa-user"></i><span>Customer</span></div>
            <div class="od-body">
                <div class="od-row"><span class="od-lbl">Name</span><span class="od-val">{{ $enquiry->name }}</span></div>
                <div class="od-row"><span class="od-lbl">Email</span><span class="od-val"><a href="mailto:{{ $enquiry->email }}" class="tbl-link">{{ $enquiry->email }}</a></span></div>
                <div class="od-row"><span class="od-lbl">Phone</span><span class="od-val"><a href="tel:{{ $enquiry->phone }}" class="tbl-link">{{ $enquiry->phone }}</a></span></div>
                <div class="od-row"><span class="od-lbl">City</span><span class="od-val">{{ $enquiry->city ?? '—' }}</span></div>
                <div class="od-row"><span class="od-lbl">Governorate</span><span class="od-val">{{ $enquiry->governorate ?? '—' }}</span></div>
                @if($enquiry->user)
                    <div class="od-row"><span class="od-lbl">Account</span><span class="od-val"><a href="{{ route('admin.users.show', $enquiry->user) }}" class="tbl-link">{{ $enquiry->user->name }}</a></span></div>
                @endif
            </div>
        </div>

        {{-- Delivery --}}
        <div class="od-section">
            <div class="od-section-head"><i class="fa-solid fa-truck-fast"></i><span>Delivery</span></div>
            <div class="od-body">
                <div class="od-row"><span class="od-lbl">Method</span><span class="od-val">{{ ucwords(str_replace('_',' ',$enquiry->delivery_method ?? 'Not specified')) }}</span></div>
                <div class="od-row"><span class="od-lbl">Address</span><span class="od-val">{{ $enquiry->delivery_address ?? '—' }}</span></div>
                <div class="od-row"><span class="od-lbl">Payment Method</span><span class="od-val">{{ ucwords(str_replace('_',' ',$enquiry->payment_method ?? 'Not specified')) }}</span></div>
            </div>
        </div>

        @if($enquiry->message)
        <div class="od-section">
            <div class="od-section-head"><i class="fa-solid fa-comment"></i><span>Customer Notes</span></div>
            <div class="od-body">
                <p style="font-size:13px;color:var(--subtext);line-height:1.7;">{{ $enquiry->message }}</p>
            </div>
        </div>
        @endif

    </div>

    {{-- Right column --}}
    <div>

        {{-- Update Status --}}
        <div class="od-section" style="margin-bottom:14px;">
            <div class="od-section-head"><i class="fa-solid fa-sliders"></i><span>Update Order Status</span></div>
            <div class="od-body">
                <form method="POST" action="{{ route('admin.orders.update-status', $enquiry) }}">
                    @csrf @method('PATCH')
                    <div style="margin-bottom:12px;">
                        <label class="form-label-sm">Order Status</label>
                        <select name="status" class="form-select-sm" required>
                            @foreach(\App\Models\Enquiry::$statuses as $v => $l)
                                <option value="{{ $v }}" {{ $enquiry->status===$v?'selected':'' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">
                        <i class="fa-solid fa-floppy-disk"></i> Save Status
                    </button>
                </form>
            </div>
        </div>

        {{-- Verify Payment (admin only) --}}
        @if(auth()->user()->isAdmin())
        <div class="od-section" style="margin-bottom:14px;">
            <div class="od-section-head"><i class="fa-solid fa-shield-halved"></i><span>Payment Verification</span></div>
            <div class="od-body">
                @if($enquiry->payment_verified_at)
                    <div style="display:flex;align-items:center;gap:8px;padding:9px 12px;background:#f0fdf4;border-radius:9px;border:1px solid #bbf7d0;margin-bottom:12px;">
                        <i class="fa-solid fa-circle-check" style="color:#16a34a;font-size:13px;"></i>
                        <div>
                            <div style="font-size:12px;font-weight:700;color:#15803d;">Verified by {{ $enquiry->paymentVerifiedBy?->name ?? 'Admin' }}</div>
                            <div style="font-size:10.5px;color:#166534;">{{ $enquiry->payment_verified_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>
                @endif
                <form method="POST" action="{{ route('admin.orders.verify-payment', $enquiry) }}">
                    @csrf @method('PATCH')
                    <div style="margin-bottom:10px;">
                        <label class="form-label-sm">Payment Status</label>
                        <select name="payment_status" class="form-select-sm" required>
                            @foreach(\App\Models\Enquiry::$paymentStatuses as $v => $l)
                                <option value="{{ $v }}" {{ ($enquiry->payment_status??'pending')===$v?'selected':'' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="margin-bottom:10px;">
                        <label class="form-label-sm">Payment Reference</label>
                        <input type="text" name="payment_reference" value="{{ old('payment_reference', $enquiry->payment_reference) }}" class="form-input-sm" placeholder="Bank ref / receipt no.">
                    </div>
                    <div style="margin-bottom:12px;">
                        <label class="form-label-sm">Admin Notes (internal)</label>
                        <textarea name="admin_notes" class="form-textarea-sm">{{ old('admin_notes', $enquiry->admin_notes) }}</textarea>
                    </div>
                    <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">
                        <i class="fa-solid fa-shield-halved"></i> Update Payment
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Timeline --}}
        <div class="od-section">
            <div class="od-section-head"><i class="fa-solid fa-clock-rotate-left"></i><span>Timeline</span></div>
            <div class="od-body" style="display:flex;flex-direction:column;gap:10px;">
                <div style="display:flex;align-items:flex-start;gap:10px;">
                    <div style="width:26px;height:26px;border-radius:50%;background:#f0fdf4;border:1px solid #bbf7d0;display:grid;place-items:center;flex-shrink:0;">
                        <i class="fa-solid fa-plus" style="color:#16a34a;font-size:9px;"></i>
                    </div>
                    <div>
                        <div style="font-size:12px;font-weight:600;color:var(--text);">Order Submitted</div>
                        <div style="font-size:11px;color:var(--muted);">{{ $enquiry->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
                @if($enquiry->payment_verified_at)
                <div style="display:flex;align-items:flex-start;gap:10px;">
                    <div style="width:26px;height:26px;border-radius:50%;background:#f0fdf4;border:1px solid #bbf7d0;display:grid;place-items:center;flex-shrink:0;">
                        <i class="fa-solid fa-shield-halved" style="color:#16a34a;font-size:9px;"></i>
                    </div>
                    <div>
                        <div style="font-size:12px;font-weight:600;color:var(--text);">Payment Verified</div>
                        <div style="font-size:11px;color:var(--muted);">{{ $enquiry->payment_verified_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
                @endif
                @if($enquiry->updated_at && $enquiry->updated_at != $enquiry->created_at)
                <div style="display:flex;align-items:flex-start;gap:10px;">
                    <div style="width:26px;height:26px;border-radius:50%;background:#eff6ff;border:1px solid #bfdbfe;display:grid;place-items:center;flex-shrink:0;">
                        <i class="fa-solid fa-pen" style="color:#2563eb;font-size:9px;"></i>
                    </div>
                    <div>
                        <div style="font-size:12px;font-weight:600;color:var(--text);">Last Updated</div>
                        <div style="font-size:11px;color:var(--muted);">{{ $enquiry->updated_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Delete (admin only) --}}
        @if(auth()->user()->isAdmin())
        <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--line);">
            <form method="POST" action="{{ route('admin.orders.destroy', $enquiry) }}"
                  onsubmit="return confirm('Permanently delete order {{ $enquiry->order_reference }}?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger" style="width:100%;justify-content:center;">
                    <i class="fa-solid fa-trash-can"></i> Delete Order
                </button>
            </form>
        </div>
        @endif

    </div>
</div>
@endsection
