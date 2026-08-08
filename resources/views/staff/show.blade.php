<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InnovaCRM - Staff Details: {{ $staff->name }}</title>

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

        .role-badge {
            font-weight: 500;
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
            font-size: 0.775rem;
            display: inline-block;
        }
        .role-badge-purple { background-color: #f3e8ff; color: #7e22ce; }
        .role-badge-cyan { background-color: #e0f2fe; color: #0284c7; }
        .role-badge-orange { background-color: #ffedd5; color: #c2410c; }
        .role-badge-green { background-color: #dcfce7; color: #15803d; }
        .role-badge-blue { background-color: #dbeafe; color: #1d4ed8; }

        .status-badge {
            font-weight: 600;
            padding: 0.3rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
        }
        .status-badge-active { background-color: #dcfce7; color: #16a34a; }
        .status-badge-inactive { background-color: #ffe4e6; color: #e11d48; }
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
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0 align-items-center" style="font-size: 0.8rem;">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-secondary text-decoration-none d-inline-flex align-items-center hover-primary">
                            <i class="fa-solid fa-house me-1 text-secondary" style="font-size: 0.75rem;"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('staff.index') }}" class="text-secondary text-decoration-none hover-primary">Staff</a>
                    </li>
                    <li class="breadcrumb-item active text-body-emphasis fw-semibold" aria-current="page">{{ $staff->name }}</li>
                </ol>
            </nav>

            <!-- Staff Profile Detail Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-body">
                        <!-- Profile Top Banner -->
                        <div class="p-4 text-center border-bottom bg-body-tertiary">
                            @php
                                $avatarSrc = $staff->avatar
                                    ? asset('storage/' . $staff->avatar)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($staff->name) . '&background=6366F1&color=fff';
                            @endphp
                            <img src="{{ $avatarSrc }}" class="rounded-circle shadow-sm mb-3 object-fit-cover" width="96" height="96" alt="{{ $staff->name }}">
                            <h3 class="fw-bold mb-1 text-body-emphasis">{{ $staff->name }}</h3>
                            <p class="text-secondary mb-3 small">{{ $staff->position ?? 'Staff Member' }} • {{ $staff->department ?? 'General' }}</p>

                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <span class="role-badge role-badge-purple">{{ ucfirst($staff->position ?? $staff->role_name) }}</span>
                                @if($staff->status === 'active')
                                    <span class="status-badge status-badge-active">Active</span>
                                @else
                                    <span class="status-badge status-badge-inactive">Inactive</span>
                                @endif
                            </div>
                        </div>

                        <!-- Details Grid -->
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3 bg-body-tertiary">
                                        <div class="text-secondary small fw-medium mb-1">
                                            <i class="fa-regular fa-envelope text-primary me-2"></i> Email Address
                                        </div>
                                        <div class="fw-semibold text-body-emphasis" style="font-size: 0.9rem;">{{ $staff->email }}</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 rounded-3 bg-body-tertiary">
                                        <div class="text-secondary small fw-medium mb-1">
                                            <i class="fa-solid fa-phone text-success me-2"></i> Phone Number
                                        </div>
                                        <div class="fw-semibold text-body-emphasis" style="font-size: 0.9rem;">{{ $staff->phone ?? 'Not provided' }}</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 rounded-3 bg-body-tertiary">
                                        <div class="text-secondary small fw-medium mb-1">
                                            <i class="fa-solid fa-briefcase text-purple me-2" style="color: #8b5cf6;"></i> Department
                                        </div>
                                        <div class="fw-semibold text-body-emphasis" style="font-size: 0.9rem;">{{ $staff->department ?? 'N/A' }}</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="p-3 rounded-3 bg-body-tertiary">
                                        <div class="text-secondary small fw-medium mb-1">
                                            <i class="fa-solid fa-user-shield text-warning me-2"></i> System Role
                                        </div>
                                        <div class="fw-semibold text-body-emphasis text-capitalize" style="font-size: 0.9rem;">{{ $staff->role_name }}</div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="p-3 rounded-3 bg-body-tertiary d-flex align-items-center justify-content-between">
                                        <div>
                                            <div class="text-secondary small fw-medium mb-1">
                                                <i class="fa-regular fa-calendar text-secondary me-2"></i> Joined Date
                                            </div>
                                            <div class="fw-semibold text-body-emphasis" style="font-size: 0.9rem;">
                                                {{ $staff->joined_date ? \Carbon\Carbon::parse($staff->joined_date)->format('F d, Y') : $staff->created_at->format('F d, Y') }}
                                            </div>
                                        </div>
                                        <span class="badge bg-primary-subtle text-primary rounded-2 px-2.5 py-1" style="font-size: 0.75rem;">
                                            ID #{{ $staff->id }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Action Buttons -->
                            <div class="d-flex align-items-center justify-content-between mt-4 pt-3 border-top">
                                <a href="{{ route('staff.index') }}" class="btn btn-light border rounded-3 px-4 py-2 fw-medium text-secondary">
                                    <i class="fa-solid fa-arrow-left me-1.5 fs-xs"></i> Back to Staff List
                                </a>

                                <div class="d-flex align-items-center gap-2">
                                    @can('staff.edit')
                                        <a href="{{ route('staff.edit', $staff->id) }}" class="btn btn-purple-primary rounded-3 px-4 py-2 fw-semibold">
                                            <i class="fa-regular fa-pen-to-square me-1.5 fs-xs"></i> Edit Details
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        @include('partials.footer')
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
