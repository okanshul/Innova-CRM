@props([
    'icon' => null,
    'title',
    'subtitle' => null,
    'iconBg' => '#f3e8ff',
    'iconColor' => '#7e22ce',
    'cardHeader' => true,
    'borderBottom' => true,
])

@if ($cardHeader)
    <div
        {{ $attributes->merge(['class' => 'card-header bg-body p-3 d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3 ' . ($borderBottom ? 'border-bottom' : 'border-0')]) }}>
        <div class="d-flex align-items-center gap-3">
            @if ($icon)
                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                    style="background: {{ $iconBg }}; color: {{ $iconColor }}; width: 44px; height: 44px;">
                    <i class="{{ $icon }} fs-5"></i>
                </div>
            @endif
            <div>
                <h4 class="fw-bold mb-0 text-body-emphasis" style="font-size: 1.25rem">{{ $title }}</h4>
                @if ($subtitle)
                    <p class="text-secondary small mb-0 text-sm-nowrap">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
        @if (isset($actions) || $slot->isNotEmpty())
            <div class="page-header-actions d-flex align-items-center gap-2 justify-content-lg-end">
                {{ $actions ?? $slot }}
            </div>
        @endif
    </div>
@else
    <div
        {{ $attributes->merge(['class' => 'd-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3 mb-4']) }}>
        <div class="d-flex align-items-center gap-3">
            @if ($icon)
                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                    style="background: {{ $iconBg }}; color: {{ $iconColor }}; width: 44px; height: 44px;">
                    <i class="{{ $icon }} fs-5"></i>
                </div>
            @endif
            <div>
                <h2 class="h3 fw-bold mb-0 text-body-emphasis" style="letter-spacing: -0.02em;">{{ $title }}
                </h2>
                @if ($subtitle)
                    <p class="text-secondary small mb-0 text-sm-nowrap">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
        @if (isset($actions) || $slot->isNotEmpty())
            <div class="page-header-actions d-flex align-items-center gap-2 justify-content-lg-end">
                {{ $actions ?? $slot }}
            </div>
        @endif
    </div>
@endif
