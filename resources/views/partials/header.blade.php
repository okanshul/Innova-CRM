<header
    class="header border-bottom py-2 px-3 px-xl-4 d-flex align-items-center justify-content-between sticky-top"
    style="min-height: 64px; backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); z-index: 1030;">
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-link link-body-emphasis p-0 fs-4 text-decoration-none shadow-none me-1" id="sidebarToggle">
            <i class="fa-solid fa-bars fs-5"></i>
        </button>
        
        <!-- Mobile Logo (Visible on mobile screens) -->
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none d-md-none">
            @if(setting('system_logo'))
                <img src="{{ asset(setting('system_logo')) }}" alt="{{ setting('app_name', 'InnovaCRM') }}" style="max-height: 32px; max-width: 120px; object-fit: contain;">
            @else
                <div class="logo-icon text-white rounded-3 d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 32px; height: 32px; background: linear-gradient(135deg, #6366f1, #a855f7) !important;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L2 7V17L12 22L22 17V7L12 Z" stroke="white" stroke-width="2" stroke-linejoin="round" />
                        <path d="M2 7L12 12L22 7" stroke="white" stroke-width="2" stroke-linejoin="round" />
                        <path d="M12 12V22" stroke="white" stroke-width="2" stroke-linejoin="round" />
                    </svg>
                </div>
                <span class="fw-bold fs-5 text-body-emphasis tracking-tight ms-1" style="letter-spacing: -0.03em;">{{ setting('app_name', 'InnovaCRM') }}</span>
            @endif
        </a>

        <!-- Desktop Search Bar -->
        <div class="input-group d-none d-md-flex align-items-center bg-body-tertiary rounded-3 p-2 border ms-2 cursor-pointer"
            id="headerSearchTrigger"
            data-bs-toggle="modal" data-bs-target="#globalSearchModal"
            style="width: 380px; cursor: pointer;">
            <i class="fa-solid fa-magnifying-glass text-secondary mx-2"></i>
            <input type="text" class="form-control border-0 bg-transparent shadow-none p-0 flex-grow-1 header-search-input"
                style="font-size: 0.875rem; border: none !important; background: transparent !important; box-shadow: none !important; padding: 0 !important; outline: none !important; cursor: pointer;" 
                placeholder="Search contacts, deals, tasks..." readonly>
            <span
                class="badge text-secondary rounded-2 bg-transparent px-1 py-1 d-flex align-items-center justify-content-center"
                style="font-size: 0.6rem; min-width:24px;">⌘ <span class="ps-1"
                    style="font-size: 13px">K</span></span>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2">
        <!-- Mobile Search Button -->
        <button class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center d-md-none text-secondary shadow-none border" 
                style="width: 36px; height: 36px; background-color: var(--bs-tertiary-bg);" 
                type="button" data-bs-toggle="modal" data-bs-target="#globalSearchModal" aria-label="Search">
            <i class="fa-solid fa-magnifying-glass fs-6"></i>
        </button>

        <!-- Theme Toggle Single Button (Cycle Light -> Dark -> Auto) -->
        <button class="btn btn-body-tertiary bg-body-tertiary rounded-circle d-flex align-items-center justify-content-center shadow-none border text-secondary p-0 transition-all hover-primary ms-1"
            id="theme-toggle-btn" type="button" aria-label="Toggle Theme"
            style="width: 36px; height: 36px; background-color: var(--bs-tertiary-bg);"
            title="Theme preference">
            <i class="theme-toggle-icon fa-solid fa-sun fs-6 text-secondary"></i>
        </button>

        <!-- Notification Bell Dropdown -->
        <div class="dropdown ms-1">
            <button class="btn btn-body-tertiary bg-body-tertiary rounded-circle d-flex align-items-center justify-content-center shadow-none border text-secondary p-0 transition-all hover-primary position-relative"
                id="notificationDropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications"
                style="width: 36px; height: 36px; background-color: var(--bs-tertiary-bg);"
                title="Notifications">
                <i class="fa-regular fa-bell fs-6 text-secondary"></i>
                <span class="position-absolute badge rounded-pill bg-danger border border-2 border-body d-flex align-items-center justify-content-center"
                    style="top: -6px; right: -7px; min-width: 18px; height: 18px; padding: 0 5px; font-size: 0.575rem; font-weight: 700; line-height: 1; box-shadow: 0 2px 4px rgba(0,0,0,0.15);">5</span>
            </button>

            <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-0 mt-2 rounded-4 overflow-hidden notification-dropdown-menu" aria-labelledby="notificationDropdown" style="width: 380px; max-width: calc(100vw - 2rem);">
                <!-- Header -->
                <div class="d-flex align-items-center justify-content-between px-3 py-3 border-bottom">
                    <h6 class="fw-bold m-0 text-body-emphasis fs-6">Notifications</h6>
                    <a href="#" class="text-decoration-none fw-semibold d-flex align-items-center gap-1.5 text-primary" style="font-size: 0.8125rem;">
                        Mark all as read
                    </a>
                </div>

                <!-- Notifications List -->
                <div class="d-flex flex-column notification-list-body" style="max-height: 380px; overflow-y: auto;">
                    <!-- Item 1: New contact added -->
                    <a href="#" class="text-decoration-none px-3 py-3 border-bottom d-flex align-items-center gap-3 notification-item bg-body-tertiary">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                            style="width: 42px; height: 42px; background: rgba(99, 102, 241, 0.12); color: #6366f1;">
                            <i class="fa-solid fa-user-plus fs-6"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0 me-1">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="fw-bold text-body-emphasis text-truncate" style="font-size: 0.875rem;">New contact added</span>
                                <small class="text-secondary flex-shrink-0 ms-2" style="font-size: 0.75rem;">2m ago</small>
                            </div>
                            <p class="text-secondary mb-0 text-truncate" style="font-size: 0.8125rem; line-height: 1.3;">John Smith has been added as a new contact.</p>
                        </div>
                        <span class="rounded-circle bg-primary flex-shrink-0 ms-1" style="width: 8px; height: 8px;"></span>
                    </a>

                    <!-- Item 2: Deal won -->
                    <a href="#" class="text-decoration-none px-3 py-3 border-bottom d-flex align-items-center gap-3 notification-item">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                            style="width: 42px; height: 42px; background: rgba(34, 197, 94, 0.12); color: #16a34a;">
                            <i class="fa-solid fa-trophy fs-6"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0 me-1">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="fw-bold text-body-emphasis text-truncate" style="font-size: 0.875rem;">Deal won</span>
                                <small class="text-secondary flex-shrink-0 ms-2" style="font-size: 0.75rem;">15m ago</small>
                            </div>
                            <p class="text-secondary mb-0 text-truncate" style="font-size: 0.8125rem; line-height: 1.3;">"Website Redesign" deal has been won.</p>
                        </div>
                        <span class="rounded-circle bg-primary flex-shrink-0 ms-1" style="width: 8px; height: 8px;"></span>
                    </a>

                    <!-- Item 3: Task overdue -->
                    <a href="#" class="text-decoration-none px-3 py-3 border-bottom d-flex align-items-center gap-3 notification-item">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                            style="width: 42px; height: 42px; background: rgba(239, 68, 68, 0.12); color: #dc2626;">
                            <i class="fa-regular fa-calendar-xmark fs-6"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0 me-1">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="fw-bold text-body-emphasis text-truncate" style="font-size: 0.875rem;">Task overdue</span>
                                <small class="text-secondary flex-shrink-0 ms-2" style="font-size: 0.75rem;">1h ago</small>
                            </div>
                            <p class="text-secondary mb-0 text-truncate" style="font-size: 0.8125rem; line-height: 1.3;">"Follow up with Laura" is overdue.</p>
                        </div>
                        <span class="rounded-circle bg-primary flex-shrink-0 ms-1" style="width: 8px; height: 8px;"></span>
                    </a>

                    <!-- Item 4: New message -->
                    <a href="#" class="text-decoration-none px-3 py-3 border-bottom d-flex align-items-center gap-3 notification-item">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                            style="width: 42px; height: 42px; background: rgba(14, 165, 233, 0.12); color: #0284c7;">
                            <i class="fa-regular fa-envelope fs-6"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0 me-1">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="fw-bold text-body-emphasis text-truncate" style="font-size: 0.875rem;">New message</span>
                                <small class="text-secondary flex-shrink-0 ms-2" style="font-size: 0.75rem;">3h ago</small>
                            </div>
                            <p class="text-secondary mb-0 text-truncate" style="font-size: 0.8125rem; line-height: 1.3;">You have a new message from Michael.</p>
                        </div>
                    </a>

                    <!-- Item 5: Report generated -->
                    <a href="#" class="text-decoration-none px-3 py-3 d-flex align-items-center gap-3 notification-item">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                            style="width: 42px; height: 42px; background: rgba(245, 158, 11, 0.12); color: #d97706;">
                            <i class="fa-solid fa-chart-column fs-6"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0 me-1">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="fw-bold text-body-emphasis text-truncate" style="font-size: 0.875rem;">Report generated</span>
                                <small class="text-secondary flex-shrink-0 ms-2" style="font-size: 0.75rem;">5h ago</small>
                            </div>
                            <p class="text-secondary mb-0 text-truncate" style="font-size: 0.8125rem; line-height: 1.3;">"Monthly Sales Report" is ready to view.</p>
                        </div>
                    </a>
                </div>

                <!-- Footer -->
                <div class="p-3 text-center border-top">
                    <a href="#" class="text-decoration-none fw-semibold text-primary" style="font-size: 0.875rem;">
                        View all notifications
                    </a>
                </div>
            </div>
        </div>

        <!-- User Dropdown Profile -->
        <div class="dropdown ms-1">
            @php
                $userAvatar = (auth()->user()->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists(auth()->user()->avatar))
                    ? asset('storage/' . auth()->user()->avatar)
                    : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=6366F1&color=fff';
                $uiAvatar = 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=6366F1&color=fff';
            @endphp
            <a href="#"
                class="dropdown-toggle d-flex align-items-center text-body-emphasis text-decoration-none shadow-none p-0 rounded"
                id="dropdownUserHeader" data-bs-toggle="dropdown" aria-expanded="false" role="button"
                style="outline: none; box-shadow: none;">
                <div class="position-relative">
                    <img src="{{ $userAvatar }}" onerror="this.onerror=null;this.src='{{ $uiAvatar }}';" alt="{{ auth()->user()->name }}" width="36" height="36"
                        class="rounded-circle object-fit-cover shadow-sm border border-2 border-white">
                </div>
                <div class="d-none d-md-flex align-items-center gap-2 text-start ms-2 me-1">
                    <div class="d-flex flex-column lh-1">
                        <strong class="fs-6 fw-bold">{{ auth()->user()->name }}</strong>
                        <span class="text-secondary text-capitalize" style="font-size: 0.7rem;">{{ auth()->user()->getRoleNames()->first() ?? 'Staff' }}</span>
                    </div>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end shadow-lg rounded-4 border-0 p-2 mt-2"
                aria-labelledby="dropdownUserHeader" style="min-width: 230px;">
                <div class="d-flex align-items-center gap-3 p-2 mb-2 bg-body-tertiary rounded-3">
                    <img src="{{ $userAvatar }}" onerror="this.onerror=null;this.src='{{ $uiAvatar }}';" alt="{{ auth()->user()->name }}" width="38" height="38"
                        class="rounded-circle object-fit-cover">
                    <div class="d-flex flex-column lh-sm text-truncate">
                        <strong class="fw-bold text-body-emphasis" style="font-size: 0.85rem;">{{ auth()->user()->name }}</strong>
                        <span class="text-secondary text-truncate" style="font-size: 0.725rem;">{{ auth()->user()->email }}</span>
                        <span class="badge bg-primary-subtle text-primary mt-1 align-self-start text-capitalize" style="font-size: 0.6rem;">{{ auth()->user()->getRoleNames()->first() ?? 'Staff' }}</span>
                    </div>
                </div>
                <div class="dropdown-divider my-1 opacity-25"></div>
                <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="{{ route('profile.index') }}">
                    <i class="fa-regular fa-user text-secondary transition-colors" style="width: 18px;"></i>
                    <span class="fw-medium">My Profile</span>
                </a>
                <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="{{ route('settings.index') }}">
                    <i class="fa-solid fa-gear text-secondary transition-colors" style="width: 18px;"></i>
                    <span class="fw-medium">Settings</span>
                </a>
                <div class="dropdown-divider my-1 opacity-25"></div>
                
                <!-- Logout Form -->
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2 text-danger w-100 text-start bg-transparent border-0">
                        <i class="fa-solid fa-right-from-bracket" style="width: 18px;"></i>
                        <span class="fw-medium">Sign out</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Search Bar Collapse -->
<div class="collapse d-md-none bg-body border-bottom p-2 px-3 sticky-top z-2 shadow-sm" id="mobileSearchCollapse">
    <div class="input-group align-items-center bg-body-tertiary rounded-3 px-2 py-1 border cursor-pointer" data-bs-toggle="modal" data-bs-target="#globalSearchModal">
        <i class="fa-solid fa-magnifying-glass text-secondary mx-2"></i>
        <input type="text" class="form-control border-0 bg-transparent shadow-none p-0 flex-grow-1 header-search-input"
            style="font-size: 0.85rem; cursor: pointer;" placeholder="Search contacts, deals, tasks..." readonly>
    </div>
</div>

