@props([
    'items' => []
])

@if(is_array($items) && count($items) > 0)
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0 align-items-center flex-wrap" style="--bs-breadcrumb-divider: ''; font-size: 0.9rem;">
            @foreach($items as $crumb)
                @if(!$loop->last)
                    <li class="breadcrumb-item d-flex align-items-center p-0">
                        <a href="{{ $crumb['url'] ?? '#' }}" class="text-decoration-none d-inline-flex align-items-center fw-medium" style="color: #64748b;">
                            @if($loop->first)
                                <i class="fa-solid fa-house me-2" style="color: #64748b; font-size: 0.9rem;"></i>
                            @endif
                            <span>{{ $crumb['label'] }}</span>
                        </a>
                        <i class="fa-solid fa-chevron-right" style="color: #94a3b8; font-size: 0.7rem; margin-left: 0.6rem;"></i>
                    </li>
                @else
                    <li class="breadcrumb-item active d-flex align-items-center fw-bold p-0 ps-0" aria-current="page" style="color: #6366f1;">
                        @if($loop->first)
                            <i class="fa-solid fa-house me-2" style="color: #64748b; font-size: 0.9rem;"></i>
                        @endif
                        <span>{{ $crumb['label'] }}</span>
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
