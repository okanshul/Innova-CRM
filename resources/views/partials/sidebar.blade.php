<aside id="sidebar" class="offcanvas-lg offcanvas-start d-flex flex-column p-3">
    <div class="d-flex align-items-center justify-content-between mb-3 px-2 pt-1">
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-white text-decoration-none sidebar-brand gap-2">
            @if(setting('favicon'))
                <img src="{{ asset(setting('favicon')) }}" alt="{{ setting('app_name', 'InnovaCRM') }}" class="sidebar-favicon-img rounded-2">
            @endif

            @if(setting('system_logo'))
                <img src="{{ asset(setting('system_logo')) }}" alt="{{ setting('app_name', 'InnovaCRM') }}" class="sidebar-logo-img sidebar-logo-full rounded-2" style="max-height: 38px; max-width: 160px; object-fit: contain;">
            @else
                <div class="d-flex align-items-center gap-2 sidebar-logo-full">
                    <div class="brand-icon rounded-3 d-flex align-items-center justify-content-center text-white shadow-sm flex-shrink-0"
                        style="width: 38px; height: 38px; min-width: 38px; background: linear-gradient(135deg, #6366f1, #a855f7) !important;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L2 7V17L12 22L22 17V7L12 Z" stroke="white" stroke-width="2" stroke-linejoin="round" />
                            <path d="M2 7L12 12L22 7" stroke="white" stroke-width="2" stroke-linejoin="round" />
                            <path d="M12 12V22" stroke="white" stroke-width="2" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <span class="fs-4 fw-bold sidebar-text ms-1" style="letter-spacing: -0.02em;">{{ setting('app_name', 'InnovaCRM') }}</span>
                </div>
            @endif
        </a>
        <button type="button" class="btn-close btn-close-white d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#sidebar" aria-label="Close"></button>
    </div>

    <ul class="nav nav-pills flex-column mb-auto gap-1">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" aria-current="page" data-bs-toggle="tooltip" data-bs-title="Dashboard" data-bs-placement="right">
                <i class="fa-solid fa-house"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>
        </li>
        @can('contacts.view')
        <li>
            <a href="{{ route('contacts.index') }}" class="sidebar-link {{ request()->routeIs('contacts.*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-title="Contacts" data-bs-placement="right">
                <i class="fa-regular fa-address-book"></i>
                <span class="sidebar-text">Contacts</span>
            </a>
        </li>
        @endcan
        @can('deals.view')
        <li>
            <a href="{{ route('deals.index') }}" class="sidebar-link {{ request()->routeIs('deals.*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-title="Deals" data-bs-placement="right">
                <i class="fa-solid fa-gem"></i>
                <span class="sidebar-text">Deals</span>
            </a>
        </li>
        @endcan
        @can('pipeline.view')
        <li>
            <a href="{{ route('pipelines.index') }}" class="sidebar-link {{ request()->routeIs('pipelines.*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-title="Pipeline" data-bs-placement="right">
                <i class="fa-solid fa-bars-staggered"></i>
                <span class="sidebar-text">Pipeline</span>
            </a>
        </li>
        @endcan
        @can('reports.view')
        <li>
            <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-title="Reports" data-bs-placement="right">
                <i class="fa-solid fa-chart-column"></i>
                <span class="sidebar-text">Reports</span>
            </a>
        </li>
        @endcan
        @can('tasks.view')
        <li>
            <a href="{{ route('tasks.index') }}" class="sidebar-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-title="Tasks" data-bs-placement="right">
                <i class="fa-regular fa-square-check"></i>
                <span class="sidebar-text">Tasks</span>
            </a>
        </li>
        @endcan
        @can('calendar.view')
        <li>
            <a href="{{ route('calendar.index') }}" class="sidebar-link {{ request()->routeIs('calendar.*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-title="Calendar" data-bs-placement="right">
                <i class="fa-regular fa-calendar"></i>
                <span class="sidebar-text">Calendar</span>
            </a>
        </li>
        @endcan
        @can('meetings.view')
        <li>
            <a href="{{ route('meetings.index') }}" class="sidebar-link {{ request()->routeIs('meetings.*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-title="Meetings" data-bs-placement="right">
                <i class="fa-solid fa-video"></i>
                <span class="sidebar-text">Meetings</span>
            </a>
        </li>
        @endcan
        @can('mail.view')
        <li>
            <a href="{{ route('mail.index') }}" class="sidebar-link {{ request()->routeIs('mail.*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-title="Mail" data-bs-placement="right">
                <i class="fa-regular fa-envelope"></i>
                <span class="sidebar-text">Mail</span>
            </a>
        </li>
        @endcan
        @can('staff.view')
        <li>
            <a href="{{ route('staff.index') }}" class="sidebar-link {{ request()->routeIs('staff.*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-title="Staff" data-bs-placement="right">
                <i class="fa-solid fa-user-group"></i>
                <span class="sidebar-text">Staff</span>
            </a>
        </li>
        @endcan
        @can('roles.view')
        <li>
            <a href="{{ route('roles.index') }}" class="sidebar-link {{ request()->routeIs('roles.*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-title="Roles & Permissions" data-bs-placement="right">
                <i class="fa-solid fa-user-shield"></i>
                <span class="sidebar-text">Roles & Permissions</span>
            </a>
        </li>
        @endcan
        @can('settings.view')
        <li>
            <a href="{{ route('settings.index') }}" class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}" data-bs-toggle="tooltip" data-bs-title="Settings" data-bs-placement="right">
                <i class="fa-solid fa-gear"></i>
                <span class="sidebar-text">Settings</span>
            </a>
        </li>
        @endcan
    </ul>

    <!-- Upgrade Card -->
    <div class="mt-auto sidebar-footer pt-3">
        <div class="card border-0 rounded-3 mb-3 p-3 text-white upgrade-card" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.08) !important;">
            <div class="card-body p-0 text-start">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fa-solid fa-wand-magic-sparkles text-primary fs-5"></i>
                    <h6 class="fw-bold mb-0 text-white" style="font-size: 0.9rem;">Upgrade Plan</h6>
                </div>
                <p class="text-secondary mb-3" style="font-size: 0.75rem; color: #9ca3af !important; line-height: 1.4;">Unlock more features and advanced reports.</p>
                <button class="btn btn-primary btn-sm w-100 fw-semibold rounded-3 py-2 border-0 shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #6366f1, #4f46e5); font-size: 0.8rem;">
                    Upgrade Now <i class="fa-solid fa-arrow-right fs-xs"></i>
                </button>
            </div>
        </div>

        <!-- User Profile -->
        <div class="dropdown dropup">
            @php
                $hasSidebarAvatar = auth()->user()->avatar && (file_exists(public_path(auth()->user()->avatar)) || \Illuminate\Support\Facades\Storage::disk('public')->exists(auth()->user()->avatar));
                $sidebarAvatar = $hasSidebarAvatar
                    ? (file_exists(public_path(auth()->user()->avatar)) ? asset(auth()->user()->avatar) : asset('storage/' . auth()->user()->avatar))
                    : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=6366F1&color=fff';
                $sidebarUiAvatar = 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=6366F1&color=fff';
            @endphp
            <a href="javascript:void(0)" class="dropdown-toggle d-flex align-items-center text-white text-decoration-none px-2 py-1 rounded-3 hover-bg sidebar-user"
                id="dropdownUser1" data-bs-toggle="dropdown" data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false" role="button">
                <div class="position-relative d-flex justify-content-center align-items-center">
                    <img src="{{ $sidebarAvatar }}" onerror="this.onerror=null;this.src='{{ $sidebarUiAvatar }}';" alt="{{ auth()->user()->name }}"
                        width="36" height="36" class="rounded-circle avatar object-fit-cover">
                </div>
                <div class="d-flex flex-column lh-sm sidebar-text ms-2 text-start me-auto">
                    <strong class="fs-6 fw-semibold text-white" style="font-size: 0.875rem;">{{ auth()->user()->name }}</strong>
                    <span class="text-secondary text-capitalize" style="font-size: 0.75rem; color: #9ca3af !important;">{{ auth()->user()->getRoleNames()->first() ?? 'Administrator' }}</span>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-dark text-small shadow-lg rounded-3 border-0 p-2 mt-2"
                aria-labelledby="dropdownUser1" style="min-width: 220px; background-color: #1a1a30; z-index: 1090;">
                <div class="d-flex align-items-center gap-3 p-2 mb-2 bg-white bg-opacity-10 rounded-3">
                    <img src="{{ $sidebarAvatar }}" onerror="this.onerror=null;this.src='{{ $sidebarUiAvatar }}';" alt="{{ auth()->user()->name }}"
                        width="36" height="36" class="rounded-circle avatar object-fit-cover">
                    <div class="d-flex flex-column lh-sm text-truncate">
                        <strong class="fw-bold text-white" style="font-size: 0.85rem;">{{ auth()->user()->name }}</strong>
                        <span class="text-white-50 text-truncate" style="font-size: 0.725rem;">{{ auth()->user()->email }}</span>
                    </div>
                </div>
                <div class="dropdown-divider my-1 opacity-25"></div>
                <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2 text-white-50" href="{{ route('profile.index') }}">
                    <i class="fa-regular fa-user text-secondary transition-colors" style="width: 18px;"></i>
                    <span class="text-white">Profile</span>
                </a>
                <a class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2 text-white-50" href="{{ route('settings.index') }}">
                    <i class="fa-solid fa-gear text-secondary transition-colors" style="width: 18px;"></i>
                    <span class="text-white">Settings</span>
                </a>
                <div class="dropdown-divider my-1 opacity-25"></div>
                
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
</aside>
