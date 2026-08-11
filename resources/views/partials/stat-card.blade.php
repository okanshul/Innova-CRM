<div class="col-6 col-md-6 col-xl-3">
    <div class="card h-100 rounded-4 shadow-sm border-0">
        <div
            class="card-body p-3 d-flex align-items-center justify-content-between position-relative overflow-hidden">
            <div class="d-flex align-items-center gap-2 gap-sm-3 w-100 position-relative z-1">
                <!-- Solid Filled Icon Circle -->
                <div class="stat-icon-circle rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0 shadow-sm bg-{{ $stat['color'] }}"
                    style="width: 44px; height: 44px;">
                    <i class="{{ $stat['icon'] }} fs-5"></i>
                </div>
                <div class="d-flex flex-column flex-grow-1 overflow-hidden">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-secondary text-truncate small fw-medium"
                            style="font-size: 0.725rem;">{{ $stat['title'] }}</span>
                        @if (($stat['title'] ?? '') === 'Conversion Rate')
                            <i
                                class="fa-solid fa-sliders text-secondary opacity-50 fs-xs ms-auto d-none d-sm-inline"></i>
                        @endif
                    </div>
                    <h4 class="mb-0.5 fw-bold text-body-emphasis mt-0.5 text-truncate fs-5 fs-sm-4"
                        style="letter-spacing: -0.02em;">{{ $stat['value'] }}</h4>
                    <div class="d-flex align-items-center gap-1 text-truncate small {{ $stat['is_positive'] ? 'text-success' : 'text-danger' }}"
                        style="font-size: 0.675rem;">
                        <i class="fa-solid {{ $stat['is_positive'] ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                        <span class="fw-semibold">{{ $stat['change'] }}</span>
                        <span class="text-secondary opacity-75 d-none d-sm-inline">vs last month</span>
                    </div>
                </div>
            </div>

            <!-- Sparkline Chart -->
            <div class="position-absolute end-0 top-50 translate-middle-y pe-2 pe-sm-3 opacity-75"
                style="pointer-events: none;">
                <svg width="48" height="24" viewBox="0 0 60 24" class="sparkline text-{{ $stat['color'] }}">
                    @if ($stat['is_positive'])
                        <path d="M0,20 L10,14 L20,16 L35,6 L45,10 L60,2" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                    @else
                        <path d="M0,5 L15,10 L25,8 L40,18 L50,15 L60,22" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                    @endif
                </svg>
            </div>
        </div>
    </div>
</div>
