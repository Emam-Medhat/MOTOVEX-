@extends('layouts.admin')
@section('title', $sparePart->name)

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.spare-parts.index') }}"
           style="font-size:12px;color:#64748b;display:inline-flex;align-items:center;gap:6px;margin-bottom:6px">
            <i class="fa-solid fa-arrow-left" style="font-size:10px"></i> Back to Spare Parts
        </a>
        <h1>{{ $sparePart->name }}</h1>
        <p style="font-size:12px;color:var(--muted);margin-top:3px">{{ $sparePart->sku ? 'SKU: '.$sparePart->sku : '' }}</p>
    </div>
    <div style="display:flex;gap:8px">
        @can('update', $sparePart)
        <a href="{{ route('admin.spare-parts.edit', $sparePart) }}" class="btn-ghost">
            <i class="fa-solid fa-pen-to-square"></i> Edit
        </a>
        @endcan
        @can('publish', $sparePart)
        <form method="POST" action="{{ route('admin.spare-parts.toggle-publish', $sparePart) }}">
            @csrf @method('PATCH')
            <button class="btn-ghost">
                <i class="fa-solid {{ $sparePart->is_published ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                {{ $sparePart->is_published ? 'Unpublish' : 'Publish' }}
            </button>
        </form>
        @endcan
        @can('delete', $sparePart)
        <form method="POST" action="{{ route('admin.spare-parts.destroy', $sparePart) }}"
              onsubmit="return confirm('Delete permanently?')">
            @csrf @method('DELETE')
            <button class="btn-danger"><i class="fa-solid fa-trash"></i> Delete</button>
        </form>
        @endcan
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:20px;align-items:start">

    {{-- Image panel --}}
    <div class="admin-panel">
        @if($sparePart->image_url)
            <img src="{{ $sparePart->image_url }}" alt="{{ $sparePart->name }}"
                 style="width:100%;aspect-ratio:4/3;object-fit:contain;border-radius:10px;background:#0f172a;padding:16px">
        @else
            <div style="width:100%;aspect-ratio:4/3;border-radius:10px;background:var(--purple-bg);color:var(--purple);display:grid;place-items:center;font-size:40px">
                <i class="fa-solid fa-gears"></i>
            </div>
        @endif

        @if(!empty($sparePart->additional_image_urls))
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:10px">
            @foreach($sparePart->additional_image_urls as $img)
            <img src="{{ $img }}" style="width:64px;height:64px;object-fit:contain;border-radius:8px;background:#0f172a;padding:4px;border:1px solid var(--line)">
            @endforeach
        </div>
        @endif
    </div>

    {{-- Details panel --}}
    <div class="admin-panel">
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px">
            <span class="badge {{ $sparePart->is_published ? 'badge-green' : 'badge-yellow' }}">
                {{ $sparePart->is_published ? 'Published' : 'Draft' }}
            </span>
            <span class="badge badge-blue">{{ $sparePart->availabilityLabel() }}</span>
        </div>

        @if($sparePart->description)
        <p style="font-size:13px;color:var(--subtext);line-height:1.7;margin-bottom:16px">{{ $sparePart->description }}</p>
        @endif

        <dl style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px">
            @foreach([
                'Brand'       => $sparePart->brand->name ?? '—',
                'Category'    => $sparePart->categoryLabel(),
                'Price'       => $sparePart->price ? number_format($sparePart->price,2).' '.$sparePart->currency : '—',
                'Stock'       => $sparePart->stock_quantity,
                'Owner'       => $sparePart->creator->name ?? '—',
                'Created'     => $sparePart->created_at->format('d M Y'),
            ] as $label => $value)
            <div>
                <dt style="font-size:10px;text-transform:uppercase;letter-spacing:1px;color:var(--muted)">{{ $label }}</dt>
                <dd style="margin-top:4px;font-weight:600;font-size:13px">{{ $value }}</dd>
            </div>
            @endforeach
        </dl>

        @if($sparePart->compatibility_notes)
        <div style="margin-top:16px;padding:10px 14px;background:#fffbeb;border:1px solid #fde68a;border-radius:9px;font-size:12px;color:#92400e">
            <i class="fa-solid fa-circle-info" style="margin-right:4px"></i>{{ $sparePart->compatibility_notes }}
        </div>
        @endif

        @if($sparePart->compatibleMotorcycles->isNotEmpty())
        <div style="margin-top:16px">
            <dt style="font-size:10px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin-bottom:8px">Compatible Motorcycles</dt>
            <div style="display:flex;gap:6px;flex-wrap:wrap">
                @foreach($sparePart->compatibleMotorcycles as $moto)
                <span style="font-size:11px;background:var(--purple-bg);color:var(--purple);border:1px solid var(--purple-border);padding:3px 10px;border-radius:20px;font-weight:600">
                    {{ $moto->brand->name ?? '' }} {{ $moto->model_name }} ({{ $moto->year }})
                </span>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
