@props([
    'href' => null,
    'variant' => 'primary',      // primary | ghost
    'magnetic' => false,
    'type' => null,
    'as' => null,
])
@php
    $tag = $as ?: ($href ? 'a' : 'button');
    $classes = 'btn btn-' . $variant;
@endphp
<{{ $tag }}
    @if($href) href="{{ $href }}" @endif
    @if(! $href && $type) type="{{ $type }}" @endif
    @if($magnetic) data-magnetic @endif
    class="{{ $classes }}"
>
    {{ $slot }}
</{{ $tag }}>
