@props([
    'items' => []
])

@if(is_array($items) && count($items) > 0)
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0 align-items-center" style="font-size: 0.825rem;">
            @foreach($items as $crumb)
                @if(!$loop->last)
                    <li class="breadcrumb-item">
                        <a href="{{ $crumb['url'] ?? '#' }}" class="text-secondary text-decoration-none d-inline-flex align-items-center gap-1 hover-primary">
                            @if($loop->first)
                                <i class="fa-solid fa-house text-secondary pe-1" style="font-size: 0.75rem;"></i>
                            @endif
                            <span>{{ $crumb['label'] }}</span>
                        </a>
                    </li>
                @else
                    <li class="breadcrumb-item active text-body-emphasis fw-semibold" aria-current="page">{{ $crumb['label'] }}</li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
