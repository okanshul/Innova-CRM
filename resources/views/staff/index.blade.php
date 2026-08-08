<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>InnovaCRM - Staff Management</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Inline script to apply collapsed state -->
    <script>
        (function() {
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                document.documentElement.classList.add('sidebar-collapsed');
            }
        })();
    </script>

    @vite(['resources/scss/theme.scss', 'resources/js/dashboard.js'])

    <style>
        /* Custom UI Helpers for Staff Page */
        .btn-purple-primary {
            background: linear-gradient(135deg, #6366f1, #4f46e5) !important;
            border: none !important;
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.3);
            transition: all 0.2s ease;
        }

        .btn-purple-primary:hover {
            opacity: 0.95;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(99, 102, 241, 0.4);
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            font-size: 0.85rem;
            transition: all 0.15s ease;
            cursor: pointer;
            text-decoration: none !important;
        }

        .action-btn-view {
            color: #6366f1;
            border-color: #e0e7ff;
        }

        .action-btn-view:hover {
            background-color: #f5f3ff;
            color: #4f46e5;
            border-color: #c7d2fe;
        }

        .action-btn-edit {
            color: #0284c7;
            border-color: #e0f2fe;
        }

        .action-btn-edit:hover {
            background-color: #f0f9ff;
            color: #0369a1;
            border-color: #bae6fd;
        }

        .action-btn-delete {
            color: #e11d48;
            border-color: #ffe4e6;
        }

        .action-btn-delete:hover {
            background-color: #fff1f2;
            color: #be123c;
            border-color: #fecdd3;
        }

        .action-btn-perm {
            color: #7e22ce;
            border-color: #f3e8ff;
            background-color: #ffffff;
        }

        .action-btn-perm:hover {
            background-color: #f3e8ff;
            color: #6b21a8;
            border-color: #e9d5ff;
        }

        /* Role Badges */
        .role-badge {
            font-weight: 500;
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
            font-size: 0.775rem;
            display: inline-block;
            white-space: nowrap;
        }

        .role-badge-purple {
            background-color: #f3e8ff;
            color: #7e22ce;
        }

        .role-badge-cyan {
            background-color: #e0f2fe;
            color: #0284c7;
        }

        .role-badge-orange {
            background-color: #ffedd5;
            color: #c2410c;
        }

        .role-badge-green {
            background-color: #dcfce7;
            color: #15803d;
        }

        .role-badge-blue {
            background-color: #dbeafe;
            color: #1d4ed8;
        }

        /* Status Badges */
        .status-badge {
            font-weight: 600;
            padding: 0.3rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
        }

        .status-badge-active {
            background-color: #dcfce7;
            color: #16a34a;
        }

        .status-badge-inactive {
            background-color: #ffe4e6;
            color: #e11d48;
        }

        /* Custom Select & Inputs for Filters Bar */
        .custom-filter-select {
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            height: 35px !important;
            font-size: 0.825rem !important;
            font-weight: 500 !important;
            color: #475569 !important;
            padding-top: 0.25rem !important;
            padding-bottom: 0.25rem !important;
            padding-right: 2.25rem !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
            transition: all 0.15s ease;
        }

        .custom-filter-select:hover,
        .custom-filter-select:focus {
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
        }

        .search-input-box {
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            height: 35px !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .search-input-box:focus-within {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15) !important;
        }

        .btn-filter-action {
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            height: 35px !important;
            font-size: 0.825rem !important;
            font-weight: 500 !important;
            color: #475569 !important;
            padding-top: 0.25rem !important;
            padding-bottom: 0.25rem !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
            transition: all 0.15s ease;
        }

        .btn-filter-action:hover {
            background-color: #f8fafc !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
        }

        .btn-delete-bulk {
            background-color: #ffffff !important;
            border: 1px solid #ffe4e6 !important;
            border-radius: 8px !important;
            height: 35px !important;
            font-size: 0.825rem !important;
            font-weight: 500 !important;
            color: #e11d48 !important;
            padding: 0.25rem 0.85rem !important;
            box-shadow: 0 1px 2px rgba(225, 29, 72, 0.06);
            transition: all 0.15s ease;
        }

        .btn-delete-bulk:hover {
            background-color: #fff1f2 !important;
            border-color: #fecdd3 !important;
            color: #be123c !important;
        }

        /* Checkbox Styling */
        .custom-checkbox {
            width: 18px !important;
            height: 18px !important;
            border: 1.5px solid #cbd5e1 !important;
            border-radius: 5px !important;
            cursor: pointer;
            vertical-align: middle;
            margin: 0 !important;
        }

        .custom-checkbox:checked {
            background-color: #6366f1 !important;
            border-color: #6366f1 !important;
        }

        /* Pagination Buttons */
        .page-btn {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            font-weight: 500;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            color: #475569;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .page-btn:hover:not(.active):not(:disabled) {
            background-color: #f8fafc;
            color: #0f172a;
            border-color: #cbd5e1;
        }

        .page-btn.active {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #ffffff;
            border-color: transparent;
            box-shadow: 0 3px 10px rgba(99, 102, 241, 0.35);
            font-weight: 600;
        }

        .page-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Dark Theme Support */
        [data-bs-theme="dark"] .custom-filter-select,
        [data-bs-theme="dark"] .search-input-box,
        [data-bs-theme="dark"] .btn-filter-action {
            background-color: #1a1a30 !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #cbd5e1 !important;
        }

        [data-bs-theme="dark"] .page-btn {
            background-color: #1a1a30;
            border-color: rgba(255, 255, 255, 0.1);
            color: #cbd5e1;
        }

        [data-bs-theme="dark"] .page-btn:hover:not(.active):not(:disabled) {
            background-color: rgba(255, 255, 255, 0.08);
            color: #ffffff;
        }
    </style>
</head>

<body>
    <script>
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            document.body.classList.add('sidebar-collapsed');
        }
    </script>

    @include('partials.sidebar')

    <div id="main-content">
        @include('partials.header')

        <main class="flex-grow-1 p-4">
            <!-- Breadcrumb Navigation -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0 align-items-center" style="font-size: 0.825rem;">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}"
                            class="text-secondary text-decoration-none d-inline-flex align-items-center gap-1 hover-primary">
                            <i class="fa-solid fa-house text-secondary pe-2" style="font-size: 0.80rem;"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-body-emphasis fw-semibold" aria-current="page">Staff</li>
                </ol>
            </nav>

            <!-- Table Card -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
                <!-- Card Header with Title & Filters -->
                <div class="card-header border-0 bg-body p-4 pb-3">
                    <!-- Header Row: Title & Add Staff Button -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center"
                                style="background: #f3e8ff; color: #7e22ce; width: 44px; height: 44px;">
                                <i class="fa-solid fa-user-group fs-5"></i>
                            </div>
                            <h2 class="h3 fw-bold mb-0 text-body-emphasis" style="letter-spacing: -0.02em;">Staff</h2>
                        </div>
                        @can('staff.create')
                            <a href="{{ route('staff.create') }}"
                                class="btn btn-purple-primary rounded-3 px-3.5 py-2.5 fw-semibold d-flex align-items-center gap-2"
                                style="font-size: 0.875rem;">
                                <i class="fa-solid fa-plus fs-sm"></i> Add Staff
                            </a>
                        @endcan
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-3" role="alert" style="font-size: 0.875rem;">
                            <i class="fa-solid fa-circle-check me-1.5"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Controls / Filters Row -->
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <!-- Left Filters -->
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <select class="form-select custom-filter-select shadow-none" id="filterDepartment"
                                style="width: auto; min-width: 165px;">
                                <option value="">All Departments</option>
                                <option value="Sales">Sales</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Customer Support">Customer Support</option>
                                <option value="Finance">Finance</option>
                                <option value="IT">IT</option>
                                <option value="Operations">Operations</option>
                            </select>

                            <select class="form-select custom-filter-select shadow-none" id="filterStatus"
                                style="width: auto; min-width: 140px;">
                                <option value="">Status: All</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>

                            @can('staff.delete')
                            <button class="btn btn-delete-bulk shadow-none d-none align-items-center gap-1.5" id="btnBulkDelete">
                                <i class="fa-regular fa-trash-can"></i> Delete Selected (<span id="selectedCount">0</span>)
                            </button>
                            @endcan
                        </div>

                        <!-- Right Actions -->
                        <div class="d-flex flex-wrap align-items-center gap-2 ms-auto">
                            <select class="form-select custom-filter-select shadow-none" id="perPage"
                                style="width: auto; min-width: 140px;">
                                <option value="10" selected>10 per page</option>
                                <option value="25">25 per page</option>
                                <option value="50">50 per page</option>
                            </select>

                            <div class="search-input-box px-3 py-1 d-flex align-items-center" style="max-width: 250px;">
                                <i class="fa-solid fa-magnifying-glass text-secondary me-2 fs-sm"></i>
                                <input type="text" id="searchInput" class="form-control border-0 bg-transparent shadow-none p-1 fs-sm" placeholder="Search staff...">
                            </div>

                            <button class="btn btn-filter-action shadow-none d-flex align-items-center gap-2" id="btnFilterTrigger">
                                <i class="fa-solid fa-sliders"></i> Filter
                            </button>

                            <button class="btn btn-filter-action shadow-none d-flex align-items-center gap-2" id="btnExport">
                                <i class="fa-solid fa-download"></i> Export
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0" id="staffTable">
                        <thead class="border-bottom bg-body-tertiary">
                            <tr class="text-secondary fw-bold" style="font-size: 0.75rem; letter-spacing: 0.03em;">
                                <th class="ps-4 py-3" style="width: 40px;">
                                    <input type="checkbox" class="form-check-input custom-checkbox" id="selectAll">
                                </th>
                                <th class="py-3">STAFF</th>
                                <th class="py-3">DEPARTMENT</th>
                                <th class="py-3">ROLE</th>
                                <th class="py-3">EMAIL</th>
                                <th class="py-3">PHONE</th>
                                <th class="py-3">STATUS</th>
                                <th class="py-3">JOINED ON</th>
                                <th class="pe-4 py-3 text-end">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody id="staffTableBody">
                            <!-- Dynamic JS Rows -->
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination -->
                <div class="d-flex flex-wrap align-items-center justify-content-between px-4 py-3 bg-body" id="tablePaginationRow">
                    <div class="text-secondary small fw-medium" id="paginationSummary">
                        Showing 1 to 10 of 21 entries
                    </div>
                    <div class="d-flex align-items-center gap-2" id="paginationControls">
                        <!-- Rendered by JS -->
                    </div>
                </div>
            </div>
        </main>
    <!-- Manage Permissions Modal -->
    <style>
        #permissionsModalBody {
            max-height: 60vh;
            overflow-y: auto;
        }
        #permissionsModalBody::-webkit-scrollbar {
            width: 6px;
        }
        #permissionsModalBody::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        #permissionsModalBody::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        #permissionsModalBody::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    <div class="modal fade" id="permissionsModal" tabindex="-1" aria-labelledby="permissionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <form id="permissionsModalForm" class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom px-4 py-3 bg-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center"
                             style="background: #f3e8ff; color: #7e22ce; width: 44px; height: 44px;">
                            <i class="fa-solid fa-shield-halved fs-5"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-body-emphasis mb-0" id="permissionsModalLabel">Manage Permissions</h5>
                            <div class="text-secondary small d-flex align-items-center gap-2 mt-0.5">
                                <span>Set individual permissions for <strong id="permStaffName" class="text-body-emphasis">...</strong></span>
                                <span id="permStaffRoleBadge" class="role-badge role-badge-purple">Role: Staff</span>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-body" id="permissionsModalBody">
                    <div class="text-center py-5 text-secondary" id="permissionsModalSpinner">
                        <i class="fa-solid fa-spinner fa-spin fs-3" style="color: #6366F1;"></i>
                        <p class="mt-2 mb-0 small">Loading permissions...</p>
                    </div>
                    <div id="permissionsModalContent" class="d-none">
                        @include('staff.partials.permissions-accordion', [
                            'groupedPermissions' => $groupedPermissions ?? [],
                            'directPermissions' => [],
                            'rolePermissions' => [],
                            'idPrefix' => 'modal_perm'
                        ])
                    </div>
                </div>
                <div class="modal-footer border-top px-4 py-3 bg-body">
                    <button type="button" class="btn btn-light border rounded-3 px-4 py-2 text-secondary fw-medium" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-purple-primary rounded-3 px-4 py-2 fw-semibold" id="btnSavePermissions">
                        <i class="fa-solid fa-check me-1.5"></i> Save Permissions
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Inject permissions for JS -->
    <script>
        window.userPermissions = {
            canEdit: @json(auth()->user()->can('staff.edit')),
            canDelete: @json(auth()->user()->can('staff.delete'))
        };
    </script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/staff.js') }}"></script>
</body>

</html>
