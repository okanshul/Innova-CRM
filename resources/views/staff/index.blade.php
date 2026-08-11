@extends('layouts.app', ['title' => 'InnovaCRM - Staff Management'])

@section('content')
    <!-- Breadcrumb Component -->
    <x-breadcrumb :items="[['label' => 'Dashboard', 'url' => route('dashboard')], ['label' => 'Staff']]" />

    <!-- Table Card Container -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
        <div class="card-header border-0 bg-body p-3">
            <!-- Header Row Component (cardHeader=false avoids nested card-header) -->
            <x-page-header title="Staff" icon="fa-solid fa-user-group" :cardHeader="false" class="mb-4">
                <x-slot:actions>
                    @can('staff.create')
                        <x-button.primary href="{{ route('staff.create') }}" icon="fa-solid fa-plus fs-sm" label="Add Staff" />
                    @endcan
                </x-slot:actions>
            </x-page-header>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-3" role="alert"
                    style="font-size: 0.875rem;">
                    <i class="fa-solid fa-circle-check me-1.5"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Controls / Filters Row -->
            <div
                class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center justify-content-between gap-3">
                <!-- Left Filters -->
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <x-form.select name="filterDepartment" id="filterDepartment" style="width: auto; min-width: 165px;"
                        class="custom-filter-select shadow-none flex-grow-1 flex-md-grow-0">
                        <option value="">All Departments</option>
                        <option value="Sales">Sales</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Customer Support">Customer Support</option>
                        <option value="Finance">Finance</option>
                        <option value="IT">IT</option>
                        <option value="Operations">Operations</option>
                    </x-form.select>

                    <x-form.select name="filterStatus" id="filterStatus" style="width: auto; min-width: 140px;"
                        class="custom-filter-select shadow-none flex-grow-1 flex-md-grow-0">
                        <option value="">Status: All</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </x-form.select>

                    @can('staff.delete')
                        <button class="btn btn-delete-bulk shadow-none d-none align-items-center gap-1.5" id="btnBulkDelete">
                            <i class="fa-regular fa-trash-can"></i> Delete Selected (<span id="selectedCount">0</span>)
                        </button>
                    @endcan
                </div>

                <!-- Right Search & Export Controls -->
                <div class="d-flex flex-wrap align-items-center gap-2 ms-md-auto">
                    <x-form.select name="perPage" id="perPage" style="width: auto; min-width: 140px;"
                        class="custom-filter-select shadow-none flex-grow-1 flex-md-grow-0">
                        <option value="10" selected>10 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                    </x-form.select>

                    <div class="search-input-box px-3 py-1 d-flex align-items-center flex-grow-1 flex-md-grow-0"
                        style="max-width: 250px;">
                        <i class="fa-solid fa-magnifying-glass text-secondary me-2 fs-sm"></i>
                        <input type="text" id="searchInput"
                            class="form-control border-0 bg-transparent shadow-none p-1 fs-sm"
                            placeholder="Search staff...">
                    </div>

                    <button
                        class="btn btn-filter-action shadow-none d-flex align-items-center gap-2 justify-content-center flex-grow-1 flex-md-grow-0"
                        id="btnFilterTrigger">
                        <i class="fa-solid fa-sliders"></i> Filter
                    </button>

                    <button
                        class="btn btn-filter-action shadow-none d-flex align-items-center gap-2 justify-content-center flex-grow-1 flex-md-grow-0"
                        id="btnExport">
                        <i class="fa-solid fa-download"></i> Export
                    </button>
                </div>
            </div>
        </div>

        <!-- Data Table Component (Desktop View: hidden below lg) -->
        <div class="d-none d-lg-block">
            <x-data-table id="staffTable" tableBodyId="staffTableBody" :showCheckboxColumn="true" :headers="[
                'STAFF',
                'DEPARTMENT',
                'ROLE',
                'EMAIL',
                'PHONE',
                'STATUS',
                'JOINED ON',
                ['title' => 'ACTIONS', 'align' => 'end'],
            ]" />
        </div>

        <!-- Card List Container (Mobile & Tablet View: hidden on lg and above) -->
        <div class="d-lg-none border-top">
            <div id="staffMobileCardList" class="d-flex flex-column bg-body">
                <!-- JS dynamically injects mobile card items here -->
            </div>
            <!-- Mobile Footer Pagination -->
            <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between p-3 bg-body gap-2"
                id="mobilePaginationRow">
                <div class="text-secondary small fw-medium text-center text-sm-start" id="mobilePaginationSummary">
                    Showing 0 entries
                </div>
                <div class="d-flex align-items-center gap-2" id="mobilePaginationControls">
                    <!-- Dynamic JS Pagination -->
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Manage Permissions Modal Component -->
    <x-modal id="permissionsModal" title="Manage Permissions" size="lg" icon="fa-solid fa-shield-halved"
        formId="permissionsModalForm" bodyId="permissionsModalBody">
        <x-slot:headerSubtitle>
            <span>Set individual permissions for <strong id="permStaffName" class="text-body-emphasis">...</strong></span>
            <x-badge.status value="Staff" type="role" id="permStaffRoleBadge" />
        </x-slot:headerSubtitle>

        <div class="text-center py-5 text-secondary" id="permissionsModalSpinner">
            <i class="fa-solid fa-spinner fa-spin fs-3" style="color: #6366F1;"></i>
            <p class="mt-2 mb-0 small">Loading permissions...</p>
        </div>

        <div id="permissionsModalContent" class="d-none">
            @include('staff.partials.permissions-accordion', [
                'groupedPermissions' => $groupedPermissions ?? [],
                'directPermissions' => [],
                'rolePermissions' => [],
                'idPrefix' => 'modal_perm',
            ])
        </div>

        <x-slot:footer>
            <x-button.secondary data-bs-dismiss="modal" label="Cancel" />
            <x-button.primary id="btnSavePermissions" type="submit" icon="fa-solid fa-check me-1.5"
                label="Save Permissions" />
        </x-slot:footer>
    </x-modal>
@endsection

@push('scripts')
    <script>
        window.userPermissions = {
            canEdit: @json(auth()->user()->can('staff.edit')),
            canDelete: @json(auth()->user()->can('staff.delete'))
        };
    </script>
    <script src="{{ asset('js/staff.js') }}"></script>
@endpush
