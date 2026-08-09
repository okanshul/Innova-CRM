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
            padding: 0.35rem 0.85rem;
            border-radius: 20px;
            font-size: 0.775rem;
            display: inline-flex;
            align-items: center;
        }
        .status-badge-active { background-color: #dcfce7; color: #15803d; }
        .status-badge-inactive { background-color: #ffe4e6; color: #e11d48; }

        /* Profile Cover & Header Enhancements */
        .profile-cover-banner {
            height: 110px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #4f46e5 100%);
            position: relative;
            overflow: hidden;
        }

        .profile-cover-banner::before {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 220px;
            height: 220px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.18) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
        }

        .profile-cover-banner::after {
            content: '';
            position: absolute;
            bottom: -60px;
            left: -40px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
        }

        .profile-avatar-wrapper {
            margin-top: -52px;
            position: relative;
            display: inline-block;
        }

        .profile-avatar-img {
            width: 104px;
            height: 104px;
            border-radius: 50%;
            border: 4px solid var(--bs-card-bg, #ffffff);
            box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.35);
            object-fit: cover;
            background-color: var(--bs-card-bg, #ffffff);
        }

        .info-card {
            background-color: var(--bs-tertiary-bg, #f8fafc);
            border: 1px solid var(--bs-border-color-translucent, #e2e8f0);
            border-radius: 14px;
            padding: 1.15rem 1.25rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.1);
            border-color: rgba(99, 102, 241, 0.3);
            background-color: var(--bs-card-bg, #ffffff);
        }

        .icon-box {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.1rem;
        }

        .icon-box-indigo { background: rgba(99, 102, 241, 0.12); color: #6366f1; }
        .icon-box-emerald { background: rgba(16, 185, 129, 0.12); color: #10b981; }
        .icon-box-purple { background: rgba(168, 85, 247, 0.12); color: #a855f7; }
        .icon-box-amber { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }
        .icon-box-sky { background: rgba(14, 165, 233, 0.12); color: #0ea5e9; }
        .icon-box-rose { background: rgba(244, 63, 94, 0.12); color: #f43f5e; }

        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }

        .pulse-dot.active {
            background-color: #10b981;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-green 2s infinite;
        }

        .pulse-dot.inactive {
            background-color: #f43f5e;
        }

        @keyframes pulse-green {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }
            70% {
                box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
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
                        <!-- Card Header -->
                        <div class="card-header border-bottom bg-body px-4 py-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center"
                                     style="background: #f3e8ff; color: #7e22ce; width: 44px; height: 44px;">
                                    <i class="fa-solid fa-user-gear fs-5"></i>
                                </div>
                                <div>
                                    <h4 class="fw-bold mb-0 text-body-emphasis">Staff Details</h4>
                                    <p class="text-secondary small mb-0">View profile and details for {{ $staff->name }}.</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                @can('staff.edit')
                                    <a href="{{ route('staff.edit', $staff->id) }}" class="btn btn-purple-primary rounded-3 px-3 py-1.5 fs-sm fw-semibold d-inline-flex align-items-center gap-1.5">
                                        <i class="fa-regular fa-pen-to-square me-1"></i>Edit
                                    </a>
                                @endcan
                                <a href="{{ route('staff.index') }}" class="btn btn-light border rounded-3 px-3 py-1.5 fs-sm text-secondary fw-medium d-inline-flex align-items-center gap-1.5">
                                    <i class="fa-solid fa-angle-left pe-1"></i> Back
                                </a>                                
                            </div>
                        </div>

                        <!-- Profile Banner & Hero Header -->
                        <div class="position-relative">
                            <div class="profile-cover-banner"></div>
                            <div class="px-4 pb-4 text-center border-bottom bg-body position-relative">
                                <div class="profile-avatar-wrapper mb-3">
                                    @php
                                        $avatarSrc = $staff->avatar
                                            ? asset('storage/' . $staff->avatar)
                                            : 'https://ui-avatars.com/api/?name=' . urlencode($staff->name) . '&background=6366F1&color=fff';
                                    @endphp
                                    <img src="{{ $avatarSrc }}" class="profile-avatar-img" alt="{{ $staff->name }}">
                                </div>
                                <h3 class="fw-bold mb-1 text-body-emphasis">{{ $staff->name }}</h3>
                                <p class="text-secondary mb-3 small fw-medium d-flex align-items-center justify-content-center gap-2">
                                    <span><i class="fa-solid fa-briefcase text-primary me-1 opacity-75"></i>{{ $staff->position ?? 'Staff Member' }}</span>
                                    <span class="text-muted">•</span>
                                    <span><i class="fa-solid fa-building text-purple me-1 opacity-75" style="color: #8b5cf6;"></i>{{ $staff->department ?? 'General' }}</span>
                                </p>

                                <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap">
                                    <span class="role-badge role-badge-purple shadow-sm">
                                        <i class="fa-solid fa-user-shield me-1"></i>{{ ucfirst($staff->position ?? $staff->role_name) }}
                                    </span>
                                    @if($staff->status === 'active')
                                        <span class="status-badge status-badge-active shadow-sm">
                                            <span class="pulse-dot active"></span> Active Status
                                        </span>
                                    @else
                                        <span class="status-badge status-badge-inactive shadow-sm">
                                            <span class="pulse-dot inactive"></span> Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Details Grid -->
                        <div class="card-body p-4 p-md-4">
                            <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
                                <h6 class="fw-bold mb-0 text-body-emphasis d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-id-badge text-primary"></i> Account Information
                                </h6>
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1 fw-semibold fs-xs">
                                    System ID #{{ $staff->id }}
                                </span>
                            </div>

                            <div class="row g-3 g-md-4">
                                <!-- Email Address -->
                                <div class="col-md-6">
                                    <div class="info-card d-flex align-items-center gap-3">
                                        <div class="icon-box icon-box-indigo">
                                            <i class="fa-solid fa-envelope"></i>
                                        </div>
                                        <div class="overflow-hidden">
                                            <div class="text-secondary text-uppercase fw-semibold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Email Address</div>
                                            <div class="fw-bold text-body-emphasis text-truncate" style="font-size: 0.925rem;">{{ $staff->email }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Phone Number -->
                                <div class="col-md-6">
                                    <div class="info-card d-flex align-items-center gap-3">
                                        <div class="icon-box icon-box-emerald">
                                            <i class="fa-solid fa-phone"></i>
                                        </div>
                                        <div class="overflow-hidden">
                                            <div class="text-secondary text-uppercase fw-semibold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Phone Number</div>
                                            <div class="fw-bold text-body-emphasis text-truncate" style="font-size: 0.925rem;">{{ $staff->phone ?? 'Not provided' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Department -->
                                <div class="col-md-6">
                                    <div class="info-card d-flex align-items-center gap-3">
                                        <div class="icon-box icon-box-purple">
                                            <i class="fa-solid fa-building-user"></i>
                                        </div>
                                        <div class="overflow-hidden">
                                            <div class="text-secondary text-uppercase fw-semibold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Department</div>
                                            <div class="fw-bold text-body-emphasis text-truncate" style="font-size: 0.925rem;">{{ $staff->department ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- System Role -->
                                <div class="col-md-6">
                                    <div class="info-card d-flex align-items-center gap-3">
                                        <div class="icon-box icon-box-amber">
                                            <i class="fa-solid fa-shield-halved"></i>
                                        </div>
                                        <div class="overflow-hidden">
                                            <div class="text-secondary text-uppercase fw-semibold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">System Role</div>
                                            <div class="fw-bold text-body-emphasis text-capitalize text-truncate" style="font-size: 0.925rem;">{{ $staff->role_name }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Joined Date -->
                                <div class="col-md-6">
                                    <div class="info-card d-flex align-items-center gap-3">
                                        <div class="icon-box icon-box-sky">
                                            <i class="fa-regular fa-calendar-check"></i>
                                        </div>
                                        <div class="overflow-hidden">
                                            <div class="text-secondary text-uppercase fw-semibold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Joined Date</div>
                                            <div class="fw-bold text-body-emphasis text-truncate" style="font-size: 0.925rem;">
                                                {{ $staff->joined_date ? \Carbon\Carbon::parse($staff->joined_date)->format('F d, Y') : $staff->created_at->format('F d, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Staff Record ID -->
                                <div class="col-md-6">
                                    <div class="info-card d-flex align-items-center gap-3">
                                        <div class="icon-box icon-box-rose">
                                            <i class="fa-solid fa-id-card"></i>
                                        </div>
                                        <div class="overflow-hidden">
                                            <div class="text-secondary text-uppercase fw-semibold mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Staff Record ID</div>
                                            <div class="fw-bold text-body-emphasis text-truncate" style="font-size: 0.925rem;">ID #{{ $staff->id }}</div>
                                        </div>
                                    </div>
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
