@extends('layouts.app', ['title' => 'InnovaCRM - Tasks'])

@section('content')
    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Tasks']]" />

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
        <div class="card-header border-0 bg-body p-3">
            <x-page-header title="Tasks" icon="fa-regular fa-square-check" :cardHeader="false" class="mb-4">
                <x-slot:actions>
                    @can('tasks.create')
                        <x-button.primary href="{{ route('tasks.create') }}" icon="fa-solid fa-plus fs-sm" label="Add Task" />
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
                <div class="search-input-box px-3 py-1 d-flex align-items-center order-1 order-md-2 ms-md-auto">
                    <i class="fa-solid fa-magnifying-glass text-secondary me-2 fs-sm"></i>
                    <input type="text" id="searchInput" class="form-control border-0 bg-transparent shadow-none p-1 fs-sm w-100" placeholder="Search tasks...">
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2 order-2 order-md-1 flex-grow-1 flex-md-grow-0">
                    <div class="flex-grow-1 flex-sm-grow-0 filter-item-half">
                        <x-form.select name="filterPriority" id="filterPriority" class="custom-filter-select w-100 shadow-none">
                            <option value="">Priority: All</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </x-form.select>
                    </div>

                    <div class="flex-grow-1 flex-sm-grow-0 filter-item-half">
                        <x-form.select name="filterStatus" id="filterStatus" class="custom-filter-select w-100 shadow-none">
                            <option value="">Status: All</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </x-form.select>
                    </div>

                    @can('tasks.delete')
                        <button class="btn btn-delete-bulk shadow-none d-none align-items-center gap-2 flex-grow-1 flex-sm-grow-0" id="btnBulkDelete">
                            <i class="fa-regular fa-trash-can"></i> Delete Selected (<span id="selectedCount">0</span>)
                        </button>
                    @endcan
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2 order-3 order-md-3">
                    <div class="flex-grow-1 flex-sm-grow-0 filter-item-third">
                        <x-form.select name="perPage" id="perPage" class="custom-filter-select w-100 shadow-none">
                            <option value="10" selected>10 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                        </x-form.select>
                    </div>

                    <div class="flex-grow-1 flex-sm-grow-0 filter-item-third">
                        <button class="btn btn-filter-action shadow-none w-100 d-flex align-items-center gap-2 justify-content-center text-nowrap" id="btnFilterTrigger" title="Reset Filters">
                            <i class="fa-solid fa-rotate-left"></i> <span>Reset</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-none d-lg-block">
            <x-data-table id="tasksTable" tableBodyId="tasksTableBody" :showCheckboxColumn="true" :headers="[
                'TASK TITLE',
                'ASSIGNED TO',
                'PRIORITY',
                'STATUS',
                'DUE DATE',
                ['title' => 'ACTIONS', 'align' => 'end'],
            ]" />
        </div>

        <div class="d-lg-none border-top">
            <div id="tasksMobileCardList" class="d-flex flex-column bg-body"></div>
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
            canEdit: @json(auth()->user()->can('tasks.edit')),
            canDelete: @json(auth()->user()->can('tasks.delete'))
        };
    </script>
    <script src="{{ asset('js/tasks.js') }}"></script>
@endpush
