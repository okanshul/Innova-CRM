@props([
    'type' => 'button',
    'label' => null,
    'icon' => null,
    'href' => null,
    'id' => null,
])

@php
    $baseClasses = 'btn btn-light border rounded-3 px-4 py-2 fw-medium text-secondary d-inline-flex align-items-center gap-2';
@endphp

@if($href)
    <a 
        href="{{ $href }}"
        @if($id) id="{{ $id }}" @endif
        {{ $attributes->merge(['class' => $baseClasses]) }}
    >
        @if($icon)
            <i class="{{ $icon }}"></i>
        @endif
        {{ $label ?? $slot }}
    </a>
@else
    <button 
        type="{{ $type }}"
        @if($id) id="{{ $id }}" @endif
        {{ $attributes->merge(['class' => $baseClasses]) }}
    >
        @if($icon)
            <i class="{{ $icon }}"></i>
        @endif
        {{ $label ?? $slot }}
    </button>
@endif
