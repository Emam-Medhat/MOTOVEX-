@extends('layouts.account')
@section('title', 'My Spare Parts')
@section('heading', 'My Spare Parts')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:1.5rem;flex-wrap:wrap">
    <p style="font-size:.875rem;color:var(--ac-muted)">
        {{ $spareParts->total() }} spare part{{ $spareParts->total() !== 1 ? 's' : '' }} listed.
        Published parts are visible on the public site after Admin approval.
    </p>
    <a href="{{ route('account.spare-parts.create') }}" class="ac-btn-primary" style="padding:.65rem 1.2rem;font-size:.82rem">
        <i class="fa-solid fa-plus"></i> Add New Part
    </a>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('account.spare-parts.index') }}"
      style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:1.5rem">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Search name or SKU…" class="ac-input" style="max-width:220px;padding:.6rem .9rem;font-size:.82rem">
    <select name="category" class="ac-input" style="max-width:160px;padding:.6rem .9rem;font-size:.82rem">
        <option value="">All Categories</option>
        @foreach(\App\Models\SparePart::$categories as $k => $v)
            <option value="{{ $k }}" {{ request('category') === $k ? 'selected' : '' }}>{{ $v }}</option>
        @endforeach
    </select>
    <button type="submit" class="ac-btn-ghost" style="padding:.62rem 1rem;font-size:.82rem">
        <i class="fa-solid fa-search"></i> Search
    </button>
    @if(request()->hasAny(['search','category']))
        <a href="{{ route('account.spare-parts.index') }}" class="ac-btn-ghost" style="padding:.62rem 1rem;font-size:.82rem">Clear</a>
    @endif
</form>

<div class="ac-card">
    @if($spareParts->isEmpty())
        <div class="ac-empty" style="margin:1.5rem">
            <div class="ac-empty-ico"><i class="fa-solid fa-gears"></i></div>
            <h3>No spare parts yet</h3>
            <p>Add your first spare part listing. It will be reviewed by an Admin before going live.</p>
            <a href="{{ route('account.spare-parts.create') }}" class="ac-btn-primary" style="margin-top:1rem;display:inline-flex">
                <i class="fa-solid fa-plus"></i> Add Spare Part
            </a>
        </div>
    @else
        <div class="ac-table-wrap">
            <table class="ac-table">
                <thead>
                    <tr>
                        <th>Part</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th style="text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($spareParts as $part)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                @if($part->image_url)
                                    <img src="{{ $part->image_url }}" alt="{{ $part->name }}"
                                         style="width:40px;height:40px;object-fit:contain;border-radius:8px;background:#0f172a;padding:3px;flex-shrink:0">
                                @else
                                    <div style="width:40px;height:40px;border-radius:8px;background:var(--ac-primary-soft);color:var(--ac-primary-d);display:grid;place-items:center;flex-shrink:0;font-size:14px">
                                        <i class="fa-solid fa-gears"></i>
                                    </div>
                                @endif
                                <div>
                                    <p style="font-weight:700;font-size:.875rem;color:var(--ac-ink)">{{ Str::limit($part->name,45) }}</p>
                                    @if($part->sku)
                                    <p style="font-size:.72rem;font-family:monospace;color:var(--ac-muted)">{{ $part->sku }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td style="font-size:.8rem">{{ $part->categoryLabel() }}</td>
                        <td style="font-size:.875rem;font-weight:700">
                            @if($part->price)
                                {{ number_format($part->price,0) }} {{ $part->currency }}
                            @else
                                <span style="color:var(--ac-muted)">—</span>
                            @endif
                        </td>
                        <td>
                            @if($part->is_published)
                                <span class="ac-badge completed">Published</span>
                            @else
                                <span class="ac-badge pending">Pending Review</span>
                            @endif
                        </td>
                        <td style="text-align:right">
                            <div style="display:flex;align-items:center;justify-content:flex-end;gap:6px">
                                <a href="{{ route('account.spare-parts.edit', $part) }}"
                                   class="ac-btn-ghost" style="padding:.45rem .85rem;font-size:.78rem">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                @if(!$part->is_published)
                                <form method="POST" action="{{ route('account.spare-parts.destroy', $part) }}"
                                      onsubmit="return confirm('Delete this spare part?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            style="padding:.45rem .85rem;font-size:.78rem;background:#fef2f2;color:#dc2626;border:1px solid #fecaca;border-radius:9px;cursor:pointer;font-weight:600;display:inline-flex;align-items:center;gap:5px">
                                        <i class="fa-solid fa-trash-can"></i> Delete
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:1rem 1.5rem">
            {{ $spareParts->links('vendor.pagination.simple-tailwind') }}
        </div>
    @endif
</div>

@endsection
