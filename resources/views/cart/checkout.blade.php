@extends('layouts.app')

@section('title', __('cart.checkout_title') . ' | MOTOVEX')

@push('head')
@include('cart.checkout-styles')
@endpush

@section('content')
{{-- When visited directly (not via modal), render as a full centred page --}}
<div id="cko-backdrop" role="dialog" aria-modal="true" aria-labelledby="cko-title">
    <div id="cko-modal">
        <button id="cko-close" aria-label="Close" type="button">
            <i class="fa-solid fa-xmark"></i>
        </button>
        @include('cart.checkout-modal')
    </div>
</div>
@endsection

@push('scripts')
@include('cart.checkout-scripts')
@endpush
