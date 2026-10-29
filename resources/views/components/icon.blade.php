@props(['name' => null, 'class' => ''])
@php
// Lucide icon placeholder — lucide.createIcons() (called in app.js) replaces
// this <i> with the matching inline SVG, copying through the class/attrs.
@endphp
<i data-lucide="{{ $name }}" aria-hidden="true" class="{{ $class }}"></i>
