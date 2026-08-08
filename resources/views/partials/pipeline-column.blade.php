<div class="pipeline-col d-flex flex-column border-top border-3 border-{{ $column['color'] }} pt-2 bg-body-tertiary shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="d-flex align-items-center gap-2">
            <span class="d-inline-block rounded-circle bg-{{ $column['color'] }}" style="width:8px; height:8px;"></span>
            <h6 class="mb-0 fw-bold fs-sm">{{ $stage }}</h6>
        </div>
        <div class="dropdown">
            <button class="btn btn-link text-secondary p-0 text-decoration-none shadow-none" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-ellipsis"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li><a class="dropdown-item" href="#">Edit Stage</a></li>
                <li><a class="dropdown-item text-danger" href="#">Delete Stage</a></li>
            </ul>
        </div>
    </div>

    <div class="d-flex justify-content-between text-secondary mb-2" style="font-size:0.7rem;">
        <span>{{ $column['count'] }} Deals</span>
        <span class="fw-bold text-body-emphasis">{{ $column['value'] }}</span>
    </div>

    <div class="d-flex flex-column gap-2 mb-2 flex-grow-1">
        @foreach ($column['deals'] as $deal)
            <div class="card rounded-3 border-0 shadow-sm">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h6 class="mb-0 fw-bold text-truncate pe-2" style="font-size: 0.8rem;">{{ $deal['company'] }}
                        </h6>
                        <span class="fw-bold" style="font-size: 0.75rem;">{{ $deal['value'] }}</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-1">
                            <div class="avatar bg-{{ $deal['contact_color'] }} text-white"
                                style="width:16px;height:16px;font-size:0.5rem">
                                {{ $deal['initials'] }}
                            </div>
                            <span class="text-secondary text-truncate"
                                style="font-size:0.65rem;max-width:80px;">{{ $deal['contact'] }}</span>
                        </div>
                        <span class="text-secondary" style="font-size:0.65rem">{{ $deal['time'] }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <button
        class="btn btn-link text-secondary text-decoration-none shadow-none text-start p-1 mt-auto w-100 d-flex align-items-center justify-content-center gap-1 hover-primary"
        style="font-size: 0.75rem; border: 1px dashed rgba(0,0,0,0.1); border-radius: 6px;">
        <i class="fa-solid fa-plus"></i> Add Deal
    </button>
</div>
