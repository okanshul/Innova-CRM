@props([
    'value',
    'type' => 'status',
])

@php
    $val = strtolower((string)$value);
    
    if ($type === 'status') {
        $isActive = ($val === 'active' || $val === '1' || $val === 'true');
        $badgeClass = $isActive ? 'status-badge status-badge-active' : 'status-badge status-badge-inactive';
        $displayText = ucfirst($value);
    } else {
        // Role / Position badge logic matching current app exact color mapping
        if (str_contains($val, 'manager') || str_contains($val, 'executive') || str_contains($val, 'admin')) {
            $badgeClass = 'role-badge role-badge-purple';
        } elseif (str_contains($val, 'lead') || str_contains($val, 'writer') || str_contains($val, 'market')) {
            $badgeClass = 'role-badge role-badge-cyan';
        } elseif (str_contains($val, 'agent') || str_contains($val, 'support')) {
            $badgeClass = 'role-badge role-badge-orange';
        } elseif (str_contains($val, 'accountant') || str_contains($val, 'finan')) {
            $badgeClass = 'role-badge role-badge-green';
        } elseif (str_contains($val, 'sys') || str_contains($val, 'it') || str_contains($val, 'developer')) {
            $badgeClass = 'role-badge role-badge-blue';
        } else {
            $badgeClass = 'role-badge role-badge-purple';
        }
        $displayText = ucfirst($value);
    }
@endphp

<span {{ $attributes->merge(['class' => $badgeClass]) }}>
    {{ $slot->isNotEmpty() ? $slot : $displayText }}
</span>
