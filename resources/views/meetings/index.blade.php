@extends('layouts.app', ['title' => 'InnovaCRM - Meetings'])

@section('content')
    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Meetings']]" />

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
        <div class="card-header border-0 bg-body p-3">
            <x-page-header title="Meetings" icon="fa-solid fa-video" :cardHeader="false" class="mb-4">
                <x-slot:actions>
                    @can('meetings.create')
                        <x-button.primary href="{{ route('meetings.create') }}" icon="fa-solid fa-plus fs-sm" label="Schedule Meeting" />
                    @endcan
                </x-slot:actions>
            </x-page-header>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-3" role="alert" style="font-size: 0.875rem;">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="filter-controls-wrapper d-flex flex-column flex-md-row align-items-stretch align-items-md-center justify-content-between gap-2">
                <!-- Filters Group - Left Side -->
                <div class="d-flex flex-wrap align-items-center gap-2 order-2 order-md-1">
                    <div class="filter-item-half">
                        <x-form.select name="filterStatus" id="filterStatus" class="custom-filter-select w-100 shadow-none">
                            <option value="">Status: All</option>
                            <option value="scheduled">Scheduled</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="rescheduled">Rescheduled</option>
                        </x-form.select>
                    </div>

                    <div class="filter-item-half">
                        <x-form.select name="perPage" id="perPage" class="custom-filter-select w-100 shadow-none">
                            <option value="5">5 per page</option>
                            <option value="10" selected>10 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </x-form.select>
                    </div>

                    @can('meetings.delete')
                        <button class="btn btn-delete-bulk shadow-none d-none align-items-center gap-2 filter-item-half" id="btnBulkDelete">
                            <i class="fa-regular fa-trash-can"></i> Delete Selected (<span id="selectedCount">0</span>)
                        </button>
                    @endcan
                </div>

                <!-- Search & Actions Group - Right Side -->
                <div class="d-flex flex-wrap flex-sm-nowrap align-items-center gap-2 order-1 order-md-2 ms-md-auto">
                    <div class="search-input-box px-3 py-1 d-flex align-items-center flex-grow-1 flex-sm-grow-0">
                        <i class="fa-solid fa-magnifying-glass text-secondary me-2 fs-sm"></i>
                        <input type="text" id="searchInput" class="form-control border-0 bg-transparent shadow-none p-1 fs-sm w-100" placeholder="Search meetings...">
                    </div>

                    <div class="filter-item-half">
                        <button class="btn btn-filter-action shadow-none w-100 d-flex align-items-center gap-2 justify-content-center text-nowrap" id="btnFilterTrigger" title="Reset Filters">
                            <i class="fa-solid fa-rotate-left"></i> <span>Reset</span>
                        </button>
                    </div>

                    <div class="filter-item-half">
                        <button class="btn btn-filter-action shadow-none w-100 d-flex align-items-center gap-2 justify-content-center text-nowrap" id="btnExport" title="Export Meetings">
                            <i class="fa-solid fa-download"></i> <span>Export</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-none d-lg-block">
            <x-data-table id="meetingsTable" tableBodyId="meetingsTableBody" :showCheckboxColumn="true" :headers="[
                'MEETING TITLE',
                'HOST',
                'START TIME',
                'END TIME',
                'LOCATION / LINK',
                'STATUS',
                ['title' => 'ACTIONS', 'align' => 'end'],
            ]" />
        </div>

        <div class="d-lg-none border-top">
            <div id="meetingsMobileCardList" class="d-flex flex-column bg-body"></div>
            <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between p-3 bg-body gap-2 border-top rounded-bottom-4" id="mobilePaginationRow">
                <div class="text-secondary small fw-medium text-center text-sm-start" id="mobilePaginationSummary">Showing 0 entries</div>
                <div class="d-flex align-items-center gap-2" id="mobilePaginationControls"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.userPermissions = {
            canEdit: @json(auth()->user()->can('meetings.edit')),
            canDelete: @json(auth()->user()->can('meetings.delete'))
        };
    </script>
    <script src="{{ asset('js/meetings.js') }}"></script>
@endpush
