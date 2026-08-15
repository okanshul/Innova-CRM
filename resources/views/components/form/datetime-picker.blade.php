@props([
    'name',
    'label' => null,
    'type' => 'date', // 'date', 'datetime', 'time', 'range'
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'icon' => null,
    'id' => null,
    'dateFormat' => null,
    'displayFormat' => null,
    'enableTime' => null,
    'noCalendar' => null,
    'minDate' => null,
    'maxDate' => null,
    'mode' => 'single',
    'containerClass' => null,
    'wrapperClass' => null,
    'disabled' => false,
    'time24hr' => false,
])

@php
    $inputId = $id ?? $name;

    // Auto-detect settings based on type
    $isTimeOnly = $type === 'time';
    $isDateTime = $type === 'datetime' || $type === 'datetime-local';
    $isRange = $type === 'range' || $mode === 'range';

    $shouldEnableTime = $enableTime ?? ($isDateTime || $isTimeOnly);
    $shouldNoCalendar = $noCalendar ?? $isTimeOnly;

    // Default Date Formats
    if (!$dateFormat) {
        if ($isTimeOnly) {
            $dateFormat = $time24hr ? 'H:i' : 'h:i K';
        } elseif ($isDateTime) {
            $dateFormat = $time24hr ? 'd-m-Y H:i' : 'd-m-Y h:i K';
        } else {
            $dateFormat = 'd-m-Y';
        }
    }

    // Default Icons
    if (!$icon) {
        $icon = ($isTimeOnly || $isDateTime) ? 'fa-regular fa-clock' : 'fa-regular fa-calendar';
    }

    // Default Placeholder
    if (!$placeholder) {
        if ($isTimeOnly) {
            $placeholder = 'Select time...';
        } elseif ($isDateTime) {
            $placeholder = 'Select date & time...';
        } elseif ($isRange) {
            $placeholder = 'Select date range...';
        } else {
            $placeholder = 'dd-mm-yyyy';
        }
    }

    // Process CSS classes for grid layout
    $attributesClass = $attributes->get('class', '');
    $hasColClass = (bool)preg_match('/\b(col-\S+|mb-\S+|mt-\S+|g-\S+)\b/', $attributesClass);

    $wrapperC = $containerClass ?? $wrapperClass;
    if (!$wrapperC && $label && $hasColClass) {
        preg_match_all('/\b(col-\S+|mb-\S+|mt-\S+|g-\S+)\b/', $attributesClass, $matches);
        $wrapperC = implode(' ', $matches[0] ?? []);
        $inputClass = trim(preg_replace('/\b(col-\S+|mb-\S+|mt-\S+|g-\S+)\b/', '', $attributesClass));
    } else {
        $inputClass = $attributesClass;
    }

    $inputAttributes = $attributes->except(['class'])->merge([
        'class' => 'form-control flatpickr-input-target ' . $inputClass . ($errors->has($name) ? ' is-invalid' : '')
    ]);

    // Format initial date value cleanly if passed as Carbon/DateTime or standard string
    $formattedValue = old($name, $value);
    if ($formattedValue instanceof \DateTimeInterface) {
        $formattedValue = $formattedValue->format($isDateTime ? ($time24hr ? 'd-m-Y H:i' : 'd-m-Y h:i K') : ($isTimeOnly ? ($time24hr ? 'H:i' : 'h:i K') : 'd-m-Y'));
    } elseif (is_string($formattedValue) && trim($formattedValue) !== '') {
        try {
            $carbonDate = \Carbon\Carbon::parse($formattedValue);
            $formattedValue = $carbonDate->format($isDateTime ? ($time24hr ? 'd-m-Y H:i' : 'd-m-Y h:i K') : ($isTimeOnly ? ($time24hr ? 'H:i' : 'h:i K') : 'd-m-Y'));
        } catch (\Throwable $e) {}
    }
@endphp

@if($label || $wrapperC)
    <div class="{{ $wrapperC ?? '' }}">
        @if($label)
            <label for="{{ $inputId }}" class="form-label fw-medium small text-secondary ps-2">
                @if($icon)
                    <i class="{{ $icon }} text-secondary me-1"></i>
                @endif
                {{ $label }}
                @if($required)
                    <span class="text-danger">*</span>
                @endif
            </label>
        @endif

        <div class="position-relative flatpickr-wrapper-input">
            <input 
                type="text" 
                id="{{ $inputId }}"
                name="{{ $name }}"
                value="{{ $formattedValue }}"
                placeholder="{{ $placeholder }}"
                data-flatpickr="true"
                data-date-format="{{ $dateFormat }}"
                data-time-24hr="{{ $time24hr ? 'true' : 'false' }}"
                @if($displayFormat) data-alt-format="{{ $displayFormat }}" data-alt-input="true" @endif
                @if($shouldEnableTime) data-enable-time="true" @endif
                @if($shouldNoCalendar) data-no-calendar="true" @endif
                @if($isRange) data-mode="range" @else data-mode="{{ $mode }}" @endif
                @if($minDate) data-min-date="{{ $minDate }}" @endif
                @if($maxDate) data-max-date="{{ $maxDate }}" @endif
                @if($disabled) disabled @endif
                @if($required) required @endif
                {{ $inputAttributes }}
            >
            @if($icon)
                <span class="position-absolute end-0 top-50 translate-middle-y text-secondary pe-3 flatpickr-toggle-btn" style="pointer-events: auto;">
                    <i class="{{ $icon }}"></i>
                </span>
            @endif
        </div>

        @error($name)
            <div class="invalid-feedback d-block ps-2">{{ $message }}</div>
        @enderror
    </div>
@else
    <div class="position-relative flatpickr-wrapper-input">
        <input 
            type="text" 
            id="{{ $inputId }}"
            name="{{ $name }}"
            value="{{ $formattedValue }}"
            placeholder="{{ $placeholder }}"
            data-flatpickr="true"
            data-date-format="{{ $dateFormat }}"
            data-time-24hr="{{ $time24hr ? 'true' : 'false' }}"
            @if($displayFormat) data-alt-format="{{ $displayFormat }}" data-alt-input="true" @endif
            @if($shouldEnableTime) data-enable-time="true" @endif
            @if($shouldNoCalendar) data-no-calendar="true" @endif
            @if($isRange) data-mode="range" @else data-mode="{{ $mode }}" @endif
            @if($minDate) data-min-date="{{ $minDate }}" @endif
            @if($maxDate) data-max-date="{{ $maxDate }}" @endif
            @if($disabled) disabled @endif
            @if($required) required @endif
            {{ $inputAttributes }}
        >
        @if($icon)
            <span class="position-absolute end-0 top-50 translate-middle-y text-secondary pe-3 flatpickr-toggle-btn" style="pointer-events: auto;">
                <i class="{{ $icon }}"></i>
            </span>
        @endif
    </div>

    @error($name)
        <div class="invalid-feedback d-block ps-2">{{ $message }}</div>
    @enderror
@endif
