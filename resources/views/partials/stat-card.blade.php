<div class="col-xl-3 col-md-6">
    <div class="card h-100 rounded-4 shadow-sm border-0">
        <div class="card-body p-3 d-flex align-items-center justify-content-between position-relative">
            <div class="d-flex align-items-center gap-3 w-100 position-relative z-1">
                <!-- Solid Filled Icon Circle -->
                <div class="stat-icon-circle rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0 shadow-sm bg-{{ $stat['color'] }}"
                    style="width: 60px; height: 60px;">
                    <i class="{{ $stat['icon'] }} fs-4"></i>
                </div>
                <div class="d-flex flex-column flex-grow-1">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="text-secondary small fw-medium">{{ $stat['title'] }}</span>
                        @if(($stat['title'] ?? '') === 'Conversion Rate')
                            <i class="fa-solid fa-sliders text-secondary opacity-50 fs-xs ms-auto"></i>
                        @endif
                    </div>
                    <h4 class="mb-1 fw-bold text-body-emphasis mt-0.5" style="letter-spacing: -0.02em;">{{ $stat['value'] }}</h4>
                    <div class="d-flex align-items-center gap-1 small {{ $stat['is_positive'] ? 'text-success' : 'text-danger' }}" style="font-size: 0.725rem;">
                        <i class="fa-solid {{ $stat['is_positive'] ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                        <span class="fw-semibold">{{ $stat['change'] }}</span>
                        <span class="text-secondary opacity-75">vs last month</span>
                    </div>
                </div>
            </div>

            <!-- Sparkline Chart -->
            <div class="position-absolute end-0 top-50 translate-middle-y pe-3" style="pointer-events: none;">
                <svg width="60" height="26" viewBox="0 0 60 24" class="sparkline text-{{ $stat['color'] }}">
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
