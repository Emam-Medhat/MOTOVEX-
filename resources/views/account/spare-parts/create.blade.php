@extends('layouts.account')
@section('title', 'Add Spare Part')
@section('heading', 'Add Spare Part')

@section('content')
<div class="ac-card">
    <div class="ac-card-header">
        <div class="ac-card-title">
            <i class="fa-solid fa-gears"></i> New Spare Part Listing
        </div>
        <a href="{{ route('account.spare-parts.index') }}" class="ac-btn-ghost" style="padding:.5rem .9rem;font-size:.8rem">
            <i class="fa-solid fa-arrow-left" style="font-size:.7rem"></i> Back
        </a>
    </div>
    <div class="ac-card-body">
        <form method="POST" action="{{ route('account.spare-parts.store') }}" enctype="multipart/form-data">
            @csrf
            @php $selected = []; @endphp
            @include('account.spare-parts._form')
            <div style="display:flex;gap:12px;margin-top:1.5rem;padding-top:1rem;border-top:1px solid var(--ac-line)">
                <button type="submit" class="ac-btn-primary">
                    <i class="fa-solid fa-paper-plane"></i> Submit for Review
                </button>
                <a href="{{ route('account.spare-parts.index') }}" class="ac-btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
