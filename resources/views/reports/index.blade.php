@extends('layouts.app', ['title' => 'InnovaCRM - Reports & Analytics'])

@section('content')
    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Reports']]" />

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body mb-4">
        <x-page-header title="Reports & Analytics" subtitle="Comprehensive performance metrics and sales overview." icon="fa-solid fa-chart-column" />

        <div class="p-4">
            <div class="row g-4">
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-body-tertiary">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3">
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
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-body-tertiary">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 bg-success bg-opacity-10 text-success rounded-3">
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
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-body-tertiary">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 bg-info bg-opacity-10 text-info rounded-3">
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
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-body-tertiary">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-3">
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
        </div>
    </div>
@endsection
