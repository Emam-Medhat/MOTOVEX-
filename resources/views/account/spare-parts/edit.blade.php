@extends('layouts.account')
@section('title', 'Edit ' . $sparePart->name)
@section('heading', 'Edit Spare Part')

@section('content')
<div class="ac-card">
    <div class="ac-card-header">
        <div class="ac-card-title">
            <i class="fa-solid fa-pen-to-square"></i> {{ Str::limit($sparePart->name, 50) }}
        </div>
        <a href="{{ route('account.spare-parts.index') }}" class="ac-btn-ghost" style="padding:.5rem .9rem;font-size:.8rem">
            <i class="fa-solid fa-arrow-left" style="font-size:.7rem"></i> Back
        </a>
    </div>

    @if($sparePart->is_published)
    <div style="padding:.85rem 1.5rem;background:#fffbeb;border-bottom:1px solid #fde68a">
        <p style="font-size:.82rem;color:#92400e;font-weight:600">
            <i class="fa-solid fa-triangle-exclamation" style="margin-right:5px"></i>
            Editing will un-publish this part. It will need re-approval from an Admin before going live again.
        </p>
    </div>
    @endif

    <div class="ac-card-body">
        <form method="POST" action="{{ route('account.spare-parts.update', $sparePart) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            @php $part = $sparePart; @endphp
            @include('account.spare-parts._form')
            <div style="display:flex;gap:12px;margin-top:1.5rem;padding-top:1rem;border-top:1px solid var(--ac-line)">
                <button type="submit" class="ac-btn-primary">
                    <i class="fa-solid fa-check"></i> Save Changes
                </button>
                <a href="{{ route('account.spare-parts.index') }}" class="ac-btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>

{{-- Danger zone: delete (only if not published) --}}
@if(!$sparePart->is_published)
<div class="ac-danger-zone" style="margin-top:1.5rem">
    <div class="ac-danger-title"><i class="fa-solid fa-triangle-exclamation"></i> Danger Zone</div>
    <p class="ac-danger-text">Permanently delete this spare part listing. This action cannot be undone.</p>
    <form method="POST" action="{{ route('account.spare-parts.destroy', $sparePart) }}"
          onsubmit="return confirm('Permanently delete this spare part?')" style="margin-top:1rem">
        @csrf @method('DELETE')
        <button type="submit"
                style="padding:.7rem 1.2rem;background:#fef2f2;color:#dc2626;border:1.5px solid #fecaca;border-radius:10px;font-weight:700;font-size:.85rem;cursor:pointer">
            <i class="fa-solid fa-trash-can"></i> Delete This Part
        </button>
    </form>
</div>
@endif

@endsection
