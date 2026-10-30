@extends('layouts.admin')
@section('title', 'Enquiry #' . $enquiry->id)

@section('styles')
<style>
/* ══════════════════════════════════════════
   ENQUIRY SHOW — Compact Single-Screen Layout
══════════════════════════════════════════ */

.back-link {
    display: inline-flex; align-items: center; gap: 7px;
    font-size: 12px; font-weight: 600; color: var(--muted);
    text-decoration: none; margin-bottom: 16px; transition: color .15s;
}
.back-link:hover { color: var(--primary); }

/* ── Top meta bar ── */
.enq-topbar {
    display: flex; align-items: center; justify-content: space-between;
    gap: 12px; flex-wrap: wrap;
    background: #fff; border: 1px solid var(--border);
    border-radius: 14px; padding: 16px 22px;
    margin-bottom: 18px;
}
.enq-topbar-left { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
.enq-subject { font-size: 17px; font-weight: 800; color: var(--dark); margin: 0; }
.enq-meta {
    font-size: 11.5px; color: var(--muted); margin-top: 3px;
    display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
}
.enq-meta-sep { opacity: .4; }

.enq-status {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 15px; border-radius: 999px;
    font-size: 12px; font-weight: 700; flex-shrink: 0;
}
.es-pending   { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
.es-contacted { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.es-completed { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
.es-cancelled { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
.es-dot { width: 7px; height: 7px; border-radius: 50%; background: currentColor; opacity: .8; }

/* ── Stats row (4 quick info chips) ── */
.enq-chips {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px; margin-bottom: 18px;
}
@media(max-width:900px){ .enq-chips{ grid-template-columns: repeat(2,1fr); } }

.enq-chip {
    background: #fff; border: 1px solid var(--border);
    border-radius: 12px; padding: 13px 16px;
    display: flex; align-items: center; gap: 11px;
}
.enq-chip-icon {
    width: 36px; height: 36px; border-radius: 10px;
    display: grid; place-items: center; font-size: 15px; flex-shrink: 0;
}
.chip-blue   { background: #eff6ff; color: #2563eb; }
.chip-purple { background: #f3e8ff; color: #7c3aed; }
.chip-green  { background: #f0fdf4; color: #16a34a; }
.chip-amber  { background: #fffbeb; color: #d97706; }
.enq-chip-lbl { font-size: 10px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; }
.enq-chip-val { font-size: 13px; font-weight: 700; color: var(--dark); margin-top: 2px; }
.enq-chip-val a { color: var(--primary); text-decoration: none; }

/* ── Main grid ── */
.enq-grid {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 18px; align-items: start;
}
@media(max-width:960px){ .enq-grid{ grid-template-columns: 1fr; } }

/* ── Card ── */
.enq-card {
    background: #fff; border: 1px solid var(--border);
    border-radius: 13px; overflow: hidden; margin-bottom: 14px;
}
.enq-card:last-child { margin-bottom: 0; }
.enq-card-head {
    display: flex; align-items: center; gap: 10px;
    padding: 13px 18px; border-bottom: 1px solid var(--border);
    background: #fafbfd;
}
.enq-card-icon {
    width: 30px; height: 30px; border-radius: 8px;
    display: grid; place-items: center; font-size: 13px; flex-shrink: 0;
    background: #eff6ff; color: var(--primary);
}
.enq-card-icon.purple { background: #f3e8ff; color: #7c3aed; }
.enq-card-icon.amber  { background: #fffbeb; color: #d97706; }
.enq-card-icon.green  { background: #f0fdf4; color: #16a34a; }
.enq-card-title { font-size: 12.5px; font-weight: 700; color: var(--dark); margin: 0; }
.enq-card-body  { padding: 18px; }

/* ── Message ── */
.msg-text {
    font-size: 13.5px; line-height: 1.85;
    color: var(--dark); white-space: pre-wrap;
    word-break: break-word; margin: 0;
}

/* ── Moto preview ── */
.moto-prev {
    display: flex; align-items: center; gap: 12px;
    padding: 13px 15px; background: #f8faff;
    border: 1px solid #dbeafe; border-radius: 11px;
}
.moto-prev-img {
    width: 64px; height: 48px; border-radius: 8px;
    overflow: hidden; flex-shrink: 0;
    border: 1px solid #bfdbfe; background: #eff6ff;
    display: grid; place-items: center;
}
.moto-prev-img img { width: 100%; height: 100%; object-fit: cover; }
.moto-prev-name  { font-size: 13px; font-weight: 700; color: var(--dark); }
.moto-prev-brand { font-size: 11.5px; color: var(--muted); margin-top: 1px; }
.moto-prev-link  {
    margin-left: auto; width: 32px; height: 32px; border-radius: 50%;
    border: 1px solid #bfdbfe; background: #fff; color: var(--primary);
    display: grid; place-items: center; text-decoration: none; flex-shrink: 0;
    transition: all .15s;
}
.moto-prev-link:hover { background: var(--primary); color: #fff; border-color: var(--primary); }

/* ── Info rows (sidebar) ── */
.ir { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; padding: 8px 0; border-bottom: 1px solid #f1f5f9; }
.ir:last-child { border-bottom: none; padding-bottom: 0; }
.ir-l { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: var(--muted); flex-shrink: 0; }
.ir-r { font-size: 12.5px; font-weight: 600; color: var(--dark); text-align: right; }
.ir-r a { color: var(--primary); text-decoration: none; }
.ir-r a:hover { text-decoration: underline; }

/* ── Status form ── */
.enq-sel {
    width: 100%; padding: 9px 12px;
    border: 1px solid var(--border); border-radius: 9px;
    font-size: 13px; font-weight: 600; color: var(--dark);
    background: #fff; outline: none; cursor: pointer; margin-bottom: 10px;
    transition: border-color .15s;
}
.enq-sel:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(8,101,223,.1); }
.btn-block { width: 100%; justify-content: center; }

/* ── Timeline ── */
.tl-item { display: flex; gap: 10px; padding-bottom: 14px; position: relative; }
.tl-item:last-child { padding-bottom: 0; }
.tl-item:not(:last-child)::before {
    content: ''; position: absolute; left: 12px; top: 26px; bottom: 0;
    width: 1px; background: linear-gradient(180deg, var(--border), transparent);
}
.tl-dot {
    width: 24px; height: 24px; border-radius: 50%;
    display: grid; place-items: center; font-size: 10px;
    flex-shrink: 0; z-index: 1;
}
.tl-green  { background: #f0fdf4; border: 2px solid #bbf7d0; color: #16a34a; }
.tl-blue   { background: #eff6ff; border: 2px solid #bfdbfe; color: #2563eb; }
.tl-amber  { background: #fffbeb; border: 2px solid #fde68a; color: #d97706; }
.tl-title { font-size: 12px; font-weight: 700; color: var(--dark); }
.tl-time  { font-size: 11px; color: var(--muted); margin-top: 1px; }
</style>
@endsection

@section('content')

<a href="{{ route('admin.enquiries.index') }}" class="back-link">
    <i class="bi bi-arrow-left"></i> Back to Enquiries
</a>

{{-- ── Top meta bar ── --}}
@php
    $statusCls = match($enquiry->status) {
        'pending'   => 'es-pending',
        'contacted' => 'es-contacted',
        'completed' => 'es-completed',
        'cancelled' => 'es-cancelled',
        default     => 'es-pending',
    };
    $typeLabel = \App\Models\Enquiry::$types[$enquiry->enquiry_type]
        ?? ucwords(str_replace('_', ' ', $enquiry->enquiry_type));
@endphp

<div class="enq-topbar">
    <div class="enq-topbar-left">
        <div>
            <h1 class="enq-subject">{{ $enquiry->subject ?: 'Enquiry #' . $enquiry->id }}</h1>
            <div class="enq-meta">
                <span><i class="bi bi-calendar3 me-1"></i>{{ $enquiry->created_at->format('d M Y, H:i') }}</span>
                <span class="enq-meta-sep">·</span>
                <span>#{{ $enquiry->id }}</span>
                <span class="enq-meta-sep">·</span>
                <span class="badge badge-blue" style="font-size:10px;">{{ $typeLabel }}</span>
            </div>
        </div>
    </div>
    <span class="enq-status {{ $statusCls }}">
        <span class="es-dot"></span>
        {{ ucfirst($enquiry->status) }}
    </span>
</div>

{{-- ── Quick info chips ── --}}
<div class="enq-chips">
    <div class="enq-chip">
        <div class="enq-chip-icon chip-blue"><i class="bi bi-person-fill"></i></div>
        <div>
            <div class="enq-chip-lbl">Customer</div>
            <div class="enq-chip-val">{{ $enquiry->name }}</div>
        </div>
    </div>
    <div class="enq-chip">
        <div class="enq-chip-icon chip-purple"><i class="bi bi-envelope-fill"></i></div>
        <div>
            <div class="enq-chip-lbl">Email</div>
            <div class="enq-chip-val"><a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a></div>
        </div>
    </div>
    <div class="enq-chip">
        <div class="enq-chip-icon chip-green"><i class="bi bi-telephone-fill"></i></div>
        <div>
            <div class="enq-chip-lbl">Phone</div>
            <div class="enq-chip-val">
                @if($enquiry->phone)
                    <a href="tel:{{ $enquiry->phone }}">{{ $enquiry->phone }}</a>
                @else
                    <span style="color:var(--muted);font-weight:400;">—</span>
                @endif
            </div>
        </div>
    </div>
    <div class="enq-chip">
        <div class="enq-chip-icon chip-amber"><i class="bi bi-clock-fill"></i></div>
        <div>
            <div class="enq-chip-lbl">Received</div>
            <div class="enq-chip-val">{{ $enquiry->created_at->diffForHumans() }}</div>
        </div>
    </div>
</div>

{{-- ── Flash ── --}}
@if(session('success'))
<div class="flash-success" style="margin-bottom:14px;"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
@endif

{{-- ── Main Grid ── --}}
<div class="enq-grid">

    {{-- ═══ LEFT — Message + Motorcycle ═══ --}}
    <div>

        {{-- Message --}}
        <div class="enq-card">
            <div class="enq-card-head">
                <div class="enq-card-icon"><i class="bi bi-chat-left-text-fill"></i></div>
                <span class="enq-card-title">Customer Message</span>
            </div>
            <div class="enq-card-body">
                @if($enquiry->message)
                    <p class="msg-text">{{ $enquiry->message }}</p>
                @else
                    <p style="color:var(--muted);font-size:13px;font-style:italic;margin:0;">No message provided.</p>
                @endif
            </div>
        </div>

        {{-- Motorcycle --}}
        @if($enquiry->motorcycle)
        <div class="enq-card">
            <div class="enq-card-head">
                <div class="enq-card-icon purple"><i class="bi bi-bicycle"></i></div>
                <span class="enq-card-title">Related Motorcycle</span>
            </div>
            <div class="enq-card-body">
                <div class="moto-prev">
                    <div class="moto-prev-img">
                        @if($enquiry->motorcycle->image_url)
                            <img src="{{ $enquiry->motorcycle->image_url }}" alt="">
                        @else
                            <i class="bi bi-bicycle" style="font-size:18px;color:#93c5fd;"></i>
                        @endif
                    </div>
                    <div>
                        <div class="moto-prev-name">{{ $enquiry->motorcycle->model_name }}</div>
                        <div class="moto-prev-brand">{{ $enquiry->motorcycle->brand?->name }}</div>
                        @if($enquiry->motorcycle->price)
                        <div style="font-size:12px;font-weight:700;color:var(--primary);margin-top:3px;">
                            {{ $enquiry->motorcycle->currency ?? 'USD' }} {{ number_format($enquiry->motorcycle->price) }}
                        </div>
                        @endif
                    </div>
                    <a href="{{ route('admin.motorcycles.show', $enquiry->motorcycle) }}" class="moto-prev-link">
                        <i class="bi bi-arrow-up-right-square" style="font-size:13px;"></i>
                    </a>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- ═══ RIGHT — Actions ═══ --}}
    <div>

        {{-- Manage --}}
        <div class="enq-card">
            <div class="enq-card-head">
                <div class="enq-card-icon amber"><i class="bi bi-sliders"></i></div>
                <span class="enq-card-title">Manage Enquiry</span>
            </div>
            <div class="enq-card-body">
                <form method="POST" action="{{ route('admin.enquiries.update', $enquiry) }}">
                    @csrf @method('PUT')
                    <label style="display:block;font-size:10.5px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:7px;">Status</label>
                    <select name="status" class="enq-sel">
                        @foreach(\App\Models\Enquiry::$statuses as $val => $lbl)
                            <option value="{{ $val }}" {{ $enquiry->status === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-primary btn-block">
                        <i class="bi bi-check2-circle"></i> Save Status
                    </button>
                </form>

                @can('delete', $enquiry)
                <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border);">
                    <form method="POST" action="{{ route('admin.enquiries.destroy', $enquiry) }}"
                          onsubmit="return confirm('Delete this enquiry permanently?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger btn-block">
                            <i class="bi bi-trash3"></i> Delete Enquiry
                        </button>
                    </form>
                </div>
                @endcan
            </div>
        </div>

        {{-- Extra info (account / preferred time) --}}
        @if($enquiry->user || $enquiry->preferred_contact_time)
        <div class="enq-card">
            <div class="enq-card-head">
                <div class="enq-card-icon green"><i class="bi bi-info-circle-fill"></i></div>
                <span class="enq-card-title">Additional Details</span>
            </div>
            <div class="enq-card-body" style="padding-top:10px;padding-bottom:10px;">
                @if($enquiry->user)
                <div class="ir">
                    <span class="ir-l">Account</span>
                    <span class="ir-r"><a href="{{ route('admin.users.show', $enquiry->user) }}">{{ $enquiry->user->name }}</a></span>
                </div>
                @endif
                @if($enquiry->preferred_contact_time)
                <div class="ir">
                    <span class="ir-l">Preferred Time</span>
                    <span class="ir-r">{{ $enquiry->preferred_contact_time }}</span>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Timeline --}}
        <div class="enq-card">
            <div class="enq-card-head">
                <div class="enq-card-icon"><i class="bi bi-clock-history"></i></div>
                <span class="enq-card-title">Timeline</span>
            </div>
            <div class="enq-card-body" style="padding-top:14px;padding-bottom:12px;">
                <div class="tl-item">
                    <div class="tl-dot tl-green"><i class="bi bi-envelope-plus"></i></div>
                    <div>
                        <div class="tl-title">Submitted</div>
                        <div class="tl-time">{{ $enquiry->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
                @if($enquiry->updated_at && !$enquiry->updated_at->equalTo($enquiry->created_at))
                <div class="tl-item">
                    <div class="tl-dot tl-blue"><i class="bi bi-arrow-repeat"></i></div>
                    <div>
                        <div class="tl-title">Status Updated</div>
                        <div class="tl-time">{{ $enquiry->updated_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
                @endif
                <div class="tl-item">
                    @php
                        $dc = match($enquiry->status) { 'completed'=>'tl-green','contacted'=>'tl-blue',default=>'tl-amber' };
                        $di = match($enquiry->status) { 'completed'=>'bi-flag-fill','contacted'=>'bi-telephone-fill','cancelled'=>'bi-x-circle',default=>'bi-hourglass-split' };
                    @endphp
                    <div class="tl-dot {{ $dc }}"><i class="bi {{ $di }}"></i></div>
                    <div>
                        <div class="tl-title">{{ ucfirst($enquiry->status) }}</div>
                        <div class="tl-time">{{ $enquiry->updated_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
