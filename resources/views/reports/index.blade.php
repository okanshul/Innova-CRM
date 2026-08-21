@extends('layouts.app', ['title' => 'InnovaCRM - Reports & Analytics'])

@section('content')
    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Reports']]" />

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body mb-4">
        <x-page-header title="Reports & Analytics" subtitle="Comprehensive performance metrics and sales overview." icon="fa-solid fa-chart-column" />

        <div class="p-3 p-md-4">
            <!-- Filter Bar -->
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-body-tertiary mb-4">
                <form method="GET" action="{{ route('reports.index') }}" id="reportsFilterForm">
                    <div class="row g-2 align-items-end">
                        <!-- Period Selector -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label small fw-semibold text-secondary mb-1">
                                <i class="fa-solid fa-calendar-days text-primary me-1"></i> Time Period
                            </label>
                            <select name="period" id="periodSelect" class="form-select form-select-sm rounded-3 shadow-none">
                                <option value="all" {{ ($period ?? 'all') === 'all' ? 'selected' : '' }}>All Time</option>
                                <option value="today" {{ ($period ?? '') === 'today' ? 'selected' : '' }}>Today</option>
                                <option value="this_week" {{ ($period ?? '') === 'this_week' ? 'selected' : '' }}>This Week</option>
                                <option value="this_month" {{ ($period ?? '') === 'this_month' ? 'selected' : '' }}>This Month</option>
                                <option value="this_quarter" {{ ($period ?? '') === 'this_quarter' ? 'selected' : '' }}>This Quarter</option>
                                <option value="this_year" {{ ($period ?? '') === 'this_year' ? 'selected' : '' }}>This Year</option>
                                <option value="custom" {{ ($period ?? '') === 'custom' ? 'selected' : '' }}>Custom Date Range</option>
                            </select>
                        </div>

                        <!-- Custom Date Range -->
                        <div class="col-6 col-sm-3 col-md-2 custom-date-field {{ ($period ?? '') !== 'custom' ? 'd-none' : '' }}">
                            <label class="form-label small fw-semibold text-secondary mb-1">Start Date</label>
                            <input type="date" name="start_date" class="form-control form-control-sm rounded-3 shadow-none" value="{{ $startDateInput ?? '' }}">
                        </div>
                        <div class="col-6 col-sm-3 col-md-2 custom-date-field {{ ($period ?? '') !== 'custom' ? 'd-none' : '' }}">
                            <label class="form-label small fw-semibold text-secondary mb-1">End Date</label>
                            <input type="date" name="end_date" class="form-control form-control-sm rounded-3 shadow-none" value="{{ $endDateInput ?? '' }}">
                        </div>

                        <!-- Owner / Staff Filter -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label small fw-semibold text-secondary mb-1">
                                <i class="fa-solid fa-user-gear text-primary me-1"></i> Owner / Staff
                            </label>
                            <select name="owner_id" class="form-select form-select-sm rounded-3 shadow-none">
                                <option value="">All Owners</option>
                                @foreach ($owners as $owner)
                                    <option value="{{ $owner->id }}" {{ (string)($ownerId ?? '') === (string)$owner->id ? 'selected' : '' }}>
                                        {{ $owner->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Pipeline Filter -->
                        <div class="col-12 col-sm-6 col-md-2">
                            <label class="form-label small fw-semibold text-secondary mb-1">
                                <i class="fa-solid fa-diagram-project text-primary me-1"></i> Pipeline
                            </label>
                            <select name="pipeline_id" class="form-select form-select-sm rounded-3 shadow-none">
                                <option value="">All Pipelines</option>
                                @foreach ($pipelines as $pipeline)
                                    <option value="{{ $pipeline->id }}" {{ (string)($pipelineId ?? '') === (string)$pipeline->id ? 'selected' : '' }}>
                                        {{ $pipeline->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Actions -->
                        <div class="col-12 col-md-2 d-flex gap-2 ms-auto">
                            <button type="submit" class="btn btn-sm btn-primary rounded-3 w-100 fw-semibold d-flex align-items-center justify-content-center gap-1 shadow-none">
                                <i class="fa-solid fa-filter fs-xs"></i> Filter
                            </button>
                            <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-secondary rounded-3 w-100 fw-semibold d-flex align-items-center justify-content-center gap-1 shadow-none">
                                <i class="fa-solid fa-rotate-left fs-xs"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Stat Cards Row -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-3 bg-body-tertiary">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3 flex-shrink-0" style="width: 48px; height: 48px;">
                                <i class="fa-solid fa-dollar-sign fs-4"></i>
                            </div>
                            <div>
                                <span class="text-secondary small fw-semibold">Won Revenue</span>
                                <h4 class="mb-0 fw-bold text-body-emphasis">{{ $stats['total_revenue'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-3 bg-body-tertiary">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-3 flex-shrink-0" style="width: 48px; height: 48px;">
                                <i class="fa-solid fa-gem fs-4"></i>
                            </div>
                            <div>
                                <span class="text-secondary small fw-semibold">Total Deals</span>
                                <h4 class="mb-0 fw-bold text-body-emphasis">{{ $stats['total_deals'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-3 bg-body-tertiary">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center bg-info bg-opacity-10 text-info rounded-3 flex-shrink-0" style="width: 48px; height: 48px;">
                                <i class="fa-regular fa-address-book fs-4"></i>
                            </div>
                            <div>
                                <span class="text-secondary small fw-semibold">Total Contacts</span>
                                <h4 class="mb-0 fw-bold text-body-emphasis">{{ $stats['total_contacts'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-3 bg-body-tertiary">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning rounded-3 flex-shrink-0" style="width: 48px; height: 48px;">
                                <i class="fa-solid fa-building fs-4"></i>
                            </div>
                            <div>
                                <span class="text-secondary small fw-semibold">Companies</span>
                                <h4 class="mb-0 fw-bold text-body-emphasis">{{ $stats['total_companies'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Charts Row 1 -->
            <div class="row g-4 mb-4">
                <!-- Monthly Revenue & Deals Trend Chart -->
                <div class="col-12 col-xl-8">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-body h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                            <div>
                                <h6 class="fw-bold mb-1 text-body-emphasis">Revenue & Deals Trend</h6>
                                <p class="text-secondary small mb-0">Monthly won revenue vs total deals count for {{ date('Y') }}</p>
                            </div>
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fw-semibold fs-xs">
                                <i class="fa-solid fa-calendar-days me-1"></i> Year {{ date('Y') }}
                            </span>
                        </div>
                        <div class="position-relative" style="min-height: 280px; height: 320px;">
                            <canvas id="reportRevenueChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Deal Win/Loss Conversion Rate Chart -->
                <div class="col-12 col-xl-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-body h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="fw-bold mb-0 text-body-emphasis">Deal Win / Loss Ratio</h6>
                                <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 fs-xs fw-bold">
                                    {{ $statusChartData['win_rate'] }}% Win Rate
                                </span>
                            </div>
                        </div>
                        <div class="position-relative d-flex align-items-center justify-content-center my-auto py-2" style="height: 220px;">
                            <canvas id="reportWinRateChart"></canvas>
                            <div class="position-absolute text-center pointer-events-none" style="pointer-events: none;">
                                <h4 class="fw-bold mb-0 text-body-emphasis">{{ $statusChartData['win_rate'] }}%</h4>
                                <span class="text-secondary small fw-medium">Win Rate</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-around border-top pt-3 mt-2 text-center">
                            <div>
                                <span class="d-block text-secondary small">Won</span>
                                <strong class="text-success">{{ $statusChartData['data'][0] }}</strong>
                            </div>
                            <div class="border-end h-100"></div>
                            <div>
                                <span class="d-block text-secondary small">Lost</span>
                                <strong class="text-danger">{{ $statusChartData['data'][1] }}</strong>
                            </div>
                            <div class="border-end h-100"></div>
                            <div>
                                <span class="d-block text-secondary small">In Progress</span>
                                <strong class="text-primary">{{ $statusChartData['data'][2] }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Secondary Charts Row 2 -->
            <div class="row g-4">
                <!-- Deals by Pipeline Stage -->
                <div class="col-12 col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-body h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="fw-bold mb-1 text-body-emphasis">Deals by Pipeline Stage</h6>
                                <p class="text-secondary small mb-0">Total deal value per pipeline stage</p>
                            </div>
                        </div>
                        <div class="position-relative" style="min-height: 260px; height: 280px;">
                            <canvas id="reportStageChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Lead Acquisition by Source -->
                <div class="col-12 col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-body h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="fw-bold mb-1 text-body-emphasis">Lead Acquisition Channels</h6>
                                <p class="text-secondary small mb-0">Distribution of leads by source</p>
                            </div>
                        </div>
                        <div class="position-relative d-flex align-items-center justify-content-center" style="min-height: 260px; height: 280px;">
                            <canvas id="reportSourceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Shared styling defaults
            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.color = '#64748b';

            // 1. Revenue & Deals Trend Chart (Combo Line/Bar)
            const revCtx = document.getElementById('reportRevenueChart');
            if (revCtx) {
                new Chart(revCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($chartMonthlyLabels),
                        datasets: [
                            {
                                label: 'Won Revenue ($)',
                                data: @json($chartMonthlyRevenue),
                                backgroundColor: 'rgba(99, 102, 241, 0.75)',
                                borderColor: '#6366f1',
                                borderWidth: 2,
                                borderRadius: 6,
                                yAxisID: 'y'
                            },
                            {
                                label: 'Total Deals',
                                data: @json($chartMonthlyDeals),
                                type: 'line',
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.35,
                                pointBackgroundColor: '#10b981',
                                pointRadius: 4,
                                yAxisID: 'y1'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        scales: {
                            x: { grid: { display: false } },
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                grid: { color: 'rgba(148, 163, 184, 0.1)' },
                                ticks: {
                                    callback: function(val) { return '$' + val.toLocaleString(); }
                                }
                            },
                            y1: {
                                type: 'linear',
                                display: true,
                                position: 'right',
                                grid: { drawOnChartArea: false },
                                ticks: { precision: 0 }
                            }
                        },
                        plugins: {
                            legend: { position: 'top', align: 'end' }
                        }
                    }
                });
            }

            // 2. Win / Loss Doughnut Chart
            const winCtx = document.getElementById('reportWinRateChart');
            if (winCtx) {
                new Chart(winCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json($statusChartData['labels']),
                        datasets: [{
                            data: @json($statusChartData['data']),
                            backgroundColor: ['#10b981', '#ef4444', '#3b82f6'],
                            borderWidth: 0,
                            borderColor: 'transparent'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '72%',
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            }

            // 3. Stage Distribution Chart (Horizontal Bar)
            const stageCtx = document.getElementById('reportStageChart');
            if (stageCtx) {
                new Chart(stageCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($stageLabels),
                        datasets: [{
                            label: 'Total Stage Value ($)',
                            data: @json($stageValues),
                            backgroundColor: ['#6366f1', '#8b5cf6', '#3b82f6', '#06b6d4', '#10b981', '#f59e0b'],
                            borderRadius: 6
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: {
                                grid: { color: 'rgba(148, 163, 184, 0.1)' },
                                ticks: { callback: function(val) { return '$' + val.toLocaleString(); } }
                            },
                            y: { grid: { display: false } }
                        }
                    }
                });
            }

            // 4. Lead Source Donut Chart
            const sourceCtx = document.getElementById('reportSourceChart');
            if (sourceCtx) {
                const sourceLabels = @json($sourceLabels);
                const sourceCounts = @json($sourceCounts);

                new Chart(sourceCtx, {
                    type: 'pie',
                    data: {
                        labels: sourceLabels.length ? sourceLabels : ['Direct', 'Website', 'Referral', 'Social Media'],
                        datasets: [{
                            data: sourceCounts.length ? sourceCounts : [10, 25, 15, 8],
                            backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#06b6d4', '#ec4899', '#8b5cf6'],
                            borderWidth: 0,
                            borderColor: 'transparent'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'right' }
                        }
                    }
                });
            }
            // Filter Period Toggle Listener
            const periodSelect = document.getElementById('periodSelect');
            if (periodSelect) {
                periodSelect.addEventListener('change', function() {
                    const isCustom = this.value === 'custom';
                    document.querySelectorAll('.custom-date-field').forEach(el => {
                        if (isCustom) {
                            el.classList.remove('d-none');
                        } else {
                            el.classList.add('d-none');
                        }
                    });
                });
            }
        });
    </script>
@endpush
