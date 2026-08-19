@props([
    'value',
    'type' => 'status',
])

@php
    $val = strtolower((string)$value);
    
    if ($type === 'status') {
        $isActive = ($val === 'active' || $val === '1' || $val === 'true');
        $badgeClass = $isActive ? 'badge rounded-pill fw-semibold px-3 py-1 badge-status-active' : 'badge rounded-pill fw-semibold px-3 py-1 badge-status-inactive';
        $displayText = ucfirst($value);
    } else {
        // Role / Position badge logic matching current app exact color mapping
        if (str_contains($val, 'admin')) {
            $badgeClass = 'role-badge role-badge-purple rounded-pill fw-semibold px-3 py-1';
        } elseif (str_contains($val, 'manager') || str_contains($val, 'executive')) {
            $badgeClass = 'role-badge role-badge-cyan rounded-pill fw-semibold px-3 py-1';
        } elseif (str_contains($val, 'lead') || str_contains($val, 'writer') || str_contains($val, 'market')) {
            $badgeClass = 'role-badge role-badge-cyan rounded-pill fw-semibold px-3 py-1';
        } elseif (str_contains($val, 'agent') || str_contains($val, 'support')) {
            $badgeClass = 'role-badge role-badge-orange rounded-pill fw-semibold px-3 py-1';
        } elseif (str_contains($val, 'accountant') || str_contains($val, 'finan')) {
            $badgeClass = 'role-badge role-badge-green rounded-pill fw-semibold px-3 py-1';
        } elseif (str_contains($val, 'sys') || str_contains($val, 'it') || str_contains($val, 'developer') || str_contains($val, 'staff')) {
            $badgeClass = 'role-badge role-badge-blue rounded-pill fw-semibold px-3 py-1';
        } else {
            $badgeClass = 'role-badge role-badge-purple rounded-pill fw-semibold px-3 py-1';
        }
        $displayText = ucfirst($value);
    }
@endphp

<span {{ $attributes->merge(['class' => $badgeClass]) }} style="font-size: 0.75rem;">
    {{ $slot->isNotEmpty() ? $slot : $displayText }}
</span>
