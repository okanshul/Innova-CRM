<header
    class="header border-bottom py-2 px-2 px-sm-3 px-xl-4 d-flex align-items-center justify-content-between sticky-top">
    <div class="d-flex align-items-center gap-1 gap-sm-2 me-2 min-w-0">
        <button class="btn btn-link link-body-emphasis p-0 fs-4 text-decoration-none shadow-none me-1 flex-shrink-0" id="sidebarToggle" aria-label="Toggle Sidebar">
            <i class="fa-solid fa-bars fs-5"></i>
        </button>
        
        <!-- Mobile Logo (Visible on mobile screens) -->
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none d-md-none min-w-0">
            @if(setting('system_logo'))
                <img src="{{ asset(setting('system_logo')) }}" alt="{{ setting('app_name', 'InnovaCRM') }}" class="header-logo-img">
            @else
                <div class="logo-icon logo-icon-sm text-white rounded-3 d-flex align-items-center justify-content-center shadow-sm flex-shrink-0">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L2 7V17L12 22L22 17V7L12 Z" stroke="white" stroke-width="2" stroke-linejoin="round" />
                        <path d="M2 7L12 12L22 7" stroke="white" stroke-width="2" stroke-linejoin="round" />
                        <path d="M12 12V22" stroke="white" stroke-width="2" stroke-linejoin="round" />
                    </svg>
                </div>
                <span class="fw-bold fs-5 text-body-emphasis tracking-tight ms-1 header-logo-text d-none d-sm-inline-block">{{ setting('app_name', 'InnovaCRM') }}</span>
            @endif
        </a>

        <!-- Desktop Search Bar -->
        <div class="input-group d-none d-md-flex align-items-center bg-body-tertiary rounded-3 p-2 border ms-2 cursor-pointer header-search-box"
            id="headerSearchTrigger"
            data-bs-toggle="modal" data-bs-target="#globalSearchModal">
            <i class="fa-solid fa-magnifying-glass text-secondary mx-2"></i>
            <input type="text" class="form-control border-0 bg-transparent shadow-none p-0 flex-grow-1 header-search-input" style="background: transparent !important;"
                placeholder="Search contacts, deals, tasks..." readonly>
            <span
                class="badge text-secondary rounded-2 bg-transparent px-1 py-1 d-flex align-items-center justify-content-center search-kbd-badge">⌘ <span class="ps-1 search-kbd-key">K</span></span>
        </div>
    </div>

    <div class="d-flex align-items-center gap-1 gap-sm-2 flex-shrink-0">
        <!-- Mobile Search Button -->
        <button class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center d-md-none text-secondary shadow-none border icon-btn" 
                type="button" data-bs-toggle="modal" data-bs-target="#globalSearchModal" aria-label="Search">
            <i class="fa-solid fa-magnifying-glass fs-6"></i>
        </button>

        <!-- Theme Toggle Single Button (Cycle Light -> Dark -> Auto) -->
        <button class="btn rounded-circle d-flex align-items-center justify-content-center shadow-none border text-secondary p-0 transition-all hover-primary ms-1 icon-btn"
            id="theme-toggle-btn" type="button" aria-label="Toggle Theme"
            title="Theme preference">
            <i class="theme-toggle-icon fa-solid fa-sun fs-6 text-secondary"></i>
        </button>

        <!-- Notification Bell Dropdown -->
        <div class="dropdown ms-1">
            <button class="btn rounded-circle d-flex align-items-center justify-content-center shadow-none border text-secondary p-0 transition-all hover-primary position-relative icon-btn"
                id="notificationDropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications"
                title="Notifications">
                <i class="fa-regular fa-bell fs-6 text-secondary"></i>
                <span class="position-absolute badge rounded-pill bg-danger border border-2 border-body d-flex align-items-center justify-content-center notif-count-badge">5</span>
            </button>

            <div class="dropdown-menu dropdown-menu-end shadow-lg rounded-3 border-0 p-2 mt-2 notification-dropdown-menu" aria-labelledby="notificationDropdown">
                <!-- Header -->
                <div class="d-flex align-items-center justify-content-between p-2 mb-2 bg-body-tertiary rounded-3">
                    <strong class="fw-bold text-body-emphasis fs-6 px-1">Notifications</strong>
                    <a href="#" class="text-decoration-none fw-semibold d-flex align-items-center gap-2 text-primary notif-mark-read px-1">
                        Mark all as read
                    </a>
                </div>

                <div class="dropdown-divider my-1 opacity-25"></div>

                <!-- Notifications List -->
                <div class="d-flex flex-column notification-list-body gap-1 my-1">
                    <!-- Item 1: New contact added -->
                    <a href="#" class="dropdown-item rounded-3 p-2 d-flex align-items-center gap-3 notification-item">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 notif-icon-avatar notif-icon-indigo">
                            <i class="fa-solid fa-user-plus fs-6"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0 me-1">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <strong class="fw-bold text-body-emphasis text-truncate notif-title">New contact added</strong>
                                <small class="text-secondary flex-shrink-0 ms-2 notif-time">2m ago</small>
                            </div>
                            <p class="text-secondary mb-0 text-truncate notif-desc">John Smith has been added as a new contact.</p>
                        </div>
                        <span class="rounded-circle bg-primary flex-shrink-0 ms-1 notif-unread-dot"></span>
                    </a>

                    <!-- Item 2: Deal won -->
                    <a href="#" class="dropdown-item rounded-3 p-2 d-flex align-items-center gap-3 notification-item">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 notif-icon-avatar notif-icon-success">
                            <i class="fa-solid fa-trophy fs-6"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0 me-1">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <strong class="fw-bold text-body-emphasis text-truncate notif-title">Deal won</strong>
                                <small class="text-secondary flex-shrink-0 ms-2 notif-time">15m ago</small>
                            </div>
                            <p class="text-secondary mb-0 text-truncate notif-desc">"Website Redesign" deal has been won.</p>
                        </div>
                        <span class="rounded-circle bg-primary flex-shrink-0 ms-1 notif-unread-dot"></span>
                    </a>

                    <!-- Item 3: Task overdue -->
                    <a href="#" class="dropdown-item rounded-3 p-2 d-flex align-items-center gap-3 notification-item">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 notif-icon-avatar notif-icon-danger">
                            <i class="fa-regular fa-calendar-xmark fs-6"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0 me-1">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <strong class="fw-bold text-body-emphasis text-truncate notif-title">Task overdue</strong>
                                <small class="text-secondary flex-shrink-0 ms-2 notif-time">1h ago</small>
                            </div>
                            <p class="text-secondary mb-0 text-truncate notif-desc">"Follow up with Laura" is overdue.</p>
                        </div>
                        <span class="rounded-circle bg-primary flex-shrink-0 ms-1 notif-unread-dot"></span>
                    </a>

                    <!-- Item 4: New message -->
                    <a href="#" class="dropdown-item rounded-3 p-2 d-flex align-items-center gap-3 notification-item">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 notif-icon-avatar notif-icon-info">
                            <i class="fa-regular fa-envelope fs-6"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0 me-1">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <strong class="fw-bold text-body-emphasis text-truncate notif-title">New message</strong>
                                <small class="text-secondary flex-shrink-0 ms-2 notif-time">3h ago</small>
                            </div>
                            <p class="text-secondary mb-0 text-truncate notif-desc">You have a new message from Michael.</p>
                        </div>
                    </a>

                    <!-- Item 5: Report generated -->
                    <a href="#" class="dropdown-item rounded-3 p-2 d-flex align-items-center gap-3 notification-item">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 notif-icon-avatar notif-icon-warning">
                            <i class="fa-solid fa-chart-column fs-6"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0 me-1">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <strong class="fw-bold text-body-emphasis text-truncate notif-title">Report generated</strong>
                                <small class="text-secondary flex-shrink-0 ms-2 notif-time">5h ago</small>
                            </div>
                            <p class="text-secondary mb-0 text-truncate notif-desc">"Monthly Sales Report" is ready to view.</p>
                        </div>
                    </a>
                </div>

                <div class="dropdown-divider my-1 opacity-25"></div>

                <!-- Footer -->
                <a href="#" class="dropdown-item rounded-3 py-2 text-center text-primary fw-semibold notif-footer-link">
                    View all notifications
                </a>
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
                class="dropdown-toggle d-flex align-items-center text-body-emphasis text-decoration-none shadow-none p-0 rounded user-dropdown-toggle"
                id="dropdownUserHeader" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                <div class="position-relative">
                    <img src="{{ $userAvatar }}" onerror="this.onerror=null;this.src='{{ $uiAvatar }}';" alt="{{ auth()->user()->name }}" width="36" height="36"
                        class="rounded-circle object-fit-cover shadow-sm border border-2 border-white avatar-sm">
                </div>
                <div class="d-none d-md-flex align-items-center gap-2 text-start ms-2 me-1">
                    <div class="d-flex flex-column lh-1">
                        <strong class="fs-6 fw-bold">{{ auth()->user()->name }}</strong>
                        <span class="text-secondary text-capitalize user-role-text">{{ auth()->user()->getRoleNames()->first() ?? 'Staff' }}</span>
                    </div>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end shadow-lg rounded-3 border-0 p-2 mt-2 user-dropdown-menu"
                aria-labelledby="dropdownUserHeader">
                <div class="d-flex align-items-center gap-3 p-2 mb-2 bg-body-tertiary rounded-3">
                    <img src="{{ $userAvatar }}" onerror="this.onerror=null;this.src='{{ $uiAvatar }}';" alt="{{ auth()->user()->name }}" width="38" height="38"
                        class="rounded-circle object-fit-cover avatar-md">
                    <div class="d-flex flex-column lh-sm text-truncate">
                        <strong class="fw-bold text-body-emphasis user-dropdown-name">{{ auth()->user()->name }}</strong>
                        <span class="text-secondary text-truncate user-dropdown-email">{{ auth()->user()->email }}</span>
                        <span class="badge bg-primary-subtle text-primary mt-1 align-self-start text-capitalize user-role-badge">{{ auth()->user()->getRoleNames()->first() ?? 'Staff' }}</span>
                    </div>
                </div>
                <div class="dropdown-divider my-1 opacity-25"></div>
                <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="{{ route('profile.index') }}">
                    <i class="fa-regular fa-user text-secondary transition-colors dropdown-item-icon"></i>
                    <span class="fw-medium">My Profile</span>
                </a>
                <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2" href="{{ route('settings.index') }}">
                    <i class="fa-solid fa-gear text-secondary transition-colors dropdown-item-icon"></i>
                    <span class="fw-medium">Settings</span>
                </a>
                <div class="dropdown-divider my-1 opacity-25"></div>
                
                <!-- Logout Form -->
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2 text-danger w-100 text-start bg-transparent border-0">
                        <i class="fa-solid fa-right-from-bracket dropdown-item-icon"></i>
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
            placeholder="Search contacts, deals, tasks..." readonly>
    </div>
</div>
