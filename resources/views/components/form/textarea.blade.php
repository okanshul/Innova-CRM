@props([
    'name',
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'icon' => null,
    'id' => null,
    'rows' => 3,
])

@php
    $inputId = $id ?? $name;
@endphp

<div {{ $attributes->merge(['class' => '']) }}>
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

    <textarea 
        id="{{ $inputId }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"

        class="form-control @error($name) is-invalid @enderror"
    >{{ old($name, $value) }}</textarea>

    @error($name)
        <div class="invalid-feedback d-block ps-2">{{ $message }}</div>
    @enderror
</div>
