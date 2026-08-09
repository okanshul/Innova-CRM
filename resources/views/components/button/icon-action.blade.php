@props([
    'icon',
    'color' => 'primary',
    'tooltip' => null,
    'onclick' => null,
    'href' => null,
    'id' => null,
])

@php
    $colorClassMap = [
        'view' => 'action-btn-view',
        'primary' => 'action-btn-view',
        'edit' => 'action-btn-edit',
        'info' => 'action-btn-edit',
        'delete' => 'action-btn-delete',
        'danger' => 'action-btn-delete',
        'perm' => 'action-btn-perm',
        'purple' => 'action-btn-perm',
    ];
    $colorClass = $colorClassMap[$color] ?? 'action-btn-view';
    $btnClasses = "action-btn {$colorClass}";
@endphp

@if($href)
    <a 
        href="{{ $href }}"
        @if($id) id="{{ $id }}" @endif
        @if($tooltip) title="{{ $tooltip }}" @endif
        {{ $attributes->merge(['class' => $btnClasses]) }}
    >
        <i class="{{ $icon }}"></i>
    </a>
@else
    <button 
        type="button"
        @if($id) id="{{ $id }}" @endif
        @if($onclick) onclick="{{ $onclick }}" @endif
        @if($tooltip) title="{{ $tooltip }}" @endif
        {{ $attributes->merge(['class' => $btnClasses]) }}
    >
        <i class="{{ $icon }}"></i>
    </button>
@endif
