@props([
    'id' => 'deleteConfirmationModal',
    'count' => null,
    'itemType' => 'Deals',
    'itemName' => null,
    'title' => null,
    'subtextLine1' => null,
    'subtextLine2' => 'This action cannot be undone.',
    'warningText' => null,
    'confirmText' => 'Yes, delete permanently',
    'cancelText' => 'Cancel',
    'formAction' => null,
    'formMethod' => 'POST',
    'deleteMethod' => 'DELETE',
])

@php
    $displayCount = $count ?? 1;
    $singularType = \Illuminate\Support\Str::singular($itemType);
    $pluralType = \Illuminate\Support\Str::plural($itemType);
    
    $displayTitle = $title ?? ($count ? "Delete <span class=\"text-danger\">{$count}</span> {$pluralType}?" : "Delete {$singularType}?");
    
    if (!$subtextLine1) {
        if ($itemName) {
            $subtextLine1 = "You are about to permanently delete \"{$itemName}\".";
        } elseif ($count && $count > 1) {
            $subtextLine1 = "You are about to permanently delete the {$count} selected {$pluralType}.";
        } else {
            $subtextLine1 = "You are about to permanently delete this {$singularType}.";
        }
    }
    
    if (!$warningText) {
        $targetNoun = ($count && $count > 1) ? "these {$pluralType}" : "this {$singularType}";
        $warningText = "Once deleted, {$targetNoun} and related data will be permanently removed from the system.";
    }
@endphp

<div class="modal fade delete-confirm-modal" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
        <div class="modal-content border-0 shadow-lg delete-confirm-card">
            <div class="modal-body p-0 text-center">
                <!-- Decorative Icon Section -->
                <div class="delete-modal-icon-wrapper">
                    <!-- 3 Scattered decorative sparkle/diamond accent shapes -->
                    <svg class="delete-sparkle sparkle-1" width="14" height="14" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 0L9.8 6.2L16 8L9.8 9.8L8 16L6.2 9.8L0 8L6.2 6.2L8 0Z" fill="#F59E0B"/>
                    </svg>
                    <svg class="delete-sparkle sparkle-2" width="12" height="12" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 0L9.8 6.2L16 8L9.8 9.8L8 16L6.2 9.8L0 8L6.2 6.2L8 0Z" fill="#A855F7"/>
                    </svg>
                    <svg class="delete-sparkle sparkle-3" width="12" height="12" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 0L9.8 6.2L16 8L9.8 9.8L8 16L6.2 9.8L0 8L6.2 6.2L8 0Z" fill="#EC4899"/>
                    </svg>

                    <!-- Large circular badge at top-center (~90px diameter, light red/pink background) -->
                    <div class="delete-modal-badge">
                        <svg width="38" height="42" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 6h18"></path>
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                    </div>
                </div>

                <!-- Heading -->
                <h3 class="delete-modal-title fw-bold" id="{{ $id }}Label">
                    {!! $displayTitle !!}
                </h3>

                <!-- Subtext -->
                <div class="delete-modal-subtext">
                    <p class="mb-1">{!! $subtextLine1 !!}</p>
                    <p class="mb-0">{{ $subtextLine2 }}</p>
                </div>

                <!-- Warning Banner -->
                <div class="delete-warning-banner">
                    <div class="delete-warning-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                            <line x1="12" y1="9" x2="12" y2="13"></line>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                    </div>
                    <div class="delete-warning-text">
                        {{ $warningText }}
                    </div>
                </div>

                <!-- Action Buttons -->
                @if ($formAction)
                    <form action="{{ $formAction }}" method="{{ $formMethod }}" id="{{ $id }}Form">
                        @csrf
                        @if (strtoupper($deleteMethod) !== 'POST')
                            @method($deleteMethod)
                        @endif
                        <div class="delete-modal-actions">
                            <button type="button" class="delete-btn-cancel" data-bs-dismiss="modal">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                                <span>{{ $cancelText }}</span>
                            </button>
                            <button type="submit" class="delete-btn-confirm" id="{{ $id }}SubmitBtn">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 6h18"></path>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                </svg>
                                <span>{{ $confirmText }}</span>
                            </button>
                        </div>
                    </form>
                @else
                    <div class="delete-modal-actions">
                        <button type="button" class="delete-btn-cancel" data-bs-dismiss="modal">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                            <span>{{ $cancelText }}</span>
                        </button>
                        <button type="button" class="delete-btn-confirm" id="{{ $id }}ConfirmBtn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18"></path>
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                            </svg>
                            <span>{{ $confirmText }}</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
