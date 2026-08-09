@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'icon' => null,
    'id' => null,
    'minlength' => null,
    'containerClass' => null,
    'wrapperClass' => null,
])

@php
    $inputId = $id ?? $name;
    $isPassword = $type === 'password';

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
        'class' => 'form-control ' . ($isPassword ? 'pe-5 ' : '') . $inputClass . ($errors->has($name) ? ' is-invalid' : '')
    ]);
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

        @if($isPassword)
            <div class="position-relative">
                <input 
                    type="password" 
                    id="{{ $inputId }}"
                    name="{{ $name }}"
                    value="{{ old($name, $value) }}"
                    placeholder="{{ $placeholder }}"
                    @if($minlength) minlength="{{ $minlength }}" @endif

                    {{ $inputAttributes }}
                >
                <button type="button" class="btn btn-link text-secondary position-absolute end-0 top-50 translate-middle-y text-decoration-none pe-3 shadow-none toggle-password-btn" style="z-index: 5;" tabindex="-1">
                    <i class="fa-regular fa-eye"></i>
                </button>
            </div>
        @else
            <input 
                type="{{ $type }}" 
                id="{{ $inputId }}"
                name="{{ $name }}"
                value="{{ old($name, $value) }}"
                placeholder="{{ $placeholder }}"
                @if($minlength) minlength="{{ $minlength }}" @endif

                {{ $inputAttributes }}
            >
        @endif

        @error($name)
            <div class="invalid-feedback d-block ps-2">{{ $message }}</div>
        @enderror
    </div>
@else
    @if($isPassword)
        <div class="position-relative">
            <input 
                type="password" 
                id="{{ $inputId }}"
                name="{{ $name }}"
                value="{{ old($name, $value) }}"
                placeholder="{{ $placeholder }}"
                @if($minlength) minlength="{{ $minlength }}" @endif

                {{ $inputAttributes }}
            >
            <button type="button" class="btn btn-link text-secondary position-absolute end-0 top-50 translate-middle-y text-decoration-none pe-3 shadow-none toggle-password-btn" style="z-index: 5;" tabindex="-1">
                <i class="fa-regular fa-eye"></i>
            </button>
        </div>
    @else
        <input 
            type="{{ $type }}" 
            id="{{ $inputId }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            @if($minlength) minlength="{{ $minlength }}" @endif

            {{ $inputAttributes }}
        >
    @endif
    @error($name)
        <div class="invalid-feedback d-block ps-2">{{ $message }}</div>
    @enderror
@endif
