@props([
    'type' => 'submit',
    'label' => null,
    'icon' => null,
    'id' => null,
    'loading' => false,
    'href' => null,
])

@php
    $baseClasses = 'btn btn-purple-primary rounded-3 px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2';
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
        @if($loading) disabled @endif
        {{ $attributes->merge(['class' => $baseClasses]) }}
    >
        @if($loading)
            <i class="fa-solid fa-spinner fa-spin me-1"></i>
        @elseif($icon)
            <i class="{{ $icon }}"></i>
        @endif
        {{ $label ?? $slot }}
    </button>
@endif
