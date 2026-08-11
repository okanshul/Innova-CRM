@props([
    'id',
    'title',
    'size' => null,
    'icon' => null,
    'iconBg' => '#f3e8ff',
    'iconColor' => '#7e22ce',
    'subtitle' => null,
    'formId' => null,
    'formAction' => null,
    'formMethod' => 'POST',
    'bodyId' => null,
])

@php
    $dialogSizeClass = $size ? "modal-{$size}" : '';
    $labelId = "{$id}Label";
    $hasForm = !empty($formId) || !empty($formAction);
@endphp

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $labelId }}" aria-hidden="true">
    <div class="modal-dialog {{ $dialogSizeClass }} modal-dialog-centered modal-dialog-scrollable">
        <{{ $hasForm ? 'form' : 'div' }} 
            @if($formId) id="{{ $formId }}" @endif
            @if($formAction) action="{{ $formAction }}" method="{{ $formMethod }}" @endif
            class="modal-content rounded-4 border-0 shadow"
        >
            <div class="modal-header border-bottom p-3 bg-body">
                <div class="d-flex align-items-center gap-3">
                    @if($icon)
                        <div class="rounded-3 d-flex align-items-center justify-content-center"
                             style="background: {{ $iconBg }}; color: {{ $iconColor }}; width: 44px; height: 44px;">
                            <i class="{{ $icon }} fs-5"></i>
                        </div>
                    @endif
                    <div>
                        <h6 class="modal-title fw-bold text-body-emphasis mb-0" id="{{ $labelId }}">{{ $title }}</h6>
                        @if($subtitle || isset($headerSubtitle))
                            <div class="text-secondary small d-flex align-items-center gap-2 mt-0.5">
                                {{ $headerSubtitle ?? $subtitle }}
                            </div>
                        @endif
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-3 bg-body" @if($bodyId) id="{{ $bodyId }}" @endif>
                {{ $slot }}
            </div>

            @if(isset($footer) && $footer->isNotEmpty())
                <div class="modal-footer border-top p-3 bg-body">
                    {{ $footer }}
                </div>
            @endif
        </{{ $hasForm ? 'form' : 'div' }}>
    </div>
</div>
