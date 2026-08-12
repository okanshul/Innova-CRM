@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
    'required' => false,
    'icon' => null,
    'placeholder' => null,
    'id' => null,
    'containerClass' => null,
    'wrapperClass' => null,
    'searchable' => null,
])

@php
    $selectId = $id ?? $name;
    $selectedValue = old($name, $value);

    $attributesClass = $attributes->get('class', '');
    $hasColClass = (bool)preg_match('/\b(col-\S+|mb-\S+|mt-\S+|g-\S+)\b/', $attributesClass);

    $wrapperC = $containerClass ?? $wrapperClass;
    if (!$wrapperC && $label && $hasColClass) {
        preg_match_all('/\b(col-\S+|mb-\S+|mt-\S+|g-\S+)\b/', $attributesClass, $matches);
        $wrapperC = implode(' ', $matches[0] ?? []);
        $selectClass = trim(preg_replace('/\b(col-\S+|mb-\S+|mt-\S+|g-\S+)\b/', '', $attributesClass));
    } else {
        $selectClass = $attributesClass;
    }

    $extraAttrs = [
        'class' => 'form-select ' . $selectClass . ($errors->has($name) ? ' is-invalid' : '')
    ];

    if ($searchable) {
        $extraAttrs['data-searchable'] = 'true';
    }

    $selectAttributes = $attributes->except(['class'])->merge($extraAttrs);
@endphp

@if($label || $wrapperC)
    <div class="{{ $wrapperC ?? '' }} position-relative">
        @if($label)
            <label for="{{ $selectId }}" class="form-label fw-medium small text-secondary ps-2">
                @if($icon)
                    <i class="{{ $icon }} text-secondary me-1"></i>
                @endif
                {{ $label }}
                @if($required)
                    <span class="text-danger">*</span>
                @endif
            </label>
        @endif

        <select 
            id="{{ $selectId }}"
            name="{{ $name }}"

            {{ $selectAttributes }}
        >
            @if($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif

            @if($slot->isNotEmpty())
                {{ $slot }}
            @elseif(is_array($options))
                @foreach($options as $optValue => $optLabel)
                    @php
                        $isAssoc = array_keys($options) !== range(0, count($options) - 1);
                        $val = $isAssoc ? $optValue : $optLabel;
                        $text = $optLabel;
                    @endphp
                    <option value="{{ $val }}" {{ (string)$selectedValue === (string)$val ? 'selected' : '' }}>
                        {{ $text }}
                    </option>
                @endforeach
            @endif
        </select>

        @error($name)
            <div class="invalid-feedback d-block ps-2">{{ $message }}</div>
        @enderror
    </div>
@else
    <select 
        id="{{ $selectId }}"
        name="{{ $name }}"

        {{ $selectAttributes }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @if($slot->isNotEmpty())
            {{ $slot }}
        @elseif(is_array($options))
            @foreach($options as $optValue => $optLabel)
                @php
                    $isAssoc = array_keys($options) !== range(0, count($options) - 1);
                    $val = $isAssoc ? $optValue : $optLabel;
                    $text = $optLabel;
                @endphp
                <option value="{{ $val }}" {{ (string)$selectedValue === (string)$val ? 'selected' : '' }}>
                    {{ $text }}
                </option>
            @endforeach
        @endif
    </select>
    @error($name)
        <div class="invalid-feedback d-block ps-2">{{ $message }}</div>
    @enderror
@endif
