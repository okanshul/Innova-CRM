@extends('layouts.app', ['title' => 'InnovaCRM - Dashboard'])

@section('content')
    <!-- Stats Row (2x2 Grid on Mobile) -->
    <div class="row g-2 g-sm-3 mb-3">
        @foreach ($stats as $stat)
            @include('partials.stat-card', ['stat' => $stat])
        @endforeach
    </div>

    <!-- Pipeline section -->
    <div class="card rounded-4 shadow-sm border-0 mb-3">
        <div class="card-body p-3">
            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                <h5 class="fw-bold mb-0">Sales Pipeline</h5>
                <div class="d-flex gap-2 align-items-center">
                    <button
                        class="btn btn-sm btn-light border text-body-secondary d-flex align-items-center gap-2 rounded-3 shadow-none px-3 py-1"
                        style="font-size: 0.75rem;">
                        <i class="fa-solid fa-filter text-secondary"></i> Filter
                    </button>
                    <div class="dropdown">
                        <button
                            class="btn btn-sm btn-light border text-body-secondary dropdown-toggle d-flex align-items-center gap-2 rounded-3 shadow-none px-3 py-1"
                            style="font-size: 0.75rem;" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            This Month
                        </button>
                        <ul class="dropdown-menu shadow-sm">
                            <li><a class="dropdown-item" href="#">This Week</a></li>
                            <li><a class="dropdown-item active" href="#">This Month</a></li>
                            <li><a class="dropdown-item" href="#">This Year</a></li>
                        </ul>
                    </div>
                    <button class="btn btn-sm btn-link text-secondary p-0 shadow-none text-decoration-none px-1"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                </div>
            </div>

            <div class="pipeline-board pb-1 px-1">
                @foreach ($pipeline as $stage => $column)
                    @include('partials.pipeline-column', ['stage' => $stage, 'column' => $column])
                @endforeach
            </div>
        </div>
    </div>

    <!-- Charts & Activity Row -->
    <div class="row g-3 mb-3">
        <!-- Revenue Chart -->
        <div class="col-xl-5 col-lg-12">
            <div class="card h-100 rounded-4 shadow-sm border-0">
                <div class="card-body p-3 d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold mb-0 fs-sm">Revenue Overview</h6>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light border text-body-secondary dropdown-toggle shadow-none rounded-3 py-1 px-2.5" style="font-size: 0.75rem;" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                This Year
                            </button>
                            <ul class="dropdown-menu shadow-sm">
                                <li><a class="dropdown-item active" href="#">This Year</a></li>
                                <li><a class="dropdown-item" href="#">Last Year</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex-grow-1 position-relative" style="min-height: 210px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leads Donut Chart -->
        <div class="col-xl-3 col-lg-6">
            <div class="card h-100 rounded-4 shadow-sm border-0">
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <h6 class="fw-bold mb-3 fs-sm">Leads by Source</h6>
                    <div class="d-flex flex-column flex-sm-row align-items-center gap-3 my-auto">
                        <div class="position-relative d-flex align-items-center justify-content-center flex-shrink-0" style="width: 160px; height: 160px;">
                            <canvas id="leadsChart"></canvas>
                            <!-- Center Text overlay for donut -->
                            <div class="position-absolute text-center d-flex flex-column align-items-center justify-content-center pointer-events-none w-100" style="pointer-events: none;">
                                <h6 class="mb-0 fw-bold fs-5">1,242</h6>
                                <span class="text-secondary" style="font-size:0.6rem">Total Leads</span>
                            </div>
                        </div>
                        <!-- Custom Side Legend -->
                        <div class="d-flex flex-column gap-2 ps-sm-1 w-100">
                            <div class="d-flex align-items-center justify-content-between justify-content-sm-start gap-3" style="font-size: 0.725rem;">
                                <div class="d-flex align-items-center gap-2" style="min-width: 105px;">
                                    <span class="d-inline-block rounded-circle bg-primary" style="width:7px;height:7px;"></span>
                                    <span class="text-body-emphasis fw-medium">Website</span>
                                </div>
                                <div class="d-flex gap-1">
                                    <span class="text-body-emphasis fw-bold">35%</span>
                                    <span class="text-secondary">(435)</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between justify-content-sm-start gap-3" style="font-size: 0.725rem;">
                                <div class="d-flex align-items-center gap-2" style="min-width: 105px;">
                                    <span class="d-inline-block rounded-circle bg-primary opacity-75" style="width:7px;height:7px; background-color: #3b82f6 !important"></span>
                                    <span class="text-body-emphasis fw-medium">Referral</span>
                                </div>
                                <div class="d-flex gap-1">
                                    <span class="text-body-emphasis fw-bold">25%</span>
                                    <span class="text-secondary">(311)</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between justify-content-sm-start gap-3" style="font-size: 0.725rem;">
                                <div class="d-flex align-items-center gap-2" style="min-width: 105px;">
                                    <span class="d-inline-block rounded-circle bg-info" style="width:7px;height:7px;"></span>
                                    <span class="text-body-emphasis fw-medium">Social Media</span>
                                </div>
                                <div class="d-flex gap-1">
                                    <span class="text-body-emphasis fw-bold">20%</span>
                                    <span class="text-secondary">(248)</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between justify-content-sm-start gap-3" style="font-size: 0.725rem;">
                                <div class="d-flex align-items-center gap-2" style="min-width: 105px;">
                                    <span class="d-inline-block rounded-circle bg-warning" style="width:7px;height:7px;"></span>
                                    <span class="text-body-emphasis fw-medium">Email Campaign</span>
                                </div>
                                <div class="d-flex gap-1">
                                    <span class="text-body-emphasis fw-bold">10%</span>
                                    <span class="text-secondary">(124)</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between justify-content-sm-start gap-3" style="font-size: 0.725rem;">
                                <div class="d-flex align-items-center gap-2" style="min-width: 105px;">
                                    <span class="d-inline-block rounded-circle bg-secondary" style="width:7px;height:7px;"></span>
                                    <span class="text-body-emphasis fw-medium">Other</span>
                                </div>
                                <div class="d-flex gap-1">
                                    <span class="text-body-emphasis fw-bold">10%</span>
                                    <span class="text-secondary">(124)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity list -->
        <div class="col-xl-4 col-lg-6">
            <div class="card h-100 rounded-4 shadow-sm border-0">
                <div class="card-body p-3 pb-0 d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="fw-bold mb-0 fs-sm">Recent Activity</h6>
                        <button class="btn btn-sm btn-link text-primary text-decoration-none rounded-3 py-1 px-1 shadow-none fw-semibold" style="font-size: 0.75rem;">View All</button>
                    </div>
                    <div class="list-group list-group-flush border-0 flex-grow-1">
                        @foreach ($activities as $activity)
                            @include('partials.activity-item', ['activity' => $activity])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contacts Table Row -->
    <div class="row">
        <div class="col-12">
            <div class="card h-100 rounded-4 shadow-sm border-0 flex-grow-1">
                <div class="card-body p-0 d-flex flex-column">
                    <div class="d-flex align-items-center justify-content-between px-3 pt-3 pb-2">
                        <h6 class="fw-bold mb-0">Recent Contacts</h6>
                        <button class="btn btn-sm btn-link text-primary text-decoration-none rounded-3 py-1 px-1 shadow-none fw-semibold" style="font-size: 0.75rem;">View All Contacts</button>
                    </div>

                    <div class="table-responsive flex-grow-1 rounded-bottom-4">
                        <table class="table table-hover align-middle mb-0" style="min-width: 600px;">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 px-3 py-2" style="font-size: 0.75rem;">Name</th>
                                    <th class="border-0 py-2" style="font-size: 0.75rem;">Company</th>
                                    <th class="border-0 py-2" style="font-size: 0.75rem;">Status</th>
                                    <th class="border-0 py-2" style="font-size: 0.75rem;">Last Contact</th>
                                    <th class="border-0 py-2 text-end" style="font-size: 0.75rem;">Deal Value</th>
                                    <th class="border-0 py-2" style="font-size: 0.75rem;">Owner</th>
                                    <th class="border-0 py-2 text-end px-3" style="font-size: 0.75rem;">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @foreach ($contacts as $contact)
                                    <tr>
                                        <td class="px-3 py-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar avatar-sm bg-{{ $contact['color'] }} bg-opacity-10 text-{{ $contact['color'] }}">
                                                    {{ $contact['initials'] }}
                                                </div>
                                                <span class="fw-bold text-body-emphasis" style="font-size: 0.8rem;">{{ $contact['name'] }}</span>
                                            </div>
                                        </td>
                                        <td class="py-2">
                                            <div class="d-flex align-items-center gap-2 text-secondary" style="font-size: 0.75rem;">
                                                <i class="fa-solid fa-building text-primary"></i>
                                                {{ $contact['company'] }}
                                            </div>
                                        </td>
                                        <td class="py-2">
                                            <span class="badge fw-bold bg-{{ $contact['color'] }} bg-opacity-10 text-{{ $contact['color'] }} px-2 py-1" style="font-size: 0.625rem;">{{ $contact['status'] }}</span>
                                        </td>
                                        <td class="py-2 text-secondary" style="font-size: 0.75rem;">{{ $contact['last_contact'] }}</td>
                                        <td class="py-2 fw-bold text-body-emphasis text-end" style="font-size: 0.8rem;">{{ $contact['value'] }}</td>
                                        <td class="py-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="https://i.pravatar.cc/150?img={{ $loop->index + 10 }}" class="avatar avatar-sm rounded-circle" alt="{{ $contact['owner'] }}" style="width: 24px; height: 24px;">
                                                <span class="text-secondary d-none d-xl-inline" style="font-size: 0.75rem;">{{ $contact['owner'] }}</span>
                                            </div>
                                        </td>
                                        <td class="py-2 text-end px-3">
                                            <div class="d-flex gap-0 justify-content-end align-items-center bg-body-tertiary rounded-pill px-1" style="display: inline-flex !important;">
                                                <button class="btn btn-sm text-primary p-0 shadow-none hover-primary rounded-circle d-flex align-items-center justify-content-center m-1" style="width: 24px; height: 24px; background-color: rgba(99, 102, 241, 0.1);"><i class="fa-solid fa-phone fs-6"></i></button>
                                                <button class="btn btn-sm text-primary p-0 shadow-none hover-primary rounded-circle d-flex align-items-center justify-content-center m-1" style="width: 24px; height: 24px; background-color: rgba(99, 102, 241, 0.1);"><i class="fa-solid fa-envelope fs-6"></i></button>
                                                <button class="btn btn-sm text-secondary p-0 shadow-none rounded-circle d-flex align-items-center justify-content-center m-1 hover-secondary" style="width: 24px; height: 24px;"><i class="fa-solid fa-ellipsis fs-6"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
